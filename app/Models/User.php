<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function userinfo()
    {
        return $this->hasOne('App\Models\Userinfo','uid','id');
    }
}
