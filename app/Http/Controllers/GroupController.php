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
    		return GroupController::add();
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
        	$group->Property()->attach($property);
        }
    	return redirect('group/'.$group->id.'/manage');
    }

    public function remove(Group $group, Request $request)
    {
    	$property = Property::find($request->prop_id);
    	$group->Property()->detach($property);
    	return redirect('group/'.$group->id.'/manage');
    }

}
