@extends('layouts.app')
@section('title', '最新公告 — 禮寵 Reiko Pet')

@section('content')

<div class="pt-24 pb-32 bg-cream-alt min-h-screen">
    <div class="max-w-4xl mx-auto px-6 lg:px-10">

        {{-- 頁首 --}}
        <div class="py-14 mb-2 border-b border-border">
            <div class="flex items-center gap-4 mb-5">
                <span class="w-8 h-px bg-warm-gray block"></span>
                <span class="text-[10px] tracking-[0.4em] uppercase text-muted">Announcements</span>
            </div>
            <h1 class="font-serif text-4xl text-ink tracking-wide">最新公告</h1>
        </div>

        {{-- 列表 --}}
        <div class="bg-cream divide-y divide-border">
            @forelse($announcements as $ann)
            <a href="{{ route('announcements.show', $ann) }}"
               class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-8 px-8 py-7
                      hover:bg-cream-alt/60 transition-colors group">
                <span class="text-xs text-muted tracking-wider shrink-0 font-light w-24">
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
            <div class="px-8 py-16 text-center text-muted text-sm tracking-wide">目前沒有公告</div>
            @endforelse
        </div>

        {{-- 分頁 --}}
        @if($announcements->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $announcements->links() }}
        </div>
        @endif

    </div>
</div>

@endsection
