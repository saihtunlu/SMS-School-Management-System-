<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedTodo extends Model
{
    use SoftDeletes;
    public function todo($value = '')
    {
        return $this->belongsTo('App\Todo');
    }
    public function employee($value = '')
    {
        return $this->belongsTo('App\User');
    }
}
