@extends('layouts.app')
@section('title', '結帳付款 — 禮寵 Reiko Pet')

@section('content')

{{-- Hero --}}
<section class="pt-32 pb-16 bg-ink">
    <div class="max-w-6xl mx-auto px-6 lg:px-10 text-center">
        <div class="flex items-center justify-center gap-4 mb-6">
            <span class="w-8 h-px bg-cream/20 block"></span>
            <span class="text-[10px] tracking-[0.5em] uppercase text-cream/40">
                @if($order['source'] === 'grooming') 美容預約
                @elseif($order['source'] === 'accommodation') 寵物住宿
                @else 線上商城
                @endif
            </span>
            <span class="w-8 h-px bg-cream/20 block"></span>
        </div>
        <h1 class="font-serif text-4xl lg:text-5xl text-cream tracking-widest leading-relaxed mb-6">
            {{ $order['title'] ?? '訂單結帳' }}
        </h1>
        <div class="h-px bg-cream/15 w-16 mx-auto mb-6"></div>
        <p class="text-sm text-cream/50 tracking-widest font-light">確認訂單資料後進行線上付款</p>
    </div>
</section>

{{-- Main --}}
<section class="py-20 bg-cream-alt">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">
        <div class="grid lg:grid-cols-5 gap-10">

            {{-- ① 訂單摘要 --}}
            <div class="lg:col-span-2">
                <div class="bg-cream border border-border p-8 lg:sticky lg:top-28">

                    <div class="flex items-center gap-3 mb-7">
                        <span class="w-4 h-px bg-accent block"></span>
                        <p class="text-[11px] tracking-[0.4em] uppercase text-muted">訂單摘要</p>
                    </div>

                    {{-- 項目清單 --}}
                    <div class="space-y-4 mb-8">
                        @foreach($order['items'] as $item)
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-ink tracking-wide leading-snug">{{ $item['name'] }}</p>
                                @if(isset($item['qty']) && $item['qty'] > 1)
                                <p class="text-xs text-muted mt-0.5 tracking-wide">數量：{{ $item['qty'] }}</p>
                                @endif
                            </div>
                            <span class="text-sm text-ink tracking-wide shrink-0">NT${{ number_format($item['amount']) }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- 優惠碼 --}}
                    <div class="border-t border-border pt-6 pb-6" id="coupon-section">
                        <p class="text-[11px] tracking-[0.3em] uppercase text-muted mb-4">優惠碼</p>
                        <div class="flex gap-2 w-full">
                            <input type="text" id="coupon-input"
                                   class="flex-1 min-w-0 border border-border px-3 py-2.5 text-sm text-ink bg-white
                                          focus:outline-none focus:border-accent transition-colors uppercase tracking-widest"
                                   placeholder="輸入優惠碼">
                            <button type="button" id="coupon-apply-btn"
                                    class="shrink-0 border border-accent text-accent text-xs tracking-widest px-4 py-2.5
                                           hover:bg-accent hover:text-cream transition-colors whitespace-nowrap">
                                套用
                            </button>
                        </div>
                        <p id="coupon-msg" class="text-xs mt-2 tracking-wide hidden"></p>

                        @if($coupons->isNotEmpty())
                        <div class="mt-5">
                            <p class="text-[10px] tracking-[0.25em] uppercase text-muted mb-3">我的可用優惠券</p>
                            <div class="space-y-2">
                                @foreach($coupons as $c)
                                <button type="button"
                                        onclick="selectCoupon('{{ $c->code }}')"
                                        class="w-full flex items-center justify-between gap-3 border border-border px-3 py-2.5
                                               hover:border-accent hover:bg-accent/5 transition-colors text-left group">
                                    <div class="flex-1 min-w-0">
                                        <span class="font-mono text-xs text-accent tracking-widest block truncate">{{ $c->code }}</span>
                                        <span class="text-xs text-muted tracking-wide block truncate">{{ $c->name }}</span>
                                    </div>
                                    <div class="text-right shrink-0">
                                        @if($c->type === 'percentage')
                                        <span class="text-xs text-ink whitespace-nowrap">{{ $c->discount_value }}% 折扣</span>
                                        @elseif($c->type === 'fixed')
                                        <span class="text-xs text-ink whitespace-nowrap">折 NT${{ number_format($c->discount_value) }}</span>
                                        @endif
                                        @if($c->minimum_amount > 0)
                                        <p class="text-[10px] text-muted whitespace-nowrap">滿 NT${{ number_format($c->minimum_amount) }}</p>
                                        @endif
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- 金額小計 --}}
                    <div class="border-t border-border pt-5 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[11px] tracking-[0.3em] uppercase text-muted">小計</span>
                            <span class="text-ink tracking-wide">NT${{ number_format($order['total']) }}</span>
                        </div>
                        <div id="discount-row" class="flex items-center justify-between text-sm hidden">
                            <span class="text-[11px] tracking-[0.3em] uppercase text-accent">優惠折抵</span>
                            <span id="discount-amt" class="text-accent tracking-wide">-NT$0</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-border">
                            <span class="text-[11px] tracking-[0.3em] uppercase text-muted">合計</span>
                            <span id="final-total" class="font-serif text-2xl text-accent tracking-wider">NT${{ number_format($order['total']) }}</span>
                        </div>
                    </div>

                    {{-- 住宿附加資訊（只顯示字串型欄位，不顯示 ID / 陣列）--}}
                    @if(isset($order['meta']) && $order['meta'] && $order['source'] === 'accommodation')
                    @php
                    $metaLabels = [
                        'guest_name'  => '聯絡人',
                        'guest_phone' => '聯絡電話',
                        'guest_email' => '電子信箱',
                        'pet_name'    => '寵物名稱',
                        'pet_type'    => '寵物種類',
                        'check_in'    => '入住日期',
                        'check_out'   => '退房日期',
                        'nights'      => '住宿天數',
                    ];
                    @endphp
                    <div class="border-t border-border mt-5 pt-5 space-y-2">
                        @foreach($metaLabels as $key => $label)
                        @if(!empty($order['meta'][$key]))
                        <div class="flex items-center justify-between gap-4 text-xs text-muted tracking-wide">
                            <span class="shrink-0">{{ $label }}</span>
                            <span class="text-right">{{ $order['meta'][$key] }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>

            {{-- ② 付款表單 --}}
            <div class="lg:col-span-3">
                <div class="bg-cream border border-border p-10">

                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-4 h-px bg-accent block"></span>
                        <p class="text-[11px] tracking-[0.4em] uppercase text-muted">付款資料</p>
                    </div>
                    <h2 class="font-serif text-2xl text-ink tracking-widest mb-8">安全結帳</h2>

                    @if($errors->any())
                    <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide space-y-1">
                        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('checkout.pay') }}" class="space-y-6" id="checkout-form">
                        @csrf
                        <input type="hidden" name="coupon_id" id="hidden-coupon-id" value="">
                        <input type="hidden" name="discount" id="hidden-discount" value="0">

                        {{-- 付款方式（商城才顯示貨到付款）--}}
                        @if($order['source'] === 'shop')
                        <div>
                            <p class="text-[11px] tracking-[0.3em] uppercase text-muted mb-4">付款方式</p>
                            <div class="space-y-2">
                                <label class="flex items-center gap-4 border border-border p-4 cursor-pointer
                                              hover:border-accent/50 transition-colors has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                    <input type="radio" name="payment_method" value="credit" checked
                                           class="accent-[#2d5a3d] shrink-0" id="pm-credit">
                                    <div>
                                        <p class="text-sm text-ink tracking-wide">信用卡 / 金融卡</p>
                                        <p class="text-xs text-muted tracking-wide mt-0.5">VISA、Mastercard、JCB</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-4 border border-border p-4 cursor-pointer
                                              hover:border-accent/50 transition-colors has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                    <input type="radio" name="payment_method" value="cod"
                                           class="accent-[#2d5a3d] shrink-0" id="pm-cod">
                                    <div>
                                        <p class="text-sm text-ink tracking-wide">貨到付款</p>
                                        <p class="text-xs text-muted tracking-wide mt-0.5">收到商品後以現金支付</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="payment_method" value="credit">
                        @endif

                        {{-- 信用卡欄位 --}}
                        <div id="card-fields" class="space-y-5">

                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">持卡人姓名 *</label>
                                <input type="text" name="card_name" value="{{ old('card_name') }}" required
                                       class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                              focus:outline-none focus:border-accent transition-colors"
                                       placeholder="請輸入持卡人姓名">
                                @error('card_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">信用卡卡號 *</label>
                                <input type="text" name="card_number" value="{{ old('card_number') }}" required
                                       maxlength="19" inputmode="numeric"
                                       class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                              focus:outline-none focus:border-accent transition-colors font-mono tracking-[0.25em]"
                                       placeholder="0000 0000 0000 0000"
                                       id="card-number-input">
                                @error('card_number')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">有效期限 *</label>
                                    <input type="text" name="card_expiry" value="{{ old('card_expiry') }}" required
                                           maxlength="5" inputmode="numeric"
                                           class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                                  focus:outline-none focus:border-accent transition-colors font-mono tracking-widest"
                                           placeholder="月 / 年"
                                           id="card-expiry-input">
                                    @error('card_expiry')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">安全碼 *</label>
                                    <input type="text" name="card_cvv" value="{{ old('card_cvv') }}" required
                                           maxlength="4" inputmode="numeric"
                                           class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                                  focus:outline-none focus:border-accent transition-colors font-mono tracking-widest"
                                           placeholder="三或四位數字">
                                    @error('card_cvv')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="border border-amber-200 bg-amber-50 px-5 py-3">
                                <p class="text-xs text-amber-700 tracking-wide">
                                    ⚠ 此為模擬付款介面，請勿輸入真實信用卡資料。
                                </p>
                            </div>

                        </div>

                        {{-- 貨到付款提示 --}}
                        <div id="cod-notice" class="hidden border border-green-200 bg-green-50 px-5 py-4">
                            <p class="text-sm text-green-700 tracking-wide">✦ 選擇貨到付款，商品送達時請備妥現金支付。</p>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="pay-btn"
                                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-4
                                           hover:bg-accent-light transition-colors duration-300">
                                確認付款 — NT${{ number_format($order['total']) }}
                            </button>
                        </div>

                    </form>
                </div>

                <p class="text-center text-[11px] text-muted tracking-wide mt-6 leading-relaxed">
                    點擊確認付款即表示同意本服務之使用條款與隱私政策<br>
                    您的資料受 SSL 加密保護，安全無虞
                </p>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function selectCoupon(code) {
    const input = document.getElementById('coupon-input');
    if (!input) return;
    input.value = code;
    document.getElementById('coupon-apply-btn').click();
}

(function () {
    const pmCredit  = document.getElementById('pm-credit');
    const pmCod     = document.getElementById('pm-cod');
    if (!pmCredit || !pmCod) return;

    const cardFields = document.getElementById('card-fields');
    const codNotice  = document.getElementById('cod-notice');
    const payBtn     = document.getElementById('pay-btn');
    const originalTotal = {{ $order['total'] }};

    function formatNT(n) { return 'NT$' + n.toLocaleString('zh-TW'); }

    function syncPaymentMethod() {
        const isCod = pmCod.checked;
        cardFields.classList.toggle('hidden', isCod);
        codNotice.classList.toggle('hidden', !isCod);
        cardFields.querySelectorAll('input').forEach(el => { el.required = !isCod; });

        const finalTotalEl = document.getElementById('final-total');
        const currentTotal = finalTotalEl
            ? parseInt(finalTotalEl.textContent.replace(/[^\d]/g, ''), 10)
            : originalTotal;

        payBtn.textContent = isCod
            ? '確認訂單（貨到付款）— ' + formatNT(currentTotal)
            : '確認付款 — ' + formatNT(currentTotal);
    }

    pmCredit.addEventListener('change', syncPaymentMethod);
    pmCod.addEventListener('change', syncPaymentMethod);
})();

(function () {
    const cardNum    = document.getElementById('card-number-input');
    const cardExpiry = document.getElementById('card-expiry-input');

    if (cardNum) {
        cardNum.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '').slice(0, 16);
            this.value = v.replace(/(.{4})/g, '$1 ').trim();
        });
    }
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '').slice(0, 4);
            if (v.length >= 2) v = v.slice(0, 2) + '/' + v.slice(2);
            this.value = v;
        });
    }
})();

