<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    public function User()
    {
    	return $this->hasOne('App\User');
    }

    public function Property()
    {
    	return $this->hasMany('App\Property');
    }}
