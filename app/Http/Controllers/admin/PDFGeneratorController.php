<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Project;
use App\Models\RoasterStatus;
use App\Models\TimeKeeper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PDFGeneratorController extends Controller
{
    public function all_report(){
        $employees = Employee::where([
            ['company',Auth::user()->company_roles->first()->company->id],
            ['role',3],
            ['status', 1]
        ])->orderBy('fname', 'asc')->get();
        $clients = Client::where('company_code', Auth::user()->company_roles->first()->company->id)->where('status', '1')->orderBy('cname', 'asc')->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        $job_types = JobType::where('company_code', Auth::user()->company_roles->first()->company->id)->get();
        $roaster_status = RoasterStatus::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('name', 'asc')->get();
        $timekeepers = [];

        Session::put('fromDate','');
        Session::put('toDate','');
        Session::put('schedule','');
        Session::put('employee_id','');
        Session::put('project_id','');
        Session::put('client_id','');
        Session::put('payment_status','');
        Session::put('sort_by','');

        return view('pages.Admin.pdf.all_report', compact('employees', 'projects', 'clients', 'timekeepers','job_types','roaster_status'));
    }

    public function search_report(Request $request){

        $employees = Employee::where([
            ['company',Auth::user()->company_roles->first()->company->id],
            ['role',3],
            ['status', 1]
        ])->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        $clients = Client::where('company_code', Auth::user()->company_roles->first()->company->id)->where('status','1')->get();
        
        $timekeepers = $this->search_module($request);
        $all_roaster=$this->all_roaster($request);

        return view('pages.Admin.pdf.all_report', compact('employees', 'projects', 'clients', 'timekeepers','all_roaster'));
    }

    public function search_module($request){
        $fromRoaster = $request->input('start_date');
        $fromRoaster = $fromRoaster ? Carbon::parse($fromRoaster) : Carbon::now()->startOfYear();
        $toRoaster = $request->input('end_date');
        $toRoaster = $toRoaster ? Carbon::parse($toRoaster) : Carbon::now()->endOfYear();

        $project_id = $request->input('project_id');
        $client_id = $request->input('client_id');
        $employee_id = $request->input('employee_id');
        $schedule = $request->input('schedule');
        $payment_status = $request->input('payment_status');
        $sortby = $request->input('sort_by');

        Session::put('fromDate',$fromRoaster);
        Session::put('toDate',$toRoaster);
        Session::put('schedule',$schedule);
        Session::put('employee_id',$employee_id);
        Session::put('project_id',$project_id);
        Session::put('client_id',$client_id);
        Session::put('payment_status',$payment_status);
        Session::put('sort_by', $sortby);

        $filter_payment = $payment_status != '' ? ['payment_status', $payment_status] : ['employee_id', '>', 0];

        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];
        $filter_client = $client_id ? ['client_id', $client_id] : ['employee_id', '>', 0];
        $filter_employee = $employee_id ? ['employee_id', $employee_id] : ['employee_id', '>', 0];
        // $filter_schedule = $schedule ? ['schedule', $schedule] : ['employee_id', '>', 0];
        $filter_schedule = $schedule ? ['roaster_type', $schedule] : ['employee_id', '>', 0];

        $timekeepers = TimeKeeper::where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            $filter_payment,
            // ['payment_status', 0],
            $filter_employee,
            $filter_project,
            $filter_client,
            $filter_schedule
        ])
            ->whereBetween('roaster_date', [$fromRoaster, $toRoaster])
        ->orderBy('employee_id', 'asc')
        ->get();

        return $timekeepers;
        // return redirect()->back()->with(['timekeepers',$timekeepers]);
    }

    public function all_roaster($request){
        $fromRoaster = $request->start_date;
        $fromDate = $fromRoaster ? Carbon::parse($fromRoaster) : Carbon::now()->startOfYear();
        $toRoaster = $request->end_date;
        $toDate = $toRoaster ? Carbon::parse($toRoaster) : Carbon::now()->endOfYear();

        $project_id = $request->project_id;
        $client_id = $request->client_id;
        $employee_id = $request->employee_id;
        $schedule = $request->schedule;

        $filter_payment = $request->payment_status != '' ? ['payment_status', $request->payment_status] : ['employee_id', '>', 0];
        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];
        $filter_client = $client_id ? ['client_id', $client_id] : ['employee_id', '>', 0];
        $filter_employee = $employee_id ? ['employee_id', $employee_id] : ['employee_id', '>', 0];
        $filter_schedule = $schedule ? ['roaster_type', $schedule] : ['employee_id', '>', 0];
        

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
                    $filter_schedule,
                    $filter_employee,
                    $filter_project,
                    $filter_payment,
                    $filter_client
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
                    $filter_schedule,
                    $filter_employee,
                    $filter_project,
                    $filter_payment,
                    $filter_client
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
                    $filter_schedule,
                    $filter_employee,
                    $filter_project,
                    $filter_payment,
                    $filter_client
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
                    $filter_schedule,
                    $filter_employee,
                    $filter_project,
                    $filter_payment,
                    $filter_client
                ])
                ->orderBy('name')
                ->get();
        }

        $all_roaster = [];
        foreach ($timekeepers as $i => $timekeeper) {
            if(Session::get('sort_by')){
                $sort = Session::get('sort_by');
                if($sort=='Venue'){
                $filter_sort_by = ['project_id',$timekeeper->id];
                }
                elseif($sort=='Date'){
                $filter_sort_by = ['roaster_date',$timekeeper->id];
                }elseif($sort=='Client'){
                $filter_sort_by = ['client_id',$timekeeper->id];
                }
                else{
                $filter_sort_by = ['employee_id',$timekeeper->id];
                }
            }else{
            $filter_sort_by = ['employee_id',$timekeeper->id];
            }

            $total_hours = 0;
            $total_amount = 0;
            $all_roaster[$i]['name'] = $timekeeper->name;
            $timekeeperData = TimeKeeper::where([
                ['company_code', Auth::user()->company_roles->first()->company->id],
                $filter_payment,
                $filter_employee,
                $filter_project,
                $filter_client,
                $filter_schedule,
                $filter_sort_by
            ])
            ->whereBetween('roaster_date', [Carbon::parse($fromRoaster), Carbon::parse($toRoaster)])->orderBy('roaster_date', 'asc')->get();

            $duration = $timekeeperData->sum("duration");
            $amount = $timekeeperData->sum("amount");
            $total_hours += floatval($duration);
            $total_amount += floatval($amount);

            $all_roaster[$i]['roasters'] = $timekeeperData;
            $all_roaster[$i]['total_hours'] = $total_hours;
            $all_roaster[$i]['total_amount'] = $total_amount;
        }
        return $all_roaster;
    }
    
    public function employee_wise($request){
        $this->employee_wise_summery($request);

        $fromRoaster = $request->start_date;
        $fromRoaster = $fromRoaster ? Carbon::parse($fromRoaster) : Carbon::now()->startOfYear();
        $toRoaster = $request->end_date;
        $toRoaster = $toRoaster ? Carbon::parse($toRoaster) : Carbon::now()->endOfYear();

        $project_id = $request->project_id;
        $client_id = $request->client_id;
        $employee_id = $request->employee_id;
        $schedule = $request->schedule;

        $filter_payment = $request->payment_status != '' ? ['payment_status', $request->payment_status] : ['employee_id', '>', 0];
        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];
        $filter_client = $client_id ? ['client_id', $client_id] : ['employee_id', '>', 0];
        $filter_employee = $employee_id ? ['employee_id', $employee_id] : ['employee_id', '>', 0];
        $filter_schedule = $schedule ? ['roaster_type', $schedule] : ['employee_id', '>', 0];

        $timekeepers = DB::table('time_keepers')
            ->select(DB::raw(
                '*',
                'e.* ,
                e.fname as name,
                sum(time_keepers.duration) as total_hours,
                sum(time_keepers.amount) as total_amount ,
                count(time_keepers.id) as record'

            ))
            ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
            ->where('e.user_id', Auth::id())
            ->groupBy("e.fname")
            ->whereBetween('roaster_date', [$fromRoaster, $toRoaster])
            ->where([
                $filter_payment,
                $filter_project,
                $filter_client,
                $filter_employee,
                $filter_schedule
            ])
            ->get();

        $all_roaster = [];
        foreach ($timekeepers as $i => $timekeeper) {
            $total_hours = 0;
            $total_amount = 0;
            $all_roaster[$i]['name'] = $timekeeper->fname;
            $timekeeperData = TimeKeeper::where([
                ['employee_id', $timekeeper->id],
                ['company_code', Auth::user()->company->company_code],
                $filter_payment,
                $filter_employee,
                $filter_project,
                $filter_client,
                $filter_schedule
            ])->whereBetween('roaster_date', [Carbon::parse($fromRoaster), Carbon::parse($toRoaster)])->orderBy('roaster_date', 'asc')->get();

            $duration = $timekeeperData->sum("duration");
            $amount = $timekeeperData->sum("amount");
            $total_hours += floatval($duration);
            $total_amount += floatval($amount);

            $all_roaster[$i]['roasters'] = $timekeeperData;
            $all_roaster[$i]['total_hours'] = $total_hours;
            $all_roaster[$i]['total_amount'] = $total_amount;
        }
        return $all_roaster;
    }
    
    public function client_wise($request){
        $this->client_wise_summery($request);

        $fromRoaster = $request->start_date;
        $fromRoaster = $fromRoaster ? Carbon::parse($fromRoaster) : Carbon::now()->startOfYear();
        $toRoaster = $request->end_date;
        $toRoaster = $toRoaster ? Carbon::parse($toRoaster) : Carbon::now()->endOfYear();

        $project_id = $request->project_id;
        $client_id = $request->client_id;
        $employee_id = $request->employee_id;
        $schedule = $request->schedule;
        
        $filter_payment = $request->payment_status != '' ? ['payment_status', $request->payment_status] : ['employee_id', '>', 0];
        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];
        $filter_client = $client_id ? ['client_id', $client_id] : ['employee_id', '>', 0];
        $filter_employee = $employee_id ? ['employee_id', $employee_id] : ['employee_id', '>', 0];
        $filter_schedule = $schedule ? ['roaster_type', $schedule] : ['employee_id', '>', 0];

        $timekeepers = DB::table('time_keepers')
            ->select(DB::raw(
                '*',
                'e.* ,
                e.cname as name,
                sum(time_keepers.duration) as total_hours,
                sum(time_keepers.amount) as total_amount ,
                count(time_keepers.id) as record'

            ))
            ->leftJoin('clients as e', 'e.id', 'time_keepers.client_id')
            ->where('e.user_id', Auth::id())
            ->groupBy("e.cname")
            ->whereBetween('roaster_date', [Carbon::parse($fromRoaster), Carbon::parse($toRoaster)])
            ->where([
                // ['sing_out','!=',null],
                $filter_payment,
                $filter_project,
                $filter_client,
                $filter_employee,
                $filter_schedule
            ])
            ->get();


        $all_roaster = [];
        foreach ($timekeepers as $i => $timekeeper) {
            $total_hours = 0;
            $total_amount = 0;
            $all_roaster[$i]['name'] = $timekeeper->cname;
            $projects = DB::table('time_keepers')
                ->select(DB::raw(
                    '*',
                    'e.* ,
                        e.pName as name'

                ))
                ->leftJoin('projects as e', 'e.id', 'time_keepers.project_id')
                ->where('e.user_id', Auth::id())
                ->groupBy("e.pName")
                ->whereBetween('roaster_date', [Carbon::parse($fromRoaster), Carbon::parse($toRoaster)])
                ->where([
                    // ['sing_out','!=',null],
                    ['client_id', $timekeeper->id],
                    $filter_payment,
                    $filter_project,
                    $filter_client,
                    $filter_employee,
                    $filter_schedule
                ])
                ->get();

            $all_roaster[$i]['projects'] = [];
            $hours = 0;
            $amount = 0;

            foreach ($projects as $n => $project) {
                $hours = 0;
                $amount = 0;
                $all_roaster[$i]['projects'][$n]['pName'] = $project->pName;
                $timekeeperData = TimeKeeper::where([
                    ['client_id', $timekeeper->id],
                    ['project_id', $project->id],
                    ['company_code', Auth::user()->company->company_code],
                    $filter_payment,
                    $filter_employee,
                    $filter_project,
                    $filter_client,
                    $filter_schedule
                ])->whereBetween('roaster_date', [Carbon::parse($fromRoaster), Carbon::parse($toRoaster)])
                    ->orderBy('employee_id', 'asc')->orderBy('roaster_date', 'asc')
                    ->get();

                $duration = $timekeeperData->sum("duration");
                $amount_ = $timekeeperData->sum("amount");
                $hours += floatval($duration);
                $amount += floatval($amount_);

                $all_roaster[$i]['projects'][$n]['roasters'] = $timekeeperData;
                $all_roaster[$i]['projects'][$n]['hours'] = $hours;
                $all_roaster[$i]['projects'][$n]['amount'] = $amount;
            }
            
            $total_hours += $hours;
            $total_amount += $amount;
            
            $all_roaster[$i]['total_hours'] = $total_hours;
            $all_roaster[$i]['total_amount'] = $total_amount;
        }
        return $all_roaster;
    }
    
    public function employee_wise_summery($request){
        $fromRoaster = $request->start_date;
        $fromRoaster = $fromRoaster ? Carbon::parse($fromRoaster) : Carbon::now()->startOfYear();
        $toRoaster = $request->end_date;
        $toRoaster = $toRoaster ? Carbon::parse($toRoaster) : Carbon::now()->endOfYear();

        $project_id = $request->project_id;
        $client_id = $request->client_id;
        $employee_id = $request->employee_id;
        $schedule = $request->schedule;

        $filter_payment = $request->payment_status != '' ? ['payment_status', $request->payment_status] : ['employee_id', '>', 0];
        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];
        $filter_client = $client_id ? ['client_id', $client_id] : ['employee_id', '>', 0];
        $filter_employee = $employee_id ? ['employee_id', $employee_id] : ['employee_id', '>', 0];
        $filter_schedule = $schedule ? ['roaster_type', $schedule] : ['employee_id', '>', 0];

        $timekeepers = DB::table('time_keepers')
            ->select(DB::raw(
                'e.* ,
                e.fname as name,
                sum(time_keepers.duration) as total_hours,
                sum(time_keepers.amount) as total_amount ,
                count(time_keepers.id) as record'

            ))
            ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
            ->where('e.user_id', Auth::id())
            ->groupBy("e.fname")
            ->whereBetween('roaster_date', [$fromRoaster, $toRoaster])
            ->where([
                $filter_payment,
                $filter_project,
                $filter_client,
                $filter_employee,
                $filter_schedule
            ])
            ->get();
            $data= [];
            $data['roasters']=$timekeepers;
            $data['total_hours']=$timekeepers->sum('total_hours');
            $data['total_amount']=$timekeepers->sum('total_amount');
        Session::put('employee_wise_summery',$data);
        return $data;
    }
    
    public function client_wise_summery($request){
        $fromRoaster = $request->start_date;
        $fromRoaster = $fromRoaster ? Carbon::parse($fromRoaster) : Carbon::now()->startOfYear();
        $toRoaster = $request->end_date;
        $toRoaster = $toRoaster ? Carbon::parse($toRoaster) : Carbon::now()->endOfYear();

        $project_id = $request->project_id;
        $client_id = $request->client_id;
        $employee_id = $request->employee_id;
        $schedule = $request->schedule;
        
        $filter_payment = $request->payment_status != '' ? ['payment_status', $request->payment_status] : ['employee_id', '>', 0];
        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];
        $filter_client = $client_id ? ['client_id', $client_id] : ['employee_id', '>', 0];
        $filter_employee = $employee_id ? ['employee_id', $employee_id] : ['employee_id', '>', 0];
        $filter_schedule = $schedule ? ['roaster_type', $schedule] : ['employee_id', '>', 0];

        $timekeepers = DB::table('time_keepers')
            ->select(DB::raw(
                'e.* ,
                e.cname as name,
                sum(time_keepers.duration) as total_hours,
                sum(time_keepers.amount) as total_amount ,
                count(time_keepers.id) as record'

            ))
            ->leftJoin('clients as e', 'e.id', 'time_keepers.client_id')
            ->where('e.user_id', Auth::id())
            ->groupBy("e.cname")
            ->whereBetween('roaster_date', [Carbon::parse($fromRoaster), Carbon::parse($toRoaster)])
            ->where([
                // ['sing_out','!=',null],
                $filter_payment,
                $filter_project,
                $filter_client,
                $filter_employee,
                $filter_schedule
            ])
            ->get();
        
        $data= [];
        $data['roasters']=$timekeepers;
        $data['total_hours']=$timekeepers->sum('total_hours');
        $data['total_amount']=$timekeepers->sum('total_amount');
        Session::put('client_wise_summery',$data);
        return $data;
    }

}
