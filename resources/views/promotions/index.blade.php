@extends('layouts.app')
@section('title', '活動優惠 — 禮寵 Reiko Pet')

@section('content')

<div class="pt-24 pb-32 bg-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        {{-- 頁首 --}}
        <div class="py-14 mb-10 border-b border-border">
            <div class="flex items-center gap-4 mb-5">
                <span class="w-8 h-px bg-warm-gray block"></span>
                <span class="text-[10px] tracking-[0.4em] uppercase text-muted">Promotions</span>
            </div>
            <h1 class="font-serif text-4xl text-ink tracking-wide">活動優惠</h1>
        </div>

        {{-- 列表 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($promotions as $p)
            <div class="bg-cream-alt overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="{{ $p->color ?? 'bg-accent' }} px-8 py-4 flex items-center justify-between">
                    <span class="text-[10px] tracking-[0.35em] uppercase text-cream font-light">{{ $p->badge }}</span>
                    <span class="text-[10px] text-cream/60 tracking-wide">{{ $p->period }}</span>
                </div>
                <div class="p-8">
                    <h3 class="font-serif text-xl text-ink tracking-wide mb-4">{{ $p->title }}</h3>
                    <p class="text-sm text-muted leading-relaxed tracking-wide font-light mb-4">{{ $p->description }}</p>
                    @if($p->coupon && $p->coupon->is_active)
                    <div class="flex items-center gap-2 mb-5">
                        <span class="text-[10px] tracking-widest text-accent border border-accent/30 bg-accent/5 px-2.5 py-1">
                            優惠碼
                        </span>
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
            <div class="col-span-3 py-20 text-center text-muted text-sm tracking-wide">目前沒有進行中的優惠活動</div>
            @endforelse
        </div>

    </div>
</div>

@endsection
