<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAlumni
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'alumni') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        if (Auth::user()->application_status !== 'approved') {
            return redirect()->route('home')->with('error', 'Your alumni account is pending approval.');
        }

        return $next($request);
    }
}
