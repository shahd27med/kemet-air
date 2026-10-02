@extends('admin.layout')

@section('title', __('admin.all_bookings') . ' — Kemet Air')

@section('admin_content')
<h1 class="font-display text-2xl text-ink mb-6" style="font-weight:700;">{{ __('admin.all_bookings') }}</h1>

<div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 overflow-hidden">
    @if($bookings->isEmpty())
        <p class="p-8 text-center text-sm text-ink/55">{{ __('admin.no_bookings') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs font-semibold uppercase tracking-wide text-ink/40 border-b border-ink/10">
                        <th class="px-5 py-3 text-start">{{ __('admin.booking_ref') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.customer') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.route') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.passengers') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.total') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.payment_status') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.booking_status') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.booked_on') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/5">
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="px-5 py-3 font-medium text-ink" dir="ltr">{{ $booking->booking_number }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ $booking->user->name }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ $booking->flight->departureAirport->city->code }} {{ app()->getLocale() === 'ar' ? '←' : '→' }} {{ $booking->flight->arrivalAirport->city->code }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ $booking->total_passengers }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ __('common.egp') }} {{ number_format($booking->final_price) }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold
                                    {{ $booking->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($booking->payment_status === 'refunded' ? 'bg-ink/10 text-ink/60' : 'bg-amber-50 text-amber-700') }}">
                                    {{ __('booking.payment_' . $booking->payment_status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold
                                    {{ $booking->booking_status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($booking->booking_status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ __('booking.status_' . $booking->booking_status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-ink/60" dir="ltr">{{ $booking->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="font-semibold text-sky hover:underline">{{ __('admin.view') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="mt-6">
    {{ $bookings->links() }}
</div>
@endsection
