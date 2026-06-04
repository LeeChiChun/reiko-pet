@extends('layouts.app')
@php $heroNav = true; @endphp
@section('title', '禮寵 Reiko Pet — 頂級寵物美容預約')

@section('content')

{{-- ══════════════════════════════════════════════════════
  SECTION 1 ▸ HERO
══════════════════════════════════════════════════════ --}}
<section class="relative min-h-screen flex items-center bg-ink overflow-hidden">

    {{-- 背景圖片（CMS 可替換）--}}
    @if(!empty($heroSettings['image']))
    <div class="absolute inset-0 z-0">
        <img src="{{ Storage::url($heroSettings['image']) }}"
             alt=""
             class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-ink/65"></div>
    </div>
    @endif

    {{-- 格線紋理 --}}
    <div class="absolute inset-0 opacity-[0.035]"
         style="background-image:
            repeating-linear-gradient(0deg,  transparent, transparent 47px, #fff 47px, #fff 48px),
            repeating-linear-gradient(90deg, transparent, transparent 47px, #fff 47px, #fff 48px);">
    </div>

    {{-- 右側裝飾色塊 --}}
    <div class="absolute right-0 top-0 bottom-0 w-[28%] bg-accent/20 hidden xl:block pointer-events-none"></div>

    {{-- 左細線 --}}
    <div class="absolute left-8 top-1/2 -translate-y-1/2 w-px h-28 bg-cream/10 hidden lg:block"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-10 w-full py-44">
        <div class="text-center mx-auto max-w-2xl">
            <div class="flex items-center justify-center gap-4 mb-12">
                <span class="w-8 h-px bg-warm-gray block shrink-0"></span>
                <span class="text-[11px] tracking-[0.45em] uppercase text-warm-gray">{{ $heroSettings['badge'] }}</span>
                <span class="w-8 h-px bg-warm-gray block shrink-0"></span>
            </div>

            @if($heroSettings['title'])
            <h1 class="font-serif text-5xl lg:text-[4.25rem] text-cream leading-[1.25] tracking-wide mb-10">
                {!! nl2br(e($heroSettings['title'])) !!}
            </h1>
            @else
            <h1 class="font-serif text-5xl lg:text-[4.25rem] text-cream leading-[1.25] tracking-wide mb-10">
                讓每一次梳理，<br>
                <em class="not-italic text-warm-gray">都成為</em><br>
                愛的語言
            </h1>
            @endif

            <p class="text-sm text-cream/55 leading-[2.1] tracking-wider mb-16 font-light">
                @if($heroSettings['subtitle'])
                    {!! nl2br(e($heroSettings['subtitle'])) !!}
                @else
                    以燕巢職人精神為核心，為每一位毛孩提供<br>
                    細緻、安心、如回家般溫柔的美容體驗。
                @endif
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/booking') }}"
                   class="inline-flex items-center justify-center gap-3 bg-accent hover:bg-accent-light
                          text-cream text-[11px] tracking-[0.35em] uppercase px-10 py-4">
                    立即預約
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                    </svg>
                </a>
                <a href="{{ url('/services') }}"
                   class="inline-flex items-center justify-center border border-cream/30 hover:border-cream/60
                          text-cream/65 hover:text-cream text-[11px] tracking-[0.35em] uppercase px-10 py-4">
                    服務介紹
                </a>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-cream/20 select-none">
        <span class="text-[9px] tracking-[0.5em] uppercase">Scroll</span>
        <div class="w-px h-10 bg-cream/15"></div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
  SECTION 2 ▸ 品牌理念
══════════════════════════════════════════════════════ --}}
<section class="py-28 lg:py-36 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">

            {{-- 左側圖片（CMS 可替換）--}}
            <div class="relative w-full">
                <div class="aspect-square bg-cream-alt flex items-center justify-center relative overflow-hidden">
                    @if(!empty($brandSettings['philosophy_image']))
                        <img src="{{ Storage::url($brandSettings['philosophy_image']) }}"
                             alt="品牌理念"
                             class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-5 border border-warm-gray/25"></div>
                        <div class="text-center z-10">
                            <p class="font-serif text-[8rem] leading-none text-accent/12 select-none">禮</p>
                            <p class="text-[10px] tracking-[0.5em] text-muted mt-3 uppercase">Reiko</p>
                        </div>
                        @foreach([['-top-px -left-px','tl'],['-top-px -right-px','tr'],['-bottom-px -left-px','bl'],['-bottom-px -right-px','br']] as [$pos,$_])
                        <div class="absolute {{ $pos }} w-4 h-4 border-warm-gray/40
                            {{ str_contains($pos,'top') && str_contains($pos,'left') ? 'border-t border-l' : '' }}
                            {{ str_contains($pos,'top') && str_contains($pos,'right') ? 'border-t border-r' : '' }}
                            {{ str_contains($pos,'bottom') && str_contains($pos,'left') ? 'border-b border-l' : '' }}
                            {{ str_contains($pos,'bottom') && str_contains($pos,'right') ? 'border-b border-r' : '' }}">
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="absolute -bottom-5 -right-5 bg-accent text-cream px-7 py-3 hidden lg:block">
                    <p class="text-[10px] tracking-[0.3em] uppercase">Est. 2026</p>
                </div>
            </div>

            {{-- 文字 --}}
            <div>
                <div class="flex items-center gap-4 mb-7">
                    <span class="w-8 h-px bg-warm-gray block"></span>
                    <span class="text-[10px] tracking-[0.4em] uppercase text-muted">Our Philosophy</span>
                </div>
                <h2 class="font-serif text-4xl lg:text-[2.6rem] text-ink leading-[1.4] tracking-wide mb-7">
                    以「禮」為心，<br>以溫柔為手
                </h2>
                <p class="text-sm text-muted leading-[2.2] tracking-wide font-light mb-5">
                    {{ $brandSettings['story_text_1'] ?? '「禮寵」中的「禮」，日文讀作「れい（Rei）」，象徵敬意與溫柔；品牌英文名 Reiko 正是由此而來。禮，是我們對每一位毛孩的承諾。' }}
                </p>
                <p class="text-sm text-muted leading-[2.2] tracking-wide font-light">
                    {{ $brandSettings['story_text_2'] ?? '美容師皆受過嚴格培訓，擅長以「零壓力」方式讓寵物放鬆，讓每次美容都成為毛孩期待的美好時光。' }}
                </p>

                <div class="mt-10 pt-8 border-t border-border grid grid-cols-3 gap-6">
                    @foreach([
                        [$brandSettings['stat_stores']       ?? '3+',   '高雄門市'],
                        [$brandSettings['stat_pets']         ?? '500+',  '服務毛孩'],
                        [$brandSettings['stat_satisfaction'] ?? '98%',   '顧客滿意'],
                    ] as [$n, $l])
                    <div>
                        <p class="font-serif text-3xl text-accent leading-none">{{ $n }}</p>
                        <p class="text-[11px] tracking-widest text-muted mt-2">{{ $l }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
  SECTION 3 ▸ 最新公告
══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-cream-alt">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="flex items-center gap-4 mb-5">
                    <span class="w-8 h-px bg-warm-gray block"></span>
                    <span class="text-[10px] tracking-[0.4em] uppercase text-muted">Announcements</span>
                </div>
                <h2 class="font-serif text-3xl text-ink tracking-wide">最新公告</h2>
            </div>
            <a href="{{ route('announcements.index') }}" class="text-[11px] tracking-[0.3em] uppercase text-accent border-b border-accent pb-0.5 hidden sm:block">
                全部公告 →
            </a>
        </div>

        <div class="divide-y divide-border bg-cream">
            @forelse($announcements as $ann)
            <a href="{{ route('announcements.show', $ann) }}"
               class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-8 px-8 py-6
                      hover:bg-cream-alt/50 transition-colors group">
                <span class="text-xs text-muted tracking-wider shrink-0 font-light">
                    {{ \Carbon\Carbon::parse($ann->published_at)->format('Y.m.d') }}
                </span>
                <span class="text-[10px] tracking-[0.25em] uppercase bg-accent/10 text-accent px-3 py-1 shrink-0">
                    {{ $ann->tag }}
                </span>
                <p class="text-sm text-ink tracking-wide flex-1">{{ $ann->title }}</p>
                <svg class="w-4 h-4 text-warm-gray shrink-0 hidden sm:block group-hover:text-accent transition-colors"
                     fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
            @empty
            <div class="px-8 py-10 text-center text-muted text-sm tracking-wide">目前沒有最新公告</div>
            @endforelse
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════
  SECTION 4 ▸ 活動優惠
══════════════════════════════════════════════════════ --}}
<section class="py-24 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="flex items-center gap-4 mb-5">
                    <span class="w-8 h-px bg-warm-gray block"></span>
                    <span class="text-[10px] tracking-[0.4em] uppercase text-muted">Promotions</span>
                </div>
                <h2 class="font-serif text-3xl text-ink tracking-wide">活動優惠</h2>
            </div>
            <a href="{{ route('promotions.index') }}" class="text-[11px] tracking-[0.3em] uppercase text-accent border-b border-accent pb-0.5 hidden sm:block">
                全部優惠 →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($promotions as $p)
            <div class="bg-cream-alt overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                {{-- 色條頂欄 --}}
                <div class="{{ $p->color ?? 'bg-accent' }} px-8 py-4 flex items-center justify-between">
                    <span class="text-[10px] tracking-[0.35em] uppercase text-cream font-light">{{ $p->badge }}</span>
                    <span class="text-[10px] text-cream/60 tracking-wide">{{ $p->period }}</span>
                </div>
                {{-- 內容 --}}
                <div class="p-8">
                    <h3 class="font-serif text-xl text-ink tracking-wide mb-4">{{ $p->title }}</h3>
                    <p class="text-sm text-muted leading-relaxed tracking-wide font-light mb-4">{{ $p->description }}</p>
                    @if($p->coupon && $p->coupon->is_active)
                    <div class="flex items-center gap-2 mb-5">
                        <span class="text-[10px] tracking-widest text-accent border border-accent/30 bg-accent/5 px-2.5 py-1">優惠碼</span>
                        <span class="font-mono text-sm text-accent tracking-widest">{{ $p->coupon->code }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] tracking-widest text-muted border border-border px-3 py-1">{{ $p->tag }}</span>
                        <a href="{{ route('promotions.show', $p) }}"
                           class="text-[10px] tracking-[0.3em] uppercase text-accent border-b border-accent pb-0.5">
                            了解更多
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 py-12 text-center text-muted text-sm tracking-wide">目前沒有進行中的優惠活動</div>
            @endforelse
        </div>

    </div>
