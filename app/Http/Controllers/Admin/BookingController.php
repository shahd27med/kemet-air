<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::with(['user', 'flight.departureAirport.city', 'flight.arrivalAirport.city'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['flight.airline', 'flight.departureAirport.city', 'flight.arrivalAirport.city', 'passengers.seat', 'payment', 'user']);

        return view('bookings.ticket', ['booking' => $booking, 'isAdminView' => true]);
    }
}
