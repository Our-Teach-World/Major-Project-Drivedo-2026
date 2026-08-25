<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\NotificationController;

// Public Routes
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// CertChain Public Verification
Route::get('/verify', [\App\Http\Controllers\Certchain\VerifyController::class, 'index'])->name('verify.index');
Route::post('/verify', [\App\Http\Controllers\Certchain\VerifyController::class, 'search'])->name('verify.search');
Route::get('/verify/{id}', [\App\Http\Controllers\Certchain\VerifyController::class, 'certificate'])->name('verify.certificate');



// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/registration-success', function () {
    return view('auth.registration-success');
})->name('registration.success');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Teacher Routes
Route::middleware(['auth.teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::post('/upload', [TeacherController::class, 'upload'])->name('teacher.upload');
    Route::post('/update-name', [TeacherController::class, 'updateName'])->name('teacher.updateName');
    Route::post('/update-image', [TeacherController::class, 'updateImage'])->name('teacher.updateImage');
    Route::post('/update-profile', [TeacherController::class, 'updateProfile'])->name('teacher.updateProfile');
    Route::get('/files', [TeacherController::class, 'getFiles'])->name('teacher.files');
    Route::delete('/file/{id}', [TeacherController::class, 'deleteFile'])->name('teacher.deleteFile');
    Route::get('/file/{id}/preview', [TeacherController::class, 'previewFile'])->name('teacher.previewFile');

    // Teacher ka dashboard jahan uske subjects dikhenge
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');

    // 1. Static/Specific Routes first
    Route::get('/attendance/export-selection', [\App\Http\Controllers\BulkAttendanceController::class, 'exportView'])->name('attendance.export.view');
    Route::get('/attendance/bulk', [\App\Http\Controllers\BulkAttendanceController::class, 'index'])->name('attendance.bulk');
    Route::post('/attendance/bulk/store', [\App\Http\Controllers\BulkAttendanceController::class, 'store'])->name('attendance.bulk.store');

    // 2. Dashboard and Wildcard Routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{semester}', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');

    // Attendance Export Download (This can stay specific)
    Route::get('/attendance/export-download/{semester}/{month}/{subject}', [\App\Http\Controllers\BulkAttendanceController::class, 'exportMonthlyReport'])->name('attendance.export.download');

    // Notice Routes for Teachers
    Route::get('/notices/create', [NoticeController::class, 'create'])->name('teacher.notices.create');
    Route::post('/notices/store', [NoticeController::class, 'store'])->name('teacher.notices.store');
    Route::get('/notices/faculty', [NoticeController::class, 'facultyIndex'])->name('teacher.notices.index');
    Route::get('/notices', [NoticeController::class, 'teacherBoard'])->name('teacher.notices.board');
    Route::get('/timetable', [TeacherController::class, 'timetableViewer'])->name('teacher.timetable');
    
    // Quiz Routes for Teachers
    Route::prefix('quizzes')->group(function () {
        Route::get('/', [\App\Http\Controllers\Quiz\QuizController::class, 'index'])->name('teacher.quizzes.index');
        Route::get('/create', [\App\Http\Controllers\Quiz\QuizController::class, 'create'])->name('teacher.quizzes.create');
        Route::post('/', [\App\Http\Controllers\Quiz\QuizController::class, 'store'])->name('teacher.quizzes.store');
        Route::get('/{quiz}/questions', [\App\Http\Controllers\Quiz\QuizController::class, 'questions'])->name('teacher.quizzes.questions');
        Route::post('/{quiz}/questions', [\App\Http\Controllers\Quiz\QuizController::class, 'storeQuestions'])->name('teacher.quizzes.questions.store');
        Route::get('/{quiz}/results', [\App\Http\Controllers\Quiz\QuizController::class, 'results'])->name('teacher.quizzes.results');
        Route::post('/{quiz}/toggle', [\App\Http\Controllers\Quiz\QuizController::class, 'toggleStatus'])->name('teacher.quizzes.toggle');
        Route::get('/{quiz}/results/{result}', [\App\Http\Controllers\Quiz\QuizController::class, 'showAttempt'])->name('teacher.quizzes.attempt.show');
        Route::delete('/{quiz}/results/{result}', [\App\Http\Controllers\Quiz\QuizController::class, 'resetAttempt'])->name('teacher.quizzes.attempt.reset');
    });
});


