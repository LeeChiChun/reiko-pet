@extends('layouts.app')
@section('title', '個人資料 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-6 lg:px-10">

        {{-- Header --}}
        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">會員中心</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-muted tracking-widest hover:text-ink transition-colors">登出</button>
            </form>
        </div>

        {{-- Nav Tabs --}}
        @include('member._tabs')

        @if(session('success'))
        <div class="mb-8 bg-accent/10 border border-accent/20 px-5 py-4 text-xs text-accent tracking-wide">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-8 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        {{-- Profile Form --}}
        <div class="bg-white border border-border p-10">
            <h2 class="font-serif text-xl text-ink tracking-widest mb-8">基本資料</h2>
            <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">姓名</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">手機號碼</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                               pattern="^09[0-9]{8}$"
                               title="請輸入 09 開頭的 10 碼手機號碼"
                               class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                               placeholder="09xxxxxxxx">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">電子信箱</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full border border-border px-4 py-3 text-sm text-muted bg-cream">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                            class="bg-accent text-cream text-sm tracking-[0.3em] uppercase px-10 py-3.5
                                   hover:bg-accent-light transition-colors duration-300">
                        儲存變更
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

@endsection
