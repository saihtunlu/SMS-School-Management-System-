<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    public function classroom($value = '')
    {
        return $this->belongsTo('App\Classroom');
    }
    public function subject($value = '')
    {
        return $this->belongsTo('App\Subject', 'subject_id');
    }
    public function attendance_groups($value = '')
    {
        return $this->hasMany('App\AttendanceGroup');
    }
    public function attendance_detail($value = '')
    {
        return $this->hasMany('App\AttendanceDetail');
    }
}
