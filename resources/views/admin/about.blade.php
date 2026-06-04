@extends('layouts.admin')
@section('title', '關於我們管理')
@section('page-title', 'About Us')

@section('content')

<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data" class="space-y-10">
    @csrf

    {{-- 頁首副標 --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">頁首副標</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應 /about 頁面最上方副標題文字</p>
        <textarea name="setting_about_hero_subtitle" rows="2"
                  class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings['about_hero_subtitle'] ?? '' }}</textarea>
    </div>

    {{-- 創辦人照片 --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">創辦人照片</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應 /about 頁面右側大圖</p>
        @if(!empty($settings['about_founder_image']))
        <div class="mb-4">
            <img src="{{ Storage::url($settings['about_founder_image']) }}" alt="創辦人" class="h-40 object-cover border border-border">
            <p class="text-xs text-muted mt-2 tracking-wide">目前照片，上傳新圖片將取代</p>
        </div>
        @endif
        <input type="file" name="setting_about_founder_image" accept="image/*" class="text-sm text-muted">
        <p class="text-[10px] text-muted mt-1 tracking-wide">建議尺寸：600×800px（直式人像）</p>
    </div>

    {{-- 品牌故事 --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">品牌故事</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應 /about 頁面故事文字段落</p>

        <div class="space-y-5">
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">故事大標題</label>
                    <input type="text" name="setting_about_story_title"
                           value="{{ $settings['about_story_title'] ?? '' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">創立年份</label>
                    <input type="text" name="setting_about_stat_founded"
                           value="{{ $settings['about_stat_founded'] ?? '2026' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
            </div>

            @foreach(['about_story_p1' => '故事段落一', 'about_story_p2' => '故事段落二', 'about_story_p3' => '故事段落三', 'about_story_p4' => '故事段落四'] as $key => $label)
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">{{ $label }}</label>
                <textarea name="setting_{{ $key }}" rows="3"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings[$key] ?? '' }}</textarea>
            </div>
            @endforeach
        </div>
    </div>

    {{-- 品牌核心價值 --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">品牌核心價值</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應 /about 頁面三個核心價值卡片</p>

        <div class="space-y-5">
            @foreach([1,2,3] as $i)
            <div class="grid md:grid-cols-3 gap-4 border border-cream-alt p-5">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">價值{{ $i }} 字符</label>
                    <input type="text" name="setting_about_val{{ $i }}_icon"
                           value="{{ $settings['about_val'.$i.'_icon'] ?? '' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent"
                           placeholder="Emoji 或符號">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">價值{{ $i }} 標題</label>
                    <input type="text" name="setting_about_val{{ $i }}_title"
                           value="{{ $settings['about_val'.$i.'_title'] ?? '' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">價值{{ $i }} 說明</label>
                    <textarea name="setting_about_val{{ $i }}_desc" rows="3"
                              class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings['about_val'.$i.'_desc'] ?? '' }}</textarea>
                </div>
            </div>
            @endforeach
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