// Student Routes
Route::middleware(['auth.student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/teachers', [StudentController::class, 'getTeachers'])->name('student.teachers');
    Route::get('/files', [StudentController::class, 'getFiles'])->name('student.files');
    Route::post('/resume-chat', [ChatController::class, 'resumeChat'])->name('student.resume.chat');
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/attendance', [StudentController::class, 'myAttendance'])->name('student.attendance');
    Route::get('/notices', [NoticeController::class, 'studentIndex'])->name('student.notices');
    Route::get('/timetable', [StudentController::class, 'timetableViewer'])->name('student.timetable');

    // ── CS/IT Project & Internship Marketplace (CS & Electronics branches only) ──
    Route::middleware(['marketplace.branch'])->group(function () {
        Route::get('/marketplace', [\App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
        Route::get('/marketplace/onboarding', [\App\Http\Controllers\MarketplaceController::class, 'onboarding'])->name('marketplace.onboarding');
        Route::post('/marketplace/onboarding', [\App\Http\Controllers\MarketplaceController::class, 'saveProfile'])->name('marketplace.onboarding.save');
        Route::get('/marketplace/feed', [\App\Http\Controllers\MarketplaceController::class, 'feed'])->name('marketplace.feed');
        Route::get('/marketplace/internships', [\App\Http\Controllers\MarketplaceController::class, 'internships'])->name('marketplace.internships');
        Route::get('/marketplace/my-projects', [\App\Http\Controllers\MarketplaceController::class, 'myProjects'])->name('marketplace.my-projects');
        Route::get('/marketplace/profile', [\App\Http\Controllers\MarketplaceController::class, 'profile'])->name('marketplace.profile');
        Route::post('/marketplace/profile', [\App\Http\Controllers\MarketplaceController::class, 'updateProfile'])->name('marketplace.profile.update');
        Route::post('/marketplace/projects/{id}/status', [\App\Http\Controllers\MarketplaceController::class, 'updateStatus'])->name('marketplace.projects.status');
        Route::post('/marketplace/reset', [\App\Http\Controllers\MarketplaceController::class, 'reset'])->name('marketplace.reset');
    });

    // ── Book Exchange / Bookloop Feature ──
    Route::prefix('books')->group(function () {
        Route::get('/', [\App\Http\Controllers\BookExchangeController::class, 'index'])->name('books.index');
        Route::get('/create', [\App\Http\Controllers\BookExchangeController::class, 'create'])->name('books.create');
        Route::post('/store', [\App\Http\Controllers\BookExchangeController::class, 'store'])->name('books.store');
        Route::get('/my-listings', [\App\Http\Controllers\BookExchangeController::class, 'myListings'])->name('books.my-listings');
        Route::get('/{id}', [\App\Http\Controllers\BookExchangeController::class, 'show'])->name('books.show');
        Route::post('/{id}/status', [\App\Http\Controllers\BookExchangeController::class, 'updateStatus'])->name('books.status');
        Route::post('/{id}/delete', [\App\Http\Controllers\BookExchangeController::class, 'destroy'])->name('books.destroy');
        
        // Chat Routes for Books
        Route::get('/chat/all', [\App\Http\Controllers\BookExchangeChatController::class, 'index'])->name('books.chat.index');
        Route::get('/chat/start/{bookId}', [\App\Http\Controllers\BookExchangeChatController::class, 'getByBook'])->name('books.chat.start');
        Route::get('/chat/{id}', [\App\Http\Controllers\BookExchangeChatController::class, 'show'])->name('books.chat.show');
        Route::post('/chat/{id}/message', [\App\Http\Controllers\BookExchangeChatController::class, 'sendMessage'])->name('books.chat.message');
    });
    // Quiz Routes for Students
    Route::prefix('quizzes')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\QuizAttemptController::class, 'index'])->name('student.quizzes.index');
        Route::get('/{quiz}/take', [\App\Http\Controllers\Student\QuizAttemptController::class, 'take'])->name('student.quizzes.take');
        Route::post('/{quiz}/submit', [\App\Http\Controllers\Student\QuizAttemptController::class, 'submit'])->name('student.quizzes.submit');
        Route::get('/{quiz}/result', [\App\Http\Controllers\Student\QuizAttemptController::class, 'result'])->name('student.quizzes.result');
    });
});


