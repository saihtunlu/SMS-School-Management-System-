<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use SoftDeletes;

    public function assigned($value = '')
    {
        return $this->hasMany('App\AssignedTodo');
    }
    public function categories($value = '')
    {
        return $this->hasMany('App\TodoCategoried');
    }
}
