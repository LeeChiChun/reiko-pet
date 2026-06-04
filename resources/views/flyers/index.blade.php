@extends('layouts.app')
@section('title', 'DM 傳單 — 禮寵 Reiko Pet')

@section('content')

<div class="pt-24 pb-32 bg-cream-alt min-h-screen" x-data="flyerPage()">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        {{-- 頁首 --}}
        <div class="py-14 mb-12 border-b border-border">
            <div class="flex items-center gap-4 mb-5">
                <span class="w-8 h-px bg-warm-gray block"></span>
                <span class="text-[10px] tracking-[0.4em] uppercase text-muted">Monthly Flyer</span>
            </div>
            <h1 class="font-serif text-4xl text-ink tracking-wide">DM 傳單</h1>
            <p class="text-sm text-muted mt-4 tracking-wide font-light">每月精選優惠，點擊放大或下載</p>
        </div>

        @if($flyers->isEmpty())
        <div class="py-24 text-center text-muted text-sm tracking-wide">目前尚無 DM，敬請期待</div>
        @else

        @php $flyerList = $flyers->values(); @endphp

        {{-- ── 主視覺輪播 ───────────────────────────────────── --}}
        <div class="relative mb-16">
            <div class="grid lg:grid-cols-2 gap-0 bg-cream border border-border overflow-hidden shadow-lg">

                {{-- 圖片區 --}}
                <div class="relative aspect-[3/4] lg:aspect-auto lg:min-h-[560px] overflow-hidden cursor-zoom-in bg-cream-alt"
                     @click="openLightbox(flyerList[current].imageUrl, flyerList[current].title)">
                    <template x-for="(f, i) in flyerList" :key="i">
                        <div x-show="current === i"
                             x-transition:enter="transition-opacity duration-500"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="absolute inset-0">
                            <img :src="f.imageUrl" :alt="f.title"
                                 class="w-full h-full object-contain" style="display:block">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 bg-black/10">
                                <svg class="w-10 h-10 text-white drop-shadow" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196 7.5 7.5 0 0016.803 15.803z"/>
                                </svg>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- 資訊區 --}}
                <div class="flex flex-col justify-between p-10 lg:p-14">
                    <div>
                        <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-6">
                            <span x-text="current + 1"></span> / <span x-text="flyerList.length"></span>
                        </p>
                        <h2 class="font-serif text-2xl lg:text-3xl text-ink tracking-widest leading-relaxed mb-4"
                            x-text="flyerList[current].title"></h2>
                        <p class="text-xs text-accent tracking-widest mb-3" x-text="flyerList[current].period || ''"></p>
                        <p class="text-sm text-muted/80 tracking-wide leading-loose font-light"
                           x-text="flyerList[current].description || ''"></p>
                    </div>

                    <div class="space-y-6 mt-10">
                        {{-- 操作按鈕 --}}
                        <div class="flex gap-4">
                            <button type="button"
                                    @click="openLightbox(flyerList[current].imageUrl, flyerList[current].title)"
                                    class="flex items-center gap-2 border border-border text-ink text-[11px]
                                           tracking-[0.25em] uppercase px-5 py-2.5
                                           hover:border-accent hover:text-accent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196 7.5 7.5 0 0016.803 15.803z"/>
                                </svg>
                                放大
                            </button>
                            <a :href="flyerList[current].downloadUrl"
                               class="flex items-center gap-2 bg-accent text-cream text-[11px]
                                      tracking-[0.25em] uppercase px-5 py-2.5
                                      hover:bg-accent-light transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                                下載
                            </a>
                        </div>

                        {{-- 前後控制 --}}
                        <div class="flex items-center gap-4">
                            <button @click="prev()"
                                    class="w-10 h-10 border border-border flex items-center justify-center
                                           text-muted hover:border-accent hover:text-accent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                </svg>
                            </button>
                            <div class="flex gap-2">
                                <template x-for="(f, i) in flyerList" :key="i">
                                    <button @click="current = i"
                                            :class="current === i ? 'bg-accent w-6' : 'bg-border w-2 hover:bg-muted'"
                                            class="h-2 transition-all duration-300 rounded-full"></button>
                                </template>
                            </div>
                            <button @click="next()"
                                    class="w-10 h-10 border border-border flex items-center justify-center
                                           text-muted hover:border-accent hover:text-accent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 縮圖列表 ─────────────────────────────────────── --}}
        @if($flyerList->count() > 1)
        <div>
            <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-6">全部傳單</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($flyerList as $i => $flyer)
                <button type="button"
                        @click="current = {{ $i }}"
                        :class="current === {{ $i }} ? 'border-accent ring-1 ring-accent' : 'border-border hover:border-accent/50'"
                        class="group border overflow-hidden transition-all duration-200 bg-cream">
                    @if($flyer->image_path)
                    <div class="aspect-[3/4] overflow-hidden">
                        <img src="{{ $flyer->imageUrl() }}" alt="{{ $flyer->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @else
                    <div class="aspect-[3/4] bg-cream-alt flex items-center justify-center">
                        <span class="font-serif text-3xl text-accent/15">禮</span>
                    </div>
                    @endif
                    <div class="p-2 border-t border-border">
                        <p class="text-[10px] text-muted tracking-wide truncate text-center">{{ $flyer->title }}</p>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
        @endif

        @endif
    </div>

    {{-- Lightbox --}}
    <div x-show="lightboxOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="lightboxOpen = false"
         @keydown.escape.window="lightboxOpen = false"
         class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
         style="display:none">
        <div class="relative max-w-3xl max-h-[90vh] w-full flex flex-col items-center">
            <img :src="lightboxSrc" :alt="lightboxTitle"
                 class="max-h-[80vh] max-w-full object-contain shadow-2xl">
            <p class="mt-5 text-white/60 text-sm tracking-widest" x-text="lightboxTitle"></p>
            <button @click="lightboxOpen = false"
                    class="absolute -top-4 -right-4 w-9 h-9 bg-white/15 hover:bg-white/35
                           text-white flex items-center justify-center transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function flyerPage() {
    const flyers = @json($flyers->values()->map(fn($f) => [
        'title'       => $f->title,
        'period'      => $f->period,
        'description' => $f->description,
        'imageUrl'    => $f->image_path ? $f->imageUrl() : null,
        'downloadUrl' => route('flyers.download', $f),
    ]));

    return {
        flyerList: flyers,
        current: 0,
        lightboxOpen: false,
        lightboxSrc: '',
        lightboxTitle: '',
        prev() { this.current = (this.current - 1 + this.flyerList.length) % this.flyerList.length; },
        next() { this.current = (this.current + 1) % this.flyerList.length; },
        openLightbox(src, title) {
            if (!src) return;
            this.lightboxSrc = src;
            this.lightboxTitle = title;
            this.lightboxOpen = true;
        },
    };
}
</script>
@endpush

@endsection
