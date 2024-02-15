<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Eventrequest;
use App\Models\Inductedsite;
use App\Models\JobType;
use App\Models\Upcomingevent;
use App\Notifications\NewShiftNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewEventNotification;
use App\Mail\NotifyNewEvent;

class AdminEventRequestController extends Controller
{
  public function index()
  {
    $projects = Project::whereHas('client', function ($query) {
      $query->where('status', 1);
    })->where([
      ['company_code', Auth::user()->company_roles->first()->company->id],
      ['Status', '1'],
    ])->orderBy('pName', 'asc')->get();

    $job_types = JobType::where('company_code', Auth::user()->company_roles->first()->company->id)
    ->orderBy('id', 'ASC')
    ->groupBy('name')
    ->get();

    return view('pages.Admin.event_request.index', compact('projects','job_types'));
  }

  public function dataget(Request $request){
    Session::put('current_week', 0);
    return $this->search($request);
  }

  public function search(Request $request)
  {
    if($request->goto == 'previous'){
      $current_week = Session::get('current_week');
      Session::put('current_week', $current_week - 1);

      $week = Carbon::now()->addWeek(Session::get('current_week'));
    }elseif($request->goto == 'next'){
      $current_week = Session::get('current_week');
      Session::put('current_week', $current_week + 1);

      $week = Carbon::now()->addWeek(Session::get('current_week'));
    }else{
      $week = Carbon::now();
    }

    $start_date = Carbon::parse($week)->startOfWeek();
    $end_date = Carbon::parse($week)->endOfWeek();


    $filter_project = $request->projectFilter ? ['project_name', $request->projectFilter] : ['project_name', '>', 0];
    $data = Upcomingevent::where([
      ['user_id', Auth::id()],
      $filter_project,
    ])
    ->whereBetween('event_date', [$start_date, $end_date])
    ->get();
    $result['events'] = [];
    $i = 0;

    foreach ($data as $key => $value) {

      $event_requests = Eventrequest::where('event_id', $value->id)->get();

      $employees = Employee::where([
        ['company', Auth::user()->company_roles->first()->company->id],
        ['role', 3],
        ['status', 1]
      ])
      ->where(function ($q) {
        avoid_expired_license($q);
      })
      ->orderBy('fname','asc')
      ->get();
      foreach ($employees as $k => $employee) {
        //employee
        $employees[$k] = $employee;
        //requested employee
        $requested = Eventrequest::where([
          ['employee_id', $employee->id],
          ['event_id', $value->id]
        ])->first();
        $employees[$k]['requested'] = $requested ? true : false;

        //inducted employee
        $inducted = Inductedsite::where([
          ['employee_id', $employee->id],
          ['user_id', Auth::id()],
        ])->first();
        $employees[$k]['inducted'] = $inducted ? true : false;

        $shift_start = $value->shift_start;
        $shift_end = $value->shift_end;

        $employee_status = TimeKeeper::where([
          ['employee_id', $employee->id],
          ['user_id', $value->user_id],
          ['project_id', $value->project_name],
          ['client_id', $value->client_name],
          // ['roaster_date', Carbon::parse($value->event_date)],
        ])
          ->where(function ($q) use ($shift_start, $shift_end) {
            $q->where('shift_start', '>=', $shift_start);
            $q->where('shift_start', '<=', $shift_end);
            $q->orWhere(function ($q) use ($shift_end, $shift_start) {
              $q->where('shift_end', '>=', $shift_start);
              $q->where('shift_end', '<=', $shift_end);
            });
          })
          ->count();
        $employees[$k]['status'] = $employee_status ? 'Added' : 'Waiting';
      }

      if (Carbon::parse($value->event_date)->toDateString() < Carbon::now()->toDateString()) {
        $status = $event_requests->count() ? 'bg-light-secondary' : 'bg-light-danger';
        $value['latest'] = false;
      } else {
        $status = $event_requests->count() ? 'bg-light-success' : 'bg-light-primary';
        $value['latest'] = true;
      }
      $value['calendar'] = $status;
      $value['employees'] = $employees;

      $result['events'][$i]['id'] = $value->id;
      $result['events'][$i]['title'] = $value->project->pName;
      $result['events'][$i]['extendedProps'] = $value;
      $result['events'][$i]['employees'] = $employees;
      $result['events'][$i]['start'] = $value->event_date;
      // $result['events'][$i]['start'] = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse(date('Y-m-d',strtotime($value->shift_start)),'UTC'));          
      // $result['events'][$i]['end'] = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse(date('Y-m-d',strtotime($value->shift_end)),'UTC'));

      // $start = date_create($value->shift_start);
      // $end = date_create($value->shift_end);
      // $diff = date_diff($end, $start)->format('%d days %h hours %i minutes');

      $result['events'][$i]['description'] = "event desctiption";
      $i++;
    }
    $result['date_between'] = $start_date->format('d M, Y').' - '.$end_date->format('d M, Y');
    return response()->json($result);
  }

