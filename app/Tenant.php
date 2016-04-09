<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    public function Users()
    {
    	return $this->belongsTo('App\User');
    }

    public function Properties()
    {
    	return $this->belongsTo('App\Property');
    }

    public function WorkOrders()
    {
    	return $this->hasMany('App\WorkOrder');
    }
}
