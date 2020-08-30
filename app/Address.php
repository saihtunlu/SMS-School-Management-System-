<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function students($value = '')
    {
        return $this->hasMany('App\Student');
    }
    public function employees($value = '')
    {
        return $this->hasMany('App\Employee');
    }
}
