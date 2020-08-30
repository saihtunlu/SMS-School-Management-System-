<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceResult extends Model
{
    protected $dates = ['date'];

    public function classroom($value = '')
    {
        return $this->belongsTo('App\Classroom');
    }
}
