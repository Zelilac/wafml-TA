<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = "ms_unit";

    protected $primaryKey = "kodeunit";

    protected $keyType = "string";

    public $timestamps = false;

    // override delete supaya tidak bisa
    public function delete()
    {
        throw new \Exception("This model is read-only.");
    }

    public function parent()
    {
        return $this->belongsTo(Unit::class, "parentunit", "kodeunit");
    }

    public function children()
    {
        return $this->hasMany(Unit::class, "parentunit", "kodeunit");
    }
}
