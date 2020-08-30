<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarCategoried extends Model
{
    public function event($value = '')
    {
        return $this->belongsTo('App\CalendarEvent');
    }
    public function category($value = '')
    {
        return $this->belongsTo('App\CalendarCategory', 'calendar_category_id');
    }
}
