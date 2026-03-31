<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth as UserAuth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'required|in:student,teacher',
        ], [
            'role.required' => 'Please select a role.',
        ]);

        $user = UserAuth::where('username', $request->username)
                        ->where('role', $request->role)
                        ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->status === 'approved') {
                Auth::login($user);
                if ($user->role === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                } elseif ($user->role === 'student') {
                    return redirect()->route('student.dashboard');
                }
            } else {
                return back()->withErrors(['status' => 'Your account is awaiting approval.']);
            }
        }

        return back()->withErrors(['login' => 'Invalid username, password, or role.']);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:auth,username|min:3',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:student,teacher',
        ]);

        UserAuth::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'pending',
        ]);

        return redirect()->route('registration.success');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
