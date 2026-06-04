<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    private const TIMEOUT_MINUTES = 30;

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $lastActivity = session('last_activity_at');

        if ($lastActivity && now()->diffInMinutes($lastActivity) >= self::TIMEOUT_MINUTES) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['session' => '閒置超過 30 分鐘，請重新登入']);
        }

        session(['last_activity_at' => now()]);
        return $next($request);
    }
}
