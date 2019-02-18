<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use App\User;
use App\Tenant;
use App\Property;
use App\WorkOrder;

class UserController extends Controller
{
	public function add()
	{
		$roles = Role::all();
		return view('user.add', compact('roles'));
	}

	public function save(Request $request)
	{
		$user = User::create([
			'name' => $request['name'],
			'email' => $request['email'],
			'password' => bcrypt($request['password']),
			'timezone' => "America/Los_Angeles"
		]);

		$role = Role::find($request->role);
		$user->Roles()->attach($role);

		return redirect('/home');
	}

	public function userlist()
	{
		$users = User::where('active',true)->orderBy('name')->get()->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE);

		return view('user.viewlist',compact('users'));
	}

	public function changePassword()
	{
		return view('auth.passwords.change');
	}

	public function savePassword(Request $request)
	{
		$this->validate($request, [
			'old_password' => 'required',
			'password' =>'required|confirmed',
		]);

		$passwordchanged = false;
		$user = \Auth::user();
		if (password_verify($request->old_password, $user->password))
		{
			$user->password = bcrypt($request->password);
			$user->save();
			$passwordchanged = true;
		}
		return view('passwordchanged', compact('passwordchanged'));
	}

	public function displayverifyuser(User $user)
	{
		$user_property_id = $user->Property()->id;

		$properties_all = Property::orderBy('name')->get();
		$properties = collect();
		$tenants = collect();
		
		foreach ($properties_all as $property)
		{
			if (\Auth::user()->can('view-all') || $property->canAccess())
			{	
				$properties->prepend($property);
				foreach ($property->Tenants as $tenant) 
				{   
					if($tenant->active == '1') 
					{           
						$tenants->prepend($tenant);
					}
				}
			}
		}
		return response()->json(['properties' => $properties, 'tenants' => $tenants, 'user' => $user, 'current_property' => $user_property_id]);
	}

	public function updateverifyuser(Request $request)
	{
		$this->validate($request, [ 
			'tenant' => 'required'
			]);

		$user = User::where('id', '=', $request->user_id)->first();
		$tenant = Tenant::where('id', '=', $request->tenant)->first();
		$user->tenant_id = $request->tenant;
		$user->company_name = $user->tenant->company_name;
		$user->verified = true;
		if ($user->Property()->id != $request->property)
		{
			$user->Properties()->detach($user->Property()->id);
			$user->Properties()->attach($tenant->property->id);
		}
		$user->save();

		$workorders = WorkOrder::where('user_id', '=', $user->id)->get();
		foreach ($workorders as $workorder) 
		{
			$workorder->tenant_id = $user->tenant_id;
			$workorder->save();
		}

		
		return view('user.show',compact('user'));
	}

	public function show(User $user)
	{
		$user->load('tenant',  'workorder');

		return view('user.show',compact('user'));
	}

}
