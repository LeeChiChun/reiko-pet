@extends('layouts.app')
@section('title', $promotion->title . ' — 禮寵 Reiko Pet')

@section('content')

<div class="pt-24 pb-32 bg-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        {{-- 返回 --}}
        <div class="mb-10">
            <a href="{{ route('promotions.index') }}"
               class="text-xs text-muted tracking-[0.3em] uppercase hover:text-accent transition-colors">
                ← 返回活動列表
            </a>
        </div>

        {{-- 頂欄 --}}
        <div class="{{ $promotion->color ?? 'bg-accent' }} px-10 py-5 flex items-center justify-between">
            <span class="text-[10px] tracking-[0.4em] uppercase text-cream font-light">{{ $promotion->badge }}</span>
            <span class="text-[10px] text-cream/60 tracking-wide">{{ $promotion->period }}</span>
        </div>

        {{-- 內容 --}}
        <div class="bg-cream-alt border-x border-b border-border px-10 py-12">
            <h1 class="font-serif text-3xl text-ink tracking-wide mb-6 leading-relaxed">{{ $promotion->title }}</h1>

            @if($promotion->tag)
            <span class="text-[10px] tracking-widest text-muted border border-border px-3 py-1 inline-block mb-8">
                {{ $promotion->tag }}
            </span>
            @endif

            <p class="text-sm text-muted leading-loose tracking-wide font-light mb-10 whitespace-pre-line">{{ $promotion->description }}</p>

            {{-- 優惠碼區塊 --}}
            @if($promotion->coupon && $promotion->coupon->is_active)
            <div class="border border-accent/30 bg-accent/5 px-8 py-7 mb-10">
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-4">專屬優惠碼</p>
                <div class="flex items-center gap-4">
                    <span id="coupon-code-display"
                          class="font-mono text-2xl text-accent tracking-[0.3em] font-medium select-all">
                        {{ $promotion->coupon->code }}
                    </span>
                    <button type="button" id="copy-btn"
                            onclick="copyCode('{{ $promotion->coupon->code }}')"
                            class="text-[10px] tracking-[0.3em] uppercase border border-accent text-accent px-4 py-2
                                   hover:bg-accent hover:text-cream transition-colors">
                        複製
                    </button>
                    <span id="copy-msg" class="text-xs text-accent hidden tracking-wide">已複製！</span>
                </div>
                <div class="mt-5 space-y-1.5 text-xs text-muted tracking-wide">
                    @if($promotion->coupon->minimum_amount > 0)
                    <p>✦ 最低消費 NT$ {{ number_format($promotion->coupon->minimum_amount) }}</p>
                    @endif
                    @if($promotion->coupon->expires_at)
                    <p>✦ 有效期限至 {{ $promotion->coupon->expires_at->format('Y年m月d日') }}</p>
                    @endif
                    @php
                    $scopeLabel = match($promotion->coupon->scope) {
                        'grooming'      => '寵物美容服務',
                        'accommodation' => '寵物住宿服務',
                        'shop'          => '線上商城',
                        default         => '全館服務',
                    };
                    @endphp
                    <p>✦ 適用於{{ $scopeLabel }}</p>
                    @if(($promotion->coupon->visibility ?? 'public') === 'member')
                    <p>✦ 需登入會員才能使用</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- CTA --}}
            <a href="{{ $promotion->ctaUrl() }}"
               class="inline-flex items-center gap-3 bg-accent text-cream text-[11px] tracking-[0.3em] uppercase px-10 py-4
                      hover:bg-accent-light transition-colors duration-300">
                @if($promotion->coupon)
                    @if($promotion->coupon->scope === 'grooming') 立即預約美容
                    @elseif($promotion->coupon->scope === 'accommodation') 立即預約住宿
                    @elseif($promotion->coupon->scope === 'shop') 前往商城
                    @else 立即使用
                    @endif
                @else
                    了解更多
                @endif
            </a>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        const msg = document.getElementById('copy-msg');
        const btn = document.getElementById('copy-btn');
        msg.classList.remove('hidden');
        btn.textContent = '已複製';
        setTimeout(() => {
            msg.classList.add('hidden');
            btn.textContent = '複製';
        }, 2000);
    });
}
</script>
@endpush
