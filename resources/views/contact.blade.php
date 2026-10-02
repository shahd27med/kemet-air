@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تواصل معنا — Kemet Air' : 'Contact Us — Kemet Air')

@section('content')

    {{-- =========================================================
        CONTACT HERO
    ========================================================== --}}
    <section class="relative overflow-hidden bg-navy text-white">

        {{-- Background decoration --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-sky/20 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-32 w-96 h-96 rounded-full bg-amber/15 blur-3xl"></div>

            <div class="absolute top-20 left-10 w-32 h-32 border border-white/10 rounded-full"></div>
            <div class="absolute top-32 right-24 w-20 h-20 border border-white/10 rounded-full"></div>

            <svg class="absolute right-0 top-0 w-full h-full opacity-10"
                 viewBox="0 0 1200 400"
                 fill="none"
                 xmlns="http://www.w3.org/2000/svg">

                <path d="M-50 300C180 80 400 380 650 150C850 -30 1030 100 1250 40"
                      stroke="white"
                      stroke-width="2"
                      stroke-dasharray="8 10"/>

                <path d="M-100 380C170 150 380 440 690 230C900 90 1050 200 1300 100"
                      stroke="white"
                      stroke-width="2"
                      stroke-dasharray="8 10"/>
            </svg>
        </div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24">

            <div class="max-w-3xl">

                {{-- Small label --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                            bg-white/10 border border-white/10
                            text-amber font-bold text-xs sm:text-sm
                            uppercase tracking-widest mb-6">

                    <span class="w-2 h-2 rounded-full bg-amber"></span>

                    {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                </div>

                {{-- Main title --}}
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl
                           font-extrabold tracking-tight leading-tight mb-6">

                    {{ app()->getLocale() === 'ar'
                        ? 'نحن هنا لمساعدتك في كل خطوة'
                        : 'We are here to help you every step of the way' }}
                </h1>

                {{-- Description --}}
                <p class="text-white/70 text-base sm:text-lg lg:text-xl
                          max-w-2xl leading-relaxed">

                    {{ app()->getLocale() === 'ar'
                        ? 'لديك استفسار عن الحجز أو الرحلات؟ تواصل معنا وسيساعدك فريق Kemet Air في الحصول على الإجابة التي تحتاجها.'
                        : 'Have a question about your booking or flights? Get in touch with Kemet Air and our team will be happy to assist you.' }}
                </p>

            </div>
        </div>
    </section>


    {{-- =========================================================
        CONTACT INFORMATION + FORM
    ========================================================== --}}
    <section class="bg-ice/40 py-16 sm:py-20">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-start">

                {{-- =================================================
                    LEFT SIDE - CONTACT INFORMATION
                ================================================== --}}
                <div class="lg:col-span-5">

                    <div class="mb-8">

                        <span class="text-sky font-bold text-sm uppercase tracking-widest">
                            {{ app()->getLocale() === 'ar'
                                ? 'ابقَ على تواصل'
                                : 'Stay Connected' }}
                        </span>

                        <h2 class="font-display text-3xl sm:text-4xl
                                   font-extrabold text-navy mt-3 mb-4">

                            {{ app()->getLocale() === 'ar'
                                ? 'كيف يمكننا مساعدتك؟'
                                : 'How can we help?' }}
                        </h2>

                        <p class="text-ink/65 leading-relaxed">
                            {{ app()->getLocale() === 'ar'
                                ? 'فريقنا جاهز لاستقبال استفساراتك ومساعدتك في أي مشكلة متعلقة بالحجز أو الرحلات.'
                                : 'Our team is ready to answer your questions and assist with anything related to your booking or flights.' }}
                        </p>

                    </div>


                    {{-- Email Card --}}
                    <div class="bg-white rounded-2xl border border-ink/10
                                shadow-card p-5 mb-4
                                hover:shadow-floating transition-all duration-300">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl
                                        bg-sky/10 text-sky
                                        flex items-center justify-center shrink-0">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>

                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-ink/45 mb-1">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                                </p>

                                <p class="font-semibold text-navy">
                                    support@kemetair.com
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- Phone Card --}}
                    <div class="bg-white rounded-2xl border border-ink/10
                                shadow-card p-5 mb-4
                                hover:shadow-floating transition-all duration-300">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl
                                        bg-amber/10 text-amber
                                        flex items-center justify-center shrink-0">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515l.57 2.28a2 2 0 01-.55 1.92L8.73 9.73a16.02 16.02 0 006.54 6.54l1.015-1.015a2 2 0 011.92-.55l2.28.57A2 2 0 0122 17.22V19a2 2 0 01-2 2h-1C10.163 21 3 13.837 3 5z"/>
                                </svg>

                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-ink/45 mb-1">
                                    {{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}
                                </p>

                                <p class="font-semibold text-navy">
                                    +20 100 000 0000
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- Support Card --}}
                    <div class="bg-white rounded-2xl border border-ink/10
                                shadow-card p-5
                                hover:shadow-floating transition-all duration-300">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl
                                        bg-navy/10 text-navy
                                        flex items-center justify-center shrink-0">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M18.364 5.636a9 9 0 010 12.728M15.536 8.464a5 5 0 010 7.072M12 9v6m0 0a3 3 0 100-6m0 6a3 3 0 110-6"/>
                                </svg>

                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-ink/45 mb-1">
                                    {{ app()->getLocale() === 'ar' ? 'خدمة العملاء' : 'Customer Support' }}
                                </p>

                                <p class="font-semibold text-navy">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'متاحون على مدار الساعة'
                                        : 'Available 24/7' }}
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- Small note --}}
                    <div class="mt-6 flex gap-3 items-start">

                        <div class="mt-1 text-amber shrink-0">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                            </svg>

                        </div>

                        <p class="text-sm text-ink/55 leading-relaxed">
                            {{ app()->getLocale() === 'ar'
                                ? 'سنحاول الرد على رسالتك في أقرب وقت ممكن.'
                                : 'We will do our best to respond to your message as soon as possible.' }}
                        </p>

                    </div>

                </div>


                {{-- =================================================
                    RIGHT SIDE - CONTACT FORM
                ================================================== --}}
                <div class="lg:col-span-7">

                    <div class="bg-white rounded-3xl
                                border border-ink/10
                                shadow-floating
                                p-6 sm:p-8 lg:p-10">

                        {{-- Form heading --}}
                        <div class="mb-8">

                            <h2 class="font-display text-2xl sm:text-3xl
                                       font-extrabold text-navy mb-2">

                                {{ app()->getLocale() === 'ar'
                                    ? 'أرسل لنا رسالة'
                                    : 'Send us a message' }}
                            </h2>

                            <p class="text-ink/60 text-sm sm:text-base">
                                {{ app()->getLocale() === 'ar'
                                    ? 'املأ البيانات التالية وسيتواصل معك فريقنا.'
                                    : 'Fill out the form below and our team will get back to you.' }}
                            </p>

                        </div>


                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="mb-6 flex items-start gap-3
                                        rounded-xl border border-green-200
                                        bg-green-50 px-4 py-3.5
                                        text-green-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 mt-0.5 shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M5 13l4 4L19 7"/>
                                </svg>

                                <p class="text-sm font-semibold">
                                    {{ session('success') }}
                                </p>

                            </div>
                        @endif


                        {{-- Validation Errors --}}
                        @if($errors->any())
                            <div class="mb-6 rounded-xl border border-red-200
                                        bg-red-50 px-4 py-3.5 text-red-700">

                                <p class="text-sm font-bold mb-2">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'يرجى مراجعة البيانات التالية:'
                                        : 'Please check the following:' }}
                                </p>

                                <ul class="text-sm space-y-1 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>

                            </div>
                        @endif


                        <form action="{{ route('contact.send') }}"
                              method="POST"
                              class="space-y-6">

                            @csrf


                            {{-- Name + Email --}}
                            <div class="grid sm:grid-cols-2 gap-5">

                                {{-- Name --}}
                                <div>

                                    <label for="name"
                                           class="block text-sm font-bold text-navy mb-2">

                                        {{ app()->getLocale() === 'ar'
                                            ? 'الاسم بالكامل'
                                            : 'Full Name' }}

                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب اسمك' : 'Enter your name' }}"
                                        class="w-full rounded-xl
                                               border border-ink/10
                                               bg-ice/50
                                               px-4 py-3.5
                                               text-sm text-ink
                                               placeholder:text-ink/35
                                               focus:border-sky
                                               focus:ring-4 focus:ring-sky/10
                                               focus:outline-none
                                               transition">

                                </div>


                                {{-- Email --}}
                                <div>

                                    <label for="email"
                                           class="block text-sm font-bold text-navy mb-2">

                                        {{ app()->getLocale() === 'ar'
                                            ? 'البريد الإلكتروني'
                                            : 'Email Address' }}

                                    </label>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        placeholder="example@email.com"
                                        class="w-full rounded-xl
                                               border border-ink/10
                                               bg-ice/50
                                               px-4 py-3.5
                                               text-sm text-ink
                                               placeholder:text-ink/35
                                               focus:border-sky
                                               focus:ring-4 focus:ring-sky/10
                                               focus:outline-none
                                               transition">

                                </div>

                            </div>


                            {{-- Subject --}}
                            <div>

                                <label for="subject"
                                       class="block text-sm font-bold text-navy mb-2">

                                    {{ app()->getLocale() === 'ar'
                                        ? 'الموضوع'
                                        : 'Subject' }}

                                </label>

                                <select
                                    id="subject"
                                    name="subject"
                                    required
                                    class="w-full rounded-xl
                                           border border-ink/10
                                           bg-ice/50
                                           px-4 py-3.5
                                           text-sm text-ink
                                           focus:border-sky
                                           focus:ring-4 focus:ring-sky/10
                                           focus:outline-none
                                           transition">

                                    <option value="" disabled {{ old('subject') ? '' : 'selected' }}>
                                        {{ app()->getLocale() === 'ar'
                                            ? 'اختر موضوع الرسالة'
                                            : 'Select a subject' }}
                                    </option>

                                    <option value="booking" {{ old('subject') === 'booking' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar'
                                            ? 'استفسار عن الحجز'
                                            : 'Booking Inquiry' }}
                                    </option>

                                    <option value="flight" {{ old('subject') === 'flight' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar'
                                            ? 'استفسار عن رحلة'
                                            : 'Flight Inquiry' }}
                                    </option>

                                    <option value="payment" {{ old('subject') === 'payment' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar'
                                            ? 'الدفع والفواتير'
                                            : 'Payment & Billing' }}
                                    </option>

                                    <option value="other" {{ old('subject') === 'other' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar'
                                            ? 'استفسار آخر'
                                            : 'Other' }}
                                    </option>

                                </select>

                            </div>


                            {{-- Message --}}
                            <div>

                                <label for="message"
                                       class="block text-sm font-bold text-navy mb-2">

                                    {{ app()->getLocale() === 'ar'
                                        ? 'رسالتك'
                                        : 'Your Message' }}

                                </label>

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="6"
                                    required
                                    placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...' }}"
                                    class="w-full rounded-xl
                                           border border-ink/10
                                           bg-ice/50
                                           px-4 py-3.5
                                           text-sm text-ink
                                           placeholder:text-ink/35
                                           focus:border-sky
                                           focus:ring-4 focus:ring-sky/10
                                           focus:outline-none
                                           transition
                                           resize-none">{{ old('message') }}</textarea>

                            </div>


                            {{-- Submit --}}
                            <div class="pt-2">

                                <button
                                    type="submit"
                                    class="w-full inline-flex items-center
                                           justify-center gap-3
                                           bg-amber hover:bg-amber-600
                                           text-navy
                                           font-extrabold
                                           py-4 px-6
                                           rounded-xl
                                           shadow-md
                                           hover:shadow-lg
                                           transition-all duration-300
                                           transform hover:-translate-y-0.5">

                                    <span>
                                        {{ app()->getLocale() === 'ar'
                                            ? 'إرسال الرسالة'
                                            : 'Send Message' }}
                                    </span>

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M22 2L11 13"/>

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M22 2l-7 20-4-9-9-4 20-7z"/>

                                    </svg>

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================
        BOTTOM CTA
    ========================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

        <div class="relative overflow-hidden
                    bg-navy rounded-3xl
                    px-6 sm:px-10 py-10 sm:py-12
                    text-white">

            {{-- Decorative circles --}}
            <div class="absolute -right-20 -top-20
                        w-64 h-64 rounded-full
                        bg-sky/15 blur-3xl"></div>

            <div class="absolute -left-20 -bottom-20
                        w-64 h-64 rounded-full
                        bg-amber/15 blur-3xl"></div>

            <div class="relative z-10
                        flex flex-col md:flex-row
                        items-center justify-between
                        gap-6">

                <div>

                    <h2 class="font-display text-2xl sm:text-3xl
                               font-extrabold mb-2">

                        {{ app()->getLocale() === 'ar'
                            ? 'مستعد لبدء رحلتك؟'
                            : 'Ready to start your journey?' }}

                    </h2>

                    <p class="text-white/65 text-sm sm:text-base">
                        {{ app()->getLocale() === 'ar'
                            ? 'ابحث عن رحلتك واحجز مقعدك بسهولة.'
                            : 'Find your flight and book your seat with ease.' }}
                    </p>

                </div>


                <a href="{{ route('home') }}#search"
                   class="shrink-0 inline-flex items-center
                          justify-center
                          px-7 py-3.5
                          rounded-xl
                          bg-amber
                          hover:bg-amber-600
                          text-navy
                          font-bold
                          text-sm
                          transition-all duration-300
                          hover:-translate-y-0.5
                          shadow-md">

                    {{ app()->getLocale() === 'ar'
                        ? 'احجز رحلتك'
                        : 'Book a Flight' }}

                </a>

            </div>

        </div>

    </section>

@endsection