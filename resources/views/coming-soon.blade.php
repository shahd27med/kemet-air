@extends('layouts.app')

@section('title', ($title ?? 'Coming soon') . ' — Kemet Air')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
    <div class="h-14 w-14 rounded-2xl bg-sky-50 text-sky flex items-center justify-center mx-auto mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
        </svg>
    </div>
    <h1 class="font-display text-2xl text-ink mb-2" style="font-weight:700;">{{ $title ?? 'Coming soon' }}</h1>
    <p class="text-ink/60 leading-relaxed">{{ $description ?? "This part of Kemet Air is being built in the next step." }}</p>
    <a href="{{ route('home') }}" class="inline-block mt-8 rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold px-5 py-2.5 text-sm transition-colors">
        Back to home
    </a>
</div>
@endsection
