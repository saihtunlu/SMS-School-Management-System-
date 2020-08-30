<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Work_History extends Model
{
    public function employee($value = '')
    {
        return $this->belongsTo('App\Employee');
    }

    public function student($value = '')
    {
        return $this->belongsTo('App\Student');
    }
    protected $table = 'work_histories';
}
