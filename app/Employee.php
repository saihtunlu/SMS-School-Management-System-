<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $fillable = [];

    public function user($value = '')
    {
        return $this->belongsTo('App\User');
    }
    public function classroom($value = '')
    {
        return $this->hasMany('App\AssignedTeacher');
    }
    public function subject($value = '')
    {
        return $this->hasOne('App\Subject');
    }
    public function address($value = '')
    {
        return $this->belongsTo('App\Address');
    }

    public function work_history($value = '')
    {
        return $this->hasMany('App\Work_History');
    }
    public function languages($value = '')
    {
        return $this->hasMany('App\User_Language');
    }
}
