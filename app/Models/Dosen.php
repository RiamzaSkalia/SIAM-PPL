<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'user_id',
        'periode_id',
        'nip',
        'nama_dosen',
        'email',
        'plain_password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_id');
    }

    public function plotting()
    {
        return $this->hasMany(PlottingBimbingan::class, 'dosen_id');
    }
}