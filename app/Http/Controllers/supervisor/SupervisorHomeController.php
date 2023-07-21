<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\JobType;
use App\Models\Project;
use App\Models\Supervisor;
use App\Models\TaskDescription;
use App\Models\TimeKeeper;
use App\Models\Upcomingevent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\UserRole;

class SupervisorHomeController extends Controller
{
    public function index()
    {
        $spervisor = Employee::where([
            ['userID', Auth::user()->id],
            // ['company_code', Auth::user()->supervisor->company],
            ['status', 1],
            ['company',auth()->user()->company_roles->first()->company_code],
            ['role', auth()->user()->company_roles->first()->role]
        ])->first();
        if (!$spervisor) {
            $notification = array(
                'message' => 'Sorry! you are not an active supervisor.',
                'alert-type' => 'error'
            );
            Auth::logout();
            return Redirect()->route('login')->with($notification);
        }


        $data = [];
        $start_week = Carbon::now()->subWeeks(2)->startOfWeek();
        $end_week =  Carbon::now()->subWeeks(2)->endOfWeek();
        
        $data['total_hour'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('duration');
        $data['total_amount'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('amount');
        $data['total_client'] = Client::where('company_code', Auth::user()->supervisor->company)->count();
        $data['total_sites'] = Project::where('company_code', Auth::user()->supervisor->company)->count();

        $data['weekly_hour'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('duration');
        $data['weekly_payment'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 1)->whereBetween('roaster_date', [$start_week, $end_week])->sum('amount');
        $data['monthly_earning'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('amount');
        $data['next_event'] = Upcomingevent::where([
            ['company_code', Auth::user()->supervisor->company],
            ['shift_start', '>=', Carbon::now()]
        ])->first();

        $data['monthly_expense'] = [];
        $data['monthly_expense'][0] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        $data['monthly_expense'][1] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths()->startOfMonth(), Carbon::now()->subMonths()->endOfMonth()])->sum('amount');
        $data['monthly_expense'][2] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->sum('amount');
        $data['monthly_expense'][3] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->sum('amount');
        $data['monthly_expense'][4] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->sum('amount');
        $data['monthly_expense'][5] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->subMonths(5)->endOfMonth()])->sum('amount');

        $data['payment_status'] = [];
        $data['payment_status']['paid'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 1)->sum('amount');
        $data['payment_status']['pending'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 0)->sum('amount');

        $data['monthly_due'] = [];
        $data['monthly_due'][0] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        $data['monthly_due'][1] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths()->startOfMonth(), Carbon::now()->subMonths()->endOfMonth()])->sum('amount');
        $data['monthly_due'][2] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->sum('amount');
        $data['monthly_due'][3] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->sum('amount');
        $data['monthly_due'][4] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->sum('amount');
        $data['monthly_due'][5] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->where('payment_status', 0)->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->subMonths(5)->endOfMonth()])->sum('amount');

        $data['employee_report'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->select(DB::raw('round(sum(amount)) as amount, round(sum(duration)) as hours , employee_id'))
            ->groupBy('employee_id')
            ->orderByRaw('amount desc')
            ->whereBetween('roaster_date', [$start_week, $end_week])
            ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])
            ->limit(5)
            ->get();

        $data['client_report'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->select(DB::raw('round(sum(amount)) as amount, round(sum(duration)) as hours , client_id'))
            ->groupBy('client_id')
            ->orderByRaw('amount desc')
            ->whereBetween('roaster_date', [$start_week, $end_week])
            ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])
            ->get();


        $data['hours_last_six'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->endOfMonth()])->sum('duration');
        $data['amount_last_six'] = TimeKeeper::where(function ($q) {
                    avoid_rejected_key($q);
                })
                ->where([
                ['user_id',Auth::user()->supervisor->user_id],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        
            
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        $job_types = JobType::where('user_id',Auth::user()->supervisor->user_id)->get();
        return view('pages.supervisor.index',compact('data','job_types','projects'));
    }

    public function taskDescriptions(){
        $data = TaskDescription::where('user_id',Auth::id())->orderBy('id','desc')->get();
        return response()->json(['data'=>$data]);
    }

    public function storeTaskDescriptions(Request $request){
        $task = new TaskDescription;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->user_id = Auth::id();

        $task->save();

        return response()->json(['status'=>'success']);
    }
    public function updateTaskDescriptions(Request $request){
        $task = TaskDescription::find($request->id);
        $task->description = $request->description;
        $task->status = $request->status;

        $task->save();

        return response()->json(['status'=>'success']);
    }

    
    public function manageTaskDescriptions(Request $request){
        if($request->manage == 'delete'){
            foreach($request->task_ids as $id){
                $task = TaskDescription::find($id);
                $task->delete();
            }
        }elseif($request->manage == 'complete'){
            foreach($request->task_ids as $id){
                $task = TaskDescription::find($id);
                $task->status = 'complete';
                $task->save();
            }
        }elseif($request->manage == 'incomplete'){
            foreach($request->task_ids as $id){
                $task = TaskDescription::find($id);
                $task->status = 'incomplete';
                $task->save();
            }
        }
        
        return response()->json(['status'=>'success']);
    }
}
