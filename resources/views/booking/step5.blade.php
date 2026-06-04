@extends('layouts.app')
@section('title', '付款 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        @include('booking._steps', ['current' => 5])

        <div class="mb-10">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Step 05 / 05</p>
            <h1 class="font-serif text-3xl text-ink tracking-widest leading-relaxed">確認預約</h1>
            <p class="text-sm text-muted tracking-wide mt-3">請確認預約資訊後前往付款</p>
        </div>

        @php $b = session('booking', []); @endphp

        <form method="POST" action="{{ route('booking.save5') }}">
            @csrf

            <div class="grid lg:grid-cols-5 gap-8 items-start">

                {{-- 左：注意事項 --}}
                <div class="lg:col-span-3">
                    <div class="bg-white border border-border p-8 space-y-4 text-sm text-muted tracking-wide leading-relaxed">
                        <p class="text-[11px] tracking-[0.35em] uppercase text-ink mb-4">預約注意事項</p>
                        <p>✦ 預約成立後，若需更改或取消，請至少於 24 小時前至會員中心操作。</p>
                        <p>✦ 當日請準時抵達，遲到超過 15 分鐘視為取消。</p>
                        <p>✦ 本服務僅接受線上付款，不接受現金或到店付款。</p>
                    </div>
                </div>

                {{-- 右：訂單摘要 + 付款按鈕 --}}
                <div class="lg:col-span-2">
                    <div class="bg-white border border-border p-7">
                        <p class="text-[11px] tracking-[0.35em] uppercase text-muted mb-6">訂單摘要</p>
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between gap-3">
                                <span class="text-muted tracking-wide">毛孩</span>
                                <span class="text-ink tracking-wide text-right">{{ $b['pet_name'] ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between gap-3">
                                <span class="text-muted tracking-wide">服務</span>
                                <span class="text-ink tracking-wide text-right">{{ $b['service_name'] ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between gap-3">
                                <span class="text-muted tracking-wide">門市</span>
                                <span class="text-ink tracking-wide text-right">{{ $b['store_name'] ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between gap-3">
                                <span class="text-muted tracking-wide shrink-0">時間</span>
                                <span class="text-ink tracking-wide text-right text-xs">
                                    {{ isset($b['appointment_at']) ? \Carbon\Carbon::parse($b['appointment_at'])->format('Y/m/d H:i') : '—' }}
                                </span>
                            </div>
                            <div class="border-t border-border pt-4 flex justify-between">
                                <span class="text-muted tracking-wide">服務費用</span>
                                <span class="text-ink">NT$ {{ number_format($b['service_price'] ?? 0) }}</span>
                            </div>
                            @if(($b['addon_total'] ?? 0) > 0)
                            <div class="flex justify-between">
                                <span class="text-muted tracking-wide">加值服務</span>
                                <span class="text-ink">+NT$ {{ number_format($b['addon_total']) }}</span>
                            </div>
                            @endif
                            <div class="border-t border-border pt-4 flex justify-between">
                                <span class="text-ink font-medium tracking-wide">合計</span>
                                <span class="text-accent text-lg font-medium">NT$ {{ number_format($b['total_price'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 前往付款（緊接訂單摘要）--}}
                    <button type="submit"
                            class="mt-3 w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-4
                                   hover:bg-accent-light transition-colors duration-300">
                        前往付款
                    </button>
                </div>

            </div>

            {{-- 上一步（置中，次要操作）--}}
            <div class="mt-8 text-center">
                <a href="{{ route('booking.step4') }}"
                   class="text-xs text-muted tracking-[0.3em] uppercase hover:text-accent transition-colors duration-300">
                    ← 上一步
                </a>
            </div>

        </form>

    </div>
</section>

@endsection
