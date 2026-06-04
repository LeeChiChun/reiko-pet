@extends('layouts.app')
@section('title', '預約成功 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-24 bg-cream-alt min-h-screen">
    <div class="max-w-2xl mx-auto px-6 lg:px-10 text-center">

        <div class="bg-cream border border-border p-12 lg:p-16">

            <div class="w-16 h-16 rounded-full bg-accent/10 flex items-center justify-center mx-auto mb-8">
                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>

            <div class="flex items-center justify-center gap-4 mb-6">
                <span class="w-8 h-px bg-ink/15 block"></span>
                <span class="text-[10px] tracking-[0.5em] uppercase text-muted">Booking Confirmed</span>
                <span class="w-8 h-px bg-ink/15 block"></span>
            </div>

            <h1 class="font-serif text-3xl text-ink tracking-widest mb-4">預約成功</h1>
            <p class="text-sm text-muted tracking-wide leading-loose mb-8">
                感謝您選擇禮寵，您的住宿預約已確認。<br>
                我們將盡快以電話或 Email 與您聯絡確認細節。
            </p>

            @if($orderNo)
            <div class="bg-cream-alt border border-border px-6 py-4 mb-8 inline-block">
                <p class="text-[11px] tracking-[0.35em] uppercase text-muted mb-1">訂單編號</p>
                <p class="font-mono text-ink tracking-widest">{{ $orderNo }}</p>
            </div>
            @endif

            <div class="space-y-3 text-left border-t border-border pt-8 mb-8">
                @foreach([
                    '請於入住前準備好您的寵物疫苗記錄',
                    '入住時間：13:00 – 20:00',
                    '如需更改或取消，請提前 48 小時來電告知',
                    '服務電話：07-601-1111',
                ] as $note)
                <div class="flex items-start gap-3 text-sm text-muted tracking-wide font-light">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0 mt-1.5"></span>
                    {{ $note }}
                </div>
                @endforeach
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}"
                   class="border border-border text-ink text-sm tracking-[0.3em] uppercase py-3 px-8
                          hover:border-accent hover:text-accent transition-colors duration-300">
                    返回首頁
                </a>
                <a href="{{ url('/accommodation') }}"
                   class="bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3 px-8
                          hover:bg-accent-light transition-colors duration-300">
                    再次預約
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
