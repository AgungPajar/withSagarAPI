<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExportExcelController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\ProfileController;

// 🔐 Login
Route::post('/login', [AuthController::class, 'login']);

// 📋 Pendaftaran Ekstrakurikuler (Tanpa login)
Route::post('/register-siswa', [RegistrationController::class, 'register']);

// 🌐 Daftar nama ekskul (untuk dropdown)
Route::get('/clubs', [ClubController::class, 'index']);

// 📋 Presensi & Laporan (Butuh login)
Route::middleware('auth:sanctum')->group(function () {

    // 💼 User info
    Route::get('/user', fn($request) => $request->user());

    // 🔧 Profil
    Route::post('/profile/update', [ProfileController::class, 'update']);

    // 🏫 Ekskul
    Route::get('/clubs/{hashedId}', [ClubController::class, 'show']);
    Route::get('/clubs/{hashedId}/students', [ClubController::class, 'getStudents']);
    Route::get('/clubs/{hashedId}/members', [ClubController::class, 'members']);
    Route::post('/clubs/{hashedId}/members', [StudentController::class, 'storeToClub']);

    // 🧑‍🎓 Siswa
    Route::apiResource('students', StudentController::class)->only(['destroy']); // opsional, kalau hanya hapus

    // 📋 Presensi & Laporan
    Route::post('/attendances', [AttendanceController::class, 'store']);
    Route::post('/clubs/{hashedId}/activity-reports', [ActivityReportController::class, 'store']);

    // 📊 Rekap
    Route::get('/rekapitulasi', [RekapController::class, 'index']);
});

// 🧑‍💼 Admin Routes
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Kelola klub
    Route::get('/clubs', [ClubController::class, 'index']);
    Route::post('/clubs', [ClubController::class, 'store']);
    Route::delete('/clubs/{club}', [ClubController::class, 'destroy']);
    // Route::post('/clubs/{hashedId}/update', [ClubController::class, 'update']);
    Route::put('/clubs/{hashedId}', [ClubController::class, 'update']);

    // Tambah pengurus ekskul
    Route::post('/users', [UserController::class, 'store']);
});

// 🧾 Export (Bisa dibuat autentikasi juga jika perlu)
Route::get('/export/harian', [ExportExcelController::class, 'exportHarian']);
Route::get('/rekap/export/monthly', [ExportExcelController::class, 'exportBulanan']);
