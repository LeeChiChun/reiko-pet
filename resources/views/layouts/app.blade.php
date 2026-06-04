<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '禮寵 Reiko Pet — 寵物美容預約')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500&family=Noto+Serif+TC:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="antialiased">

@php $heroNav = $heroNav ?? false; @endphp

{{-- ══ 導覽列 ══════════════════════════════════════════ --}}
<header id="navbar"
        data-hero="{{ $heroNav ? 'true' : 'false' }}"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-500
               {{ $heroNav ? 'bg-transparent' : 'bg-cream border-b border-border' }}">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex flex-col leading-none">
                <span id="logo-main"
                      class="font-serif text-xl tracking-widest transition-colors duration-300
                             {{ $heroNav ? 'text-cream' : 'text-accent' }}">禮寵</span>
                <span id="logo-sub"
                      class="text-[10px] tracking-[0.3em] uppercase mt-0.5 transition-colors duration-300
                             {{ $heroNav ? 'text-cream/50' : 'text-muted' }}">Reiko Pet</span>
            </a>

            {{-- 桌機主選單 --}}
            <nav class="hidden lg:flex items-center gap-8">
                @php
                $navItems = [
                    ['url' => url('/'),               'label' => '首頁'],
                    ['url' => url('/services'),        'label' => '寵物美容'],
                    ['url' => url('/accommodation'),   'label' => '寵物住宿'],
                    ['url' => url('/shop'),            'label' => '線上商城'],
                    ['url' => url('/articles'),        'label' => '寵物專欄'],
                    ['url' => url('/about'),           'label' => '關於我們'],
                ];
                @endphp
                @foreach($navItems as $item)
                <a href="{{ $item['url'] }}"
                   class="nav-link relative text-sm tracking-widest transition-colors duration-300 pb-1
                          {{ $heroNav ? 'text-cream/80 hover:text-cream' : 'text-ink hover:text-accent' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>

            {{-- 右側：購物車 + 會員/登入 --}}
            <div class="hidden lg:flex items-center gap-5">
                {{-- 購物車圖示 --}}
                @php $navCartCount = collect(session('cart', []))->sum('qty'); @endphp
                <a href="{{ route('shop.cart') }}" id="nav-cart-link"
                   class="nav-btn relative flex items-center transition-colors duration-300
                          {{ $heroNav ? 'text-cream/80 hover:text-cream' : 'text-ink hover:text-accent' }}"
                   aria-label="購物車">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.916-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                    <span id="nav-cart-count"
                          class="{{ $navCartCount > 0 ? '' : 'hidden' }}
                                 absolute -top-1.5 -right-1.5 bg-accent text-cream text-[9px]
                                 w-4 h-4 rounded-full flex items-center justify-center font-medium">
                        {{ $navCartCount }}
                    </span>
                </a>

                @auth
                @if(in_array(Auth::user()->role->value, ['admin', 'groomer']))
                <a href="{{ url('/manage-panel') }}"
                   class="nav-btn text-xs tracking-widest transition-colors duration-300 border px-4 py-1.5
                          {{ $heroNav
                             ? 'text-amber-300 hover:text-amber-200 border-amber-400/50 hover:border-amber-300'
                             : 'text-accent hover:text-accent-light border-accent/40 hover:border-accent' }}">
                    管理後台
                </a>
                @endif
                <a href="{{ route('member.index') }}"
                   class="nav-btn text-sm tracking-widest transition-colors duration-300 border px-4 py-1.5
                          {{ $heroNav
                             ? 'text-cream/80 hover:text-cream border-cream/30 hover:border-cream'
                             : 'text-ink hover:text-accent border-ink/20 hover:border-accent' }}">
                    會員中心
                </a>
                @else
                <a href="{{ url('/login') }}"
                   class="nav-btn text-sm tracking-widest transition-colors duration-300
                          {{ $heroNav ? 'text-cream/80 hover:text-cream' : 'text-ink hover:text-accent' }}">
                    登入
                </a>
                @endauth
            </div>

            {{-- 手機漢堡 --}}
            <button id="menu-toggle"
                    class="lg:hidden transition-colors duration-300
                           {{ $heroNav ? 'text-cream' : 'text-ink' }}"
                    aria-label="選單">
                <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- 手機展開選單 --}}
    <div id="mobile-menu" class="hidden lg:hidden bg-cream border-t border-border shadow-md">
        <nav class="max-w-7xl mx-auto px-6 py-6 flex flex-col gap-5">
            <a href="{{ url('/') }}"               class="text-sm tracking-widest text-ink py-1">首頁</a>
            <a href="{{ url('/services') }}"       class="text-sm tracking-widest text-ink py-1">寵物美容</a>
            <a href="{{ url('/accommodation') }}"  class="text-sm tracking-widest text-ink py-1">寵物住宿</a>
            <a href="{{ url('/shop') }}"           class="text-sm tracking-widest text-ink py-1">線上商城</a>
            <a href="{{ url('/articles') }}"       class="text-sm tracking-widest text-ink py-1">寵物專欄</a>
            <a href="{{ url('/about') }}"          class="text-sm tracking-widest text-ink py-1">關於我們</a>
            <hr class="border-border">
            @auth
            @if(in_array(Auth::user()->role->value, ['admin', 'groomer']))
            <a href="{{ url('/manage-panel') }}" class="text-sm tracking-widest text-amber-600 py-1 font-medium">管理後台</a>
            @endif
            <a href="{{ route('member.index') }}" class="text-sm tracking-widest text-accent py-1">會員中心</a>
            @else
            <a href="{{ url('/login') }}" class="text-sm tracking-widest text-muted py-1">登入</a>
            @endauth
        </nav>
    </div>
