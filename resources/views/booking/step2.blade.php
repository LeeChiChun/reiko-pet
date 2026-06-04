@extends('layouts.app')
@section('title', '預約 Step 2 — 選擇服務')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        @include('booking._steps', ['current' => 2])

        <div class="mb-10">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Step 02 / 05</p>
            <h1 class="font-serif text-3xl text-ink tracking-widest leading-relaxed">選擇服務</h1>
            <p class="text-sm text-muted tracking-wide mt-3">
                毛孩：<span class="text-ink">{{ session('booking.pet_name') }}</span>
                （{{ ['dog'=>'狗狗','cat'=>'貓咪','other'=>'其他'][session('booking.pet_type')] ?? '' }}）
            </p>
        </div>

        @if($errors->any())
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        {{-- 基礎洗澡固定包含標籤 --}}
        <div class="flex items-center gap-3 mb-8 px-5 py-3.5 bg-cream-alt border border-border">
            <svg class="w-3.5 h-3.5 text-muted shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            <span class="text-xs text-muted tracking-[0.25em]">所有服務皆已包含基礎洗澡，無法取消</span>
            <span class="ml-auto text-[10px] tracking-[0.2em] uppercase border border-border px-3 py-1 text-muted shrink-0 whitespace-nowrap">已包含基礎洗澡</span>
        </div>

        <form method="POST" action="{{ route('booking.save2') }}">
            @csrf

            @php
            $petType = session('booking.pet_type');
            $sections = [];
            if($singles->isNotEmpty())  $sections[] = ['單項服務', $singles];
            if($petType === 'dog' && $dogSvcs->isNotEmpty()) $sections[] = ['狗狗全套', $dogSvcs];
            if($petType === 'cat' && $catSvcs->isNotEmpty()) $sections[] = ['貓咪全套', $catSvcs];
            if($smallPkgs->isNotEmpty()) $sections[] = ['小套餐', $smallPkgs];
            if($largePkgs->isNotEmpty()) $sections[] = ['大套餐', $largePkgs];
            @endphp

            @foreach($sections as [$sectionLabel, $group])
            <div class="mb-8">
                <p class="text-[11px] tracking-[0.35em] uppercase text-muted mb-4">{{ $sectionLabel }}</p>
                <div class="space-y-3">
                    @foreach($group as $service)
                    <label class="flex items-start gap-5 bg-white border border-border p-6 cursor-pointer
                                  hover:border-accent transition-colors has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                        <input type="radio" name="service_id" value="{{ $service->id }}"
                               class="accent-accent mt-1"
                               {{ (old('service_id') == $service->id || session('booking.service_id') == $service->id) ? 'checked' : '' }}>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <p class="text-sm text-ink tracking-widest">{{ $service->name }}</p>
                                <p class="text-accent font-medium text-sm shrink-0">NT$ {{ number_format($service->price) }}</p>
                            </div>
                            @if($service->description)
                            <p class="text-xs text-muted tracking-wide mt-2 leading-relaxed">{{ $service->description }}</p>
                            @endif
                            @if($service->duration_minutes)
                            <p class="text-[11px] text-muted tracking-widest mt-2">約 {{ $service->duration_minutes }} 分鐘</p>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

            @if(collect($sections)->isEmpty())
            <p class="text-center py-12 text-muted tracking-widest border border-border bg-white">目前無可預約的服務</p>
            @endif

            <div class="mt-8 flex justify-between">
                <a href="{{ route('booking.step1') }}"
                   class="border border-border text-muted text-sm tracking-[0.3em] uppercase px-10 py-4
                          hover:border-accent hover:text-accent transition-colors duration-300">
                    上一步
                </a>
                <button type="submit"
                        class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-12 py-4
                               hover:bg-accent-light transition-colors duration-300">
                    下一步
                </button>
            </div>
        </form>
    </div>
</section>

@endsection
