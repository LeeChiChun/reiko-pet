@extends('layouts.app')
@section('title', '寵物專欄 — 禮寵 Reiko Pet')

@section('content')

{{-- Page Header --}}
<section class="pt-32 pb-10 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="text-center mb-10">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-5">Pet Column</p>
            <h1 class="font-serif text-4xl lg:text-5xl text-ink tracking-widest leading-relaxed mb-6">寵物專欄</h1>
            <div class="h-px bg-border w-16 mx-auto mb-6"></div>
            <p class="text-sm text-muted tracking-wide leading-loose">美容知識、健康護理、毛孩生活日常，我們的專業知識全為你整理好了。</p>
        </div>

        {{-- 分類 Filter --}}
        <div class="flex flex-wrap justify-center gap-2">
            <a href="{{ route('articles.index') }}"
               class="text-[11px] tracking-[0.3em] uppercase px-5 py-2 border transition-colors duration-200
                      {{ !request('category')
                          ? 'bg-accent text-cream border-accent'
                          : 'border-border text-muted hover:border-accent hover:text-accent' }}">
                全部
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('articles.index', ['category' => $cat]) }}"
               class="text-[11px] tracking-[0.3em] px-5 py-2 border transition-colors duration-200
                      {{ request('category') === $cat
                          ? 'bg-accent text-cream border-accent'
                          : 'border-border text-muted hover:border-accent hover:text-accent' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Articles Grid --}}
<section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">

        @if($articles->isEmpty())
        <div class="text-center py-24 text-muted tracking-widest">此分類暫無文章</div>
        @else

        {{-- 結果數量 --}}
        <p class="text-xs text-muted tracking-wide mb-8">
            共 {{ $articles->total() }} 篇文章
            @if(request('category'))
            — <span class="text-accent">{{ request('category') }}</span>
            @endif
        </p>

        {{-- 統一等高卡片 Grid（全部 3 欄，移除 featured 特殊樣式）--}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($articles as $article)
            <a href="{{ route('articles.show', $article) }}"
               class="group border border-border hover:border-accent transition-colors duration-300 flex flex-col bg-white">

                {{-- 封面圖（固定高度）--}}
                <div class="h-52 overflow-hidden bg-cream-alt flex items-center justify-center shrink-0">
                    @if($article->cover_image)
                        <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <span class="font-serif text-5xl text-accent/20">禮</span>
                    @endif
                </div>

                {{-- 內容（固定佈局）--}}
                <div class="p-6 flex flex-col flex-1">

                    {{-- 分類標籤 --}}
                    <span class="text-[10px] tracking-[0.25em] uppercase bg-accent/10 text-accent px-2.5 py-1 self-start mb-3">
                        {{ $article->category }}
                    </span>

                    {{-- 標題（2 行截斷）--}}
                    <h3 class="font-serif text-sm text-ink tracking-widest leading-relaxed mb-3
                               group-hover:text-accent transition-colors line-clamp-2 h-[2.8em]">
                        {{ $article->title }}
                    </h3>

                    {{-- 摘要（3 行截斷）--}}
                    <p class="text-xs text-muted leading-loose tracking-wide line-clamp-3 flex-1 mb-4">
                        {{ Str::limit(strip_tags($article->content), 90) }}
                    </p>

                    {{-- 底部：作者 / 日期 --}}
                    <div class="flex items-center justify-between text-[11px] text-muted tracking-wide
                                border-t border-border pt-4 mt-auto">
                        <span class="truncate max-w-[60%]">{{ $article->author ?? '禮寵編輯部' }}</span>
                        <span class="shrink-0">{{ $article->published_at?->format('Y.m.d') ?? $article->created_at->format('Y.m.d') }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($articles->hasPages())
        <div class="mt-14 flex justify-center gap-2">
            {{ $articles->withQueryString()->links() }}
        </div>
        @endif

        @endif
    </div>
</section>

@endsection
