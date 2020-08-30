<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function items($value = '')
    {
        return $this->hasMany('App\InvoiceItem');
    }
}
