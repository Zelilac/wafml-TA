<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = "ms_mhs";

    protected $primaryKey = "nim";

    protected $keyType = "string";

    public $timestamps = false;

    // override delete supaya tidak bisa
    public function delete()
    {
        throw new \Exception("This model is read-only.");
    }

    public function prodi()
    {
        return $this->belongsTo(Unit::class, "kodeunit", "kodeunit");
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, "nim", "nim");
    }
}
