<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public function grade_group($value = '')
    {
        return $this->hasMany('App\GradeGroup');
    }
    public function classroom($value = '')
    {
        return $this->belongsTo('App\Classroom');
    }
}
