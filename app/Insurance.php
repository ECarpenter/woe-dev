<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'insurance';

    public function Tenant()
    {
    	return $this->belongsTo('App\Tenant');
    }
}
