<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    public function classrooms($value = '')
    {
        return $this->hasMany('App\Classrooms');
    }
    public function students($value = '')
    {
        return $this->hasMany('App\students');
    }
}
