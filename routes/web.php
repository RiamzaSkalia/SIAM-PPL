<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\KonsultasiController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\VerifikasiController;
use App\Http\Controllers\Pamong\RegisterController;

Route::get('/', fn () => redirect()->route('login'));

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});

// Pendaftaran Guru Pamong
Route::controller(RegisterController::class)->prefix('pamong')->name('pamong.')->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('register.post');
});


Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {

        // Pengaturan Sistem
        Route::prefix('pengaturan')->name('pengaturan.')->controller(PengaturanController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/periode-aktif', 'setPeriodeAktif')->name('periode.aktif');
            Route::post('/periode-store', 'storePeriode')->name('periode.store');
            Route::post('/periode-hapus/{id}', 'destroyPeriode')->name('periode.destroy');
            Route::post('/update', 'updatePengaturan')->name('update');
        });

        // Modul Admin Utama
        Route::controller(AdminController::class)->group(function () {
            // Dashboard & Export
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/pemetaan/pdf', 'exportPemetaanPdf')->name('pemetaan.pdf');

            // Data Dosen
            Route::get('/dosen', 'indexDosen')->name('dosen.index');
            Route::get('/dosen/create', 'createDosen')->name('dosen.create');
            Route::post('/dosen/store', 'storeDosen')->name('dosen.store');
            Route::get('/dosen/{id}/edit', 'editDosen')->name('dosen.edit');
            Route::put('/dosen/{id}', 'updateDosen')->name('dosen.update');
            Route::delete('/dosen/{id}', 'destroyDosen')->name('dosen.destroy');

            // Data Mahasiswa
            Route::get('/mahasiswa', 'indexMahasiswa')->name('mahasiswa.index');
            Route::get('/mahasiswa/create', 'createMahasiswa')->name('mahasiswa.create');
            Route::post('/mahasiswa/store', 'storeMahasiswa')->name('mahasiswa.store');
            Route::get('/mahasiswa/{id}/edit', 'editMahasiswa')->name('mahasiswa.edit');
            Route::put('/mahasiswa/{id}', 'updateMahasiswa')->name('mahasiswa.update');
            Route::delete('/mahasiswa/{id}', 'destroyMahasiswa')->name('mahasiswa.destroy');

            // Data Sekolah
            Route::get('/sekolah', 'indexSekolah')->name('sekolah.index');
            Route::get('/sekolah/create', 'createSekolah')->name('sekolah.create');
            Route::post('/sekolah/store', 'storeSekolah')->name('sekolah.store');
            Route::get('/sekolah/{id}/edit', 'editSekolah')->name('sekolah.edit');
            Route::put('/sekolah/{id}', 'updateSekolah')->name('sekolah.update');
            Route::delete('/sekolah/{id}', 'destroySekolah')->name('sekolah.destroy');

            // Pemetaan Bimbingan
            Route::get('/pemetaan', 'indexPemetaan')->name('pemetaan.index');
            Route::get('/pemetaan/create', 'createPemetaan')->name('pemetaan.create');
            Route::post('/pemetaan/store', 'storePemetaan')->name('pemetaan.store');
            Route::get('/pemetaan/{id}/edit', 'editPemetaan')->name('pemetaan.edit');
            Route::put('/pemetaan/{id}', 'updatePemetaan')->name('pemetaan.update');
            Route::delete('/pemetaan/{id}', 'destroyPemetaan')->name('pemetaan.destroy');
        });
    });

    // MAHASISWA ROUTES
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        Route::controller(KonsultasiController::class)->prefix('konsultasi')->name('konsultasi.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });
    });

    // DOSEN ROUTES
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

        Route::controller(VerifikasiController::class)->prefix('konsultasi')->name('konsultasi.')->group(function () {
            Route::post('/{konsultasi}/setujui', 'setujui')->name('setujui');
            Route::post('/{konsultasi}/tolak', 'tolak')->name('tolak');
        });

        Route::get('/mahasiswa-bimbingan', [DosenDashboardController::class, 'mahasiswaBimbingan'])->name('mahasiswa.bimbingan');
        Route::get('/mahasiswa-bimbingan/{id}', [DosenDashboardController::class, 'detailMahasiswa'])->name('mahasiswa.detail');
    });

});

Route::get('/preview-dashboard', function () {
    Auth::loginUsingId(1);
    return redirect()->route('mahasiswa.dashboard');
});

Route::get('/preview-dashboard-dosen', function () {
    $dosen = Dosen::first();
    if ($dosen) {
        Auth::loginUsingId($dosen->user_id);
    }
    return redirect()->route('dosen.dashboard');
});