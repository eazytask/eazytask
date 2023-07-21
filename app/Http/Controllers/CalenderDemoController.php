<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use App\Models\TimeKeeper;
use App\Models\Payment;
use App\Models\RoasterType;
use App\Models\RoasterStatus;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobType;

class CalenderDemoController extends Controller
{
  public function index($id)
  {

    return view('pages.Admin.calender.index');
  }
  public function calender_demo($id)
  {
    // $timekeepers= TimeKeeper::where('user_id',Auth::id())->get();
    $employees = Employee::where([
      ['company', Auth::user()->company_roles->first()->company->id],
      ['role', 3],
      ['status', 1]
    ])
      ->where(function ($q) {
        avoid_expired_license($q);
      })
      ->orderBy('fname', 'asc')->get();
    $projects = Project::whereHas('client', function ($query) {
      $query->where('status', 1);
    })->where([
      ['company_code', Auth::user()->company_roles->first()->company->id],
      ['Status', '1'],
    ])->orderBy('pName', 'asc')->get();
    $job_types = JobType::where('company_code', Auth::user()->company_roles->first()->company->id)->get();
    $roaster_status = RoasterStatus::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('name', 'asc')->get();
    return view('pages.Admin.calender.demo', compact('employees', 'projects', 'job_types', 'roaster_status'));
  }

  public function dataget(Request $request)
  {
    // return $events;
    $data = TimeKeeper::select('time_keepers.id as tid', 'time_keepers.user_id', 'time_keepers.payment_status', 'time_keepers.company_code', 'time_keepers.employee_id', 'time_keepers.client_id', 'time_keepers.project_id', 'time_keepers.company_id', 'time_keepers.roaster_date', 'time_keepers.shift_start', 'time_keepers.shift_end', 'time_keepers.sing_in', 'time_keepers.sing_out', 'time_keepers.duration', 'time_keepers.ratePerHour', 'time_keepers.amount', 'time_keepers.job_type_id', 'time_keepers.roaster_status_id', 'time_keepers.roaster_type', 'time_keepers.remarks', 'e.fname', 'e.mname', 'e.lname', 'c.cname', 'p.pName')
      ->where('time_keepers.user_id', Auth::id())
      ->leftJoin('clients as c', 'c.id', 'time_keepers.client_id')
      ->leftJoin('projects as p', 'p.id', 'time_keepers.project_id')
      ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id');
    if ($employeeFilter = $request->query('employeeFilter', false)) {
      $data->whereIn('time_keepers.employee_id', explode(",", $employeeFilter));
    }
    if ($projectFilter = $request->query('projectFilter', false)) {
      $data->whereIn('time_keepers.project_id', explode(",", $projectFilter));
    }
    $data = $data->orderBy('fname')->get();
    $result['events'] = [];
    $i = 0;
    foreach ($data as $key => $value) {
      $result['events'][$i]['id'] = $value->tid;
      $result['events'][$i]['title'] = $value->fname;
      $value['calendar'] = $value->fname;
      $result['events'][$i]['extendedProps'] = $value;
      $result['events'][$i]['start'] = $value->shift_start;
      $result['events'][$i]['end'] = $value->shift_end;
      // $result['events'][$i]['start'] = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse(date('Y-m-d',strtotime($value->shift_start)),'UTC'));          
      // $result['events'][$i]['end'] = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse(date('Y-m-d',strtotime($value->shift_end)),'UTC'));

      $start = date_create($value->shift_start);
      $end = date_create($value->shift_end);
      $diff = date_diff($end, $start)->format('%d days %h hours %i minutes');

      // "<div><p>Name: " . $value->fname . "</p><p>Time:".date('M-d-y', strtotime($value->roasterStartDate)) . " - ".date('M-d-y', strtotime($value->roasterEndDate))."</p><p><b>Project: " . $value->pName . "</b></p><p><b>Payment: " . $value->amount . "</b></p><p><b>Hours: " . $value->duration . "</b></p></div>";          
      $result['events'][$i]['description'] = "<b>Name : " . $value->fname . " " . $value->mname . " " . $value->lname . "<br>Project : " . $value->pName . "<br>Time : " . date('d-M-y', strtotime($value->shift_start)) . " - " . date('d-M-y', strtotime($value->shift_end)) . "<br>Payment : " . $value->amount . "<br>Hours : " . $diff . "</b>";
      $i++;
    }
    return $result;
  }

