<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = env('VITE_API_KEY');

        // If no VITE_API_KEY is configured in .env, allow all requests without requiring it
        if (blank($apiKey)) {
            return $next($request);
        }

        if ($request->hasHeader('x-api-key') && $request->header('x-api-key') == $apiKey) {
            return $next($request);
        }

        return response()->json(trans('all.message.invalid_api_key'), 400);
    }
}
