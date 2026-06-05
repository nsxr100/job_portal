<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\JobController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Api\ApplicationController; 
use App\Http\Controllers\ResumeController; 

// === Public Routes ===

// 3. TWEAK: Moved the logic into the JobController for cleaner architecture
Route::get('/', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show'])->where('id', '[0-9]+');
Route::post('/jobs/{id}/apply', [App\Http\Controllers\Api\ApplicationController::class, 'applyWeb']);


// === Guest-Only Route Restrictions ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});



// === Auth Protected Routes ===
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // === Employer Web Routes ===
    Route::get('/employer/dashboard', [App\Http\Controllers\Api\ApplicationController::class, 'employerIndexWeb']);
    Route::patch('/applications/{id}/status', [App\Http\Controllers\Api\ApplicationController::class, 'updateStatusWeb']);
    
    // Create jobs
    Route::get('/jobs/create', [JobController::class, 'create']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->where('id', '[0-9]+');
    Route::patch('/jobs/{id}', [JobController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->where('id', '[0-9]+');

    // Reports, exports, and imports
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/export/{format}', [ReportController::class, 'export'])
        ->whereIn('format', ['pdf', 'xlsx', 'csv', 'json']);
    Route::post('/reports/import/jobs', [ReportController::class, 'importJobs']);
    
    // 2. FIXED: Moved Applications inside the 'auth' group!
    // Note: Pointing to 'indexWeb' based on the controller update we did earlier
    Route::get('/applications', [ApplicationController::class, 'indexWeb']);
    
    //applicants
    Route::get('/resume', [ResumeController::class, 'index']);
    Route::post('/resume', [ResumeController::class, 'store']);
    
    // Explicit Admin Route Protection Group
    Route::middleware(\App\Http\Middleware\EnsureUserIsAdmin::class)->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::post('/users', [AdminController::class, 'storeUser']);
        Route::patch('/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser']);
        Route::delete('/jobs/{job}', [AdminController::class, 'destroyJob']);
        Route::patch('/applications/{application}', [AdminController::class, 'updateApplication']);
        Route::delete('/applications/{application}', [AdminController::class, 'destroyApplication']);
        Route::delete('/resumes/{resume}', [AdminController::class, 'destroyResume']);
    });
});
