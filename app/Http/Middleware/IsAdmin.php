<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $response = $next($request);

            // Check if the response is a BinaryFileResponse or a FileResponse
            if (method_exists($response, 'headers')) {
                $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
            }

            return $response;
        } else {
            return redirect('/');
        }
    }
}
