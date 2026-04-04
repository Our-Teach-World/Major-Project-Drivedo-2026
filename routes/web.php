<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ChatController;

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
});

// Student Routes
Route::middleware(['auth.student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/teachers', [StudentController::class, 'getTeachers'])->name('student.teachers');
    Route::get('/files', [StudentController::class, 'getFiles'])->name('student.files');
    Route::post('/resume-chat', [ChatController::class, 'resumeChat'])->name('student.resume.chat');
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
    });
});
