@extends('layouts.admin')
@section('title', '文章管理')
@section('page-title', 'Articles')

@section('content')

<div class="grid lg:grid-cols-5 gap-8">

    {{-- Create Form --}}
    <div class="lg:col-span-2 bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增文章</h2>
        <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">標題 *</label>
                <input type="text" name="title" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">分類 *</label>
                <select name="category"
                        class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                    @foreach(['grooming'=>'美容知識','health'=>'健康護理','lifestyle'=>'生活日常','nutrition'=>'營養飲食'] as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">作者</label>
                <input type="text" name="author"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">封面圖片</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full text-xs text-muted file:mr-4 file:py-2 file:px-4 file:border file:border-border file:text-xs file:tracking-widest file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                <p class="text-[10px] text-muted mt-1 tracking-wide">建議尺寸：800×500px（橫式）</p>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">內容 *</label>
                <textarea name="content" required rows="10"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" checked class="accent-accent">
                <span class="text-sm text-muted tracking-wide">立即發布</span>
            </label>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增文章
            </button>
        </form>
    </div>

    <div class="lg:col-span-3">
        <div class="bg-white border border-border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                        <th class="text-left px-6 py-4">標題</th>
                        <th class="text-left px-6 py-4">作者</th>
                        <th class="text-left px-6 py-4">建立時間</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($articles as $article)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4 text-ink tracking-wide max-w-[200px] truncate">{{ $article->title }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">{{ $article->author ?? '—' }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide whitespace-nowrap">{{ $article->created_at->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 flex items-center gap-4">
                            <button onclick="openEdit({{ $article->id }})"
                                    class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                            <a href="{{ route('articles.show', $article) }}" target="_blank"
                               class="text-xs text-muted hover:text-ink tracking-wide transition-colors">預覽</a>
                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('確定刪除？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                            </form>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $article->id }}" class="hidden">
                        <td colspan="4" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-3">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">標題</label>
                                        <input type="text" name="title" value="{{ $article->title }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">分類</label>
                                        <select name="category" class="w-full border border-border px-3 py-2 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                                            @foreach(['grooming'=>'美容知識','health'=>'健康護理','lifestyle'=>'生活日常','nutrition'=>'營養飲食'] as $catVal => $catLabel)
                                            <option value="{{ $catVal }}" {{ $article->category === $catVal ? 'selected' : '' }}>{{ $catLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">作者</label>
                                        <input type="text" name="author" value="{{ $article->author }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">新封面圖片（留空保留原圖）</label>
                                        <input type="file" name="cover_image" accept="image/*"
                                               class="w-full text-xs text-muted file:mr-3 file:py-1.5 file:px-3 file:border file:border-border file:text-xs file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                                        <p class="text-[10px] text-muted mt-0.5 tracking-wide">建議尺寸：800×500px</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">內容</label>
                                    <textarea name="content" required rows="6"
                                              class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $article->content }}</textarea>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_published" value="1" {{ ($article->is_published ?? true) ? 'checked' : '' }} class="accent-accent">
                                        <span class="text-sm text-muted">立即發布</span>
                                    </label>
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $article->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-muted tracking-widest">尚無文章</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages())
        <div class="mt-6">{{ $articles->links() }}</div>
        @endif
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
