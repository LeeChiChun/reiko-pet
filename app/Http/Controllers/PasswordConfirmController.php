<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordConfirmController extends Controller
{
    public function show()
    {
        return view('auth.confirm-password');
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => '請輸入密碼',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => '密碼不正確']);
        }

        session(['password_confirmed_at' => now()->timestamp]);
        return redirect()->intended(route('admin.dashboard'));
    }
}
