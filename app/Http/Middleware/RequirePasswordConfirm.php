<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordConfirm
{
    private const CONFIRM_VALID_SECONDS = 10800;

    public function handle(Request $request, Closure $next): Response
    {
        $confirmedAt = session('password_confirmed_at');

        if (!$confirmedAt || now()->timestamp - $confirmedAt > self::CONFIRM_VALID_SECONDS) {
            session(['url.intended' => $request->url()]);
            return redirect()->route('password.confirm');
        }

        return $next($request);
    }
}
