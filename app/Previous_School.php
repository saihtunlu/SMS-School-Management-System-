<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Previous_School extends Model
{
    public function students($value = '')
    {
        return $this->hasMany('App\Student');
    }
    protected $table = 'previous_schools';
}
