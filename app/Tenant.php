<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    public function User()
    {
    	return $this->hasOne('App\User');
    }

    public function Property()
    {
    	return $this->hasOne('App\Property');
    }

    public function WorkOrder()
    {
    	return $this->hasMany('App\WorkOrder');
    }
}
