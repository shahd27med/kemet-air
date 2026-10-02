@extends('layouts.app')

@section('title', __('flights.all_scheduled_heading') . ' — Kemet Air')

@section('content')
<div class="bg-ice min-h-[60vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="text-center mb-8">
            <h1 class="font-display text-2xl sm:text-3xl text-ink mb-2" style="font-weight:700;">{{ __('flights.all_scheduled_heading') }}</h1>
            <p class="text-sm text-ink/55">{{ __('flights.all_scheduled_subtitle') }}</p>
        </div>

        @if($flights->isEmpty())
            <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-10 text-center">
                <div class="h-14 w-14 rounded-2xl bg-sky-50 text-sky flex items-center justify-center mx-auto mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    </svg>
                </div>
                <p class="text-sm text-ink/60 mb-6">{{ __('flights.no_upcoming_available') }}</p>
                <a href="{{ route('home') }}#search" class="rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold px-5 py-2.5 text-sm transition-colors">
                    {{ __('flights.modify_search') }}
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($flights as $result)
                    @include('flights.partials.flight-card', ['result' => $result, 'passengers' => $passengers ?? 1, 'showContext' => true])
                @endforeach
            </div>

            <div class="mt-8">
                {{ $flights->links() }}
            </div>
        @endif
    </div>
</div>
@endsection