<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemType extends Model
{


    public function WorkOrder()
    {
    	return $this->belongsTo('App\WorkOrder');
    }
}
