<?php

namespace App\Http\Middleware;

use Closure;

class ParseJsonRequest
{
    public function handle($request, Closure $next)
    {
        if ($request->isJson()) {
            $request->merge(json_decode($request->getContent(), true));
        }

        return $next($request);
    }
}
