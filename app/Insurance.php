<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'insurance';
    protected $dates = ['created_at', 'updated_at', 'last_notice_sent'];

    public function Tenant()
    {
    	return $this->belongsTo('App\Tenant');
    }
}
