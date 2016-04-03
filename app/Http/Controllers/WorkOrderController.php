<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ProblemType;

class WorkOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function submit()
    {
    	$typeList = array();
    	$problemTypes = ProblemType::all();
    	foreach ($problemTypes as $problemType) 
    	{
    		array_push($typeList, $problemType->type);
    	}
    	 
        return view('wo.submit', compact('problemTypes'));
    }
}
