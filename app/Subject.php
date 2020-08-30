<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'classroom_id', 'employee_id'];


    public function teacher($value = '')
    {
        return $this->belongsTo('App\Employee', 'employee_id');
    }
    public function attendance($value = '')
    {
        return $this->hasMany('App\Attendance');
    }
    public function classroom($value = '')
    {
        return $this->belongsTo('App\Classroom', 'classroom_id');
    }
    public function timetable($value = '')
    {
        return $this->hasMany('App\TimetableDetail');
    }
}
