<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        if (! auth()->user()->isActive()) {
            auth()->logout();

            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact support.');
        }

        return $next($request);
    }
}