</header>

{{-- 主內容 --}}
<main>@yield('content')</main>

{{-- ══ Footer ══════════════════════════════════════════ --}}
<footer class="bg-ink">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-16 pb-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 mb-14">

            {{-- 品牌 --}}
            <div class="col-span-2 md:col-span-1">
                <div class="mb-5">
                    <p class="font-serif text-2xl tracking-widest text-cream">禮寵</p>
                    <p class="text-[10px] tracking-[0.3em] text-cream/35 mt-1 uppercase">Reiko Pet</p>
                </div>
                <p class="text-xs text-cream/40 leading-loose tracking-wide max-w-[200px]">
                    以溫柔呵護每一位毛孩，<br>提供媲美日本標準的美容服務。
                </p>
            </div>

            {{-- 服務 --}}
            <div>
                <h4 class="text-[10px] tracking-[0.35em] uppercase text-cream/30 mb-5">服務</h4>
                <ul class="space-y-3">
                    <li><a href="{{ url('/services') }}" class="text-xs text-cream/50 hover:text-cream tracking-wide">美容服務</a></li>
                    <li><a href="{{ url('/booking') }}"  class="text-xs text-cream/50 hover:text-cream tracking-wide">線上預約</a></li>
                    <li><a href="{{ url('/shop') }}"     class="text-xs text-cream/50 hover:text-cream tracking-wide">線上商城</a></li>
                    <li><a href="{{ url('/articles') }}" class="text-xs text-cream/50 hover:text-cream tracking-wide">寵物專欄</a></li>
                </ul>
            </div>

            {{-- 關於 --}}
            <div>
                <h4 class="text-[10px] tracking-[0.35em] uppercase text-cream/30 mb-5">關於</h4>
                <ul class="space-y-3">
                    <li><a href="{{ url('/about') }}"          class="text-xs text-cream/50 hover:text-cream tracking-wide">關於我們</a></li>
                    <li><a href="{{ url('/about') }}#stores"    class="text-xs text-cream/50 hover:text-cream tracking-wide">門市資訊</a></li>
                    <li><a href="{{ url('/about') }}#careers"   class="text-xs text-cream/50 hover:text-cream tracking-wide">加入我們</a></li>
                    <li><a href="{{ url('/contact') }}"         class="text-xs text-cream/50 hover:text-cream tracking-wide">聯絡我們</a></li>
                </ul>
            </div>

            {{-- 服務時間 & 聯絡 --}}
            <div>
                <h4 class="text-[10px] tracking-[0.35em] uppercase text-cream/30 mb-5">服務時間</h4>
                <ul class="space-y-2.5 mb-6">
                    <li class="flex items-center justify-between gap-4">
                        <span class="text-xs text-cream/40 tracking-wide">週一 ～ 週五</span>
                        <span class="text-xs text-cream/55 tracking-wide">10:00 – 19:00</span>
                    </li>
                    <li class="flex items-center justify-between gap-4">
                        <span class="text-xs text-cream/40 tracking-wide">週六</span>
                        <span class="text-xs text-cream/55 tracking-wide">09:00 – 18:00</span>
                    </li>
                    <li class="flex items-center justify-between gap-4">
                        <span class="text-xs text-cream/40 tracking-wide">週日</span>
                        <span class="text-xs text-red-400/60 tracking-wide">公休</span>
                    </li>
                </ul>
                <div class="border-t border-cream/10 pt-4">
                    <h4 class="text-[10px] tracking-[0.35em] uppercase text-cream/30 mb-3">聯絡電話</h4>
                    <a href="tel:0766011111" class="text-xs text-cream/50 hover:text-cream tracking-wide">07-601-1111</a>
                </div>
            </div>

        </div>
        <div class="border-t border-cream/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-3">
            <p class="text-[11px] tracking-widest text-cream/25">© 2026 禮寵 Reiko Pet. All rights reserved.</p>
            <a href="{{ url('/about') }}#careers"
               class="text-[11px] tracking-[0.3em] uppercase text-cream/35 hover:text-cream/70 border-b border-cream/15 hover:border-cream/40 pb-0.5">
                We're Hiring — 加入我們
            </a>
        </div>
    </div>
