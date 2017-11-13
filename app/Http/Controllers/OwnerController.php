<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Helper;
use Response;
use Log;

use App\Http\Requests;
use App\Remit;
use App\Vendor;
use App\Property;
use App\User;
Use App\Owner;

class OwnerController extends Controller
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

	public function add(Request $request)
	{
		$this->validate($request, [
			'owner_name'=>'required',
			'ap_email'=>'required',
			'ar_email'=>'required',
		]);

		$owner = new Owner;
		$owner->name = $request->owner_name;
		$owner->ap_email = $request->ap_email;
		$owner->ar_email = $request->ar_email;
		$owner->save();

		return back();

	}
}