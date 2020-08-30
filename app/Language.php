<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public function user_language($value = '')
    {
        return $this->hasMany('App\User_Language');
    }
}
