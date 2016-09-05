<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table='vendors';

    public function Properties()
    {
        return $this->belongsToMany('App\Property');
    }

}
