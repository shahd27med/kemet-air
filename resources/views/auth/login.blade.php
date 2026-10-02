@extends('layouts.app')

@section('title', __('auth.login_title') . ' — Kemet Air')

@section('content')
<div class="min-h-[calc(100vh-4rem)] grid lg:grid-cols-2">

    <!-- Decorative brand panel -->
    <div class="hidden lg:flex relative flex-col justify-between bg-navy text-white px-12 py-14 overflow-hidden">
        <svg class="absolute inset-0 h-full w-full opacity-20" viewBox="0 0 500 700" preserveAspectRatio="none" aria-hidden="true">
            <path d="M0 620 Q 80 560 160 610 T 320 590 T 500 630 L 500 700 L 0 700 Z" fill="#0284C7" />
            <path d="M0 660 Q 100 610 220 655 T 500 660 L 500 700 L 0 700 Z" fill="#F59E0B" opacity="0.35" />
        </svg>
        <svg class="absolute top-14 end-8 h-28 w-28 text-amber/15" viewBox="0 0 48 48" fill="currentColor" aria-hidden="true">
            <path d="M5 37L20.5 10.5 25 16.5 14 37z"/>
            <path d="M15 37L29 6.5 33.5 12.5 23 37z" opacity="0.72"/>
            <path d="M24 37L36.5 4 41 10 30.5 37z" opacity="0.5"/>
        </svg>

        <div class="relative">
            <a href="{{ route('home') }}"><x-brand-mark size="lg" /></a>
        </div>

        <div class="relative max-w-sm">
            <p class="text-sky-300 font-semibold text-sm mb-3 tracking-wide">{{ __('home.slogan') }}</p>
            <h1 class="font-display text-3xl leading-tight mb-4" style="font-weight:700;">{{ __('auth.login_panel_heading') }}</h1>
            <p class="text-white/70 leading-relaxed">
                {{ __('auth.login_panel_body') }}
            </p>
        </div>

        <p class="relative text-xs text-white/40">&copy; {{ date('Y') }} Kemet Air. {{ __('footer.rights_reserved') }}</p>
    </div>

    <!-- Form panel -->
    <div class="flex items-center justify-center px-4 sm:px-6 py-14 bg-ice">
        <div class="w-full max-w-sm">
            <div class="mb-8">
                <h2 class="font-display text-2xl text-ink" style="font-weight:700;">{{ __('auth.login_title') }}</h2>
                <p class="text-sm text-ink/60 mt-1.5">
                    {{ __('auth.new_to_kemet') }}
                    <a href="{{ route('register') }}" class="text-sky font-semibold hover:underline">{{ __('auth.create_account_link') }}</a>
                </p>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('auth.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" dir="ltr"
                           class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-ink/30 focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-ink/80">{{ __('auth.password') }}</label>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password" dir="ltr"
                           class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-ink/30 focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-ink/30 text-sky focus:ring-sky/40">
                    <label for="remember" class="ms-2 text-sm text-ink/70">{{ __('auth.remember_me') }}</label>
                </div>

                <button type="submit"
                        class="w-full rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold py-2.5 text-sm shadow-sm transition-colors">
                    {{ __('auth.login_button') }}
                </button>
            </form>

            <p class="mt-8 text-xs text-center text-ink/40">
                {{ __('auth.terms_login') }}
            </p>
        </div>
    </div>
</div>
@endsection
