<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ProblemType;
use App\WorkOrder;
use App\Tenant;
use App\User;
use App\Property;
use App\Owner;


class PropertyController extends Controller
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

    public function showid(Request $request)
    {
    	$property = Property::where('property_system_id',$request->property_system_id)->first();
		
			if ($property != null) {
			$property->load(['tenants' => function($query) {
				$query->orderBy('company_name');
			}]);
    	    
    		return PropertyController::show($property);
    	}
    	else {
    		return PropertyController::proplist();
    	}
    }

    public function proplist()
    {
        $properties = Property::orderBy('name')->get();
        return view('property.viewlist',compact('properties'));
    }

    public function show(Property $property)
    {
    	
    	$property->load('owner');
    	return view('property.show', compact('property'));
    	
    }

    public function add()
    {
    	
    	$users = User::orderBy('name')->get();
    	$managers = array();
    	foreach ($users as $user)
    	{
    		if ($user->hasRole('manager'))
    		{
    			$managers[] = $user;
    		}
    	}
				
    	$owners = Owner::orderBy('name')->get();

    	return view('property.add', compact('managers','owners'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'owner_id'=> 'required',
            'manager'=> 'required',
            'name'=> 'required',
            'property_system_id'=> 'required',
            'address'=> 'required',
            'city'=> 'required',
            'state'=> 'required',
            'zip'=> 'required',
            ]);

    	$property = Property::create([
    		'name' => $request->name,
    		'property_system_id' => $request->property_system_id,
    		'address' => $request->address,
    		'city' => $request->city,
    		'state' => $request->state,
    		'zip' => $request->zip,
    		'owner_id' => $request->owner_id
		]);

        $property->Users()->attach(User::find($request->manager));

		return redirect('/property/'.$property->id);
    }

}
