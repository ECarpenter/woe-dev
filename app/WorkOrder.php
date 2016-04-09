<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    public function Tenants()
    {
    	return $this->belongsTo('App\Tenant');
    }

    public function ProblemTypes()
    {
    	return $this->hasOne('App\ProblemType');
    }
}
