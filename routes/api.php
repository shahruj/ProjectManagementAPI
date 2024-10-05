<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TimesheetController;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Routes
    Route::get('/users', [UserController::class, 'index']); // Read all users with filters
    Route::get('/users/{id}', [UserController::class, 'show']); // Read single user
    Route::post('/users/update', [UserController::class, 'update']); // Update user
    Route::post('/users/delete', [UserController::class, 'destroy']); // Delete user

    // Project Routes
    Route::post('/projects', [ProjectController::class, 'store']); // Create project
    Route::get('/projects', [ProjectController::class, 'index']); // Read all projects with filters
    Route::get('/projects/{id}', [ProjectController::class, 'show']); // Read single project
    Route::post('/projects/update', [ProjectController::class, 'update']); // Update project
    Route::post('/projects/delete', [ProjectController::class, 'destroy']); // Delete project

    // Timesheet Routes
    Route::post('/timesheets', [TimesheetController::class, 'store']); // Create timesheet
    Route::get('/timesheets', [TimesheetController::class, 'index']); // Read all timesheets with filters
    Route::get('/timesheets/{id}', [TimesheetController::class, 'show']); // Read single timesheet
    Route::post('/timesheets/update', [TimesheetController::class, 'update']); // Update timesheet
    Route::post('/timesheets/delete', [TimesheetController::class, 'destroy']); // Delete timesheet

    // User-Project Assignment Routes
    Route::post('/users/assign-project', [UserController::class, 'assignProject']);
    Route::post('/users/remove-project', [UserController::class, 'removeProject']);

});


