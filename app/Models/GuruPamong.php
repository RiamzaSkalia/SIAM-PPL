<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruPamong extends Model
{
    use HasFactory;

    protected $table = 'guru_pamong';

    protected $fillable = [
        'user_id',
        'sekolah_id',
        'nip_nik',
        'nama_guru_pamong',
        'no_hp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sekolah()
    {
        return $this->belongsTo(SekolahMitra::class, 'sekolah_id');
    }
}