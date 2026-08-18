<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\Admin;
use Carbon\Carbon;

class TimetableController extends Controller
{
    public function schedule(Request $request)
    {
        $admin = Admin::find(session('admin_id'));
        if (!$admin) return redirect()->route('admin.login');
        
        $branch = $admin->branch;
        
        // 1. Fetch unique semesters that already have a timetable in this branch
        $savedSemesters = \App\Models\Timetable::where('branch', $branch)
            ->select('semester')
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester');

        // 2. Fetch existing data if a semester is selected
        $selectedSemester = $request->semester;
        $existingData = [];
        if ($selectedSemester) {
            $existingData = \App\Models\Timetable::where('branch', $branch)
                ->where('semester', $selectedSemester)
                ->get()
                ->groupBy('day');
        }

        // 3. Fetch only approved teachers belonging to the HOD's branch
        $teachers = \App\Models\User::where('role', 'teacher')
            ->where('status', 'approved')
            ->whereHas('teacherProfile', function($query) use ($branch) {
                $query->where('branch', $branch);
            })
            ->get()
            ->map(function($t) {
                $profile = \App\Models\Teacher::where('user_id', $t->id)->first();
                return $profile->display_name ?? $t->username;
            });

        return view('admin.timetable.setup', compact('branch', 'teachers', 'savedSemesters', 'existingData', 'selectedSemester'));
    }

    public function getSubjects(Request $request)
    {
        $admin = Admin::find(session('admin_id'));
        $branch = $admin->branch;
        $semester = $request->semester;

        $subjects = \App\Models\Subject::where('branch', $branch)
            ->where('semester', $semester)
            ->get(['name', 'code']);

        return response()->json($subjects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester' => 'required',
            'slots' => 'required|array',
            'allocation' => 'required|array'
        ]);

        $admin = Admin::find(session('admin_id'));
        $branch = $admin->branch;
        $semester = $request->semester;
        $allocations = $request->allocation; // Mapping: Subject Name => Teacher Name

        // Fetch subjects for this branch/semester to get codes efficiently
        $subjects = \App\Models\Subject::where('branch', $branch)
            ->where('semester', $semester)
            ->get()
            ->keyBy('name');

        // 🛑 PREVENT OVERLAP: Delete existing timetable for this specific branch & semester
        // before deploying the new one. This ensures no duplicate entries for the same day.
        \App\Models\Timetable::where('branch', $branch)
            ->where('semester', $semester)
            ->delete();

        foreach ($request->slots as $day => $daySlots) {
            foreach ($daySlots as $slot) {
                $subjectName = $slot['subject_name'];
                if (empty($subjectName) || empty($slot['start_time'])) continue;

                $teacherName = $allocations[$subjectName] ?? 'Pending';
                $subjectCode = isset($subjects[$subjectName]) ? $subjects[$subjectName]->code : null;

                // Create fresh record
                \App\Models\Timetable::create([
                    'branch' => $branch,
                    'semester' => $semester,
                    'day' => $day,
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'subject_name' => $subjectName,
                    'subject_code' => $subjectCode,
                    'teacher_name' => $teacherName,
                    'room_no' => $slot['room_no'],
                ]);
            }
        }

        return back()->with('success', '🚀 Timetable for Semester ' . $semester . ' has been deployed successfully!');
    }

    public function print($semester)
    {
        $admin = Admin::find(session('admin_id'));
        $branch = $admin->branch;
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $timetables = Timetable::where('branch', $branch)
            ->where('semester', $semester)
            ->get()
            ->groupBy('day');

        return view('hod.timetable.print', compact('timetables', 'branch', 'semester', 'days'));
    }
}
