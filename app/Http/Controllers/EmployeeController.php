<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserCompliance;
use App\Models\UserRole;
use App\Notifications\ExistingUserNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hash;
use App\Notifications\UserCredential;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Image;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\JobType;
use App\Models\RoasterStatus;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Employee View File
    public function index()
    {
        // $employees = Employee::where('company', Auth::user()->company_roles->first()->company->id)->orderBy('fname', 'asc')->get();
        $compliance = Compliance::all();
        return view('pages.Admin.employee.index', compact('compliance'));
    }

    public function fetch()
    {
        $employees = Employee::where('company', Auth::user()->company_roles->first()->company->id)->orderBy('fname', 'asc')->get();

        $html = '';
        $numbering = 1;
        foreach ($employees as $loop => $row) {
            if (!$row->image) {
                $row->image = 'images/app/no-image.png';
            }
            $json = json_encode($row->toArray(), false);

            $role = '';
            if ($row->role == 3) {
                $role = "<span class='badge badge-pill badge-light-primary mr-1'>Employee</span>";
            } elseif ($row->role == 4) {
                $role = "<span class='badge badge-pill badge-light-info mr-1'>Supervisor</span>";
            }


            if ($row->status == 1) {
                $status = "<span class='badge badge-pill badge-light-success mr-1'>Active</span>";
            } else {
                $status = "<span class='badge badge-pill badge-light-danger mr-1'>Inactive</span>";
            }

            $html .= "
            <tr>
                                <td>" . $numbering++ . "</td>
                                <td>
                                    <div class='avatar bg-light-primary'>
                                        <div class='avatar-content'>
                                            <img class='img-fluid' src='" . 'https://api.eazytask.au/' . $row->image . "' alt=''>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                     $row->fname  $row->mname $row->lname 
                                </td>
                                <td>$row->email
                                </td>
                                <td>$row->contact_number </td>
                                <td>
                                    $role
                                </td>
                                <td>
                                $status
                                </td>
                                <td>
                                    <input type='hidden' name='id' value='$row->id'>
                                    <input type='hidden' name='user_id' value='$row->user_id'>

                                    <button class='edit-btn btn btn-gradient-primary mb-25' data-row='$json'><i data-feather='edit'></i></button>
                                    <a class='btn del btn-gradient-danger text-white' data-id='$row->id'><i data-feather='trash-2'></i></a>
                                </td>

                            </tr>
            ";
        }

        $admins = DB::table('users')->join('user_roles', 'users.id', '=', 'user_roles.user_id')->where('user_roles.company_code', Auth::user()->company_roles->first()->company->id)->whereIn('user_roles.role', [2,5,6,7])->get();
        
        foreach ($admins as $loop => $row) {
            if (!$row->image) {
                $row->image = 'images/app/no-image.png';
            }
            $json = json_encode($row, false);

            $role = '';
            if ($row->role == 2) {
                $role = "<span class='badge badge-pill badge-light-info mr-1'>Admin</span>";
            } elseif ($row->role == 5) {
                $role = "<span class='badge badge-pill badge-light-info mr-1'>Operation</span>";
            } elseif ($row->role == 6) {
                $role = "<span class='badge badge-pill badge-light-info mr-1'>Manager</span>";
            } elseif ($row->role == 7) {
                $role = "<span class='badge badge-pill badge-light-info mr-1'>Account</span>";
            }


            if ($row->status == 1) {
                $status = "<span class='badge badge-pill badge-light-success mr-1'>Active</span>";
            } else {
                $status = "<span class='badge badge-pill badge-light-danger mr-1'>Inactive</span>";
            }

            $html .= "
            <tr>
                                <td>" . $numbering++ . "</td>
                                <td>
                                    <div class='avatar bg-light-primary'>
                                        <div class='avatar-content'>
                                            <img class='img-fluid' src='" . 'https://api.eazytask.au/' . $row->image . "' alt=''>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                     $row->name  $row->mname $row->lname 
                                </td>
                                <td>$row->email
                                </td>
                                <td>- </td>
                                <td>
                                    $role
                                </td>
                                <td>
                                $status
                                </td>
                                <td>
                                    <input type='hidden' name='id' value='$row->id'>
                                    <input type='hidden' name='user_id' value='$row->user_id'>

                                    <button class='edit-btn btn btn-gradient-primary mb-25' data-row='$json'><i data-feather='edit'></i></button>
                                    <a class='btn del btn-gradient-danger text-white' data-id='$row->id'><i data-feather='trash-2'></i></a>
                                </td>

                            </tr>
            ";
        }

        return response()->json(['employees' => $html]);
    }

    public function filter_compliance(Request $request)
    {
        if (!empty($request->email) || $request->email != '') {
            $compliances = UserCompliance::where('email', $request->email)->orderBy('id', 'desc')->get();
        }else{
            $compliances = [];
        }
        $user = User::where('email',$request->email)->first();
        return response()->json(['compliances' => $compliances, 'user'=>$user]);
    }

    //Employee Store
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $this->validate($request, $rules, $customMessages);

        if($request->role == 2) {
            // WILL CREATE ADMIN
            $image = $request->file('file');
            $filename = null;

            if ($request->password) {
                $password = $request->password;
            } else {
                $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $password = substr(str_shuffle($chars), 0, 10);
            }

            $email_data['email'] = $request['email'];
            $email_data['name'] = $request['fname'];
            $email_data['mname'] = $request['mname'];
            $email_data['lname'] = $request['lname'];
            $email_data['password'] = $password;
            $email_data['company']= Auth::user()->company->company;


            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = new User;
                $user->name = $request->fname;
                $user->mname = $request->mname;
                $user->lname = $request->lname;
                $user->email = $request->email;
                $user->password = Hash::make($password);
                $user->save();
                $GLOBALS['data'] = $user;

                try {
                    $mail = $GLOBALS['data']->notify(new UserCredential($email_data));
                } catch (\Exception $e) {
                    // $GLOBALS['data']->delete();
                    // $notification = array(
                    //     'message' => 'Sorry! this email is incorrect.',
                    //     'alert-type' => 'warning'
                    // );
                    // return Redirect()->back()->with($notification);
                }
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
            $company = Auth::user()->company;

            if ($company->Save()) {
                JobType::create([
                    'name' => 'core',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                ]);
                JobType::create([
                    'name' => 'Ad hoc',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                ]);

                RoasterStatus::create([
                    'name' => 'Not published',
                    'user_id' => $GLOBALS['data']->id,
                    'color' => '#ff9f43',
                    'company_code' => $company->id,
                ]);
                RoasterStatus::create([
                    'name' => 'Published',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'color' => '#7367f0',
                    'text_color' => '#fff',
                ]);
                RoasterStatus::create([
                    'name' => 'Accepted',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'color' => '#28c76f',
                ]);
                RoasterStatus::create([
                    'name' => 'Rejected',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'color' => '#ea5455',
                ]);

                UserRole::create([
                    'role' => 2,
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'status' =>  $request->status,
                ]);
            }

            return response()->json([
                'message' => 'Admin Added Successfully Added.',
                'alertType' => 'success'
            ]);
        }elseif($request->role == 5 || $request->role == 6 || $request->role == 7) {
            // WILL CREATE OPERATION
            $image = $request->file('file');
            $filename = null;

            if ($request->password) {
                $password = $request->password;
            } else {
                $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $password = substr(str_shuffle($chars), 0, 10);
            }

            $email_data['email'] = $request['email'];
            $email_data['name'] = $request['fname'];
            $email_data['mname'] = $request['mname'];
            $email_data['lname'] = $request['lname'];
            $email_data['password'] = $password;
            $email_data['company']= Auth::user()->company->company;


            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = new User;
                $user->name = $request->fname;
                $user->mname = $request->mname;
                $user->lname = $request->lname;
                $user->email = $request->email;
                $user->password = Hash::make($password);
                $user->save();
                $GLOBALS['data'] = $user;

                try {
                    $mail = $GLOBALS['data']->notify(new UserCredential($email_data));
                } catch (\Exception $e) {
                    // $GLOBALS['data']->delete();
                    // $notification = array(
                    //     'message' => 'Sorry! this email is incorrect.',
                    //     'alert-type' => 'warning'
                    // );
                    // return Redirect()->back()->with($notification);
                }
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
            $company = Auth::user()->company;

            if ($company->Save()) {
                JobType::create([
                    'name' => 'core',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                ]);
                JobType::create([
                    'name' => 'Ad hoc',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                ]);

                RoasterStatus::create([
                    'name' => 'Not published',
                    'user_id' => $GLOBALS['data']->id,
                    'color' => '#ff9f43',
                    'company_code' => $company->id,
                ]);
                RoasterStatus::create([
                    'name' => 'Published',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'color' => '#7367f0',
                    'text_color' => '#fff',
                ]);
                RoasterStatus::create([
                    'name' => 'Accepted',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'color' => '#28c76f',
                ]);
                RoasterStatus::create([
                    'name' => 'Rejected',
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'color' => '#ea5455',
                ]);

                UserRole::create([
                    'role' => $request->role,
                    'user_id' => $GLOBALS['data']->id,
                    'company_code' => $company->id,
                    'status' =>  $request->status,
                ]);
            }

            if ($request->role == 5) {
                return response()->json([
                    'message' => 'Operation Added Successfully Added.',
                    'alertType' => 'success'
                ]);
            }elseif ($request->role == 6) {
                return response()->json([
                    'message' => 'Manager Added Successfully Added.',
                    'alertType' => 'success'
                ]);
            }if ($request->role == 7) {
                return response()->json([
                    'message' => 'Account Added Successfully Added.',
                    'alertType' => 'success'
                ]);
            }
        }else{
            $total_employee = Employee::where([
                ['email', $request->email],
                ['company', Auth::user()->company_roles->first()->company->id],
                ['role', $request->role]
            ])->count();
            if ($total_employee > 0) {
                return response()->json([
                    'message' => 'Sorry! this email is already used.',
                    'alertType' => 'warning'
                ]);
            }
    
            $image = $request->file;
            $filename = null;
    
            $user = User::where('email', $request->email)->first();
            if (!$user) {
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
                $email_data['company']=Auth::user()->company->company;
    
                $user = new User;
                $user->name = $request->fname;
                $user->mname = $request->mname;
                $user->lname = $request->lname;
                $user->email = $request->email;
                $user->password = Hash::make($password);
                $user->save();
                $GLOBALS['data'] = $user;
    
                try {
                    $mail = $GLOBALS['data']->notify(new UserCredential($email_data));
                } catch (\Exception $e) {
                    // return response()->json([
                    //     'message' => 'Sorry! this email is incorrect.',
                    //     'alertType' => 'warning'
                    // ]);
                }
            } else {
                $GLOBALS['data'] = $user;
                try {
                    $mail = $GLOBALS['data']->notify(new ExistingUserNotification($request->name,Auth::user()->company->company));
                } catch (\Exception $e) {
                    // return response()->json([
                    //     'message' => 'Sorry! this email is incorrect.',
                    //     'alertType' => 'warning'
                    // ]);
                }
            }
            // $GLOBALS['data']->notify(new UserCredential($email_data));
            if ($image) {
                $basePath = "/home/eklaw543/api.eazytask.au/public/";
                $folderPath = "images/employees/";
                $image_parts = explode(";base64,", $image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $img_name = date('sihdmy') .'.'. $image_type;
                $filename = $folderPath . $img_name;
                Image::make($image_base64)->save($basePath.$filename);
            }
            $employee = new Employee;
            $employee->user_id = Auth::user()->id;
            $employee->userID = $GLOBALS['data']->id;
            // $employee->fname = $GLOBALS['data']->name;
            // $employee->mname = $GLOBALS['data']->mname;
            // $employee->lname = $GLOBALS['data']->lname;
                $employee->fname = $request->fname;
                $employee->mname = $request->mname;
                $employee->lname = $request->lname;
    
            $employee->address = $request->address;
            $employee->suburb = $request->suburb;
            $employee->state = $request->state;
            $employee->postal_code = $request->postal_code;
            $employee->email = $request->email;
            $employee->contact_number = $request->contact_number;
            $employee->status = $request->status;
    
            function set_date($date=null){
                return $date? Carbon::parse($date)->toDateString():null;
            }
    
            $employee->date_of_birth = set_date($request->date_of_birth);
            $employee->license_no = $request->license_no;
            $employee->license_expire_date = set_date($request->license_expire_date);
            $employee->first_aid_license = $request->first_aid_license;
            $employee->first_aid_expire_date = set_date($request->first_aid_expire_date);
            
            $employee->company = Auth::user()->company_roles->first()->company->id;
            $employee->role = $request->role;
            $employee->image = $filename;
            if ($filename) {
                $employee->image = $filename;
                $user = User::find($employee->userID);
                
                try{
                    unlink($basePath.$user->image);
                }catch(\Throwable $e){}
    
                $user->image = $filename;
                $user->save();
                DB::table('employees')->where('userID', $employee->userID)->update(array(
                    'image' => $filename,
                ));
                
            }
            
    
            if ($employee->save()) {
                $employees = Employee::where([
                    ['userID', $employee->userID],
                    ['role',$request->role]
                ])->get();
        
                foreach ($employees as $row) {
                    $row->fname = $request->fname;
                    $row->mname = $request->mname;
                    $row->lname = $request->lname;
                    $row->address = $request->address;
                    $row->suburb = $request->suburb;
                    $row->state = $request->state;
                    $row->status = $request->status;
                    $row->postal_code = $request->postal_code;
                    // $row->email = $request->email;
                    $row->contact_number = $request->contact_number;
                    $row->date_of_birth = set_date($request->date_of_birth);
                    $row->license_no = $request->license_no;
                    $row->license_expire_date = set_date($request->license_expire_date);
                    $row->first_aid_license = $request->first_aid_license;
                    $row->first_aid_expire_date = set_date($request->first_aid_expire_date);
                    
                    if ($filename) {
                        $employee->image = $filename;
        
                        $user = User::find($employee->userID);
                        $user->image = $filename;
                        $user->save();
                        $row->image = $filename;
                    }
                    $row->save();
                }
    
                $user_role = new UserRole;
                $user_role->company_code = Auth::user()->company_roles->first()->company->id;
                $user_role->user_id = $employee->userID;
                $user_role->role = $request->role;
                if ($request->status == 1) {
                    $user_role->status = 1;
                } else {
                    $user_role->status = 0;
                }
                $user_role->sub_domain = Auth::user()->company_roles->first()->company->sub_domain ? 1 : 0;
                $user_role->save();
    
                if ($request->has_compliance == 'on' || $request->has_compliance == 1) {
                    foreach ($request->Compliance as $compliance) {
                        $exist_comp = UserCompliance::where([
                            ['user_id', $employee->userID],
                            ['compliance_id', $compliance['compliance']]
                        ])->first();
    
                        $image = $compliance['document'];
                        $filename = null;
                
                        if ($image) {
                            $basePath = "/home/eklaw543/api.eazytask.au/public/";
                            $folderPath = "images/compliance/";
                            $image_parts = explode(";base64,", $image);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type = $image_type_aux[1];
                            $image_base64 = base64_decode($image_parts[1]);
                            $img_name = date('sihdmy') .'.'. $image_type;
                            $filename = $folderPath . $img_name;
                            Image::make($image_base64)->save($basePath.$filename);
                        }
    
                        if (!$exist_comp) {
                            $user_compliance = new UserCompliance;
                            $user_compliance->user_id = $employee->userID;
                            $user_compliance->email = $request->email;
                            $user_compliance->compliance_id = $compliance['compliance'];
                            $user_compliance->certificate_no = $compliance['certificate_no'];
                            $user_compliance->comment = $compliance['comment'];
                            $user_compliance->expire_date = Carbon::parse($compliance['expire_date']);
                            $user_compliance->document = $filename;
                            
                            $user_compliance->save();
                        } else {
                            $exist_comp->certificate_no = $compliance['certificate_no'];
                            $exist_comp->comment = $compliance['comment'];
                            $exist_comp->expire_date = Carbon::parse($compliance['expire_date']);
                            $user_compliance->document = $filename;
                            
                            $exist_comp->save();
                        }
                    }
                }
            }
            
            return response()->json([
                'message' => 'Employee Added Successfully Added.',
                'alertType' => 'success'
            ]);
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'fname' => 'required',
            'email' => 'required|email',
            // 'company_code' => 'required',
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $this->validate($request, $rules, $customMessages);

        $employee = Employee::find($request->id);
        if ($employee) {
            // $total_employee = Employee::where([
            //     ['email', $request->email],
            //     ['company', Auth::user()->company_roles->first()->company->id],
            //     ['role',$request->role],
            //     ['id', '!=', $employee->id]
            // ])->count();

            // if ($total_employee > 0) {
            //     $notification = array(
            //         'message' => 'Sorry! this email is already used.',
            //         'alertType' => 'warning'
            //     );
            //     return Redirect()->back()->with($notification);
            // }

            // $employee->fname = $request->fname;
            // $employee->mname = $request->mname;
            // $employee->lname = $request->lname;

            $img = $request->file;
            $filename = null;
            if ($img) {
                $basePath = "/home/eklaw543/api.eazytask.au/public/";
                $folderPath = "images/employees/";
                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $img_name = date('sihdmy') .'.'. $image_type;

                try{
                    unlink($basePath.$employee->image);
                }catch(\Throwable $e){}

                $filename = $folderPath . $img_name;
                Image::make($image_base64)->save($basePath.$filename);
            }

            $emp_role = $employee->role;
            $employee->role = $request->role;

        $employees = Employee::where([
            ['userID', $employee->userID],
            ['role',$request->role]
        ])->get();

        if(!$employees->count()){
            $employees = Employee::where([
                ['userID', $employee->userID],
                ['role',$emp_role]
            ])->get();
        }

        function set_date($date=null){
            return $date? Carbon::parse($date)->toDateString():null;
        }
        foreach ($employees as $row) {
            $row->fname = $request->fname;
            $row->mname = $request->mname;
            $row->lname = $request->lname;
            $row->address = $request->address;
            $row->suburb = $request->suburb;
            $row->state = $request->state;
            $row->status = $request->status;
            $row->postal_code = $request->postal_code;
            // $row->email = $request->email;
            $row->contact_number = $request->contact_number;
            $row->date_of_birth = set_date($request->date_of_birth);
            $row->license_no = $request->license_no;
            $row->license_expire_date = set_date($request->license_expire_date);
            $row->first_aid_license = $request->first_aid_license;
            $row->first_aid_expire_date = set_date($request->first_aid_expire_date);
            
            if ($filename) {
                $employee->image = $filename;

                $user = User::find($employee->userID);
                $user->image = $filename;
                $user->save();
                $row->image = $filename;
            }
            $row->save();
        }

            


            

                if ($request->password) {
                    User::findOrFail($employee->userID)->update([
                        'password' => Hash::make($request->password),
                    ]);
                }

                $user_role = UserRole::where([
                    ['user_id', $employee->userID],
                    ['role', $emp_role],
                    ['company_code', $employee->company]
                ])->first();
                if ($request->status == 1) {
                    $user_role->status = 1;
                } else {
                    $user_role->status = 0;
                }
                $user_role->role = $request->role;
                $user_role->save();

                if ($request->has_compliance) {
                    foreach ($request->Compliance as $compliance) {
                        $exist_comp = UserCompliance::where([
                            ['user_id', $employee->userID],
                            ['compliance_id', $compliance['compliance']]
                        ])->first();
                        if (!$exist_comp) {
                            $user_compliance = new UserCompliance;
                            $user_compliance->user_id = $employee->userID;
                            $user_compliance->email = $request->email;
                            $user_compliance->compliance_id = $compliance['compliance'];
                            $user_compliance->certificate_no = $compliance['certificate_no'];
                            $user_compliance->comment = $compliance['comment'];
                            $user_compliance->expire_date = Carbon::parse($compliance['expire_date']);
                            $user_compliance->save();
                        } else {
                            $exist_comp->certificate_no = $compliance['certificate_no'];
                            $exist_comp->comment = $compliance['comment'];
                            $exist_comp->expire_date = Carbon::parse($compliance['expire_date']);
                            $exist_comp->save();
                        }
                    }
                
            }

            return response()->json([
                'message' => 'Employee Updated Successfully.',
                'alertType' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'something went wrong!',
                'alertType' => 'warning'
            ]);
        }
    }
    public function delete($id)
    {
        try {
            $employee = Employee::find($id);
            // $basePath = "/home/eklaw543/api.eazytask.au/public/";
            // try{
            //     unlink($basePath.$employee->image);
            // }catch(\Throwable $e){}

            $user_id = $employee->userID;
            $emp_role = $employee->role;
            if ($employee->delete()) {
                $user_role = UserRole::where([
                    ['company_code', Auth::user()->company_roles->first()->company->id],
                    ['user_id', $user_id],
                    ['role', $emp_role],
                ])->first();
                if ($user_role) {
                    $user_role->delete();
                }
            }

            return response()->json([
                'message' => 'Employee deleted successfully.',
                'alertType' => 'success'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // var_dump($e->errorInfo);
            return response()->json([
                'message' => 'Sorry! This employee used somewhere.',
                'alertType' => 'warning'
            ]);
        }

        // return Redirect()->back()->with($notification);
    }

    public function userchangePinStore(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_pin' => 'required|min:4',
            'pin_confirmation' => 'required|min:4',
        ]);
        $db_pass = Auth::user()->password;
        $current_password = $request->old_password;
        $newpass = $request->new_pin;
        $confirmpass = $request->pin_confirmation;

        if (Hash::check($current_password, $db_pass)) {
            if ($newpass === $confirmpass) {
                $user = User::find(Auth::id());
                $user->pin = $newpass;

                if ($user->save()) {
                    $notification = array(
                        'message' => 'Your pin changed successfully.',
                        'alert-type' => 'success'
                    );
                } else {
                    $notification = array(
                        'message' => 'Something went wrong!',
                        'alert-type' => 'warning'
                    );
                }
                return Redirect()->back()->with($notification);
            } else {

                $notification = array(
                    'message' => 'New Pin And Confirm Pin Are Not Same',
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
}
