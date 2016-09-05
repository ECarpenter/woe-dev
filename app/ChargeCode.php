<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeCode extends Model
{
    public function Owner()
    {
         return $this->belongsTo('App\Owner');
    }
}
