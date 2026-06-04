@extends('layouts.app')
@section('title', '預約記錄 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">預約記錄</h1>
            </div>
            <a href="{{ route('booking.step1') }}"
               class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-8 py-3.5
                      hover:bg-accent-light transition-colors duration-300">
                新增預約
            </a>
        </div>

        {{-- Nav Tabs --}}
        @include('member._tabs')

        @if(session('success'))
        <div class="mb-8 bg-accent/10 border border-accent/20 px-5 py-4 text-xs text-accent tracking-wide">{{ session('success') }}</div>
        @endif

        @php use App\Enums\AppointmentStatus; @endphp

        @forelse($appointments as $appt)
        <div class="bg-white border border-border mb-4 p-7">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <p class="font-serif text-base text-ink tracking-widest">{{ $appt->service->name ?? '服務已刪除' }}</p>
                        <span class="text-[10px] tracking-widest border px-2.5 py-0.5 {{ $appt->status->color() }}">{{ $appt->status->label() }}</span>
                    </div>
                    <p class="text-xs text-muted tracking-wide">
                        毛孩：{{ $appt->pet->name ?? '—' }}
                        @if($appt->store) · 門市：{{ $appt->store->name }} @endif
                    </p>
                    <p class="text-xs text-muted tracking-wide">
                        時間：{{ \Carbon\Carbon::parse($appt->appointment_at)->format('Y年m月d日 H:i') }}
                    </p>
                    @if($appt->addons->count())
                    <p class="text-xs text-muted tracking-wide">
                        加值：{{ $appt->addons->map(fn($a) => $a->addonService->name ?? '—')->join('、') }}
                    </p>
                    @endif
                    @if($appt->note)
                    <p class="text-xs text-muted tracking-wide">備註：{{ $appt->note }}</p>
                    @endif
                </div>
                <div class="text-right space-y-3">
                    <p class="text-accent font-medium tracking-wide">NT$ {{ number_format($appt->total_price) }}</p>
                    @if($appt->status->canCancel())
                    <form method="POST" action="{{ route('member.appointments.cancel', $appt) }}"
                          onsubmit="return confirm('確定要取消這個預約嗎？')">
                        @csrf
                        <button type="submit"
                                class="text-[11px] tracking-widest text-red-400 hover:text-red-600 transition-colors border border-red-200 px-4 py-1.5 hover:border-red-400">
                            取消預約
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-24 border border-border bg-white">
            <p class="font-serif text-2xl text-ink/30 tracking-widest mb-6">尚無預約記錄</p>
            <a href="{{ route('booking.step1') }}"
               class="inline-block bg-accent text-cream text-sm tracking-[0.3em] uppercase px-10 py-3.5 hover:bg-accent-light transition-colors">
                立即預約
            </a>
        </div>
        @endforelse

        @if($appointments->hasPages())
        <div class="mt-10">{{ $appointments->links() }}</div>
        @endif

    </div>
</section>

@endsection
