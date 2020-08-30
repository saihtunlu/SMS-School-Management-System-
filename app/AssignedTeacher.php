<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedTeacher extends Model
{
    public function teacher($value = '')
    {
        return $this->belongsTo('App\Employee', 'employee_id');
    }
    public function classroom($value = '')
    {
        return $this->belongsTo('App\Classroom');
    }
}
