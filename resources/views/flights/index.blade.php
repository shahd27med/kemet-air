@extends('layouts.app')

@section('title', $originAirport->city->name . ' → ' . $destinationAirport->city->name . ' — Kemet Air')

@php
    $hasActiveFilters = $filters['min_price'] !== null
        || $filters['max_price'] !== null
        || ! empty($filters['departure_window'])
        || ! empty($filters['airline_id']);

    $baseSearchParams = [
        'origin_airport_id' => $originAirport->id,
        'destination_airport_id' => $destinationAirport->id,
        'departure_date' => $departureDate,
        'return_date' => $returnDate,
        'passengers_count' => $passengers,
        'class_type' => $classType,
    ];

    $routeArrow = app()->getLocale() === 'ar' ? '←' : '→';
@endphp

@section('content')
<div class="bg-ice">

    <!-- ============ MODIFY SEARCH BAR ============ -->
    <section class="bg-navy">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <form method="GET" action="{{ route('flights.search') }}" x-data="{ from: '{{ $originAirport->id }}', to: '{{ $destinationAirport->id }}' }">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                    <div class="lg:col-span-3">
                        <label for="origin_airport_id" class="block text-[11px] font-semibold uppercase tracking-wide text-white/50 mb-1">{{ __('search.from') }}</label>
                        <select id="origin_airport_id" name="origin_airport_id" x-model="from" required
                                class="w-full rounded-lg border-0 bg-white/10 text-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-amber focus:outline-none transition [&>option]:text-ink">
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city->name }} ({{ $airport->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-3">
                        <label for="destination_airport_id" class="block text-[11px] font-semibold uppercase tracking-wide text-white/50 mb-1">{{ __('search.to') }}</label>
                        <select id="destination_airport_id" name="destination_airport_id" x-model="to" required
                                class="w-full rounded-lg border-0 bg-white/10 text-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-amber focus:outline-none transition [&>option]:text-ink">
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city->name }} ({{ $airport->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="departure_date" class="block text-[11px] font-semibold uppercase tracking-wide text-white/50 mb-1">{{ __('search.departure') }}</label>
                        <input type="date" id="departure_date" name="departure_date" value="{{ $departureDate }}" required min="{{ now()->format('Y-m-d') }}"
                               class="w-full rounded-lg border-0 bg-white/10 text-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-amber focus:outline-none transition">
                    </div>

                    <div class="lg:col-span-2">
                        <label for="return_date" class="block text-[11px] font-semibold uppercase tracking-wide text-white/50 mb-1">{{ __('search.return') }} <span class="normal-case text-white/30">{{ __('search.optional') }}</span></label>
                        <input type="date" id="return_date" name="return_date" value="{{ $returnDate }}" min="{{ $departureDate }}"
                               class="w-full rounded-lg border-0 bg-white/10 text-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-amber focus:outline-none transition">
                    </div>

                    <div class="lg:col-span-1">
                        <label for="passengers_count" class="block text-[11px] font-semibold uppercase tracking-wide text-white/50 mb-1">{{ __('search.pax_short') }}</label>
                        <select id="passengers_count" name="passengers_count"
                                class="w-full rounded-lg border-0 bg-white/10 text-white px-2 py-2.5 text-sm focus:ring-2 focus:ring-amber focus:outline-none transition [&>option]:text-ink">
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}" @selected($passengers === $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="lg:col-span-1">
                        <button type="submit"
                                class="w-full h-[42px] rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold text-sm shadow-sm transition-colors flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-2">
                    <label for="class_type" class="sr-only">{{ __('search.cabin_class') }}</label>
                    <select id="class_type" name="class_type"
                            class="rounded-lg border-0 bg-white/10 text-white px-3 py-1.5 text-xs focus:ring-2 focus:ring-amber focus:outline-none transition [&>option]:text-ink">
                        <option value="" @selected(!$classType)>{{ __('search.any_class') }}</option>
                        <option value="economy" @selected($classType === 'economy')>{{ __('search.economy') }}</option>
                        <option value="business" @selected($classType === 'business')>{{ __('search.business') }}</option>
                    </select>
                </div>
            </form>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 {{ $outboundResults->isNotEmpty() ? 'lg:grid-cols-4' : '' }} gap-8">

        <!-- ============ FILTER SIDEBAR (Only shown when there are results) ============ -->
        @if($outboundResults->isNotEmpty())
        <aside class="lg:col-span-1">
            <form method="GET" action="{{ route('flights.search') }}" class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 sticky top-20">
                @foreach($baseSearchParams as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-display text-base text-ink" style="font-weight:700;">{{ __('flights.filters') }}</h2>
                    @if($hasActiveFilters)
                        <a href="{{ route('flights.search', $baseSearchParams) }}" class="text-xs font-semibold text-sky hover:underline">{{ __('flights.clear_all') }}</a>
                    @endif
                </div>

                <!-- Price range -->
                <div class="mb-6">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-ink/50 mb-2.5">{{ __('flights.price_range') }}</h3>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" value="{{ $filters['min_price'] }}" placeholder="{{ number_format($priceBounds['min']) }}" min="0"
                               class="w-full rounded-lg border border-ink/15 px-2.5 py-2 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                        <span class="text-ink/30 text-sm">–</span>
                        <input type="number" name="max_price" value="{{ $filters['max_price'] }}" placeholder="{{ number_format($priceBounds['max']) }}" min="0"
                               class="w-full rounded-lg border border-ink/15 px-2.5 py-2 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                    </div>
                </div>

                <!-- Departure time -->
                <div class="mb-6">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-ink/50 mb-2.5">{{ __('flights.departure_time') }}</h3>
                    <div class="space-y-2">
                        @foreach(['morning' => __('flights.window_morning'), 'afternoon' => __('flights.window_afternoon'), 'evening' => __('flights.window_evening'), 'night' => __('flights.window_night')] as $value => $label)
                            <label class="flex items-center gap-2.5 text-sm text-ink/70 cursor-pointer">
                                <input type="checkbox" name="departure_window[]" value="{{ $value }}"
                                       @checked(in_array($value, $filters['departure_window']))
                                       class="rounded border-ink/30 text-sky focus:ring-sky/40">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Airlines -->
                @if($availableAirlines->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-ink/50 mb-2.5">{{ __('flights.airline') }}</h3>
                        <div class="space-y-2">
                            @foreach($availableAirlines as $airline)
                                <label class="flex items-center gap-2.5 text-sm text-ink/70 cursor-pointer">
                                    <input type="checkbox" name="airline_id[]" value="{{ $airline->id }}"
                                           @checked(in_array($airline->id, $filters['airline_id']))
                                           class="rounded border-ink/30 text-sky focus:ring-sky/40">
                                    {{ $airline->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="submit" class="w-full rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold py-2.5 text-sm transition-colors">
                    {{ __('flights.apply_filters') }}
                </button>
            </form>
        </aside>
        @endif

        <!-- ============ RESULTS ============ -->
        <div class="{{ $outboundResults->isNotEmpty() ? 'lg:col-span-3' : 'w-full' }} space-y-8">

            <!-- Outbound -->
            <div>
                <div class="flex items-baseline justify-between mb-4">
                    <div>
                        <h1 class="font-display text-xl text-ink" style="font-weight:700;">
                            {{ $originAirport->city->name }} <span class="text-ink/30">{{ $routeArrow }}</span> {{ $destinationAirport->city->name }}
                        </h1>
                        <p class="text-sm text-ink/50">{{ \Illuminate\Support\Carbon::parse($departureDate)->translatedFormat('D, d M Y') }} &middot; {{ trans_choice('search.passenger_count', $passengers, ['count' => $passengers]) }}</p>
                    </div>
                    <p class="text-sm text-ink/50 shrink-0">{{ trans_choice('flights.flights_found', $outboundResults->count(), ['count' => $outboundResults->count()]) }}</p>
                </div>

                @if($outboundResults->isEmpty())
                    @include('flights.partials.empty-state', [
                        'baseSearchParams' => $baseSearchParams,
                        'hasActiveFilters' => $hasActiveFilters,
                        'upcomingFlights' => $upcomingFlights,
                        'passengers' => $passengers,
                    ])
                @else
                    <div class="space-y-4">
                        @foreach($outboundResults as $result)
                            @include('flights.partials.flight-card', ['result' => $result, 'passengers' => $passengers])
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Return (only when a return date was searched) -->
            @if(! is_null($returnResults))
                <div class="pt-4 border-t border-ink/10">
                    <div class="flex items-baseline justify-between mb-4">
                        <div>
                            <h2 class="font-display text-xl text-ink" style="font-weight:700;">
                                {{ $destinationAirport->city->name }} <span class="text-ink/30">{{ $routeArrow }}</span> {{ $originAirport->originAirport->city->name ?? $originAirport->city->name }}
                                <span class="text-sm font-normal text-ink/40">{{ __('flights.return_label') }}</span>
                            </h2>
                            <p class="text-sm text-ink/50">{{ \Illuminate\Support\Carbon::parse($returnDate)->translatedFormat('D, d M Y') }} &middot; {{ trans_choice('search.passenger_count', $passengers, ['count' => $passengers]) }}</p>
                        </div>
                        <p class="text-sm text-ink/50 shrink-0">{{ trans_choice('flights.flights_found', $returnResults->count(), ['count' => $returnResults->count()]) }}</p>
                    </div>

                    @if($returnResults->isEmpty())
                        @include('flights.partials.empty-state', [
                            'baseSearchParams' => $baseSearchParams,
                            'hasActiveFilters' => $hasActiveFilters,
                            'upcomingFlights' => $returnUpcomingFlights,
                            'passengers' => $passengers,
                        ])
                    @else
                        <div class="space-y-4">
                            @foreach($returnResults as $result)
                                @include('flights.partials.flight-card', ['result' => $result, 'passengers' => $passengers])
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>
@endsection