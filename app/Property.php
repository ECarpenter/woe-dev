<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public function Tenant()
    {
    	return $this->hasOne('App\Tenant');
    }

    public function Manager()
    {
    	return $this->hasMany('App\Manager');
    }

    public function WorkOrder()
    {
    	return $this->hasMany('App\WorkOrder');
    }
}
