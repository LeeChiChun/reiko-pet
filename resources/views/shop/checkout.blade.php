@extends('layouts.app')
@section('title', '結帳 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        <div class="mb-12">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Checkout</p>
            <h1 class="font-serif text-4xl text-ink tracking-widest">結帳</h1>
        </div>

        @php
            $cart  = session('cart', []);
            $total = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
            $shipping = $total >= 999 ? 0 : 60;
        @endphp

        @if($errors->any())
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide leading-relaxed">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        <div class="grid md:grid-cols-3 gap-12">

            {{-- Form --}}
            <form method="POST" action="{{ route('shop.checkout.process') }}" class="md:col-span-2 space-y-8">
                @csrf

                <div class="bg-white border border-border p-8">
                    <h2 class="font-serif text-lg text-ink tracking-widest mb-7">收件資訊</h2>
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">姓名</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required
                                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                            </div>
                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">手機</label>
                                <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required
                                       pattern="^09[0-9]{8}$"
                                       title="請輸入 09 開頭的 10 碼手機號碼"
                                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">電子信箱</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required
                                   class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">收件地址</label>
                            <input type="text" name="address" value="{{ old('address') }}" required
                                   class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                                   placeholder="縣市 + 詳細地址">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">備註</label>
                            <textarea name="note" rows="3"
                                      class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"
                                      placeholder="例：週六可取件、請勿按門鈴...">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-border p-8">
                    <h2 class="font-serif text-lg text-ink tracking-widest mb-7">付款方式</h2>
                    <div class="space-y-3">
                        @foreach(['credit_card' => '信用卡', 'atm' => 'ATM 轉帳', 'cod' => '貨到付款'] as $val => $label)
                        <label class="flex items-center gap-4 p-4 border border-border cursor-pointer hover:border-accent transition-colors has-[:checked]:border-accent">
                            <input type="radio" name="payment_method" value="{{ $val }}" class="accent-accent"
                                   {{ old('payment_method', 'credit_card') === $val ? 'checked' : '' }}>
                            <span class="text-sm text-ink tracking-wide">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-4
                               hover:bg-accent-light transition-colors duration-300">
                    確認下單
                </button>
            </form>

            {{-- Order Summary --}}
            <div class="space-y-6">
                <div class="bg-white border border-border p-7">
                    <h2 class="font-serif text-base text-ink tracking-widest mb-6">訂單確認</h2>
                    <div class="space-y-4">
                        @foreach($cart as $item)
                        <div class="flex items-start gap-3 text-xs text-muted tracking-wide">
                            <span class="flex-1">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                            <span class="text-ink shrink-0">NT$ {{ number_format($item['price'] * $item['qty']) }}</span>
                        </div>
                        @endforeach
                        <div class="h-px bg-border my-2"></div>
                        <div class="flex justify-between text-xs text-muted"><span>運費</span><span>{{ $shipping > 0 ? 'NT$ '.$shipping : '免運' }}</span></div>
                        <div class="flex justify-between text-sm font-medium text-ink">
                            <span>合計</span>
                            <span class="text-accent">NT$ {{ number_format($total + $shipping) }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-xs text-muted tracking-wide leading-loose space-y-1.5">
                    <p>✦ 滿 NT$999 免運費</p>
                    <p>✦ 7 天鑑賞期保障</p>
                    <p>✦ 3–5 個工作天出貨</p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
