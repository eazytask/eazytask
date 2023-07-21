<?php

namespace App\Http\Controllers\admin;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\JobType;
use App\Models\RoasterType;
use App\Models\RoasterStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// use Session;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NewTimeKeeperController;
use App\Models\TimeKeeper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ViewScheduleController extends Controller
{
    public function index($id)
    {
        $timekeepers = [];
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

        Session::put('fromDate', '');
        Session::put('toDate', '');
        Session::put('schedule', '');
        Session::put('employee_id', '');
        Session::put('project_id', '');
        Session::put('sort_by', '');

        return view('pages.Admin.view_schedule.index', compact('timekeepers', 'employees', 'projects'));
    }

    public function search(Request $request)
    {
        $fromDate = $request->input('start_date');
        $fromDate = Carbon::parse($fromDate);

        $toDate = $request->input('end_date');
        $toDate = Carbon::parse($toDate);

        $schedule = $request->input('schedule');
        $employee_id = $request->input('employee_id');
        $project_id = $request->input('project_id');
        $sortby = $request->input('sort_by');

        Session::put('fromDate', $fromDate);
        Session::put('toDate', $toDate);
        Session::put('schedule', $schedule);
        Session::put('employee_id', $employee_id);
        Session::put('project_id', $project_id);
        Session::put('sort_by', $sortby);

        Session::put('current_employee', '');

        return $this->searchModule($fromDate, $toDate, $schedule, $employee_id, $project_id);
    }

    public function update(Request $request)
    {
        // $project = Project::find($request->project_id);
        $timekeeper = TimeKeeper::find($request->timepeeper_id);
        // $request->roaster_date =  Carbon::parse($request->roaster_date)->format('d-m-Y');
        $shift_start = Carbon::parse($timekeeper->roaster_date . $request->app_start);
        $shift_end = Carbon::parse($shift_start)->addMinute($request->app_duration * 60);


        // $timekeeper->employee_id = $request->employee_id;
        // $timekeeper->client_id = $project->clientName;
        // $timekeeper->project_id = $request->project_id;
        // $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
        $timekeeper->Approved_start_datetime = $shift_start;
        $timekeeper->Approved_end_datetime = $shift_end;
        $timekeeper->app_duration = $request->app_duration;
        $timekeeper->app_rate = $request->app_rate;
        $timekeeper->app_amount = $request->app_amount;
        // $timekeeper->job_type_id = $request->job_type_id;

        // $timekeeper->remarks = $request->remarks;
        $timekeeper->save();

        $fromDate = Session::get('fromDate');
        $toDate = Session::get('toDate');
        $schedule = Session::get('schedule');
        $employee_id = Session::get('employee_id');
        $project_id = Session::get('project_id');

        Session::put('current_employee', $request->employee_id);

        return $this->searchModule($fromDate, $toDate, $schedule, $employee_id, $project_id);
    }

    public function delete($id)
    {
        $timekeeperController = new NewTimeKeeperController;
        $timekeeperController->delete($id);

        $fromDate = Session::get('fromDate');
        $toDate = Session::get('toDate');
        $schedule = Session::get('schedule');
        $employee_id = Session::get('employee_id');
        $project_id = Session::get('project_id');

        return $this->searchModule($fromDate, $toDate, $schedule, $employee_id, $project_id);
    }

    public function searchModule($fromDate, $toDate, $schedule, $employee_id, $project_id)
    {
        $filter_roaster_type = $schedule && $schedule != 'All' ? ['roaster_type', $schedule] : ['employee_id', '>', 0];
        // dd($filter_roaster_type);
        $filter_employee = $employee_id ? ['employee_id', $employee_id] : ['employee_id', '>', 0];
        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];

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
        $roaster_types = RoasterType::all();
        // $roaster_status = RoasterStatus::where('company_code', Auth::user()->company_roles->first()->company->id)->get();

        if (Session::get('sort_by') && Session::get('sort_by') == 'Venue') {
            $timekeepers = DB::table('time_keepers')
                ->select(DB::raw(
                    'e.* ,
                            e.pName as name,
                            sum(time_keepers.duration) as total_hours,
                            sum(time_keepers.amount) as total_amount ,
                            count(time_keepers.id) as record'

                ))
                ->leftJoin('projects as e', 'e.id', 'time_keepers.project_id')
                ->groupBy("e.id")
                ->orderBy('e.pName', 'asc')
                ->whereBetween('roaster_date', [$fromDate, $toDate])
                ->where([
                    ['time_keepers.company_code', Auth::user()->company_roles->first()->company->id],
                    $filter_roaster_type,
                    $filter_employee,
                    $filter_project,
                ])
                ->orderBy('name')
                ->get();
        } elseif (Session::get('sort_by') && Session::get('sort_by') == 'Date') {
            $timekeepers = DB::table('time_keepers')
                ->select(DB::raw(
                    'time_keepers.roaster_date,
                        time_keepers.roaster_date as id,
                            sum(time_keepers.duration) as total_hours,
                            sum(time_keepers.amount) as total_amount ,
                            count(time_keepers.id) as record'

                ))
                ->groupBy("time_keepers.roaster_date")
                ->orderBy('time_keepers.roaster_date', 'asc')
                ->whereBetween('roaster_date', [$fromDate, $toDate])
                ->where([
                    ['company_code', Auth::user()->company_roles->first()->company->id],
                    $filter_roaster_type,
                    $filter_employee,
                    $filter_project,
                ])
                ->get();
        } elseif (Session::get('sort_by') && Session::get('sort_by') == 'Client') {
            $timekeepers = DB::table('time_keepers')
                ->select(DB::raw(
                    'e.* ,
                            e.cname as name,
                            sum(time_keepers.duration) as total_hours,
                            sum(time_keepers.amount) as total_amount ,
                            count(time_keepers.id) as record'

                ))
                ->leftJoin('clients as e', 'e.id', 'time_keepers.client_id')
                ->groupBy("e.id")
                ->orderBy('e.cname', 'asc')
                ->whereBetween('roaster_date', [$fromDate, $toDate])
                ->where([
                    ['time_keepers.company_code', Auth::user()->company_roles->first()->company->id],
                    $filter_roaster_type,
                    $filter_employee,
                    $filter_project,
                ])
                ->orderBy('name')
                ->get();
        } else {
            $timekeepers = DB::table('time_keepers')
                ->select(DB::raw(
                    'e.* ,
                            e.fname as name,
                            sum(time_keepers.duration) as total_hours,
                            sum(time_keepers.amount) as total_amount ,
                            count(time_keepers.id) as record'

                ))
                ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
                ->where([
                    ['e.company', Auth::user()->company_roles->first()->company->id],
                    ['e.role', 3]
                ])
                ->groupBy("e.id")
                ->orderBy('e.fname', 'asc')
                ->whereBetween('roaster_date', [$fromDate, $toDate])
                ->where([
                    ['company_code', Auth::user()->company_roles->first()->company->id],
                    $filter_roaster_type,
                    $filter_employee,
                    $filter_project,
                ])
                //     ->where(function ($q) {
                //     avoid_rejected_key($q);
                // })
                ->orderBy('name')
                ->get();
        } // dd($timekeepers);
        return view('pages.Admin.view_schedule.index', compact('employees', 'projects', 'timekeepers', 'job_types', 'roaster_types'));
    }

    public function approve($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $timekeeper = TimeKeeper::find($id);
            if ($timekeeper) {
                if ($timekeeper->shift_end <= Carbon::now()) {
                    $timekeeper->is_approved = 1;
                    $timekeeper->save();
                }
            }
        }

        $fromDate = Session::get('fromDate');
        $toDate = Session::get('toDate');
        $schedule = Session::get('schedule');
        $employee_id = Session::get('employee_id');
        $project_id = Session::get('project_id');

        Session::put('current_employee', $timekeeper ? $timekeeper->employee_id : '');

        return $this->searchModule($fromDate, $toDate, $schedule, $employee_id, $project_id);
    }
}
