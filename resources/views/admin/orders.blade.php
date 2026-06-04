@extends('layouts.admin')
@section('title', '訂單管理')
@section('page-title', 'Shop Orders')

@section('content')

{{-- Status Filter --}}
<div class="flex flex-wrap gap-3 mb-8">
    @foreach(array_merge(['all' => '全部'], $statusMap->toArray()) as $val => $label)
    <a href="{{ route('admin.orders', ['status' => $val]) }}"
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
                    <th class="text-left px-6 py-4">顧客</th>
                    <th class="text-left px-6 py-4">商品摘要</th>
                    <th class="text-left px-6 py-4">金額</th>
                    <th class="text-left px-6 py-4">建立時間</th>
                    <th class="text-left px-6 py-4">狀態</th>
                    <th class="text-left px-6 py-4">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($orders as $order)
                <tr class="hover:bg-cream/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-ink tracking-wide text-xs font-mono">{{ $order->order_no }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-ink tracking-wide">{{ $order->guest_name }}</p>
                        <p class="text-muted text-xs tracking-wide">{{ $order->guest_phone }}</p>
                        @if($order->user)
                        <p class="text-muted/60 text-xs tracking-wide">會員</p>
                        @else
                        <p class="text-muted/60 text-xs tracking-wide">訪客</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-0.5">
                            @foreach(array_slice($order->items ?? [], 0, 2) as $item)
                            <p class="text-muted text-xs tracking-wide">{{ $item['name'] }} × {{ $item['qty'] }}</p>
                            @endforeach
                            @if(count($order->items ?? []) > 2)
                            <p class="text-muted/60 text-xs">⋯ 共 {{ count($order->items) }} 項</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-ink tracking-wide">NT$ {{ number_format($order->total) }}</td>
                    <td class="px-6 py-4 text-muted text-xs tracking-wide whitespace-nowrap">
                        {{ $order->created_at->format('Y/m/d') }}<br>
                        <span class="text-muted/60">{{ $order->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] tracking-widest px-2.5 py-1 {{ $order->status->color() }}">
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick="openDetail({{ $order->id }})"
                                    class="text-xs text-muted hover:text-ink tracking-wide transition-colors">詳情</button>
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex gap-2">
                                @csrf @method('PUT')
                                <select name="status"
                                        class="border border-border text-xs px-2 py-1 text-ink bg-white focus:outline-none focus:border-accent">
                                    @foreach($statusMap as $val => $label)
                                    <option value="{{ $val }}" {{ $order->status->value === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                        class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">更新</button>
                            </form>
                        </div>
                    </td>
                </tr>
                {{-- Detail Row --}}
                <tr id="detail-row-{{ $order->id }}" class="hidden">
                    <td colspan="7" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-xs mb-4">
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">訂單資訊</p>
                                <p class="text-ink font-mono mb-1">{{ $order->order_no }}</p>
                                <p class="text-muted">建立：{{ $order->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">聯絡人</p>
                                <p class="text-ink mb-1">{{ $order->guest_name }}</p>
                                <p class="text-muted">{{ $order->guest_phone }}</p>
                                <p class="text-muted">{{ $order->guest_email }}</p>
                                @if($order->guest_address)
                                <p class="text-muted mt-1">{{ $order->guest_address }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">金額</p>
                                <p class="text-accent text-sm font-medium">NT$ {{ number_format($order->total) }}</p>
                                @if($order->user)
                                <p class="text-muted mt-1">會員：{{ $order->user->name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="border-t border-dashed border-border pt-4">
                            <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-3">訂購商品</p>
                            <div class="space-y-2">
                                @foreach($order->items ?? [] as $item)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-muted tracking-wide">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                                    <span class="text-ink tracking-wide">NT$ {{ number_format($item['amount']) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="button" onclick="closeDetail({{ $order->id }})"
                                    class="text-xs text-muted hover:text-ink tracking-wide border border-border px-4 py-1.5 hover:border-accent transition-colors">收起詳情</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-muted tracking-widest">尚無訂單資料</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($orders->hasPages())
<div class="mt-8">{{ $orders->links() }}</div>
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
