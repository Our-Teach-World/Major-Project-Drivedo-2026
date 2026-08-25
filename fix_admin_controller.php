<?php
$file = "app/Http/Controllers/AdminController.php";
$content = file_get_contents($file);

$useStatements = <<<'PHP'
use App\Models\Subject;
use App\Services\UploadService;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\BlockchainBlock;
use App\Services\BlockchainService;
PHP;

$content = str_replace("use App\Models\Subject;\nuse App\Services\UploadService;", $useStatements, $content);

$dashboardMethodOld = <<<'PHP'
    public function dashboard()
    {
        $admin = Admin::find(session('admin_id'));
        $query = User::whereIn('role', ['teacher', 'student', 'alumni']);

        if ($admin && $admin->branch && !in_array($admin->role, ['principal', 'admin'])) {
            $query->where(function ($q) use ($admin) {
                $q->where(function ($sq) use ($admin) {
                    $sq->where('role', 'student')
                        ->whereHas('studentProfile', function ($sp) use ($admin) {
                            $sp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($tq) use ($admin) {
                    $tq->where('role', 'teacher')
                        ->whereHas('teacherProfile', function ($tp) use ($admin) {
                            $tp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($aq) use ($admin) {
                    $aq->where('role', 'alumni')
                        ->where('branch', $admin->branch);
                });
            });
        }

        $baseQuery = clone $query;
        $totalUsers = (clone $baseQuery)->count();
        $pendingUsers = (clone $baseQuery)->whereIn('status', ['pending', ''])->count();
        $approvedUsers = (clone $baseQuery)->where('status', 'approved')->count();
        $teachers = (clone $baseQuery)->where('role', 'teacher')->count();
        $students = (clone $baseQuery)->where('role', 'student')->count();
        $alumni = (clone $baseQuery)->where('role', 'alumni')->count();

        return view('admin.dashboard', compact('totalUsers', 'pendingUsers', 'approvedUsers', 'teachers', 'students', 'alumni'));
    }
PHP;

$dashboardMethodNew = <<<'PHP'
    public function dashboard(BlockchainService $blockchain)
    {
        $admin = Admin::find(session('admin_id'));
        $query = User::whereIn('role', ['teacher', 'student', 'alumni']);

        if ($admin && $admin->branch && !in_array($admin->role, ['principal', 'admin'])) {
            $query->where(function ($q) use ($admin) {
                $q->where(function ($sq) use ($admin) {
                    $sq->where('role', 'student')
                        ->whereHas('studentProfile', function ($sp) use ($admin) {
                            $sp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($tq) use ($admin) {
                    $tq->where('role', 'teacher')
                        ->whereHas('teacherProfile', function ($tp) use ($admin) {
                            $tp->where('branch', $admin->branch);
                        });
                })->orWhere(function ($aq) use ($admin) {
                    $aq->where('role', 'alumni')
                        ->where('branch', $admin->branch);
                });
            });
        }

        $baseQuery = clone $query;
        $totalUsers = (clone $baseQuery)->count();
        $pendingUsers = (clone $baseQuery)->whereIn('status', ['pending', ''])->count();
        $approvedUsers = (clone $baseQuery)->where('status', 'approved')->count();
        $teachers = (clone $baseQuery)->where('role', 'teacher')->count();
        $students = (clone $baseQuery)->where('role', 'student')->count();
        $alumni = (clone $baseQuery)->where('role', 'alumni')->count();

        // Certchain Stats
        $stats = [
            'total_users'        => User::count(),
            'total_events'       => Event::count(),
            'total_certificates' => Certificate::count(),
            'total_blocks'       => BlockchainBlock::count(),
            'emails_sent'        => Certificate::where('email_sent', true)->count(),
            'revoked'            => Certificate::where('status', 'revoked')->count(),
        ];

        $recentCertificates = Certificate::with(['event', 'issuer'])->latest()->limit(8)->get();
        $chainStatus = $blockchain->validateChain();

        $monthlyStats = Certificate::selectRaw("MONTH(created_at) as month, COUNT(*) as count")
            ->whereRaw("YEAR(created_at) = ?", [date('Y')])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        return view('admin.dashboard', compact('totalUsers', 'pendingUsers', 'approvedUsers', 'teachers', 'students', 'alumni', 'stats', 'recentCertificates', 'chainStatus', 'monthlyStats'));
    }
PHP;

$content = str_replace($dashboardMethodOld, $dashboardMethodNew, $content);
file_put_contents($file, $content);
echo "AdminController fixed.\n";

