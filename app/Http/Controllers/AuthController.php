<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;

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
            'role' => 'required|in:student,teacher,alumni',
        ], [
            'role.required' => 'Please select a role.',
        ]);

        $user = User::where('username', $request->username)
            ->where('role', $request->role)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // For Alumni, check application_status instead of status (or both)
            // But main project uses 'status' for students/teachers.
            // Alumni uses 'application_status' for the mentorship feature approval.
            // Let's check 'status' as the primary account active flag.
            if ($user->status === 'approved') {
                Auth::login($user);
                if ($user->role === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                } elseif ($user->role === 'student') {
                    return redirect()->route('student.dashboard');
                } elseif ($user->role === 'alumni') {
                    return redirect()->route('alumni.dashboard');
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
            'Computer Science & Engineering',
            'Instrumentation & Control Plastic Technology',
            'Chemical Engineering',
        ];

        $validated = $request->validate([
            'username' => 'required|unique:users,username|min:3',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:student,teacher,alumni',
            'branch' => 'required|in:' . implode(',', $branches),
            'semester' => 'required_if:role,student|nullable|integer|min:1|max:6',
            'enrollment_no' => 'required_if:role,student|nullable|string|unique:students,enrollment_no',
            'company' => 'required_if:role,alumni|nullable|string|max:255',
            'bio' => 'required_if:role,alumni|nullable|string|max:1000',
        ], [
            'branch.required' => 'Please select your branch.',
            'semester.required_if' => 'Please select your semester.',
            'enrollment_no.required_if' => 'Please enter your enrollment number.',
            'enrollment_no.unique' => 'This enrollment number is already registered.',
            'company.required_if' => 'Please enter your company/organization.',
            'bio.required_if' => 'Please enter a short professional bio.',
        ]);

        $userData = [
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'pending', // Accounts need admin approval
        ];

        if ($request->role === 'alumni') {
            $userData['company'] = $request->company;
            $userData['bio'] = $request->bio;
            $userData['branch'] = $request->branch;
            $userData['application_status'] = 'pending'; // Feature-specific status
        }

        $user = User::create($userData);

        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'enrollment_no' => $request->enrollment_no,
                'branch' => $request->branch,
                'semester' => $request->semester,
            ]);
        } elseif ($request->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'branch' => $request->branch,
            ]);
        }

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
