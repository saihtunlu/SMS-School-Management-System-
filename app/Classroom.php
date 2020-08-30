<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['gade', 'section'];

    public function attendances($value = '')
    {
        return $this->hasMany('App\Attendance');
    }
    public function attendance_results($value = '')
    {
        return $this->hasMany('App\AttendanceResult');
    }
    public function academic_year($value = '')
    {
        return $this->belongsTo('App\AcademicYear');
    }
    public function students($value = '')
    {
        return $this->hasMany('App\StudentClassroom');
    }
    public function teachers($value = '')
    {
        return $this->hasMany('App\AssignedTeacher');
    }
    public function subjects($value = '')
    {
        return $this->hasMany('App\Subject');
    }
}
