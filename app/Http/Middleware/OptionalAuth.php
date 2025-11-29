<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuth
{

    public function handle($request, Closure $next)
{
    if ($request->bearerToken()) {
        auth()->shouldUse('sanctum'); // يحاول يعمل تسجيل دخول لو فيه token
    }

    return $next($request);
}

}
