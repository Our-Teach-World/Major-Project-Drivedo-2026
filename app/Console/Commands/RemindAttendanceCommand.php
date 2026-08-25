<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemAlert;

class RemindAttendanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remind-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind teachers to mark attendance for the week if they haven\'t done so.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');

        // Find all teachers
        $teachers = User::where('role', 'teacher')->get();

        foreach ($teachers as $teacher) {
            // Check if this teacher has recorded ANY attendance this week
            $hasRecorded = Attendance::where('teacher_id', $teacher->id)
                ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek])
                ->exists();

            if (!$hasRecorded) {
                try {
                    $notificationTitle = "Attendance Reminder";
                    $notificationMsg = "Hi {$teacher->username}, you haven't recorded any attendance for this week. Please complete it before the week ends.";
                    $actionUrl = route('attendance.bulk');

                    Notification::send($teacher, new SystemAlert($notificationTitle, $notificationMsg, '⏰', $actionUrl));
                    $this->info("Reminder sent to {$teacher->username}");
                } catch (\Exception $e) {
                    $this->error("Failed to send reminder to {$teacher->username}: " . $e->getMessage());
                }
            }
        }

        $this->info('Attendance reminder process completed.');
    }
}
