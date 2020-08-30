<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EMail extends Model
{
    use SoftDeletes;
    public function to($value = '')
    {
        return $this->hasMany('App\MailTo', 'mail_id');
    }
    public function cc($value = '')
    {
        return $this->hasMany('App\MailCC', 'mail_id');
    }
    public function bcc($value = '')
    {
        return $this->hasMany('App\MailBCC', 'mail_id');
    }
    public function categories($value = '')
    {
        return $this->hasMany('App\MailCategoried', 'mail_id');
    }
    public function user($value = '')
    {
        return $this->belongsTo('App\User');
    }

    protected $table = 'mails';
}
