<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    public function fromContact()
    {
        return $this->hasOne(User::class, 'id', 'from');
    }
    public function images($value = '')
    {
        return $this->hasMany('App\ChatFile');
    }
    public function files()
    {
        return $this->hasOne('App\MoreFile');
    }
}
