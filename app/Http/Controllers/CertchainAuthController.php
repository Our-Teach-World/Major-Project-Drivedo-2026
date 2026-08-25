<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertchainAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.certchain_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('faculty.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
