<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoreFile extends Model
{
    public function message($value = '')
    {
        return $this->belongsTo('App\Message', 'message_id');
    }
}