</section>



{{-- ══════════════════════════════════════════════════════
  SECTION 5 ▸ 本期 DM
══════════════════════════════════════════════════════ --}}
<section class="py-24 bg-accent" x-data="flyerLightbox()">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-14">
            <div class="flex items-center justify-center gap-4 mb-5">
                <span class="w-8 h-px bg-cream/30 block"></span>
                <span class="text-[10px] tracking-[0.4em] uppercase text-cream/50">Monthly Flyer</span>
                <span class="w-8 h-px bg-cream/30 block"></span>
            </div>
            <h2 class="font-serif text-4xl text-cream tracking-wide leading-[1.4] mb-5">本期 DM</h2>
            <p class="text-sm text-cream/65 leading-[2.1] tracking-wide font-light">
                點擊圖片放大瀏覽，或下載保存
            </p>
        </div>

        @if($flyers->isEmpty())
        <div class="text-center text-cream/50 text-sm tracking-wide py-8">目前尚無 DM，敬請期待</div>
        @else
        @php
            $flyerCount = $flyers->count();
            $gridClass  = match(true) {
                $flyerCount === 1 => 'flex justify-center',
                $flyerCount === 2 => 'grid grid-cols-1 sm:grid-cols-2 max-w-2xl mx-auto gap-6',
                default           => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6',
            };
            $cardClass  = $flyerCount === 1 ? 'w-full max-w-sm' : '';
        @endphp
        <div class="{{ $gridClass }}">
            @foreach($flyers as $flyer)
            <div class="{{ $cardClass }}"><?php // wrapper for single-DM width control ?>
            <div class="group relative bg-cream-alt overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                {{-- 圖片（可點擊放大） --}}
                @if($flyer->image_path)
                <button type="button"
                        @click="open('{{ $flyer->imageUrl() }}', '{{ $flyer->title }}')"
                        class="block w-full aspect-[3/4] overflow-hidden cursor-zoom-in">
                    <img src="{{ $flyer->imageUrl() }}" alt="{{ $flyer->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </button>
                @else
                <div class="aspect-[3/4] bg-cream flex items-center justify-center">
                    <span class="font-serif text-6xl text-accent/15">禮</span>
                </div>
                @endif

                {{-- 資訊欄 --}}
                <div class="p-5 bg-cream">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-serif text-sm text-ink tracking-wide truncate">{{ $flyer->title }}</h3>
                            @if($flyer->period)
                            <p class="text-[10px] text-muted tracking-wide mt-1">{{ $flyer->period }}</p>
                            @endif
                        </div>
                        @if($flyer->image_path)
                        <a href="{{ route('flyers.download', $flyer) }}"
                           class="shrink-0 flex items-center gap-1.5 bg-accent hover:bg-accent-light
                                  text-cream text-[10px] tracking-[0.2em] uppercase px-4 py-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            下載
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            </div>{{-- /wrapper --}}
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('flyers.index') }}"
               class="inline-flex items-center gap-2 border border-cream/40 hover:border-cream
                      text-cream/70 hover:text-cream text-[11px] tracking-[0.3em] uppercase px-9 py-3.5 transition-colors">
                查看全部 DM
            </a>
        </div>
        @endif

    </div>

    {{-- Lightbox 遮罩 --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="close()"
         @keydown.escape.window="close()"
         class="fixed inset-0 z-50 bg-black/85 flex items-center justify-center p-4"
         style="display:none">
        <div class="relative max-w-4xl max-h-[90vh] w-full">
            <img :src="currentSrc" :alt="currentTitle"
                 class="max-h-[85vh] max-w-full mx-auto object-contain shadow-2xl">
            <div class="mt-4 text-center">
                <p class="text-cream/70 text-sm tracking-wide" x-text="currentTitle"></p>
            </div>
            <button @click="close()"
                    class="absolute -top-4 -right-4 w-9 h-9 bg-cream/20 hover:bg-cream/40
                           text-cream flex items-center justify-center transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
function flyerLightbox() {
    return {
        isOpen: false,
        currentSrc: '',
        currentTitle: '',
        open(src, title) {
            this.currentSrc  = src;
            this.currentTitle = title;
            this.isOpen = true;
        },
        close() { this.isOpen = false; }
    };
}
</script>
@endpush


{{-- ══════════════════════════════════════════════════════
  SECTION 6 ▸ 精選美容服務
══════════════════════════════════════════════════════ --}}
<section class="py-24 lg:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex items-end justify-between mb-16">
            <div>
                <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-4">Our Services</p>
                <h2 class="font-serif text-3xl lg:text-4xl text-ink tracking-wide">精選美容服務</h2>
            </div>
            <a href="{{ route('services') }}"
               class="hidden sm:inline-flex items-center gap-2 text-xs tracking-[0.3em] uppercase text-muted
                      hover:text-accent transition-colors border-b border-border pb-0.5">
                查看全部
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                </svg>
            </a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-border">
            @foreach($featuredServices as $svc)
            <div class="bg-white p-8 flex flex-col hover:bg-cream transition-colors duration-300 group">
                <div class="w-8 h-px bg-accent mb-6 group-hover:w-12 transition-all duration-300"></div>
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">
                    {{ match($svc->category) {
                        'dog'       => 'Dog Grooming',
                        'cat'       => 'Cat Grooming',
                        'single'    => 'Single Service',
                        'small_pkg' => 'Small Package',
                        'large_pkg' => 'Large Package',
                        default     => 'Service'
                    } }}
                </p>
                <h3 class="font-serif text-lg text-ink tracking-widest mb-4 leading-relaxed">{{ $svc->name }}</h3>
                <p class="text-xs text-muted leading-[2] tracking-wide flex-1 line-clamp-3">{{ $svc->description }}</p>
                <div class="mt-6 pt-5 border-t border-border flex items-center justify-between">
                    <span class="text-accent tracking-wider">NT$ {{ number_format($svc->price) }}</span>
                    <a href="{{ route('booking.step1') }}"
                       class="text-[10px] tracking-[0.3em] uppercase text-muted hover:text-accent transition-colors">
                        預約 →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
  SECTION 7 ▸ 精選商品
══════════════════════════════════════════════════════ --}}
<section class="py-24 lg:py-32 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex items-end justify-between mb-16">
            <div>
                <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-4">Online Shop</p>
                <h2 class="font-serif text-3xl lg:text-4xl text-ink tracking-wide">精選商品</h2>
            </div>
            <a href="{{ route('shop.index') }}"
               class="hidden sm:inline-flex items-center gap-2 text-xs tracking-[0.3em] uppercase text-muted
                      hover:text-accent transition-colors border-b border-border pb-0.5">
                前往商城
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                </svg>
            </a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <a href="{{ route('shop.show', $product) }}" class="group block bg-white border border-border hover:border-accent/40 transition-colors duration-300">
                <div class="aspect-square overflow-hidden bg-cream-alt">
                    @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="font-serif text-4xl text-accent/15">禮</span>
                    </div>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-xs text-muted tracking-[0.2em] mb-1">
                        {{ match($product->category) {
                            'snack'   => 'Snack',
                            'toy'     => 'Toy',
                            'clean'   => 'Clean',
                            'apparel' => 'Apparel',
                            'health'  => 'Health',
                            default   => ''
                        } }}
                    </p>
                    <h3 class="font-serif text-sm text-ink tracking-widest leading-relaxed mb-3">{{ $product->name }}</h3>
                    <p class="text-accent tracking-wide text-sm">NT$ {{ number_format($product->price) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
  SECTION 8 ▸ 熱門文章
══════════════════════════════════════════════════════ --}}
<section class="py-24 lg:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex items-end justify-between mb-16">
            <div>
                <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-4">Pet Column</p>
                <h2 class="font-serif text-3xl lg:text-4xl text-ink tracking-wide">熱門文章</h2>
            </div>
            <a href="{{ route('articles.index') }}"
               class="hidden sm:inline-flex items-center gap-2 text-xs tracking-[0.3em] uppercase text-muted
                      hover:text-accent transition-colors border-b border-border pb-0.5">
                更多文章
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                </svg>
            </a>
        </div>
        <div class="grid lg:grid-cols-3 gap-8">
            @foreach($recentArticles as $article)
            <a href="{{ route('articles.show', $article) }}" class="group block">
                <div class="aspect-[3/2] overflow-hidden bg-cream-alt mb-5">
                    @if($article->cover_image)
                    <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="font-serif text-4xl text-accent/15">禮</span>
                    </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-[10px] tracking-[0.3em] uppercase border border-accent/40 text-accent px-2.5 py-0.5">
                            {{ match($article->category) {
                                'grooming' => '美容知識',
                                'health'   => '健康照護',
                                'feeding'  => '飼養指南',
                                default    => $article->category
                            } }}
                        </span>
                        <span class="text-[10px] text-muted tracking-wide">{{ \Carbon\Carbon::parse($article->published_at)->format('Y.m.d') }}</span>
                    </div>
                    <h3 class="font-serif text-base text-ink tracking-widest leading-relaxed group-hover:text-accent transition-colors mb-2">
                        {{ $article->title }}
                    </h3>
                    <p class="text-xs text-muted leading-[2] tracking-wide line-clamp-2">{{ Str::limit(strip_tags($article->content), 80) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
  SECTION 9 ▸ 門市快速入口
══════════════════════════════════════════════════════ --}}
<section class="bg-ink overflow-hidden">

    {{-- 標題區 --}}
    <div class="py-16 px-6 lg:px-10 text-center border-b border-cream/10">
        <p class="text-[10px] tracking-[0.5em] uppercase text-cream/40 mb-4">Our Stores</p>
        <h2 class="font-serif text-3xl lg:text-4xl text-cream tracking-wide">門市據點</h2>
    </div>

    {{-- 門市卡片 --}}
    <div class="grid md:grid-cols-{{ min($stores->count(), 3) }} divide-y md:divide-y-0 md:divide-x divide-cream/10">
        @foreach($stores as $index => $store)
        <div class="relative p-10 lg:p-14 group hover:bg-accent/8 transition-colors duration-300">
            {{-- 序號水印 --}}
            <span class="absolute top-6 right-8 font-serif text-[5rem] leading-none text-cream/[0.04] select-none pointer-events-none">
                0{{ $index + 1 }}
            </span>
            <p class="text-[10px] tracking-[0.4em] uppercase text-accent/60 mb-6">Store 0{{ $index + 1 }}</p>
            <h3 class="font-serif text-2xl text-cream tracking-widest mb-5 group-hover:text-accent transition-colors duration-300">
                {{ $store->name }}
            </h3>
            <div class="w-8 h-px bg-cream/20 mb-6 group-hover:w-14 transition-all duration-300"></div>
            <div class="space-y-3 mb-8">
                <div class="flex items-start gap-3">
                    <svg class="w-3.5 h-3.5 text-accent/50 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                    <p class="text-xs text-cream/45 tracking-wide leading-[1.9]">{{ $store->address }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-3.5 h-3.5 text-accent/50 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                    <p class="text-xs text-cream/45 tracking-wide">{{ $store->phone }}</p>
                </div>
                @if($store->business_hours)
                <div class="flex items-center gap-3">
                    <svg class="w-3.5 h-3.5 text-accent/50 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-[10px] text-cream/35 tracking-widest">{{ $store->business_hours }}</p>
                </div>
                @endif
            </div>
            <a href="https://maps.google.com?q={{ urlencode($store->address) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 text-[10px] tracking-[0.3em] uppercase text-cream/40
                      hover:text-accent border-b border-cream/20 hover:border-accent pb-0.5 transition-colors duration-200">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
                Google 地圖
            </a>
        </div>
        @endforeach
    </div>

    <div class="py-10 px-6 text-center border-t border-cream/10">
        <a href="{{ route('about') }}"
           class="inline-flex items-center gap-3 border border-cream/20 hover:border-cream/50
                  text-cream/60 hover:text-cream text-[11px] tracking-[0.35em] uppercase px-10 py-4 transition-colors duration-300">
            查看門市詳情
        </a>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
  SECTION 10 ▸ 底部 CTA
