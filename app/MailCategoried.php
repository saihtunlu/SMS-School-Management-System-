<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailCategoried extends Model
{
    public function mail($value = '')
    {
        return $this->belongsTo('App\EMail', 'mail_id');
    }
    public function category($value = '')
    {
        return $this->belongsTo('App\MailCategory', 'mail_category_id');
    }
}
