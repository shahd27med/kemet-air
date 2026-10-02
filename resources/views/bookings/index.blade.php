@extends('layouts.app')

@section('title', __('booking.my_bookings_heading') . ' — Kemet Air')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-display text-2xl text-ink mb-6" style="font-weight:700;">{{ __('booking.my_bookings_heading') }}</h1>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-10 text-center">
            <div class="h-14 w-14 rounded-2xl bg-sky-50 text-sky flex items-center justify-center mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <p class="text-sm text-ink/60 mb-6">{{ __('booking.no_bookings') }}</p>
            <a href="{{ route('home') }}#search" class="rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold px-5 py-2.5 text-sm transition-colors">
                {{ __('booking.start_searching') }}
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <a href="{{ route('bookings.show', $booking) }}" class="block bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 sm:p-6 hover:shadow-floating transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="text-center shrink-0">
                                <p class="font-display text-lg text-ink" style="font-weight:800;" dir="ltr">{{ $booking->flight->departure_time->format('H:i') }}</p>
                                <p class="text-xs text-ink/45">{{ $booking->flight->departureAirport->code }}</p>
                            </div>
                            <span class="text-ink/25">{{ app()->getLocale() === 'ar' ? '←' : '→' }}</span>
                            <div class="text-center shrink-0">
                                <p class="font-display text-lg text-ink" style="font-weight:800;" dir="ltr">{{ $booking->flight->arrival_time->format('H:i') }}</p>
                                <p class="text-xs text-ink/45">{{ $booking->flight->arrivalAirport->code }}</p>
                            </div>
                            <div class="ps-4 border-s border-ink/10">
                                <p class="text-sm font-semibold text-ink">{{ $booking->flight->departureAirport->city->name }} &ndash; {{ $booking->flight->arrivalAirport->city->name }}</p>
                                <p class="text-xs text-ink/45">{{ $booking->flight->departure_time->translatedFormat('d M Y') }} &middot; <span dir="ltr">{{ $booking->booking_number }}</span></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold
                                {{ $booking->booking_status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($booking->booking_status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                                {{ __('booking.status_' . $booking->booking_status) }}
                            </span>
                            <span class="font-display text-base text-navy" style="font-weight:800;">{{ __('common.egp') }} {{ number_format($booking->final_price) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
