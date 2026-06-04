@extends('layouts.app')
@section('title', $announcement->title . ' — 禮寵 Reiko Pet')

@section('content')

<div class="pt-24 pb-32 bg-cream-alt min-h-screen">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        {{-- 麵包屑 --}}
        <nav class="py-8 flex items-center gap-2 text-[10px] tracking-[0.3em] uppercase text-muted">
            <a href="{{ route('home') }}" class="hover:text-accent transition-colors">首頁</a>
            <span class="text-border mx-1">/</span>
            <a href="{{ route('announcements.index') }}" class="hover:text-accent transition-colors">最新公告</a>
            <span class="text-border mx-1">/</span>
            <span class="text-ink/60 truncate max-w-xs">{{ $announcement->title }}</span>
        </nav>

        {{-- 文章卡 --}}
        <article class="bg-cream border border-border p-10 lg:p-14">

            {{-- Meta --}}
            <div class="flex items-center gap-4 mb-8">
                <span class="text-[10px] tracking-[0.25em] uppercase bg-accent/10 text-accent px-3 py-1">
                    {{ $announcement->tag }}
                </span>
                <span class="text-xs text-muted tracking-wide font-light">
                    {{ \Carbon\Carbon::parse($announcement->published_at)->format('Y 年 m 月 d 日') }}
                </span>
            </div>

            {{-- 標題 --}}
            <h1 class="font-serif text-2xl lg:text-3xl text-ink tracking-wide leading-[1.6] mb-10">
                {{ $announcement->title }}
            </h1>

            <div class="w-12 h-px bg-accent mb-10"></div>

            {{-- 內容 --}}
            <div class="prose prose-sm max-w-none text-muted leading-[2.1] tracking-wide font-light">
                {!! nl2br(e($announcement->content)) !!}
            </div>

        </article>

        {{-- 返回按鈕 --}}
        <div class="mt-10">
            <a href="{{ route('announcements.index') }}"
               class="inline-flex items-center gap-3 text-[11px] tracking-[0.3em] uppercase text-muted
                      hover:text-accent border-b border-border hover:border-accent pb-0.5 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                返回公告列表
            </a>
        </div>

    </div>
</div>

@endsection
