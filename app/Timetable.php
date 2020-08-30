<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    public function classroom($value = '')
    {
        return $this->belongsTo('App\Classroom', 'classroom_id');
    }

    public function timetable_details($value = '')
    {
        return $this->hasMany('App\TimetableDetail');
    }
}
