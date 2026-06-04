<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGroomer
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(
            !$request->user() || !in_array($request->user()->role->value, ['groomer', 'admin']),
            403,
            '無美容師權限'
        );

        return $next($request);
    }
}
