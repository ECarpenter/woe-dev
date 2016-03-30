<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    public function User()
    {
    	return $this->hasOne('App\User');
    }
}
