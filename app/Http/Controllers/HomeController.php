<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\City;
use App\Models\Flight;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Cities featured in the "Popular Destinations" section, in display order.
     */
    private const FEATURED_CITY_CODES = ['CAI', 'ASW', 'SSH', 'HRG', 'ALX'];

    /**
     * Default "From" airport used when a destination card is clicked
     * directly, keyed by the destination's own city code (so Cairo's card
     * doesn't try to route from Cairo to Cairo).
     */
    private const DEFAULT_ORIGIN_FOR_DESTINATION = [
        'CAI' => 'HRG',
        'ASW' => 'CAI',
        'SSH' => 'CAI',
        'HRG' => 'CAI',
        'ALX' => 'CAI',
    ];

    public function index(): View
    {
        // Airports (not cities) populate the search dropdowns, since a
        // search is between two specific airports.
        $airports = Airport::with('city')->get()->sortBy(fn (Airport $a) => $a->city->name)->values();

        $airportsByCode = $airports->keyBy(fn (Airport $a) => $a->city->code);

        $featuredCities = City::whereIn('code', self::FEATURED_CITY_CODES)->get()->keyBy('code');

        $destinations = collect(self::FEATURED_CITY_CODES)
            ->map(function (string $code) use ($featuredCities, $airportsByCode) {
                $city = $featuredCities->get($code);
                $destinationAirport = $airportsByCode->get($code);

                if (! $city || ! $destinationAirport) {
                    return null;
                }

                $fromPrice = Flight::query()
                    ->where('arrival_airport_id', $destinationAirport->id)
                    ->active()
                    ->upcoming()
                    ->min('price');

                $originCode = self::DEFAULT_ORIGIN_FOR_DESTINATION[$code] ?? 'CAI';
                $originAirport = $airportsByCode->get($originCode);

                return [
                    'city' => $city,
                    'from_price' => $fromPrice,
                    'destination_airport_id' => $destinationAirport->id,
                    'origin_airport_id' => $originAirport?->id,
                ];
            })
            ->filter()
            ->values();

        return view('home', compact('airports', 'destinations'));
    }
}
