@extends('layouts.app')

@section('title', __('profile.heading') . ' — Kemet Air')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-display text-2xl text-ink mb-6" style="font-weight:700;">{{ __('profile.heading') }}</h1>

    <!-- Account details -->
    <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-6 sm:p-8 mb-8">
        <div class="flex items-center gap-4 mb-6">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-sky text-white text-xl font-bold shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div>
                <p class="font-display text-lg text-ink" style="font-weight:700;">{{ $user->name }}</p>
                <p class="text-sm text-ink/50">{{ __('profile.member_since', ['date' => $user->created_at->translatedFormat('F Y')]) }}</p>
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-5 pt-5 border-t border-ink/10 text-sm">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-1">{{ __('profile.name') }}</p>
                <p class="text-ink">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-1">{{ __('profile.email') }}</p>
                <p class="text-ink" dir="ltr">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-1">{{ __('profile.phone') }}</p>
                <p class="text-ink" dir="ltr">{{ $user->phone ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Recent bookings -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-lg text-ink" style="font-weight:700;">{{ __('profile.recent_bookings') }}</h2>
        @if($recentBookings->isNotEmpty())
            <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-sky hover:underline">{{ __('profile.view_all_bookings') }}</a>
        @endif
    </div>

    @if($recentBookings->isEmpty())
        <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-8 text-center text-sm text-ink/55">
            {{ __('profile.no_bookings_yet') }}
        </div>
    @else
        <div class="space-y-3">
            @foreach($recentBookings as $booking)
                <a href="{{ route('bookings.show', $booking) }}" class="flex items-center justify-between bg-white rounded-xl shadow-card ring-1 ring-ink/5 p-4 hover:shadow-floating transition-shadow">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-ink">{{ $booking->flight->departureAirport->city->name }}</span>
                        <span class="text-ink/25">{{ app()->getLocale() === 'ar' ? '←' : '→' }}</span>
                        <span class="text-sm font-semibold text-ink">{{ $booking->flight->arrivalAirport->city->name }}</span>
                        <span class="text-xs text-ink/40">&middot; {{ $booking->flight->departure_time->translatedFormat('d M Y') }}</span>
                    </div>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold
                        {{ $booking->booking_status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($booking->booking_status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                        {{ __('booking.status_' . $booking->booking_status) }}
                    </span>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
