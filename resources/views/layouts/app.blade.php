<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('locales.available.' . app()->getLocale() . '.dir', 'ltr') }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kemet Air — Your Journey, Our Sky.')</title>
    <meta name="description" content="@yield('meta_description', 'Book domestic flights across Egypt — Cairo, Alexandria, Luxor, Aswan, Sharm El-Sheikh and Hurghada — in minutes.')">

    {{--
        Fonts: Cairo (a deliberate nod to the product's home city) is used
        for display type in BOTH languages — it was designed with Arabic +
        Latin glyphs together, so headings never need to switch. Body text
        uses Inter for English and Tajawal for Arabic, since Inter has no
        Arabic glyphs at all.
    --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Inter:wght@400;500;600;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN, no build step required) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { DEFAULT: '#1E3A8A', 50: '#EEF2FB', 100: '#DCE4F5', 700: '#1B3475', 900: '#152a5c' },
                        sky: { DEFAULT: '#0284C7', 50: '#F0F9FF', 100: '#E0F2FE' },
                        amber: { DEFAULT: '#F59E0B', 50: '#FFFBEB', 600: '#D98A08' },
                        ice: '#F8FAFC',
                        ink: '#0F172A',
                    },
                    fontFamily: {
                        display: ['Cairo', 'ui-sans-serif', 'sans-serif'],
                        sans: @if(app()->getLocale() === 'ar') ['Tajawal', 'Cairo', 'ui-sans-serif', 'sans-serif'] @else ['Inter', 'ui-sans-serif', 'sans-serif'] @endif,
                    },
                    boxShadow: {
                        card: '0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 24px rgba(15, 23, 42, 0.06)',
                        floating: '0 20px 45px rgba(15, 23, 42, 0.18)',
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js: powers the mobile menu and small interactive bits, no page reloads needed -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

    <style>
        body { font-family: @if(app()->getLocale() === 'ar') 'Tajawal', 'Cairo', ui-sans-serif, sans-serif @else 'Inter', ui-sans-serif, sans-serif @endif; background-color: #F8FAFC; color: #0F172A; }
        .font-display { font-family: 'Cairo', ui-sans-serif, sans-serif; }
        [x-cloak] { display: none !important; }
        /* RTL: flip icons that visually encode a left/right direction (arrows, chevrons) */
        [dir="rtl"] .rtl-flip { transform: scaleX(-1); }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased">

    @include('layouts.partials.navigation')

    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <x-alert type="success" :message="session('success')" />
            <x-alert type="error" :message="session('error')" />
            <x-alert type="warning" :message="session('warning')" />

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700" role="alert">
                    <p class="font-semibold mb-1">{{ app()->getLocale() === 'ar' ? 'يرجى تصحيح ما يلي:' : 'Please fix the following:' }}</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
