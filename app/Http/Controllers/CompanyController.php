<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyType;
use App\Models\JobType;
use App\Models\RoasterStatus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\UserRole;
use App\Notifications\UserCredential;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $companies =  Company::where('id','!=',0)->get();
        $company_types =  CompanyType::get();
        return view('pages.SuperAdmin.Company.index', compact('companies','company_types'));
    }

    public function storeCompanies(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'company_code' => 'required',
            'status' => 'required',
            'company' => 'required',
            'company_contact' => 'required',
            'master_license' => 'required',
            'expire_date' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $this->validate($request, $rules, $customMessages);

        $image = $request->file('file');
        $filename = null;

        if ($request->password) {
            $password = $request->password;
        } else {
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $password = substr(str_shuffle($chars), 0, 10);
        }

        $email_data['email'] = $request['email'];
        $email_data['name'] = $request['name'];
        $email_data['mname'] = $request['mname'];
        $email_data['lname'] = $request['lname'];
        $email_data['password'] = $password;


        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = new User;
            $user->name = $request->name;
            $user->mname = $request->mname;
            $user->lname = $request->lname;
            $user->email = $request->email;
            $user->password = Hash::make($password);
            $user->save();
            $GLOBALS['data'] = $user;

            // try {
            //     $mail = $GLOBALS['data']->notify(new UserCredential($email_data));
            // } catch (\Exception $e) {
            //     $GLOBALS['data']->delete();
            //     $notification = array(
            //         'message' => 'Sorry! this email is incorrect.',
            //         'alert-type' => 'warning'
            //     );
            //     return Redirect()->back()->with($notification);
            // }
        } else {
            $GLOBALS['data'] = $user;
        }

        if ($image) {
            $ext = strtolower($image->getClientOriginalExtension());
            $basePath = "/home/eklaw543/api.eazytask.au/public/";
            $folderPath = "images/superadmin/";
            $img_name = date('sihdmy');
            $full_name = $img_name . '.' . $ext;
            $filename = $folderPath . $full_name;

            $image->move($basePath, $filename);
        }
        //=========================================================================//
        //================Store Company Details in Company Table===================//
        $company = new Company();
        $company->user_id =  $GLOBALS['data']->id;
        $company->company_code = $request->company_code;
        $company->image = $filename;
        $company->mname = $GLOBALS['data']->mname;
        $company->lname = $GLOBALS['data']->lname;
        $company->status = $request->status;
        $company->master_license = $request->master_license;
        $company->expire_date = Carbon::parse($request->expire_date);
        $company->company = $request->company;
        $company->company_type_id = $request->company_type_id;
        $company->company_contact = $request->company_contact;
        $company->created_at = Carbon::now();

        if ($company->Save()) {
            JobType::create([
                'name' => 'core',
                'user_id' => $company->user_id,
                'company_code' => $company->id,
            ]);
            JobType::create([
                'name' => 'Ad hoc',
                'user_id' => $company->user_id,
                'company_code' => $company->id,
            ]);

            RoasterStatus::create([
                'name' => 'Not published',
                'user_id' => $company->user_id,
                'color' => '#ff9f43',
                'company_code' => $company->id,
            ]);
            RoasterStatus::create([
                'name' => 'Published',
                'user_id' => $company->user_id,
                'company_code' => $company->id,
                'color' => '#7367f0',
                'text_color' => '#fff',
            ]);
            RoasterStatus::create([
                'name' => 'Accepted',
                'user_id' => $company->user_id,
                'company_code' => $company->id,
                'color' => '#28c76f',
            ]);
            RoasterStatus::create([
                'name' => 'Rejected',
                'user_id' => $company->user_id,
                'company_code' => $company->id,
                'color' => '#ea5455',
            ]);

            UserRole::create([
                'role' => 2,
                'user_id' => $company->user_id,
                'company_code' => $company->id,
                'status' =>  $request->status,
            ]);
        }

        $notification = array(
            'message' => 'Company Admin Added Successfully',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    public function updateCompany(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'company_code' => 'required',
            'status' => 'required',
            'company' => 'required',
            'company_contact' => 'required',
            'master_license' => 'required',
            'expire_date' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $this->validate($request, $rules, $customMessages);

        $image = $request->file('file');
        $filename = null;
        

        $company = Company::find($request->id);
        if ($request->password) {
            User::findOrFail($company->user_id)->update([
                'password' => Hash::make($request->password)
            ]);
        }
        if ($image) {
            $ext = strtolower($image->getClientOriginalExtension());
            $basePath = "/home/eklaw543/api.eazytask.au/public/";
            $folderPath = "images/superadmin/";
            $img_name = date('sihdmy');
            $full_name = $img_name . '.' . $ext;
            $filename = $folderPath . $full_name;
            $image->move($basePath, $filename);

            try{
                unlink($basePath . $company->image);
            }catch(\Throwable $e){}
        }
        $company->status = $request->status;
        $company->master_license = $request->master_license;
        $company->expire_date = Carbon::parse($request->expire_date);
        $company->company = $request->company;
        $company->company_code = $request->company_code;
        $company->company_type_id = $request->company_type_id;
        $company->company_contact = $request->company_contact;
        $company->image = $filename;
        $company->save();

        User::findOrFail($company->user_id)->update([
            'name' => $request->name,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'email' => $request->email,
        ]);

        $notification = array(
            'message' => 'Company Updated Successfully',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    // public function delete($id)
    // {
    //     //dd($id);
    //     $employee = Company::find($id);
    //     if (!empty($employee->image)) {
    //         if(file_exists($employee->image)){
    //             unlink($employee->image);
    //         }
    //     }
    //     //dd($employee);
    //     $employee->delete();
    //     $notification = array(
    //         'message' => 'Company record has been deleted successfully.',
    //         'alert-type' => 'error'
    //     );
    //     return Redirect()->back()->with($notification);
    // }

    #superadmin
    public function SuperAdminProfile($id)
    {
        return view('pages.SuperAdmin.profile');
    }
    public function profileUpdate(Request $request)
    {
        //dd($request);

        $superadmin = User::find(Auth::user()->id);
        $superadmin->name = $request->name;
        $superadmin->email = $request->email;
        $superadmin->save();

        $notification = array(
            'message' => 'Super Admin Profile Updated successfully',
            'alert-type' => 'success'
        );
        return Redirect()->route('super-admin.home')->with($notification);
    }
    public function changePassStore(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'password_confirmation' => 'required|min:5',
        ]);
        $db_pass = Auth::user()->password;
        $current_password = $request->old_password;
        $newpass = $request->new_password;
        $confirmpass = $request->password_confirmation;

        if (Hash::check($current_password, $db_pass)) {
            if ($newpass === $confirmpass) {
                User::findOrFail(Auth::id())->update([
                    'password' => Hash::make($newpass)
                ]);

                Auth::logout();
                $notification = array(
                    'message' => 'Your Password Change Success. Now Login With New Password',
                    'alert-type' => 'success'
                );
                return Redirect()->route('login')->with($notification);
            } else {

                $notification = array(
                    'message' => 'New Password And Confirm Password Not Same',
                    'alert-type' => 'error'
                );
                return Redirect()->back()->with($notification);
            }
        } else {
            $notification = array(
                'message' => 'Old Password Not Match',
                'alert-type' => 'error'
            );
            return Redirect()->back()->with($notification);
        }
    }
    public function UpdateSuperAdminPhoto(Request $request)
    {
        $superadmin = User::find($request->id);

        $img = $request->file('file');
        $filename = null;
        if ($img) {
            $img_name = date('sihdmy');
            $ext = strtolower($img->getClientOriginalExtension());
            $full_name = $img_name . '.' . $ext;
            $folderPath = "images/superadmin/";
            $basePath = "/home/eklaw543/api.eazytask.au/public/";
            $filename = $folderPath . $full_name;

            try{
                unlink($basePath . $superadmin->image);
            }catch(\Throwable $e){}

            $img->move($basePath . $folderPath, $full_name);
            $filename = $filename;
        }
        if ($filename) {
            $superadmin->image = $filename;
        }

        $superadmin->save();
        $notification = array(
            'message' => 'Super-Admin Profile Updated successfully!!!',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    #admin
    public function AdminProfile($id)
    {
        return view('pages.Admin.profile');
    }
    public function AdminprofileUpdate(Request $request)
    {
        $admin = User::find(Auth::id());
        $admin->name = $request->name;
        $admin->mname = $request->mname;
        $admin->lname = $request->lname;
        // $admin->email = $request->email;
        $admin->save();

        if ($request->company) {
            $company = Auth::user()->company;
            $company->company = $request->company;
            $company->company_contact = $request->company_contact;
            $company->save();
        }
        $companies = Company::where('user_id', Auth::id())->get();
        foreach ($companies as $row) {
            $row->mname = $request->mname;
            $row->lname = $request->lname;
            $row->save();
        }

        $employees = Employee::where('userID', Auth::id())->get();
        foreach ($employees as $row) {
            $row->fname = $request->name;
            $row->mname = $request->mname;
            $row->lname = $request->lname;
            $row->save();
        }

        $notification = array(
            'message' => 'profile updated successfully.',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }
    public function AdminchangePassStore(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'password_confirmation' => 'required|min:5',
        ]);
        $db_pass = Auth::user()->password;
        $current_password = $request->old_password;
        $newpass = $request->new_password;
        $confirmpass = $request->password_confirmation;

        if (Hash::check($current_password, $db_pass)) {
            if ($newpass === $confirmpass) {
                User::findOrFail(Auth::id())->update([
                    'password' => Hash::make($newpass)
                ]);

                Auth::logout();
                $notification = array(
                    'message' => 'Your Password Change Success. Now Login With New Password',
                    'alert-type' => 'success'
                );
                return Redirect()->route('login')->with($notification);
            } else {

                $notification = array(
                    'message' => 'New Password And Confirm Password Not Same',
                    'alert-type' => 'error'
                );
                return Redirect()->back()->with($notification);
            }
        } else {
            $notification = array(
                'message' => 'Old Password Not Match',
                'alert-type' => 'error'
            );
            return Redirect()->back()->with($notification);
        }
    }
    public function UpdateAdminPhoto(Request $request)
    {
        // $admin = Company::find(Auth::user()->company_roles->first()->company->id);
        $admin = User::find(Auth::id());
        $img = $request->file('file');
        $filename = null;
        if ($img) {
            $img_name = date('sihdmy');
            $ext = strtolower($img->getClientOriginalExtension());
            $full_name = $img_name . '.' . $ext;
            $folderPath = "images/admins/";
            $basePath = "/home/eklaw543/api.eazytask.au/public/";
            $filename = $folderPath . $full_name;

            try{
                unlink($basePath . $admin->image);
            }catch(\Throwable $e){}

            $img->move($basePath . $folderPath, $full_name);
            // $img->move('S:\images', $full_name);
            $filename = $filename;
        }
        if ($filename) {
            $admin->image = $filename;
            DB::table('employees')->where('userID', Auth::id())->update(array(
                'image' => $filename,
            ));
        }

        $admin->save();
        $notification = array(
            'message' => 'profile updated successfully.',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }
}
