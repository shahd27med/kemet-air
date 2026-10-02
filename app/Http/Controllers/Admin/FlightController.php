<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function index(): View
    {
        $flights = Flight::with(['airline', 'departureAirport.city', 'arrivalAirport.city'])
            ->orderByDesc('departure_time')
            ->paginate(15);

        return view('admin.flights.index', compact('flights'));
    }

    public function create(): View
    {
        return view('admin.flights.form', [
            'flight' => new Flight(['status' => 'scheduled']),
            'airlines' => Airline::orderBy('name')->get(),
            'airports' => Airport::with('city')->get()->sortBy(fn (Airport $a) => $a->city->name)->values(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFlight($request);

        $flight = Flight::create(array_merge($validated, [
            'available_seats' => $validated['total_seats'],
        ]));

        $flight->generateSeatMap((int) $validated['total_seats']);

        return redirect()->route('admin.flights.index')
            ->with('success', __('admin.flight_created'));
    }

    public function edit(Flight $flight): View
    {
        return view('admin.flights.form', [
            'flight' => $flight,
            'airlines' => Airline::orderBy('name')->get(),
            'airports' => Airport::with('city')->get()->sortBy(fn (Airport $a) => $a->city->name)->values(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Flight $flight): RedirectResponse
    {
        $validated = $this->validateFlight($request, $flight);

        // Total/available seats are intentionally not editable here — the
        // seat map is already generated and tied to specific seat rows.
        unset($validated['total_seats']);

        $flight->update($validated);

        return redirect()->route('admin.flights.index')
            ->with('success', __('admin.flight_updated'));
    }

    public function destroy(Flight $flight): RedirectResponse
    {
        if ($flight->bookings()->exists()) {
            return back()->with('error', __('admin.cannot_delete_has_bookings'));
        }

        $flight->seats()->delete();
        $flight->delete();

        return redirect()->route('admin.flights.index')
            ->with('success', __('admin.flight_deleted'));
    }

    private function validateFlight(Request $request, ?Flight $flight = null): array
    {
        $rules = [
            'airline_id' => ['required', 'integer', 'exists:airlines,id'],
            'flight_number' => ['required', 'string', 'max:20'],
            'departure_airport_id' => ['required', 'integer', 'exists:airports,id', 'different:arrival_airport_id'],
            'arrival_airport_id' => ['required', 'integer', 'exists:airports,id'],
            'departure_time' => ['required', 'date'],
            'arrival_time' => ['required', 'date', 'after:departure_time'],
            'price' => ['required', 'numeric', 'min:1'],
            'status' => ['required', Rule::in(['scheduled', 'delayed', 'cancelled', 'completed'])],
        ];

        if (! $flight) {
            $rules['total_seats'] = ['required', 'integer', 'min:4', 'max:400', 'multiple_of:4'];
        }

        return $request->validate($rules);
    }
}
