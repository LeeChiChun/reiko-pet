@extends('layouts.app')
@section('title', '預約 Step 4 — 選擇門市與時間')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        @include('booking._steps', ['current' => 4])

        <div class="mb-10">
            <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Step 04 / 05</p>
            <h1 class="font-serif text-3xl text-ink tracking-widest leading-relaxed">選擇門市與時間</h1>
        </div>

        @if($errors->any())
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('booking.save4') }}">
            @csrf

            {{-- Store --}}
            <div class="mb-10">
                <p class="text-[11px] tracking-[0.35em] uppercase text-muted mb-5">選擇門市</p>
                <div class="space-y-4">
                    @forelse($stores as $store)
                    <label class="flex items-start gap-5 bg-white border border-border p-7 cursor-pointer
                                  hover:border-accent transition-colors has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                        <input type="radio" name="store_id" value="{{ $store->id }}"
                               class="accent-accent mt-1"
                               {{ (old('store_id') == $store->id || session('booking.store_id') == $store->id) ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm text-ink tracking-widest mb-2">{{ $store->name }}</p>
                            @if($store->address)
                            <p class="text-xs text-muted tracking-wide mb-1">📍 {{ $store->address }}</p>
                            @endif
                            @if($store->phone)
                            <p class="text-xs text-muted tracking-wide mb-1">📞 {{ $store->phone }}</p>
                            @endif
                            @if($store->business_hours)
                            <p class="text-xs text-muted tracking-wide">🕐 {{ $store->business_hours }}</p>
                            @endif
                        </div>
                    </label>
                    @empty
                    <p class="text-center py-12 text-muted tracking-widest">目前無門市資料</p>
                    @endforelse
                </div>
            </div>

            {{-- Date & Time --}}
            <div class="bg-white border border-border p-8 space-y-6 mb-8">
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-3">預約日期</label>
                    <input type="date" name="date"
                           value="{{ old('date') }}"
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           max="{{ now()->addMonths(2)->format('Y-m-d') }}"
                           class="w-full border border-border px-4 py-3.5 text-sm text-ink focus:outline-none focus:border-accent transition-colors bg-white">
                </div>
                <div>
                    <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-3">預約時段</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30'] as $time)
                        <label class="text-center border border-border py-3 cursor-pointer text-sm text-muted
                                      hover:border-accent hover:text-accent transition-colors
                                      has-[:checked]:border-accent has-[:checked]:bg-accent has-[:checked]:text-cream">
                            <input type="radio" name="time" value="{{ $time }}" class="sr-only"
                                   {{ old('time') === $time ? 'checked' : '' }}>
                            {{ $time }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('booking.step3') }}"
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
