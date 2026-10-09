<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'periode_id',
        'nim',
        'nama_mahasiswa',
        'no_hp',
    ];

    // Relasi Banyak ke Banyak melalui pivot plotting_mahasiswa
    public function plottingBimbingan(): BelongsToMany
    {
        return $this->belongsToMany(
            PlottingBimbingan::class,
            'plotting_mahasiswa',
            'mahasiswa_id',
            'plotting_id'
        );
    }

    // Alias relasi agar kompatibel
    public function plotting(): BelongsToMany
    {
        return $this->plottingBimbingan();
    }
}