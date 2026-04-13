<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NoticeController extends Controller
{
    // 1. Notice create karne ka form dikhana
    public function create()
    {
        $adminId = session('admin_id');
        $admin = $adminId ? \App\Models\Admin::find($adminId) : null;
        $user = auth()->user();

        // If the URL starts with /teacher/, force teacher UI
        if (request()->is('teacher/*')) {
            $isTeacher = $user && $user->role === 'teacher';
            if (!$isTeacher) return redirect()->route('login')->with('error', 'Unauthorized.');
            
            $teacherProfile = \App\Models\Teacher::where('user_id', $user->id)->first();
            $semesters = json_decode($teacherProfile->semester ?? '[]', true) ?? [];
            return view('teacher.create_notice', compact('user', 'teacherProfile', 'semesters'));
        }

        // Handle Principal & HOD UI (admin prefix or principal prefix)
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized.');
        }

        $branches = [];
        if ($admin->role === 'principal') {
            // Principal can see all branches
            $branches = \App\Models\Admin::where('role', 'hod')->distinct()->pluck('branch')
                ->merge(\App\Models\Student::distinct()->pluck('branch'))
                ->merge(\App\Models\Teacher::distinct()->pluck('branch'))
                ->filter()
                ->unique()
                ->toArray();
        }

        return view('admin.create_notice', compact('admin', 'branches'));
    }

    // 2. Notice ko DB me save karna aur Notification bhejna
    public function store(Request $request)
    {
        // A. Form Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // Max 5MB
        ]);

        // B. File Upload Logic (agar koi PDF attach ki hai)
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('notices', 'public');
        }

        // C. Determine Creator strictly based on the route being used
        // This prevents attribution errors if a Principal is also logged into a Teacher account.
        $creatorId = null;
        $creatorType = null;
        $targetBranch = $request->target_branch;

        if (request()->is('teacher/*')) {
            // Must be a teacher
            $user = auth()->user();
            if (!$user || $user->role !== 'teacher') {
                return redirect()->back()->with('error', 'Unauthorized: You must be logged in as a teacher.');
            }
            $teacherProfile = \App\Models\Teacher::where('user_id', $user->id)->first();
            $targetBranch = $teacherProfile->branch ?? 'Unknown';
            $creatorId = $user->id;
            $creatorType = \App\Models\User::class;
        } else {
            // Must be an admin/principal (admin prefix or principal prefix)
            $adminId = session('admin_id');
            $admin = $adminId ? \App\Models\Admin::find($adminId) : null;
            if (!$admin) {
                return redirect()->back()->with('error', 'Unauthorized: Admin session not found.');
            }
            if ($admin->role !== 'principal') {
                $targetBranch = $admin->branch; // HODs are locked to their own branch
            }
            $creatorId = $admin->id;
            $creatorType = \App\Models\Admin::class;
        }

        $notice = Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'attachment_path' => $attachmentPath,
            'target_branch' => $targetBranch, 
            'target_semester' => $request->target_semester,
            'target_role' => $request->target_role ?? 'student',
            'created_by' => $creatorId,
            'creator_type' => $creatorType,
        ]);

        // D. OneSignal API with Targeted Filters
        try {
            $filters = [];
            
            // 1. Role Filter
            if ($notice->target_role && $notice->target_role !== 'all') {
                $filters[] = ['field' => 'tag', 'key' => 'role', 'relation' => '=', 'value' => $notice->target_role];
            }

            // 2. Branch Filter
            if ($notice->target_branch) {
                if (!empty($filters)) $filters[] = ['operator' => 'AND'];
                $filters[] = ['field' => 'tag', 'key' => 'branch', 'relation' => '=', 'value' => $notice->target_branch];
            }

            // 3. Semester Filter
            if ($notice->target_semester) {
                if (!empty($filters)) $filters[] = ['operator' => 'AND'];
                $filters[] = ['field' => 'tag', 'key' => 'semester', 'relation' => '=', 'value' => (string)$notice->target_semester];
            }

            $notifData = [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'headings' => ['en' => '📢 ' . $request->title],
                'contents' => ['en' => substr($request->content, 0, 100) . '...'],
                'url' => $notice->target_role === 'student' ? url('/student/dashboard?section=notices') : url('/dashboard'),
            ];

            if (empty($filters)) {
                $notifData['included_segments'] = ['Total Subscriptions'];
            } else {
                $notifData['filters'] = $filters;
            }

            Http::withHeaders([
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', $notifData);
        } catch (\Exception $e) {
            \Log::error('OneSignal Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Notice published and notifications sent successfully!');
    }

    // 3. Student ke liye notices fetch karna
    public function studentIndex()
    {
        $user = auth()->user();
        $profile = optional($user->studentProfile);
        
        $branch = $profile->branch;
        $semester = $profile->semester;

        $notices = Notice::where('target_role', 'student')
            ->where(function($mainQuery) use ($branch, $semester) {
                $mainQuery->where(function($q) {
                    $q->whereNull('target_branch')->whereNull('target_semester');
                })->orWhere(function($q) use ($branch) {
                    if ($branch) {
                        $q->where('target_branch', $branch)->whereNull('target_semester');
                    }
                })->orWhere(function($q) use ($branch, $semester) {
                    if ($branch && $semester) {
                        $q->where('target_branch', $branch)->where('target_semester', $semester);
                    }
                })->orWhere(function($q) use ($semester) {
                    if ($semester) {
                        $q->whereNull('target_branch')->where('target_semester', $semester);
                    }
                });
            })
            ->with(['creator' => function($morphTo) {
                $morphTo->morphWith([
                    \App\Models\User::class => ['teacherProfile'],
                    \App\Models\Admin::class => [],
                ]);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notices);
    }

    // 4. Faculty ke liye notices fetch karna
    public function facultyIndex()
    {
        $adminId = session('admin_id');
        $admin = $adminId ? \App\Models\Admin::find($adminId) : null;
        $user = auth()->user();
        $isTeacher = $user && $user->role === 'teacher';

        $role = $isTeacher ? 'teacher' : ($admin ? $admin->role : null);
        $currBranch = $isTeacher ? optional(\App\Models\Teacher::where('user_id', $user->id)->first())->branch : ($admin ? $admin->branch : null);

        if (!$role) return response()->json([]);

        $notices = \App\Models\Notice::whereIn('target_role', [$role, 'all'])
            ->where(function($query) use ($currBranch, $role) {
                if ($role !== 'principal') {
                    $query->where(function($q) use ($currBranch) {
                        $q->whereNull('target_branch')
                          ->orWhere('target_branch', $currBranch);
                    });
                }
            })
            ->with(['creator' => function($morphTo) {
                $morphTo->morphWith([
                    \App\Models\User::class => ['teacherProfile'],
                    \App\Models\Admin::class => [],
                ]);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notices);
    }

    // 5. Teacher Oriented Board View
    public function teacherBoard()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'teacher') return redirect()->route('login');
        
        $role = 'teacher';
        $teacherProfile = \App\Models\Teacher::where('user_id', $user->id)->first();
        return view('teacher.notices_board', compact('role', 'user', 'teacherProfile'));
    }

    // 6. Admin Oriented Board View
    public function adminBoard()
    {
        $adminId = session('admin_id');
        $admin = $adminId ? \App\Models\Admin::find($adminId) : null;
        if (!$admin) return redirect()->route('admin.login');

        $role = $admin->role;
        return view('admin.notices_board', compact('role', 'admin'));
    }
}
