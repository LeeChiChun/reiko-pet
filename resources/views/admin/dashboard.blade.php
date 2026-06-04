@extends('layouts.admin')
@section('title', '儀表板')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    @foreach([
        ['待確認',   $pendingCount ?? 0, '筆', 'border-amber-300'],
        ['商品數量', $totalProducts ?? 0, '件', 'border-border'],
        ['會員總數', $totalUsers ?? 0, '人', 'border-border'],
        ['總預約數', $totalAppointments ?? 0, '筆', 'border-accent/30'],
    ] as $stat)
    <div class="bg-white border {{ $stat[3] }} border-l-4 p-6">
        <p class="text-[11px] tracking-[0.3em] uppercase text-muted mb-3">{{ $stat[0] }}</p>
        <p class="font-serif text-3xl text-ink tracking-widest">{{ $stat[1] }}<span class="text-sm ml-1 text-muted">{{ $stat[2] }}</span></p>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-8">

    {{-- Recent Appointments --}}
    <div class="bg-white border border-border">
        <div class="px-7 py-5 border-b border-border flex items-center justify-between">
            <h2 class="text-sm tracking-[0.3em] uppercase text-ink">最新預約</h2>
            <a href="{{ route('admin.appointments') }}" class="text-xs text-muted hover:text-accent tracking-wide transition-colors">查看全部</a>
        </div>
        <div class="divide-y divide-border">
            @forelse($recentAppointments ?? [] as $appt)
            <div class="px-7 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-ink tracking-wide">{{ $appt->pet->name ?? '—' }} — {{ $appt->service->name ?? '—' }}</p>
                    <p class="text-xs text-muted tracking-wide mt-0.5">{{ \Carbon\Carbon::parse($appt->appointment_at)->format('m/d H:i') }} · {{ $appt->customer->name ?? '—' }}</p>
                </div>
                <span class="text-[10px] tracking-widest px-2.5 py-1 {{ $appt->status->color() }}">
                    {{ $appt->status->label() }}
                </span>
            </div>
            @empty
            <div class="px-7 py-8 text-center text-muted text-xs tracking-widest">無預約資料</div>
            @endforelse
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="bg-white border border-border">
        <div class="px-7 py-5 border-b border-border">
            <h2 class="text-sm tracking-[0.3em] uppercase text-ink">快速操作</h2>
        </div>
        <div class="p-7 grid grid-cols-2 gap-4">
            @foreach([
                ['新增服務',   route('admin.services')],
                ['新增商品',   route('admin.products')],
                ['新增文章',   route('admin.articles')],
                ['管理門市',   route('admin.stores')],
                ['管理美容師', route('admin.groomers')],
                ['管理加值',   route('admin.addons')],
            ] as $link)
            <a href="{{ $link[1] }}"
               class="border border-border px-5 py-4 text-sm text-muted tracking-widest
                      hover:border-accent hover:text-accent transition-colors text-center">
                {{ $link[0] }}
            </a>
            @endforeach
        </div>
    </div>

</div>

@endsection
