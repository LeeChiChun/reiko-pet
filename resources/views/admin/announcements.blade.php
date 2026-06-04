@extends('layouts.admin')
@section('title', '公告管理')
@section('page-title', 'Announcements')

@section('content')

<div class="grid lg:grid-cols-5 gap-8">

    {{-- Create Form --}}
    <div class="lg:col-span-2 bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增公告</h2>
        <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">標題 *</label>
                <input type="text" name="title" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">標籤</label>
                <input type="text" name="tag" placeholder="例：門市公告、活動資訊"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">內容 *</label>
                <textarea name="content" required rows="8"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">排序（越大越前）</label>
                <input type="number" name="sort_order" value="0" min="0"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">發布日期</label>
                <input type="datetime-local" name="published_at"
                       value="{{ now()->format('Y-m-d\TH:i') }}"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-accent">
                <span class="text-sm text-muted tracking-wide">立即上架</span>
            </label>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增公告
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
                        <th class="text-left px-6 py-4">標籤</th>
                        <th class="text-left px-6 py-4">發布日期</th>
                        <th class="text-left px-6 py-4">狀態</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($announcements as $ann)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4 text-ink tracking-wide max-w-[180px] truncate">{{ $ann->title }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">{{ $ann->tag ?? '—' }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide whitespace-nowrap">
                            {{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y/m/d') : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.announcements.toggle', $ann) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[10px] tracking-widest border px-2.5 py-1 cursor-pointer transition-colors
                                               {{ $ann->is_active
                                                  ? 'text-accent bg-accent/10 border-accent/20 hover:bg-red-50 hover:text-red-500 hover:border-red-200'
                                                  : 'text-muted bg-cream border-border hover:bg-accent/10 hover:text-accent hover:border-accent/20' }}">
                                    {{ $ann->is_active ? '上架' : '下架' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <button onclick="openEdit({{ $ann->id }})"
                                        class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                                <a href="{{ route('announcements.show', $ann) }}" target="_blank"
                                   class="text-xs text-muted hover:text-ink tracking-wide transition-colors">預覽</a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}" onsubmit="return confirm('確定刪除此公告？')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $ann->id }}" class="hidden">
                        <td colspan="5" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.announcements.update', $ann) }}">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">標題</label>
                                        <input type="text" name="title" value="{{ $ann->title }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">標籤</label>
                                        <input type="text" name="tag" value="{{ $ann->tag }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">內容</label>
                                    <textarea name="content" required rows="4"
                                              class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $ann->content }}</textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">排序</label>
                                        <input type="number" name="sort_order" value="{{ $ann->sort_order }}" min="0"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">發布日期</label>
                                        <input type="datetime-local" name="published_at"
                                               value="{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '' }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $ann->is_active ? 'checked' : '' }} class="accent-accent">
                                        <span class="text-sm text-muted">立即上架</span>
                                    </label>
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $ann->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-muted text-sm">尚無公告</td></tr>
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
