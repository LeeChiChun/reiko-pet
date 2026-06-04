@extends('layouts.app')
@section('title', '預約 Step 3 — 加值項目')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        @include('booking._steps', ['current' => 3])

        <div class="mb-10">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Step 03 / 05</p>
            <h1 class="font-serif text-3xl text-ink tracking-widest leading-relaxed">加值項目</h1>
            <p class="text-sm text-muted tracking-wide mt-3">可複選，不需要可直接跳過</p>
        </div>

        <form method="POST" action="{{ route('booking.save3') }}">
            @csrf

            @if($addons->isEmpty())
            <div class="bg-white border border-border p-12 text-center text-muted tracking-widest text-sm">
                目前無加值項目
            </div>
            @else
            <div class="space-y-3">
                @foreach($addons as $addon)
                <label class="flex items-start gap-5 bg-white border border-border p-6 cursor-pointer
                              hover:border-accent transition-colors has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                    <input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}"
                           class="accent-accent mt-1"
                           {{ in_array($addon->id, old('addon_ids', session('booking.addon_ids', []))) ? 'checked' : '' }}>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <p class="text-sm text-ink tracking-widest">{{ $addon->name }}</p>
                            <p class="text-accent font-medium text-sm shrink-0">+NT$ {{ number_format($addon->price) }}</p>
                        </div>
                        @if($addon->description)
                        <p class="text-xs text-muted tracking-wide mt-2 leading-relaxed">{{ $addon->description }}</p>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>
            @endif

            <div class="mt-8 flex justify-between">
                <a href="{{ route('booking.step2') }}"
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
