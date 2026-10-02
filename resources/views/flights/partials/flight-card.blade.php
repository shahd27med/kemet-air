@php
    /** @var \App\Models\Flight $flight */
    $flight = $result['flight'];
    $fares = $result['fares'];

    // تعريف عدد المسافرين الممرر للكارت، أو اعتماده من الطلب (Request) بحد أدنى 1
    $passengers = $passengers ?? request('passengers_count', 1);

    $avatarPalette = ['bg-navy', 'bg-sky-700', 'bg-amber-600', 'bg-navy-700'];
    $avatarColor = $avatarPalette[$flight->airline_id % count($avatarPalette)];

    $classLabels = ['economy' => __('search.economy'), 'business' => __('search.business')];

    // On the main results page the route + date are already shown once in
    // the section heading above the card list, so the card itself only
    // needs times. In contexts where cards can span different routes/dates
    // (upcoming-flight suggestions, the "browse all" page), pass
    // showContext => true to also print the date and city names.
    $showContext = $showContext ?? false;
@endphp

<div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 sm:p-6">
    <div class="flex flex-col lg:flex-row lg:items-center gap-6">

        <!-- Airline + route + timing -->
        <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="flex items-center gap-3 sm:w-44 shrink-0">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full {{ $avatarColor }} text-white text-sm font-bold shrink-0">
                    {{ $flight->airline->code }}
                </span>
                <div>
                    <p class="text-sm font-semibold text-ink leading-tight">{{ $flight->airline->name }}</p>
                    <p class="text-xs text-ink/45" dir="ltr">{{ $flight->flight_number }}</p>
                    @if($showContext)
                        <p class="text-xs text-sky font-medium mt-0.5">{{ $flight->departure_time->translatedFormat('D, d M') }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4 flex-1">
                <div class="text-center">
                    <p class="text-lg font-bold text-ink font-display" dir="ltr">{{ $flight->departure_time->format('H:i') }}</p>
                    <p class="text-xs text-ink/50">{{ $flight->departureAirport->code }}</p>
                    @if($showContext)
                        <p class="text-[11px] text-ink/40">{{ $flight->departureAirport->city->name }}</p>
                    @endif
                </div>

                <div class="flex-1 flex flex-col items-center min-w-[5rem]">
                    <span class="text-[11px] text-ink/40 mb-1" dir="ltr">{{ $flight->formatted_duration }}</span>
                    <div class="w-full flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-sky"></span>
                        <span class="flex-1 border-t border-dashed border-ink/20"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 text-sky rotate-90 rtl-flip">
                            <path d="M21.5 15.5l-6-2.3V6.6c0-1-.8-2.1-1.8-2.4-.4-.1-.7.2-.7.6v8L6.5 10 5 11l6.6 4.6-.2 3.6-2 1.2c-.3.2-.4.5-.3.8l.1.4c.1.3.5.5.8.4l2.5-.8h.1l2.5.8c.3.1.7-.1.8-.4l.1-.4c.1-.3 0-.6-.3-.8l-2-1.2-.2-3.6L20 12l1.5-.9v-1.6z"/>
                        </svg>
                        <span class="flex-1 border-t border-dashed border-ink/20"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-sky"></span>
                    </div>
                    <span class="text-[11px] text-ink/40 mt-1">{{ __('flights.direct') }}</span>
                </div>

                <div class="text-center">
                    <p class="text-lg font-bold text-ink font-display" dir="ltr">{{ $flight->arrival_time->format('H:i') }}</p>
                    <p class="text-xs text-ink/50">{{ $flight->arrivalAirport->code }}</p>
                    @if($showContext)
                        <p class="text-[11px] text-ink/40">{{ $flight->arrivalAirport->city->name }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fare options -->
        <div class="lg:w-72 shrink-0 flex flex-col gap-2.5 border-t lg:border-t-0 lg:border-s border-ink/10 pt-4 lg:pt-0 lg:ps-6">
            @foreach($fares as $fare)
                @php
                    // احتساب إجمالي السعر بناءً على عدد المسافرين
                    $unitPrice = $fare['final_price'];
                    $totalPrice = $unitPrice * $passengers;
                    
                    if ($fare['discount_percentage'] > 0) {
                        $unitSubtotal = $fare['subtotal'];
                        $totalSubtotal = $unitSubtotal * $passengers;
                    }
                @endphp

                <div class="rounded-xl border border-ink/10 p-3 flex items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="text-xs font-bold uppercase tracking-wide {{ $fare['seat_class'] === 'business' ? 'text-amber-600' : 'text-sky' }}">
                                {{ $classLabels[$fare['seat_class']] }}
                            </span>
                            @if($fare['seats_left'] <= 5)
                                <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 rounded-full px-1.5 py-0.5">{{ __('flights.seats_left', ['count' => $fare['seats_left']]) }}</span>
                            @endif
                        </div>

                        @if($fare['discount_percentage'] > 0)
                            <p class="text-xs text-ink/40 line-through">{{ __('common.egp') }} {{ number_format($totalSubtotal) }}</p>
                        @endif
                        <p class="text-base font-bold text-ink font-display">{{ __('common.egp') }} {{ number_format($totalPrice) }}</p>
                        <p class="text-[11px] text-ink/45">
                            @if($passengers > 1)
                                لعدد {{ $passengers }} مسافرين ({{ number_format($unitPrice) }} / للمسافر)
                            @else
                                لعدد 1 مسافر واحد
                            @endif

                            @if($fare['discount_percentage'] > 0)
                                &middot; <span class="text-amber-700 font-semibold">{{ __('flights.off', ['percent' => rtrim(rtrim(number_format($fare['discount_percentage'], 1), '0'), '.')]) }}</span>
                            @endif
                        </p>
                    </div>

                    <a href="{{ route('bookings.create', ['flight_id' => $flight->id, 'seat_class' => $fare['seat_class'], 'passengers_count' => $passengers]) }}"
                       class="shrink-0 rounded-lg bg-amber hover:bg-amber-600 text-navy text-xs font-bold px-3.5 py-2 transition-colors whitespace-nowrap">
                        {{ __('flights.book_now') }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>