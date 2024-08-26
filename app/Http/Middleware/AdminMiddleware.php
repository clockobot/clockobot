<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // If the user is not authenticated, redirect to the home page or login
        return redirect('/')->with('error', 'You do not have admin access.');
    }
}
