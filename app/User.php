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
        'name', 'email', 'password','timezone','company_name','job_title'
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
        return $this->Properties()->first();
    }

    public function Tenant()
    {
        return $this->belongsTo('App\Tenant');
    }

    public function WorkOrder()
    {
        return $this->hasMany('App\WorkOrder');
    }
 
}
