<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    // Syarat minimal konsultasi yang harus "disetujui" sebelum kartu konsultasi bisa dicetak
    public const MINIMAL_KONSULTASI = 5;

    protected $fillable = [
        'user_id',
        'nim',
        'nama_mahasiswa',
        'prodi',
        'semester',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // relasi one-to-one karena kolom mahasiswa_id di plotting_bimbingan bersifat unique
    // (satu mahasiswa hanya punya satu plotting aktif)
    public function plottingBimbingan(): HasOne
    {
        return $this->hasOne(PlottingBimbingan::class, 'mahasiswa_id');
    }
}
