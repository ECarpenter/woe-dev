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
        $current = collect();
        $missing = collect();
        $issues = Helper::processInsuranceChecks($tenants);
        $current = Helper::collectCurrent($tenants);
        $missing = Helper::collectMissing($tenants);


        Excel::create('Insurance Report', function($excel) use($issues, $current, $missing) {

            $excel->sheet('Insurance', function($sheet) use($issues, $current, $missing) {

                $sheet->appendRow(array('Expired'));
                $sheet->appendRow(array('Property ID','Property Name','Tenant ID', 'Tenant Name','Status'));
                $expired = $issues->get('expired');
                foreach($expired as $tenant)
                {
                    $sheet->appendRow(array($tenant->Property->property_system_id,$tenant->Property->name,$tenant->tenant_system_id, $tenant->company_name, 'expired'));
                }
                foreach($current as $tenant)
                {
                    $sheet->appendRow(array($tenant->Property->property_system_id,$tenant->Property->name,$tenant->tenant_system_id, $tenant->company_name, 'current'));
                }
                foreach($missing as $tenant)
                {
                    $sheet->appendRow(array($tenant->Property->property_system_id,$tenant->Property->name,$tenant->tenant_system_id, $tenant->company_name, 'missing'));
                }



            });

        })->export('xls');
        return redirect('/home');
    }


    // adds all current notices for a properrty to a zip file
    public function insurancedownload(Request $request) 
    {
        $tenants = Helper::filterbyproperty($request->property_system_id);
        $tenants = $tenants->filter(function ($tenant) {
                return $tenant->active;
            });
        log::info("hello");

        $archive_file = storage_path('download.zip');
        $archive = new \ZipArchive();
        if($archive->open($archive_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE))
        {
            $archive->addFromString('ReadMe.txt','Hello');
            foreach ($tenants as $tenant) {

                if ($tenant->insurance->endorsement_filename != null)
                {
                    $archive->addFromString($tenant->property->property_system_id.'-'.$tenant->tenant_system_id.'-other.pdf', file_get_contents(Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->liability_filename)));

                    log::info("add1");
                }
                if ($tenant->insurance->liability_filename != null)
                {
                    $archive->addFromString($tenant->property->property_system_id.'-'.$tenant->tenant_system_id.'-liability.pdf', file_get_contents(Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->liability_filename)));
                   
                    log::info("add2");
                }
            }
            $archive->close();
        }
        else
        {
            log::info("FAIL");
        }

        //header('Content-disposition: attachment; filename=files.zip');
        //header('Content-type: application/zip');
        //readfile($archive_file);

        return response()->download($archive_file)->deleteFileAfterSend(true);
    }


    public function autoUpdate()
    {

    }
}