// Chat Route
Route::match(['get', 'post'], '/api/chat', [ChatController::class, 'chat'])->name('chat.api');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    })->name('admin.login');

    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth.admin'])->group(function () {
        // Notice Routes for Admins (HOD)
        Route::get('/notices/create', [NoticeController::class, 'create'])->name('admin.notices.create');
        Route::post('/notices/store', [NoticeController::class, 'store'])->name('admin.notices.store');
        Route::get('/notices/faculty', [NoticeController::class, 'facultyIndex'])->name('admin.notices.index');
        Route::get('/notices', [NoticeController::class, 'adminBoard'])->name('admin.notices.board');

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/search', [AdminController::class, 'users'])->name('admin.users.search');
        Route::get('/add-user', [AdminController::class, 'addUser'])->name('admin.add-user');
        Route::post('/add-user', [AdminController::class, 'addUser'])->name('admin.add-user.post');
        Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.edit-user');
        Route::post('/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.edit-user.post');
        Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('admin.bulk-action');
        Route::post('/approve-user/{id}', [AdminController::class, 'approveUser'])->name('admin.approveUser');
        Route::post('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::get('/export', [AdminController::class, 'export'])->name('admin.export');

        Route::get('/subjects', [AdminController::class, 'subjects'])->name('admin.subjects');
        Route::post('/subjects/bulk-store', [AdminController::class, 'bulkStoreSubject'])->name('admin.subjects.bulkStore');
        Route::delete('/subjects/destroy/{id}', [AdminController::class, 'destroySubject'])->name('admin.subjects.destroy');

        // Timetable Routes for HOD
        Route::get('/timetable/setup', [\App\Http\Controllers\TimetableController::class, 'schedule'])->name('admin.timetable.setup');
        Route::post('/timetable/store', [\App\Http\Controllers\TimetableController::class, 'store'])->name('admin.timetable.store');
        Route::get('/timetable/print/{semester}', [\App\Http\Controllers\TimetableController::class, 'print'])->name('admin.timetable.print');
        Route::get('/timetable/get-subjects', [\App\Http\Controllers\TimetableController::class, 'getSubjects'])->name('admin.timetable.getSubjects');

        // CertChain Routes for HOD (Admin)
        Route::prefix('certchain')->group(function () {
            Route::get('/hub', [\App\Http\Controllers\AdminController::class, 'certchainHub'])->name('admin.certchain.hub');
            Route::get('/blockchain', [\App\Http\Controllers\Certchain\Admin\AdminController::class, 'blockchain'])->name('admin.certchain.blockchain');
            
            // Events
            Route::get('/events', [\App\Http\Controllers\Certchain\EventController::class, 'index'])->name('teacher.certchain.events.index');
            Route::get('/events/create', [\App\Http\Controllers\Certchain\EventController::class, 'create'])->name('teacher.certchain.events.create');
            Route::post('/events', [\App\Http\Controllers\Certchain\EventController::class, 'store'])->name('teacher.certchain.events.store');
            Route::get('/events/{event}/edit', [\App\Http\Controllers\Certchain\EventController::class, 'edit'])->name('teacher.certchain.events.edit');
            Route::put('/events/{event}', [\App\Http\Controllers\Certchain\EventController::class, 'update'])->name('teacher.certchain.events.update');
            Route::delete('/events/{event}', [\App\Http\Controllers\Certchain\EventController::class, 'destroy'])->name('teacher.certchain.events.destroy');

            // Certificates
            Route::get('/certificates', [\App\Http\Controllers\Certchain\CertificateController::class, 'index'])->name('teacher.certchain.certificates.index');
            Route::get('/certificates/issue', [\App\Http\Controllers\Certchain\CertificateController::class, 'create'])->name('teacher.certchain.certificates.create');
            Route::post('/certificates/issue', [\App\Http\Controllers\Certchain\CertificateController::class, 'store'])->name('teacher.certchain.certificates.store');
            Route::get('/certificates/bulk', [\App\Http\Controllers\Certchain\CertificateController::class, 'bulkCreate'])->name('teacher.certchain.certificates.bulk');
            Route::post('/certificates/bulk', [\App\Http\Controllers\Certchain\CertificateController::class, 'bulkStore'])->name('teacher.certchain.certificates.bulkStore');
            Route::get('/certificates/{certificate}', [\App\Http\Controllers\Certchain\CertificateController::class, 'show'])->name('teacher.certchain.certificates.show');
            Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\Certchain\CertificateController::class, 'download'])->name('teacher.certchain.certificates.download');
            Route::post('/certificates/{certificate}/email', [\App\Http\Controllers\Certchain\CertificateController::class, 'sendEmail'])->name('teacher.certchain.certificates.email');
            Route::post('/certificates/{certificate}/revoke', [\App\Http\Controllers\Certchain\CertificateController::class, 'revoke'])->name('teacher.certchain.certificates.revoke');
        });

        Route::prefix('certchain/templates')->group(function () {
            Route::get('/', [\App\Http\Controllers\Certchain\Admin\TemplateController::class, 'index'])->name('admin.certchain.templates.index');
            Route::get('/create', [\App\Http\Controllers\Certchain\Admin\TemplateController::class, 'create'])->name('admin.certchain.templates.create');
            Route::post('/', [\App\Http\Controllers\Certchain\Admin\TemplateController::class, 'store'])->name('admin.certchain.templates.store');
            Route::get('/{template}/edit', [\App\Http\Controllers\Certchain\Admin\TemplateController::class, 'edit'])->name('admin.certchain.templates.edit');
            Route::put('/{template}', [\App\Http\Controllers\Certchain\Admin\TemplateController::class, 'update'])->name('admin.certchain.templates.update');
            Route::get('/{template}/preview', [\App\Http\Controllers\Certchain\Admin\TemplateController::class, 'preview'])->name('admin.certchain.templates.preview');
            Route::delete('/{template}', [\App\Http\Controllers\Certchain\Admin\TemplateController::class, 'destroy'])->name('admin.certchain.templates.destroy');
        });


    });
});
// Principal Routes
Route::prefix('principal')->middleware(['auth.principal'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\PrincipalController::class, 'dashboard'])->name('principal.dashboard');
    Route::get('/hods', [\App\Http\Controllers\PrincipalController::class, 'manageHods'])->name('principal.hods');
    Route::post('/hods', [\App\Http\Controllers\PrincipalController::class, 'storeHod'])->name('principal.store_hod');
    Route::post('/hods/toggle/{id}', [\App\Http\Controllers\PrincipalController::class, 'toggleHodStatus'])->name('principal.toggle_hod_status');
    Route::delete('/hods/{id}', [\App\Http\Controllers\PrincipalController::class, 'deleteHod'])->name('principal.delete_hod');
    Route::get('/notice', [NoticeController::class, 'create'])->name('principal.notices.create');
    Route::post('/notice', [NoticeController::class, 'store'])->name('principal.notices.store');
    Route::get('/notices/faculty', [NoticeController::class, 'facultyIndex'])->name('principal.notices.index');
    Route::get('/notices', [NoticeController::class, 'adminBoard'])->name('principal.notices.board');
});

