<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemType extends Model
{
	protected $table = 'problem_types';

    public function WorkOrders()
    {
    	return $this->hasOne('App\WorkOrder');
    }
}
