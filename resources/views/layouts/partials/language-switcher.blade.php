<div class="flex items-center gap-1 rounded-full bg-white/10 p-1">
    @foreach(config('locales.available') as $code => $locale)
        <a href="{{ route('lang.switch', $code) }}"
           class="px-2.5 py-1 rounded-full text-xs font-bold transition-colors {{ app()->getLocale() === $code ? 'bg-amber text-navy' : 'text-white/70 hover:text-white' }}">
            {{ $locale['short'] }}
        </a>
    @endforeach
</div>
