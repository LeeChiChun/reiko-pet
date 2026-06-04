@extends('layouts.admin')
@section('title', '住宿內容管理')
@section('page-title', 'Accommodation CMS')

@section('content')

<form method="POST" action="{{ route('admin.accommodation.cms.update') }}" enctype="multipart/form-data" class="space-y-10">
    @csrf

    {{-- Hero 區塊 --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">Hero 區塊</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應 /accommodation 頁面最上方標題</p>

        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">Hero 標題</label>
                <input type="text" name="setting_accom_hero_title"
                       value="{{ $settings['accom_hero_title'] ?? '寵物住宿' }}"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">Hero 副標</label>
                <textarea name="setting_accom_hero_subtitle" rows="3"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings['accom_hero_subtitle'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- 住宿福利 --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">住宿福利</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應住宿頁面 8 項福利列表</p>

        <div class="space-y-4">
            @for($i = 1; $i <= 8; $i++)
            <div class="grid grid-cols-3 gap-3 items-start border border-cream-alt p-4">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">福利{{ $i }} 圖示</label>
                    <input type="text" name="setting_accom_b{{ $i }}_icon"
                           value="{{ $settings['accom_b'.$i.'_icon'] ?? '' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent"
                           placeholder="Emoji 或文字">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">福利{{ $i }} 標題</label>
                    <input type="text" name="setting_accom_b{{ $i }}_title"
                           value="{{ $settings['accom_b'.$i.'_title'] ?? '' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">福利{{ $i }} 說明</label>
                    <textarea name="setting_accom_b{{ $i }}_desc" rows="2"
                              class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings['accom_b'.$i.'_desc'] ?? '' }}</textarea>
                </div>
            </div>
            @endfor
        </div>
    </div>

    {{-- 住宿須知 --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">住宿須知</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應住宿頁面 4 組須知卡片</p>

        <div class="space-y-4">
            @for($i = 1; $i <= 4; $i++)
            <div class="grid md:grid-cols-2 gap-4 border border-cream-alt p-4">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">須知{{ $i }} 標題</label>
                    <input type="text" name="setting_accom_r{{ $i }}_title"
                           value="{{ $settings['accom_r'.$i.'_title'] ?? '' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">須知{{ $i }} 內容（每行一項）</label>
                    <textarea name="setting_accom_r{{ $i }}_items" rows="4"
                              class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings['accom_r'.$i.'_items'] ?? '' }}</textarea>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit"
                class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-10 py-3.5
                       hover:bg-accent-light transition-colors">
            儲存變更
        </button>
    </div>

</form>

@endsection
