<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Compliance;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\UserCompliance;
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
        return $req->all();
        $req->validate([
            
        ]);
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
