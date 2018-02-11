<?php

namespace App\Http\Controllers;

use Excel;

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
               // if (\Auth::user()->tenant_id == 0)
               // {
                    if ($workorder->user_id == \Auth::user()->id)
                    {
                        $workorders->prepend($workorder);
                    }
                //}
                //elseif ($workorder->Tenant->id == \Auth::user()->tenant_id)
                //{
                    
               //     $workorders->prepend($workorder);
               // }   
                
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
        $file->move('tmp/','import.xls');

        if ($request->importType == 'property')
        {
            Helper::importProperty('tmp/import.xls');
        }
        elseif ($request->importType == 'tenant')
        {
            Helper::importTenant('tmp/import.xls');
        }
        elseif ($request->importType == 'past')
        {
            Helper::importPastTenant('tmp/import.xls');
        }
        elseif ($request->importType == 'sold')
        {
            Helper::importSoldProperties('tmp/import.xls');
        }
        elseif ($request->importType == 'transfer');
        {
            Helper::importTransfer('tmp/import.xls');
        }
        
        return redirect('/home');
    }

    public function insurancereport(Request $request)
    {
        
        $tenants = Helper::filterbyproperty($request->property_system_id);
        $tenants = $tenants->filter(function ($tenant) {
                return $tenant->active;
            });

        $tenants = Helper::processInsuranceChecks($tenants);
        Excel::create('Filename', function($excel) use($tenants) {

            $excel->sheet('Insurance', function($sheet) use($tenants) {

                $sheet->appendRow(array('Property ID','Property Name','Tenant ID', 'Tenant Name'));
                foreach($tenants as $tenant)
                {
                    $sheet->appendRow(array($tenant->Property->property_system_id,$tenant->Property->name,$tenant->tenant_system_id, $tenant->company_name));
                }

            });

        })->export('xls');
        return redirect('/home');
    }

    public function autoUpdate()
    {

    }
}
