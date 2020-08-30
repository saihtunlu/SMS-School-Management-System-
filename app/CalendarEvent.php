<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use SoftDeletes;
    public function categories($value = '')
    {
        return $this->hasOne('App\CalendarCategoried', 'calendarEvent_id');
    }
}
