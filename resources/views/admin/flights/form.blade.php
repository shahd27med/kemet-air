@extends('admin.layout')

@section('title', ($isEdit ? __('admin.edit_flight') : __('admin.add_flight')) . ' — Kemet Air')

@section('admin_content')
<h1 class="font-display text-2xl text-ink mb-6" style="font-weight:700;">{{ $isEdit ? __('admin.edit_flight') : __('admin.add_flight') }}</h1>

<form method="POST" action="{{ $isEdit ? route('admin.flights.update', $flight) : route('admin.flights.store') }}" class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-6 sm:p-8 space-y-5">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.airline') }}</label>
            <select name="airline_id" required class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                @foreach($airlines as $airline)
                    <option value="{{ $airline->id }}" @selected(old('airline_id', $flight->airline_id) == $airline->id)>{{ $airline->name }}</option>
                @endforeach
            </select>
            @error('airline_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.flight_number') }}</label>
            <input type="text" name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}" required dir="ltr"
                   class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start">
            @error('flight_number') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.departure_airport') }}</label>
            <select name="departure_airport_id" required class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                @foreach($airports as $airport)
                    <option value="{{ $airport->id }}" @selected(old('departure_airport_id', $flight->departure_airport_id) == $airport->id)>{{ $airport->city->name }} ({{ $airport->code }})</option>
                @endforeach
            </select>
            @error('departure_airport_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.arrival_airport') }}</label>
            <select name="arrival_airport_id" required class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                @foreach($airports as $airport)
                    <option value="{{ $airport->id }}" @selected(old('arrival_airport_id', $flight->arrival_airport_id) == $airport->id)>{{ $airport->city->name }} ({{ $airport->code }})</option>
                @endforeach
            </select>
            @error('arrival_airport_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.departure_time') }}</label>
            <input type="datetime-local" name="departure_time"
                   value="{{ old('departure_time', optional($flight->departure_time)->format('Y-m-d\TH:i')) }}" required
                   class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
            @error('departure_time') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.arrival_time') }}</label>
            <input type="datetime-local" name="arrival_time"
                   value="{{ old('arrival_time', optional($flight->arrival_time)->format('Y-m-d\TH:i')) }}" required
                   class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
            @error('arrival_time') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.price') }}</label>
            <input type="number" step="0.01" min="1" name="price" value="{{ old('price', $flight->price) }}" required
                   class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
            @error('price') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.status') }}</label>
            <select name="status" required class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                @foreach(['scheduled', 'delayed', 'cancelled', 'completed'] as $statusOption)
                    <option value="{{ $statusOption }}" @selected(old('status', $flight->status) === $statusOption)>{{ __('admin.status_' . $statusOption) }}</option>
                @endforeach
            </select>
            @error('status') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        @if($isEdit)
            <div>
                <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.total_seats') }}</label>
                <input type="number" value="{{ $flight->total_seats }}" disabled
                       class="w-full rounded-lg border border-ink/10 bg-ice px-3.5 py-2.5 text-sm text-ink/50 cursor-not-allowed">
                <p class="mt-1.5 text-xs text-ink/40">{{ __('admin.total_seats_locked_note') }}</p>
            </div>
        @else
            <div>
                <label class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('admin.total_seats') }}</label>
                <input type="number" name="total_seats" min="4" max="400" step="4" value="{{ old('total_seats', 60) }}" required
                       class="w-full rounded-lg border border-ink/15 px-3.5 py-2.5 text-sm focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                <p class="mt-1.5 text-xs text-ink/40">{{ __('admin.seat_map_note') }}</p>
                @error('total_seats') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold text-sm px-5 py-2.5 transition-colors">
            {{ $isEdit ? __('admin.save_changes') : __('admin.create_flight') }}
        </button>
        <a href="{{ route('admin.flights.index') }}" class="text-sm font-semibold text-ink/60 hover:text-ink">{{ __('booking.back') }}</a>
    </div>
</form>
@endsection
