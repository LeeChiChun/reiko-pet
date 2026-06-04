@extends('layouts.app')
@section('title', '會員中心 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        {{-- Header --}}
        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">會員中心</h1>
                <p class="text-sm text-muted mt-2 tracking-wide font-light">嗨，{{ $user->name }}！</p>
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

        {{-- Stats Row --}}
        <div class="grid grid-cols-3 gap-5 mb-10">
            @foreach([
                ['累計預約', $totalAppointments, '次', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['毛孩數量', $petsCount, '隻', 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5'],
                ['會員等級', '一般', '', 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
            ] as [$label, $value, $unit, $icon])
            <div class="bg-white border border-border p-7 flex flex-col items-center text-center">
                <svg class="w-6 h-6 text-accent/50 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                </svg>
                <p class="font-serif text-3xl text-accent tracking-widest mb-1">{{ $value }}{{ $unit }}</p>
                <p class="text-[10px] tracking-[0.35em] uppercase text-muted">{{ $label }}</p>
            </div>
            @endforeach
        </div>

        {{-- Two-column layout --}}
        <div class="grid lg:grid-cols-5 gap-8">

            {{-- 即將到來的預約 (3/5) --}}
            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-serif text-lg text-ink tracking-widest">即將到來的預約</h2>
                    <a href="{{ route('member.appointments') }}"
                       class="text-[10px] tracking-[0.3em] uppercase text-accent border-b border-accent pb-0.5">
                        全部記錄 →
                    </a>
                </div>

                @if($upcomingAppointments->isEmpty())
                <div class="bg-white border border-border p-10 text-center">
                    <p class="text-sm text-muted tracking-wide mb-5">目前沒有即將到來的預約</p>
                    <a href="{{ route('booking.step1') }}"
                       class="inline-flex items-center gap-2 bg-accent text-cream text-[11px]
                              tracking-[0.3em] uppercase px-8 py-3">
                        立即預約
                    </a>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($upcomingAppointments as $apt)
                    <div class="bg-white border border-border p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] tracking-[0.25em] uppercase
                                                 {{ match($apt->status->value) {
                                                     'pending'     => 'bg-amber-50 text-amber-600',
                                                     'in_progress' => 'bg-blue-50 text-blue-600',
                                                     'completed'   => 'bg-green-50 text-green-600',
                                                     'cancelled'   => 'bg-red-50 text-red-400',
                                                     default       => 'bg-cream-alt text-muted'
                                                 } }} px-2.5 py-0.5">
                                        {{ match($apt->status->value) {
                                            'pending'     => '待確認',
                                            'in_progress' => '服務中',
                                            'completed'   => '已完成',
                                            'cancelled'   => '已取消',
                                            default       => $apt->status->value
                                        } }}
                                    </span>
                                    <span class="text-xs text-muted">{{ $apt->store->name ?? '' }}</span>
                                </div>
                                <p class="text-sm text-ink tracking-wide">
                                    {{ $apt->pet->name ?? '' }} · {{ $apt->service->name ?? '' }}
                                </p>
                                <p class="text-xs text-muted mt-1 tracking-wide">
                                    {{ \Carbon\Carbon::parse($apt->appointment_at)->format('Y年m月d日 H:i') }}
                                </p>
                            </div>
                            @if($apt->status->canCancel())
                            <form method="POST" action="{{ route('member.appointments.cancel', $apt) }}"
                                  onsubmit="return confirm('確認取消此預約？')">
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

                <div class="mt-4">
                    <a href="{{ route('booking.step1') }}"
                       class="inline-flex items-center gap-2 border border-accent text-accent text-[11px]
                              tracking-[0.3em] uppercase px-8 py-3 hover:bg-accent hover:text-cream transition-colors">
                        新增預約
                    </a>
                </div>
                @endif
            </div>

            {{-- 側欄：近期優惠 (2/5) --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-serif text-lg text-ink tracking-widest">近期優惠</h2>
                    <a href="{{ route('promotions.index') }}"
                       class="text-[10px] tracking-[0.3em] uppercase text-accent border-b border-accent pb-0.5">
                        全部 →
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($homeCoupons as $c)
                    <div class="bg-white border border-border overflow-hidden">
                        <div class="bg-accent px-5 py-2.5 flex items-center justify-between">
                            <span class="text-[10px] tracking-[0.3em] uppercase text-cream">{{ $c->scopeLabel() }}</span>
                            @if($c->expires_at)
                            <span class="text-[10px] text-cream/60">到期 {{ $c->expires_at->format('m/d') }}</span>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-sm text-ink tracking-wide mb-1">{{ $c->name }}</h3>
                            <p class="text-xs text-accent font-medium tracking-wide mb-2">{{ $c->discountLabel() }}</p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="font-mono text-xs text-muted tracking-widest border border-border px-2 py-0.5">{{ $c->code }}</span>
                                <a href="{{ $c->scopeUrl() }}"
                                   class="text-[10px] tracking-[0.25em] uppercase text-accent border-b border-accent pb-0.5">
                                    立即使用
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white border border-border p-6 text-center text-sm text-muted tracking-wide">
                        目前沒有專屬優惠券
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</section>

@endsection
