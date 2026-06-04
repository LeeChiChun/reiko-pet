@extends('layouts.admin')
@section('title', '門市管理')
@section('page-title', 'Stores')

@section('content')

<div class="grid lg:grid-cols-3 gap-8">

    {{-- Create Form --}}
    <div class="bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增門市</h2>
        <form method="POST" action="{{ route('admin.stores.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">門市名稱 *</label>
                <input type="text" name="name" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">地址</label>
                <input type="text" name="address"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">電話</label>
                <input type="tel" name="phone"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">營業時間</label>
                <input type="text" name="hours"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-accent">
                <span class="text-sm text-muted tracking-wide">啟用</span>
            </label>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增門市
            </button>
        </form>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white border border-border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                        <th class="text-left px-6 py-4">名稱</th>
                        <th class="text-left px-6 py-4">地址</th>
                        <th class="text-left px-6 py-4">電話</th>
                        <th class="text-left px-6 py-4">狀態</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($stores as $store)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4 text-ink tracking-wide">{{ $store->name }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide text-xs max-w-[200px] truncate">{{ $store->address ?? '—' }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">{{ $store->phone ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.stores.toggle', $store) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[10px] tracking-widest border px-2.5 py-1 cursor-pointer transition-colors
                                               {{ $store->is_active
                                                  ? 'text-accent bg-accent/10 border-accent/20 hover:bg-red-50 hover:text-red-500 hover:border-red-200'
                                                  : 'text-muted bg-cream border-border hover:bg-accent/10 hover:text-accent hover:border-accent/20' }}">
                                    {{ $store->is_active ? '啟用' : '停用' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-4">
                            <button onclick="openEdit({{ $store->id }})"
                                    class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                            <form method="POST" action="{{ route('admin.stores.destroy', $store) }}" onsubmit="return confirm('確定刪除？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                            </form>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $store->id }}" class="hidden">
                        <td colspan="5" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.stores.update', $store) }}">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">名稱</label>
                                        <input type="text" name="name" value="{{ $store->name }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">地址</label>
                                        <input type="text" name="address" value="{{ $store->address }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">電話</label>
                                        <input type="tel" name="phone" value="{{ $store->phone }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">營業時間</label>
                                        <input type="text" name="hours" value="{{ $store->hours }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $store->is_active ? 'checked' : '' }} class="accent-accent">
                                        <span class="text-sm text-muted">啟用</span>
                                    </label>
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $store->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-muted tracking-widest">尚無門市資料</td></tr>
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
