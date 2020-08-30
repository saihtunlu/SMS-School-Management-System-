<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecentChat extends Model
{
    public function Send_Messages($value = '')
    {
        return $this->hasMany(Message::class, 'from', 'auth_id');
    }
    public function Receive_Messages($value = '')
    {
        return $this->hasMany(Message::class, 'to', 'auth_id');
    }
    public function User($value = '')
    {
        return $this->belongsTo('App\User');
    }
}
