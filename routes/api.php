<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\EventController;


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
    Route::post('/notices/upload-image', [NoticeController::class, 'uploadImage']);
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

    // 💬 Feedback
    Route::get('/feedbacks', [FeedbackController::class, 'index']);
    Route::post('/feedbacks', [FeedbackController::class, 'store']);
    Route::get('/feedbacks/{id}', [FeedbackController::class, 'show']);
    Route::delete('/feedbacks/{id}', [FeedbackController::class, 'destroy']);

    // 📅 Public Event APIs
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);

    // 📅 Event APIs
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::post('/events/upload-image', [EventController::class, 'uploadImage']);
});
