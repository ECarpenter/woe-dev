<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Insurance;

class InsuranceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Insurance $insurance, Request $request)
    {
    	$this->validate($request, [
    		]);
    	$insurance->liability_start = $request->liability_start;
    	$insurance->liability_end = $request->liability_end;
    	$insurance->liability_single_limit = $request->liability_single_limit;
    	$insurance->liability_combined_limit = $request->liability_combined_limit;
    	$insurance->umbrella_start = $request->umbrella_start;
    	$insurance->umbrella_end = $request->umbrella_end;
    	$insurance->umbrella_limit = $request->umbrella_limit;
    	$insurance->auto_start = $request->auto_start;
    	$insurance->auto_end = $request->auto_end;
    	$insurance->auto_limit = $request->auto_limit;
		$insurance->workerscomp_start = $request->workerscomp_start;
    	$insurance->workerscomp_end = $request->workerscomp_end;
    	$insurance->workerscomp_limit = $request->workerscomp_limit;
    	$insurance->save();

    	return back();
    }
}
