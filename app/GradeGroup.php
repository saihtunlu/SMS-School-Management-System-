<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeGroup extends Model
{
    public function grade($value = '')
    {
        return $this->belongsTo('App\Grade');
    }

    public function grade_details($value = '')
    {
        return $this->hasMany('App\GradeDetail');
    }
}
