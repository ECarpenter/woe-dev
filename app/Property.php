<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{

    protected $fillable = ['name', 'property_system_id', 'address', 'city','state','zip','owner_id','active'
    ];

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

     public function Owner()
     {
         return $this->belongsTo('App\Owner');
     }

    public function Managers()
    {
        $managers = array();

        foreach ($this->Users as $user) {
            
            if($user->hasRole('manager'))
            {
                $managers[] = $user;
            }
        }

        return $managers;
    }

    public function Group()
    {
        return $this->hasMany('App\Group');
    }
}