(function () {
    const originalTotal  = {{ $order['total'] }};
    const applyBtn       = document.getElementById('coupon-apply-btn');
    const couponInput    = document.getElementById('coupon-input');
    const couponMsg      = document.getElementById('coupon-msg');
    const discountRow    = document.getElementById('discount-row');
    const discountAmt    = document.getElementById('discount-amt');
    const finalTotal     = document.getElementById('final-total');
    const payBtn         = document.getElementById('pay-btn');
    const hiddenId       = document.getElementById('hidden-coupon-id');
    const hiddenDiscount = document.getElementById('hidden-discount');

    function formatNT(n) { return 'NT$' + n.toLocaleString('zh-TW'); }

    applyBtn.addEventListener('click', async function () {
        const code = couponInput.value.trim();
        if (!code) return;

        applyBtn.disabled = true;
        applyBtn.textContent = '驗證中…';

        try {
            const res = await fetch('{{ route('checkout.validate-coupon') }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ code, total: originalTotal }),
            });
            const data = await res.json();
            couponMsg.classList.remove('hidden', 'text-accent', 'text-red-500');

            if (data.success) {
                const discount  = data.discount;
                const newTotal  = Math.max(0, originalTotal - discount);
                discountAmt.textContent = '-' + formatNT(discount);
                discountRow.classList.remove('hidden');
                finalTotal.textContent = formatNT(newTotal);

                const pmCodEl = document.getElementById('pm-cod');
                const isCod   = pmCodEl && pmCodEl.checked;
                payBtn.textContent = isCod
                    ? '確認訂單（貨到付款）— ' + formatNT(newTotal)
                    : '確認付款 — ' + formatNT(newTotal);

                hiddenId.value       = data.coupon_id;
                hiddenDiscount.value = discount;
                couponMsg.textContent = data.message;
                couponMsg.classList.add('text-accent');
                applyBtn.textContent = '已套用';
                applyBtn.disabled    = true;
                couponInput.disabled = true;
            } else {
                couponMsg.textContent = data.message;
                couponMsg.classList.add('text-red-500');
                applyBtn.disabled    = false;
                applyBtn.textContent = '套用';
            }
        } catch (e) {
            couponMsg.textContent = '網路錯誤，請再試一次';
            couponMsg.classList.add('text-red-500');
            applyBtn.disabled    = false;
            applyBtn.textContent = '套用';
        }

        couponMsg.classList.remove('hidden');
    });
})();
</script>
@endpush
