@extends('layouts.app')
@section('title', '加入會員 — 禮寵 Reiko Pet')

@section('content')
<div class="min-h-screen bg-cream flex items-center justify-center px-6 py-24">
    <div class="w-full max-w-md">

        <div class="text-center mb-12">
            <a href="{{ url('/') }}">
                <p class="font-serif text-3xl tracking-widest text-accent">禮寵</p>
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mt-1">Reiko Pet</p>
            </a>
            <div class="mt-8 h-px bg-border w-16 mx-auto"></div>
            <h1 class="mt-8 text-sm tracking-[0.3em] text-ink uppercase">加入會員</h1>
        </div>

        @if($errors->any())
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide leading-relaxed">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        <form method="POST" action="{{ url('/register') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">姓名</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full bg-white border border-border px-4 py-3.5 text-sm text-ink tracking-wide
                              focus:outline-none focus:border-accent transition-colors"
                       placeholder="您的名字">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">電子信箱</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-white border border-border px-4 py-3.5 text-sm text-ink tracking-wide
                              focus:outline-none focus:border-accent transition-colors"
                       placeholder="your@email.com">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">手機號碼</label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       pattern="^09[0-9]{8}$"
                       title="請輸入 09 開頭的 10 碼手機號碼"
                       class="w-full bg-white border border-border px-4 py-3.5 text-sm text-ink tracking-wide
                              focus:outline-none focus:border-accent transition-colors"
                       placeholder="09xxxxxxxx">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">密碼</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full bg-white border border-border px-4 py-3.5 text-sm text-ink tracking-wide
                              focus:outline-none focus:border-accent transition-colors"
                       placeholder="至少 8 個字元">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">確認密碼</label>
                <input type="password" name="password_confirmation" required
                       class="w-full bg-white border border-border px-4 py-3.5 text-sm text-ink tracking-wide
                              focus:outline-none focus:border-accent transition-colors"
                       placeholder="再次輸入密碼">
            </div>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-4
                           hover:bg-accent-light transition-colors duration-300">
                建立帳號
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-xs text-muted tracking-wide">
                已有帳號？
                <a href="{{ url('/login') }}" class="text-accent underline underline-offset-4 hover:text-accent-light">直接登入</a>
            </p>
        </div>

    </div>
</div>
@endsection
