<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HelloMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (preg_match('/balrog$/i', $request->getRequestUri())) {
            return response('You Shall Not Pass!'. $request->getRequestUri(), 403);
        }
        return $next($request);
    }
}