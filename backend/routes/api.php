<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ApplicationNoteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);

    // Dashboard routes
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/success-rate', [DashboardController::class, 'successRate']);
    Route::get('/dashboard/timeline', [DashboardController::class, 'timeline']);

    // Application routes
    Route::apiResource('applications', ApplicationController::class);
    
    // Interview routes
    Route::get('/applications/{application}/interviews', [InterviewController::class, 'index']);
    Route::post('/applications/{application}/interviews', [InterviewController::class, 'store']);
    Route::get('/interviews/{interview}', [InterviewController::class, 'show']);
    Route::put('/interviews/{interview}', [InterviewController::class, 'update']);
    Route::delete('/interviews/{interview}', [InterviewController::class, 'destroy']);
    
    // Application notes routes
    Route::get('/applications/{application}/notes', [ApplicationNoteController::class, 'index']);
    Route::post('/applications/{application}/notes', [ApplicationNoteController::class, 'store']);
    Route::get('/notes/{note}', [ApplicationNoteController::class, 'show']);
    Route::put('/notes/{note}', [ApplicationNoteController::class, 'update']);
    Route::delete('/notes/{note}', [ApplicationNoteController::class, 'destroy']);

    // Company routes
    Route::apiResource('companies', CompanyController::class);
    
    // Job routes
    Route::get('/jobs', function (Request $request) {
        // Get jobs with company information
        $jobs = \App\Models\Job::with('company')
            ->when($request->company_id, function ($query, $companyId) {
                return $query->where('company_id', $companyId);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('posted_date', 'desc')
            ->paginate(15);
            
        return response()->json($jobs);
    });
});
