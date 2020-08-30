<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mother extends Model
{
    public function children($value = '')
    {
        return $this->hasMany('App\Student');
    }
}
