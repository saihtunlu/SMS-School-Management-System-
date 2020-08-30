<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimetableDetail extends Model
{
    public function subject($value = '')
    {
        return $this->belongsTo('App\Subject');
    }
    public function timetable($value = '')
    {
        return $this->belongsTo('App\Timetable');
    }
}
