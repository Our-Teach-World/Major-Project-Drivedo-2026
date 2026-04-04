<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Teacher;

class SeedTeacherProfiles extends Command
{
    protected $signature   = 'teachers:seed-profiles';
    protected $description = 'Create empty teachers table rows for all existing teacher accounts';

    public function handle()
    {
        $teachers = Student::where('role', 'teacher')->get();

        $created = 0;
        foreach ($teachers as $teacher) {
            $profile = Teacher::firstOrCreate(['student_id' => $teacher->id]);
            if ($profile->wasRecentlyCreated) {
                $created++;
                $this->line("  Created profile for: {$teacher->username}");
            } else {
                $this->line("  Already exists for: {$teacher->username}");
            }
        }

        $this->info("Done. {$created} new teacher profile row(s) created.");
    }
}
