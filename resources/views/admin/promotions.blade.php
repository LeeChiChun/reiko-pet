@extends('layouts.admin')
@section('title', '優惠管理')
@section('page-title', 'Promotions')

@section('content')

<div class="grid lg:grid-cols-5 gap-8">

    {{-- Create Form --}}
    <div class="lg:col-span-2 bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增優惠</h2>
        <form method="POST" action="{{ route('admin.promotions.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">標題 *</label>
                <input type="text" name="title" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">Badge 標籤（頂欄文字）</label>
                <input type="text" name="badge" placeholder="例：限時優惠、新客優惠"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">期間</label>
                <input type="text" name="period" placeholder="例：即日起 – 2026.06.30"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">描述</label>
                <textarea name="description" rows="5"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">Tag 標籤（卡片底部）</label>
                <input type="text" name="tag" placeholder="例：全館適用、單次使用"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">頂欄顏色</label>
                <select name="color"
                        class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                    <option value="bg-accent">accent（深棕）</option>
                    <option value="bg-warm-gray">warm-gray（灰）</option>
                    <option value="bg-ink">ink（深色）</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">連結網址（選填）</label>
                <input type="url" name="link_url" placeholder="https://..."
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">綁定優惠券（選填）</label>
                <select name="coupon_id" class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent transition-colors">
                    <option value="">── 不綁定優惠券 ──</option>
                    @foreach($coupons as $c)
                    <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">排序（越大越前）</label>
                <input type="number" name="sort_order" value="0" min="0"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-accent">
                <span class="text-sm text-muted tracking-wide">立即上架</span>
            </label>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增優惠
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="lg:col-span-3">
        <div class="bg-white border border-border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                        <th class="text-left px-6 py-4">標題</th>
                        <th class="text-left px-6 py-4">期間</th>
                        <th class="text-left px-6 py-4">優惠碼</th>
                        <th class="text-left px-6 py-4">狀態</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($promotions as $p)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4 text-ink tracking-wide max-w-[180px] truncate">{{ $p->title }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide text-xs">{{ $p->period ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if($p->coupon)
                            <span class="font-mono text-xs text-accent tracking-widest">{{ $p->coupon->code }}</span>
                            @else
                            <span class="text-muted text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.promotions.toggle', $p) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[10px] tracking-widest border px-2.5 py-1 cursor-pointer transition-colors
                                               {{ $p->is_active
                                                  ? 'text-accent bg-accent/10 border-accent/20 hover:bg-red-50 hover:text-red-500 hover:border-red-200'
                                                  : 'text-muted bg-cream border-border hover:bg-accent/10 hover:text-accent hover:border-accent/20' }}">
                                    {{ $p->is_active ? '上架' : '下架' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <button onclick="openEdit({{ $p->id }})"
                                        class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                                <form method="POST" action="{{ route('admin.promotions.destroy', $p) }}" onsubmit="return confirm('確定刪除此優惠？')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $p->id }}" class="hidden">
                        <td colspan="5" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.promotions.update', $p) }}">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">標題</label>
                                        <input type="text" name="title" value="{{ $p->title }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">Badge 標籤</label>
                                        <input type="text" name="badge" value="{{ $p->badge }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">期間</label>
                                        <input type="text" name="period" value="{{ $p->period }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">Tag 標籤</label>
                                        <input type="text" name="tag" value="{{ $p->tag }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">描述</label>
                                    <textarea name="description" rows="3"
                                              class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $p->description }}</textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">頂欄顏色</label>
                                        <select name="color" class="w-full border border-border px-3 py-2 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                                            <option value="bg-accent"    {{ $p->color === 'bg-accent'    ? 'selected' : '' }}>accent（深棕）</option>
                                            <option value="bg-warm-gray" {{ $p->color === 'bg-warm-gray' ? 'selected' : '' }}>warm-gray（灰）</option>
                                            <option value="bg-ink"       {{ $p->color === 'bg-ink'       ? 'selected' : '' }}>ink（深色）</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">排序</label>
                                        <input type="number" name="sort_order" value="{{ $p->sort_order }}" min="0"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">連結網址</label>
                                        <input type="url" name="link_url" value="{{ $p->link_url }}" placeholder="https://..."
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">綁定優惠券</label>
                                        <select name="coupon_id" class="w-full border border-border px-3 py-2 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                                            <option value="">── 不綁定 ──</option>
                                            @foreach($coupons as $c)
                                            <option value="{{ $c->id }}" {{ $p->coupon_id == $c->id ? 'selected' : '' }}>
                                                {{ $c->code }} — {{ $c->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $p->is_active ? 'checked' : '' }} class="accent-accent">
                                        <span class="text-sm text-muted">立即上架</span>
                                    </label>
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $p->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-muted text-sm">尚無優惠活動</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
function openEdit(id) {
    document.querySelectorAll('[id^="edit-row-"]').forEach(r => r.classList.add('hidden'));
    document.getElementById('edit-row-' + id).classList.remove('hidden');
    document.getElementById('edit-row-' + id).scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function closeEdit(id) {
    document.getElementById('edit-row-' + id).classList.add('hidden');
}
</script>

@endsection
