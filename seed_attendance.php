<?php
for ($i = 0; $i < 10; $i++) {
    $status = ($i % 4 == 0) ? 'Absent' : 'Present'; // Mixed attendance
    App\Models\Attendance::updateOrCreate(
        [
            'student_id' => 5,
            'subject_id' => 2,
            'attendance_date' => now()->subDays(10 - $i)->format('Y-m-d'),
        ],
        [
            'teacher_id' => 1,
            'status' => $status,
        ]
    );
}
echo "Attendance records created!\n";
