<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Tambahkan Route Logout ini
Route::post('/logout', function () {
    // Sementara redirect ke halaman utama jika belum menggunakan Auth Breeze/UI
    return redirect('/'); 
})->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    // Dosen Routes
    Route::get('/dosen', [AdminController::class, 'createDosen'])->name('dosen.index');
    Route::get('/dosen/create', [AdminController::class, 'createDosen'])->name('dosen.create');
    Route::post('/dosen/store', [AdminController::class, 'storeDosen'])->name('dosen.store');

    // Mahasiswa Routes
    Route::get('/mahasiswa', [AdminController::class, 'createMahasiswa'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('mahasiswa.create');
    Route::post('/mahasiswa/store', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');
});