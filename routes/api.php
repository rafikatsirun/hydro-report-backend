<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;

// ✅ Akses terbuka (tidak perlu login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/laporan',  [LaporanController::class, 'store']);
Route::get('/laporan',   [LaporanController::class, 'index']); // 👈 dipindahkan ke sini agar publik bisa akses

// ✅ Akses khusus admin/user login
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/statistik', [LaporanController::class, 'statistik']);

    Route::get('/laporan/{id}', [LaporanController::class, 'show']); // ambil detail laporan
    Route::put('/laporan/{id}', [LaporanController::class, 'update']); // edit laporan
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy']); // hapus laporan

    Route::put('/laporan/{id}/status', [LaporanController::class, 'updateStatus']);
});
