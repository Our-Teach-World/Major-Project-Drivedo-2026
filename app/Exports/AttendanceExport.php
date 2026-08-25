<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $semester;
    protected $month;
    protected $year;
    protected $branch;
    protected $subject;
    protected $attendances;

    public function __construct($semester, $month, $branch, $subjectId)
    {
        $this->semester = $semester;
        $this->month = $month;
        $this->year = date('Y');
        $this->branch = $branch;
        $this->subject = \App\Models\Subject::findOrFail($subjectId);

        // Pre-fetch all attendance for this specific subject and month to avoid N+1 queries
        $this->attendances = Attendance::whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $this->year)
            ->where('subject_id', $this->subject->id)
            ->get()
            ->groupBy(['student_id', 'attendance_date']);
    }

    public function collection()
    {
        return User::where('role', 'student')
            ->where('status', 'approved')
            ->with('studentProfile')
            ->whereHas('studentProfile', function ($query) {
                $query->where('semester', $this->semester)->where('branch', $this->branch);
            })
            ->orderBy('username', 'asc')
            ->get();
    }

    public function headings(): array
    {
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;
        $headings = ['Subject', 'Enrollment No', 'Student Name'];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $headings[] = $i;
        }
        return $headings;
    }

    public function map($student): array
    {
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;
        $enrollment = $student->studentProfile->enrollment_no ?? 'N/A';
        $row = [$this->subject->name, $enrollment, $student->name ?? $student->username];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $carbonDate = Carbon::create($this->year, $this->month, $day);
            $date = $carbonDate->format('Y-m-d');
            
            $existing = $this->attendances[$student->id][$date] ?? null;
            
            if ($existing) {
                // Since we filtered by subjectId, there should be only one or we pick the first
                $status = $existing[0]->status;
                $row[] = ($status == 'Present' ? 'P' : 'A');
            } else {
                // Check if it's Sunday
                if ($carbonDate->isSunday()) {
                    $row[] = 'Sunday';
                } else {
                    $row[] = '-';
                }
            }
        }
        
        return $row;
    }
}
