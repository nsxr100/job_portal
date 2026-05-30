<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\ApplicationController;

//auth endpoint
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//public access
Route::get('/jobs', [JobController::class, 'index']);          // GET /api/jobs (Read All)
Route::get('/jobs/{id}', [JobController::class, 'show']);// GET /api/jobs/{id} (Read Single)
//protected
Route::middleware('auth:sanctum')->group(function () {

    // Current Authenticated User Context
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Revoke Authentication Token
    Route::post('/logout', [AuthController::class, 'logout']);

    // Job Modification Endpoints (CRUD - Create, Update, Delete)
    Route::get('/jobs', [JobController::class, 'index']); // get /api/jobs
    Route::post('/jobs', [JobController::class, 'store']);          // POST /api/jobs (Create)
    Route::put('/jobs/{id}', [JobController::class, 'update']);      // PUT /api/jobs/{id} (Update)
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);  // DELETE /api/jobs/{id} (Delete)

    // Job Application Handling Endpoints
    Route::get('/applications', [ApplicationController::class, 'index']);       // GET /api/applications (List submissions)
    Route::post('/applications', [ApplicationController::class, 'store']);     // POST /api/applications (Apply to a job)
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);  // GET /api/applications/{id} (View submission details)
});
