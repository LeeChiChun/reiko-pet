@extends('layouts.admin')
@section('title', '滿意度問卷管理')
@section('page-title', 'Survey')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <h1 class="font-serif text-2xl text-ink tracking-widest">滿意度問卷管理</h1>
        <div class="flex items-center gap-4 text-sm text-muted tracking-wide">
            <span>總回饋數：<strong class="text-ink">{{ $totalResponses }}</strong></span>
            <span>·</span>
            <span>顧客滿意度：<strong class="text-accent">{{ $satisfaction }}</strong></span>
            <a href="{{ route('admin.survey.responses') }}"
               class="border border-border px-4 py-2 text-xs tracking-widest hover:border-accent hover:text-accent transition-colors">
                查看回饋列表
            </a>
        </div>
    </div>

    {{-- 新增題目 --}}
    <div class="bg-white border border-border p-8 mb-8">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-6">新增題目</h2>
        <form method="POST" action="{{ route('admin.survey.store') }}" class="space-y-5">
            @csrf
            <div class="grid md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">題目標題 *</label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">題目說明</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">題型</label>
                    <select name="type" class="w-full border border-border px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-accent">
                        <option value="star" {{ old('type') === 'star' ? 'selected' : '' }}>★ 星級評分（1–5）</option>
                        <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>文字填寫</option>
                        <option value="choice" {{ old('type') === 'choice' ? 'selected' : '' }}>單選題</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">選項（單選題用，每行一個選項）</label>
                    <textarea name="options" rows="3"
                              class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent resize-none">{{ old('options') }}</textarea>
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">排序</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="w-full border border-border px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input type="checkbox" name="is_active" value="1" id="new-active" checked class="accent-accent">
                    <label for="new-active" class="text-sm text-ink tracking-wide cursor-pointer">啟用中</label>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-accent text-cream text-xs tracking-[0.3em] uppercase px-8 py-2.5 hover:bg-accent-light transition-colors">
                    新增題目
                </button>
            </div>
        </form>
    </div>

    {{-- 題目列表 --}}
    <div class="bg-white border border-border">
        <div class="border-b border-border px-8 py-5">
            <h2 class="font-serif text-lg text-ink tracking-widest">題目列表</h2>
        </div>
        @forelse($questions as $q)
        <div class="border-b border-border last:border-0 p-6" id="q-{{ $q->id }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-[10px] tracking-widest border px-2 py-0.5
                            {{ $q->type === 'star' ? 'border-amber-300 text-amber-600' : ($q->type === 'choice' ? 'border-blue-300 text-blue-600' : 'border-border text-muted') }}">
                            {{ ['star'=>'星級','text'=>'文字','choice'=>'單選'][$q->type] }}
                        </span>
                        @if(!$q->is_active)
                        <span class="text-[10px] tracking-widest border border-red-200 text-red-400 px-2 py-0.5">停用</span>
                        @endif
                        <span class="text-xs text-muted">排序 {{ $q->sort_order }}</span>
                    </div>
                    <p class="text-sm text-ink tracking-wide">{{ $q->title }}</p>
                    @if($q->description)<p class="text-xs text-muted mt-1">{{ $q->description }}</p>@endif
                    @if($q->type === 'choice' && $q->options)
                    <p class="text-xs text-muted/70 mt-1">選項：{{ implode('、', $q->options) }}</p>
                    @endif
                </div>
                <div class="flex gap-3">
                    <button onclick="document.getElementById('edit-q-{{ $q->id }}').classList.toggle('hidden')"
                            class="text-xs text-muted hover:text-accent transition-colors tracking-wide">編輯</button>
                    <form method="POST" action="{{ route('admin.survey.destroy', $q) }}"
                          onsubmit="return confirm('確定刪除此題目？所有相關回答也會刪除。')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-muted hover:text-red-400 transition-colors tracking-wide">刪除</button>
                    </form>
                </div>
            </div>

            {{-- 編輯表單 --}}
            <form id="edit-q-{{ $q->id }}" method="POST" action="{{ route('admin.survey.update', $q) }}"
                  class="hidden mt-5 pt-5 border-t border-border space-y-4">
                @csrf @method('PUT')
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] tracking-widest uppercase text-muted mb-1.5">題目標題</label>
                        <input type="text" name="title" value="{{ $q->title }}" required
                               class="w-full border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-[10px] tracking-widest uppercase text-muted mb-1.5">題目說明</label>
                        <input type="text" name="description" value="{{ $q->description }}"
                               class="w-full border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-[10px] tracking-widest uppercase text-muted mb-1.5">題型</label>
                        <select name="type" class="w-full border border-border px-3 py-2 text-sm bg-white focus:outline-none focus:border-accent">
                            <option value="star" {{ $q->type === 'star' ? 'selected' : '' }}>★ 星級評分</option>
                            <option value="text" {{ $q->type === 'text' ? 'selected' : '' }}>文字填寫</option>
                            <option value="choice" {{ $q->type === 'choice' ? 'selected' : '' }}>單選題</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] tracking-widests uppercase text-muted mb-1.5">選項（單選題）</label>
                        <textarea name="options" rows="3"
                                  class="w-full border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent resize-none">{{ $q->options ? implode("\n", $q->options) : '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] tracking-widests uppercase text-muted mb-1.5">排序</label>
                        <input type="number" name="sort_order" value="{{ $q->sort_order }}"
                               class="w-full border border-border px-3 py-2 text-sm focus:outline-none focus:border-accent">
                    </div>
                    <div class="flex items-center gap-3 pt-4">
                        <input type="checkbox" name="is_active" value="1" id="active-{{ $q->id }}" {{ $q->is_active ? 'checked' : '' }} class="accent-accent">
                        <label for="active-{{ $q->id }}" class="text-sm text-ink cursor-pointer">啟用中</label>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('edit-q-{{ $q->id }}').classList.add('hidden')"
                            class="text-xs text-muted tracking-widest hover:text-ink transition-colors">取消</button>
                    <button type="submit" class="bg-accent text-cream text-xs tracking-widest px-6 py-2 hover:bg-accent-light transition-colors">儲存</button>
                </div>
            </form>
        </div>
        @empty
        <p class="p-8 text-center text-muted text-sm tracking-wide">尚無題目，請新增第一個問卷題目</p>
        @endforelse
    </div>

</div>
@endsection
