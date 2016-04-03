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
        'name', 'email', 'password',
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

    // public function Role()
    // {
    //     return $this->belongsToMany('App\Role');    
    // }

    public function Property()
    {
        return $this->belongsToMany('App\Property');
    }
 
}