  public function storeCalenderTimeKeeper(Request $request)
  {
    // dd('demo');
    //roster store
    $project = Project::find($request->project_id);

    $shift_start = Carbon::parse($request->roaster_date . $request->shift_start);
    $shift_end = Carbon::parse($shift_start)->addMinute($request->duration * 60);

    $timekeeper = new TimeKeeper();
    $timekeeper->user_id = Auth::id();
    $timekeeper->company_code = Auth::user()->company_roles->first()->company->id;
    $timekeeper->employee_id = $request->employee_id;
    $timekeeper->client_id = $project->clientName;
    $timekeeper->project_id = $request->project_id;
    $timekeeper->company_id = Auth::id();
    $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
    $timekeeper->shift_start = $shift_start;
    $timekeeper->shift_end = $shift_end;

    $start = Carbon::parse($request->shift_start);
    $end = Carbon::parse($request->shift_end);
    $timekeeper->duration = $request->duration;
    $timekeeper->ratePerHour = $request->ratePerHour;
    $timekeeper->amount = $request->amount;
    $timekeeper->job_type_id = $request->job_type_id;
    $timekeeper->roaster_status_id = $request->roaster_status_id;
    $timekeeper->roaster_type = $request->roaster_type;
    $timekeeper->remarks = $request->remarks;
    $timekeeper->created_at = Carbon::now();
    //$timekeeper = $request->query('id');
    if ($timekeeper->save()) {
      // $payment= new Payment;
      // $payment->roaster_id= $timekeeper->id;
      // $payment->save();

      // $roster= new RoasterType;
      // $roster->roaster_id= $timekeeper->id;
      // $roster->save();

      $notification = array(
        'message' => 'Roster Successfully Added !!!',
        'alert-type' => 'success'
      );
    } else {
      $notification = array(
        'message' => 'Scheduler Not Added !!!',
        'alert-type' => 'error'
      );
    }

    return $notification;
  }

  public function updateCalenderTimeKeeper(Request $request)
  {
    // dd('demo');
    //roster store
    $timekeeper = TimeKeeper::where('user_id', Auth::id())->find($request->id);
    if ($timekeeper != '') {
      $project = Project::find($request->project_id);
      // $timekeeper->user_id= Auth::id();        
      // $timekeeper->company_code= Auth::user()->company_roles->first()->company->id;

      $request->roaster_date =  Carbon::parse($request->roaster_date)->format('d-m-Y');
      $shift_start = Carbon::parse($request->roaster_date . $request->shift_start);
      $shift_end = Carbon::parse($shift_start)->addMinute($request->duration * 60);

      $timekeeper->employee_id = $request->employee_id;
      $timekeeper->client_id = $project->clientName;
      $timekeeper->project_id = $request->project_id;
      // $timekeeper->company_id= Auth::id();
      $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
      $timekeeper->shift_start = $shift_start;
      $timekeeper->shift_end = $shift_end;

      $start = Carbon::parse($request->roasterStartDate);
      $end = Carbon::parse($request->roasterEndDate);
      $timekeeper->duration = $request->duration;
      $timekeeper->ratePerHour = $request->ratePerHour;
      $timekeeper->amount = $request->amount;
      $timekeeper->job_type_id = $request->job_type_id;
      $timekeeper->roaster_status_id = $request->roaster_status_id;
      $timekeeper->roaster_type = $request->roaster_type;
      $timekeeper->remarks = $request->remarks;
      $timekeeper->updated_at = Carbon::now();

      //$timekeeper = $request->query('id');
      $timekeeper->save();
      $notification = array(
        'message' => 'Scheduler Updated Successfully !!!',
        'alert-type' => 'success'
      );
    } else {
      $notification = array(
        'message' => 'Scheduler Not Updated !!!',
        'alert-type' => 'error'
      );
    }
    return $notification;
  }

  public function deleteCalenderTimeKeeper(Request $request)
  {
    $timekeeper = TimeKeeper::where('user_id', Auth::id())->find($request->id);
    if ($timekeeper != '') {
      $timekeeper->delete();
      $notification = array(
        'message' => 'Scheduler Deleted Successfully !!!',
        'alert-type' => 'success'
      );
    } else {
      $notification = array(
        'message' => 'Scheduler Not Deleted !!!',
        'alert-type' => 'error'
      );
    }
    return $notification;
  }
}
