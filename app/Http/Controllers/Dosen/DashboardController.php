<?php

<<<<<<< Updated upstream
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlottingBimbingan;

class DashboardController extends Controller
{
    public function index()
    {
        // Contoh mengambil data dosen yang sedang login beserta mahasiswa bimbingannya
        $dosen = Auth::user()->dosen; // sesuaikan relasi user ke dosen jika ada
        
        // Mengarahkan ke file view dashboard dosen yang asli
        return view('dosen.dashboard');
    }
=======
namespace App\Http\Controllers\Dosen; // Musti pakai \Dosen

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // ...
>>>>>>> Stashed changes
}