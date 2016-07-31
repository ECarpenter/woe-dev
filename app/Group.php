<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $table = 'group';

    public function Properties()
    {
    	return $this->belongsToMany('App\Property');
    }

    public function  getPropertyAttribute()
    {
    	return $this->properties->first();
    }
}


