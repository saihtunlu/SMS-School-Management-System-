<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeDetail extends Model
{
    public function grade_group($value = '')
    {
        return $this->belongsTo('App\GradeGroup');
    }
    public function subject($value = '')
    {
        return $this->belongsTo('App\Subject');
    }
}
