@extends('layouts.admin')
@section('title', '優惠券管理')
@section('page-title', 'Coupons')

@section('content')
<div class="max-w-6xl mx-auto">

    <h1 class="font-serif text-2xl text-ink tracking-widests mb-8">優惠券管理</h1>

    @if($errors->any())
    <div class="mb-6 border border-red-200 bg-red-50 px-5 py-3 text-xs text-red-600 tracking-wide">
        @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
    </div>
    @endif

    {{-- 新增優惠券 --}}
    <div class="bg-white border border-border p-8 mb-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-6">新增優惠券</h2>
        <form method="POST" action="{{ route('admin.coupons.store') }}" class="space-y-5">
            @csrf
            <div class="grid md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">優惠碼 *（大寫英數）</label>
                    <input type="text" name="code" required value="{{ old('code') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm uppercase focus:outline-none focus:border-accent"
                           placeholder="SUMMER2026">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">優惠名稱 *</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent"
                           placeholder="夏日感恩特惠">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">折扣類型</label>
                    <select name="type" class="w-full border border-border px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-accent">
                        <option value="percentage">百分比折扣（%）</option>
                        <option value="fixed">固定金額折抵（NT$）</option>
                        <option value="buy_one_get_one">買一送一（顯示用）</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">折扣數值</label>
                    <input type="number" name="discount_value" min="0" value="{{ old('discount_value', 0) }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent"
                           placeholder="例：10 = 9折 或 NT$100">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">最低消費金額</label>
                    <input type="number" name="minimum_amount" min="0" value="{{ old('minimum_amount', 0) }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">適用範圍</label>
                    <select name="scope" class="w-full border border-border px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-accent">
                        <option value="all">全館通用</option>
                        <option value="shop">線上商城</option>
                        <option value="grooming">寵物美容</option>
                        <option value="accommodation">寵物住宿</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">使用資格</label>
                    <select name="visibility" id="new-coupon-visibility"
                            class="w-full border border-border px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-accent">
                        <option value="public">公開（含訪客）</option>
                        <option value="member">會員限定</option>
                        <option value="personal">個人專屬</option>
                    </select>
                </div>
                <div id="new-assigned-user" class="hidden">
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">指定會員 ID</label>
                    <input type="number" name="assigned_user_id" min="1" value="{{ old('assigned_user_id') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent"
                           placeholder="會員 user_id">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">最高使用次數（空白=無限）</label>
                    <input type="number" name="max_uses" min="1" value="{{ old('max_uses') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">開始時間</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">到期時間</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
                </div>
                <div class="flex items-center gap-3 pt-5">
                    <input type="checkbox" name="is_active" value="1" id="new-coupon-active" checked class="accent-accent">
                    <label for="new-coupon-active" class="text-sm text-ink cursor-pointer">立即啟用</label>
                </div>
                <div class="flex items-center gap-3 pt-5">
                    <input type="checkbox" name="show_on_home" value="1" id="new-coupon-home" class="accent-accent">
                    <label for="new-coupon-home" class="text-sm text-ink cursor-pointer">顯示於首頁推薦</label>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-8 py-2.5 hover:bg-accent-light transition-colors">
                    建立優惠券
                </button>
            </div>
        </form>
    </div>

    {{-- 優惠券列表 --}}
    <div class="bg-white border border-border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-border">
                <tr class="text-left">
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">優惠碼</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">名稱</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">類型</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">折扣</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">資格</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">使用</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">到期</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">狀態</th>
                    <th class="px-5 py-4 text-[10px] tracking-widest uppercase text-muted font-normal">首頁推薦</th>
                    <th class="px-5 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr class="border-b border-border last:border-0 hover:bg-cream/30 transition-colors">
                    <td class="px-5 py-4 font-mono tracking-widest text-accent">{{ $coupon->code }}</td>
                    <td class="px-5 py-4 text-ink tracking-wide">{{ $coupon->name }}</td>
                    <td class="px-5 py-4 text-muted">
                        {{ ['percentage'=>'折%','fixed'=>'折NT$','buy_one_get_one'=>'買一送一'][$coupon->type] }}
                    </td>
                    <td class="px-5 py-4 text-ink">
                        @if($coupon->type === 'percentage') {{ $coupon->discount_value }}%
                        @elseif($coupon->type === 'fixed') NT$ {{ number_format($coupon->discount_value) }}
                        @else —
                        @endif
                    </td>
                    <td class="px-5 py-4 text-muted text-xs">{{ $coupon->visibilityLabel() }}</td>
                    <td class="px-5 py-4 text-muted">
                        {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                    </td>
                    <td class="px-5 py-4 text-xs text-muted">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('Y/m/d') : '無期限' }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-[10px] tracking-widest border px-2 py-0.5
                            {{ $coupon->is_active ? 'border-green-300 text-green-600' : 'border-red-200 text-red-400' }}">
                            {{ $coupon->is_active ? '啟用' : '停用' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="{{ $coupon->show_on_home ? 'text-accent' : 'text-muted/30' }}">
                            {{ $coupon->show_on_home ? '✓' : '—' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center gap-3 justify-end">
                            <button onclick="document.getElementById('edit-coupon-{{ $coupon->id }}').classList.toggle('hidden')"
                                    class="text-xs text-muted hover:text-accent transition-colors">編輯</button>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}"
                                  onsubmit="return confirm('確定刪除此優惠券？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-muted hover:text-red-400 transition-colors">刪除</button>
                            </form>
                        </div>
                    </td>
                </tr>
                {{-- 編輯列 --}}
                <tr id="edit-coupon-{{ $coupon->id }}" class="hidden bg-cream/30 border-b border-border">
                    <td colspan="10" class="px-5 py-5">
                        <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="grid md:grid-cols-4 gap-4">
                            @csrf @method('PUT')
                            <input type="text" name="code" value="{{ $coupon->code }}" placeholder="優惠碼"
                                   class="border border-border px-3 py-2 text-sm uppercase focus:outline-none focus:border-accent">
                            <input type="text" name="name" value="{{ $coupon->name }}" placeholder="名稱"
                                   class="border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                            <select name="type" class="border border-border px-3 py-2 text-sm bg-white focus:outline-none focus:border-accent">
                                <option value="percentage" {{ $coupon->type === 'percentage' ? 'selected' : '' }}>折%</option>
                                <option value="fixed" {{ $coupon->type === 'fixed' ? 'selected' : '' }}>折NT$</option>
                                <option value="buy_one_get_one" {{ $coupon->type === 'buy_one_get_one' ? 'selected' : '' }}>買一送一</option>
                            </select>
                            <input type="number" name="discount_value" value="{{ $coupon->discount_value }}" placeholder="折扣值"
                                   class="border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                            <input type="number" name="minimum_amount" value="{{ $coupon->minimum_amount }}" placeholder="最低消費"
                                   class="border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                            <select name="scope" class="border border-border px-3 py-2 text-sm bg-white focus:outline-none focus:border-accent">
                                <option value="all" {{ $coupon->scope === 'all' ? 'selected' : '' }}>全館</option>
                                <option value="shop" {{ $coupon->scope === 'shop' ? 'selected' : '' }}>商城</option>
                                <option value="grooming" {{ $coupon->scope === 'grooming' ? 'selected' : '' }}>美容</option>
                                <option value="accommodation" {{ $coupon->scope === 'accommodation' ? 'selected' : '' }}>住宿</option>
                            </select>
                            <select name="visibility" class="border border-border px-3 py-2 text-sm bg-white focus:outline-none focus:border-accent"
                                    onchange="this.nextElementSibling.classList.toggle('hidden', this.value !== 'personal')">
                                <option value="public"   {{ ($coupon->visibility ?? 'public') === 'public'   ? 'selected' : '' }}>公開</option>
                                <option value="member"   {{ ($coupon->visibility ?? 'public') === 'member'   ? 'selected' : '' }}>會員限定</option>
                                <option value="personal" {{ ($coupon->visibility ?? 'public') === 'personal' ? 'selected' : '' }}>個人專屬</option>
                            </select>
                            <input type="number" name="assigned_user_id" value="{{ $coupon->assigned_user_id }}"
                                   placeholder="指定 user_id"
                                   class="border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent {{ ($coupon->visibility ?? 'public') !== 'personal' ? 'hidden' : '' }}">
                            <input type="number" name="max_uses" value="{{ $coupon->max_uses }}" placeholder="最高次數（空=無限）"
                                   class="border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                            <input type="datetime-local" name="expires_at"
                                   value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '' }}"
                                   class="border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" value="1"
                                       id="coupon-active-{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }} class="accent-accent">
                                <label for="coupon-active-{{ $coupon->id }}" class="text-sm text-ink cursor-pointer">啟用</label>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="show_on_home" value="1"
                                       id="coupon-home-{{ $coupon->id }}" {{ $coupon->show_on_home ? 'checked' : '' }} class="accent-accent">
                                <label for="coupon-home-{{ $coupon->id }}" class="text-sm text-ink cursor-pointer">首頁推薦</label>
                            </div>
                            <div class="flex gap-3 items-center md:col-span-2">
                                <button type="submit" class="bg-accent text-cream text-xs tracking-widest px-6 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                <button type="button" onclick="document.getElementById('edit-coupon-{{ $coupon->id }}').classList.add('hidden')"
                                        class="text-xs text-muted tracking-widest hover:text-ink">取消</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="px-5 py-10 text-center text-muted tracking-wide text-sm">尚無優惠券</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $coupons->links() }}</div>

</div>

<script>
const newVis = document.getElementById('new-coupon-visibility');
const newAssigned = document.getElementById('new-assigned-user');
if (newVis && newAssigned) {
    newVis.addEventListener('change', () => {
        newAssigned.classList.toggle('hidden', newVis.value !== 'personal');
    });
}
</script>

@endsection
