<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultPermission extends Model
{
    public function roles($value = '')
    {
        return $this->belongsTo('App\Role', 'role_id');
    }
}
