<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Compliance;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\UserCompliance;
use Carbon\Carbon;
use Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminComplianceController extends Controller
{
    public function index(){
        $compliances = Compliance::get();
        $employee = Employee::where('company', Auth::user()->company_roles->first()->company->id)
        ->orderBy('fname', 'asc')->get();
        return view('pages.Admin.compliance.index', ['compliances'=> $compliances, 'employees'=> $employee]);
    }
    public function get_compliance(){
        $compliances = UserCompliance::select('user_compliances.id as id', 'user_compliances.*', 'compliances.name as compliance_name', DB::raw("CONCAT(employees.fname, ' ', COALESCE(employees.mname, ''), ' ', employees.lname) AS employee_name"), 'employees.contact_number', 'employees.image', 'employees.id as emp_id')
        ->leftjoin('employees', 'employees.email', 'user_compliances.email')
        ->leftjoin('compliances', 'compliances.id', 'user_compliances.compliance_id')
        ->where('employees.company', Auth::user()->company_roles->first()->company->id)
        ->orderBy('user_compliances.id', 'desc')
        ->get();

        return response()->json([
            'status'=> true,
            'data'=> $compliances
        ]);
    }

    public function store_compliance(Request $req){
        $employee = Employee::whereId($req->emp_id)->first();
        $user_compliance = new UserCompliance();
        $user_compliance->user_id = $employee->userID;
        $user_compliance->email = $employee->email;
        $user_compliance->compliance_id = $req->compliance;
        $user_compliance->certificate_no = $req->certificate_no;
        $user_compliance->expire_date = Carbon::parse($req->expire_date);
        $user_compliance->comment = $req->comment;
        $image = $req->hasFile('document');
        if($image){
            $file = $req->file('document');
            $basePath = "/home/eazytask-api/htdocs/www.api.eazytask.au/public/";
            $folderPath = "images/employees/";
            $file_ext = $file->extension();
            $image_name = date('sihdmy').'.'.$file_ext;
            $file_name = $folderPath.$image_name;
            $user_compliance->document = $this->saveImage($file, $basePath, $file_name);
        }
        $user_compliance->save();
        return response()->json([
            'status'=> true,
            'message'=> 'Successfuly store a new compliance.'
        ]);
    }

    public function update_compliance(Request $req){
        // return $req->all();
        $employee = Employee::whereId($req->emp_id)->first();
        // $user_compliance = UserCompliance::whereId($req->id);
        $user_compliance = UserCompliance::find($req->data_id);
        $user_compliance->user_id = $employee->userID;
        $user_compliance->email = $employee->email;
        $user_compliance->compliance_id = $req->compliance;
        $user_compliance->certificate_no = $req->certificate_no;
        $user_compliance->expire_date = Carbon::parse($req->expire_date);
        $user_compliance->comment = $req->comment;
        $image = $req->hasFile('document');
        if($image){
            $file = $req->file('document');
            $basePath = "/home/eazytask-api/htdocs/www.api.eazytask.au/public/";
            $folderPath = "images/employees/";
            $file_ext = $file->extension();
            $image_name = date('sihdmy').'.'.$file_ext;
            $file_name = $folderPath.$image_name;
            $user_compliance->document = $this->saveImage($file, $basePath, $file_name);
        }
        $user_compliance->save();
        return response()->json([
            'status'=> true,
            'message'=> 'Successfuly updated a compliance.'
        ]);
    }

    public static function saveImage($image, $path, $filename){
        try {
            if($image != null) {

                // $image_path = $image->store($path, $option);
                $image_path = Image::make($image)->save($path.$filename);
                return $image_path;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            echo 'Image Helper saveImage ' .$e->getMessage();
        }
    }

    public function delete_compliance($id){
        $delete = UserCompliance::whereId($id)->delete();
        if($delete){
            return response()->json([
                'status'=> true,
                'alertType'=> 'success',
                'message'=> 'A compliance deleted successfuly.'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'alertType'=> 'warning',
                'message'=> 'Compliance can not be deleted right now.'
            ]);
        }
    }
}
