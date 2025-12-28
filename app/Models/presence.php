<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tanggal_absen',
        'jam_masuk',
        'jam_keluar',
        'status',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

