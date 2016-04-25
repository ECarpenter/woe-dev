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
    	return $this->belongsTo('App\ProblemType','problem_id');
    }

    public function Manager()
    {
    	$managers = array();

    	

    	foreach ($this->Tenant->Property->Users as $user) {
    		
    		if($user->hasRole('manager'))
    		{

    			$managers[] = $user;
    		}
    	}
    	
    	return $managers;
    }
}
