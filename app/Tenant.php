<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{

    protected $fillable = ['company_name','active','verified','tenant_system_id','unit'];

    public function User()
    {
    	return $this->hasMany('App\User');
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
