<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{

    public function User()
    {
    	return $this->belongsTo('App\User');
    }

    public function Property()
    {
    	return $this->belongsTo('App\Property');
    }

    public function WorkOrder()
    {
    	return $this->hasMany('App\WorkOrder');
    }

    public function Insurance()
    {
        return $this->hasOne('App\Insurance');
    }
}
