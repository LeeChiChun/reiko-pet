@extends('layouts.app')
@section('title', '商城訂單 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        {{-- Header --}}
        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">商城訂單紀錄</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-muted tracking-widest hover:text-ink transition-colors">登出</button>
            </form>
        </div>

        {{-- Nav Tabs --}}
        @include('member._tabs')

        @if($orders->isEmpty())
        <div class="bg-white border border-border p-14 text-center">
            <p class="text-sm text-muted tracking-wide mb-6">目前沒有商城訂單紀錄</p>
            <a href="{{ route('shop.index') }}"
               class="inline-flex items-center gap-2 bg-accent text-cream text-[11px]
                      tracking-[0.3em] uppercase px-8 py-3">
                前往商城
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white border border-border p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-[10px] tracking-[0.25em] uppercase {{ $order->status->color() }} px-2.5 py-0.5">
                                {{ $order->status->label() }}
                            </span>
                            <span class="text-xs text-muted tracking-wide">訂單編號：{{ $order->order_no }}</span>
                        </div>
                        <p class="text-xs text-muted tracking-wide">{{ $order->created_at->format('Y年m月d日 H:i') }}</p>
                    </div>
                    <p class="text-accent tracking-wide font-medium text-sm shrink-0">NT$ {{ number_format($order->total) }}</p>
                </div>
                <div class="border-t border-border pt-4 space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted tracking-wide">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                        <span class="text-ink tracking-wide">NT$ {{ number_format($item['amount']) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
        @endif

    </div>
</section>

@endsection
