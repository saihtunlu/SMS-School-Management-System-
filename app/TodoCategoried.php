<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TodoCategoried extends Model
{
    use SoftDeletes;
    public function todo($value = '')
    {
        return $this->belongsTo('App\Todo');
    }
    public function category($value = '')
    {
        return $this->belongsTo('App\TodoCategory', 'todo_category_id');
    }
}
