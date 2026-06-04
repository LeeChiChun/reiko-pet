<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS    = 5;
    private const LOCKOUT_MINUTES = 15;

    public function __construct(private OtpService $otpService) {}

    public function showLogin()
    {
        return Auth::check() ? redirect()->route('member.index') : view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => '請輸入 Email',
            'email.email'       => 'Email 格式不正確',
            'password.required' => '請輸入密碼',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->isLockedOut()) {
            $remaining = now()->diffInMinutes($user->login_locked_until) + 1;
            return back()->withErrors(['email' => "帳號已鎖定，請 {$remaining} 分鐘後再試"])->withInput();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $user->update(['login_failed_count' => 0, 'login_locked_until' => null]);

            if ($user->isAdmin() || $user->isGroomer()) {
                $code = $this->otpService->generate($user, 'login');
                $this->otpService->sendByMail($user, $code, 'login');
                return redirect()->route('2fa.verify');
            }

            return redirect()->intended(route('member.index'));
        }

        if ($user) {
            $failedCount = $user->login_failed_count + 1;
            $updates = ['login_failed_count' => $failedCount];
            if ($failedCount >= self::MAX_ATTEMPTS) {
                $updates['login_locked_until'] = now()->addMinutes(self::LOCKOUT_MINUTES);
                $updates['login_failed_count'] = 0;
            }
            $user->update($updates);
        }

        return back()->withErrors(['email' => 'Email 或密碼不正確'])->withInput();
    }

    public function showRegister()
    {
        return Auth::check() ? redirect()->route('member.index') : view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:50',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => '請輸入姓名',
            'email.required'     => '請輸入 Email',
            'email.email'        => 'Email 格式不正確',
            'email.unique'       => '此 Email 已被使用',
            'password.required'  => '請輸入密碼',
            'password.min'       => '密碼至少 8 個字元',
            'password.confirmed' => '兩次密碼不一致',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $code = $this->otpService->generate($user, 'register');
        $this->otpService->sendByMail($user, $code, 'register');

        return redirect()->route('email.verify');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
