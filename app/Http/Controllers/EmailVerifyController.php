<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerifyController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function show()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('member.index');
        }
        return view('auth.email-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => '請輸入驗證碼',
            'code.size'     => '驗證碼為 6 位數',
        ]);

        $user = Auth::user();

        if (!$this->otpService->verify($user, $request->code, 'register')) {
            return back()->withErrors(['code' => '驗證碼不正確或已過期']);
        }

        $user->update(['email_verified_at' => now()]);

        return redirect()->route('member.index')->with('success', '信箱驗證成功，歡迎加入！');
    }

    public function resend()
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', '信箱已驗證');
        }

        $code = $this->otpService->generate($user, 'register');
        $this->otpService->sendByMail($user, $code, 'register');

        return back()->with('success', '驗證碼已重新寄出');
    }
}
