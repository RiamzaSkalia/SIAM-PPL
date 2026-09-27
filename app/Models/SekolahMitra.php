<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SekolahMitra extends Model
{
    use HasFactory;

    protected $table = 'sekolah_mitra';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
    ];

    public function plottingBimbingan(): HasMany
    {
        return $this->hasMany(PlottingBimbingan::class, 'sekolah_id');
    }
}
