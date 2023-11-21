<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RoasterStatus;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        // Load user from database
        $user = User::where('email', $request->email)->first();

        if(!$user){
            throw ValidationException::withMessages([
                'email' => [trans("this email doesn't exist!")],
            ]);
        }
        if(!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => [trans('wrong password!')],
            ]);
        }
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $remember_me = $request->has('remember_me') ? true : false;
        
        $this->sendFailedLoginResponse($request);
        
        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), $remember_me)) {
            if(auth()->user()->user_roles->count() > 0){
                if ((auth()->user()->company_roles->first()->role != 1) && (auth()->user()->company_roles->first()->company->status == 0 || Carbon::parse(auth()->user()->company_roles->first()->company->expire_date)<Carbon::now()->toDateString())) {
                    $c_name = auth()->user()->company_roles->first()->company->company;
    
                    $all_roles = auth()->user()->user_roles->unique('company_code')->sortByDesc('role');
                    $company= null;
                    $c_id = '';
                    foreach ($all_roles as $role) {
                        if(!$company && $role->company->status == 1 && Carbon::parse($role->company->expire_date)>Carbon::now()->toDateString()){
                            $company = $role->company;
                            $c_id = $role->company->id;
                        }
                        $role->last_login = $role->company_code  == $c_id ?1:0;
                        $role->save();
                    }
                    if($company){
                        $notification = array(
                            'message' => "sorry! $c_name has temporarily blocked!",
                            'alert-type' => 'error'
                        );
                        if (!$company->sub_domain) {
                            return redirect('/')->with($notification);
                        } else {
                            // Auth::logout();
                            // return redirect('http://localhost:8888/autologin?id=' . Auth::id() . '&api_token=token')->with($notification);
                            return redirect('https://' . $company->sub_domain . '.easytask.com.au/autologin?id=' . Auth::id() . '&api_token=token');
                        }
                    }else{
                        Auth::logout();
                        $notification = array(
                            'message' => 'sorry! your company has temporarily blocked!',
                            'alert-type' => 'error'
                        );
                        return redirect()->route('login')->with($notification);
                    }
                }else{
                    if(auth()->user()->company_roles->first()->company->sub_domain){
                        // return redirect('http://localhost:8888/autologin?id='.auth()->user()->id.'&api_token=token');
                        return redirect('http://'.auth()->user()->company_roles->first()->company->sub_domain .'.easytask.com.au/autologin?id='.auth()->user()->id.'&api_token=token');
                    }else{
                        if(auth()->user()->company_roles->first()->role != 1){
                            $roaster_statuses = RoasterStatus::where([
                                ['company_code', Auth::user()->company_roles->first()->company->id],
                            ])->get();
                            $roaster_status = [];
                            foreach ($roaster_statuses as $status) {
                                $roaster_status[$status->name] = $status->id;
                            }
                            Session::put('roaster_status', $roaster_status);
                        }
                        if (auth()->user()->company_roles->first()->role== 2 || auth()->user()->company_roles->first()->role== 5) {
                            return redirect('/admin/home/{id}');
                        } elseif (auth()->user()->company_roles->first()->role== 1) {
                            return redirect()->route('super-admin.home');
                        } elseif (auth()->user()->company_roles->first()->role== 4) {
                            return redirect()->route('supervisor.dashboard');
                        }
                        else {
                            return redirect()->route('home');
                        }
                    }
                }
            }else{
                Auth::logout();
                $notification = array(
                    'message' => 'you are not an active user!',
                    'alert-type' => 'error'
                );
                return redirect()->route('login')->with($notification);
            }
        } else {
            $notification=array(
                'message'=>'Email and Password did not match!',
                'alert-type' =>'error'
            );
            // Alert::error('Error', 'Email and Password did not match!');
          return redirect()->route('login')->with($notification);
        }
    }
}
