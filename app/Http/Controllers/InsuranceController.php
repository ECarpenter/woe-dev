<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Mail;


use App\Helpers\Helper;
use App\Http\Requests;
use App\Tenant;
use App\Role;
use App\Insurance;

class InsuranceController extends Controller
{
    public function __construct()
    {
        
    }

    public function update(Insurance $insurance, Request $request)
    {
        $this->validate($request, [ 
            'insurance_cert' => 'mimes:pdf'
            ]);        

        if ($insurance->tempfile != null) {
            if ($request->tenantUpload == 'accept') {
                
                (InsuranceController::processInsuranceFile($insurance->tempfile, $insurance, $request)); 
            }
            //Reject tenant original upload need add a message back to the tenant.
            else {
                $insurance->rejection_msg = $request->rejection_msg;
                $insurance->save();
                Helper::sendInsuranceNotice($insurance->Tenant, 'reject');
                Storage::delete($insurance->filepath.$insurance->tempfile);
            }
            $insurance->tempfile = null;
            $insurance->rejection_msg = null;
        }
        else if ($request->insurance_cert != null) {
            $fname = 'ins-'.$insurance->Tenant->tenant_system_id.'-'.date('ymd-His', strtotime(\Carbon\Carbon::now())).'.pdf';
            if (InsuranceController::processInsuranceFile($fname, $insurance, $request)) {
                $file = $request->insurance_cert;
                $file->move('files/insurance/', $fname);
            }
        }
        

        
    	$insurance->liability_start = $request->liability_start;
    	$insurance->liability_end = $request->liability_end;
    	$insurance->liability_single_limit = $request->liability_single_limit;
    	$insurance->liability_combined_limit = $request->liability_combined_limit;
    	$insurance->umbrella_start = $request->umbrella_start;
    	$insurance->umbrella_end = $request->umbrella_end;
    	$insurance->umbrella_limit = $request->umbrella_limit;
    	$insurance->auto_start = $request->auto_start;
    	$insurance->auto_end = $request->auto_end;
    	$insurance->auto_limit = $request->auto_limit;
		$insurance->workerscomp_start = $request->workerscomp_start;
    	$insurance->workerscomp_end = $request->workerscomp_end;
    	$insurance->workerscomp_limit = $request->workerscomp_limit;
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
            'tenant_system_id' => 'required'
            ]);

        $tenant = Tenant::where('tenant_system_id', $request->tenant_system_id)->first();
        if($tenant != null && $tenant->Insurance->upload_token == $request->token)
        {
            $fname = 'ins-'.$tenant->tenant_system_id.'-'.date('ymd-His', strtotime(\Carbon\Carbon::now())).'.pdf';
            $file = $request->insurance_cert;
            $file->move($tenant->Insurance->filepath, $fname);
            $tenant->Insurance->tempfile = $fname;
            $tenant->Insurance->save();

            $users = Role::where('name','insurance')->first()->users()->get();
            foreach ($users as $user) {
                $emails[] = $user->email;
            }

            if (!empty($emails)) {
                Mail::queue('email.uploadedcert',compact('tenant'), function ($message) use ($emails) {
                    $message->from('davispartners@ejcustom.com', 'EJCustom');
                    $message->subject('New Insurance Upload');
                    $message->to($emails);
                });
            }

            return view('insurance.thankyou');
        }
        return view('errors.upload');
    }

    public function processInsuranceFile($fname, $insurance, $request)
    {
        
        $used = false;
        // Associate the file name of the insurance certificate 
        // with the various possible insurance types.
        // first determines file type
        
        if ($request->typeSelect == 'certificate') {
            if ($request->liability == 'Y') {
                $insurance->liability_filename = $fname;
                $used = true;
            }
            if ($request->auto == 'Y') {
                $insurance->auto_filename = $fname;
                $used = true;
            }
            if ($request->workerscomp == 'Y') {
                $insurance->workerscomp_filename = $fname;
                $used = true;
            }
            if ($request->umbrella == 'Y') {
                $insurance->umbrella_filename = $fname;
                $used = true;
            }
        }
        elseif ($request->typeSelect == 'endorsement'){
            $insurance->endorsement_filename = $fname;
            $used = true;
        }

        return $used;
    }
}
