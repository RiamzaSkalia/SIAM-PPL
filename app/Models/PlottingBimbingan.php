<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlottingBimbingan extends Model
{
    use HasFactory;

    protected $table = 'plotting_bimbingan';

    protected $fillable = [
        'periode_id',
        'sekolah_id',
        'dosen_id',
        'mahasiswa_id', // BARU: tanpa ini, mahasiswa_id gagal tersimpan saat create()
        'gupam_id',     // BARU: tanpa ini, gupam_id gagal tersimpan saat create()
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_id');
    }

    public function sekolah()
    {
        return $this->belongsTo(SekolahMitra::class, 'sekolah_id');
    }

    // BARU: alias nama lain untuk sekolah(), dipakai oleh DashboardController
    public function sekolahMitra()
    {
        return $this->belongsTo(SekolahMitra::class, 'sekolah_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'plotting_mahasiswa', 'plotting_id', 'mahasiswa_id');
    }

    // BARU: relasi langsung lewat kolom mahasiswa_id (bukan pivot), dipakai oleh Mahasiswa::plottingBimbingan()
    public function mahasiswaLangsung()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // BARU: relasi ke guru pamong, dipakai oleh DashboardController
    public function guruPamong()
    {
        return $this->belongsTo(GuruPamong::class, 'gupam_id');
    }

    // BARU: relasi ke daftar konsultasi dalam plotting ini, dipakai oleh DashboardController & KonsultasiController
    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class, 'plotting_id');
    }
}