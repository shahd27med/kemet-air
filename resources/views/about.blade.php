@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'من نحن — Kemet Air' : 'About Us — Kemet Air')

@section('content')

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="relative min-h-[520px] flex items-center overflow-hidden">

        {{-- Background Image --}}
        <img
            src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=2000&q=85"
            alt="Kemet Air"
            class="absolute inset-0 w-full h-full object-cover"
        >

        {{-- Dark blue overlay --}}
        <div class="absolute inset-0 bg-gradient-to-r from-navy via-navy/85 to-navy/35"></div>

        {{-- Subtle bottom fade --}}
        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-navy/70 to-transparent"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-24">

            <div class="max-w-2xl">

                <p class="text-amber font-bold text-sm uppercase tracking-[0.2em] mb-5">
                    {{ app()->getLocale() === 'ar' ? 'من نحن' : 'ABOUT KEMET AIR' }}
                </p>

                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl text-white font-extrabold leading-tight mb-6">
                    {{ app()->getLocale() === 'ar'
                        ? 'نربط مدن مصر برحلة أسهل'
                        : 'Connecting Egypt, one journey at a time.' }}
                </h1>

                <p class="text-white/80 text-base sm:text-lg leading-relaxed max-w-xl">
                    {{ app()->getLocale() === 'ar'
                        ? 'كيميت إير هي منصة لحجز الرحلات الجوية الداخلية، صُممت لتجعل البحث عن رحلتك وحجز مقعدك تجربة بسيطة وسريعة.'
                        : 'Kemet Air is a domestic flight booking platform designed to make finding your flight and booking your seat simple, clear and convenient.' }}
                </p>

                <div class="mt-8">
                    <a
                        href="{{ route('home') }}#search"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold text-sm transition-all duration-300"
                    >
                        {{ app()->getLocale() === 'ar' ? 'ابحث عن رحلة' : 'Search Flights' }}

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14m-6-6 6 6-6 6"
                            />
                        </svg>
                    </a>
                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        OUR STORY
    ========================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

            {{-- Image --}}
            <div class="lg:col-span-6">

                <div class="relative overflow-hidden rounded-2xl shadow-floating">

                    <img
                        src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1200&q=85"
                        alt="Kemet Air Journey"
                        class="w-full h-[380px] sm:h-[440px] object-cover"
                    >

                    <div class="absolute inset-0 bg-gradient-to-t from-navy/45 via-transparent to-transparent"></div>

                    <div class="absolute bottom-5 left-5 right-5">

                        <div class="bg-white/95 backdrop-blur-sm rounded-xl px-5 py-4 shadow-lg">

                            <p class="text-xs uppercase tracking-widest font-bold text-sky mb-1">
                                {{ app()->getLocale() === 'ar' ? 'رحلتنا' : 'OUR JOURNEY' }}
                            </p>

                            <p class="font-display text-lg font-bold text-navy">
                                {{ app()->getLocale() === 'ar'
                                    ? 'السفر داخل مصر بطريقة أبسط'
                                    : 'A simpler way to travel across Egypt' }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Story --}}
            <div class="lg:col-span-6">

                <p class="text-sky font-semibold text-sm uppercase tracking-widest mb-3">
                    {{ app()->getLocale() === 'ar' ? 'قصتنا' : 'OUR STORY' }}
                </p>

                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-navy leading-tight mb-6">
                    {{ app()->getLocale() === 'ar'
                        ? 'بدأت الفكرة من تجربة سفر أبسط'
                        : 'It started with a simple idea: make flying easier.' }}
                </h2>

                <div class="space-y-4 text-ink/65 text-base leading-relaxed">

                    <p>
                        {{ app()->getLocale() === 'ar'
                            ? 'تم تصميم كيميت إير لتقديم تجربة حجز رقمية واضحة للمسافرين الراغبين في التنقل بين أهم المدن والوجهات داخل مصر.'
                            : 'Kemet Air was designed to provide a clear digital booking experience for travelers moving between Egypt’s major cities and destinations.' }}
                    </p>

                    <p>
                        {{ app()->getLocale() === 'ar'
                            ? 'بدلًا من الخطوات المعقدة، نركز على عرض الرحلات والأسعار والمقاعد المتاحة بطريقة مباشرة تساعد المسافر على اتخاذ قراره بسرعة.'
                            : 'Instead of complicated booking steps, we focus on presenting flights, fares and available seats in a straightforward way that helps travelers book with confidence.' }}
                    </p>

                </div>


                {{-- Small stats --}}
                <div class="grid grid-cols-3 gap-5 mt-9 pt-8 border-t border-ink/10">

                    <div>
                        <p class="font-display text-2xl sm:text-3xl font-extrabold text-navy">
                            6+
                        </p>

                        <p class="text-xs sm:text-sm text-ink/50 mt-1">
                            {{ app()->getLocale() === 'ar' ? 'وجهات' : 'Destinations' }}
                        </p>
                    </div>

                    <div>
                        <p class="font-display text-2xl sm:text-3xl font-extrabold text-navy">
                            24/7
                        </p>

                        <p class="text-xs sm:text-sm text-ink/50 mt-1">
                            {{ app()->getLocale() === 'ar' ? 'حجز إلكتروني' : 'Online Access' }}
                        </p>
                    </div>

                    <div>
                        <p class="font-display text-2xl sm:text-3xl font-extrabold text-navy">
                            100%
                        </p>

                        <p class="text-xs sm:text-sm text-ink/50 mt-1">
                            {{ app()->getLocale() === 'ar' ? 'رقمي' : 'Digital' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        VISION + MISSION
    ========================================================== --}}
    <section class="bg-slate-50 border-y border-ink/5">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

            <div class="grid md:grid-cols-2 gap-6 lg:gap-8">

                {{-- Vision --}}
                <div class="bg-white border border-ink/10 rounded-2xl p-8 sm:p-10">

                    <div class="flex items-center gap-4 mb-6">

                        <div class="w-11 h-11 rounded-lg bg-sky-50 text-sky flex items-center justify-center">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                class="w-6 h-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z"
                                />
                                <circle cx="12" cy="12" r="2.5"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-widest text-sky font-bold">
                                {{ app()->getLocale() === 'ar' ? 'رؤيتنا' : 'OUR VISION' }}
                            </p>
                        </div>

                    </div>

                    <h3 class="font-display text-2xl font-bold text-navy mb-4">
                        {{ app()->getLocale() === 'ar'
                            ? 'أن تصبح الرحلات الداخلية أكثر سهولة للجميع'
                            : 'Making domestic travel easier for everyone.' }}
                    </h3>

                    <p class="text-ink/60 leading-relaxed">
                        {{ app()->getLocale() === 'ar'
                            ? 'نطمح إلى بناء تجربة سفر رقمية واضحة وموثوقة تربط المسافرين بالوجهات المصرية المختلفة.'
                            : 'We aim to build a clear and reliable digital travel experience connecting passengers with destinations across Egypt.' }}
                    </p>

                </div>


                {{-- Mission --}}
                <div class="bg-navy rounded-2xl p-8 sm:p-10 text-white">

                    <div class="flex items-center gap-4 mb-6">

                        <div class="w-11 h-11 rounded-lg bg-amber text-navy flex items-center justify-center">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                class="w-6 h-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                />

                            </svg>

                        </div>

                        <p class="text-xs uppercase tracking-widest text-amber font-bold">
                            {{ app()->getLocale() === 'ar' ? 'مهمتنا' : 'OUR MISSION' }}
                        </p>

                    </div>

                    <h3 class="font-display text-2xl font-bold mb-4">
                        {{ app()->getLocale() === 'ar'
                            ? 'تجربة حجز واضحة من البداية حتى الإقلاع'
                            : 'A clear booking experience from search to takeoff.' }}
                    </h3>

                    <p class="text-white/65 leading-relaxed">
                        {{ app()->getLocale() === 'ar'
                            ? 'نركز على البساطة والوضوح في كل خطوة، بداية من البحث عن الرحلة وحتى اختيار المقعد والحصول على الحجز.'
                            : 'We focus on simplicity and clarity at every step, from finding a flight to selecting a seat and completing a booking.' }}
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        EXPLORE EGYPT
    ========================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-8">

            <div>

                <p class="text-sky font-semibold text-sm uppercase tracking-widest mb-2">
                    {{ app()->getLocale() === 'ar' ? 'وجهاتنا' : 'OUR DESTINATIONS' }}
                </p>

                <h2 class="font-display text-3xl sm:text-4xl text-navy font-extrabold">
                    {{ app()->getLocale() === 'ar'
                        ? 'اكتشف مصر مع كيميت إير'
                        : 'Explore Egypt with Kemet Air' }}
                </h2>

            </div>

            <p class="text-ink/55 text-sm max-w-sm sm:text-right">
                {{ app()->getLocale() === 'ar'
                    ? 'من القاهرة إلى الجنوب والساحل، اكتشف مجموعة من الوجهات داخل مصر.'
                    : 'From Cairo to the south and the coast, discover destinations across Egypt.' }}
            </p>

        </div>


        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

            {{-- Cairo --}}
            <div class="group relative h-64 rounded-xl overflow-hidden">

                <img
                    src="{{ asset('images/destinations/cairo.jpg') }}"
                    alt="Cairo"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="text-white font-bold text-lg">Cairo</p>
                </div>

            </div>


            {{-- Alexandria --}}
            <div class="group relative h-64 rounded-xl overflow-hidden">

                <img
                    src="{{ asset('images/destinations/alexandria.jpg') }}"
                    alt="Alexandria"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="text-white font-bold text-lg">Alexandria</p>
                </div>

            </div>


            {{-- Luxor --}}
            <div class="group relative h-64 rounded-xl overflow-hidden">

                <img
                    src="{{ asset('images/destinations/luxor.jpg') }}"
                    alt="Luxor"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="text-white font-bold text-lg">Luxor</p>
                </div>

            </div>


            {{-- Aswan --}}
            <div class="group relative h-64 rounded-xl overflow-hidden">

                <img
                    src="{{ asset('images/destinations/aswan.jpg') }}"
                    alt="Aswan"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="text-white font-bold text-lg">Aswan</p>
                </div>

            </div>


            {{-- Sharm --}}
            <div class="group relative h-64 rounded-xl overflow-hidden">

                <img
                    src="{{ asset('images/destinations/sharm.jpg') }}"
                    alt="Sharm El-Sheikh"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="text-white font-bold text-lg">Sharm El-Sheikh</p>
                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        WHY KEMET AIR
    ========================================================== --}}
    <section class="bg-slate-50 border-y border-ink/5">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

            <div class="max-w-2xl mb-12">

                <p class="text-sky font-semibold text-sm uppercase tracking-widest mb-2">
                    {{ app()->getLocale() === 'ar' ? 'لماذا كيميت إير؟' : 'WHY KEMET AIR' }}
                </p>

                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-navy mb-4">
                    {{ app()->getLocale() === 'ar'
                        ? 'كل ما تحتاجه في تجربة حجز واحدة'
                        : 'Everything you need in one booking experience' }}
                </h2>

                <p class="text-ink/60 leading-relaxed">
                    {{ app()->getLocale() === 'ar'
                        ? 'صممنا النظام ليكون واضحًا وسهل الاستخدام من أول بحث وحتى إتمام الحجز.'
                        : 'The platform is designed to stay simple and easy to use from the first search to the final booking.' }}
                </p>

            </div>


            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Feature 1 --}}
                <div class="bg-white border border-ink/10 rounded-xl p-6">

                    <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky flex items-center justify-center mb-5">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                    <h3 class="font-display font-bold text-navy text-lg mb-2">
                        {{ app()->getLocale() === 'ar' ? 'حجز بسيط' : 'Simple Booking' }}
                    </h3>

                    <p class="text-sm text-ink/55 leading-relaxed">
                        {{ app()->getLocale() === 'ar'
                            ? 'ابحث عن الرحلة المناسبة وأكمل عملية الحجز بخطوات واضحة.'
                            : 'Find the right flight and complete your booking through clear, simple steps.' }}
                    </p>

                </div>


                {{-- Feature 2 --}}
                <div class="bg-white border border-ink/10 rounded-xl p-6">

                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-5">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                    <h3 class="font-display font-bold text-navy text-lg mb-2">
                        {{ app()->getLocale() === 'ar' ? 'تجربة سريعة' : 'Fast Experience' }}
                    </h3>

                    <p class="text-sm text-ink/55 leading-relaxed">
                        {{ app()->getLocale() === 'ar'
                            ? 'واجهة مباشرة تساعدك على الوصول إلى المعلومات التي تحتاجها بسرعة.'
                            : 'A straightforward interface that helps you reach the information you need quickly.' }}
                    </p>

                </div>


                {{-- Feature 3 --}}
                <div class="bg-white border border-ink/10 rounded-xl p-6">

                    <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky flex items-center justify-center mb-5">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                            />
                        </svg>

                    </div>

                    <h3 class="font-display font-bold text-navy text-lg mb-2">
                        {{ app()->getLocale() === 'ar' ? 'اختيار المقعد' : 'Seat Selection' }}
                    </h3>

                    <p class="text-sm text-ink/55 leading-relaxed">
                        {{ app()->getLocale() === 'ar'
                            ? 'اختر المقعد المناسب لك ضمن خطوات الحجز.'
                            : 'Choose the seat that works best for you as part of the booking process.' }}
                    </p>

                </div>


                {{-- Feature 4 --}}
                <div class="bg-white border border-ink/10 rounded-xl p-6">

                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-5">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                            />
                        </svg>

                    </div>

                    <h3 class="font-display font-bold text-navy text-lg mb-2">
                        {{ app()->getLocale() === 'ar' ? 'دعم العملاء' : 'Customer Support' }}
                    </h3>

                    <p class="text-sm text-ink/55 leading-relaxed">
                        {{ app()->getLocale() === 'ar'
                            ? 'نظام مصمم ليجعل الوصول إلى معلومات الحجز والمساعدة أكثر سهولة.'
                            : 'A system designed to make booking information and assistance easier to access.' }}
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        FINAL CTA
    ========================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="relative overflow-hidden rounded-2xl bg-navy px-8 py-12 sm:px-12 sm:py-14">

            {{-- Decorative elements --}}
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full border border-white/10"></div>
            <div class="absolute -right-10 -top-10 w-44 h-44 rounded-full border border-white/10"></div>

            <div class="relative z-10 max-w-2xl">

                <p class="text-amber text-sm font-bold uppercase tracking-widest mb-3">
                    {{ app()->getLocale() === 'ar' ? 'رحلتك تبدأ هنا' : 'YOUR JOURNEY STARTS HERE' }}
                </p>

                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white leading-tight mb-4">
                    {{ app()->getLocale() === 'ar'
                        ? 'مستعد لاكتشاف وجهتك القادمة؟'
                        : 'Ready to discover your next destination?' }}
                </h2>

                <p class="text-white/65 leading-relaxed max-w-xl mb-7">
                    {{ app()->getLocale() === 'ar'
                        ? 'ابحث عن رحلتك القادمة واستكشف الوجهات التي يمكنك الوصول إليها مع كيميت إير.'
                        : 'Search for your next flight and explore the destinations available with Kemet Air.' }}
                </p>

                <a
                    href="{{ route('home') }}#search"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold text-sm transition-all duration-300"
                >
                    {{ app()->getLocale() === 'ar' ? 'احجز رحلتك' : 'Book Your Flight' }}

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        class="w-4 h-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 12h14m-6-6 6 6-6 6"
                        />
                    </svg>

                </a>

            </div>

        </div>

    </section>

@endsection