<?php

namespace App\Http\Controllers;

use Excel;
use Log;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Helpers\Helper;
use App\WorkOrder;
use App\User;
use App\Tenant;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $workorders = collect();
        if(\Auth::user()->hasRole('tenant'))
        {
            $tempworkorders = WorkOrder::all();
            foreach ($tempworkorders as $workorder) 
            {
                if (\Auth::user()->tenant_id == 0)
                {
                    if ($workorder->user_id == \Auth::user()->id)
                    {
                        $workorders->prepend($workorder);
                    }
                }
                elseif ($workorder->Tenant->id == \Auth::user()->Tenant->id)
                {
                    $workorders->prepend($workorder);
                }   
                
            }
        }
        else
        {
            $tenants = Tenant::where('active', true)->orderBy('company_name')->get();
        
            $tenants = Helper::checkPermissions($tenants);
            foreach ($tenants as $tenant) 
            {
                $tempworkorders = $tenant->WorkOrder()->where('status', '!=','Closed')->get();
                foreach ($tempworkorders as $workorder) 
                {
                    $workorders->prepend($workorder);
                }
            }
        }

        $users = User::all();
        $users = Helper::CheckUserStatus($users);
        
        return view('home', compact('workorders','users'));
    }

    //takes an .xls file to import in a mass amount of data at once. 
    public function import(Request $request)
    {

        $file = $request->importFile;
        $extension = $request->importFile->getClientOriginalExtension();
        $file->move('tmp/','import.' . $extension);
        $filename = 'tmp/import.' . $extension;

        if ($request->importType == 'property')
        {
            Helper::importProperty($filename);
        }
        elseif ($request->importType == 'tenant')
        {
            Helper::importTenant($filename);
        }
        elseif ($request->importType == 'past')
        {
            Helper::importPastTenant($filename);
        }
        elseif ($request->importType == 'sold')
        {
            Helper::importSoldProperties($filename);
        }
        elseif ($request->importType == 'transfer')
        {
            Helper::importTransfer($filename);
        }
        elseif ($request->importType == 'insreq')
        {
            Helper::importInsuranceRequirements($filename);
        }
        elseif ($request->importType == 'lease')
        {
            Helper::importNewLease($filename);
        }
        elseif ($request->importType == 'remit')
        {
            Helper::importRemit($filename);
        }
        elseif ($request->importType == 'manager')
        {
            Helper::importManager($filename);
        }

        
        return redirect('/home');
    }

    //old report now broken left behind to guide implementation of the new reporting package
    //current report returns all active tenants
    public function insurancereport(Request $request)
    {
        
        $tenants = Helper::filterbyproperty($request->property_system_id);
        $tenants = $tenants->filter(function ($tenant) {
                return $tenant->active;
            });

        $issues = collect();
        $issues = Helper::processInsuranceChecks($tenants);


        Excel::create('Filename', function($excel) use($issues) {

            $excel->sheet('Insurance', function($sheet) use($issues) {

                $sheet->appendRow(array('Expired'));
                $sheet->appendRow(array('Property ID','Property Name','Tenant ID', 'Tenant Name'));
                $expired = $issues->get('expired');
                foreach($expired as $tenant)
                {
                    $sheet->appendRow(array($tenant->Property->property_system_id,$tenant->Property->name,$tenant->tenant_system_id, $tenant->company_name,$tenant->Insurance->liability_filename, $tenant->Insurance->endorsement_filename));
                }

            });

        })->export('xls');
        return redirect('/home');
    }

    public function autoUpdate()
    {

    }
}
