<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
	protected $fillable = [
        'billing_description', 'job_cost', 'amount_billed', 'cos_filename'
    ];

    public function Tenant()
    {
    	return $this->belongsTo('App\Tenant');
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function ProblemType()
    {
    	return $this->belongsTo('App\ProblemType','problem_id');
    }

    public function Post()
    {
        return $this->hasMany('App\Post');
    }

    public function Managers()
    {
    	$managers = array();

    	foreach ($this->Property()->Users as $user) {	
    		if($user->hasRole('manager')) {
    			$managers[] = $user;
    		}
    	}
    	
    	return $managers;
    }

    public function Property()
    {

        if($this->tenant_id == 0)
        {
            return $this->User->Properties()->first();
        }
        else
        {
            return $this->Tenant->Property;
        }
    }

    public function Company_Name()
    {
        if($this->tenant_id == 0)
        {
            return $this->User->company_name;
        }
        else
        {
            return $this->Tenant->company_name;
        }
    }

    public function Unit()
    {
        if($this->tenant_id == 0)
        {
            return 'unknown';
        }
        else
        {
            return $this->Tenant->unit;
        }
    }
}
