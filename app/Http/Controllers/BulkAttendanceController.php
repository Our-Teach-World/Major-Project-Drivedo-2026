<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class BulkAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacherProfile = auth()->user()->teacherProfile;
        if (!$teacherProfile) {
            return redirect()->route('teacher.dashboard')->with('error', 'Please complete your profile first.');
        }

        $branch = $teacherProfile->branch;
        $activeSems = json_decode($teacherProfile->semester ?? '[]', true) ?? [];

        // Specific Semester passed from Dashboard
        $selectedSem = $request->get('semester', !empty($activeSems) ? $activeSems[0] : null);
        
        // Fetch all subjects for this branch and semester (same as daily attendance)
        $subjects = Subject::where('semester', $selectedSem)
            ->where('branch', $branch)
            ->get();
            
        $selectedSubject = $request->get('subject_id', $subjects->first()->id ?? null);

        // Dynamic Week Calculation: Use selected date or default to today
        $selectedDate = $request->get('date', Carbon::now()->format('Y-m-d'));
        $startOfWeek = Carbon::parse($selectedDate)->startOfWeek(Carbon::MONDAY);
        
        $dates = [];
        for ($i = 0; $i < 6; $i++) {
            $dates[] = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
        }

        $students = User::where('role', 'student')
            ->where('status', 'approved')
            ->whereHas('studentProfile', function ($query) use ($selectedSem, $branch) {
                $query->where('semester', $selectedSem)->where('branch', $branch);
            })
            ->orderBy('username', 'asc')
            ->get();

        // Fetch existing attendance for this week/subject to pre-fill checkboxes
        $attendanceData = Attendance::where('subject_id', $selectedSubject)
            ->whereIn('attendance_date', $dates)
            ->get()
            ->groupBy(['student_id', 'attendance_date']);

        return view('teacher.bulk', compact('students', 'dates', 'selectedSem', 'selectedSubject', 'subjects', 'attendanceData', 'activeSems', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'attendance' => 'required|array',
        ]);

        $subjectId = $request->subject_id;
        $teacherId = auth()->id();

        foreach ($request->attendance as $studentId => $dates) {
            foreach ($dates as $date => $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'attendance_date' => $date,
                    ],
                    [
                        'teacher_id' => $teacherId,
                        'status' => $status == '1' ? 'Present' : 'Absent',
                    ]
                );
            }
        }

        // Notify Students about Attendance Update
        try {
            $studentIds = array_keys($request->attendance);
            $students = User::whereIn('id', $studentIds)->get();
            $subject = Subject::find($subjectId);
            
            $notificationTitle = "Attendance Updated";
            $notificationMsg = "Your attendance for the week in {$subject->name} has been updated by " . auth()->user()->username;
            $actionUrl = route('student.attendance');

            \Illuminate\Support\Facades\Notification::send($students, new \App\Notifications\SystemAlert($notificationTitle, $notificationMsg, '📅', $actionUrl));
        } catch (\Exception $e) {
            \Log::error('Bulk Attendance Notification Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Weekly attendance records updated successfully!');
    }

    public function exportView()
    {
        $teacherProfile = auth()->user()->teacherProfile;
        $branch = $teacherProfile->branch;
        $activeSems = json_decode($teacherProfile->semester ?? '[]', true) ?? [];
        
        // Fetch subjects for each active semester to use in JS filtering
        $subjectsBySem = Subject::where('branch', $branch)
            ->whereIn('semester', $activeSems)
            ->get()
            ->groupBy('semester');

        return view('teacher.attendance_export', compact('activeSems', 'subjectsBySem'));
    }

    public function exportMonthlyReport($semester, $month, $subjectId)
    {
        $teacherProfile = auth()->user()->teacherProfile;
        $branch = $teacherProfile->branch;
        
        $year = date('Y');
        $subject = Subject::findOrFail($subjectId);
        $monthName = \Carbon\Carbon::create($year, $month)->format('F');
        $fileName = "Attendance_{$subject->code}_Sem{$semester}_{$monthName}_{$year}.csv";

        return Excel::download(new \App\Exports\AttendanceExport($semester, $month, $branch, $subjectId), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
}
