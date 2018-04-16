<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem;
use Illuminate\Support\Facades\Session;

use Log;
use Storage;
use Mail;
use Response;


use App\Helpers\Helper;
use App\Http\Requests;
use App\Tenant;
use App\Role;
use App\Insurance;
use App\Property;

class InsuranceController extends Controller
{
	public function __construct()
	{
		
	}

	public function update(Insurance $insurance, Request $request)
	{
		$this->validate($request, [ 
			'insurance_cert' => 'mimes:pdf',
			'liability_end' => 'required'
			]);        

		if ($insurance->tempfile != null || $insurance->tempfile2 != null) {
			if ($request->tenantUpload == 'accept') {

				if ($insurance->tempfile != null)
				{
					if ($insurance->combined_file)
					{
					$request->typeSelect = 'both';
					}
					else
					{
					$request->typeSelect = 'a25';
					}
					InsuranceController::processInsuranceFile($insurance->tempfile, $insurance, $request); 
				}
				if ($insurance->tempfile2 != null)
				{
					$request->typeSelect = 'a28';
					InsuranceController::processInsuranceFile($insurance->tempfile2, $insurance, $request); 
				}


			}
			//Reject tenant original upload need add a message back to the tenant.
			else {
				$insurance->rejection_msg = $request->rejection_msg;
				$insurance->combined = false;
				$insurance->save();
				Helper::sendInsuranceNotice($insurance->Tenant, 'reject');
				Storage::delete($insurance->filepath.$insurance->tempfile);
			}
			$insurance->tempfile = null;
			$insurance->tempfile2 = null;
			$insurance->rejection_msg = null;
		}
		else if ($request->insurance_cert != null) {
			$fname = 'ins-'.$insurance->Tenant->tenant_system_id.'-'.date('ymd-His', strtotime(\Carbon\Carbon::now())).'.pdf';
			if (InsuranceController::processInsuranceFile($fname, $insurance, $request)) {
				$file = $request->insurance_cert;
				Storage::put($insurance->filepath.$fname, file_get_contents($file));
			}
		}
		
		if ($request->workerscomp_applicable == 'N') {
			$insurance->workerscomp_applicable = false;
		}
		else {
			$insurance->workerscomp_applicable = true;
		}

		$insurance->liability_end = $request->liability_end;
		$insurance->note = $request->note;
		if ($insurance->liability_end > date("Y-m-d")) {
			$insurance->expired = false;
		} 
		if ($request->compliant == 'on')
		{
			$insurance->compliant = true;
		}
		else
		{
			$insurance->compliant = false;
		}
		if ($request->auto_notice == 'on')
		{
			$insurance->auto_notice = true;
		}
		else
		{
			$insurance->auto_notice = false;
		}
		$insurance->save();

		return back();
	}

	public function upload($token)
	{
		return view('insurance.upload', compact('token'));
	}

	public function save(Request $request)
	{
		$this->validate($request, [ 
			'insurance_cert' => 'required|mimes:pdf', 
			'tenant_system_id' => 'required',
			'form_type' => 'required'
			]);

		$tenant = Tenant::where('tenant_system_id', $request->tenant_system_id)->first();
		if($tenant != null && $tenant->Insurance->upload_token == $request->token)
		{
			$fname = 'ins-'.$tenant->tenant_system_id.'-'.date('ymd-His', strtotime(\Carbon\Carbon::now())).'.pdf';
			$file = $request->file('insurance_cert');
			Storage::put($tenant->insurance->filepath.$fname, file_get_contents($file));
			if ($request->form_type == 'a25')
			{
				$tenant->Insurance->tempfile = $fname;
				
			}
			elseif ($request->form_type == 'a28') 
			{
				$tenant->Insurance->tempfile2 = $fname;
			}
			else
			{
				$tenant->Insurance->tempfile = $fname;
				$tenant->Insurance->combined_file = true;
			}
			$tenant->Insurance->save();

			$role = Role::where('name','insurance-admin')->first();
			if ($role != null) 
			{
				$users = $role->users()->get();
				foreach ($users as $user) 
				{
					$emails[] = $user->email;
				}
			}

			if (!empty($emails)) 
			{
				Log::info('Insurance upload notification e-mail sent to',[$emails]);
				Mail::queue('email.uploadedcert',compact('tenant'), function ($message) use ($emails) {
					$message->from('davispartners@ejcustom.com', 'EJCustom');
					$message->subject('New Insurance Upload');
					$message->to($emails);
				});
			}

            Session::flash('success', 'Thank You, Your upload was successful');
			return redirect('upload/insurance/'.$tenant->Insurance->upload_token.'?tenant_system_id='.$tenant->tenant_system_id);
		}
		return view('errors.upload');
	}

	public function savereq(Request $request)
	{
		if ($request->type == 'tenant') {
			$entity = Tenant::find($request->id);
		}
		elseif ($request->type == 'property') {
			$entity = Property::find($request->id);
		}

		$entity->req_liability_single_limit = $request->req_liability_single_limit;
		$entity->req_liability_combined_limit = $request->req_liability_combined_limit;
		$entity->req_auto_limit = $request->req_auto_limit;
		$entity->req_umbrella_limit = $request->req_umbrella_limit;
		$entity->req_workerscomp_limit = $request->req_workerscomp_limit;
		$entity->save();

		return back();
	}

	public function processInsuranceFile($fname, $insurance, $request)
	{
		
		$used = false;
		// Associate the file name of the insurance certificate 
		// with the various possible insurance types.
		// first determines file type
		
		if ($request->typeSelect == 'a25') {
			$insurance->liability_filename = $fname;
			$insurance->combined_file = false;
			$used = true;
		}
		elseif ($request->typeSelect == 'both'){
			$insurance->liability_filename = $fname;
			$insurance->combined_file = true;
			$used = true;
		}
		elseif ($request->typeSelect == 'a28') {
			$insurance->endorsement_filename = $fname;
			$used = true;
		}
		$insurance->save();
		return $used;
	}

	public function response(Insurance $insurance)
	{
		return Response::json($insurance);
	}
}
