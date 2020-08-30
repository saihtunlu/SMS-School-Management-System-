<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function user_role($value = '')
    {
        return $this->hasMany('App\User_Role');
    }
    public function permissions($value = '')
    {
        return $this->hasMany('App\DefaultPermission');
    }
}
