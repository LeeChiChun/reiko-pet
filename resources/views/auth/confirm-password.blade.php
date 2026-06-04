@extends('layouts.app')

@section('title', '確認密碼')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">確認密碼</h1>
        <p class="text-gray-500 text-sm mb-6">此操作為敏感操作，請再次輸入密碼以繼續</p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm.submit') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">密碼</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#6B4C3B]"
                    autofocus>
            </div>
            <button type="submit"
                class="w-full bg-[#6B4C3B] text-white py-2 rounded-lg hover:bg-[#5a3d2f] transition font-medium">
                確認
            </button>
        </form>
    </div>
</div>
@endsection
