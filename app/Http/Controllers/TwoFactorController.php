<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function showVerify()
    {
        return view('auth.2fa-verify');
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

        if (!$this->otpService->verify($user, $request->code, 'login')) {
            return back()->withErrors(['code' => '驗證碼不正確或已過期']);
        }

        session(['2fa_verified' => true]);

        if ($user->isAdmin())   return redirect()->route('admin.dashboard');
        return redirect()->route('groomer.schedule');
    }
}
