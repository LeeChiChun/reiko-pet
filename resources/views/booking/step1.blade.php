@extends('layouts.app')
@section('title', '預約 Step 1 — 選擇寵物')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-2xl mx-auto px-6 lg:px-10">

        @include('booking._steps', ['current' => 1])

        <div class="mb-10">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Step 01 / 05</p>
            <h1 class="font-serif text-3xl text-ink tracking-widest leading-relaxed">選擇寵物</h1>
        </div>

        @if($errors->any())
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        @if($pets->isEmpty())
        <div class="bg-white border border-border p-12 text-center">
            <p class="font-serif text-xl text-ink/50 tracking-widest mb-6">尚未登記任何寵物</p>
            <p class="text-sm text-muted tracking-wide mb-8">請先至會員中心新增您的寵物，再進行預約。</p>
            <a href="{{ route('member.pets') }}"
               class="inline-block bg-accent text-cream text-sm tracking-[0.3em] uppercase px-10 py-3.5
                      hover:bg-accent-light transition-colors duration-300">
                前往新增寵物
            </a>
        </div>
        @else

        <form method="POST" action="{{ route('booking.save1') }}">
            @csrf
            <div class="space-y-4 mb-8">
                @foreach($pets as $pet)
                <label class="flex items-center gap-5 bg-white border border-border p-6 cursor-pointer
                              hover:border-accent transition-colors has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                    <input type="radio" name="pet_id" value="{{ $pet->id }}"
                           class="accent-accent"
                           {{ (old('pet_id') == $pet->id || session('booking.pet_id') == $pet->id) ? 'checked' : '' }}>
                    <div>
                        <p class="text-sm text-ink tracking-widest mb-1">{{ $pet->name }}</p>
                        <p class="text-xs text-muted tracking-wide">
                            {{ ['dog'=>'狗狗','cat'=>'貓咪','other'=>'其他'][$pet->type] ?? $pet->type }}
                            @if($pet->breed) · {{ $pet->breed }} @endif
                            @if($pet->weight) · {{ $pet->weight }} kg @endif
                        </p>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="flex justify-between items-center mt-8">
                <a href="{{ route('member.pets') }}"
                   class="text-xs text-muted tracking-widest hover:text-accent transition-colors">
                    + 新增寵物
                </a>
                <button type="submit"
                        class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-12 py-4
                               hover:bg-accent-light transition-colors duration-300">
                    下一步
                </button>
            </div>
        </form>

        @endif
    </div>
</section>

@endsection
