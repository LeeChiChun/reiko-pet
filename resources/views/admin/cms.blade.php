@extends('layouts.admin')
@section('title', '內容管理')
@section('page-title', 'Content Management')

@section('content')

<form method="POST" action="{{ route('admin.cms.update') }}" enctype="multipart/form-data" class="space-y-10">
    @csrf

    {{-- ════════════════════════════════════════════════
        首頁 Hero 區塊
    ════════════════════════════════════════════════ --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widest mb-1">首頁 Hero</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應首頁最上方的大標題區塊</p>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">Badge 文字</label>
                <input type="text" name="setting_hero_badge"
                       value="{{ $settings['hero_badge'] ?? 'Premium Pet Grooming' }}"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">主標題</label>
                <input type="text" name="setting_hero_title"
                       value="{{ $settings['hero_title'] ?? '' }}"
                       class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent"
                       placeholder="留空則使用預設文字">
            </div>
            <div class="md:col-span-2">
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">副標題</label>
                <textarea name="setting_hero_subtitle" rows="3"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none"
                          placeholder="留空則使用預設文字">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">背景圖片</label>
                @if(!empty($settings['hero_image']))
                <div class="mb-3">
                    <img src="{{ Storage::url($settings['hero_image']) }}" alt="Hero" class="h-32 object-cover border border-border">
                    <p class="text-xs text-muted mt-1 tracking-wide">目前圖片，上傳新圖片將取代</p>
                </div>
                @endif
                <input type="file" name="setting_hero_image" accept="image/*" class="text-sm text-muted">
                <p class="text-[10px] text-muted mt-1 tracking-wide">建議尺寸：1920×800px（橫式全寬）</p>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════
        首頁 品牌故事 + Philosophy 區塊
    ════════════════════════════════════════════════ --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">首頁 OUR PHILOSOPHY 區塊</h2>
        <p class="text-xs text-muted tracking-wide mb-7">對應首頁品牌理念區塊</p>

        <div class="space-y-5">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">左側圖片</label>
                    @if(!empty($settings['philosophy_image']))
                    <div class="mb-3">
                        <img src="{{ Storage::url($settings['philosophy_image']) }}" alt="Philosophy" class="h-32 object-cover border border-border">
                        <p class="text-xs text-muted mt-1 tracking-wide">目前圖片，上傳新圖片將取代</p>
                    </div>
                    @endif
                    <input type="file" name="setting_philosophy_image" accept="image/*" class="text-sm text-muted">
                    <p class="text-[10px] text-muted mt-1 tracking-wide">建議尺寸：800×600px（橫式）</p>
                    <p class="text-xs text-muted mt-1 tracking-wide">留空則顯示預設裝飾方框</p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">高雄門市 數字</label>
                    <input type="text" name="setting_stat_stores"
                           value="{{ $settings['stat_stores'] ?? '3+' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">服務毛孩 數字</label>
                    <input type="text" name="setting_stat_pets"
                           value="{{ $settings['stat_pets'] ?? '500+' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">顧客滿意 數字</label>
                    <input type="text" name="setting_stat_satisfaction"
                           value="{{ $settings['stat_satisfaction'] ?? '98%' }}"
                           class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent">
                </div>
            </div>

            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">品牌故事 段落一</label>
                <textarea name="setting_brand_story_text_1" rows="4"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings['brand_story_text_1'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">品牌故事 段落二</label>
                <textarea name="setting_brand_story_text_2" rows="4"
                          class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent resize-none">{{ $settings['brand_story_text_2'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════
        其他管理捷徑
    ════════════════════════════════════════════════ --}}
    <div class="bg-white border border-border p-8">
        <h2 class="font-serif text-lg text-ink tracking-widests mb-1">其他內容管理</h2>
        <p class="text-xs text-muted tracking-wide mb-7">以下項目請至各專屬管理頁面操作</p>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['route' => 'admin.stores',        'label' => '門市資訊',  'desc' => '地址、電話、營業時間'],
                ['route' => 'admin.services',       'label' => '服務項目',  'desc' => '美容服務品項與價格'],
                ['route' => 'admin.announcements',  'label' => '最新公告',  'desc' => '公告發布與下架'],
                ['route' => 'admin.promotions',     'label' => '活動優惠',  'desc' => '促銷活動與折扣'],
                ['route' => 'admin.articles',       'label' => '寵物專欄',  'desc' => '文章新增與編輯'],
                ['route' => 'admin.flyers',         'label' => 'DM 傳單',   'desc' => '傳單上傳與管理'],
                ['route' => 'admin.products',       'label' => '線上商品',  'desc' => '商品上架與管理'],
            ] as $shortcut)
            <a href="{{ route($shortcut['route']) }}"
               class="border border-border p-5 hover:border-accent transition-colors duration-200 group">
                <p class="text-sm text-ink tracking-wide group-hover:text-accent transition-colors">{{ $shortcut['label'] }}</p>
                <p class="text-xs text-muted tracking-wide mt-1">{{ $shortcut['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit"
                class="bg-accent text-cream text-sm tracking-[0.3em] uppercase py-3.5 px-10
                       hover:bg-accent-light transition-colors duration-300">
            儲存所有變更
        </button>
    </div>

</form>

@endsection
