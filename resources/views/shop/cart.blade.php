@extends('layouts.app')
@section('title', '購物車 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-6 lg:px-10">

        <div class="mb-12">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Cart</p>
            <h1 class="font-serif text-4xl text-ink tracking-widest">購物車</h1>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-accent/10 border border-accent/20 px-5 py-4 text-xs text-accent tracking-wide">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->has('stock'))
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide">
            {{ $errors->first('stock') }}
        </div>
        @endif

        @php $cart = session('cart', []); $total = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']); @endphp

        @if(empty($cart))
        <div class="text-center py-24 border border-border bg-white">
            <p class="font-serif text-2xl text-ink/30 tracking-widest mb-6">購物車是空的</p>
            <a href="{{ route('shop.index') }}"
               class="inline-block bg-accent text-cream text-sm tracking-[0.3em] uppercase px-10 py-3.5 hover:bg-accent-light transition-colors">
                繼續購物
            </a>
        </div>
        @else

        <div class="grid md:grid-cols-3 gap-10">
            {{-- Item List --}}
            <div class="md:col-span-2 space-y-px">
                @foreach($cart as $key => $item)
                <div class="bg-white border border-border p-6 flex items-center gap-6">
                    <div class="bg-cream-alt w-20 h-20 flex items-center justify-center shrink-0 overflow-hidden">
                        @if(!empty($item['image']))
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl">🐾</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-serif text-sm text-ink tracking-widest truncate">{{ $item['name'] }}</p>
                        <p class="text-xs text-muted tracking-wide mt-1">NT$ {{ number_format($item['price']) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Minus --}}
                        <form method="POST" action="{{ route('shop.cart.update') }}">
                            @csrf
                            <input type="hidden" name="qty[{{ $key }}]" value="{{ max(0, $item['qty'] - 1) }}">
                            <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center border border-border text-muted hover:text-ink hover:border-accent transition-colors text-sm">
                                −
                            </button>
                        </form>
                        <span class="w-8 text-center text-sm text-ink select-none">{{ $item['qty'] }}</span>
                        {{-- Plus --}}
                        <form method="POST" action="{{ route('shop.cart.update') }}">
                            @csrf
                            <input type="hidden" name="qty[{{ $key }}]" value="{{ $item['qty'] + 1 }}">
                            <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center border border-border text-muted hover:text-ink hover:border-accent transition-colors text-sm">
                                +
                            </button>
                        </form>
                        {{-- Remove --}}
                        <form method="POST" action="{{ route('shop.cart.remove') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $key }}">
                            <button type="submit" class="text-muted hover:text-red-400 transition-colors ml-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    <p class="text-sm text-ink font-medium w-24 text-right">NT$ {{ number_format($item['price'] * $item['qty']) }}</p>
                </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="bg-white border border-border p-8 h-fit">
                <h2 class="font-serif text-lg text-ink tracking-widest mb-8">訂單摘要</h2>
                <div class="space-y-4 text-sm text-muted tracking-wide mb-6">
                    <div class="flex justify-between">
                        <span>小計</span>
                        <span class="text-ink">NT$ {{ number_format($total) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>運費</span>
                        <span class="text-ink">{{ $total >= 999 ? '免運' : 'NT$ 60' }}</span>
                    </div>
                    <div class="h-px bg-border"></div>
                    <div class="flex justify-between text-base font-medium text-ink">
                        <span>合計</span>
                        <span class="text-accent">NT$ {{ number_format($total >= 999 ? $total : $total + 60) }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('checkout.from-cart') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase text-center py-4
                                   hover:bg-accent-light transition-colors duration-300">
                        前往結帳
                    </button>
                </form>
                <a href="{{ route('shop.index') }}"
                   class="block w-full text-center text-xs text-muted tracking-widest mt-4 hover:text-ink transition-colors">
                    繼續購物
                </a>
            </div>
        </div>

        @endif
    </div>
</section>

@endsection
