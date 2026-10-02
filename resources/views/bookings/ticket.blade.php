@extends('layouts.app')

@section('title', __('booking.ticket_heading') . ' ' . $booking->booking_number . ' — Kemet Air')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if($justConfirmed ?? false)
        <div class="text-center mb-8 print:hidden">
            <div class="h-14 w-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h1 class="font-display text-2xl text-ink mb-1" style="font-weight:700;">{{ __('booking.confirmation_heading') }}</h1>
            <p class="text-sm text-ink/60">{{ __('booking.confirmation_subtitle') }}</p>
        </div>
    @endif

    @if($isAdminView ?? false)
        <div class="mb-6 print:hidden">
            <a href="{{ route('admin.bookings.index') }}" class="text-sm font-semibold text-sky hover:underline">&larr; {{ __('admin.all_bookings') }}</a>
        </div>
    @endif

    <!-- ============ TICKET ============ -->
    <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 overflow-hidden">

        <!-- Header -->
        <div class="bg-navy text-white p-6 sm:p-8 flex items-center justify-between gap-4">
            <div>
                <x-brand-mark size="sm" />
                <p class="text-xs text-white/50 mt-2">{{ __('booking.booking_reference') }}</p>
                <p class="font-display text-lg tracking-wide" style="font-weight:800;" dir="ltr">{{ $booking->booking_number }}</p>
            </div>
            <div class="text-end">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold
                    {{ $booking->booking_status === 'confirmed' ? 'bg-emerald-400/20 text-emerald-300' : ($booking->booking_status === 'cancelled' ? 'bg-red-400/20 text-red-300' : 'bg-amber/20 text-amber') }}">
                    {{ __('booking.status_' . $booking->booking_status) }}
                </span>
                <p class="text-xs text-white/50 mt-2">{{ __('booking.booked_on', ['date' => $booking->created_at->translatedFormat('d M Y')]) }}</p>
            </div>
        </div>

        <!-- Flight info -->
        <div class="p-6 sm:p-8 border-b border-dashed border-ink/15">
            <div class="flex items-center gap-3 mb-5">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-navy text-white text-sm font-bold">
                    {{ $booking->flight->airline->code }}
                </span>
                <div>
                    <p class="text-sm font-semibold text-ink">{{ $booking->flight->airline->name }}</p>
                    <p class="text-xs text-ink/45" dir="ltr">{{ $booking->flight->flight_number }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between max-w-md">
                <div>
                    <p class="font-display text-2xl text-ink" style="font-weight:800;" dir="ltr">{{ $booking->flight->departure_time->format('H:i') }}</p>
                    <p class="text-sm text-ink/60">{{ $booking->flight->departureAirport->city->name }} ({{ $booking->flight->departureAirport->code }})</p>
                </div>
                <div class="flex-1 flex flex-col items-center px-4">
                    <span class="text-[11px] text-ink/40 mb-1" dir="ltr">{{ $booking->flight->formatted_duration }}</span>
                    <div class="w-full flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-sky"></span>
                        <span class="flex-1 border-t border-dashed border-ink/20"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-sky"></span>
                    </div>
                </div>
                <div class="text-end">
                    <p class="font-display text-2xl text-ink" style="font-weight:800;" dir="ltr">{{ $booking->flight->arrival_time->format('H:i') }}</p>
                    <p class="text-sm text-ink/60">{{ $booking->flight->arrivalAirport->city->name }} ({{ $booking->flight->arrivalAirport->code }})</p>
                </div>
            </div>
            <p class="text-sm text-ink/50 mt-3">{{ $booking->flight->departure_time->translatedFormat('l, d F Y') }}</p>
        </div>

        <!-- Passengers -->
        <div class="p-6 sm:p-8 border-b border-dashed border-ink/15">
            <h2 class="font-display text-sm text-ink/50 uppercase tracking-wide mb-4" style="font-weight:700;">{{ __('booking.passenger_summary') }}</h2>
            <div class="space-y-3">
                @foreach($booking->passengers as $passenger)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-ink">{{ $passenger->full_name }}</p>
                            <p class="text-xs text-ink/45" dir="ltr">{{ $passenger->national_id_or_passport }}</p>
                        </div>
                        <div class="text-end">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-navy bg-navy/5 rounded-full px-2.5 py-1">
                                {{ __('booking.seat_label', ['seat' => $passenger->seat?->seat_number ?? '—']) }}
                            </span>
                            <p class="text-[11px] text-ink/40 mt-1 uppercase">{{ $passenger->seat?->seat_class === 'business' ? __('search.business') : __('search.economy') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Payment -->
        <div class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-ink/60">{{ __('booking.total') }}</span>
                <span class="font-display text-xl text-navy" style="font-weight:800;">{{ __('common.egp') }} {{ number_format($booking->final_price) }}</span>
            </div>
            @if($booking->discount_amount > 0)
                <p class="text-xs text-amber-700 text-end mb-2">
                    {{ __('booking.discount', ['percent' => rtrim(rtrim(number_format(($booking->discount_amount / max($booking->subtotal, 1)) * 100, 1), '0'), '.')]) }}
                    &middot; &minus; {{ __('common.egp') }} {{ number_format($booking->discount_amount) }}
                </p>
            @endif
            <div class="flex items-center justify-between text-xs text-ink/45">
                <span>{{ __('booking.payment_method') }}: {{ __('booking.' . ($booking->payment->payment_method ?? 'credit_card')) }}</span>
                <span>{{ __('booking.payment_' . $booking->payment_status) }}</span>
            </div>

            <!-- Decorative barcode -->
            <div class="mt-6 flex items-end gap-[3px] h-10 opacity-70" aria-hidden="true">
                @php $bars = str_split(str_pad(preg_replace('/[^0-9]/', '', $booking->booking_number . $booking->id), 28, '4791', STR_PAD_RIGHT)); @endphp
                @foreach($bars as $bar)
                    <span class="bg-ink" style="width: {{ (((int)$bar % 3) + 1) }}px; height: {{ 60 + ((int)$bar * 4) }}%;"></span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center gap-3 mt-6 print:hidden">
        <button type="button" onclick="window.print()" class="rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold text-sm px-5 py-2.5 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.318 2.865a.75.75 0 01-.674.818 48.336 48.336 0 01-9.808 0 .75.75 0 01-.674-.818L6.34 18m11.32 0h1.183c.483 0 .964-.078 1.42-.231A2.985 2.985 0 0021.75 15V9.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25V15a2.985 2.985 0 001.827 2.769c.456.153.937.231 1.42.231H6.34M17.66 18h-11.32M6.75 7.5V4.875c0-.621.504-1.125 1.125-1.125h8.25c.621 0 1.125.504 1.125 1.125V7.5" />
            </svg>
            {{ __('booking.print_ticket') }}
        </button>

        @if(! ($isAdminView ?? false))
            <a href="{{ route('bookings.index') }}" class="rounded-lg border border-ink/15 text-ink/70 hover:bg-ice font-semibold text-sm px-5 py-2.5 transition-colors">
                {{ __('booking.view_my_bookings') }}
            </a>
        @endif
    </div>
</div>

@push('styles')
<style>
    @media print {
        body { background: white !important; }
        .print\:hidden { display: none !important; }
    }
</style>
@endpush
@endsection
