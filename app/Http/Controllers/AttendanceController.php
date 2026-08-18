<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject; // Subject model use karna zaroori hai
use App\Models\User;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    // Smart Attendance Dashboard
    public function index()
    {
        $teacherProfile = auth()->user()->teacherProfile;
        return view('teacher.attendance_dashboard', compact('teacherProfile'));
    }

        // Attendance Page dikhane ke liye
        public function create($semester)
    {
        $teacherProfile = auth()->user()->teacherProfile; 
        $teacherBranch = $teacherProfile->branch;

        // 1. Sirf wahi Subjects jo teacher ki branch aur selected semester ke hon
        $subjects = \App\Models\Subject::where('semester', $semester)
                        ->where('branch', $teacherBranch)
                        ->get();

        // 2. Sirf wahi Students jo teacher ki branch aur selected semester ke hon
        $students = \App\Models\User::where('role', 'student')
                        ->where('status', 'approved')
                        ->whereHas('studentProfile', function($query) use ($semester, $teacherBranch) {
                            $query->where('semester', $semester)
                                  ->where('branch', $teacherBranch);
                        })
                        ->orderBy('username', 'asc')
                        ->get();

        $todayDate = now()->format('Y-m-d'); 

        return view('teacher.take_attendance', compact('students', 'subjects', 'semester', 'todayDate'));
    }
    // Attendance Save karne ke liye (Ye waise hi rahega, bas thoda clean kiya hai)
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id', // Subject ID validate karega
            'attendance_date' => 'required|date',
            'attendance' => 'required|array', 
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            foreach ($request->attendance as $student_id => $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $student_id,
                        'subject_id' => $request->subject_id,
                        'attendance_date' => $request->attendance_date,
                    ],
                    [
                        'teacher_id' => auth()->id(),
                        'status' => $status,
                    ]
                );
            }
        });

        return redirect()->route('attendance.index')->with('success', "Attendance recorded successfully!");
    }
}