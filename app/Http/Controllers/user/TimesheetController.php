<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobType;
use App\Models\Payment;
use App\Models\Project;
use App\Models\RoasterStatus;
use App\Models\RoasterType;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class TimesheetController extends Controller
{
    public function index(){
        $fromRoaster = Carbon::now()->subWeek()->startOfWeek()->subDays();
        $toRoaster = Carbon::now();
        if(!Session::get('fromRoaster')){
            Session::put('fromRoaster', $fromRoaster);
        }
        if(!Session::get('toRoaster')){
            Session::put('fromRoaster', $toRoaster);
        }

        return $this->search_module();
    }
    public function search(Request $request)
    {
        $fromRoaster = $request->input('start_date');
        $fromRoaster = Carbon::parse($fromRoaster);

        $toRoaster = $request->input('end_date');
        $toRoaster = Carbon::parse($toRoaster);

        Session::put('fromRoaster', $fromRoaster);
        Session::put('toRoaster', $toRoaster);
        Session::put('project_id', $request->project_id);

        return $this->search_module();
    }
    public function search_module()
    {
        $job_types = JobType::where('company_code', Auth::user()->employee->company)->get();
        $roaster_status= RoasterStatus::where('company_code', Auth::user()->employee->company)->orderBy('name','asc')->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        $filter_project = Session::get('project_id') ? ['project_id', Session::get('project_id')] : ['employee_id', '>', 0];

        $timekeepers = TimeKeeper::where([
            $filter_project,
            ['employee_id', Auth::user()->employee->id],
            ['company_code', Auth::user()->employee->company],
            ['payment_status', 0],
            ['roaster_type', 'Unschedueled']
        ])
        ->where(function ($q) {
            avoid_rejected_key($q);
        })
        ->whereBetween('roaster_date', [Carbon::parse(Session::get('fromRoaster')), Carbon::parse(Session::get('toRoaster'))])
        ->orderBy('roaster_date', 'asc')
        ->get();

        return view('pages.User.timesheet.index',compact('timekeepers','projects','job_types','roaster_status'));
    }

    public function store(Request $request){
        if($this->check_license()){
            return $this->check_license();
        }

        $project= Project::find($request->project_id);
        $shift_start= Carbon::parse($request->roaster_date . $request->shift_start);
        $shift_end = Carbon::parse($shift_start)->addMinute($request->duration*60);

        $timekeeper = new TimeKeeper();
        
        if (Carbon::parse($request->roaster_date) > Carbon::now()) {
            $notification = array(
                'message' => 'advance date not support for unschedule!',
                'alert-type' => 'warning'
            );
            return Redirect('/home/timesheet')->with($notification);
        } else {
            $notification = array(
                'message' => 'Roster Successfully Added.',
                'alert-type' => 'success'
            );
            $timekeeper->roaster_type = 'Unschedueled';
        }

        $timekeeper->user_id =  Auth::user()->employee->user_id;
        $timekeeper->employee_id = Auth::user()->employee->id;
        $timekeeper->client_id = $project->clientName;
        $timekeeper->project_id = $request->project_id;
        $timekeeper->company_id = Auth::user()->employee->user_id;
        $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
        $timekeeper->shift_start = $shift_start;
        $timekeeper->shift_end = $shift_end;

        $timekeeper->company_code = Auth::user()->employee->company;
        $timekeeper->duration = $request->duration;
        $timekeeper->ratePerHour = $request->ratePerHour;
        $timekeeper->amount = $request->amount;
        $timekeeper->job_type_id = $request->job_type_id;
        // $timekeeper->roaster_id = Auth::id();
        $timekeeper->roaster_status_id = Session::get('roaster_status')['Accepted'];
        $timekeeper->remarks = $request->remarks;
        $timekeeper->created_at = Carbon::now();
        $timekeeper->save();

        return Redirect('/home/timesheet')->with($notification);
    }

    public function update(Request $request){
        if($this->check_license()){
            return $this->check_license();
        }

        $project= Project::find($request->project_id);
        $shift_start = Carbon::parse($request->roaster_date . $request->shift_start);
        $shift_end = Carbon::parse($shift_start)->addMinute($request->duration*60);

        $timekeeper = TimeKeeper::find($request->timekeeper_id);
        
        if (Carbon::parse($request->roaster_date) > Carbon::now()) {
            $notification = array(
                'message' => 'advance date not support for unschedule!',
                'alert-type' => 'warning'
            );
            return Redirect('/home/timesheet')->with($notification);
        }

        $timekeeper->client_id = $project->clientName;
        $timekeeper->project_id = $request->project_id;
        $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
        $timekeeper->shift_start = $shift_start;
        $timekeeper->shift_end = $shift_end;

        $timekeeper->duration = $request->duration;
        $timekeeper->ratePerHour = $request->ratePerHour;
        $timekeeper->amount = $request->amount;
        $timekeeper->job_type_id = $request->job_type_id;
        $timekeeper->remarks = $request->remarks;
        $timekeeper->save();

        $notification = array(
            'message' => 'Roster Successfully updated.',
            'alert-type' => 'success'
        );
        return Redirect('/home/timesheet')->with($notification);
    }

    public function delete($id)
    {
        if($this->check_license()){
            return $this->check_license();
        }

        $timekeeper = TimeKeeper::find($id);
        $timekeeper->delete();
        $notification = array(
            'message' => 'Deleted successfully!!!',
            'alert-type' => 'error'
        );
        return Redirect('/home/timesheet')->with($notification);;
    }

    private function check_license(){
        $emp = Auth::user()->employee;
        if($emp->license_expire_date < Carbon::now()->toDateString()||$emp->first_aid_expire_date < Carbon::now()->toDateString()){
            $notification = array(
                'message' => 'sorry! your license is expired.',
                'alert-type' => 'warning'
            );
        }elseif($comp = $emp->compliances->where('expire_date','<',Carbon::now()->toDateString())->first()){
            $notification = array(
                'message' => "sorry! your compliance ".$comp->compliance->name." is expired.",
                'alert-type' => 'warning'
            );
        }else{
            return false;
        }
        return Redirect('/home/timesheet')->with($notification);
    }
}
