<?php

namespace App\Http\Controllers;

use App\Mail\LeaveDay;
use App\Models\Myavailability;
use App\Models\Employee;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;

class LeaveController extends Controller
{
    public function index()
    {
        Session::put('leave_start_date', '');
        Session::put('leave_end_date', '');
        return $this->search_module();
    }

    public function admin_search(Request $request)
    {
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

        Session::put('leave_start_date', $start_date);
        Session::put('leave_end_date', $end_date);

        return $this->search_module();
    }

    public function search_module()
    {
        $start_date = Session::get('leave_start_date') ? Session::get('leave_start_date') : Carbon::now()->startOfYear();
        $end_date = Session::get('leave_end_date') ? Session::get('leave_end_date') : Carbon::now();

        $total_employee = DB::table('myavailabilities')
            ->select(DB::raw(
                'e.id,e.fname,e.mname,e.lname,
        sum(myavailabilities.total) as total_day'

            ))
            ->leftJoin('employees as e', 'e.id', 'myavailabilities.employee_id')
            ->where([
                ['myavailabilities.status', 'approved'],
                ['myavailabilities.company_code', Auth::user()->company_roles->first()->company->id],
                ['myavailabilities.start_date', '>=', Carbon::parse($start_date)->toDateString()],
                ['myavailabilities.end_date', '<=', Carbon::parse($end_date)->toDateString()],
                ['myavailabilities.is_leave', 1]
            ])
            ->groupBy("e.id")
            ->orderBy('fname', 'asc')
            ->get();

        $data = Myavailability::where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['end_date', '>=', Carbon::now()->toDateString()],
            ['is_leave', 1]
        ])
            ->orderBy('start_date', 'asc')
            ->get();

        $employees = Employee::where([
            ['company', Auth::user()->company_roles->first()->company->id],
            ['role', 3],
            ['status', 1]
        ])->orderBy('fname', 'asc')->get();
        $leave_types = LeaveType::all();

        return view("pages.Admin.leave.index", compact('data', 'employees', 'leave_types', 'total_employee'));
    }

    public function admin_store(Request $request)
    {
        $single = new Myavailability();
        $single->user_id = Auth::id();;
        $single->employee_id = $request->employee_id;
        $single->company_code = Auth::user()->company_roles->first()->company->id;
        $single->remarks = $request->remarks;
        $single->start_date = Carbon::parse($request->start_date);
        $single->end_date = Carbon::parse($request->end_date);
        $single->leave_type_id = $request->leave_type_id;
        $single->total = $single->start_date->floatDiffInRealDays($single->end_date) + 1;
        $single->status = $request->status;
        $single->is_leave = 1;
        $single->save();

        if ($single->status == 'approved') {
            try {
                Mail::to($single->employee->email)->send(new LeaveDay((object)[
                    'name' => $single->employee->fname,
                    'start_date' => $single->start_date,
                    'end_date' => $single->end_date,
                    'total' => $single->total,
                    'leave_type' => $single->leave_type->name,
                ]));
            } catch (\Exception $e) {
            }
        }
        return redirect()->back();
    }
    public function admin_update(Request $request)
    {
        $single = Myavailability::find($request->id);
        if ($single) {
            $single->employee_id = $request->employee_id;
            $single->remarks = $request->remarks;
            $single->start_date = Carbon::parse($request->start_date);
            $single->end_date = Carbon::parse($request->end_date);
            $single->leave_type_id = $request->leave_type_id;
            $single->total = $single->start_date->floatDiffInRealDays($single->end_date) + 1;
            $single->status = $request->status;
            $single->is_leave = 1;

            $single->save();
        }
        if ($single->status == 'approved') {
            try {
                Mail::to($single->employee->email)->send(new LeaveDay((object)[
                    'name' => $single->employee->fname,
                    'start_date' => $single->start_date,
                    'end_date' => $single->end_date,
                    'total' => $single->total,
                    'leave_type' => $single->leave_type->name,
                ]));
            } catch (\Exception $e) {
            }
        }

        return redirect()->back();
    }


    // public function userIndex()
    // {
    //     $data = Myavailability::where([
    //         ['employee_id', Auth::user()->employee->id],
    //         ['company_code', Auth::user()->employee->company],
    //         ['end_date', '>=', Carbon::now()->subMonth(12)],
    //         ['is_leave', 1]
    //     ])
    //         ->orderBy('start_date', 'desc')
    //         ->get();
    //     $leave_types = LeaveType::all();
    //     return view("pages.User.myavailability.index", compact('data', 'leave_types'));
    // }

    public function store(Request $request)
    {
        $single = new Myavailability();
        $single->user_id = Auth::user()->employee->user_id;;
        $single->employee_id = Auth::user()->employee->id;
        $single->company_code = Auth::user()->employee->company;
        $single->remarks = $request->remarks;
        $single->start_date = Carbon::parse($request->start_date);
        $single->end_date = Carbon::parse($request->end_date);
        $single->leave_type_id = $request->leave_type_id;
        $single->total = $single->start_date->floatDiffInRealDays($single->end_date) + 1;
        $single->is_leave = 1;

        $single->save();

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $single = Myavailability::find($request->id);
        if ($single) {
            $single->remarks = $request->remarks;
            $single->leave_type_id = $request->leave_type_id;
            $single->start_date = Carbon::parse($request->start_date);
            $single->end_date = Carbon::parse($request->end_date);
            $single->total = $single->start_date->floatDiffInRealDays($single->end_date) + 1;

            $single->save();
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $single = Myavailability::find($id);
        if ($single) {
            $single->delete();
        }

        return redirect()->back();
    }

    public function approve($id) 
    {
        $single = Myavailability::find($id);
        if ($single) {
            $single->status = 'approved';
            $single->save();
        }

        if ($single->status == 'approved') {
            try {
                Mail::to($single->employee->email)->send(new LeaveDay((object)[
                    'name'=>$single->employee->fname,
                    'start_date'=>$single->start_date,
                    'end_date'=>$single->end_date,
                    'total'=>$single->total,
                    'leave_type'=>$single->leave_type->name,
                ]));
            } catch (\Exception $e) {
            }
        }

        return redirect()->back();
    }
}
