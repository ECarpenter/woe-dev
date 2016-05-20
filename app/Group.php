<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function Property()
    {
    	return $this->hasMany('App\Property');
    }
}
