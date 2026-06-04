@extends('layouts.admin')
@section('title', '商品管理')
@section('page-title', 'Products')

@section('content')

<div class="grid lg:grid-cols-3 gap-8">

    {{-- Create Form --}}
    <div class="bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增商品</h2>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">商品名稱 *</label>
                <input type="text" name="name" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">分類</label>
                <select name="category"
                        class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                    @foreach(['food'=>'食品','care'=>'護毛','accessory'=>'配件','toy'=>'玩具'] as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">售價 (NT$) *</label>
                    <input type="number" name="price" required min="0"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">庫存</label>
                    <input type="number" name="stock" min="0" value="0"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">商品圖片</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-xs text-muted file:mr-4 file:py-2 file:px-4 file:border file:border-border file:text-xs file:tracking-widest file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                <p class="text-[10px] text-muted mt-1 tracking-wide">建議尺寸：800×800px（正方形）</p>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">商品描述</label>
                <textarea name="description" rows="4"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"></textarea>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-accent">
                <span class="text-sm text-muted tracking-wide">上架</span>
            </label>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增商品
            </button>
        </form>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white border border-border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                        <th class="text-left px-6 py-4">名稱</th>
                        <th class="text-left px-6 py-4">分類</th>
                        <th class="text-left px-6 py-4">售價</th>
                        <th class="text-left px-6 py-4">庫存</th>
                        <th class="text-left px-6 py-4">狀態</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($products as $product)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4 text-ink tracking-wide max-w-[140px] truncate">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">{{ ['food'=>'食品','care'=>'護毛','accessory'=>'配件','toy'=>'玩具'][$product->category] ?? '—' }}</td>
                        <td class="px-6 py-4 text-ink">NT$ {{ number_format($product->price) }}</td>
                        <td class="px-6 py-4 text-muted">{{ $product->stock }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[10px] tracking-widest border px-2.5 py-1 cursor-pointer transition-colors
                                               {{ $product->is_active
                                                  ? 'text-accent bg-accent/10 border-accent/20 hover:bg-red-50 hover:text-red-500 hover:border-red-200'
                                                  : 'text-muted bg-cream border-border hover:bg-accent/10 hover:text-accent hover:border-accent/20' }}">
                                    {{ $product->is_active ? '上架' : '下架' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-4">
                            <button onclick="openEdit({{ $product->id }})"
                                    class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('確定刪除？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                            </form>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $product->id }}" class="hidden">
                        <td colspan="6" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">名稱</label>
                                        <input type="text" name="name" value="{{ $product->name }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">分類</label>
                                        <select name="category" class="w-full border border-border px-3 py-2 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                                            @foreach(['food'=>'食品','care'=>'護毛','accessory'=>'配件','toy'=>'玩具'] as $catVal => $catLabel)
                                            <option value="{{ $catVal }}" {{ $product->category === $catVal ? 'selected' : '' }}>{{ $catLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">售價 (NT$)</label>
                                        <input type="number" name="price" value="{{ $product->price }}" required min="0"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">庫存</label>
                                        <input type="number" name="stock" value="{{ $product->stock }}" min="0"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div class="md:col-span-3">
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">新圖片（留空保留原圖）</label>
                                        <input type="file" name="image" accept="image/*"
                                               class="w-full text-xs text-muted file:mr-3 file:py-1.5 file:px-3 file:border file:border-border file:text-xs file:text-muted file:bg-cream hover:file:border-accent hover:file:text-accent">
                                        <p class="text-[10px] text-muted mt-0.5 tracking-wide">建議尺寸：800×800px</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">描述</label>
                                    <textarea name="description" rows="2"
                                              class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $product->description }}</textarea>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="accent-accent">
                                        <span class="text-sm text-muted">上架</span>
                                    </label>
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $product->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-muted tracking-widest">尚無商品</td></tr>
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
