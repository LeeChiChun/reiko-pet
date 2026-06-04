@extends('layouts.app')
@section('title', '訂單成立 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen flex items-center">
    <div class="max-w-2xl mx-auto px-6 w-full text-center">

        {{-- Check icon --}}
        <div class="w-20 h-20 rounded-full border-2 border-accent flex items-center justify-center mx-auto mb-10">
            <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>

        <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-5">Order Confirmed</p>
        <h1 class="font-serif text-3xl text-ink tracking-widest mb-6 leading-relaxed">訂單已成立</h1>
        <div class="h-px bg-border w-16 mx-auto mb-8"></div>

        @if($orderId)
        <div class="bg-white border border-border px-10 py-8 mb-10 text-left">
            <div class="grid grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-[11px] tracking-[0.3em] uppercase text-muted mb-1.5">訂單編號</p>
                    <p class="text-ink font-medium tracking-widest">{{ $orderId }}</p>
                </div>
                <div>
                    <p class="text-[11px] tracking-[0.3em] uppercase text-muted mb-1.5">成立時間</p>
                    <p class="text-ink tracking-wide">{{ now()->format('Y/m/d H:i') }}</p>
                </div>
            </div>
        </div>
        @endif

        <p class="text-sm text-muted leading-loose tracking-wide mb-10">
            感謝您的訂購！確認信已寄至您的電子信箱。<br>
            商品將於 3–5 個工作天內寄出，請耐心等候。
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}"
               class="border border-border text-ink text-sm tracking-[0.3em] uppercase px-10 py-3.5
                      hover:border-accent hover:text-accent transition-colors duration-300">
                繼續購物
            </a>
            <a href="{{ route('member.appointments') }}"
               class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-10 py-3.5
                      hover:bg-accent-light transition-colors duration-300">
                查看訂單
            </a>
        </div>
    </div>
</section>

@endsection
