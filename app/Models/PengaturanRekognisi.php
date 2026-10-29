<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanRekognisi extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_rekognisi';

    protected $fillable = [
        'periode_id',
        'tenggat_waktu',
        'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'tenggat_waktu' => 'datetime',
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_id');
    }
}