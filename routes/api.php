<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 🔐 Login
Route::post('/login', [AuthController::class, 'login']);

// 🏫 Klub (Ekskul)
// Route::middleware(['auth:sanctum', 'can.access.club'])->group(function () {
//     Route::get('/clubs/{clubId}', [ClubController::class, 'show']);
// });
Route::middleware('auth:sanctum')->get('/clubs/{id}', [ClubController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/clubs', [ClubController::class, 'show']);
});

// 👥 Admin OSIS Routes
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('/clubs', [ClubController::class, 'index']); // Daftar ekskul
    Route::post('/clubs', [ClubController::class, 'store']); // Tambah ekskul
    Route::delete('/clubs/{club}', [ClubController::class, 'destroy']); // Hapus ekskul

    Route::post('/users', [UserController::class, 'store']); // Tambah pengurus ekskul
});

// 🧑‍🎓 Siswa
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('students', StudentController::class);
});

// 📋 Presensi
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('attendances', AttendanceController::class);
});

// 🌐 Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function ($request) {
        return $request->user();
    });
});