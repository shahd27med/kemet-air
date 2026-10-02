<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $recentBookings = Booking::with(['flight.departureAirport.city', 'flight.arrivalAirport.city'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('profile.show', [
            'user' => $request->user(),
            'recentBookings' => $recentBookings,
        ]);
    }
}
