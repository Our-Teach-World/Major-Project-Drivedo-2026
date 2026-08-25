<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        // Check if account is still active
        $admin = \App\Models\Admin::find(session('admin_id'));
        if (!$admin || $admin->status !== 'active') {
            session()->flush();
            return redirect()->route('admin.login')->with('error', 'Your account has been disabled. Please contact the Principal.');
        }

        return $next($request);
    }
}
