<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'phone', 'class_id', 'father_id', 'user_id'];

    public function user($value = '')
    {
        return $this->belongsTo('App\User');
    }
    public function year($value = '')
    {
        return $this->belongsTo('App\AcademicYear');
    }
    public function father($value = '')
    {
        return $this->belongsTo('App\Father');
    }
    public function mother($value = '')
    {
        return $this->belongsTo('App\Mother');
    }
    public function previous_schools($value = '')
    {
        return $this->hasMany('App\Previous_School');
    }
    public function attendances($value = '')
    {
        return $this->hasMany('App\AttendanceGroup');
    }
    public function address($value = '')
    {
        return $this->belongsTo('App\Address');
    }
    public function classroom($value = '')
    {
        return $this->belongsTo('App\Classroom', 'classroom_id');
    }
}
