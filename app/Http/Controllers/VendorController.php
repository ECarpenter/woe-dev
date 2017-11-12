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

class VendorController extends Controller
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
			'system_id'=>'required',
			'payable_to'=>'required',
			'address'=>'required',
			'city'=>'required',
			'state'=>'required',
			'zip'=>'required'
		]);
		$vendor = new Vendor;

		$vendor->system_id = $request->system_id;
		$vendor->payable_to = $request->payable_to;
		$vendor->address = $request->address;
		$vendor->city = $request->city;
		$vendor->state = $request->state;
		$vendor->zip = $request->zip;
		$vendor->remit = true;
		$vendor->address_secondline = $request->address_secondline;
		$vendor->save();

		return back();

	}

	public function remitdisplay()
	{
		$remits = Remit::where('remit','=', '1')->orderBy('payable_to','asc')->get();
		//$remits = Remit::all();
		return Response::json($remits);
	}

}