<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Excel;

use App\Http\Requests;
use App\ProblemType;
use App\WorkOrder;
use App\Tenant;
use App\User;
use App\Property;
use App\Owner;
use App\Group;


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

    //Checks if the property id entered is valid 
    //if not then the list of all properties is displayed.
    public function showid(Request $request)
    {
    	$property = Property::where('property_system_id',$request->property_system_id)->first();
        $group = Group::where('group_system_id', $request->property_system_id)->first();
		if ($group != null) 
        {
            return redirect('/group/'.$group->id);
        }
        elseif ($property != null) 
        {
    		
    	    
    		return PropertyController::show($property);
    	}
    	else 
        {
    		return PropertyController::proplist();
    	}
    }

    //Displays a list of all the properties
    public function proplist()
    {
        $properties = Property::orderBy('name')->get();
        return view('property.viewlist',compact('properties'));
    }

    //Shows an individual property to the user
    public function show(Property $property)
    {
    	$property->load('owner');
        $tenants = collect();
        foreach ($property->Tenants as $tenant) 
        {               
            $tenants->prepend($tenant);
        }
        $tenants = $tenants->sortBy('company_name');

        $workorders = collect();
        foreach ($tenants as $tenant)
        {
            foreach ($tenant->WorkOrder as $workorder)
            {
                $workorders->prepend($workorder);
            }
        }
        $workorders = $workorders->sortByDesc('created_at');
    	return view('property.show', compact('property', 'tenants','workorders'));
    }

    //Creates the form to add a new property
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

    //validates and saves a new property
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


    //takes an .xls file to import in a mass amount of properties at once. 
    public function import(Request $request)
    {
        $file = $request->propertyimport;
        $file->move('tmp/','import.xls');

        Excel::load('tmp/import.xls', function($reader) {
           
            $reader->each(function($sheet){
                $sheet->each(function($row){
                    
                    $property = Property::firstOrCreate([
                        'name' => $row->name,
                        'property_system_id' => $row->property_system_id,
                        'address' => $row->address,
                        'city' => $row->city,
                        'state' => $row->state,
                        'zip' => $row->zip,
                        'owner_id' => $row->owner_id,
                        'req_liability_single_limit' => $row->req_liability_single_limit,
                        'req_liability_combined_limit' => $row->req_liability_combined_limit,
                        'req_auto_limit' => $row->req_auto_limit,
                        'req_umbrella_limit' => $row->req_umbrella_limit,
                        'req_workerscomp_limit' => $row->req_workerscomp_limit
                    ]);
                    
                });
            });

        });

        return redirect('/property/list');
    }

}
