@extends('layouts.app')

@section('title', __('auth.register_title') . ' — Kemet Air')

@section('content')
<div class="min-h-[calc(100vh-4rem)] grid lg:grid-cols-2">

    <!-- Decorative brand panel -->
    <div class="hidden lg:flex relative flex-col justify-between bg-navy text-white px-12 py-14 overflow-hidden order-2">
        <svg class="absolute inset-0 h-full w-full opacity-20" viewBox="0 0 500 700" preserveAspectRatio="none" aria-hidden="true">
            <path d="M0 100 Q 90 150 180 105 T 500 120 L 500 0 L 0 0 Z" fill="#0284C7" />
            <path d="M0 60 Q 120 110 260 60 T 500 70 L 500 0 L 0 0 Z" fill="#F59E0B" opacity="0.35" />
        </svg>
        <svg class="absolute bottom-16 start-8 h-28 w-28 text-amber/15 -scale-x-100" viewBox="0 0 48 48" fill="currentColor" aria-hidden="true">
            <path d="M5 37L20.5 10.5 25 16.5 14 37z"/>
            <path d="M15 37L29 6.5 33.5 12.5 23 37z" opacity="0.72"/>
            <path d="M24 37L36.5 4 41 10 30.5 37z" opacity="0.5"/>
        </svg>

        <div class="relative">
            <a href="{{ route('home') }}"><x-brand-mark size="lg" /></a>
        </div>

        <div class="relative max-w-sm">
            <p class="text-sky-300 font-semibold text-sm mb-3 tracking-wide">{{ __('home.slogan') }}</p>
            <h1 class="font-display text-3xl leading-tight mb-4" style="font-weight:700;">{{ __('auth.register_panel_heading') }}</h1>
            <p class="text-white/70 leading-relaxed">
                {{ __('auth.register_panel_body') }}
            </p>
        </div>

        <p class="relative text-xs text-white/40">&copy; {{ date('Y') }} Kemet Air. {{ __('footer.rights_reserved') }}</p>
    </div>

    <!-- Form panel -->
    <div class="flex items-center justify-center px-4 sm:px-6 py-14 bg-ice order-1">
        <div class="w-full max-w-sm">
            <div class="mb-8">
                <h2 class="font-display text-2xl text-ink" style="font-weight:700;">{{ __('auth.register_title') }}</h2>
                <p class="text-sm text-ink/60 mt-1.5">
                    {{ __('auth.already_have_account') }}
                    <a href="{{ route('login') }}" class="text-sky font-semibold hover:underline">{{ __('auth.login_instead') }}</a>
                </p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('auth.full_name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-ink/30 focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition"
                           placeholder="Shahd Ahmed">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('auth.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" dir="ltr"
                           class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-ink/30 focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('auth.phone') }}</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" dir="ltr"
                           class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-ink/30 focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start"
                           placeholder="+20 10 1234 5678">
                    @error('phone')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('auth.password') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" dir="ltr"
                               class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-ink/30 focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-ink/80 mb-1.5">{{ __('auth.confirm_password') }}</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" dir="ltr"
                               class="w-full rounded-lg border border-ink/15 bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-ink/30 focus:border-sky focus:ring-2 focus:ring-sky/30 focus:outline-none transition text-start"
                               placeholder="••••••••">
                    </div>
                </div>
                <p class="text-xs text-ink/40 -mt-3">{{ __('auth.password_hint') }}</p>

                <button type="submit"
                        class="w-full rounded-lg bg-navy hover:bg-navy-700 text-white font-semibold py-2.5 text-sm shadow-sm transition-colors">
                    {{ __('auth.register_button') }}
                </button>
            </form>

            <p class="mt-8 text-xs text-center text-ink/40">
                {{ __('auth.terms_register') }}
            </p>
        </div>
    </div>
</div>
@endsection
