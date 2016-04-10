<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    public function Tenant()
    {
    	return $this->belongsTo('App\Tenant');
    }

    public function ProblemType()
    {
    	return $this->hasOne('App\ProblemType');
    }
}
