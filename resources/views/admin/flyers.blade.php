@extends('layouts.admin')
@section('title', 'DM 傳單管理')
@section('page-title', 'Flyers')

@section('content')

<div class="grid lg:grid-cols-5 gap-8">

    {{-- Create Form --}}
    <div class="lg:col-span-2 bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增 DM</h2>
        <form method="POST" action="{{ route('admin.flyers.store') }}"
              enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">標題 *</label>
                <input type="text" name="title" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">期間</label>
                <input type="text" name="period" placeholder="例：2026.05"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">說明</label>
                <textarea name="description" rows="4"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">DM 圖片</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-xs text-muted file:mr-4 file:py-2 file:px-4 file:border file:border-border file:text-xs file:tracking-widest file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                <p class="text-[10px] text-muted mt-1 tracking-wide">建議尺寸：794×1123px（A4 直式）</p>
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
                新增 DM
            </button>
        </form>
    </div>

    {{-- Card Grid --}}
    <div class="lg:col-span-3">
        <div class="grid sm:grid-cols-2 gap-5">
            @forelse($flyers as $flyer)
            <div class="bg-white border border-border overflow-hidden group">
                {{-- 圖片預覽 --}}
                @if($flyer->image_path)
                <div class="aspect-[3/4] overflow-hidden bg-cream-alt">
                    <img src="{{ $flyer->imageUrl() }}" alt="{{ $flyer->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-[3/4] bg-cream-alt flex items-center justify-center">
                    <span class="font-serif text-4xl text-accent/15">禮</span>
                </div>
                @endif
                {{-- 資訊 --}}
                <div class="p-5 space-y-1">
                    <p class="text-sm text-ink tracking-wide font-medium">{{ $flyer->title }}</p>
                    @if($flyer->period)
                    <p class="text-[10px] text-muted tracking-wide">{{ $flyer->period }}</p>
                    @endif
                    <div class="flex items-center gap-2 pt-1">
                        <span class="text-[10px] tracking-widest uppercase px-2.5 py-1
                                     {{ $flyer->is_active ? 'text-accent bg-accent/10' : 'text-muted bg-cream border border-border' }}">
                            {{ $flyer->is_active ? '上架' : '下架' }}
                        </span>
                    </div>
                </div>
                {{-- 操作 --}}
                <div class="px-5 pb-4 flex items-center gap-4 border-t border-border pt-4">
                    <button onclick="openEdit({{ $flyer->id }})"
                            class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                    <a href="{{ route('flyers.download', $flyer) }}"
                       class="text-xs text-muted hover:text-ink tracking-wide transition-colors">下載</a>
                    <form method="POST" action="{{ route('admin.flyers.destroy', $flyer) }}"
                          onsubmit="return confirm('確定刪除此 DM？')" class="ml-auto">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                    </form>
                </div>
                {{-- Inline Edit Panel --}}
                <div id="edit-row-{{ $flyer->id }}" class="hidden border-t border-dashed border-border bg-cream/40 p-5">
                    <form method="POST" action="{{ route('admin.flyers.update', $flyer) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">標題</label>
                            <input type="text" name="title" value="{{ $flyer->title }}" required
                                   class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">期間</label>
                            <input type="text" name="period" value="{{ $flyer->period }}"
                                   class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">說明</label>
                            <textarea name="description" rows="2"
                                      class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $flyer->description }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">新圖片（留空保留原圖）</label>
                            @if($flyer->image_path)
                            <img src="{{ $flyer->imageUrl() }}" alt="" class="max-h-20 mb-2 border border-border object-contain">
                            @endif
                            <input type="file" name="image" accept="image/*"
                                   class="w-full text-xs text-muted file:mr-3 file:py-1.5 file:px-3 file:border file:border-border file:text-xs file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                            <p class="text-[10px] text-muted mt-0.5 tracking-wide">建議尺寸：794×1123px（A4 直式）</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">排序</label>
                                <input type="number" name="sort_order" value="{{ $flyer->sort_order }}" min="0"
                                       class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-2 cursor-pointer pb-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $flyer->is_active ? 'checked' : '' }} class="accent-accent">
                                    <span class="text-sm text-muted">上架</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="submit"
                                    class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                            <button type="button" onclick="closeEdit({{ $flyer->id }})"
                                    class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div class="sm:col-span-2 py-20 text-center text-muted text-sm tracking-wide border border-border bg-white">
                尚無 DM，請新增
            </div>
            @endforelse
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
