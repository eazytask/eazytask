<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Project;
use App\Models\RoasterStatus;
use App\Models\RoasterType;
use App\Models\TimeKeeper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;

class PDFGeneratorController extends Controller
{
    // public function index(Request $request)
    // {
    //     if ($request->generate_type == 'employee') {
    //         $all_roaster = $this->employee_wise($request);
    //         return view('pages.Admin.pdf.employee_wise', compact('all_roaster'));
    //     } else {
    //         $all_roaster = $this->client_wise($request);
    //         // return $all_roaster;
    //         return view('pages.Admin.pdf.client_wise', compact('all_roaster'));
    //     }

    //     // return $all_roaster;
    // }
    public function date_wise(){
        $data=[];
        $req = [
            'start_date'=>Carbon::now()->subWeeks(2)->startOfWeek(),
            'end_date'=>Carbon::now()->subWeeks(2)->endOfWeek(),
            'project_id'=>'',
            'client_id'=>'',
            'employee_id'=>'',
            'roaster_status'=>'',
            'payment_status'=>'',
            'schedule'=>''
        ];
        //last week
        $request = (object) $req;
        $data['last_week']['employee_wise_report']= $this->employee_wise($request);
        $data['last_week']['client_wise_report']= $this->client_wise($request);
        $data['last_week']['employee_wise_summery']= $this->employee_wise_summery($request);
        $data['last_week']['client_wise_summery']= $this->client_wise_summery($request);
        //last month
        $month = (object) $req;
        $month->start_date = Carbon::now()->subMonth()->startOfMonth();
        $month->end_date = Carbon::now()->subMonth()->endOfMonth();

        $data['last_month']['employee_wise_report']= $this->employee_wise($month);
        $data['last_month']['client_wise_report']= $this->client_wise($month);
        $data['last_month']['employee_wise_summery']=  $this->employee_wise_summery($month);
        $data['last_month']['client_wise_summery']=  $this->client_wise_summery($month);
        
        return view('pages.Admin.pdf.date_wise_report',compact('data'));
    }

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

        return view('pages.Admin.pdf.all_report', compact('employees', 'projects', 'clients', 'timekeepers','job_types','roaster_status'));
    }

    public function search_report(Request $request){
        $employee_wise_report = $this->employee_wise($request);
        $client_wise_report = $this->client_wise($request);

        Session::put('employee_wise_report',$employee_wise_report);
        Session::put('client_wise_report',$client_wise_report);

        $timekeepers = $this->search_module($request);
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
        $roaster_types = RoasterType::all();
        
        $roaster_status= RoasterStatus::where('user_id',Auth::id())->get();
        // return Session::get('client_wise_report');
        return view('pages.Admin.pdf.all_report', compact('employees', 'projects', 'clients', 'timekeepers','roaster_types','roaster_status'));
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

        Session::put('fromDate',$fromRoaster);
        Session::put('toDate',$toRoaster);
        Session::put('schedule',$schedule);
        Session::put('employee_id',$employee_id);
        Session::put('project_id',$project_id);
        Session::put('client_id',$client_id);
        Session::put('payment_status',$payment_status);

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

    public function employee_wise($request)
    {
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
            ->where([
            ['e.company',Auth::user()->company_roles->first()->company->id],
            ['e.role',3]
        ])
            ->groupBy("e.id")
            ->orderBy('e.fname','asc')
            
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
                ['company_code', Auth::user()->company_roles->first()->company->id],
                $filter_payment,
                $filter_employee,
                $filter_project,
                $filter_client,
                $filter_schedule
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
    public function employee_wise_summery($request)
    {
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
            ->where([
            ['e.company',Auth::user()->company_roles->first()->company->id],
            ['e.role',3]
        ])
            ->groupBy("e.id")
            ->orderBy('e.fname','asc')
            
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

    public function client_wise($request)
    {
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
            ->where([
            ['e.company_code',Auth::user()->company_roles->first()->company->id],
        ])
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
                ->where([
            ['e.company_code',Auth::user()->company_roles->first()->company->id]
        ])
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

            foreach ($projects as $n => $project) {
                $hours = 0;
                $amount = 0;
                $all_roaster[$i]['projects'][$n]['pName'] = $project->pName;
                $timekeeperData = TimeKeeper::where([
                    ['client_id', $timekeeper->id],
                    ['project_id', $project->id],
                    ['company_code', Auth::user()->company_roles->first()->company->id],
                    $filter_payment,
                    $filter_employee,
                    $filter_project,
                    $filter_client,
                    $filter_schedule
                ])
            ->whereBetween('roaster_date', [Carbon::parse($fromRoaster), Carbon::parse($toRoaster)])
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

    public function client_wise_summery($request)
    {
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
            ->where([
            ['e.company_code',Auth::user()->company_roles->first()->company->id]
        ])
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
