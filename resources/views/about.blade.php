@extends('layouts.app')
@section('title', '關於我們 — 禮寵 Reiko Pet')

@section('content')

{{-- Page Header --}}
<section class="pt-32 pb-16 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="text-center">
            <p class="text-[11px] tracking-[0.5em] uppercase text-muted mb-5">Our Story</p>
            <h1 class="font-serif text-4xl lg:text-5xl text-ink tracking-widest leading-relaxed mb-6">關於禮寵</h1>
            <div class="h-px bg-border w-16 mx-auto mb-6"></div>
            <p class="text-sm text-muted tracking-wide leading-loose max-w-xl mx-auto">
                {{ $settings['hero_subtitle'] }}
            </p>
        </div>
    </div>
</section>

{{-- Brand Story --}}
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- 左側圖 --}}
            <div class="relative">
                <div class="aspect-[4/5] bg-cream-alt overflow-hidden">
                    @if($settings['founder_image'])
                        <img src="{{ Storage::url($settings['founder_image']) }}"
                             alt="創辦人故事"
                             class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/about/founder.jpg') }}" alt="創辦人 Justin 與旺財"
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="font-serif text-[120px] text-accent/10 select-none leading-none">禮</span>
                        </div>
                    @endif
                </div>
                {{-- 裝飾角線 --}}
                <div class="absolute -bottom-4 -right-4 w-24 h-24 border-r-2 border-b-2 border-accent/30"></div>
                <div class="absolute -top-4 -left-4 w-24 h-24 border-l-2 border-t-2 border-accent/30"></div>
            </div>

            {{-- 右側文字 --}}
            <div>
                <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-5">Founder's Story</p>
                <h2 class="font-serif text-3xl text-ink tracking-wide leading-relaxed mb-8">
                    {!! nl2br(e($settings['story_title'])) !!}
                </h2>

                <div class="space-y-5 text-sm text-muted leading-[2.2] tracking-wide font-light">
                    @foreach(['story_p1','story_p2','story_p3','story_p4'] as $key)
                        @if($settings[$key])
                        <p>{{ $settings[$key] }}</p>
                        @endif
                    @endforeach
                </div>

                <div class="mt-10 pt-8 border-t border-border flex gap-10">
                    <div>
                        <p class="font-serif text-3xl text-accent">{{ $settings['stat_founded'] }}</p>
                        <p class="text-[11px] tracking-widest text-muted mt-1.5">創立年份</p>
                    </div>
                    <div>
                        <p class="font-serif text-3xl text-accent">{{ $settings['stat_stores'] }}</p>
                        <p class="text-[11px] tracking-widest text-muted mt-1.5">高雄門市</p>
                    </div>
                    <div>
                        <p class="font-serif text-3xl text-accent">{{ $settings['stat_satisfaction'] }}</p>
                        <p class="text-[11px] tracking-widest text-muted mt-1.5">顧客滿意</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Brand Values --}}
<section class="py-20 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="text-center mb-14">
            <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-4">Our Values</p>
            <h2 class="font-serif text-3xl text-ink tracking-widest">品牌核心價值</h2>
            <div class="h-px bg-border w-12 mx-auto mt-6"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                [$settings['val1_icon'], $settings['val1_title'], $settings['val1_desc']],
                [$settings['val2_icon'], $settings['val2_title'], $settings['val2_desc']],
                [$settings['val3_icon'], $settings['val3_title'], $settings['val3_desc']],
            ] as [$icon, $title, $desc])
            <div class="text-center px-6 py-10 bg-white border border-border">
                <div class="font-serif text-5xl text-accent/30 mb-5 leading-none">{{ $icon }}</div>
                <h3 class="font-serif text-lg text-ink tracking-widest mb-4">{{ $title }}</h3>
                <p class="text-xs text-muted leading-[2] tracking-wide">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stores --}}
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="text-center mb-14">
            <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-4">Our Locations</p>
            <h2 class="font-serif text-3xl text-ink tracking-widest">門市據點</h2>
            <div class="h-px bg-border w-12 mx-auto mt-6"></div>
        </div>

        <div class="space-y-12">
            @forelse($stores as $index => $store)
            <div class="grid lg:grid-cols-2 gap-0 border border-border {{ $loop->even ? 'lg:flex-row-reverse' : '' }}">

                {{-- 門市圖片 --}}
                <div class="h-72 lg:h-auto bg-cream-alt overflow-hidden relative flex items-center justify-center
                            {{ $loop->even ? 'lg:order-2' : '' }}">
                    <img src="{{ asset('images/stores/0' . ($index + 1) . '.jpg') }}"
                         alt="{{ $store->name }}"
                         class="w-full h-full object-cover"
                         onerror="this.style.display='none'">
                    <span class="absolute font-serif text-6xl text-accent/15 select-none pointer-events-none">禮</span>
                </div>

                {{-- 門市資訊 --}}
                <div class="p-10 lg:p-14 flex flex-col justify-center {{ $loop->even ? 'lg:order-1' : '' }}">
                    <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">
                        Store {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </p>
                    <h3 class="font-serif text-2xl text-ink tracking-widest mb-6">{{ $store->name }}</h3>

                    <div class="space-y-3 mb-8">
                        @if($store->address && $store->address !== 'XXX')
                        <div class="flex gap-3 text-sm text-muted tracking-wide">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $store->address }}</span>
                        </div>
                        @endif

                        @if($store->phone && $store->phone !== 'XXX')
                        <div class="flex gap-3 text-sm text-muted tracking-wide">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
                            </svg>
                            <span>{{ $store->phone }}</span>
                        </div>
                        @endif

                        <div class="flex gap-3 text-sm text-muted tracking-wide">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $store->business_hours }}</span>
                        </div>
                    </div>

                    @if($store->description)
                    <p class="text-xs text-muted leading-[2.2] tracking-wide border-t border-border pt-6 italic">
                        {{ $store->description }}
                    </p>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-16 text-muted tracking-widest text-sm">門市資料載入中…</div>
            @endforelse
        </div>
    </div>
