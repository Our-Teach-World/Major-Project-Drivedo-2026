<?php
// Run: php artisan tinker < database/seeders/seed_teacher_profiles.php

$teachers = App\Models\Student::where('role', 'teacher')->get();
foreach ($teachers as $t) {
    App\Models\Teacher::firstOrCreate(['student_id' => $t->id]);
}
echo "Created profile rows for " . $teachers->count() . " teacher(s).\n";
