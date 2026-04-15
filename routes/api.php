<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;

// 🔐 AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 📢 PUBLIC
Route::get('/notices', [NoticeController::class, 'index']);
Route::get('/notices/{id}', [NoticeController::class, 'show']);

// 🔒 PROTECTED
Route::middleware('auth:sanctum')->group(function () {

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::put('/profile/update', [ProfileController::class, 'update']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
                                                                           
    // 📢 Notices
    Route::post('/notices', [NoticeController::class, 'store']);
    Route::put('/notices/{id}', [NoticeController::class, 'update']);
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy']);

    // 🖼️ Images
    Route::get('/images', [ImageController::class, 'index']);
    Route::post('/images', [ImageController::class, 'store']);
    Route::get('/images/{id}', [ImageController::class, 'show']);
    Route::put('/images/{id}', [ImageController::class, 'update']);
    Route::delete('/images/{id}', [ImageController::class, 'destroy']);

    // 🎓 HOD
    Route::get('/hod/pending-students', [AuthController::class, 'pendingStudents']);
    Route::post('/hod/approve-student/{id}', [AuthController::class, 'approveStudent']);
    Route::post('/hod/reject-student/{id}', [AuthController::class, 'rejectStudent']);

    // 📸 Profile Image
    Route::post('/upload-profile', [ProfileController::class, 'uploadProfile']);
});
