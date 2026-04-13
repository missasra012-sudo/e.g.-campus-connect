<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;

// =========================
// 🔐 AUTH ROUTES
// =========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// =========================
// 📢 PUBLIC NOTICE ROUTES
// =========================
Route::get('/notices', [NoticeController::class, 'index']);
Route::get('/notices/{id}', [NoticeController::class, 'show']);

// =========================
// 🔒 PROTECTED ROUTES (Requires Sanctum Token)
// =========================
Route::middleware('auth:sanctum')->group(function () {

    // 👤 Profile
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/upload-profile', [ProfileController::class, 'uploadProfile']);

    // 📢 Notice CRUD (Teacher/HOD)
    Route::post('/notices', [NoticeController::class, 'store']);
    Route::put('/notices/{id}', [NoticeController::class, 'update']);
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy']);

    // 🖼️ Images CRUD
    Route::get('/images', [ImageController::class, 'index']);
    Route::post('/images', [ImageController::class, 'store']);
    Route::get('/images/{id}', [ImageController::class, 'show']);
    Route::put('/images/{id}', [ImageController::class, 'update']);
    Route::delete('/images/{id}', [ImageController::class, 'destroy']);

    // 🎓 HOD Actions
    Route::get('/hod/pending-students', [AuthController::class, 'pendingStudents']);
    Route::post('/hod/approve-student/{id}', [AuthController::class, 'approveStudent']);
    Route::post('/hod/reject-student/{id}', [AuthController::class, 'rejectStudent']);

    // 🚪 Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});