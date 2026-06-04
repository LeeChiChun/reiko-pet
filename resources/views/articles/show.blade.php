@extends('layouts.app')
@section('title', $article->title . ' — 禮寵 Reiko Pet')

@section('content')

{{-- Article Header --}}
<section class="pt-32 pb-16 bg-cream">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">
        <a href="{{ route('articles.index') }}"
           class="inline-flex items-center gap-2 text-[11px] tracking-[0.3em] uppercase text-muted hover:text-accent transition-colors mb-10">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            返回專欄
        </a>
        <h1 class="font-serif text-3xl lg:text-4xl text-ink tracking-widest leading-relaxed mb-8">
            {{ $article->title }}
        </h1>
        <div class="flex items-center justify-between gap-8 pb-8 border-b border-border">
            <div class="flex items-center gap-8 text-xs text-muted tracking-wide">
                <span>{{ $article->author ?? '禮寵編輯部' }}</span>
                <span>{{ $article->created_at->format('Y年m月d日') }}</span>
            </div>

            {{-- 收藏按鈕 --}}
            @auth
            <button id="bookmark-btn"
                    data-url="{{ route('articles.bookmark', $article) }}"
                    data-csrf="{{ csrf_token() }}"
                    data-bookmarked="{{ $isBookmarked ? 'true' : 'false' }}"
                    class="flex items-center gap-2 text-[11px] tracking-[0.25em] uppercase border px-4 py-2 transition-colors duration-200
                           {{ $isBookmarked
                              ? 'border-accent text-accent bg-accent/5'
                              : 'border-border text-muted hover:border-accent hover:text-accent' }}">
                <svg class="w-3.5 h-3.5" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                </svg>
                <span id="bookmark-label">{{ $isBookmarked ? '已收藏' : '收藏' }}</span>
            </button>
            @else
            <a href="{{ route('login') }}"
               class="flex items-center gap-2 text-[11px] tracking-[0.25em] uppercase border border-border text-muted px-4 py-2 hover:border-accent hover:text-accent transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                </svg>
                登入後收藏
            </a>
            @endauth
        </div>
    </div>
</section>

{{-- Cover Image --}}
@if($article->cover_image)
<div class="bg-cream-alt">
    <div class="max-w-4xl mx-auto">
        <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}"
             class="w-full max-h-[480px] object-cover">
    </div>
</div>
@endif

{{-- Article Content --}}
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">
        <div class="prose prose-sm max-w-none
                    prose-headings:font-serif prose-headings:tracking-widest prose-headings:text-ink
                    prose-p:text-muted prose-p:leading-loose prose-p:tracking-wide
                    prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                    prose-img:mx-auto">
            {!! $article->content !!}
        </div>

        {{-- Back --}}
        <div class="mt-16 pt-10 border-t border-border">
            <a href="{{ route('articles.index') }}"
               class="inline-flex items-center gap-3 text-sm tracking-widest text-muted hover:text-accent transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                回到寵物專欄
            </a>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-cream border-t border-border">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <p class="font-serif text-2xl text-ink tracking-widest mb-6">讓毛孩享受最好的</p>
        <a href="{{ route('booking.step1') }}"
           class="inline-block bg-accent text-cream text-sm tracking-[0.3em] uppercase px-12 py-4
                  hover:bg-accent-light transition-colors duration-300">
            立即預約
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const btn = document.getElementById('bookmark-btn');
    if (!btn) return;

    btn.addEventListener('click', async function () {
        const url  = this.dataset.url;
        const csrf = this.dataset.csrf;
        let bookmarked = this.dataset.bookmarked === 'true';

        btn.disabled = true;
        try {
            const res  = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const data = await res.json();
            bookmarked = data.bookmarked;
            btn.dataset.bookmarked = bookmarked ? 'true' : 'false';

            const label = document.getElementById('bookmark-label');
            const svg   = btn.querySelector('svg');

            if (bookmarked) {
                label.textContent = '已收藏';
                svg.setAttribute('fill', 'currentColor');
                btn.classList.remove('border-border', 'text-muted', 'hover:border-accent', 'hover:text-accent');
                btn.classList.add('border-accent', 'text-accent', 'bg-accent/5');
            } else {
                label.textContent = '收藏';
                svg.setAttribute('fill', 'none');
                btn.classList.remove('border-accent', 'text-accent', 'bg-accent/5');
                btn.classList.add('border-border', 'text-muted', 'hover:border-accent', 'hover:text-accent');
            }
        } catch (e) {
            console.error(e);
        } finally {
            btn.disabled = false;
        }
    });
})();
</script>
@endpush
