<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Role extends Model
{

    public function users($value = '')
    {
        return $this->belongsTo('App\User');
    }
    public function role($value = '')
    {
        return $this->belongsTo('App\Role');
    }
    protected $table = 'user_roles';
}
