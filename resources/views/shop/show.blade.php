@extends('layouts.app')
@section('title', $product->name . ' — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[11px] tracking-widest text-muted mb-10">
            <a href="{{ route('shop.index') }}" class="hover:text-accent transition-colors">線上商城</a>
            <span>/</span>
            <span class="text-ink">{{ $product->name }}</span>
        </nav>

        <div class="grid md:grid-cols-2 gap-16">
            {{-- Image --}}
            <div class="bg-cream-alt aspect-square flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                @else
                    <span class="text-8xl text-warm-gray/40">🐾</span>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex flex-col">
                <p class="text-[11px] tracking-[0.3em] uppercase text-muted mb-4">
                    {{ ['snack'=>'零食點心','toy'=>'玩具','cleaning'=>'清潔用品','clothing'=>'衣著配件','health'=>'保健食品'][$product->category] ?? $product->category }}
                </p>
                <h1 class="font-serif text-3xl text-ink tracking-widest leading-relaxed mb-6">{{ $product->name }}</h1>
                <div class="h-px bg-border mb-8"></div>

                <p class="text-sm text-muted leading-loose tracking-wide mb-8">{{ $product->description }}</p>

                <div class="flex items-baseline gap-4 mb-10">
                    <span class="text-3xl text-accent font-medium tracking-wide">NT$ {{ number_format($product->price) }}</span>
                </div>

                @if($product->stock > 0)
                <div class="flex gap-4">
                    <div class="flex items-center border border-border">
                        <button type="button" id="qty-dec"
                                class="px-4 py-3 text-muted hover:text-ink transition-colors">−</button>
                        <input type="number" id="qty-input" value="1" min="1" max="{{ $product->stock }}"
                               class="w-14 text-center text-sm text-ink border-x border-border py-3 focus:outline-none">
                        <button type="button" id="qty-inc"
                                class="px-4 py-3 text-muted hover:text-ink transition-colors">+</button>
                    </div>
                    <button id="add-cart-btn"
                            data-add-url="{{ route('shop.cart.add', $product) }}"
                            data-csrf="{{ csrf_token() }}"
                            data-stock="{{ $product->stock }}"
                            class="flex-1 bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3
                                   hover:bg-accent-light transition-colors duration-300">
                        加入購物車
                    </button>
                </div>
                <p class="mt-3 text-xs text-muted tracking-wide">庫存：{{ $product->stock }} 件</p>

                {{-- Toast --}}
                <div id="show-toast"
                     class="hidden mt-4 bg-accent/10 border border-accent/30 px-4 py-3 text-sm text-accent tracking-wide"></div>

                @push('scripts')
                <script>
                (function () {
                    const qtyInput = document.getElementById('qty-input');
                    const stock    = parseInt(document.getElementById('add-cart-btn').dataset.stock);

                    document.getElementById('qty-dec').addEventListener('click', () => {
                        qtyInput.value = Math.max(1, parseInt(qtyInput.value) - 1);
                    });
                    document.getElementById('qty-inc').addEventListener('click', () => {
                        qtyInput.value = Math.min(stock, parseInt(qtyInput.value) + 1);
                    });

                    document.getElementById('add-cart-btn').addEventListener('click', async function () {
                        const btn  = this;
                        const qty  = parseInt(qtyInput.value) || 1;
                        const url  = btn.dataset.addUrl;
                        const csrf = btn.dataset.csrf;
                        const toast = document.getElementById('show-toast');

                        btn.disabled  = true;
                        btn.textContent = '加入中…';

                        try {
                            const body = new URLSearchParams({ _token: csrf, quantity: qty });
                            const res  = await fetch(url, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: body.toString(),
                            });
                            const data = await res.json();
                            if (data.success) {
                                btn.textContent = '✓ 已加入';
                                const badge = document.getElementById('nav-cart-count');
                                if (badge) {
                                    badge.textContent = data.cartCount;
                                    badge.classList.remove('hidden');
                                }
                                toast.textContent = data.message;
                                toast.classList.remove('hidden');
                                setTimeout(() => {
                                    btn.textContent = '加入購物車';
                                    btn.disabled = false;
                                    toast.classList.add('hidden');
                                }, 2500);
                            } else {
                                btn.textContent = '加入購物車';
                                btn.disabled = false;
                            }
                        } catch (e) {
                            btn.textContent = '加入購物車';
                            btn.disabled = false;
                        }
                    });
                })();
                </script>
                @endpush
                @else
                <p class="text-sm text-muted tracking-widest py-4 border border-border text-center">已售罄</p>
                @endif

                {{-- 收藏按鈕 --}}
                @auth
                <div class="mt-6">
                    <button id="bookmark-btn"
                            data-url="{{ route('shop.bookmark', $product) }}"
                            data-csrf="{{ csrf_token() }}"
                            data-bookmarked="{{ $isBookmarked ? '1' : '0' }}"
                            class="flex items-center gap-2 text-sm tracking-wide transition-colors
                                   {{ $isBookmarked ? 'text-accent' : 'text-muted hover:text-accent' }}">
                        <svg id="bookmark-icon" class="w-4 h-4"
                             fill="{{ $isBookmarked ? 'currentColor' : 'none' }}"
                             stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                        </svg>
                        <span id="bookmark-label">{{ $isBookmarked ? '已收藏' : '加入收藏' }}</span>
                    </button>
                </div>
                @push('scripts')
                <script>
                (function () {
                    const btn   = document.getElementById('bookmark-btn');
                    const icon  = document.getElementById('bookmark-icon');
                    const label = document.getElementById('bookmark-label');
                    let bookmarked = btn.dataset.bookmarked === '1';

                    btn.addEventListener('click', async function () {
                        try {
                            const res = await fetch(btn.dataset.url, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': btn.dataset.csrf,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                            });
                            const data = await res.json();
                            if (data.success) {
                                bookmarked = data.bookmarked;
                                icon.setAttribute('fill', bookmarked ? 'currentColor' : 'none');
                                label.textContent = bookmarked ? '已收藏' : '加入收藏';
                                btn.classList.toggle('text-accent', bookmarked);
                                btn.classList.toggle('text-muted', !bookmarked);
                            }
                        } catch (e) {}
                    });
                })();
                </script>
                @endpush
                @else
                <div class="mt-6">
                    <a href="{{ url('/login') }}"
                       class="flex items-center gap-2 text-sm text-muted tracking-wide hover:text-accent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                        </svg>
                        登入後可收藏商品
                    </a>
                </div>
                @endauth

                <div class="mt-8 pt-8 border-t border-border text-xs text-muted tracking-wide space-y-2">
                    <p>✦ 滿 NT$999 免運費</p>
                    <p>✦ 7 天鑑賞期</p>
                    <p>✦ 訂單成立後 3–5 個工作天出貨</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <h2 class="font-serif text-xl text-ink tracking-widest mb-8">商品詳情</h2>
        <div class="prose prose-sm max-w-none prose-p:text-muted prose-p:leading-loose prose-p:tracking-wide">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>
</section>

@endsection
