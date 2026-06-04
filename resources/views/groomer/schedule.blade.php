@extends('layouts.app')
@section('title', '美容師排程 — 禮寵 Reiko Pet')

@section('content')
<div class="pt-28 pb-16 min-h-screen bg-cream">
    <div class="max-w-4xl mx-auto px-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-1">Groomer Panel</p>
                <h1 class="font-serif text-2xl text-ink tracking-widest">美容師排程</h1>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                       class="text-sm border border-border bg-white px-3 py-2 text-ink tracking-wide
                              focus:outline-none focus:border-accent">
                <button type="submit"
                        class="text-[11px] tracking-[0.25em] uppercase px-4 py-2 bg-accent text-cream">
                    查詢
                </button>
            </form>
        </div>

        {{-- Date display --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('groomer.schedule', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
               class="w-8 h-8 flex items-center justify-center border border-border hover:border-accent text-muted hover:text-accent transition-colors">
                ‹
            </a>
            <span class="text-sm text-ink tracking-widest font-serif">{{ $date->format('Y 年 m 月 d 日') }}（{{ ['日','一','二','三','四','五','六'][$date->dayOfWeek] }}）</span>
            <a href="{{ route('groomer.schedule', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
               class="w-8 h-8 flex items-center justify-center border border-border hover:border-accent text-muted hover:text-accent transition-colors">
                ›
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm tracking-wide">
            {{ session('success') }}
        </div>
        @endif

        {{-- Appointments --}}
        @if($appointments->isEmpty())
        <div class="text-center py-20 text-muted tracking-widest text-sm border border-border bg-white">
            今日無預約
        </div>
        @else
        <div class="space-y-4">
            @foreach($appointments as $appt)
            <div class="bg-white border border-border p-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    {{-- 左側資訊 --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="font-serif text-base text-ink tracking-widest">
                                {{ $appt->appointment_at->format('H:i') }}
                            </span>
                            <span class="text-[10px] tracking-[0.2em] px-2.5 py-1 {{ $appt->status->color() }}">
                                {{ $appt->status->label() }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-x-8 gap-y-1.5 text-xs text-muted tracking-wide">
                            <div><span class="text-ink/60">顧客</span>　{{ $appt->customer->name }}</div>
                            <div><span class="text-ink/60">電話</span>　{{ $appt->customer->phone ?? '—' }}</div>
                            <div><span class="text-ink/60">寵物</span>　{{ $appt->pet->name }}（{{ $appt->pet->breed ?? $appt->pet->type }}）</div>
                            <div><span class="text-ink/60">門市</span>　{{ $appt->store->name }}</div>
                            <div class="col-span-2"><span class="text-ink/60">服務</span>　{{ $appt->service->name }}</div>
                            @if($appt->addons->isNotEmpty())
                            <div class="col-span-2">
                                <span class="text-ink/60">加值</span>　{{ $appt->addons->pluck('addonService.name')->join('、') }}
                            </div>
                            @endif
                            @if($appt->note)
                            <div class="col-span-2"><span class="text-ink/60">備註</span>　{{ $appt->note }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- 右側更新表單 --}}
                    <form method="POST"
                          action="{{ route('groomer.status', $appt) }}"
                          class="flex flex-col gap-2 min-w-[180px]">
                        @csrf
                        @method('PUT')
                        <select name="status"
                                class="text-xs border border-border bg-white px-3 py-2 text-ink tracking-wide
                                       focus:outline-none focus:border-accent w-full">
                            <option value="pending"     {{ $appt->status->value === 'pending'     ? 'selected' : '' }}>待服務</option>
                            <option value="in_progress" {{ $appt->status->value === 'in_progress' ? 'selected' : '' }}>進行中</option>
                            <option value="completed"   {{ $appt->status->value === 'completed'   ? 'selected' : '' }}>已完成</option>
                            <option value="cancelled"   {{ $appt->status->value === 'cancelled'   ? 'selected' : '' }}>已取消</option>
                        </select>
                        <textarea name="note" rows="2"
                                  placeholder="服務備註（選填）"
                                  class="text-xs border border-border bg-white px-3 py-2 text-muted tracking-wide
                                         focus:outline-none focus:border-accent resize-none w-full">{{ $appt->note }}</textarea>
                        <button type="submit"
                                class="text-[10px] tracking-[0.25em] uppercase py-2
                                       bg-accent text-cream hover:bg-accent-light transition-colors">
                            更新狀態
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
