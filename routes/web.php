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

// Route Admin (Diperbarui agar mendukung Halaman Tabel & Form Tambah)
Route::prefix('admin')->name('admin.')->group(function () {
    // Dosen Routes
    Route::get('/dosen', [AdminController::class, 'indexDosen'])->name('dosen.index');
    Route::get('/dosen/create', [AdminController::class, 'createDosen'])->name('dosen.create');
    Route::post('/dosen/store', [AdminController::class, 'storeDosen'])->name('dosen.store');
    Route::get('/dosen/{id}/edit', [AdminController::class, 'editDosen'])->name('dosen.edit');
    Route::put('/dosen/{id}', [AdminController::class, 'updateDosen'])->name('dosen.update');
    Route::delete('/dosen/{id}', [AdminController::class, 'destroyDosen'])->name('dosen.destroy');

    // Mahasiswa Routes
    Route::get('/mahasiswa', [AdminController::class, 'indexMahasiswa'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('mahasiswa.create');
    Route::post('/mahasiswa/store', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}/edit', [AdminController::class, 'editMahasiswa'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [AdminController::class, 'updateMahasiswa'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [AdminController::class, 'destroyMahasiswa'])->name('mahasiswa.destroy');
});

// Route Khusus Mahasiswa (BAWAAN GIT - TIDAK DIUBAH)
Route::middleware(['auth'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route Log Bimbingan / Konsultasi
    Route::get('/konsultasi/tambah', [KonsultasiController::class, 'create'])->name('konsultasi.create');
    Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
});

// Route Preview / Testing (BAWAAN GIT - TIDAK DIUBAH)
Route::get('/preview-dashboard', function () {
    Auth::loginUsingId(1);
    return redirect()->route('mahasiswa.dashboard');
});