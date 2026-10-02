@extends('layouts.app')

@section('title', __('booking.select_seats_heading') . ' — Kemet Air')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @include('bookings.partials.steps', ['current' => 1])
    @include('bookings.partials.flight-summary', ['flight' => $flight, 'draft' =>$draft])

    <div class="text-center mb-6">
        <h1 class="font-display text-2xl text-ink mb-1" style="font-weight:700;">{{ __('booking.select_seats_heading') }}</h1>
        <p class="text-sm text-ink/55">{{ __('booking.select_seats_subtitle', ['count' => $draft['passengers_count']]) }}</p>
    </div>

    <form method="POST" action="{{ route('bookings.seats.store') }}"
          x-data="{ selected: [], max: {{ (int) $draft['passengers_count'] }} }">
        @csrf

        <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-6 sm:p-8">

            <!-- Legend -->
            <div class="flex items-center justify-center gap-6 mb-8 text-xs text-ink/60">
                <span class="flex items-center gap-1.5"><span class="h-4 w-4 rounded border border-ink/20 bg-white"></span> {{ __('booking.seat_available') }}</span>
                <span class="flex items-center gap-1.5"><span class="h-4 w-4 rounded bg-amber"></span> {{ __('booking.seat_selected') }}</span>
                <span class="flex items-center gap-1.5"><span class="h-4 w-4 rounded bg-ink/15"></span> {{ __('booking.seat_booked') }}</span>
            </div>

            <!-- Nose of the plane, purely decorative -->
            <div class="w-24 h-8 mx-auto mb-6 rounded-t-full border-2 border-b-0 border-ink/10"></div>

            <div class="max-w-xs mx-auto space-y-2">
                @php $previousCabin = null; @endphp
                @foreach($seatsByRow as $rowNumber =>$rowSeats)
                    @php $cabin =$rowSeats->first()->seat_class; @endphp

                    @if($cabin !==$previousCabin)
                        <p class="text-center text-[11px] font-bold uppercase tracking-wide {{ $cabin === 'business' ? 'text-amber-600' : 'text-sky' }} pt-2 pb-1">
                            {{ $cabin === 'business' ? __('booking.business_cabin') : __('booking.economy_cabin') }}
                        </p>
                    @endif
                    @php $previousCabin =$cabin; @endphp

                    <div class="flex items-center justify-center gap-2">
                        <span class="w-4 text-[10px] text-ink/30 text-center">{{ $rowNumber }}</span>

                        @foreach($rowSeats as $index =>$seat)
                            @if($index === 2)
                                <span class="w-4"></span> {{-- aisle --}}
                            @endif

                            <div>
                                <input type="checkbox"
                                       id="seat-{{ $seat->id }}"
                                       name="seat_ids[]"
                                       value="{{ $seat->id }}"
                                       x-model="selected"
                                       @if($seat->is_booked) disabled @else :disabled="!selected.includes(String({{ $seat->id }})) && selected.length >= max" @endif
                                       class="sr-only peer">
                                <label for="seat-{{ $seat->id }}"
                                       class="flex h-9 w-9 items-center justify-center rounded-lg border text-[11px] font-semibold cursor-pointer transition-colors
                                              border-ink/15 text-ink/70 hover:border-sky
                                              peer-checked:bg-amber peer-checked:text-navy peer-checked:border-amber
                                              peer-disabled:cursor-not-allowed peer-disabled:hover:border-ink/15
                                              {{ $seat->is_booked ? 'peer-disabled:bg-ink/15 peer-disabled:text-ink/30 peer-disabled:border-transparent' : 'peer-disabled:opacity-30' }}">
                                    {{ $seat->seat_number }}
                                </label>
                            </div>
                        @endforeach

                        <span class="w-4 text-[10px] text-ink/30 text-center">{{ $rowNumber }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sticky action bar -->
        <div class="sticky bottom-4 mt-6 bg-navy rounded-2xl shadow-floating p-4 flex items-center justify-between gap-4">
            <p class="text-sm font-semibold text-white ps-2" x-text="`{{ __('booking.seats_selected', ['selected' => '__SEL__', 'total' => $draft['passengers_count']]) }}`.replace('__SEL__', selected.length)"></p>
            <button type="submit" :disabled="selected.length !== max"
                    :class="selected.length === max ? 'bg-amber hover:bg-amber-600 text-navy' : 'bg-white/10 text-white/40 cursor-not-allowed'"
                    class="rounded-lg font-bold text-sm px-6 py-2.5 transition-colors">
                {{ __('booking.continue') }}
            </button>
        </div>
    </form>
</div>
@endsection