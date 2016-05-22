<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Group;
use App\Property;

class GroupController extends Controller
{
    public function add()
    {
    	return view('group.add');
    }

    public function save(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'group_system_id' => 'required'
    		]);

    	$group = new Group;
    	$group->name = $request->name;
    	$group->group_system_id = $request->group_system_id;
    	$group->save();

    	return redirect('group/'.$group->id.'/manage');
    }

    public function showid(Request $request)
    {
    	$group = Group::where('group_system_id',$request->group_system_id)->first();
		if ($group != null) 
        {    	    
    		return GroupController::manage($group);
    	}
    	else
    	{
    		return GroupController::grouplist();
    	}
    }

    public function manage(Group $group)
    {
    	return view('group.manage',compact('group'));
    }

    public function update(Group $group, Request $request)
    {
		
    	$property = Property::where('property_system_id',$request->property_system_id)->first();

		if ($property != null) 
        {
        	$group->Properties()->attach($property);
        }
    	return redirect('group/'.$group->id.'/manage');
    }

    public function remove(Group $group, Request $request)
    {
    	$property = Property::find($request->prop_id);
    	$group->Properties()->detach($property);
    	return redirect('group/'.$group->id.'/manage');
    }

    public function grouplist()
    {
        $groups = Group::orderBy('name')->get();
        return view('group.viewlist',compact('groups'));
    }

    public function show(Group $group)
    {
        $group->load('properties', 'properties.owner');

        $tenants = collect();
        foreach ($group->Properties as $property) 
        {
            foreach ($property->Tenants as $tenant) 
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

        return view('group.show', compact('group','tenants', 'workorders'));
    }


}
