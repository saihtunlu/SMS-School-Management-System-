<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Father extends Model
{
    protected $fillable = ['name', 'phone', 'NRC'];

    public function children($value = '')
    {
        return $this->hasMany('App\Student');
    }
}
