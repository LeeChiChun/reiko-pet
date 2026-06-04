@extends('layouts.app')
@section('title', '寵物美容 — 禮寵 Reiko Pet')

@section('content')

{{-- ══ 頁首 ══════════════════════════════════════════════ --}}
<div class="pt-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16 lg:py-20">
        <div class="flex items-center gap-4 mb-5">
            <span class="w-8 h-px bg-warm-gray block"></span>
            <span class="text-[10px] tracking-[0.4em] uppercase text-muted">Pet Grooming</span>
        </div>
        <h1 class="font-serif text-4xl lg:text-5xl text-ink tracking-wide">寵物美容</h1>
    </div>

    {{-- ── 頁內小導覽 ─────────────────────────────── --}}
    <div class="border-t border-border sticky top-20 bg-cream/97 backdrop-blur-sm z-40">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex gap-8 overflow-x-auto">
                @foreach([
                    ['#single',    '單項服務'],
                    ['#small-pkg', '小套餐'],
                    ['#large-pkg', '大套餐'],
                    ['#addon',     '加值服務'],
                ] as [$anchor, $label])
                <a href="{{ $anchor }}"
                   class="py-5 text-xs tracking-[0.3em] whitespace-nowrap text-muted hover:text-accent border-b-2 border-transparent hover:border-accent shrink-0">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
  BLOCK 1 ▸ 寵物美容
