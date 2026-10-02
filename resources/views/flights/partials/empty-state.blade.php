@php
    $upcomingFlights = $upcomingFlights ?? collect();
@endphp

<div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-10 text-center">
    <div class="h-14 w-14 rounded-2xl bg-sky-50 text-sky flex items-center justify-center mx-auto mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
        </svg>
    </div>
    <h3 class="font-display text-lg text-ink mb-2" style="font-weight:700;">{{ __('flights.no_flights_title') }}</h3>
    <p class="text-sm text-ink/55 max-w-sm mx-auto leading-relaxed mb-6">
        @if($hasActiveFilters)
            {{ __('flights.no_flights_filtered') }}
        @else
            {{ __('flights.no_flights_plain') }}
        @endif
    </p>
    <div class="flex items-center justify-center flex-wrap gap-3">
        @if($hasActiveFilters)
            <a href="{{ route('flights.search', $baseSearchParams) }}"
               class="rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold px-4 py-2.5 text-sm transition-colors">
                {{ __('flights.clear_filters') }}
            </a>
        @endif
        <a href="{{ route('home') }}#search"
           class="rounded-lg border border-ink/15 text-ink/70 hover:bg-ice font-semibold px-4 py-2.5 text-sm transition-colors">
            {{ __('flights.modify_search') }}
        </a>
        <a href="{{ route('flights.all', array_filter(['passengers_count' => $passengers ?? 1, 'class_type' => $classType ?? null])) }}"
           class="rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold px-4 py-2.5 text-sm transition-colors flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
            {{ __('flights.show_all_scheduled') }}
        </a>
    </div>
</div>

<!-- ============ UPCOMING AVAILABLE FLIGHTS (suggestions) ============ -->
@if($upcomingFlights->isNotEmpty())
    <div class="mt-8">
        <div class="flex items-center gap-2.5 mb-1.5">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-amber-50 text-amber-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
            </span>
            <h3 class="font-display text-lg text-ink" style="font-weight:700;">{{ __('flights.upcoming_suggestions_heading') }}</h3>
        </div>
        <p class="text-sm text-ink/55 mb-5">{{ __('flights.upcoming_suggestions_subtitle') }}</p>

        <div class="space-y-4">
            @foreach($upcomingFlights as $result)
                @include('flights.partials.flight-card', ['result' => $result, 'passengers' => $passengers ?? 1, 'showContext' => true])
            @endforeach
        </div>
    </div>
@endif