<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\KonsultasiController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\VerifikasiController;

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

    // Sekolah & Guru Pamong Routes
    Route::get('/sekolah', [AdminController::class, 'indexSekolah'])->name('sekolah.index');
    Route::get('/sekolah/create', [AdminController::class, 'createSekolah'])->name('sekolah.create');
    Route::post('/sekolah/store', [AdminController::class, 'storeSekolah'])->name('sekolah.store');
    Route::get('/sekolah/{id}/edit', [AdminController::class, 'editSekolah'])->name('sekolah.edit');
    Route::put('/sekolah/{id}', [AdminController::class, 'updateSekolah'])->name('sekolah.update');
    Route::delete('/sekolah/{id}', [AdminController::class, 'destroySekolah'])->name('sekolah.destroy');

    // Pemetaan Bimbingan Routes
    Route::get('/pemetaan', [AdminController::class, 'indexPemetaan'])->name('pemetaan.index');
    Route::get('/pemetaan/create', [AdminController::class, 'createPemetaan'])->name('pemetaan.create');
    Route::post('/pemetaan/store', [AdminController::class, 'storePemetaan'])->name('pemetaan.store');
    Route::get('/pemetaan/{id}/edit', [AdminController::class, 'editPemetaan'])->name('pemetaan.edit');
    Route::put('/pemetaan/{id}', [AdminController::class, 'updatePemetaan'])->name('pemetaan.update');
    Route::delete('/pemetaan/{id}', [AdminController::class, 'destroyPemetaan'])->name('pemetaan.destroy');
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

Route::middleware(['auth'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
 
    Route::post('/konsultasi/{konsultasi}/setujui', [VerifikasiController::class, 'setujui'])->name('konsultasi.setujui');
    Route::post('/konsultasi/{konsultasi}/tolak', [VerifikasiController::class, 'tolak'])->name('konsultasi.tolak');
});

Route::get('/preview-dashboard-dosen', function () {
    $dosen = \App\Models\Dosen::first();
    Auth::loginUsingId($dosen->user_id);
    return redirect()->route('dosen.dashboard');
});