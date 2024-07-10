<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->active) {
            auth()->logout();
            return redirect()->json(
                ["message" => "Unauthorized: User is inactive", "status_code" => "401", "status_text" => "user_inactive"],
                401
            );
        }
        return $next($request);
    }
}
