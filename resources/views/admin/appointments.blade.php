@extends('layouts.admin')
@section('title', '預約管理')
@section('page-title', 'Appointments')

@section('content')

@php
use App\Enums\AppointmentStatus;
$statusMap = collect(AppointmentStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()]);
@endphp

{{-- Filter --}}
<div class="flex flex-wrap gap-3 mb-8">
    @foreach(array_merge(['all' => '全部'], $statusMap->toArray()) as $val => $label)
    <a href="{{ route('admin.appointments', ['status' => $val]) }}"
       class="text-xs tracking-[0.25em] uppercase px-4 py-2 border transition-colors
              {{ ($status ?? 'all') === $val ? 'bg-accent text-cream border-accent' : 'border-border text-muted hover:border-accent hover:text-accent' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white border border-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-border">
                <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                    <th class="text-left px-6 py-4">預約人</th>
                    <th class="text-left px-6 py-4">毛孩</th>
                    <th class="text-left px-6 py-4">服務</th>
                    <th class="text-left px-6 py-4">門市</th>
                    <th class="text-left px-6 py-4">時間</th>
                    <th class="text-left px-6 py-4">金額</th>
                    <th class="text-left px-6 py-4">狀態</th>
                    <th class="text-left px-6 py-4">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($appointments as $appt)
                <tr class="hover:bg-cream/50 transition-colors">
                    <td class="px-6 py-4 text-ink tracking-wide">{{ $appt->customer->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-muted tracking-wide">{{ $appt->pet->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-muted tracking-wide">{{ $appt->service->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-muted tracking-wide">{{ $appt->store->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-muted tracking-wide whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($appt->appointment_at)->format('m/d H:i') }}
                    </td>
                    <td class="px-6 py-4 text-ink tracking-wide">NT$ {{ number_format($appt->total_price) }}</td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] tracking-widest border px-2.5 py-1 {{ $appt->status->color() }}">
                            {{ $appt->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.appointments.status', $appt) }}" class="flex gap-2">
                            @csrf @method('PUT')
                            <select name="status" class="border border-border text-xs px-2 py-1 text-ink bg-white focus:outline-none focus:border-accent">
                                @foreach($statusMap as $val => $label)
                                <option value="{{ $val }}" {{ $appt->status->value === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">更新</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-12 text-center text-muted tracking-widest">無預約資料</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($appointments->hasPages())
<div class="mt-8">{{ $appointments->links() }}</div>
@endif

@endsection
