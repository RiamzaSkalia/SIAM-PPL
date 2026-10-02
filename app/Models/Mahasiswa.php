<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    public const MINIMAL_KONSULTASI = 5; 

    protected $fillable = [
        'user_id',
        'nim',
        'nama_mahasiswa',
        'no_hp',
        'prodi',
        'semester',
        'angkatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plotting()
    {
        return $this->belongsToMany(PlottingBimbingan::class, 'plotting_mahasiswa', 'mahasiswa_id', 'plotting_id');
    }

    // BARU: relasi langsung ke plotting_bimbingan sesuai kolom mahasiswa_id
    // (dipakai oleh DashboardController dan KonsultasiController)
    public function plottingBimbingan()
    {
        return $this->hasOne(PlottingBimbingan::class, 'mahasiswa_id');
    }
}