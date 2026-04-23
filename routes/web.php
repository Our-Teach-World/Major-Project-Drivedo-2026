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

        // Removed from here to allow teacher access too
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
