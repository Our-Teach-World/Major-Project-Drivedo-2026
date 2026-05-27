<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
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
            session([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'admin_role' => $admin->role ?? 'hod'
            ]);

            if ($admin->role === 'principal') {
                return redirect()->route('principal.dashboard');
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        // Dynamically ensure admin columns exist
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('admin', 'name')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admin` ADD COLUMN `name` VARCHAR(255) NULL AFTER `username`;");
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('admin', 'image_path')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admin` ADD COLUMN `image_path` VARCHAR(255) NULL AFTER `name`;");
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed dynamically adding admin columns in dashboard: " . $e->getMessage());
        }

        $admin = Admin::find(session('admin_id'));
        $query = User::whereIn('role', ['teacher', 'student', 'alumni']);

        if ($admin && $admin->branch && !in_array($admin->role, ['principal', 'admin'])) {
            $query->where(function ($q) use ($admin) {
                $q->where(function ($sq) use ($admin) {
                    $sq->where('role', 'student')
                        ->whereHas('studentProfile', function ($sp) use ($admin) {
                            $sp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($tq) use ($admin) {
                    $tq->where('role', 'teacher')
                        ->whereHas('teacherProfile', function ($tp) use ($admin) {
                            $tp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($aq) use ($admin) {
                    $aq->where('role', 'alumni')
                        ->where('branch', $admin->branch);
                });
            });
        }

        $baseQuery = clone $query;
        $totalUsers = (clone $baseQuery)->count();
        $pendingUsers = (clone $baseQuery)->whereIn('status', ['pending', ''])->count();
        $approvedUsers = (clone $baseQuery)->where('status', 'approved')->count();
        $teachers = (clone $baseQuery)->where('role', 'teacher')->count();
        $students = (clone $baseQuery)->where('role', 'student')->count();
        $alumni = (clone $baseQuery)->where('role', 'alumni')->count();

        return view('admin.dashboard', compact('totalUsers', 'pendingUsers', 'approvedUsers', 'teachers', 'students', 'alumni'));
    }

    public function profile()
    {
        // Dynamically ensure admin columns exist
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('admin', 'name')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admin` ADD COLUMN `name` VARCHAR(255) NULL AFTER `username`;");
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('admin', 'image_path')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admin` ADD COLUMN `image_path` VARCHAR(255) NULL AFTER `name`;");
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed dynamically adding admin columns in profile: " . $e->getMessage());
        }

        $admin = Admin::find(session('admin_id'));
        if (!$admin) {
            return redirect()->route('admin.login')->withErrors(['login' => 'Please login first.']);
        }
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        // Dynamically ensure admin columns exist
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('admin', 'name')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admin` ADD COLUMN `name` VARCHAR(255) NULL AFTER `username`;");
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('admin', 'image_path')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admin` ADD COLUMN `image_path` VARCHAR(255) NULL AFTER `name`;");
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed dynamically adding admin columns in updateProfile: " . $e->getMessage());
        }

        $admin = Admin::find(session('admin_id'));
        if (!$admin) {
            return redirect()->route('admin.login')->withErrors(['login' => 'Please login first.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin->name = $request->name;

        if ($request->hasFile('image')) {
            if ($admin->image_path && file_exists(public_path($admin->image_path))) {
                @unlink(public_path($admin->image_path));
            }

            $file = $request->file('image');
            $filename = 'admin_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/admin_profiles'), $filename);
            $admin->image_path = 'uploads/admin_profiles/' . $filename;
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function certchainHub()
    {
        $admin = Admin::find(session('admin_id'));
        if (!$admin) {
            return redirect()->route('admin.login')->withErrors(['login' => 'Please login first.']);
        }
        return view('admin.certchain.hub', compact('admin'));
    }

    public function users(Request $request)
    {
        $admin = Admin::find(session('admin_id'));
        $query = User::whereIn('role', ['teacher', 'student', 'alumni']);

        if ($admin && $admin->branch && !in_array($admin->role, ['principal', 'admin'])) {
            // Only filter by branch when admin has a branch assigned.
            // Use role-aware whereHas so users without profile rows are NOT hidden
            // from admins who have no branch restriction.
            $query->where(function ($q) use ($admin) {
                $q->where(function ($sq) use ($admin) {
                    $sq->where('role', 'student')
                        ->whereHas('studentProfile', function ($sp) use ($admin) {
                            $sp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($tq) use ($admin) {
                    $tq->where('role', 'teacher')
                        ->whereHas('teacherProfile', function ($tp) use ($admin) {
                            $tp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($aq) use ($admin) {
                    $aq->where('role', 'alumni')
                        ->where('branch', $admin->branch);
                });
            });
        }

        // AJAX search: detect via X-Requested-With header OR ?ajax=1 query param
        if ($request->ajax() || $request->has('ajax')) {
            $search = $request->get('q', '');
            $ajaxQuery = clone $query;
            if ($search) {
                $ajaxQuery->where(function ($q) use ($search) {
                    $q->where('username', 'LIKE', "%$search%")
                        ->orWhere('role', 'LIKE', "%$search%")
                        ->orWhere('status', 'LIKE', "%$search%");
                });
            }

            $users = $ajaxQuery->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->select('users.*', 'students.enrollment_no')
                ->orderBy('users.id', 'DESC')
                ->get();

            return response()->json($users);
        }

        $users = $query->orderBy('id', 'DESC')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $updateData = ['status' => 'approved'];
        
        if ($user->role === 'alumni') {
            $updateData['application_status'] = 'approved';
        }
        
        $user->update($updateData);

        // Always return JSON for fetch()/AJAX calls
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User approved successfully.']);
        }

        return back()->with('success', 'User approved successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        // Cascading deletion handled via DB constraints or manual deletion here depending on setup.
        // If no constraints, we can explicitly delete profiles.
        if ($user->role === 'student' && $user->studentProfile) {
            $user->studentProfile->delete();
        } elseif ($user->role === 'teacher' && $user->teacherProfile) {
            $user->teacherProfile->delete();
        }
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
            User::whereIn('id', $userIds)->update(['status' => 'approved']);
            User::whereIn('id', $userIds)->where('role', 'alumni')->update(['application_status' => 'approved']);
        } elseif ($action === 'delete') {
            // Need to clean up profiles manually if no cascade
            Student::whereIn('user_id', $userIds)->delete();
            Teacher::whereIn('user_id', $userIds)->delete();
            User::whereIn('id', $userIds)->delete();
        }

        return response()->json(['success' => true]);
    }

    public function addUser(Request $request)
    {
        if ($request->method() === 'POST') {
            $request->validate([
                'username' => 'required|unique:users,username|min:6',
                'password' => 'required|min:4',
                'role' => 'required|in:teacher,student,alumni',
                'branch' => 'required',
                'enrollment_no' => 'required_if:role,student|nullable|string|unique:students,enrollment_no',
            ]);

            $userData = [
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'pending',
                'branch' => $request->branch,
            ];

            if ($request->role === 'alumni') {
                $userData['alumni_details'] = $request->alumni_details;
                $userData['company'] = $request->company;
                $userData['bio'] = $request->bio;
            }

            $user = User::create($userData);

            $admin = Admin::find(session('admin_id'));
            $branch = $request->branch ?: (($admin && !in_array($admin->role, ['principal', 'admin'])) ? $admin->branch : null);

            if ($request->role === 'student') {
                Student::create([
                    'user_id' => $user->id,
                    'branch' => $branch,
                    'enrollment_no' => $request->enrollment_no
                ]);
            } elseif ($request->role === 'teacher') {
                Teacher::create([
                    'user_id' => $user->id,
                    'branch' => $branch
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User created successfully.');
        }

        $branches = \App\Models\Admin::where('role', 'hod')
            ->distinct()
            ->pluck('branch')
            ->merge(\App\Models\Student::distinct()->pluck('branch'))
            ->unique()
            ->values();
        return view('admin.add-user', compact('branches'));
    }

    public function editUser($id, Request $request)
    {
        $user = User::findOrFail($id);

        if ($request->method() === 'POST') {
            $request->validate([
                'username' => 'required|min:6|unique:users,username,' . $id,
                'status' => 'required|in:pending,approved',
                'role' => 'required|in:teacher,student,alumni',
                'password' => 'nullable|min:4',
                'profileImage' => 'nullable|image|max:5000',
                'enrollment_no' => 'required_if:role,student|nullable|string|unique:students,enrollment_no,' . ($user->studentProfile->id ?? 'NULL'),
            ]);

            $data = [
                'username' => $request->username,
                'status' => $request->status,
                'role' => $request->role,
            ];

            if ($request->role === 'alumni' && $request->status === 'approved') {
                $data['application_status'] = 'approved';
            }

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Handle profile image upload
            if ($request->hasFile('profileImage')) {
                UploadService::saveProfileImage($request->file('profileImage'), $user->username);
            }

            if ($user->role === 'student' && $user->studentProfile) {
                $user->studentProfile->update([
                    'enrollment_no' => $request->enrollment_no
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User updated successfully.');
        }

        return view('admin.edit-user', compact('user'));
    }

    public function export()
    {
        $admin = Admin::find(session('admin_id'));
        $query = User::whereIn('role', ['teacher', 'student', 'alumni']);

        if ($admin && $admin->branch && !in_array($admin->role, ['principal', 'admin'])) {
            $query->where(function ($q) use ($admin) {
                $q->whereHas('studentProfile', function ($sq) use ($admin) {
                    $sq->where('branch', $admin->branch);
                })->orWhereHas('teacherProfile', function ($tq) use ($admin) {
                    $tq->where('branch', $admin->branch);
                });
            });
        }

        $users = $query->get();
        $csv = "Id,Name,Role,Password,Reg Date,Status\n";

        foreach ($users as $user) {
            $csv .= "{$user->id},{$user->username},{$user->role}," . str_repeat('*', 8) . ",{$user->created_at->format('d-m-Y H:i A')},{$user->status}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users.csv"');
    }

    public function subjects()
    {
        $admin = Admin::find(session('admin_id'));
        $subjects = collect();

        if ($admin && $admin->branch && !in_array($admin->role, ['principal', 'admin'])) {
            $subjects = Subject::where('branch', $admin->branch)
                ->orderBy('semester')
                ->orderBy('name')
                ->get()
                ->groupBy('semester');
        }

        return view('admin.subjects', compact('subjects'));
    }

    public function bulkStoreSubject(Request $request)
    {
        $admin = Admin::find(session('admin_id'));

        if (!$admin || !$admin->branch) {
            return back()->withErrors(['error' => 'You must have a branch assigned to create subjects.']);
        }

        $request->validate([
            'semester' => 'required|integer|min:1|max:6',
            'subjects' => 'required|array',
            'subjects.*.name' => 'required|string|max:255',
            'subjects.*.code' => 'required|string|max:50',
        ]);

        $count = 0;
        foreach ($request->subjects as $subj) {
            Subject::create([
                'name' => $subj['name'],
                'code' => $subj['code'],
                'semester' => $request->semester,
                'branch' => $admin->branch,
            ]);
            $count++;
        }

        return back()->with('success', "$count subjects added successfully.");
    }

    public function destroySubject($id)
    {
        $subject = Subject::findOrFail($id);

        $admin = Admin::find(session('admin_id'));
        if ($admin && $admin->branch === $subject->branch) {
            $subject->delete();
            return back()->with('success', 'Subject deleted successfully.');
        }

        return back()->withErrors(['error' => 'Unauthorized to delete this subject.']);
    }

    public function logout()
    {
        session()->forget(['admin_id', 'admin_username']);
        return redirect()->route('admin.login');
    }
}
