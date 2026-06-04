@extends('layouts.app')
@section('title', '線上商城 — 禮寵 Reiko Pet')

@section('content')

{{-- Header --}}
<section class="pt-32 pb-10 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Online Shop</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">線上商城</h1>
            </div>
        </div>

        {{-- Filter & Search --}}
        <form method="GET" action="{{ route('shop.index') }}" class="flex flex-wrap gap-4 items-center">
            <div class="flex gap-2 flex-wrap">
                @foreach(['all' => '全部', 'snack' => '零食點心', 'toy' => '玩具', 'cleaning' => '清潔用品', 'clothing' => '衣著配件', 'health' => '保健食品'] as $val => $label)
                <a href="{{ route('shop.index', array_merge(request()->query(), ['category' => $val])) }}"
                   class="text-[11px] tracking-[0.3em] uppercase px-4 py-2 border transition-colors duration-200
                          {{ (request('category', 'all') === $val)
                              ? 'bg-accent text-cream border-accent'
                              : 'border-border text-muted hover:border-accent hover:text-accent' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
            <div class="flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="搜尋商品..."
                       class="w-full border border-border px-4 py-2 text-sm text-ink tracking-wide
                              focus:outline-none focus:border-accent placeholder-warm-gray">
            </div>
            <button type="submit" class="bg-accent text-cream text-xs tracking-widest px-6 py-2.5 hover:bg-accent-light transition-colors">搜尋</button>
        </form>
    </div>
</section>

{{-- Products --}}
<section class="py-10 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        @if($products->isEmpty())
        <div class="text-center py-24 text-muted tracking-widest">目前沒有符合條件的商品</div>
        @else

        <p class="text-xs text-muted tracking-wide mb-8">共 {{ $products->total() }} 項商品</p>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="group border border-border hover:border-accent transition-colors duration-300 flex flex-col">
                <a href="{{ route('shop.show', $product) }}" class="block overflow-hidden">
                    <div class="bg-cream-alt aspect-square flex items-center justify-center overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <span class="text-4xl text-warm-gray">🐾</span>
                        @endif
                    </div>
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <a href="{{ route('shop.show', $product) }}"
                       class="font-serif text-sm text-ink tracking-widest leading-relaxed hover:text-accent transition-colors mb-2 line-clamp-2">
                        {{ $product->name }}
                    </a>
                    <p class="text-xs text-muted tracking-wide mb-4 flex-1 line-clamp-2">{{ $product->description }}</p>
                    <div class="flex items-center justify-between mt-auto">
                        <span class="text-accent font-medium tracking-wide">NT$ {{ number_format($product->price) }}</span>
                        {{-- AJAX 加入購物車按鈕 --}}
                        <button type="button"
                                data-product-id="{{ $product->id }}"
                                data-add-url="{{ route('shop.cart.add', $product) }}"
                                data-csrf="{{ csrf_token() }}"
                                class="cart-btn text-[11px] tracking-[0.25em] uppercase border border-accent text-accent px-3 py-1.5
                                       hover:bg-accent hover:text-cream transition-colors duration-200">
                            加入
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-14">{{ $products->withQueryString()->links() }}</div>

        @endif
    </div>
</section>

{{-- Toast 通知 --}}
<div id="cart-toast"
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-ink text-cream text-sm tracking-wide
            px-5 py-3.5 shadow-xl opacity-0 translate-y-4 transition-all duration-300 pointer-events-none">
    <svg id="cart-toast-icon" class="w-4 h-4 text-accent shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    <span id="cart-toast-msg"></span>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const toast    = document.getElementById('cart-toast');
    const toastMsg = document.getElementById('cart-toast-msg');
    let hideTimer;

    function showToast(msg) {
        clearTimeout(hideTimer);
        toastMsg.textContent = msg;
        toast.classList.remove('opacity-0', 'translate-y-4');
        toast.classList.add('opacity-100', 'translate-y-0');
        hideTimer = setTimeout(() => {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', 'translate-y-4');
        }, 2500);
    }

    function updateNavCartCount(count) {
        const badge = document.getElementById('nav-cart-count');
        if (!badge) return;
        badge.textContent = count;
        if (count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    document.querySelectorAll('.cart-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const url  = this.dataset.addUrl;
            const csrf = this.dataset.csrf;
            const el   = this;

            el.disabled = true;
            el.textContent = '加入中…';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await res.json();

                if (data.success) {
                    // 按鈕變色 + 打勾
                    el.textContent = '✓ 已加入';
                    el.classList.remove('border-accent', 'text-accent');
                    el.classList.add('border-green-500', 'text-green-600', 'bg-green-50');

                    updateNavCartCount(data.cartCount);
                    showToast(data.message);

                    setTimeout(() => {
                        el.textContent = '加入';
                        el.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
                        el.classList.add('border-accent', 'text-accent');
                        el.disabled = false;
                    }, 1800);
                }
            } catch (e) {
                el.textContent = '加入';
                el.disabled = false;
            }
        });
    });
})();
</script>
@endpush
