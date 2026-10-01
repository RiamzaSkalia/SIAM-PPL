<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahMitra extends Model
{
    use HasFactory;

    protected $table = 'sekolah_mitra';

    protected $fillable = [
        'nama_sekolah',
        'jenjang',
        'kuota',
        'alamat',
    ];

    public function guruPamong()
    {
        return $this->hasOne(GuruPamong::class, 'sekolah_id');
    }
}