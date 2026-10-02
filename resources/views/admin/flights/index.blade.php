@extends('admin.layout')

@section('title', __('admin.manage_flights') . ' — Kemet Air')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-display text-2xl text-ink" style="font-weight:700;">{{ __('admin.manage_flights') }}</h1>
    <a href="{{ route('admin.flights.create') }}" class="rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold text-sm px-4 py-2.5 transition-colors flex items-center gap-1.5">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        {{ __('admin.add_flight') }}
    </a>
</div>

<div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 overflow-hidden">
    @if($flights->isEmpty())
        <p class="p-8 text-center text-sm text-ink/55">{{ __('admin.no_flights') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs font-semibold uppercase tracking-wide text-ink/40 border-b border-ink/10">
                        <th class="px-5 py-3 text-start">{{ __('admin.flight_number') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.airline') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.route') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.departure_time') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.price') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.available_seats') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.status') }}</th>
                        <th class="px-5 py-3 text-start">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/5">
                    @foreach($flights as $flight)
                        <tr>
                            <td class="px-5 py-3 font-medium text-ink" dir="ltr">{{ $flight->flight_number }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ $flight->airline->name }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ $flight->departureAirport->city->code }} {{ app()->getLocale() === 'ar' ? '←' : '→' }} {{ $flight->arrivalAirport->city->code }}</td>
                            <td class="px-5 py-3 text-ink/70" dir="ltr">{{ $flight->departure_time->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ __('common.egp') }} {{ number_format($flight->price) }}</td>
                            <td class="px-5 py-3 text-ink/70">{{ $flight->available_seats }} / {{ $flight->total_seats }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold
                                    {{ match($flight->status) {
                                        'scheduled' => 'bg-sky-50 text-sky',
                                        'delayed' => 'bg-amber-50 text-amber-700',
                                        'cancelled' => 'bg-red-50 text-red-700',
                                        'completed' => 'bg-ink/10 text-ink/60',
                                        default => 'bg-ink/10 text-ink/60',
                                    } }}">
                                    {{ __('admin.status_' . $flight->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.flights.edit', $flight) }}" class="font-semibold text-sky hover:underline">{{ __('admin.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.flights.destroy', $flight) }}" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-red-600 hover:underline">{{ __('admin.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="mt-6">
    {{ $flights->links() }}
</div>
@endsection
