<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has admin role
        // You can implement this based on your user role system
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}