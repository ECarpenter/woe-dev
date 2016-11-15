<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Property extends Model
{

    protected $fillable = ['name', 'property_system_id', 'address', 'city','state','zip','owner_id','active', 'req_liability_single_limit', 'req_liability_combined_limit', 'req_auto_limit', 'req_umbrella_limit', 'req_workerscomp_limit','remit_id', 'primary_manager'
    ];

    public function Tenants()
    {
    	return $this->hasMany('App\Tenant');
    }

    public function Vendors()
    {
        return $this->hasMany('App\Vendor');
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

    public function PrimaryManager()
    {   
        return User::find($this->primary_manager);
    }

    public function canAccess()
    {   
        if (\Auth::user()->can('view-all'))
        {
            return true;
        }
        else if ($this->hasUser(\Auth::user()->id))
        {
            return true;       
        }
        return false;
    }

    public function Group()
    {
        return $this->belongsToMany('App\Group');
    }

    public function Remit()
    {
        return $this->belongsTo('App\Remit');
    }

    public function hasUser($userid)
    {
        foreach ($this->Users as $user) 
        { 
            if ($user->id == $userid)
            {

                return true;
            }
        }
        return false;

    }
}
