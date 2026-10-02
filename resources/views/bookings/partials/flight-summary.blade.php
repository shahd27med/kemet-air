@php
    $classLabel = $draft['seat_class'] === 'business' ? __('search.business') : __('search.economy');
@endphp

<div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 mb-6 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
    <div class="flex items-center gap-3 shrink-0">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-navy text-white text-sm font-bold">
            {{ $flight->airline->code }}
        </span>
        <div>
            <p class="text-sm font-semibold text-ink">{{ $flight->airline->name }}</p>
            <p class="text-xs text-ink/45" dir="ltr">{{ $flight->flight_number }}</p>
        </div>
    </div>

    <div class="flex items-center gap-3 flex-1 text-sm">
        <div class="text-center">
            <p class="font-bold text-ink" dir="ltr">{{ $flight->departure_time->format('H:i') }}</p>
            <p class="text-xs text-ink/50">{{ $flight->departureAirport->code }}</p>
        </div>
        <span class="text-ink/30">{{ app()->getLocale() === 'ar' ? '←' : '→' }}</span>
        <div class="text-center">
            <p class="font-bold text-ink" dir="ltr">{{ $flight->arrival_time->format('H:i') }}</p>
            <p class="text-xs text-ink/50">{{ $flight->arrivalAirport->code }}</p>
        </div>
        <span class="text-ink/30 mx-1">&middot;</span>
        <p class="text-ink/60">{{ $flight->departure_time->translatedFormat('D, d M Y') }}</p>
    </div>

    <div class="flex items-center gap-2 shrink-0">
        <span class="text-xs font-bold uppercase tracking-wide {{ $draft['seat_class'] === 'business' ? 'text-amber-600' : 'text-sky' }}">{{ $classLabel }}</span>
        <span class="text-ink/30">&middot;</span>
        <span class="text-sm text-ink/60">{{ trans_choice('search.passenger_count', $draft['passengers_count'], ['count' => $draft['passengers_count']]) }}</span>
    </div>
</div>
