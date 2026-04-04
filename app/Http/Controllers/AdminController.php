<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Student;
use App\Services\UploadService;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_id' => $admin->id, 'admin_username' => $admin->username]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        $totalUsers = Student::count();
        $pendingUsers = Student::where('status', 'pending')->count();
        $approvedUsers = Student::where('status', 'approved')->count();
        $teachers = Student::where('role', 'teacher')->count();
        $students = Student::where('role', 'student')->count();

        return view('admin.dashboard', compact('totalUsers', 'pendingUsers', 'approvedUsers', 'teachers', 'students'));
    }

    public function users(Request $request)
    {
        // AJAX search: detect via X-Requested-With header OR ?ajax=1 query param
        if ($request->ajax() || $request->has('ajax')) {
            $search = $request->get('q', '');
            $users = Student::where(function ($query) use ($search) {
                if ($search) {
                    $query->where('username', 'LIKE', "%$search%")
                        ->orWhere('role', 'LIKE', "%$search%")
                        ->orWhere('status', 'LIKE', "%$search%");
                }
            })->orderBy('id', 'DESC')->get(['id', 'username', 'role', 'status', 'created_at']);

            return response()->json($users);
        }

        $users = Student::orderBy('id', 'DESC')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function approveUser($id)
    {
        $user = Student::findOrFail($id);
        $user->update(['status' => 'approved']);

        // Always return JSON for fetch()/AJAX calls
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User approved successfully.']);
        }

        return back()->with('success', 'User approved successfully.');
    }

    public function deleteUser($id)
    {
        $user = Student::findOrFail($id);
        $user->delete();

        // Always return JSON for fetch()/AJAX calls
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }

        return back()->with('success', 'User deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->get('bulk_action');
        $userIds = $request->get('user_ids', []);

        if ($action === 'approve') {
            Student::whereIn('id', $userIds)->update(['status' => 'approved']);
        } elseif ($action === 'delete') {
            Student::whereIn('id', $userIds)->delete();
        }

        return response()->json(['success' => true]);
    }

    public function addUser(Request $request)
    {
        if ($request->method() === 'POST') {
            $request->validate([
                'username' => 'required|unique:students,username|min:6',
                'password' => 'required|min:4',
                'role' => 'required|in:teacher,student',
            ]);

            Student::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'pending',
            ]);

            return redirect()->route('admin.users')->with('success', 'User created successfully.');
        }

        return view('admin.add-user');
    }

    public function editUser($id, Request $request)
    {
        $user = Student::findOrFail($id);

        if ($request->method() === 'POST') {
            $request->validate([
                'username' => 'required|min:6|unique:students,username,' . $id,
                'status' => 'required|in:pending,approved',
                'role' => 'required|in:teacher,student',
                'password' => 'nullable|min:4',
                'profileImage' => 'nullable|image|max:5000',
            ]);

            $data = [
                'username' => $request->username,
                'status' => $request->status,
                'role' => $request->role,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Handle profile image upload
            if ($request->hasFile('profileImage')) {
                UploadService::saveProfileImage($request->file('profileImage'), $user->username);
            }

            return redirect()->route('admin.users')->with('success', 'User updated successfully.');
        }

        return view('admin.edit-user', compact('user'));
    }

    public function export()
    {
        $users = Student::all();
        $csv = "Id,Name,Role,Password,Reg Date,Status\n";
        
        foreach ($users as $user) {
            $csv .= "{$user->id},{$user->username},{$user->role}," . str_repeat('*', 8) . ",{$user->created_at->format('d-m-Y H:i A')},{$user->status}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users.csv"');
    }

    public function logout()
    {
        session()->forget(['admin_id', 'admin_username']);
        return redirect()->route('admin.login');
    }
}
