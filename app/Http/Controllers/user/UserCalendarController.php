<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventToRosterResource;
use App\Models\JobType;
use App\Models\Project;
use App\Models\RoasterStatus;
use App\Models\RoasterType;
use App\Models\TimeKeeper;
use App\Models\Upcomingevent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserCalendarController extends Controller
{
  public function calender()
  {
    $projects = Project::whereHas('client', function ($query) {
      $query->where('status', 1);
    })->where([
      ['company_code', Auth::user()->employee->company],
      ['Status', '1'],
    ])->orderBy('pName', 'asc')->get();
    $job_types = JobType::where('company_code', Auth::user()->employee->company)->get();
    $roaster_types = RoasterType::all();
    $roaster_status = RoasterStatus::where('company_code', Auth::user()->employee->company)->get();


    return view('pages.User.calendar.index', compact('projects', 'job_types', 'roaster_types', 'roaster_status'));
  }

  public function dataget(Request $request)
  {
    $shifts = TimeKeeper::where([
      ['employee_id', Auth::user()->employee->id],
      ['company_code', Auth::user()->employee->company],
      ['roaster_date', '>=', Carbon::now()->subMonths(6)->toDateString()]
    ])
      ->where(function ($q) {
        $q->where('roaster_type', 'Unschedueled');
        $q->orWhere(function ($q) {
          $q->where('roaster_status_id', '!=', Session::get('roaster_status')['Not published']);
        });
      })
      ->where(function ($q) {
        avoid_rejected_key($q);
      });
    if ($projectFilter = $request->query('projectFilter', false)) {
      $shifts->whereIn('project_id', explode(",", $projectFilter));
    }
    $shifts = $shifts->get();

    $events = Upcomingevent::where([
      ['company_code', Auth::user()->company_roles->first()->company->id],
      ['event_date', '>=', Carbon::now()->subMonths(6)->toDateString()]
    ]);
    if ($projectFilter = $request->query('projectFilter', false)) {
      $events->whereIn('project_name', explode(",", $projectFilter));
    }
    $events = $events->get();
    $data = $shifts->merge(EventToRosterResource::collection($events));

    $result['events'] = [];
    $i = 0;

    foreach ($data as $key => $value) {
      $result['events'][$i]['id'] = $value->project->pName;
      $result['events'][$i]['title'] = $value->project->pName . '
      ' . Carbon::parse($value->shift_start)->format('H:i') . '-' . Carbon::parse($value->shift_end)->format('H:i ');

      $value['latest'] = true;
      if ($value->roaster_type == 'Unschedueled') {
        $status = 'bg-light-info';
      } elseif (Carbon::parse($value->shift_start) < Carbon::now() && Carbon::parse($value->shift_end) < Carbon::now()) {
        $status = $value->sing_in ? 'bg-light-primary' : 'bg-light-warning';
        $value['latest'] = false;
      } elseif ($value->roaster_status_id == Session::get('roaster_status')['Published']) {
        $status = 'bg-light-danger';
      } else {
        $status = 'bg-light-success';
      }
      $value['calendar'] = $status;
      $value['is_applied'] = $this->apply_status($value);

      $result['events'][$i]['extendedProps'] = $value;
      $result['events'][$i]['start'] = Carbon::parse($value->shift_start)->toDateString();
      $result['events'][$i]['end'] = Carbon::parse($value->shift_start)->toDateString();

      $start = Carbon::parse($value->shift_start);
      $end = Carbon::parse($value->shift_end);
      $diff = $start->floatDiffInRealHours($end);

      // "<div><p>Name: " . $value->fname . "</p><p>Time:".date('M-d-y', strtotime($value->roasterStartDate)) . " - ".date('M-d-y', strtotime($value->roasterEndDate))."</p><p><b>Project: " . $value->pName . "</b></p><p><b>Payment: " . $value->amount . "</b></p><p><b>Hours: " . $value->duration . "</b></p></div>";          
      $result['events'][$i]['description'] = "<b>Address : " . $value->project->suburb . " " . $value->project->project_address . ".<br>Duration : " . round($diff, 2) . ' hours';
      $i++;
    }
    return $result;
  }
  
  protected function apply_status($value){
    if ($value->roaster_type == 'Unschedueled') {
        return 1;
    }elseif ($value->roaster_status_id == Session::get('roaster_status')['Rejected']) {
        return 1;
    } elseif ($value->roaster_status_id == Session::get('roaster_status')['Accepted']) {
        return 1;
    } else {
        return 0;
    }
}
}
