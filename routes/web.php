<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\JobController;
use App\Http\Controllers\Api\ApplicationController; 
use App\Models\Job;
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
    
    // 2. FIXED: Moved Applications inside the 'auth' group!
    // Note: Pointing to 'indexWeb' based on the controller update we did earlier
    Route::get('/applications', [ApplicationController::class, 'indexWeb']);
    
    //applicants
    Route::get('/resume', [ResumeController::class, 'index']);
    Route::post('/resume', [ResumeController::class, 'store']);
    
    // Explicit Admin Route Protection Group
    Route::middleware(\App\Http\Middleware\EnsureUserIsAdmin::class)->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return "Welcome Admin to the protected route area.";
        });
    });
});