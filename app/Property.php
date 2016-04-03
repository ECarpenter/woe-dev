<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public function Tenant()
    {
    	return $this->hasMany('App\Tenant');
    }

    public function User()
    {
        return $this->belongsToMany('App\User');
    }
    public function WorkOrder()
    {
    	return $this->hasMany('App\WorkOrder');
    }
}
