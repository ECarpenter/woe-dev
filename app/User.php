<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','timezone','company_name','job_title', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    use EntrustUserTrait;

    public function Properties()
    {
        return $this->belongsToMany('App\Property');
    }

    public function Property()
    {
        //Checks to see if user is a tenant or manager
        if ($this->Tenant()->first() == null)
        {
            return $this->Properties()->first();
        }   
        else
        {
            return $this->Tenant->Property;
        }
    }

    public function Tenant()
    {
        return $this->belongsTo('App\Tenant');
    }

    public function WorkOrder()
    {
        return $this->hasMany('App\WorkOrder');
    }

    public function Post()
    {
        return $this->hasMany('App\Post');
    } 
    public static function Managers()
    {
        $users = User::orderBy('name')->get();
        $managers = array();
        foreach ($users as $user)
        {
            if ($user->hasRole('manager'))
            {
                $managers[] = $user;
            }
        }

        return $managers;
    }
 
}
