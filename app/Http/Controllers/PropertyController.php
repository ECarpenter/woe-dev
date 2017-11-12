<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Excel;
use Helper;
use Response;
use Log;

use App\Http\Requests;
use App\ProblemType;
use App\WorkOrder;
use App\Tenant;
use App\User;
use App\Property;
use App\Owner;
use App\Group;
use App\Remit;


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
		if (!\Auth::user()->can('view-all'))
		{
			$count = 0;
			foreach ($properties as $property)
			{
				if (!$property->canAccess())
				{	
					$properties->forget($count);
				}
				$count++;
			}
		}
		return view('property.viewlist',compact('properties'));
	}

	//Shows an individual property to the user
	public function show(Property $property)
	{
		if (\Auth::user()->can('view-all') || $property->canAccess())
		{
			$property->load('owner');
			$tenants = collect();
			foreach ($property->Tenants as $tenant) 
			{   
				if($tenant->active) 
				{           
					$tenants->prepend($tenant);
				}
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
		else
		{
			return PropertyController::proplist();
		}
	}

	//Creates the form to add a new property
	public function add()
	{
		
		
		$managers = User::Managers();
				
		$owners = Owner::orderBy('name')->get();

		$remits = Remit::where('remit','=', '1')->orderBy('payable_to','asc')->get();

		return view('property.add', compact('managers','owners','remits'));
	}

	public function update(Property $property, Request $request)
	{
		$property->name = $request->property_name;
		$property->address = $request->address;
		$property->city = $request->city;
		$property->state = $request->state;
		$property->zip = $request->zip;
		$property->owner_id = $request->owner;
		if ($request->active_switch == 'false')
		{
			$property->active = false;
		}
		else
		{
			$property->active = true;
		}

		$property->save();

		return redirect('/property/'.$property->id);
	}

	//validates and saves a new property
	public function save(Request $request)
	{
		$this->validate($request, [
			'owner_id'=> 'required',
			'remit' => 'required',
			'manager'=> 'required',
			'name'=> 'required',
			'property_system_id'=> 'required',
			'address'=> 'required',
			'city'=> 'required',
			'state'=> 'required',
			'zip'=> 'required',
			'req_liability_single_limit' => 'required',
			'req_liability_combined_limit' => 'required',
			'req_auto_limit' => 'required',
			'req_umbrella_limit' => 'required',
			'req_workerscomp_limit' => 'required',
		]);

		$property = Property::create([
			'name' => $request->name,
			'property_system_id' => $request->property_system_id,
			'address' => $request->address,
			'city' => $request->city,
			'state' => $request->state,
			'zip' => $request->zip,
			'owner_id' => $request->owner_id,
			'req_liability_single_limit' => $request->req_liability_single_limit,
			'req_liability_combined_limit' => $request->req_liability_combined_limit,
			'req_auto_limit' => $request->req_auto_limit,
			'req_umbrella_limit' => $request->req_umbrella_limit,
			'req_workerscomp_limit' => $request->req_workerscomp_limit,
			'primary_manager' => $request->manager,
			'remit_id' => $request->remit,
		]);

		$property->Users()->attach(User::find($request->manager));

		return redirect('/property/'.$property->id);
	}

	public function changeactive(Property $property)
	{

		if ($property->active)
		{
			$property->active = false;
		}
		else
		{
			$property->active = true;
		}
		$property->save();
		return redirect('/property/list');
	}


	//takes an .xls file to import in a mass amount of properties at once. 
	public function import(Request $request)
	{
		$file = $request->propertyimport;
		$file->move('tmp/','import.xls');

		Helper::importProperty('tmp/import.xls');

		return redirect('/property/list');
	}

	public function remit(Property $property, Request $request)
	{
		$property->remit_id = $request->remit;
		$property->save();
		return back();
	}



	public function response(Property $property)
	{
		$owners = Owner::all();
		return Response::json(['property'=>$property, 'owners'=>$owners]);
	}
	

	//fix to include tenants, seperate out then recombine
	public function user(Property $property, Request $request)
	{
		$this->validate($request, [
			'property_user_multiselect'=> 'required',
			'primary_manager'=> 'required',
			]);

		$property->Users()->sync($request->property_user_multiselect);

		$primary_included = false;

		foreach ($request->property_user_multiselect as $selected) {
			if ($selected == $request->primary_manager)
			{
				$primary_included = true;
			}
		}

		if (!$primary_included)
		{
			$property->Users()->attach(User::find($request->primary_manager));
		}

		$property->primary_manager = $request->primary_manager;
		$property->save();

		return redirect('/property/'.$property->id);
	}

	public function multiselectdisplay(Property $property)
	{
		$managers = User::Managers();
		usort($managers, array($this,"cmp"));
		$selected = collect();

		foreach($managers as $key =>$manager)
		{
			if ($property->hasUser($manager->id))
			{
				$selected->push($manager);
				unset($managers[$key]);
			}

		}

		return Response()->json(['managers'=>$managers,'selected'=>$selected, 'primary_manager'=>$property->primary_manager]);
		
	}

	public function cmp($a, $b)
	{
    	return strcmp($a->name, $b->name);
	}

}
