<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRekognisi extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_rekognisi';

    protected $fillable = [
        'mahasiswa_id',
        'matkul_rekognisi_id',
        'bukti_pendukung', // Tambahkan ini agar file bisa tersimpan
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function matkulRekognisi()
    {
        return $this->belongsTo(MatkulRekognisi::class, 'matkul_rekognisi_id');
    }
}