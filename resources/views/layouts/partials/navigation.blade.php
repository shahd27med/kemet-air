<nav x-data="{ mobileOpen: false, profileOpen: false }" class="bg-navy sticky top-0 z-50 shadow-lg shadow-navy-900/10 print:hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo / wordmark -->
            <a href="{{ route('home') }}" class="flex items-center shrink-0 group">
                <x-brand-mark />
            </a>

            <!-- Desktop links -->
            <div class="hidden md:flex items-center gap-1">
                @php
                    $navLink = fn ($active) => $active
                        ? 'px-3 py-2 rounded-md text-sm font-semibold text-white bg-white/10'
                        : 'px-3 py-2 rounded-md text-sm font-medium text-white/80 hover:text-sky-300 hover:bg-white/5 transition-colors';
                @endphp
                <a href="{{ route('home') }}" class="{{ $navLink(request()->routeIs('home')) }}">{{ __('nav.home') }}</a>
                <a href="{{ route('home') }}#search" class="{{ $navLink(false) }}">{{ __('nav.search_flights') }}</a>
                <a href="{{ route('about') }}" class="{{ $navLink(request()->routeIs('about')) }}">
                    {{ app()->getLocale() === 'ar' ? 'من نحن' : 'About' }}
                </a>
                <a href="{{ route('contact') }}" class="{{ $navLink(request()->routeIs('contact')) }}">
                    {{ app()->getLocale() === 'ar' ? 'اتصل بنا' : 'Contact' }}
                </a>

                @auth
                    <a href="{{ route('bookings.index') }}" class="{{ $navLink(request()->routeIs('bookings.*')) }}">{{ __('nav.my_bookings') }}</a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="{{ $navLink(request()->routeIs('admin.*')) }}">
                            {{ __('nav.admin_dashboard') }}
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Right side: language switcher + auth actions -->
            <div class="hidden md:flex items-center gap-3">
                @include('layouts.partials.language-switcher')

                <span class="h-5 w-px bg-white/15"></span>

                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-semibold text-white/90 hover:text-white transition-colors">
                        {{ __('nav.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-semibold text-navy bg-amber hover:bg-amber-600 transition-colors shadow-sm">
                        {{ __('nav.register') }}
                    </a>
                @else
                    <div class="relative" @click.outside="profileOpen = false">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-white/90 hover:bg-white/5 transition-colors">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-sky text-white text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span>{{ Str::of(auth()->user()->name)->before(' ') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-white/60">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-cloak x-show="profileOpen" x-transition
                             class="absolute end-0 mt-2 w-52 rounded-xl bg-white shadow-floating ring-1 ring-black/5 py-1.5 text-sm">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-ink/80 hover:bg-ice hover:text-sky">{{ __('nav.my_profile') }}</a>
                            <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-ink/80 hover:bg-ice hover:text-sky">{{ __('nav.my_bookings') }}</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-ink/80 hover:bg-ice hover:text-sky">{{ __('nav.admin_dashboard') }}</a>
                            @endif
                            <div class="my-1 border-t border-ink/5"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-start px-4 py-2 text-red-600 hover:bg-red-50">{{ __('nav.logout') }}</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile menu toggle -->
            <button @click="mobileOpen = !mobileOpen" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-white/90 hover:bg-white/10" aria-label="{{ __('nav.toggle_navigation') }}">
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
                <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu panel -->
    <div x-cloak x-show="mobileOpen" x-transition class="md:hidden border-t border-white/10 bg-navy">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">{{ __('nav.home') }}</a>
            <a href="{{ route('home') }}#search" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">{{ __('nav.search_flights') }}</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">
                {{ app()->getLocale() === 'ar' ? 'من نحن' : 'About' }}
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">
                {{ app()->getLocale() === 'ar' ? 'اتصل بنا' : 'Contact' }}
            </a>

            @auth
                <a href="{{ route('bookings.index') }}" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">{{ __('nav.my_bookings') }}</a>
                <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">{{ __('nav.my_profile') }}</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">{{ __('nav.admin_dashboard') }}</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-1">
                    @csrf
                    <button type="submit" class="w-full text-start px-3 py-2 rounded-md text-red-300 hover:bg-white/10">{{ __('nav.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-white/90 hover:bg-white/10">{{ __('nav.login') }}</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md font-semibold text-navy bg-amber text-center mt-1">{{ __('nav.register') }}</a>
            @endauth

            <div class="pt-2 mt-2 border-t border-white/10">
                @include('layouts.partials.language-switcher')
            </div>
        </div>
    </div>
</nav>