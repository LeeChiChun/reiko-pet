@extends('layouts.app')
@section('title', '預約成立 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen flex items-center">
    <div class="max-w-2xl mx-auto px-6 w-full text-center">

        <div class="w-20 h-20 rounded-full border-2 border-accent flex items-center justify-center mx-auto mb-10">
            <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>

        <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-5">Booking Confirmed</p>
        <h1 class="font-serif text-3xl text-ink tracking-widest mb-6 leading-relaxed">預約成立</h1>
        <div class="h-px bg-border w-16 mx-auto mb-10"></div>

        @if(isset($appointment))
        <div class="bg-white border border-border px-10 py-8 mb-10 text-left space-y-4">
            @foreach([
                ['毛孩', $appointment->pet->name ?? '—'],
                ['服務', $appointment->service->name ?? '—'],
                ['門市', $appointment->store->name ?? '—'],
                ['時間', \Carbon\Carbon::parse($appointment->appointment_at)->format('Y年m月d日 H:i')],
                ['費用', 'NT$ '.number_format($appointment->total_price)],
            ] as $row)
            <div class="flex items-center gap-6 text-sm">
                <span class="text-[11px] tracking-[0.3em] uppercase text-muted w-16 shrink-0">{{ $row[0] }}</span>
                <span class="text-ink tracking-wide">{{ $row[1] }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <p class="text-sm text-muted leading-loose tracking-wide mb-10">
            預約確認通知已發送至您的電子信箱。<br>
            如需更改或取消，請至會員中心操作（需在 24 小時前）。
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('member.appointments') }}"
               class="border border-border text-ink text-sm tracking-[0.3em] uppercase px-10 py-3.5
                      hover:border-accent hover:text-accent transition-colors duration-300">
                查看預約
            </a>
            <a href="{{ url('/') }}"
               class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-10 py-3.5
                      hover:bg-accent-light transition-colors duration-300">
                回到首頁
            </a>
        </div>
    </div>
</section>

@endsection
