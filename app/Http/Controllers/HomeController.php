<?php

namespace App\Http\Controllers;

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
}
