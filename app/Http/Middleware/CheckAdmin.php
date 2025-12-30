<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/')->with('error', 'You must be logged in.');
        }

        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Unauthorized access. Admin only.');
        }

        return $next($request);
    }
}