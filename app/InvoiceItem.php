<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    public function invoice($value = '')
    {
        return $this->belongsTo('App\invoice');
    }
}
