<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class ApiSanctumAuth
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('log from ApiSanctumAuth');
        Log::info($request);
        if (!Auth::guard('api')->check()) {
            return response()->json(['message' => 'Unauthorized Token Expired'], 401);
        }

        return $next($request);
    }
}
