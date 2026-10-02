<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_flights' => Flight::count(),
            'upcoming_flights' => Flight::upcoming()->active()->count(),
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('booking_status', 'confirmed')->count(),
            'total_revenue' => (float) Booking::where('payment_status', 'paid')->sum('final_price'),
            'registered_users' => User::where('role', 'customer')->count(),
        ];

        $recentBookings = Booking::with(['user', 'flight.departureAirport.city', 'flight.arrivalAirport.city'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
