<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $table = 'group';

    public function Property()
    {
    	return $this->belongsToMany('App\Property');
    }
}
