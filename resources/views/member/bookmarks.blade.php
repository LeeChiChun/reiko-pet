@extends('layouts.app')
@section('title', '收藏文章 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        {{-- Header --}}
        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">收藏的文章</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-muted tracking-widest hover:text-ink transition-colors">登出</button>
            </form>
        </div>

        {{-- Nav Tabs --}}
        @include('member._tabs')

        @if(session('success'))
        <div class="mb-8 bg-accent/10 border border-accent/20 px-5 py-4 text-xs text-accent tracking-wide">
            {{ session('success') }}
        </div>
        @endif

        @if($bookmarks->isEmpty())
        <div class="bg-white border border-border p-14 text-center">
            <p class="text-sm text-muted tracking-wide mb-6">還沒有收藏任何文章</p>
            <a href="{{ route('articles.index') }}"
               class="inline-flex items-center gap-2 bg-accent text-cream text-[11px]
                      tracking-[0.3em] uppercase px-8 py-3">
                瀏覽寵物專欄
            </a>
        </div>
        @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookmarks as $bookmark)
            @php $article = $bookmark->article; @endphp
            @if($article)
            <div class="group bg-white border border-border hover:border-accent transition-colors duration-300">
                <a href="{{ route('articles.show', $article) }}" class="block">
                    <div class="aspect-[3/2] overflow-hidden bg-cream-alt">
                        @if($article->cover_image)
                        <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="font-serif text-4xl text-accent/15">禮</span>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] tracking-[0.3em] uppercase border border-accent/40 text-accent px-2 py-0.5">
                            {{ match($article->category) {
                                'grooming' => '美容知識',
                                'health'   => '健康照護',
                                'feeding'  => '飼養指南',
                                default    => $article->category
                            } }}
                        </span>
                    </div>
                    <a href="{{ route('articles.show', $article) }}"
                       class="font-serif text-sm text-ink tracking-widest leading-relaxed hover:text-accent transition-colors line-clamp-2 block mb-3">
                        {{ $article->title }}
                    </a>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-muted tracking-wide">
                            {{ \Carbon\Carbon::parse($article->published_at)->format('Y.m.d') }}
                        </span>
                        <form method="POST" action="{{ route('member.bookmarks.remove', $bookmark) }}"
                              onsubmit="return confirm('確認取消收藏？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-[10px] tracking-[0.25em] uppercase text-muted hover:text-red-400 transition-colors">
                                取消收藏
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="mt-8">{{ $bookmarks->links() }}</div>
        @endif

    </div>
</section>

@endsection
