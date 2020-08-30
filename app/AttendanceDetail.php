<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    public function attendance($value = '')
    {
        return $this->belongsTo('App\Attendance');
    }
    public function student($value = '')
    {
        return $this->belongsTo('App\Student');
    }
}
