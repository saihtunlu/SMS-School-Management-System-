<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailCategory extends Model
{
    public function mails($value = '')
    {
        return $this->hasMany('App\MailCategoried');
    }
    public function user($value = '')
    {
        return $this->belongsTo('App\User');
    }
}
