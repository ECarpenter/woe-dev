<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remit extends Model
{
	protected $table='vendors';

    public function Property()
    {
    	return $this->hasMany('App\Property');
    }
}
