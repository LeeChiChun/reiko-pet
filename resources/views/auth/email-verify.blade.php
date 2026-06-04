@extends('layouts.app')

@section('title', '信箱驗證')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">驗證您的信箱</h1>
        <p class="text-gray-500 text-sm mb-6">
            已寄出 6 位數驗證碼到 <span class="font-medium text-gray-700">{{ Auth::user()->email }}</span>，請於 10 分鐘內輸入。
        </p>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('email.verify.submit') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">驗證碼</label>
                <input type="text" name="code" inputmode="numeric"
                    maxlength="6" autocomplete="one-time-code"
                    class="w-full border rounded-lg px-4 py-2 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-[#6B4C3B]"
                    autofocus>
            </div>
            <button type="submit"
                class="w-full bg-[#6B4C3B] text-white py-2 rounded-lg hover:bg-[#5a3d2f] transition font-medium">
                確認驗證
            </button>
        </form>

        <form method="POST" action="{{ route('email.verify.resend') }}" class="mt-4">
            @csrf
            <button type="submit" class="w-full text-sm text-gray-500 hover:text-gray-700 underline">
                沒收到？重新寄送驗證碼
            </button>
        </form>
    </div>
</div>
@endsection