  public function publish(Request $request)
  {
    $event = Upcomingevent::find($request->event_id);
    $pro = $event->project;
    $msg = 'There is an shift at '.$pro->pName.' for week ending ' . Carbon::parse($event->event_date)->endOfWeek()->format('d-m-Y');

    foreach ($request->employee_ids as $employee_id) {
      $shift_start = Carbon::parse($event->shift_start);
      $shift_end = Carbon::parse($event->shift_end);

      $duration = round($shift_start->floatDiffInRealHours($shift_end), 2);

      $timekeeper = new TimeKeeper();
      $timekeeper->user_id = Auth::id();
      $timekeeper->employee_id = $employee_id;
      $timekeeper->client_id = $event->client_name;
      $timekeeper->project_id = $event->project_name;
      $timekeeper->company_id = Auth::id();
      $timekeeper->roaster_date = Carbon::parse($event->event_date);
      $timekeeper->shift_start = $shift_start;
      $timekeeper->shift_end = $shift_end;
      $timekeeper->company_code = $event->company_code;
      $timekeeper->ratePerHour = $event->rate;
      $timekeeper->roaster_status_id = Session::get('roaster_status')['Published'];
      $timekeeper->roaster_type = 'Schedueled';
      $timekeeper->remarks = $event->remarks;
      $timekeeper->duration = $duration;
      $timekeeper->amount = $duration * $event->rate;

      $timekeeper->job_type_id =   $event->job_type_name;
      // $timekeeper->roaster_id = Auth::id();
      $timekeeper->created_at = Carbon::now();
      $timekeeper->save();

      $timekeeper->employee->user->notify(new NewShiftNotification($msg,$timekeeper));
      push_notify('Shift Alert:',$msg.' Please log on to eazytask to accept / declined it.',$timekeeper->employee->employee_role,$timekeeper->employee->firebase,'unconfirmed-shift');
    }
    return Response()->json(['status' => 'sccess']);
  }

  public function complete(Request $request)
  {
    $event = Upcomingevent::find($request->event_id);
    $event->status_text = 'complete';
    $event->save();

    return Response()->json(['status' => 'sccess']);
  }

  public function sendNotif(Request $request, $ext=false)
  {
    $event = Upcomingevent::find($request->event_id);

    if(Carbon::parse($event->event_date)->toDateString() != Carbon::now()->toDateString()){
          $pro = $event->project;
          $ext = $ext?' updated':'';
          $msg = 'There is an event'.$ext.' "'.$pro->pName.'" on ' . Carbon::parse($event->event_date)->format('d-m-Y') . '(' . Carbon::parse($event->shift_start)->format('H:i') . '-' . Carbon::parse($event->shift_end)->format('H:i') . ') near "'.$pro->project_address.' '.$pro->suburb.' '.$pro->project_state.'"';

          $employees = Employee::where([
              ['company', Auth::user()->company_roles->first()->company->id],
              ['role', 3],
              ['status', 1]
          ])
          ->where(function ($q) {
              avoid_expired_license($q);
          })
          ->get();

          foreach ($employees as $emp) {
              $emp->user->notify(new NewEventNotification($msg));
              push_notify('Event Alert :', $msg.'. If you interested please open app and send event request',$emp->employee_role, $emp->firebase,'user-event',$event->id);
              
              try {
                  Mail::to($emp->user->email)->send(new NotifyNewEvent($emp, $msg));
              } catch (\Exception $e) {
              }
          }
      }
      return Response()->json(['status' => 'sccess']);
    // $event = Upcomingevent::find($request->event_id);
    // $pro = $event->project;
    // $msg = 'There is an shift at '.$pro->pName.' for week ending ' . Carbon::parse($event->event_date)->endOfWeek()->format('d-m-Y');

    // foreach ($request->employee_ids as $employee_id) {
    //   $shift_start = Carbon::parse($event->shift_start);
    //   $shift_end = Carbon::parse($event->shift_end);

    //   $duration = round($shift_start->floatDiffInRealHours($shift_end), 2);

    //   $timekeeper = TimeKeeper::where('employee_id', $employee_id)
    //   ->where('client_id', $event->client_name)
    //   ->where('project_id', $event->project_name)
    //   ->where('roaster_date', Carbon::parse($event->event_date))
    //   ->where('ratePerHour', $event->rate)
    //   ->where('job_type_id', $event->job_type_name)
    //   ->where('roaster_type', 'Schedueled')
    //   ->first();

    //   $timekeeper->employee->user->notify(new NewShiftNotification($msg,$timekeeper));
    //   push_notify('Shift Alert:',$msg.' Please log on to eazytask to accept / declined it.',$timekeeper->employee->employee_role,$timekeeper->employee->firebase,'unconfirmed-shift');
    // }
  }
}
