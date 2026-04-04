<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

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

        $user = Student::where('username', $request->username)
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
        $branches = [
            'Civil Engineering',
            'Mechanical Engineering',
            'Electrical Engineering',
            'Electronics Engineering (EL)',
            'Computer Engineering/Science & Engineering',
            'Instrumentation & Control Plastic Technology',
            'Chemical Engineering',
        ];

        $validated = $request->validate([
            'username' => 'required|unique:students,username|min:3',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:student,teacher',
            'branch'   => 'required_if:role,student|nullable|in:' . implode(',', $branches),
            'semester' => 'required_if:role,student|nullable|integer|min:1|max:6',
        ], [
            'branch.required_if'   => 'Please select your branch.',
            'semester.required_if' => 'Please select your semester.',
        ]);

        $data = [
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'pending',
        ];

        if ($request->role === 'student') {
            $data['branch']   = $request->branch;
            $data['semester'] = $request->semester;
        }

        Student::create($data);

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
