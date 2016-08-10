<?php

namespace App\Http\Controllers\Auth;

use DB;

use App\User;
use App\Tenant;
use App\Property;
use Validator;
use Request;
use Response;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'job_title' => 'required',
            'property' =>'required',
            'company_name' =>'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $tenant = Tenant::where('tenant_system_id', '=', $data['tenant_system_id'])->first();
        $property = Property::where('id', '=', $data['property'])->first();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'timezone' => "America/Los_Angeles",
            'company_name' => $data['company_name'],
            'job_title' => $data['job_title']
        ]);

        $role = DB::table('roles')->where('name', '=', 'tenant')->pluck('id');
        $user->Roles()->attach($role);
        $user->Properties()->attach($property);
        if ($tenant != null)
        {
            $user->Tenant()->associate($tenant);
            $user->verified = true;
            $user->save();
        }
        else
        {
            $user->tenant_id = 0;
            $user->save();
        }
        

        return $user;

    }

    public function tenantregister()
    {
        $properties = Property::orderBy('name')->get();
        $cities = collect();
        foreach ($properties as $property) 
        {
           if (!$cities->contains($property->city))
           {
                $cities->prepend($property->city);
           }  
        }

        $cities = $cities->sort();

        return view('auth.register', compact('properties', 'cities'));
    }

    public function city()
    {
        $city = Request::get('city');
        $city = str_replace("%20"," ",$city);
        $property = Property::where('city','=', $city)->get();
        return $property;
    }

    public function id()
    {
        $id = Request::get('tenantid');
        $tenant = Tenant::where('tenant_system_id', 'like', $id)->first();
        if ($tenant == null)
        {
            return response()->json('failure');
        }
        else
        {
            return $tenant;
        }
    }

}
