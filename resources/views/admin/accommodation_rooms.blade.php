@extends('layouts.admin')
@section('title', '住宿房型管理')
@section('page-title', 'Accommodation Rooms')

@section('content')

<div class="grid lg:grid-cols-3 gap-8">

    {{-- Create Form --}}
    <div class="bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增房型</h2>
        <form method="POST" action="{{ route('admin.accommodation.rooms.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">房型名稱 *</label>
                <input type="text" name="name" required placeholder="例：舒適房"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">識別碼 * <span class="text-muted/50 normal-case tracking-normal">(英文小寫，不可重複)</span></label>
                <input type="text" name="slug" required placeholder="例：comfort"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">每晚價格 (NT$) *</label>
                    <input type="number" name="price_per_night" required min="0"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">體重上限 (kg)</label>
                    <input type="number" name="max_weight" min="1" placeholder="空白=不限"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">描述</label>
                <textarea name="description" rows="3"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">特色（每行一項）</label>
                <textarea name="features" rows="4" placeholder="獨立空間&#10;每日清潔&#10;2 次餵食"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">房型圖片</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-xs text-muted file:mr-4 file:py-2 file:px-4 file:border file:border-border file:text-xs file:tracking-widest file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                       <p class="text-[10px] text-muted mt-1 tracking-wide">建議尺寸：800×600px（橫式）</p>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">排序（越小越前）</label>
                <input type="number" name="sort_order" value="0" min="0"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-accent">
                <span class="text-sm text-muted tracking-wide">啟用</span>
            </label>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增房型
            </button>
        </form>
    </div>

    {{-- Room List --}}
    <div class="lg:col-span-2">
        <div class="bg-white border border-border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                        <th class="text-left px-6 py-4">名稱</th>
                        <th class="text-left px-6 py-4">價格</th>
                        <th class="text-left px-6 py-4">體重上限</th>
                        <th class="text-left px-6 py-4">狀態</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($rooms as $room)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-ink tracking-wide">{{ $room->name }}</p>
                            <p class="text-[10px] text-muted tracking-widest mt-0.5">{{ $room->slug }}</p>
                        </td>
                        <td class="px-6 py-4 text-ink">NT$ {{ number_format($room->price_per_night) }} <span class="text-muted text-xs">/ 晚</span></td>
                        <td class="px-6 py-4 text-muted">{{ $room->max_weight ? $room->max_weight . ' kg' : '不限' }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.accommodation.rooms.toggle', $room) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[10px] tracking-widest border px-2.5 py-1 cursor-pointer transition-colors
                                               {{ $room->is_active
                                                  ? 'text-accent bg-accent/10 border-accent/20 hover:bg-red-50 hover:text-red-500 hover:border-red-200'
                                                  : 'text-muted bg-cream border-border hover:bg-accent/10 hover:text-accent hover:border-accent/20' }}">
                                    {{ $room->is_active ? '啟用' : '停用' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-4">
                            <button onclick="openEdit({{ $room->id }})"
                                    class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                            <form method="POST" action="{{ route('admin.accommodation.rooms.destroy', $room) }}"
                                  onsubmit="return confirm('確定刪除此房型？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                            </form>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $room->id }}" class="hidden">
                        <td colspan="5" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.accommodation.rooms.update', $room) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">名稱</label>
                                        <input type="text" name="name" value="{{ $room->name }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">價格 (NT$)</label>
                                        <input type="number" name="price_per_night" value="{{ $room->price_per_night }}" required min="0"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">體重上限 (kg)</label>
                                        <input type="number" name="max_weight" value="{{ $room->max_weight }}" min="1" placeholder="留空=不限"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">描述</label>
                                    <textarea name="description" rows="2"
                                              class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $room->description }}</textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">特色（每行一項）</label>
                                        <textarea name="features" rows="3"
                                                  class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ implode("\n", $room->featuresArray()) }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">新圖片（留空保留原圖）</label>
                                        @if($room->imageUrl())
                                        <img src="{{ $room->imageUrl() }}" alt="" class="max-h-16 mb-2 border border-border object-contain">
                                        @endif
                                        <input type="file" name="image" accept="image/*"
                                               class="w-full text-xs text-muted file:mr-2 file:py-1.5 file:px-3 file:border file:border-border file:text-xs file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                                        <p class="text-[10px] text-muted mt-0.5 tracking-wide">建議尺寸：800×600px（橫式）</p>
                                        <div class="mt-2">
                                            <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">排序</label>
                                            <input type="number" name="sort_order" value="{{ $room->sort_order }}" min="0"
                                                   class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $room->is_active ? 'checked' : '' }} class="accent-accent">
                                        <span class="text-sm text-muted">啟用</span>
                                    </label>
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $room->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-muted tracking-widest">尚無房型資料</td></tr>
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
