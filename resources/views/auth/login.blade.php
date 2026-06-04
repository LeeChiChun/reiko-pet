@extends('layouts.app')
@section('title', '登入 — 禮寵 Reiko Pet')

@section('content')
<div class="min-h-screen bg-cream flex items-center justify-center px-6 py-24">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-12">
            <a href="{{ url('/') }}">
                <p class="font-serif text-3xl tracking-widest text-accent">禮寵</p>
                <p class="text-[10px] tracking-[0.4em] uppercase text-muted mt-1">Reiko Pet</p>
            </a>
            <div class="mt-8 h-px bg-border w-16 mx-auto"></div>
            <h1 class="mt-8 text-sm tracking-[0.3em] text-ink uppercase">會員登入</h1>
        </div>

        {{-- Error --}}
        @if($errors->any())
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide leading-relaxed">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ url('/login') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">電子信箱</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-white border border-border px-4 py-3.5 text-sm text-ink tracking-wide
                              focus:outline-none focus:border-accent transition-colors placeholder-warm-gray"
                       placeholder="your@email.com">
            </div>
            <div>
                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">密碼</label>
                <div class="relative">
                    <input id="password-input" type="password" name="password" required
                           class="w-full bg-white border border-border px-4 py-3.5 text-sm text-ink tracking-wide
                                  focus:outline-none focus:border-accent transition-colors placeholder-warm-gray pr-12"
                           placeholder="••••••••">
                    <button type="button" id="toggle-password"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-ink transition-colors"
                            aria-label="顯示/隱藏密碼">
                        <svg id="eye-open" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg id="eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-xs text-muted tracking-wide cursor-pointer">
                    <input type="checkbox" name="remember" class="accent-accent">
                    記住我
                </label>
            </div>
            <button type="submit"
                    class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-4
                           hover:bg-accent-light transition-colors duration-300">
                登入
            </button>
        </form>

        {{-- Divider --}}
        <div class="my-8 flex items-center gap-4">
            <div class="flex-1 h-px bg-border"></div>
            <span class="text-xs text-muted tracking-widest">or</span>
            <div class="flex-1 h-px bg-border"></div>
        </div>

        <div class="text-center space-y-3">
            <p class="text-xs text-muted tracking-wide">
                還沒有帳號？
                <a href="{{ url('/register') }}" class="text-accent underline underline-offset-4 hover:text-accent-light">註冊</a>
            </p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('toggle-password').addEventListener('click', function () {
    const input = document.getElementById('password-input');
    const open  = document.getElementById('eye-open');
    const closed = document.getElementById('eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        open.classList.add('hidden');
        closed.classList.remove('hidden');
    } else {
        input.type = 'password';
        open.classList.remove('hidden');
        closed.classList.add('hidden');
    }
});
</script>
@endpush
