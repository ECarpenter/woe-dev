<?php

namespace App\Http\Controllers\Auth;

use DB;

use App\User;
use App\Tenant;
use Validator;
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
            'suite' => 'required',
            'company_name' =>'required',
            'job_title' => 'required',
            'property' =>'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data\
     * @return User
     */
    protected function create(array $data)
    {


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'timezone' => "America/Los_Angeles"
        ]);

        $role = DB::table('roles')->where('name', '=', 'tenant')->pluck('id');
        $user->Roles()->attach($role);

        $tenant = new Tenant;
        $tenant->user_id = $user->id;
        $tenant->unit = $data['suite'];
        $tenant->property_id = $data['property'];
        $tenant->company_name = $data['company_name'];
        $tenant->job_title = $data['job_title'];
        $tenant->tenant_system_id = $data['tenant_system_id'];
        $tenant->save();

        return $user;



    }
}
