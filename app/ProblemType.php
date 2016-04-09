<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemType extends Model
{


    public function WorkOrders()
    {
    	return $this->belongsTo('App\WorkOrder');
    }
}
