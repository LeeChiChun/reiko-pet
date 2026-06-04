@extends('layouts.app')

@section('title', '設定雙重驗證')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">設定雙重驗證</h1>
        <p class="text-gray-500 text-sm mb-6">
            請用 Google Authenticator 掃描以下 QR Code，完成後輸入驗證碼確認
        </p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex justify-center mb-6">
            {!! $qrCodeSvg !!}
        </div>

        <form method="POST" action="{{ route('2fa.setup.submit') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">輸入驗證碼確認</label>
                <input type="text" name="one_time_password" inputmode="numeric"
                    maxlength="6" autocomplete="one-time-code"
                    class="w-full border rounded-lg px-4 py-2 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-[#6B4C3B]"
                    autofocus>
            </div>
            <button type="submit"
                class="w-full bg-[#6B4C3B] text-white py-2 rounded-lg hover:bg-[#5a3d2f] transition font-medium">
                完成設定
            </button>
        </form>
    </div>
</div>
@endsection
