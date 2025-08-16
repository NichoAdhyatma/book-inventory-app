<?php

namespace App\Http\Livewire\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to access this page.');
            return redirect()->route('login');
        }

        return $next($request);
    }
}