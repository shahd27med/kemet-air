@extends('admin.layout')

@section('title', __('admin.dashboard') . ' — Kemet Air')

@section('admin_content')
<h1 class="font-display text-2xl text-ink mb-6" style="font-weight:700;">{{ __('admin.overview') }}</h1>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    @php
        $cards = [
            ['label' => __('admin.stat_total_flights'), 'value' => $stats['total_flights'], 'color' => 'text-navy bg-navy/5'],
            ['label' => __('admin.stat_upcoming_flights'), 'value' => $stats['upcoming_flights'], 'color' => 'text-sky bg-sky-50'],
            ['label' => __('admin.stat_total_bookings'), 'value' => $stats['total_bookings'], 'color' => 'text-navy bg-navy/5'],
            ['label' => __('admin.stat_confirmed_bookings'), 'value' => $stats['confirmed_bookings'], 'color' => 'text-emerald-700 bg-emerald-50'],
            ['label' => __('admin.stat_total_revenue'), 'value' => __('common.egp') . ' ' . number_format($stats['total_revenue']), 'color' => 'text-amber-700 bg-amber-50'],
            ['label' => __('admin.stat_registered_users'), 'value' => $stats['registered_users'], 'color' => 'text-sky bg-sky-50'],
        ];
    @endphp

    @foreach($cards as $card)
        <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5">
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold mb-3 {{ $card['color'] }}">{{ $card['label'] }}</span>
            <p class="font-display text-2xl text-ink" style="font-weight:800;">{{ $card['value'] }}</p>
        </div>
    @endforeach
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="font-display text-lg text-ink" style="font-weight:700;">{{ __('admin.recent_bookings') }}</h2>
    <a href="{{ route('admin.bookings.index') }}" class="text-sm font-semibold text-sky hover:underline">{{ __('admin.view_all') }}</a>
</div>

<div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 overflow-hidden">
    @if($recentBookings->isEmpty())
        <p class="p-8 text-center text-sm text-ink/55">{{ __('admin.no_bookings') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-start text-xs font-semibold uppercase tracking-wide text-ink/40 border-b border-ink/10">
                        <th class="px-5 py-3 text-start">{{ __('admin.booking_ref') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.customer') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.route') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.total') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.booking_status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/5">
                    @foreach($recentBookings as $booking)
                        <tr>
                            <td class="px-5 py-3 font-medium text-ink" dir="ltr">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="hover:text-sky">{{ $booking->booking_number }}</a>
                            </td>
                            <td class="px-5 py-3 text-ink/70">{{ $booking->user->name }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ $booking->flight->departureAirport->city->code }} {{ app()->getLocale() === 'ar' ? '←' : '→' }} {{ $booking->flight->arrivalAirport->city->code }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ __('common.egp') }} {{ number_format($booking->final_price) }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold
                                    {{ $booking->booking_status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($booking->booking_status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ __('booking.status_' . $booking->booking_status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
