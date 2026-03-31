<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('web')->user();

        if ($user->role !== 'student') {
            abort(403, 'Unauthorized');
        }

        if ($user->status !== 'approved') {
            Auth::logout();
            return redirect()->route('login')->withErrors('Your account has not been approved yet.');
        }

        return $next($request);
    }
}
