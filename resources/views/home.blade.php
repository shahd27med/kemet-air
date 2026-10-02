@extends('layouts.app')

@section('title', 'Kemet Air — ' . __('home.slogan'))

@section('content')

    <!-- ============ HERO ============ -->
    <section class="relative overflow-hidden bg-gradient-to-br from-navy via-navy to-sky-700"
        style="background-image: linear-gradient(135deg, #1E3A8A 0%, #1E3A8A 55%, #075985 100%);">

       
  


        <!-- Decorative skyline + flight path (single deliberate motion moment) -->
        <svg class="absolute inset-x-0 bottom-0 w-full h-40 sm:h-48 text-navy-900/40" viewBox="0 0 1200 200"
            preserveAspectRatio="none" aria-hidden="true">
            <path
                d="M0 170 L60 170 L80 110 L100 170 L140 170 L155 130 L170 170 L230 170 L250 90 L270 170 L340 170 L360 140 L380 170 L440 170 L460 60 L480 170 L560 170 L580 120 L600 170 L1200 170 L1200 200 L0 200 Z"
                fill="#0F172A" opacity="0.25" />
        </svg>

        <svg class="absolute top-10 left-0 w-full h-64 opacity-70" viewBox="0 0 1200 260" preserveAspectRatio="none"
            aria-hidden="true">
            <path id="flight-path" d="M-50 220 C 200 40, 500 240, 750 90 S 1150 40, 1300 -10" fill="none" stroke="#F8FAFC"
                stroke-width="1.5" stroke-dasharray="6 10" opacity="0.35" />
            <g>
                <animateMotion dur="14s" repeatCount="indefinite" rotate="auto">
                    <mpath href="#flight-path" />
                </animateMotion>
                <path d="M14 0l-4 4-6-2-2 2 4 3-3 2 .5 2 3-1 2 3 2-1-1-3 4-2z" fill="#F59E0B" transform="scale(1.6)" />
            </g>
        </svg>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-40 sm:pt-20 sm:pb-48">
            <div class="max-w-2xl">
                <div class="mb-8">
                    <x-brand-mark size="lg" />
                </div>

                <h1 class="font-display text-4xl sm:text-5xl leading-[1.1] text-white mb-4" style="font-weight:800;">
                    {{ __('home.slogan') }}
                </h1>

                <p class="text-sky-300 font-semibold text-base sm:text-lg mb-5">
                    {{ __('home.hero_subheading') }}
                </p>

                <p class="text-white/75 text-lg leading-relaxed max-w-xl">
                    {{ __('home.hero_description') }}
                </p>
            </div>
        </div>
    </section>

    <!-- ============ SEARCH CARD (overlaps hero bottom edge) ============ -->
    <section id="search" class="relative -mt-28 sm:-mt-32 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-floating ring-1 ring-ink/5 p-6 sm:p-8">

                <h2 class="font-display text-lg text-ink mb-5" style="font-weight:700;">
                    {{ __('home.find_flight') }}
                </h2>

                <form method="GET" action="{{ route('flights.search') }}" x-data="{ from: '', to: '', tripType: 'oneway', departureDate: '{{ now()->format('Y-m-d') }}' }">

                    <div class="flex items-center gap-5 mb-4 text-sm">

                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" x-model="tripType" value="oneway" class="text-sky focus:ring-sky/40">

                            <span :class="tripType === 'oneway' ? 'text-ink font-semibold' : 'text-ink/50'">
                                {{ __('search.one_way') }}
                            </span>
                        </label>

                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" x-model="tripType" value="roundtrip" class="text-sky focus:ring-sky/40">

                            <span :class="tripType === 'roundtrip' ? 'text-ink font-semibold' : 'text-ink/50'">
                                {{ __('search.round_trip') }}
                            </span>
                        </label>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-3 items-end">

                        <div class="lg:col-span-3">
                            <label for="origin_airport_id"
                                class="block text-xs font-semibold uppercase tracking-wide text-ink/50 mb-1.5">
                                {{ __('search.from') }}
                            </label>

                            <select id="origin_airport_id" name="origin_airport_id" x-model="from" required
                                class="w-full rounded-lg border border-ink/15 bg-ice px-3 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">

                                <option value="" disabled selected>
                                    {{ __('search.departure_city_placeholder') }}
                                </option>

                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}">
                                        {{ $airport->city->name }} ({{ $airport->code }})
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="hidden lg:flex lg:col-span-1 justify-center pb-2.5">
                            <button type="button"
                                @click="const t = from; from = to; to = t;"
                                class="h-9 w-9 rounded-full bg-ice border border-ink/10 flex items-center justify-center text-sky hover:bg-sky-50 transition"
                                aria-label="{{ __('search.swap') }}">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" class="h-4 w-4 rtl-flip">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h18M16.5 3L21 7.5m0 0L16.5 12M21 7.5H3" />

                                </svg>

                            </button>
                        </div>

                        <div class="lg:col-span-3">
                            <label for="destination_airport_id"
                                class="block text-xs font-semibold uppercase tracking-wide text-ink/50 mb-1.5">
                                {{ __('search.to') }}
                            </label>

                            <select id="destination_airport_id" name="destination_airport_id" x-model="to" required
                                class="w-full rounded-lg border border-ink/15 bg-ice px-3 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">

                                <option value="" disabled selected>
                                    {{ __('search.arrival_city_placeholder') }}
                                </option>

                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}">
                                        {{ $airport->city->name }} ({{ $airport->code }})
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="lg:col-span-2">
                            <label for="departure_date"
                                class="block text-xs font-semibold uppercase tracking-wide text-ink/50 mb-1.5">
                                {{ __('search.departure') }}
                            </label>

                            <input type="date" id="departure_date" name="departure_date" x-model="departureDate" required
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full rounded-lg border border-ink/15 bg-ice px-3 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                        </div>

                        <div class="lg:col-span-2" x-show="tripType === 'roundtrip'" x-cloak>
                            <label for="return_date"
                                class="block text-xs font-semibold uppercase tracking-wide text-ink/50 mb-1.5">
                                {{ __('search.return') }}
                            </label>

                            <input type="date" id="return_date" name="return_date"
                                :required="tripType === 'roundtrip'"
                                :min="departureDate || '{{ now()->format('Y-m-d') }}'"
                                class="w-full rounded-lg border border-ink/15 bg-ice px-3 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">
                        </div>

                        <div class="lg:col-span-2">
                            <button type="submit"
                                class="w-full h-[42px] rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold text-sm shadow-sm transition-colors flex items-center justify-center gap-2 px-4 whitespace-nowrap">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" class="h-4 w-4 shrink-0">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />

                                </svg>

                                <span>{{ __('search.search') }}</span>
                            </button>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-3 items-end mt-4">

                        <div class="lg:col-span-3">
                            <label for="passengers_count"
                                class="block text-xs font-semibold uppercase tracking-wide text-ink/50 mb-1.5">
                                {{ __('search.passengers') }}
                            </label>

                            <select id="passengers_count" name="passengers_count"
                                class="w-full rounded-lg border border-ink/15 bg-ice px-3 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">

                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">
                                        {{ trans_choice('search.passenger_count', $i, ['count' => $i]) }}
                                    </option>
                                @endfor

                            </select>
                        </div>

                        <div class="lg:col-span-3">
                            <label for="class_type"
                                class="block text-xs font-semibold uppercase tracking-wide text-ink/50 mb-1.5">
                                {{ __('search.cabin_class') }}
                            </label>

                            <select id="class_type" name="class_type"
                                class="w-full rounded-lg border border-ink/15 bg-ice px-3 py-2.5 text-sm text-ink focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition">

                                <option value="">
                                    {{ __('search.any_class') }}
                                </option>

                                <option value="economy">
                                    {{ __('search.economy') }}
                                </option>

                                <option value="business">
                                    {{ __('search.business') }}
                                </option>

                            </select>
                        </div>

                    </div>

                    <p class="mt-3 text-xs text-ink/40">
                        {{ __('home.discount_note') }}
                    </p>

                </form>
            </div>
        </div>
    </section>

    <!-- ============ POPULAR DESTINATIONS ============ -->
    <section id="destinations" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-sky font-semibold text-sm mb-1.5">
                    {{ __('home.destinations_eyebrow') }}
                </p>

                <h2 class="font-display text-2xl sm:text-3xl text-ink" style="font-weight:700;">
                    {{ __('home.destinations_heading') }}
                </h2>
            </div>
        </div>

        @php
            $destinationMeta = [
                'CAI' => [
                    'image' => 'images/destinations/cairo.jpg',
                    'accent' => 'from-navy to-sky-700',
                    'span' => 'sm:col-span-2 sm:row-span-2',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3L2 19h20L12 3zM7 19l5-10 5 10M2 19l4 3h12l4-3" />',
                ],
                'ASW' => [
                    'image' => 'images/destinations/aswan.jpg',
                    'accent' => 'from-amber-600 to-amber',
                    'span' => '',
                    'icon' => '<path d="M3 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0" /><rect x="10.5" y="6" width="3" height="9" rx="0.5"/><path d="M9.5 6h5" />',
                ],
                'SSH' => [
                    'image' => 'images/destinations/sharm.jpg',
                    'accent' => 'from-sky-700 to-sky',
                    'span' => '',
                    'icon' => '<path d="M2 16c2.5-2.5 5-2.5 7.5 0s5 2.5 7.5 0 5-2.5 7.5 0" /><circle cx="12" cy="8" r="3.2"/>',
                ],
                'HRG' => [
                    'image' => 'images/destinations/hurghada.jpg',
                    'accent' => 'from-sky to-navy',
                    'span' => '',
                    'icon' => '<path d="M4 19h16" /><path d="M6 19V9l9 5-9 3" /><path d="M6 9l1.2-4.2" />',
                ],
                'ALX' => [
                    'image' => 'images/destinations/alexandria.jpg',
                    'accent' => 'from-navy-700 to-sky-700',
                    'span' => '',
                    'icon' => '<path d="M4 20V9l8-5 8 5v11" /><path d="M9 20v-6h6v6" /><path d="M4 20h16" />',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 auto-rows-[190px]">

            @foreach($destinations as $destination)
                @php
                    $code = $destination['city']->code;

                    $meta = $destinationMeta[$code] ?? [
                        'image' => null,
                        'accent' => 'from-navy to-sky',
                        'span' => '',
                        'icon' => ''
                    ];

                    $tagline = __('home.destination_tagline')[$code] ?? '';

                    $destinationImage = !empty($meta['image'])
                        ? asset($meta['image'])
                        : null;
                @endphp

                <a href="{{ route('flights.search', [
                    'origin_airport_id' => $destination['origin_airport_id'],
                    'destination_airport_id' => $destination['destination_airport_id'],
                    'departure_date' => now()->addDay()->toDateString(),
                    'passengers_count' => 1,
                ]) }}"
                    class="group relative rounded-2xl overflow-hidden text-white shadow-card hover:shadow-floating transition-all duration-300 {{ $meta['span'] }} bg-gradient-to-br {{ $meta['accent'] }}"
                    @if($destinationImage)
                        style="background-image: url('{{ $destinationImage }}'); background-size: cover; background-position: center;"
                    @endif>

                    <!-- Dark overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-black/10 group-hover:from-black/80 group-hover:via-black/35 transition-all duration-300">
                    </div>

                    <!-- Decorative icon -->
                    <svg class="absolute -right-4 -bottom-4 h-32 w-32 opacity-20 group-hover:opacity-30 transition-opacity z-[1]"
                        viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1" aria-hidden="true">
                        {!! $meta['icon'] !!}
                    </svg>

                    <div class="relative z-[2] h-full flex flex-col justify-between p-5">
                        <div class="flex items-start justify-between">

                            <!-- Small icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.6" class="h-7 w-7 text-white/90">
                                {!! $meta['icon'] !!}
                            </svg>

                            <!-- Price -->
                            @if($destination['from_price'])
                                <span class="inline-flex items-center rounded-full bg-amber text-navy text-xs font-bold px-2.5 py-1 shadow-sm">
                                    {{ __('home.from_price', [
                                        'price' => number_format($destination['from_price'])
                                    ]) }}
                                </span>
                            @endif

                        </div>

                        <div>
                            <h3 class="font-display text-xl mb-1 drop-shadow-md" style="font-weight:700;">
                                {{ $destination['city']->name }}
                            </h3>

                            <p class="text-sm text-white/85 leading-snug max-w-[22rem] drop-shadow-md">
                                {{ $tagline }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach

        </div>
    </section>

    <!-- ============ WHY KEMET AIR (Interactive Cards) ============ -->
    <section class="bg-slate-50/60 border-y border-ink/5 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-3 gap-8">

                <!-- Feature 1: Instant Confirmation -->
                <div class="group bg-white p-7 rounded-2xl border border-ink/10 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-sky-50 text-sky flex items-center justify-center mb-5 group-hover:bg-sky group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-lg text-ink mb-2" style="font-weight:700;">
                        {{ __('home.feature_1_title') }}
                    </h3>
                    <p class="text-sm text-ink/60 leading-relaxed">
                        {{ __('home.feature_1_body') }}
                    </p>
                </div>

                <!-- Feature 2: Group Discounts -->
                <div class="group bg-white p-7 rounded-2xl border border-ink/10 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5 group-hover:bg-amber group-hover:text-navy transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197M13.5 6a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-lg text-ink mb-2" style="font-weight:700;">
                        {{ __('home.feature_2_title') }}
                    </h3>
                    <p class="text-sm text-ink/60 leading-relaxed">
                        {{ __('home.feature_2_body') }}
                    </p>
                </div>

                <!-- Feature 3: Pick Your Own Seat -->
                <div class="group bg-white p-7 rounded-2xl border border-ink/10 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-navy-50 text-navy flex items-center justify-center mb-5 group-hover:bg-navy group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 3h12a1 1 0 011 1v11a2 2 0 01-2 2H7a2 2 0 01-2-2V4a1 1 0 011-1zM4 17h16M8 21h8" />
                        </svg>
                    </div>
                    <h3 class="font-display text-lg text-ink mb-2" style="font-weight:700;">
                        {{ __('home.feature_3_title') }}
                    </h3>
                    <p class="text-sm text-ink/60 leading-relaxed">
                        {{ __('home.feature_3_body') }}
                    </p>
                </div>

            </div>
        </div>
    </section>

@endsection