<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Language extends Model
{
    public function language($value = '')
    {
        return $this->belongsTo('App\Language');
    }
    public function employee($value = '')
    {
        return $this->belongsTo('App\Employee');
    }
}
