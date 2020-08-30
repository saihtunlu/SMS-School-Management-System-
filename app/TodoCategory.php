<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoCategory extends Model
{
    public function todo($value = '')
    {
        return $this->hasMany('App\TodoCategoried');
    }
    public function user($value = '')
    {
        return $this->hasMany('App\User');
    }
}
