<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatFile extends Model
{
    public function message($value = '')
    {
        return $this->belongsTo('App\Message');
    }
}
