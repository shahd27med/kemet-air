<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightSearchRequest;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FlightController extends Controller
{
    /**
     * Departure-time-of-day windows used by the sidebar filter.
     */
    private const TIME_WINDOWS = [
        'morning'   => ['05:00:00', '11:59:59'],
        'afternoon' => ['12:00:00', '16:59:59'],
        'evening'   => ['17:00:00', '20:59:59'],
        'night'     => ['21:00:00', '04:59:59'], // wraps past midnight
    ];

    /**
     * How many "you might like" suggestions to show under an empty result set.
     */
    private const SUGGESTION_LIMIT = 6;

    /**
     * Handle a flight search from the homepage or the results page's own
     * "modify search" / filter form.
     */
    public function search(FlightSearchRequest $request): View
    {
        $validated = $request->validated();

        $originAirport = Airport::with('city')->findOrFail($validated['origin_airport_id']);
        $destinationAirport = Airport::with('city')->findOrFail($validated['destination_airport_id']);

        $passengers = max((int) ($validated['passengers_count'] ?? 1), 1);
        $classType = $validated['class_type'] ?? null;

        $filters = [
            'min_price'        => isset($validated['min_price']) ? (float) $validated['min_price'] : null,
            'max_price'        => isset($validated['max_price']) ? (float) $validated['max_price'] : null,
            'departure_window' => $validated['departure_window'] ?? [],
            'airline_id'       => array_map('intval', $validated['airline_id'] ?? []),
        ];

        // 1. جلب جميع الرحلات المتاحة على مسار الذهاب كقاعدة بحساب الأسعار الدقيقة
        $baseOutboundFlights = $this->baseRouteQuery(
            $validated['origin_airport_id'],
            $validated['destination_airport_id'],
            $validated['departure_date']
        )->get();

        // 2. حساب شركات الطيران المتاحة
        $availableAirlines = Airline::whereIn('id', $baseOutboundFlights->pluck('airline_id')->unique())
            ->orderBy('name')
            ->get();

        // 3. حساب حدود الأسعار (Min/Max) بناءً على إجمالي سعر المسافرين المختارين
        $allFaresPrices = collect();
        $classesToOffer = $classType ? [$classType] : array_keys(Flight::CLASS_PRICE_MULTIPLIERS);

        foreach ($baseOutboundFlights as $flight) {
            $attached = $this->attachFares($flight, $passengers, $classesToOffer);
            foreach ($attached['fares'] as $fare) {
                $allFaresPrices->push($fare['final_price']);
            }
        }

        $priceBounds = [
            'min' => $allFaresPrices->isNotEmpty() ? floor($allFaresPrices->min()) : 0,
            'max' => $allFaresPrices->isNotEmpty() ? ceil($allFaresPrices->max()) : 0,
        ];

        // 4. جلب وتصفية نتائج رحلات الذهاب
        $outboundResults = $this->buildResults(
            $validated['origin_airport_id'],
            $validated['destination_airport_id'],
            $validated['departure_date'],
            $passengers,
            $classType,
            $filters
        );

        // مقترحات في حال عدم وجود نتائج
        $upcomingFlights = collect();
        if ($outboundResults->isEmpty()) {
            $upcomingFlights = $this->getUpcomingSuggestions(
                $validated['origin_airport_id'],
                $validated['destination_airport_id'],
                $passengers,
                $classType
            );
        }

        // 5. رحلات العودة (إن وجدت)
        $returnResults = null;
        $returnUpcomingFlights = collect();
        if (! empty($validated['return_date'])) {
            $returnResults = $this->buildResults(
                $validated['destination_airport_id'],
                $validated['origin_airport_id'],
                $validated['return_date'],
                $passengers,
                $classType,
                $filters
            );

            if ($returnResults->isEmpty()) {
                $returnUpcomingFlights = $this->getUpcomingSuggestions(
                    $validated['destination_airport_id'],
                    $validated['origin_airport_id'],
                    $passengers,
                    $classType
                );
            }
        }

        return view('flights.index', [
            'airports'               => Airport::with('city')->get()->sortBy(fn (Airport $a) => $a->city->name)->values(),
            'originAirport'          => $originAirport,
            'destinationAirport'     => $destinationAirport,
            'departureDate'          => $validated['departure_date'],
            'returnDate'             => $validated['return_date'] ?? null,
            'passengers'             => $passengers,
            'classType'              => $classType,
            'filters'                => $filters,
            'availableAirlines'      => $availableAirlines,
            'priceBounds'            => $priceBounds,
            'outboundResults'        => $outboundResults,
            'returnResults'          => $returnResults,
            'upcomingFlights'        => $upcomingFlights,
            'returnUpcomingFlights'  => $returnUpcomingFlights,
        ]);
    }

    /**
     * Public "browse everything" page — every upcoming, bookable flight.
     */
    public function all(Request $request): View
    {
        // استقبال عدد المسافرين والفئة إن تم استدعاؤها عبر رابط البحث
        $passengers = max((int) $request->input('passengers_count', $request->input('passengers', 1)), 1);
        $classType = $request->input('class_type');

        $flights = Flight::with(['airline', 'departureAirport.city', 'arrivalAirport.city', 'seats'])
            ->upcoming()
            ->active()
            ->orderBy('departure_time')
            ->paginate(12)
            ->withQueryString();

        $classesToOffer = $classType ? [$classType] : array_keys(Flight::CLASS_PRICE_MULTIPLIERS);

        // حساب الأسعار بحسب عدد المسافرين المختار
        $flights->getCollection()->transform(
            fn (Flight $flight) => $this->attachFares($flight, $passengers, $classesToOffer)
        );

        return view('flights.all', [
            'flights'    => $flights,
            'passengers' => $passengers,
        ]);
    }

    /**
     * The unfiltered set of active flights for a route + date.
     */
    private function baseRouteQuery(int $originId, int $destinationId, string $date): Builder
    {
        return Flight::query()
            ->with(['airline', 'departureAirport.city', 'arrivalAirport.city', 'seats'])
            ->betweenAirports($originId, $destinationId)
            ->whereDate('departure_time', $date)
            ->active();
    }

    /**
     * Fetch flights for a route + date, apply the sidebar filters, and attach fares.
     *
     * @return Collection<int, array{flight: Flight, fares: array}>
     */
    private function buildResults(
        int $originId,
        int $destinationId,
        string $date,
        int $passengers,
        ?string $classType,
        array $filters
    ): Collection {
        $query = Flight::query()
            ->with(['airline', 'departureAirport.city', 'arrivalAirport.city', 'seats'])
            ->betweenAirports($originId, $destinationId)
            ->whereDate('departure_time', $date)
            ->active();

        if (! empty($filters['airline_id'])) {
            $query->whereIn('airline_id', $filters['airline_id']);
        }

        if (! empty($filters['departure_window'])) {
            $query->where(function (Builder $q) use ($filters) {
                foreach ($filters['departure_window'] as $window) {
                    [$start, $end] = self::TIME_WINDOWS[$window] ?? [null, null];

                    if (! $start) {
                        continue;
                    }

                    if ($window === 'night') {
                        $q->orWhere(function (Builder $qq) use ($start, $end) {
                            $qq->whereTime('departure_time', '>=', $start)
                               ->orWhereTime('departure_time', '<=', $end);
                        });
                    } else {
                        $q->orWhere(function (Builder $qq) use ($start, $end) {
                            $qq->whereTime('departure_time', '>=', $start)
                               ->whereTime('departure_time', '<=', $end);
                        });
                    }
                }
            });
        }

        $classesToOffer = $classType ? [$classType] : array_keys(Flight::CLASS_PRICE_MULTIPLIERS);

        return $query->orderBy('departure_time')
            ->get()
            ->map(fn (Flight $flight) => $this->attachFares($flight, $passengers, $classesToOffer))
            ->filter(function (array $result) use ($filters) {
                if (empty($result['fares'])) {
                    return false;
                }

                // فلترة الأسعار بناءً على السعر النهائي المحسوب (Final Price)
                if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
                    $result['fares'] = array_filter($result['fares'], function ($fare) use ($filters) {
                        $price = $fare['final_price'];
                        if ($filters['min_price'] !== null && $price < $filters['min_price']) {
                            return false;
                        }
                        if ($filters['max_price'] !== null && $price > $filters['max_price']) {
                            return false;
                        }
                        return true;
                    });
                    
                    // تحسين إعادة ترقيم المصفوفة بعد الفلترة
                    $result['fares'] = array_values($result['fares']);
                }

                return ! empty($result['fares']);
            })
            ->values();
    }

    /**
     * Upcoming flights to suggest when an exact search comes back empty.
     *
     * @return Collection<int, array{flight: Flight, fares: array}>
     */
    private function getUpcomingSuggestions(
        int $originId,
        int $destinationId,
        int $passengers,
        ?string $classType
    ): Collection {
        $classesToOffer = $classType ? [$classType] : array_keys(Flight::CLASS_PRICE_MULTIPLIERS);

        $sameRoute = $this->fetchUpcomingFlights(
            $passengers,
            $classesToOffer,
            self::SUGGESTION_LIMIT,
            $originId,
            $destinationId
        );

        if ($sameRoute->isNotEmpty()) {
            return $sameRoute;
        }

        return $this->fetchUpcomingFlights(
            $passengers,
            $classesToOffer,
            self::SUGGESTION_LIMIT
        );
    }

    /**
     * Shared query for both suggestion lookups and the "browse all" page.
     *
     * @return Collection<int, array{flight: Flight, fares: array}>
     */
    private function fetchUpcomingFlights(
        int $passengers,
        array $classesToOffer,
        int $limit,
        ?int $originId = null,
        ?int $destinationId = null
    ): Collection {
        $query = Flight::query()
            ->with(['airline', 'departureAirport.city', 'arrivalAirport.city', 'seats'])
            ->upcoming()
            ->active()
            ->orderBy('departure_time');

        if ($originId && $destinationId) {
            $query->betweenAirports($originId, $destinationId);
        }

        return $query->limit($limit * 3)
            ->get()
            ->map(fn (Flight $flight) => $this->attachFares($flight, $passengers, $classesToOffer))
            ->filter(fn (array $result) => ! empty($result['fares']))
            ->take($limit)
            ->values();
    }

    /**
     * Compute the bookable fare option(s) for a flight at a given passenger count.
     */
    private function attachFares(Flight $flight, int $passengers, array $classesToOffer): array
    {
        $fares = [];

        foreach ($classesToOffer as $class) {
            $seatsLeft = $flight->availableSeatsForClass($class);

            if ($seatsLeft < $passengers) {
                continue;
            }

            $pricePerSeat = $flight->priceForClass($class);
            $pricing = Booking::calculatePricing($pricePerSeat, $passengers);

            $fares[] = array_merge($pricing, [
                'seat_class'     => $class,
                'seats_left'     => $seatsLeft,
                'price_per_seat' => $pricePerSeat,
            ]);
        }

        return ['flight' => $flight, 'fares' => $fares];
    }
}