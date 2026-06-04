@extends('layouts.app')
@section('title', '住宿預約紀錄 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        {{-- Header --}}
        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">住宿預約紀錄</h1>
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

        @if($reservations->isEmpty())
        <div class="bg-white border border-border p-14 text-center">
            <p class="text-sm text-muted tracking-wide mb-6">目前沒有住宿預約紀錄</p>
            <a href="{{ route('accommodation.index') }}"
               class="inline-flex items-center gap-2 bg-accent text-cream text-[11px]
                      tracking-[0.3em] uppercase px-8 py-3">
                立即預約住宿
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($reservations as $r)
            @php
            $s = ['label' => $r->status->label(), 'class' => $r->status->color()];
            @endphp
            <div class="bg-white border border-border p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[10px] tracking-[0.25em] uppercase {{ $s['class'] }} px-2.5 py-0.5">
                                {{ $s['label'] }}
                            </span>
                            <span class="text-xs text-muted tracking-wide">訂單編號：{{ $r->order_no }}</span>
                        </div>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                            <div>
                                <p class="text-[10px] tracking-widest text-muted uppercase mb-1">寵物</p>
                                <p class="text-ink tracking-wide">{{ $r->pet_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-widest text-muted uppercase mb-1">房型</p>
                                <p class="text-ink tracking-wide">{{ $r->roomName() }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-widest text-muted uppercase mb-1">入住 / 退房</p>
                                <p class="text-ink tracking-wide">{{ $r->check_in->format('Y/m/d') }} – {{ $r->check_out->format('Y/m/d') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-widest text-muted uppercase mb-1">費用</p>
                                <p class="text-accent tracking-wide font-medium">NT$ {{ number_format($r->total_price) }}</p>
                            </div>
                        </div>
                    </div>
                    @if($r->status !== \App\Enums\AccommodationReservationStatus::Cancelled)
                    <form method="POST" action="{{ route('member.accommodation.cancel', $r) }}"
                          onsubmit="return confirm('確認取消此住宿預約？')">
                        @csrf
                        <button type="submit"
                                class="text-[10px] tracking-[0.25em] uppercase text-red-400
                                       border border-red-200 hover:bg-red-50 px-3 py-1.5 transition-colors">
                            取消
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $reservations->links() }}</div>
        @endif

    </div>
</section>

@endsection