══════════════════════════════════════════════════════ --}}
<section id="grooming" class="bg-cream scroll-mt-32">

    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        {{-- ─────────── 單項服務 ─────────── --}}
        <div id="single" class="py-20 scroll-mt-44 border-b border-border">
            <div class="mb-12">
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">Individual Services</p>
                <h2 class="font-serif text-3xl text-ink tracking-wide">單項服務</h2>
                <p class="text-sm text-muted mt-3 font-light tracking-wide">不需要整套，只補充毛孩需要的那一項。</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-border">
                @forelse($singles as $svc)
                <div class="bg-cream p-10 group hover:bg-cream-alt transition-colors">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="font-serif text-lg text-ink tracking-wide group-hover:text-accent transition-colors">
                                {{ $svc->name }}
                            </p>
                        </div>
                        <div class="text-right shrink-0 ml-4">
                            <span class="text-[10px] text-muted">NT$</span>
                            <span class="font-serif text-2xl text-ink ml-0.5">{{ number_format($svc->price) }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-muted leading-relaxed tracking-wide font-light">
                        {{ $svc->description ?? '依實際狀況由美容師評估。' }}
                    </p>
                </div>
                @empty
                <div class="bg-cream p-10 col-span-3 text-center text-muted text-sm">暫無資料</div>
                @endforelse

                {{-- 補充說明格 --}}
                <div class="bg-cream-alt p-10 flex flex-col justify-center">
                    <p class="text-xs tracking-[0.3em] uppercase text-muted mb-3">備註</p>
                    <p class="text-sm text-muted leading-relaxed font-light">
                        單項服務可與套餐搭配，<br>或單獨預約。<br>
                        價格依體型調整，詳情請洽門市。
                    </p>
                </div>
            </div>
        </div>

        {{-- ─────────── 全套服務（dog / cat 分類）─────────── --}}
        @if($dogs->isNotEmpty() || $cats->isNotEmpty())
        <div id="full-pkg" class="py-20 scroll-mt-44 border-b border-border">
            <div class="mb-12">
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">Full Grooming</p>
                <h2 class="font-serif text-3xl text-ink tracking-wide">全套服務</h2>
                <p class="text-sm text-muted mt-3 font-light tracking-wide">依犬貓種類設計的專屬全套美容，從清潔到造型一次完成。</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($dogs->merge($cats) as $svc)
                <div class="bg-ink group hover:bg-accent-light transition-colors duration-500 overflow-hidden relative">
                    <div class="absolute right-6 bottom-4 font-serif text-8xl text-cream/5 select-none pointer-events-none leading-none">
                        {{ $svc->category === 'dog' ? '犬' : '猫' }}
                    </div>
                    <div class="p-10 relative z-10">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <span class="text-[10px] tracking-[0.3em] uppercase text-cream/40">
                                    {{ $svc->category === 'dog' ? '🐕 Dog' : '🐈 Cat' }} — Full Service
                                </span>
                                <h3 class="font-serif text-2xl text-cream tracking-wide mt-2">{{ $svc->name }}</h3>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <p class="text-[10px] text-cream/40">NT$</p>
                                <p class="font-serif text-4xl text-cream leading-none mt-1">{{ number_format($svc->price) }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-cream/60 leading-relaxed tracking-wide font-light mb-8">
                            {{ $svc->description ?? '依犬貓品種設計的全套美容，從洗澡、吹整到造型修剪一次完成。' }}
                        </p>
                        @if($svc->duration_minutes)
                        <p class="text-[11px] text-cream/40 tracking-widest mb-8">約 {{ $svc->duration_minutes }} 分鐘</p>
                        @endif
                        <a href="{{ url('/booking') }}"
                           class="inline-flex items-center gap-2 text-[10px] tracking-[0.3em] uppercase
                                  text-cream border-b border-cream/40 pb-0.5 hover:border-cream">
                            立即預約
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ─────────── 小套餐 ─────────── --}}
        <div id="small-pkg" class="py-20 scroll-mt-44 border-b border-border">
            <div class="mb-12">
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">Small Package</p>
                <h2 class="font-serif text-3xl text-ink tracking-wide">小套餐</h2>
                <p class="text-sm text-muted mt-3 font-light tracking-wide">基礎清潔一次搞定，適合定期維護。</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @forelse($smallPkgs as $svc)
                <div class="border border-border bg-cream hover:border-accent transition-colors group">
                    {{-- 頂部色帶 --}}
                    <div class="h-1 {{ str_contains($svc->name, '狗') ? 'bg-accent' : 'bg-warm-gray' }}"></div>
                    <div class="p-10">
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <span class="text-[10px] tracking-[0.3em] uppercase text-muted">
                                    {{ str_contains($svc->name, '狗') ? '🐕 Dog' : '🐈 Cat' }}
                                </span>
                                <h3 class="font-serif text-2xl text-ink tracking-wide mt-2 group-hover:text-accent transition-colors">
                                    {{ $svc->name }}
                                </h3>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <p class="text-[10px] text-muted">NT$</p>
                                <p class="font-serif text-4xl text-accent leading-none mt-1">
                                    {{ number_format($svc->price) }}
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-muted leading-relaxed tracking-wide font-light mb-8">
                            {{ $svc->description ?? '洗澡、吹乾、耳朵清潔、剪指甲。' }}
                        </p>

                        {{-- 包含項目 --}}
                        <div class="border-t border-border pt-6 space-y-2">
                            @foreach(['洗澡 + 吹乾', '耳朵清潔', '剪指甲'] as $item)
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-1 rounded-full bg-accent shrink-0"></div>
                                <span class="text-xs text-muted tracking-wide">{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            <a href="{{ url('/booking') }}"
                               class="inline-flex items-center gap-2 text-[10px] tracking-[0.3em] uppercase
                                      text-accent border-b border-accent pb-0.5 hover:text-accent-light">
                                立即預約
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-2 py-10 text-center text-muted text-sm">暫無資料</div>
                @endforelse
            </div>
        </div>

        {{-- ─────────── 大套餐 ─────────── --}}
        <div id="large-pkg" class="py-20 scroll-mt-44 border-b border-border">
            <div class="mb-12">
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">Large Package</p>
                <h2 class="font-serif text-3xl text-ink tracking-wide">大套餐</h2>
                <p class="text-sm text-muted mt-3 font-light tracking-wide">全套美容造型，讓毛孩煥然一新。</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @forelse($largePkgs as $svc)
                <div class="bg-ink group hover:bg-accent-light transition-colors duration-500 overflow-hidden relative">
                    {{-- 背景大字裝飾 --}}
                    <div class="absolute right-6 bottom-4 font-serif text-8xl text-cream/5 select-none pointer-events-none leading-none">
                        {{ str_contains($svc->name, '狗') ? '犬' : '猫' }}
                    </div>

                    <div class="p-10 relative z-10">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <span class="text-[10px] tracking-[0.3em] uppercase text-cream/40">
                                    {{ str_contains($svc->name, '狗') ? '🐕 Dog' : '🐈 Cat' }} — Full Package
                                </span>
                                <h3 class="font-serif text-2xl text-cream tracking-wide mt-2">{{ $svc->name }}</h3>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <p class="text-[10px] text-cream/40">NT$</p>
                                <p class="font-serif text-4xl text-cream leading-none mt-1">
                                    {{ number_format($svc->price) }}
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-cream/60 leading-relaxed tracking-wide font-light mb-8">
                            {{ $svc->description ?? '全套美容造型服務，從清潔到造型一次完成。' }}
                        </p>

                        <div class="border-t border-cream/10 pt-6 space-y-2 mb-8">
                            @foreach(['造型修剪','洗澡 + 吹乾','耳朵清潔','剪指甲'] as $item)
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-1 rounded-full bg-warm-gray shrink-0"></div>
                                <span class="text-xs text-cream/60 tracking-wide">{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>

                        <a href="{{ url('/booking') }}"
                           class="inline-flex items-center gap-2 text-[10px] tracking-[0.3em] uppercase
                                  text-cream border-b border-cream/40 pb-0.5 hover:border-cream">
                            立即預約
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-2 py-10 text-center text-muted text-sm">暫無資料</div>
                @endforelse
            </div>
        </div>

        {{-- ─────────── 加值服務 ─────────── --}}
        <div id="addon" class="py-20 scroll-mt-44">
            <div class="mb-12">
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">Add-On Services</p>
                <h2 class="font-serif text-3xl text-ink tracking-wide">加值服務</h2>
                <p class="text-sm text-muted mt-3 font-light tracking-wide">可與任何套餐搭配，依寵物種類自動過濾可選項目。</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-border">
                @forelse($addons as $addon)
                <div class="bg-cream p-10 group hover:bg-cream-alt transition-colors">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="text-[10px] tracking-[0.25em] uppercase px-3 py-1
                            {{ $addon->applicable_to === 'dog'  ? 'bg-accent/10 text-accent' :
                               ($addon->applicable_to === 'cat' ? 'bg-warm-gray/20 text-ink'  : 'bg-cream-alt text-muted') }}">
                            {{ $addon->applicable_to === 'dog' ? '🐕 狗狗專用' : ($addon->applicable_to === 'cat' ? '🐈 貓咪專用' : '🐾 通用') }}
                        </span>
                    </div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="font-serif text-lg text-ink tracking-wide group-hover:text-accent transition-colors">
                            {{ $addon->name }}
                        </h3>
                        <div class="text-right shrink-0 ml-4">
                            <span class="text-[10px] text-muted">+NT$</span>
                            <span class="font-serif text-xl text-ink">{{ number_format($addon->price) }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-muted leading-relaxed tracking-wide font-light">
                        {{ $addon->description ?? '' }}
                    </p>
                </div>
                @empty
                <div class="col-span-3 py-10 text-center text-muted text-sm">暫無資料</div>
                @endforelse
            </div>

            <div class="mt-8 p-6 bg-cream-alt border-l-2 border-accent">
                <p class="text-sm text-muted tracking-wide font-light">
                    <span class="text-accent font-medium">預約說明：</span>
                    加值服務於預約流程第三步選擇，系統將依您的寵物種類自動顯示可選項目。
                </p>
            </div>
        </div>

    </div>
</section>




{{-- ══ 底部 CTA ══════════════════════════════════════════ --}}
<section class="py-24 bg-accent text-center">
    <div class="max-w-xl mx-auto px-6">
        <h2 class="font-serif text-3xl text-cream tracking-wide mb-5">準備好了嗎？</h2>
        <p class="text-sm text-cream/60 leading-[2] font-light tracking-wide mb-10">
            立即線上預約，為毛孩安排最舒適的美容體驗。
        </p>
        <a href="{{ url('/booking') }}"
           class="inline-flex items-center gap-3 bg-cream hover:bg-cream-alt text-accent
                  text-[11px] tracking-[0.35em] uppercase px-12 py-4">
            立即預約
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
// 服務頁小導覽 active 高亮
const subNavLinks = document.querySelectorAll('[href^="#"]');
const subSections = ['single','small-pkg','large-pkg','addon'].map(id => document.getElementById(id)).filter(Boolean);

window.addEventListener('scroll', () => {
    let current = 0;
    subSections.forEach((s, i) => {
        if (s && window.scrollY + 200 >= s.offsetTop) current = i;
    });
    subNavLinks.forEach((a, i) => {
        const isActive = i === current;
        a.classList.toggle('text-accent', isActive);
        a.classList.toggle('border-accent', isActive);
        a.classList.toggle('text-muted', !isActive);
        a.classList.toggle('border-transparent', !isActive);
    });
}, { passive: true });
</script>
@endpush
