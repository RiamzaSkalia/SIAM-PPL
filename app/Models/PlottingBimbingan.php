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
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_id');
    }

    public function sekolah()
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
}