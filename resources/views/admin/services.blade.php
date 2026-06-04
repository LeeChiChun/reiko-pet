@extends('layouts.admin')
@section('title', '服務管理')
@section('page-title', 'Services')

@section('content')

<div class="grid lg:grid-cols-3 gap-8">

    {{-- Create Form (新增專用) --}}
    <div class="bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增服務</h2>
        <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">服務名稱 *</label>
                <input type="text" name="name" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">分類</label>
                <select name="category"
                        class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                    @foreach(['single'=>'單項','dog'=>'全套（狗）','cat'=>'全套（貓）','small_pkg'=>'小套餐','large_pkg'=>'大套餐'] as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">價格 (NT$) *</label>
                <input type="number" name="price" required min="0"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">預估時間（分鐘）</label>
                <input type="number" name="duration_minutes"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">說明</label>
                <textarea name="description" rows="3"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-accent">
                <span class="text-sm text-muted tracking-wide">啟用</span>
            </label>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增服務
            </button>
        </form>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2">
        <div class="bg-white border border-border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                        <th class="text-left px-6 py-4">名稱</th>
                        <th class="text-left px-6 py-4">分類</th>
                        <th class="text-left px-6 py-4">價格</th>
                        <th class="text-left px-6 py-4">狀態</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($services as $service)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4 text-ink tracking-wide">{{ $service->name }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">
                            {{ ['single'=>'單項','dog'=>'全套（狗）','cat'=>'全套（貓）','small_pkg'=>'小套餐','large_pkg'=>'大套餐'][$service->category] ?? $service->category }}
                        </td>
                        <td class="px-6 py-4 text-ink">NT$ {{ number_format($service->price) }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.services.toggle', $service) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[10px] tracking-widest border px-2.5 py-1 cursor-pointer transition-colors
                                               {{ $service->is_active
                                                  ? 'text-accent bg-accent/10 border-accent/20 hover:bg-red-50 hover:text-red-500 hover:border-red-200'
                                                  : 'text-muted bg-cream border-border hover:bg-accent/10 hover:text-accent hover:border-accent/20' }}">
                                    {{ $service->is_active ? '啟用' : '停用' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-4">
                            <button onclick="openEdit({{ $service->id }})"
                                    class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                  onsubmit="return confirm('確定刪除此服務？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                            </form>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $service->id }}" class="hidden">
                        <td colspan="5" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.services.update', $service) }}">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">名稱</label>
                                        <input type="text" name="name" value="{{ $service->name }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">分類</label>
                                        <select name="category" class="w-full border border-border px-3 py-2 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                                            @foreach(['single'=>'單項','dog'=>'全套（狗）','cat'=>'全套（貓）','small_pkg'=>'小套餐','large_pkg'=>'大套餐'] as $catVal => $catLabel)
                                            <option value="{{ $catVal }}" {{ $service->category === $catVal ? 'selected' : '' }}>{{ $catLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">價格 (NT$)</label>
                                        <input type="number" name="price" value="{{ $service->price }}" required min="0"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">時間（分）</label>
                                        <input type="number" name="duration_minutes" value="{{ $service->duration_minutes }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">說明</label>
                                    <textarea name="description" rows="2"
                                              class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $service->description }}</textarea>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }} class="accent-accent">
                                        <span class="text-sm text-muted">啟用</span>
                                    </label>
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $service->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-muted tracking-widest">尚無服務資料</td></tr>
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