</section>

{{-- 顧客滿意度問卷 --}}
@if($surveyQuestions->isNotEmpty())
<section class="py-20 bg-cream" id="survey">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-12">
            <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-4">Customer Feedback</p>
            <h2 class="font-serif text-3xl text-ink tracking-widest leading-relaxed">顧客滿意度回饋</h2>
            <div class="h-px bg-border w-12 mx-auto mt-6"></div>
        </div>

        @if(session('survey_success'))
        <div class="mb-8 bg-accent/10 border border-accent/20 px-6 py-4 text-sm text-accent tracking-wide text-center">
            {{ session('survey_success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-8 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('survey.submit') }}"
              class="bg-white border border-border p-8 lg:p-12">
            @csrf

            {{-- 橫式排版：左右兩欄，大螢幕 2 欄 --}}
            <div class="grid md:grid-cols-2 gap-x-12 gap-y-10">
                @foreach($surveyQuestions as $q)
                <div class="{{ $q->type === 'text' ? 'md:col-span-2' : '' }}">
                    <p class="text-sm text-ink tracking-wide mb-1">{{ $q->title }}</p>
                    @if($q->description)
                    <p class="text-xs text-muted tracking-wide mb-3">{{ $q->description }}</p>
                    @else
                    <div class="mb-3"></div>
                    @endif

                    @if($q->type === 'star')
                    <div class="flex items-center gap-2" x-data="{ rating{{ $q->id }}: {{ old('answers.' . $q->id, 0) }} }">
                        @for($s = 1; $s <= 5; $s++)
                        <button type="button"
                                @click="rating{{ $q->id }} = {{ $s }}"
                                :class="rating{{ $q->id }} >= {{ $s }} ? 'text-amber-400' : 'text-border hover:text-amber-300'"
                                class="text-3xl transition-colors leading-none">★</button>
                        @endfor
                        <input type="hidden" name="answers[{{ $q->id }}]" :value="rating{{ $q->id }}">
                        <span class="text-xs text-muted tracking-wide ml-1"
                              x-text="['','非常不滿意','不滿意','普通','滿意','非常滿意'][rating{{ $q->id }}] || ''"></span>
                    </div>

                    @elseif($q->type === 'choice' && $q->options)
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($q->options as $opt)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}"
                                   {{ old('answers.' . $q->id) === $opt ? 'checked' : '' }}
                                   class="accent-accent">
                            <span class="text-sm text-ink tracking-wide group-hover:text-accent transition-colors">{{ $opt }}</span>
                        </label>
                        @endforeach
                    </div>

                    @else {{-- text --}}
                    <textarea name="answers[{{ $q->id }}]" rows="4"
                              placeholder="請分享您的想法…"
                              class="w-full border border-border px-4 py-3 text-sm text-ink tracking-wide
                                     focus:outline-none focus:border-accent transition-colors resize-none">{{ old('answers.' . $q->id) }}</textarea>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="flex justify-center pt-10 border-t border-border mt-10">
                <button type="submit"
                        class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-16 py-3.5
                               hover:bg-accent-light transition-colors duration-300">
                    提交回饋
                </button>
            </div>
        </form>
    </div>
</section>
@endif

{{-- Join Us --}}
<section class="py-20 bg-accent text-cream">
    <div class="max-w-4xl mx-auto px-6 lg:px-10 text-center">
        <p class="text-[10px] tracking-[0.5em] uppercase opacity-60 mb-5">Join Our Team</p>
        <h2 class="font-serif text-3xl lg:text-4xl tracking-widest leading-relaxed mb-6">
            加入禮寵，成為職人的一員
        </h2>
        <div class="h-px bg-cream/20 w-16 mx-auto mb-8"></div>
        <p class="text-sm opacity-75 tracking-wide leading-[2.2] mb-10 max-w-lg mx-auto">
            我們正在尋找熱愛動物、追求職人精神的美容師夥伴。<br>
            燕巢旗艦店、水星門市、木星門市皆有職缺。
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="mailto:hr@reikopet.com"
               class="inline-block text-[11px] tracking-[0.3em] uppercase px-8 py-3.5
                      border border-cream text-cream hover:bg-cream hover:text-accent transition-colors duration-300">
                投遞履歷
            </a>
        </div>
    </div>
</section>

@endsection