// Common Notification Routes
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

// Alumni Routes
Route::middleware(['auth.alumni'])->prefix('alumni')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AlumniController::class, 'dashboard'])->name('alumni.dashboard');
    Route::get('/profile', [\App\Http\Controllers\AlumniController::class, 'profile'])->name('alumni.profile');
    Route::post('/profile', [\App\Http\Controllers\AlumniController::class, 'updateProfile'])->name('alumni.profile.update');
    Route::get('/requests', [\App\Http\Controllers\AlumniController::class, 'requests'])->name('alumni.requests');
    Route::post('/requests/{id}/accept', [\App\Http\Controllers\AlumniController::class, 'acceptRequest'])->name('alumni.requests.accept');
    Route::post('/requests/{id}/decline', [\App\Http\Controllers\AlumniController::class, 'declineRequest'])->name('alumni.requests.decline');
    Route::get('/sessions', [\App\Http\Controllers\AlumniController::class, 'sessions'])->name('alumni.sessions');
    Route::get('/sessions/{id}/chat', [\App\Http\Controllers\AlumniController::class, 'sessionChat'])->name('alumni.session.chat');
    Route::post('/sessions/{id}/message', [\App\Http\Controllers\AlumniController::class, 'sendMessage'])->name('alumni.session.message');
});

// Mentorship Routes for Students
Route::middleware(['auth.student'])->prefix('student/mentorship')->group(function () {
    Route::get('/browse', [\App\Http\Controllers\MentorshipController::class, 'browseAlumni'])->name('mentorship.browse');
    Route::post('/request', [\App\Http\Controllers\MentorshipController::class, 'sendRequest'])->name('mentorship.request');
    Route::get('/requests', [\App\Http\Controllers\MentorshipController::class, 'myRequests'])->name('mentorship.requests');
    Route::get('/sessions', [\App\Http\Controllers\MentorshipController::class, 'mySessions'])->name('mentorship.sessions');
    Route::get('/sessions/{id}/chat', [\App\Http\Controllers\MentorshipController::class, 'sessionChat'])->name('mentorship.session.chat');
    Route::post('/sessions/{id}/message', [\App\Http\Controllers\MentorshipController::class, 'sendSessionMessage'])->name('mentorship.session.message');
});
