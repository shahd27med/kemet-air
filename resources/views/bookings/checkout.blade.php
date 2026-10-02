@extends('layouts.app')

@section('title', __('booking.checkout_heading') . ' — Kemet Air')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" 
     x-data="{ 
        paymentMethod: 'credit_card', 
        showOtpModal: false, 
        otpCode: '',
        walletNumber: '',
        cardNumber: '',
        isOtpSubmitted: false,
        
        handlePaymentSubmit(e) {
            // إذا كانت طريقة الدفع إلكترونية ولم يتجاوز مرحلة الـ OTP بعد
            if (this.paymentMethod !== 'cash' && !this.isOtpSubmitted) {
                e.preventDefault();
                this.showOtpModal = true;
            }
        },

        submitFinalForm() {
            if (this.paymentMethod !== 'cash' && !this.otpCode) {
                alert('يرجى إدخال رمز التحقق (OTP)');
                return;
            }
            this.isOtpSubmitted = true;
            this.$nextTick(() => {
                this.$refs.checkoutForm.submit();
            });
        }
     }">

    @include('bookings.partials.steps', ['current' => 3])
    @include('bookings.partials.flight-summary', ['flight' => $flight, 'draft' => $draft])

    <h1 class="font-display text-2xl text-ink mb-6 text-center" style="font-weight:700;">{{ __('booking.checkout_heading') }}</h1>

    <form x-ref="checkoutForm" method="POST" action="{{ route('bookings.confirm') }}" @submit="handlePaymentSubmit($event)" class="grid lg:grid-cols-5 gap-6">
        @csrf

        <!-- Passenger & seat summary -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 sm:p-6">
                <h2 class="font-display text-base text-ink mb-4" style="font-weight:700;">{{ __('booking.passenger_summary') }}</h2>
                <div class="divide-y divide-ink/5">
                    @foreach($draft['passengers'] as $index => $passenger)
                        @php $seat = $seats->get($passenger['seat_id']); @endphp
                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $passenger['full_name'] }}</p>
                                <p class="text-xs text-ink/45">{{ __('booking.passenger_number', ['number' => $index + 1]) }} &middot; {{ $passenger['gender'] === 'male' ? __('booking.male') : __('booking.female') }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-navy bg-navy/5 rounded-full px-2.5 py-1">
                                {{ __('booking.seat_label', ['seat' => $seat?->seat_number]) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment method -->
            <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 sm:p-6">
                <h2 class="font-display text-base text-ink mb-4" style="font-weight:700;">{{ __('booking.payment_method') }}</h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        'credit_card' => ['label' => __('booking.credit_card'), 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5h-15A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z'],
                        'debit_card' => ['label' => __('booking.debit_card'), 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5h-15A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z'],
                        'wallet' => ['label' => __('booking.wallet'), 'icon' => 'M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9A2.25 2.25 0 0018.75 6.75H5.25A2.25 2.25 0 003 9v3'],
                        'cash' => ['label' => __('booking.cash'), 'icon' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ] as $method => $meta)
                        <label class="flex items-center gap-2.5 rounded-xl border border-ink/15 p-3 cursor-pointer transition-colors has-[:checked]:border-amber has-[:checked]:bg-amber-50">
                            <input type="radio" name="payment_method" value="{{ $method }}" x-model="paymentMethod" @checked($loop->first) required class="text-amber-600 focus:ring-amber/40">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5 text-ink/60 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}" />
                            </svg>
                            <span class="text-sm text-ink/80">{{ $meta['label'] }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- 1. نموذج بيانات البطاقة -->
                <div x-show="paymentMethod === 'credit_card' || paymentMethod === 'debit_card'" 
                     x-transition
                     class="mt-6 pt-5 border-t border-ink/10 space-y-4">
                    <h3 class="font-bold text-sm text-ink mb-2">بيانات البطاقة</h3>
                    
                    <div>
                        <label class="block text-xs font-semibold text-ink/70 mb-1">اسم حامل البطاقة</label>
                        <input type="text" name="card_holder" placeholder="Shahd Ahmed" 
                               class="w-full px-3.5 py-2 rounded-lg border border-ink/15 text-sm focus:border-amber focus:ring-amber">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink/70 mb-1">رقم البطاقة</label>
                        <input type="text" name="card_number" x-model="cardNumber" maxlength="19" placeholder="0000 0000 0000 0000" 
                               class="w-full px-3.5 py-2 rounded-lg border border-ink/15 text-sm focus:border-amber focus:ring-amber">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink/70 mb-1">تاريخ الانتهاء</label>
                            <input type="text" name="card_expiry" placeholder="MM/YY" maxlength="5"
                                   class="w-full px-3.5 py-2 rounded-lg border border-ink/15 text-sm focus:border-amber focus:ring-amber">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink/70 mb-1">رمز الأمان (CVV)</label>
                            <input type="password" name="card_cvv" maxlength="4" placeholder="123" 
                                   class="w-full px-3.5 py-2 rounded-lg border border-ink/15 text-sm focus:border-amber focus:ring-amber">
                        </div>
                    </div>
                </div>

                <!-- 2. خيار المحفظة الإلكترونية -->
                <div x-show="paymentMethod === 'wallet'" 
                     x-transition
                     class="mt-6 pt-5 border-t border-ink/10 space-y-3">
                    <h3 class="font-bold text-sm text-ink">بيانات المحفظة</h3>
                    <div>
                        <label class="block text-xs font-semibold text-ink/70 mb-1">رقم الهاتف المرتبط بالمحفظة (فودافون كاش / أورانج / اتصالات / WE)</label>
                        <input type="text" name="wallet_number" x-model="walletNumber" placeholder="01012345678" 
                               class="w-full px-3.5 py-2 rounded-lg border border-ink/15 text-sm focus:border-amber focus:ring-amber">
                    </div>
                    <p class="text-xs text-ink/55">سيتم إرسال رمز تحقق OTP إلى هذا الرقم لتأكيد خصم المبلغ.</p>
                </div>

                <!-- 3. خيار الدفع نقداً -->
                <div x-show="paymentMethod === 'cash'" 
                     x-transition
                     class="mt-6 pt-5 border-t border-ink/10 space-y-2">
                    <h3 class="font-bold text-sm text-ink">تعليمات الدفع النقدي</h3>
                    <div class="bg-amber/10 border border-amber/30 rounded-xl p-3.5 text-xs text-ink/80 leading-relaxed space-y-1.5">
                        <p>• سيتم إصدار الحجز بحالة <strong>"بانتظار السداد" (Pending)</strong> لمحتوى تذكرتك.</p>
                        <p>• يرجى زيارة أقرب فرع من مكاتب مبيعات الشركة أو مكاتب المطار خلال 24 ساعة لسداد قيمة التذكرة وتأكيد الحجز قبل إلغائه تلقائياً.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Fare breakdown -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-card ring-1 ring-ink/5 p-5 sm:p-6 sticky top-20">
                <h2 class="font-display text-base text-ink mb-4" style="font-weight:700;">{{ __('booking.fare_breakdown') }}</h2>

                <div class="space-y-2.5 text-sm">
                    <div class="flex items-center justify-between text-ink/70">
                        <span>{{ __('booking.base_fare', ['count' => $draft['passengers_count'], 'price' => number_format($pricePerSeat)]) }}</span>
                        <span>{{ __('common.egp') }} {{ number_format($pricing['subtotal']) }}</span>
                    </div>

                    @if($pricing['discount_amount'] > 0)
                        <div class="flex items-center justify-between text-amber-700 font-medium">
                            <span>{{ __('booking.discount', ['percent' => rtrim(rtrim(number_format($pricing['discount_percentage'], 1), '0'), '.')]) }}</span>
                            <span>&minus; {{ __('common.egp') }} {{ number_format($pricing['discount_amount']) }}</span>
                        </div>
                    @endif

                    <div class="border-t border-ink/10 pt-3 flex items-center justify-between">
                        <span class="font-display text-base text-ink" style="font-weight:700;">{{ __('booking.total') }}</span>
                        <span class="font-display text-xl text-navy" style="font-weight:800;">{{ __('common.egp') }} {{ number_format($pricing['final_price']) }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 rounded-lg bg-amber hover:bg-amber-600 text-navy font-bold text-sm py-3 transition-colors">
                    {{ __('booking.pay_now') }}
                </button>

                <a href="{{ route('bookings.passengers') }}" class="block text-center mt-3 text-sm font-semibold text-ink/60 hover:text-ink">
                    {{ __('booking.back') }}
                </a>
            </div>
        </div>

        <!-- Modal نافذة تأكيد OTP -->
        <div x-show="showOtpModal" 
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 text-center space-y-4" @click.away="showOtpModal = false">
                <div class="w-12 h-12 bg-amber/15 text-amber-700 rounded-full flex items-center justify-center mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-ink">تأكيد عملية الدفع (OTP)</h3>
                <p class="text-xs text-ink/60">
                    تم إرسال رمز التحقق المكون من 6 أرقام إلى 
                    <span class="font-bold text-ink" x-text="paymentMethod === 'wallet' ? (walletNumber || 'رقم المحفظة') : 'رقم الهاتف المسجل للبطاقة'"></span>
                </p>

                <div>
                    <input type="text" name="otp_code" x-model="otpCode" maxlength="6" placeholder="1 2 3 4 5 6" 
                           class="w-full text-center tracking-[0.5em] text-lg font-bold px-3 py-2.5 rounded-xl border border-ink/20 focus:border-amber focus:ring-amber">
                    <p class="text-[11px] text-ink/40 mt-1">رمز تجريبي للعرض: 123456</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showOtpModal = false" class="w-1/2 py-2.5 rounded-xl border border-ink/20 text-xs font-semibold text-ink hover:bg-ink/5">
                        إلغاء
                    </button>
                    <button type="button" @click="submitFinalForm()" class="w-1/2 py-2.5 rounded-xl bg-amber hover:bg-amber-600 text-navy text-xs font-bold transition-colors">
                        تأكيد وإتمام الدفع
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection