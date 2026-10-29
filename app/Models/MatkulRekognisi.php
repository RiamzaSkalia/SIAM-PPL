<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatkulRekognisi extends Model
{
    use HasFactory;

    protected $table = 'matkul_rekognisi';

    protected $fillable = [
        'periode_id',
        'kode_mk',
        'nama_mk',
        'sks',
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanRekognisi::class, 'matkul_rekognisi_id');
    }
}