@extends('layouts.admin')
@section('title', '住宿預約管理')
@section('page-title', 'Accommodation Bookings')

@section('content')

{{-- Status Filter --}}
<div class="flex flex-wrap gap-3 mb-8">
    @foreach(array_merge(['all' => '全部'], $statusMap->toArray()) as $val => $label)
    <a href="{{ route('admin.accommodation.bookings', ['status' => $val]) }}"
       class="text-xs tracking-[0.25em] uppercase px-4 py-2 border transition-colors
              {{ $status === $val ? 'bg-accent text-cream border-accent' : 'border-border text-muted hover:border-accent hover:text-accent' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white border border-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-border">
                <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                    <th class="text-left px-6 py-4">訂單編號</th>
                    <th class="text-left px-6 py-4">入住／退房</th>
                    <th class="text-left px-6 py-4">房型</th>
                    <th class="text-left px-6 py-4">顧客</th>
                    <th class="text-left px-6 py-4">寵物</th>
                    <th class="text-left px-6 py-4">金額</th>
                    <th class="text-left px-6 py-4">狀態</th>
                    <th class="text-left px-6 py-4">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($reservations as $r)
                <tr class="hover:bg-cream/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-ink tracking-wide text-xs font-mono">{{ $r->order_no }}</p>
                    </td>
                    <td class="px-6 py-4 text-muted tracking-wide whitespace-nowrap text-xs">
                        <p>{{ $r->check_in->format('Y/m/d') }}</p>
                        <p class="text-muted/60">→ {{ $r->check_out->format('Y/m/d') }}</p>
                        <p class="text-accent/70 mt-0.5">{{ $r->nights }} 晚</p>
                    </td>
                    <td class="px-6 py-4 text-ink tracking-wide">{{ $r->roomName() }}</td>
                    <td class="px-6 py-4">
                        <p class="text-ink tracking-wide">{{ $r->guest_name }}</p>
                        <p class="text-muted text-xs tracking-wide">{{ $r->guest_phone }}</p>
                    </td>
                    <td class="px-6 py-4 text-muted tracking-wide">{{ $r->pet_name }}</td>
                    <td class="px-6 py-4 text-ink tracking-wide">NT$ {{ number_format($r->total_price) }}</td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] tracking-widest px-2.5 py-1 {{ $r->status->color() }}">
                            {{ $r->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick="openDetail({{ $r->id }})"
                                    class="text-xs text-muted hover:text-ink tracking-wide transition-colors">詳情</button>
                            <form method="POST" action="{{ route('admin.accommodation.bookings.status', $r) }}" class="flex gap-2">
                                @csrf @method('PUT')
                                <select name="status"
                                        class="border border-border text-xs px-2 py-1 text-ink bg-white focus:outline-none focus:border-accent">
                                    @foreach($statusMap as $val => $label)
                                    <option value="{{ $val }}" {{ $r->status->value === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                        class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">更新</button>
                            </form>
                        </div>
                    </td>
                </tr>
                {{-- Detail Row --}}
                <tr id="detail-row-{{ $r->id }}" class="hidden">
                    <td colspan="8" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-xs">
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">訂單資訊</p>
                                <p class="text-ink font-mono mb-1">{{ $r->order_no }}</p>
                                <p class="text-muted">建立：{{ $r->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">住宿資訊</p>
                                <p class="text-ink mb-1">{{ $r->roomName() }}</p>
                                <p class="text-muted">{{ $r->check_in->format('Y/m/d') }} → {{ $r->check_out->format('Y/m/d') }}</p>
                                <p class="text-muted">{{ $r->nights }} 晚 × NT${{ number_format($r->price_per_night) }}</p>
                                <p class="text-accent mt-1">總計 NT${{ number_format($r->total_price) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">聯絡人</p>
                                <p class="text-ink mb-1">{{ $r->guest_name }}</p>
                                <p class="text-muted">{{ $r->guest_phone }}</p>
                                <p class="text-muted">{{ $r->guest_email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">寵物資料</p>
                                <p class="text-ink">{{ $r->pet_name }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="button" onclick="closeDetail({{ $r->id }})"
                                    class="text-xs text-muted hover:text-ink tracking-wide border border-border px-4 py-1.5 hover:border-accent transition-colors">收起詳情</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-muted tracking-widest">尚無住宿預約資料</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($reservations->hasPages())
<div class="mt-8">{{ $reservations->links() }}</div>
@endif


<script>
function openDetail(id) {
    document.querySelectorAll('[id^="detail-row-"]').forEach(r => r.classList.add('hidden'));
    document.getElementById('detail-row-' + id).classList.remove('hidden');
}
function closeDetail(id) {
    document.getElementById('detail-row-' + id).classList.add('hidden');
}
</script>

@endsection