══════════════════════════════════════════════════════ --}}
<section class="py-32 bg-cream text-center">
    <div class="max-w-2xl mx-auto px-6">
        <div class="flex items-center justify-center gap-5 mb-10">
            <span class="w-10 h-px bg-border block"></span>
            <span class="font-serif text-xl tracking-[0.3em] text-muted">禮 寵</span>
            <span class="w-10 h-px bg-border block"></span>
        </div>
        <h2 class="font-serif text-4xl lg:text-5xl text-ink tracking-wide leading-snug mb-7">
            讓我們為<br>您的毛孩服務
        </h2>
        <p class="text-sm text-muted leading-[2.1] tracking-wide font-light mb-14 max-w-xs mx-auto">
            線上預約，即時確認，全程透明收費。<br>
            帶著毛孩，走進屬於牠的美容時光。
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/booking') }}"
               class="inline-flex items-center justify-center gap-3 bg-accent hover:bg-accent-light
                      text-cream text-[11px] tracking-[0.35em] uppercase px-12 py-4">
                立即預約
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                </svg>
            </a>
            <a href="{{ url('/register') }}"
               class="inline-flex items-center justify-center border border-ink/20 hover:border-ink
                      text-ink text-[11px] tracking-[0.35em] uppercase px-12 py-4">
                免費加入會員
            </a>
        </div>
    </div>
</section>

@endsection
