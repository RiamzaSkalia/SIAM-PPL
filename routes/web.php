<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\KonsultasiController;

Route::get('/', function () {
    return view('welcome');
});

// Route Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); 
})->name('logout');

// Route Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dosen', [AdminController::class, 'createDosen'])->name('dosen.index');
    Route::get('/dosen/create', [AdminController::class, 'createDosen'])->name('dosen.create');
    Route::post('/dosen/store', [AdminController::class, 'storeDosen'])->name('dosen.store');

    Route::get('/mahasiswa', [AdminController::class, 'createMahasiswa'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('mahasiswa.create');
    Route::post('/mahasiswa/store', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');
});

// Route Khusus Mahasiswa (Dilindungi Middleware Auth)
Route::middleware(['auth'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route Log Bimbingan / Konsultasi
    Route::get('/konsultasi/tambah', [KonsultasiController::class, 'create'])->name('konsultasi.create');
    Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
});

// Route Preview / Testing (Opsional untuk development)
Route::get('/preview-dashboard', function () {
    Auth::loginUsingId(1); // Ganti angka 1 dengan ID user mahasiswa di database kamu
    return redirect()->route('mahasiswa.dashboard');
});