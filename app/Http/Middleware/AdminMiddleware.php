<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access admin panel.');
        }

        if (! auth()->user()->isAdmin() || ! auth()->user()->isActive()) {
            abort(403, 'Unauthorized admin access.');
        }

        return $next($request);
    }
}
