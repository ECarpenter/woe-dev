<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public function Tenants()
    {
    	return $this->hasMany('App\Tenant');
    }

    public function Users()
    {
        return $this->belongsToMany('App\User');
    }
    public function WorkOrders()
    {
    	return $this->hasManyThrough('App\WorkOrder', 'App\User');
    }
}
