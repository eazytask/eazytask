<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\paymentmaster;
use App\Models\Project;
use App\Models\RoasterStatus;
use App\Models\TaskDescription;
use App\Models\TimeKeeper;
use App\Models\Upcomingevent;
use App\Models\Myavailability;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Message;
use App\Models\MessageReply;
use App\Models\MessageConfirm;
use App\Models\UserCompliance;
use App\Models\Inductedsite;

class HomeController extends Controller
{

    public function markNotification()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }
    public function delete_notifications()
    {
        $user = Auth::user();
        $user->notifications()->delete();
        return response()->json(['status' => 'success']);
    }

    public function redirectSubdomain()
    {
        $employees = Employee::where([
            ['userID', Auth::id()],
            ['status', 1],
            ['company', auth()->user()->company_roles->first()->company_code],
            ['role', auth()->user()->company_roles->first()->role]
        ])->get();
        $employee = $employees->first();

        // if (!$employee) {
        //     $notification = array(
        //         'message' => 'Sorry! you are not an active employee.',
        //         'alert-type' => 'error'
        //     );
        //     Auth::logout();
        //     return redirect('https://eazytask.au/autologout');
        //     // return redirect('http://localhost:8000/autologout');
        // }

        // return Auth::user()->employee;
        // return redirect(url('http://'.$employees->first()->company.'.' . env('SITE_URL', 'myroaster.info').'/home'));
        return redirect()->route('user.dashboard');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $project=null;
        $roasters = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id ?? false],
            ['company_code',Auth::user()->employee->company ?? false],
            ['sing_out',null],
        ])->where(function ($q) {
            $q->where('roaster_type','Schedueled');
            $q->where('roaster_status_id',Session::get('roaster_status')['Accepted']);
            $q->orWhere(function ($q) {
                $q->where('roaster_type','Unschedueled');
                $q->where('sing_in', '!=', null);
            });
        })->where(function ($q) {
            $q->where('sing_in', '!=', null);
            $q->orWhere(function ($q) {
                $q->where('shift_end', '>', Carbon::now());
            });
        })->where(function ($q) {
            $q->where('roaster_date', Carbon::now()->format("Y-m-d"));
            $q->orWhere(function ($q) {
                $q->where('roaster_date', Carbon::now()->subDay()->format("Y-m-d"));
                $q->where('shift_end','>', Carbon::now()->format("Y-m-d"));
            });
        })
        ->orderBy('shift_start','asc')->limit(1)->get();
        //for unsheduled user
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        $job_types = JobType::where('company_code', Auth::user()->employee->company ?? false)
        ->orderBy('id', 'ASC')
        ->groupBy('name')
        ->get();

        $roaster_status = RoasterStatus::where('company_code', Auth::user()->employee->company ?? false)->get();

        // upcoming shifts
        $upcoming_roasters = TimeKeeper::where([
            ['time_keepers.employee_id',Auth::user()->employee->id ?? false],
            ['time_keepers.company_code',Auth::user()->employee->company ?? false],
            ['time_keepers.roaster_status_id',Session::get('roaster_status')['Accepted']],
            ['time_keepers.shift_end','>=',Carbon::now()],
            ['time_keepers.sing_in',null]
        ])->leftjoin('clients', 'clients.id', 'time_keepers.client_id')
        ->select('time_keepers.*', 'clients.cimage')
        ->orderBy('time_keepers.shift_start','asc')->limit(3)->get();

        // past shifts
        $past_roasters = TimeKeeper::where([
            ['time_keepers.employee_id',Auth::user()->employee->id ?? false],
            ['time_keepers.company_code',Auth::user()->employee->company ?? false],
            ['time_keepers.shift_end','<=',Carbon::now()]
        ])
        ->where(function ($q) {
            $q->where('time_keepers.sing_in', '!=', null);
            $q->where('time_keepers.sing_out', '!=', null);
            $q->orWhere(function ($q) {
                $q->where('time_keepers.shift_end', '<=', Carbon::now());
            });
        })
        ->where(function ($q) {
            avoid_rejected_key($q);
        })
        ->leftjoin('clients', 'clients.id', 'time_keepers.client_id')
        ->select('time_keepers.*', 'clients.cimage')
        ->orderBy('time_keepers.shift_end','desc')->limit(3)->get();
        // return $past_roasters;

        // unconfirm shifts
        $unconfirm_roasters = TimeKeeper::where([
            ['time_keepers.employee_id',Auth::user()->employee->id ?? false],
            ['time_keepers.company_code',Auth::user()->employee->company ?? false],
            ['time_keepers.roaster_status_id',Session::get('roaster_status')['Published']],
            ['time_keepers.shift_end','>=',Carbon::now()],
        ])
        ->leftjoin('clients', 'clients.id', 'time_keepers.client_id')
        ->orderBy('time_keepers.shift_start','asc')
        ->select('time_keepers.*', 'clients.cimage')->limit(3)->get();

        //upcoming events
        $upcomingevents = Upcomingevent::where([
            ['upcomingevents.company_code', Auth::user()->employee->company ?? false],
            ['event_date', '>', Carbon::now()]
        ])
        ->leftjoin('clients', 'clients.id', 'upcomingevents.client_name')
        ->orderBy('event_date','asc')
        ->limit(3)
        ->select('upcomingevents.*', 'clients.cimage', 'clients.cname')->get();

        //timesheets
        $fromRoaster = Carbon::now()->subWeek()->startOfWeek()->subDays();
        $toRoaster = Carbon::now();
        $timesheets = TimeKeeper::where([
            ['employee_id', Auth::user()->employee->id ?? false],
            ['company_code', Auth::user()->employee->company ?? false],
            ['payment_status', 0],
            ['roaster_type', 'Unschedueled']
        ])
        ->where(function ($q) {
            avoid_rejected_key($q);
        })
        ->orderBy('roaster_date', 'desc')
        ->limit(3)->get();

        //payments
        $payments = paymentmaster::where([
                ['payment_master.employee_id',Auth::user()->employee->id ?? false],
            ])
            ->leftjoin('employees', 'employees.id', 'payment_master.employee_id')
            ->orderBy('payment_master.Payment_Date', 'desc')
            ->select('payment_master.*', 'employees.image')
            ->limit(3)->get();
            
        //time off
        $unavailabilities = Myavailability::where([
            ['employee_id', Auth::user()->employee->id ?? false],
            ['company_code', Auth::user()->employee->company ?? false],
            ['end_date', '>=', Carbon::now()->subMonth(6)],
            ['is_leave', 0]
        ])
            ->orderBy('start_date', 'desc')
            ->get();
        
        $leaves = Myavailability::where([
            ['employee_id', Auth::user()->employee->id ?? false],
            ['company_code', Auth::user()->employee->company ?? false],
            ['end_date', '>=', Carbon::now()->subMonth(6)],
            ['is_leave', 1]
        ])
            ->orderBy('start_date', 'desc')
            ->get();

        $leave_types = LeaveType::all();

        $messages = Message::with('replies', 'confirms')->orderBy('created_at', 'DESC')->limit(3)->get();
        foreach ($messages as $message) {
            // Access message properties
            $message->purposes = $message->getListVenue();
            $message->replies = $message->replies;
            $message->confirms = $message->confirms;
            if($message->need_confirm == 'Y') {
                $message->my_confirm = MessageConfirm::where('message_id', $message->id)->where('user_id', Auth::user()->id)->count() > 0;
            }else{
                $message->my_confirm = false;
            }
        }
        
        $compliances = UserCompliance::select('user_compliances.*', 'compliances.name as compliance_name', DB::raw("CONCAT(employees.fname, ' ', COALESCE(employees.mname, ''), ' ', employees.lname) AS employee_name"), 'employees.contact_number', 'employees.image')
        ->leftjoin('employees', 'employees.email', 'user_compliances.email')
        ->leftjoin('compliances', 'compliances.id', 'user_compliances.compliance_id')
        ->where('user_compliances.email', Auth::user()->email)
        ->orderBy('id', 'desc')
        ->get();
        

        $inductions = Inductedsite::where([
            ['inductedsites.company_code', Auth::user()->company_roles->first()->company->id],
            ['inductedsites.employee_id', Auth::user()->employee->id]
        ])->leftjoin('employees', 'employees.id', 'inductedsites.employee_id')
        ->leftjoin('projects', 'projects.id', 'inductedsites.project_id')
        ->orderBy('employee_id', 'asc')->select('inductedsites.*', 'projects.pName', 'employees.image', DB::raw("CONCAT(employees.fname, ' ', COALESCE(employees.mname, ''), ' ', employees.lname) AS employee_name"))->get();

        return view('pages.User.index',compact('roasters', 'projects','job_types','roaster_status','upcoming_roasters','past_roasters','unconfirm_roasters','upcomingevents','timesheets','payments', 'unavailabilities','leaves','leave_types', 'messages', 'compliances', 'inductions'));
        // return redirect('home/sign/in');
    }

    public function adminHome()
    {
        $data = [];
        $start_week = Carbon::now()->subWeeks(2)->startOfWeek();
        $end_week =  Carbon::now()->subWeeks(2)->endOfWeek();

        $data['total_hour'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('duration');
        // return $end_week->format('h:i D d-y-m');
        $data['total_amount'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('amount');
        $data['total_client'] = Client::where('company_code', Auth::user()->company_roles->first()->company->id)->count();
        $data['total_sites'] = Project::where('company_code', Auth::user()->company_roles->first()->company->id)->count();

        $data['weekly_hour'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('duration');
        $data['weekly_payment'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 1)->whereBetween('roaster_date', [$start_week, $end_week])->sum('amount');
        $data['monthly_earning'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('amount');
        $data['next_event'] = Upcomingevent::where([
            ['user_id', Auth::id()],
            ['shift_start', '>=', Carbon::now()]
        ])->first();

        $data['monthly_expense'] = [];
        $data['monthly_expense'][0] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        $data['monthly_expense'][1] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->subMonths()->startOfMonth(), Carbon::now()->subMonths()->endOfMonth()])->sum('amount');
        $data['monthly_expense'][2] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->sum('amount');
        $data['monthly_expense'][3] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->sum('amount');
        $data['monthly_expense'][4] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->sum('amount');
        $data['monthly_expense'][5] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->subMonths(5)->endOfMonth()])->sum('amount');

        $data['payment_status'] = [];
        $data['payment_status']['paid'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 1)->sum('amount');
        $data['payment_status']['pending'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 0)->sum('amount');

        $data['monthly_due'] = [];
        $data['monthly_due'][0] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        $data['monthly_due'][1] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths()->startOfMonth(), Carbon::now()->subMonths()->endOfMonth()])->sum('amount');
        $data['monthly_due'][2] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->sum('amount');
        $data['monthly_due'][3] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->sum('amount');
        $data['monthly_due'][4] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->sum('amount');
        $data['monthly_due'][5] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->subMonths(5)->endOfMonth()])->sum('amount');

        $data['employee_report'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->select(DB::raw('round(sum(amount)) as amount, round(sum(duration)) as hours , employee_id'), 'fname', 'mname', 'lname')
            ->join('employees', 'employees.id', '=', 'time_keepers.employee_id')
            ->groupBy('employee_id')
            ->orderByRaw('amount desc')
            ->whereBetween('roaster_date', [$start_week, $end_week])
            // ->where([
            //     ['time_keepers.user_id', Auth::id()]
            // ])
            ->limit(5)
            ->orderBy('fname', 'asc')
            ->get();

        $data['client_report'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->select(DB::raw('round(sum(amount)) as amount, round(sum(duration)) as hours , client_id'))
            ->groupBy('client_id')
            ->orderByRaw('amount desc')
            ->whereBetween('roaster_date', [$start_week, $end_week])
            // ->where([
            //     ['user_id', Auth::id()]
            // ])
            ->get();


        $data['hours_last_six'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->endOfMonth()])->sum('duration');
        $data['amount_last_six'] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()]
        ])->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');

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

        return view('pages.Admin.index', compact('data','projects','job_types'));
    }
    public function taskDescriptions()
    {
        $data = TaskDescription::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function storeTaskDescriptions(Request $request)
    {
        $task = new TaskDescription;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->user_id = Auth::id();

        $task->save();

        return response()->json(['status' => 'success']);
    }
    public function updateTaskDescriptions(Request $request)
    {
        $task = TaskDescription::find($request->id);
        $task->description = $request->description;
        $task->status = $request->status;

        $task->save();

        return response()->json(['status' => 'success']);
    }


    public function manageTaskDescriptions(Request $request)
    {
        if ($request->manage == 'delete') {
            foreach ($request->task_ids as $id) {
                $task = TaskDescription::find($id);
                $task->delete();
            }
        } elseif ($request->manage == 'completed') {
            foreach ($request->task_ids as $id) {
                $task = TaskDescription::find($id);
                $task->status = 'completed';
                $task->save();
            }
        } elseif ($request->manage == 'incomplete') {
            foreach ($request->task_ids as $id) {
                $task = TaskDescription::find($id);
                $task->status = 'incomplete';
                $task->save();
            }
        }

        return response()->json(['status' => 'success']);
    }


    public function SuperadminHome()
    {
        return view('pages.SuperAdmin.index');
    }
    public function adminHomeall($id)
    {
        //dd($id);
        // $admin = User::where('Status', '=', 1)->get();
        if (Auth::user()->Status == 1) {
            return view('pages.Admin.index');
        } elseif (Auth::user()->Status == 2) {
            $notification = array(
                'message' => 'This is inactive !!!',
                'alert-type' => 'warning'
            );
            return Redirect()->route('login')->with($notification);
        }
    }

    // public function adminsHome(Request $request)
    // {
    //     $user = DB::table('users')
    //         ->where('id', $request->userid)
    //         ->first();
    //     $request->session()->put('super_admin', 1);
    //     $request->session()->put('id', Auth::id());
    //     if (Auth::loginUsingId($user->id)) {
    //         return redirect('/');
    //     }
    // }
}
