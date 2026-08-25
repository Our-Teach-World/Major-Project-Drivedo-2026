<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Upload;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }

    /**
     * Returns approved teachers.
     * Optional ?semester=N filter — only returns teachers whose uploaded files include that semester.
     */
    public function getTeachers(Request $request)
    {
        $semester = $request->get('semester'); // optional filter

        $query = User::where('role', 'teacher')
            ->where('status', 'approved')
            ->with('teacherProfile');

        if (Auth::check() && Auth::user()->role === 'student') {
            $studentBranch = optional(Auth::user()->studentProfile)->branch;
            if ($studentBranch) {
                $query->whereHas('teacherProfile', function ($q) use ($studentBranch) {
                    $q->where('branch', $studentBranch);
                });
            }
        }

        if ($semester) {
            // Only return teachers who have selected this semester as active
            $query->whereHas('teacherProfile', function ($q) use ($semester) {
                $q->where('semester', 'LIKE', '%' . $semester . '%');
            });
        }

        $teachers = $query->get(['id', 'username'])
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'username' => $teacher->username,
                    'display_name' => optional($teacher->teacherProfile)->display_name ?? $teacher->username,
                    'profile_image' => optional($teacher->teacherProfile)->profile_image,
                    'bio' => optional($teacher->teacherProfile)->bio,
                    'branch' => optional($teacher->teacherProfile)->branch,
                ];
            });

        return response()->json($teachers);
    }

    public function getFiles(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:teachers,folders,files',
            'teacher' => 'nullable|string|max:255',
            'folder' => 'nullable|string|max:255',
            'semester' => 'nullable|integer|min:1|max:6',
        ]);

        $action = $validated['action'];
        $teacherName = $validated['teacher'] ?? null;
        $folderName = $validated['folder'] ?? null;
        $semester = isset($validated['semester']) ? (int) $validated['semester'] : null;

        if ($action === 'teachers') {
            $query = User::where('role', 'teacher')
                ->where('status', 'approved')
                ->with('teacherProfile');

            if (Auth::check() && Auth::user()->role === 'student') {
                $studentBranch = optional(Auth::user()->studentProfile)->branch;
                if ($studentBranch) {
                    $query->whereHas('teacherProfile', function ($q) use ($studentBranch) {
                        $q->where('branch', $studentBranch);
                    });
                }
            }

            if ($semester) {
                $query->whereHas('teacherProfile', function ($q) use ($semester) {
                    $q->where('semester', 'LIKE', '%' . $semester . '%');
                });
            }

            $teachers = $query->get(['id', 'username'])
                ->map(function ($t) {
                    return [
                        'username' => $t->username,
                        'display_name' => optional($t->teacherProfile)->display_name ?? $t->username,
                        'profile_image' => optional($t->teacherProfile)->profile_image,
                    ];
                });
            return response()->json($teachers);
        }

        if ($action === 'folders' && $teacherName) {
            $teacher = User::where('username', $teacherName)->where('role', 'teacher')->first();

            if ($teacher) {
                $baseQuery = fn($type) => Upload::where('user_id', $teacher->id)
                    ->where('filepath', 'like', "%{$type}%")
                    ->when($semester, fn($q) => $q->where('semester', $semester));

                $folders = [
                    ['name' => 'documents', 'icon' => '📄', 'count' => $baseQuery('documents')->count()],
                    ['name' => 'images', 'icon' => '🖼️', 'count' => $baseQuery('images')->count()],
                    ['name' => 'audio', 'icon' => '🎵', 'count' => $baseQuery('audio')->count()],
                    ['name' => 'video', 'icon' => '🎬', 'count' => $baseQuery('video')->count()],
                ];
                return response()->json($folders);
            }
        }

        if ($action === 'files' && $teacherName && $folderName) {
            $teacher = User::where('username', $teacherName)->where('role', 'teacher')->first();

            if ($teacher) {
                $files = Upload::where('user_id', $teacher->id)
                    ->where('filepath', 'like', "%{$folderName}%")
                    ->when($semester, fn($q) => $q->where('semester', $semester))
                    ->get(['filename', 'filepath', 'semester']);
                return response()->json($files);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function myAttendance()
    {
        $studentId = auth()->id();

        // Database se directly Total aur Present count fetch karna
        $attendanceStats = \App\Models\Attendance::where('student_id', $studentId)
            ->selectRaw('subject_id, 
                         count(*) as total_classes, 
                         sum(case when status = "Present" then 1 else 0 end) as present_classes')
            ->groupBy('subject_id')
            ->with('subject') // Subject ka naam dikhane ke liye relation
            ->get();

        return view('student.attendance', compact('attendanceStats'));
    }

    public function timetableViewer()
    {
        $profile = auth()->user()->studentProfile;
        if (!$profile)
            return back()->with('error', 'Profile not found.');

        $branch = $profile->branch;
        $semester = $profile->semester;

        $timetables = \App\Models\Timetable::where('branch', $branch)
            ->where('semester', $semester)
            ->get()
            ->groupBy('day');

        return view('student.timetable', compact('timetables', 'branch', 'semester'));
    }
}
