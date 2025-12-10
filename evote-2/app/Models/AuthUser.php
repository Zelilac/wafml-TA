<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AuthUser extends Authenticatable
{
    use Notifiable;

    protected $table = "sc_user";

    protected $primaryKey = "userid";

    public $incrementing = false;

    public $timestamps = false;

    // public function getAuthPassword()
    // {
    //     return $this->password;
    // }
}
