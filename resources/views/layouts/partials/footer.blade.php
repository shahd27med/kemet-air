<footer class="bg-navy-900 bg-navy text-white/80 mt-16 print:hidden">
    <div class="h-1 w-full bg-gradient-to-r from-amber via-sky to-amber"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

        <div>
            <div class="mb-3">
                <x-brand-mark size="sm" />
            </div>
            <p class="text-sm leading-relaxed text-white/60 italic">&ldquo;{{ __('footer.tagline') }}&rdquo;</p>
            <p class="text-sm leading-relaxed text-white/60 mt-2">
                {{ __('footer.description') }}
            </p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white mb-4 font-display">{{ __('footer.explore') }}</h3>
            <ul class="space-y-2 text-sm text-white/60">
                <li><a href="{{ route('home') }}" class="hover:text-sky-300 transition-colors">{{ __('nav.home') }}</a></li>
                <li><a href="{{ route('home') }}#search" class="hover:text-sky-300 transition-colors">{{ __('nav.search_flights') }}</a></li>
                <li><a href="{{ route('home') }}#destinations" class="hover:text-sky-300 transition-colors">{{ __('footer.popular_destinations') }}</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white mb-4 font-display">{{ __('footer.account') }}</h3>
            <ul class="space-y-2 text-sm text-white/60">
                @auth
                    <li><a href="{{ route('bookings.index') }}" class="hover:text-sky-300 transition-colors">{{ __('nav.my_bookings') }}</a></li>
                    <li><a href="{{ route('profile.show') }}" class="hover:text-sky-300 transition-colors">{{ __('nav.my_profile') }}</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-sky-300 transition-colors">{{ __('nav.login') }}</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-sky-300 transition-colors">{{ __('nav.register') }}</a></li>
                @endauth
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white mb-4 font-display">{{ __('footer.support') }}</h3>
            <ul class="space-y-2 text-sm text-white/60">
                <li><a href="mailto:support@kemetair.eg" class="hover:text-sky-300 transition-colors" dir="ltr">support@kemetair.eg</a></li>
                <li><a href="tel:+20090001234" class="hover:text-sky-300 transition-colors" dir="ltr">19XXX</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-white/50">
            <p>&copy; {{ date('Y') }} Kemet Air. {{ __('footer.rights_reserved') }}</p>
            <p>{{ __('footer.made_for') }}</p>
        </div>
    </div>
</footer>