</footer>

<script>
(function () {
    const navbar   = document.getElementById('navbar');
    const isHero   = navbar.dataset.hero === 'true';
    const logoMain = document.getElementById('logo-main');
    const logoSub  = document.getElementById('logo-sub');

    function applyScrolled() {
        navbar.classList.remove('bg-transparent');
        navbar.classList.add('bg-cream/97', 'backdrop-blur-sm', 'border-b', 'border-border', 'shadow-sm');
        navbar.querySelectorAll('.nav-link, .nav-btn').forEach(el => {
            el.classList.replace('text-cream/80', 'text-ink');
            el.classList.replace('hover:text-cream', 'hover:text-accent');
            el.classList.replace('border-cream/30', 'border-ink/20');
            el.classList.replace('hover:border-cream', 'hover:border-accent');
        });
        if (logoMain) { logoMain.classList.replace('text-cream', 'text-accent'); }
        if (logoSub)  { logoSub.classList.replace('text-cream/50', 'text-muted'); }
        const toggle = document.getElementById('menu-toggle');
        if (toggle) { toggle.classList.replace('text-cream', 'text-ink'); }
    }

    function applyTop() {
        navbar.classList.add('bg-transparent');
        navbar.classList.remove('bg-cream/97', 'backdrop-blur-sm', 'border-b', 'border-border', 'shadow-sm');
        navbar.querySelectorAll('.nav-link, .nav-btn').forEach(el => {
            el.classList.replace('text-ink', 'text-cream/80');
            el.classList.replace('hover:text-accent', 'hover:text-cream');
            el.classList.replace('border-ink/20', 'border-cream/30');
            el.classList.replace('hover:border-accent', 'hover:border-cream');
        });
        if (logoMain) { logoMain.classList.replace('text-accent', 'text-cream'); }
        if (logoSub)  { logoSub.classList.replace('text-muted', 'text-cream/50'); }
        const toggle = document.getElementById('menu-toggle');
        if (toggle) { toggle.classList.replace('text-ink', 'text-cream'); }
    }

    if (isHero) {
        const onScroll = () => window.scrollY > 60 ? applyScrolled() : applyTop();
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // 手機選單
    document.getElementById('menu-toggle').addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('hidden');
        document.getElementById('icon-open').classList.toggle('hidden');
        document.getElementById('icon-close').classList.toggle('hidden');
    });
})();
</script>

@stack('scripts')
</body>
</html>
