<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    public function Property()
    {
    	return $this->hasMany('App\Property');
    }
}
