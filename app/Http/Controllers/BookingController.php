<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class BookingController extends Controller
{
    /**
     * Session key used to hold the in-progress booking draft as the user
     * moves through seats -> passengers -> checkout. Nothing here is
     * trusted at face value on the final step: price and seat availability
     * are always recomputed from the database at confirm() time.
     */
    private const SESSION_KEY = 'booking_draft';

    /**
     * List the authenticated user's own bookings, most recent first.
     */
    public function index(Request $request): View
    {
        $bookings = Booking::with(['flight.airline', 'flight.departureAirport.city', 'flight.arrivalAirport.city'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show a single booking (ticket view). Only the owner may view it here;
     * admins view any booking via the separate Admin\BookingController.
     */
    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['flight.airline', 'flight.departureAirport.city', 'flight.arrivalAirport.city', 'passengers.seat', 'payment', 'user']);

        return view('bookings.ticket', ['booking' => $booking]);
    }

    /**
     * Entry point from a search result's "Book Now" fare button. Validates
     * the flight/class/passenger count still has room, then starts a fresh
     * booking draft in the session.
     */
    public function create(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'flight_id' => ['required', 'integer', 'exists:flights,id'],
            'seat_class' => ['required', 'in:economy,business'],
            'passengers_count' => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        $flight = Flight::findOrFail($validated['flight_id']);

        if ($flight->availableSeatsForClass($validated['seat_class']) < $validated['passengers_count']) {
            return redirect()->route('home')
                ->with('error', 'Sorry, there are no longer enough seats available for that search. Please search again.');
        }

        session([self::SESSION_KEY => [
            'flight_id' => $flight->id,
            'seat_class' => $validated['seat_class'],
            'passengers_count' => $validated['passengers_count'],
            'seat_ids' => null,
            'passengers' => null,
        ]]);

        return redirect()->route('bookings.seats');
    }

    /**
     * Step 1: interactive seat map for the flight/class chosen in create().
     */
    public function selectSeats(Request $request): View|RedirectResponse
    {
        $draft = $this->requireDraft($request);
        if ($draft instanceof RedirectResponse) {
            return $draft;
        }

        $flight = Flight::with(['airline', 'departureAirport.city', 'arrivalAirport.city'])->findOrFail($draft['flight_id']);

        $seats = $flight->seats()
            ->where('seat_class', $draft['seat_class'])
            ->orderBy('seat_number')
            ->get();

        // Group into rows (numeric part of the seat number) with seats
        // ordered A, B, C, D within each row, for a realistic grid.
        $seatsByRow = $seats->groupBy(fn (Seat $seat) => (int) preg_replace('/\D/', '', $seat->seat_number))
            ->sortKeys();

        return view('bookings.seats', [
            'flight' => $flight,
            'draft' => $draft,
            'seatsByRow' => $seatsByRow,
        ]);
    }

    /**
     * Persist the chosen seat IDs (must exactly match passengers_count,
     * belong to this flight/class, and still be free) then move on.
     */
    public function storeSeats(Request $request): RedirectResponse
    {
        $draft = $this->requireDraft($request);
        if ($draft instanceof RedirectResponse) {
            return $draft;
        }

        $validated = $request->validate([
            'seat_ids' => ['required', 'array', 'size:' . $draft['passengers_count']],
            'seat_ids.*' => ['integer', 'distinct', 'exists:seats,id'],
        ]);

        $seats = Seat::whereIn('id', $validated['seat_ids'])
            ->where('flight_id', $draft['flight_id'])
            ->where('seat_class', $draft['seat_class'])
            ->where('is_booked', false)
            ->orderBy('seat_number')
            ->get();

        if ($seats->count() !== (int) $draft['passengers_count']) {
            return back()->with('error', 'One or more selected seats are no longer available. Please choose again.');
        }

        $draft['seat_ids'] = $seats->pluck('id')->values()->all();
        $draft['passengers'] = null; // reset a later step if seats changed
        session([self::SESSION_KEY => $draft]);

        return redirect()->route('bookings.passengers');
    }

    /**
     * Step 2: one form block per passenger, pre-labeled with their
     * assigned seat (seats were sorted by seat_number in storeSeats()).
     */
    public function passengerForm(Request $request): View|RedirectResponse
    {
        $draft = $this->requireDraft($request, requireSeats: true);
        if ($draft instanceof RedirectResponse) {
            return $draft;
        }

        $flight = Flight::findOrFail($draft['flight_id']);
        $seatsById = Seat::whereIn('id', $draft['seat_ids'])->get()->keyBy('id');
        // Preserve the original seat order chosen in storeSeats().
        $orderedSeats = collect($draft['seat_ids'])->map(fn ($id) => $seatsById->get($id));

        return view('bookings.passengers', [
            'flight' => $flight,
            'draft' => $draft,
            'seats' => $orderedSeats,
        ]);
    }

    /**
     * Persist passenger details (matching the `passengers` table schema),
     * zipped in order with the previously chosen seats.
     */
    public function storePassengers(Request $request): RedirectResponse
    {
        $draft = $this->requireDraft($request, requireSeats: true);
        if ($draft instanceof RedirectResponse) {
            return $draft;
        }

        $count = (int) $draft['passengers_count'];

        $validated = $request->validate([
            'passengers' => ['required', 'array', 'size:' . $count],
            'passengers.*.full_name' => ['required', 'string', 'max:150'],
            'passengers.*.national_id_or_passport' => ['required', 'string', 'max:50'],
            'passengers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'passengers.*.gender' => ['required', 'in:male,female'],
        ]);

        $passengers = [];
        foreach (array_values($validated['passengers']) as $index => $passenger) {
            $passengers[] = array_merge($passenger, [
                'seat_id' => $draft['seat_ids'][$index],
            ]);
        }

        $draft['passengers'] = $passengers;
        session([self::SESSION_KEY => $draft]);

        return redirect()->route('bookings.checkout');
    }

    /**
     * Step 3: fare breakdown + payment method, recomputed fresh from the
     * flight/class/count stored server-side (never trusting client input).
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        $draft = $this->requireDraft($request, requireSeats: true, requirePassengers: true);
        if ($draft instanceof RedirectResponse) {
            return $draft;
        }

        $flight = Flight::with(['airline', 'departureAirport.city', 'arrivalAirport.city'])->findOrFail($draft['flight_id']);
        $seats = Seat::whereIn('id', $draft['seat_ids'])->get()->keyBy('id');

        $pricePerSeat = $flight->priceForClass($draft['seat_class']);
        $pricing = Booking::calculatePricing($pricePerSeat, (int) $draft['passengers_count']);

        return view('bookings.checkout', [
            'flight' => $flight,
            'draft' => $draft,
            'seats' => $seats,
            'pricePerSeat' => $pricePerSeat,
            'pricing' => $pricing,
        ]);
    }

    /**
     * Final step: re-validate seat availability under a DB lock, create the
     * Booking/Passengers/Payment rows in one transaction, update seat and
     * flight availability, and clear the draft.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $draft = $this->requireDraft($request, requireSeats: true, requirePassengers: true);
        if ($draft instanceof RedirectResponse) {
            return $draft;
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:credit_card,debit_card,wallet,cash'],
        ]);

        try {
            $booking = DB::transaction(function () use ($draft, $validated, $request) {
                $flight = Flight::lockForUpdate()->findOrFail($draft['flight_id']);

                $lockedSeats = Seat::whereIn('id', $draft['seat_ids'])
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($draft['seat_ids'] as $seatId) {
                    $seat = $lockedSeats->get($seatId);
                    if (! $seat || $seat->is_booked) {
                        throw new RuntimeException('seat_conflict');
                    }
                }

                $pricePerSeat = $flight->priceForClass($draft['seat_class']);
                $pricing = Booking::calculatePricing($pricePerSeat, (int) $draft['passengers_count']);

                $booking = Booking::create([
                    'user_id' => $request->user()->id,
                    'flight_id' => $flight->id,
                    'total_passengers' => $draft['passengers_count'],
                    'subtotal' => $pricing['subtotal'],
                    'discount_amount' => $pricing['discount_amount'],
                    'final_price' => $pricing['final_price'],
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed',
                ]);

                foreach ($draft['passengers'] as $passengerData) {
                    Passenger::create([
                        'booking_id' => $booking->id,
                        'full_name' => $passengerData['full_name'],
                        'national_id_or_passport' => $passengerData['national_id_or_passport'],
                        'date_of_birth' => $passengerData['date_of_birth'],
                        'gender' => $passengerData['gender'],
                        'seat_id' => $passengerData['seat_id'],
                    ]);

                    $lockedSeats->get($passengerData['seat_id'])->update(['is_booked' => true]);
                }

                $flight->decrementAvailableSeats((int) $draft['passengers_count']);

                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $pricing['final_price'],
                    'status' => 'completed',
                ]);

                return $booking;
            });
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'seat_conflict') {
                $draft['seat_ids'] = null;
                $draft['passengers'] = null;
                session([self::SESSION_KEY => $draft]);

                return redirect()->route('bookings.seats')
                    ->with('error', 'One of your selected seats was just booked by someone else. Please choose again.');
            }

            throw $e;
        }

        session()->forget(self::SESSION_KEY);

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Your booking is confirmed. Reference: ' . $booking->booking_number);
    }

    /**
     * Post-payment confirmation screen (same ticket view used by show()).
     */
    public function confirmation(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['flight.airline', 'flight.departureAirport.city', 'flight.arrivalAirport.city', 'passengers.seat', 'payment', 'user']);

        return view('bookings.ticket', ['booking' => $booking, 'justConfirmed' => true]);
    }

    /**
     * Read the session draft, redirecting to the right earlier step (with
     * a friendly message) if a prerequisite stage is missing.
     */
    private function requireDraft(Request $request, bool $requireSeats = false, bool $requirePassengers = false): array|RedirectResponse
    {
        $draft = session(self::SESSION_KEY);

        if (! $draft || ! isset($draft['flight_id'])) {
            return redirect()->route('home')
                ->with('error', 'Start a new search to book a flight.');
        }

        if ($requireSeats && empty($draft['seat_ids'])) {
            return redirect()->route('bookings.seats')
                ->with('error', 'Please choose your seats first.');
        }

        if ($requirePassengers && empty($draft['passengers'])) {
            return redirect()->route('bookings.passengers')
                ->with('error', 'Please enter passenger details first.');
        }

        return $draft;
    }
}
