<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresidenBem extends Model
{
    protected $table = "z_evote_mst_presiden_bem";

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'periode',
        'nim_ketua',
        'foto_ketua',
        'nim_wakil',
        'foto_wakil',
        'created_at',
    ];

    public function mahasiswa_ketua()
    {
        return $this->belongsTo(Mahasiswa::class, "nim_ketua", "nim");
    }

    public function mahasiswa_wakil()
    {
        return $this->belongsTo(Mahasiswa::class, "nim_wakil", "nim");
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, "id_ms_presiden_bem");
    }
}
