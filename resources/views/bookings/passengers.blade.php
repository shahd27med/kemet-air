@extends('layouts.app')

@section('title', __('booking.passenger_details_heading') . ' — Kemet Air')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @include('bookings.partials.steps', ['current' => 2])
    @include('bookings.partials.flight-summary', ['flight' => $flight, 'draft' => $draft])

    <div class="text-center mb-6">
        <h1 class="font-display text-2xl text-ink mb-1" style="font-weight:700;">{{ __('booking.passenger_details_heading') }}</h1>
        <p class="text-sm text-ink/55 max-w-md mx-auto">{{ __('booking.passenger_details_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('bookings.passengers.store') }}" class="space-y-5">
        @csrf

        @foreach($seats as $index => $seat)
            @php $old = old("passengers.$index", []); @endphp
            <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 sm:p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-display text-base text-ink" style="font-weight:700;">{{ __('booking.passenger_number', ['number' => $index + 1]) }}</h2>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-navy bg-navy/5 rounded-full px-2.5 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-3.5 w-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M6 19V9l6-5 6 5v10" />
                        </svg>
                        {{ __('booking.seat_label', ['seat' => $seat->seat_number]) }}
                    </span>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('booking.full_name') }}</label>
                        <input type="text" name="passengers[{{ $index }}][full_name]" value="{{ $old['full_name'] ?? '' }}" required
                               class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                        @error("passengers.$index.full_name")
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('booking.id_or_passport') }}</label>
                        <input type="text" name="passengers[{{ $index }}][national_id_or_passport]" value="{{ $old['national_id_or_passport'] ?? '' }}" required dir="ltr"
                               class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start">
                        @error("passengers.$index.national_id_or_passport")
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('booking.date_of_birth') }}</label>
                        <input type="date" name="passengers[{{ $index }}][date_of_birth]" value="{{ $old['date_of_birth'] ?? '' }}" required max="{{ now()->subDay()->toDateString() }}"
                               class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                        @error("passengers.$index.date_of_birth")
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('booking.gender') }}</label>
                        <div class="flex items-center gap-5">
                            <label class="flex items-center gap-2 text-sm text-ink/70 cursor-pointer">
                                <input type="radio" name="passengers[{{ $index }}][gender]" value="male" @checked(($old['gender'] ?? '') === 'male') required class="text-sky focus:ring-sky/40">
                                {{ __('booking.male') }}
                            </label>
                            <label class="flex items-center gap-2 text-sm text-ink/70 cursor-pointer">
                                <input type="radio" name="passengers[{{ $index }}][gender]" value="female" @checked(($old['gender'] ?? '') === 'female') required class="text-sky focus:ring-sky/40">
                                {{ __('booking.female') }}
                            </label>
                        </div>
                        @error("passengers.$index.gender")
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('bookings.seats') }}" class="text-sm font-semibold text-ink/60 hover:text-ink">
                {{ __('booking.back') }}
            </a>
            <button type="submit" class="rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold text-sm px-6 py-2.5 transition-colors">
                {{ __('booking.continue') }}
            </button>
        </div>
    </form>
</div>
@endsection
