@extends('layouts.admin')
@section('title', '美容師管理')
@section('page-title', 'Groomers')

@section('content')

<div class="grid lg:grid-cols-3 gap-8">

    {{-- Create Form --}}
    <div class="bg-white border border-border p-8 h-fit">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增美容師</h2>
        <form method="POST" action="{{ route('admin.groomers.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">姓名 *</label>
                <input type="text" name="name" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">Email *</label>
                <input type="email" name="email" required
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">手機</label>
                <input type="tel" name="phone"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">密碼（至少 8 字元）*</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
            </div>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 hover:bg-accent-light transition-colors">
                新增美容師
            </button>
        </form>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white border border-border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                        <th class="text-left px-6 py-4">姓名</th>
                        <th class="text-left px-6 py-4">Email</th>
                        <th class="text-left px-6 py-4">手機</th>
                        <th class="text-left px-6 py-4">加入時間</th>
                        <th class="text-left px-6 py-4">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($groomers as $groomer)
                    <tr class="hover:bg-cream/50 transition-colors">
                        <td class="px-6 py-4 text-ink tracking-wide">{{ $groomer->name }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">{{ $groomer->email }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">{{ $groomer->phone ?? '—' }}</td>
                        <td class="px-6 py-4 text-muted tracking-wide">{{ $groomer->created_at->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 flex items-center gap-4">
                            <button onclick="openEdit({{ $groomer->id }})"
                                    class="text-xs text-accent hover:text-accent-light tracking-wide transition-colors">編輯</button>
                            <form method="POST" action="{{ route('admin.groomers.destroy', $groomer) }}" onsubmit="return confirm('確定刪除此美容師帳號？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 tracking-wide transition-colors">刪除</button>
                            </form>
                        </td>
                    </tr>
                    {{-- Inline Edit Row --}}
                    <tr id="edit-row-{{ $groomer->id }}" class="hidden">
                        <td colspan="5" class="px-6 py-5 bg-cream/40 border-t border-dashed border-border">
                            <form method="POST" action="{{ route('admin.groomers.update', $groomer) }}">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">姓名</label>
                                        <input type="text" name="name" value="{{ $groomer->name }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">Email</label>
                                        <input type="email" name="email" value="{{ $groomer->email }}" required
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">手機</label>
                                        <input type="tel" name="phone" value="{{ $groomer->phone }}"
                                               class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-[10px] tracking-[0.3em] uppercase text-muted mb-1">新密碼（留空則不更改）</label>
                                    <input type="password" name="password" minlength="8" placeholder="至少 8 個字元"
                                           class="w-full border border-border px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent">
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="submit"
                                            class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-5 py-2 hover:bg-accent-light transition-colors">儲存</button>
                                    <button type="button" onclick="closeEdit({{ $groomer->id }})"
                                            class="border border-border text-muted text-xs tracking-[0.3em] uppercase px-5 py-2 hover:border-accent hover:text-accent transition-colors">取消編輯</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-muted tracking-widest">尚無美容師資料</td></tr>
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
