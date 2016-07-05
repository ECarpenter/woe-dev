<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use App\User;

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
		$users = User::orderByRaw('name COLLATE NOCASE')->get();

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

}
