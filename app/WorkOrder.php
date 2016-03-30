<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    public function Tenant()
    {
    	return $this->hasOne('App\Tenant');
    }

    public function Property()
    {
    	return $this->hasOne('App\Property');
    }

    public function ProblemType()
    {
    	return $this->hasOne('App\ProblemType');
    }
}
