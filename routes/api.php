<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;

Route::get('/notices', [NoticeController::class, 'index']);
Route::post('/notices', [NoticeController::class, 'store']);
Route::get('/notices/{id}', [NoticeController::class, 'show']);
Route::put('/notices/{id}', [NoticeController::class, 'update']);
Route::delete('/notices/{id}', [NoticeController::class, 'destroy']);