<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\paymentmaster;
use App\Models\Project;
use App\Models\TimeKeeper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class UserReportController extends Controller
{
    public function all_report(){
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        
        $req = [
            'start_date'=>Carbon::now()->startOfWeek(),
            'end_date'=>Carbon::now()->endOfWeek(),
            'project_id'=>'',
            'payment_status'=>'',
        ];
        //last week
        $request = (object) $req;
        $timekeepers = $this->search_module($request);

        return view('pages.User.pdf.all_report', compact('timekeepers', 'projects'));
    }

    public function search_report(Request $request){

        $timekeepers = $this->search_module($request);
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        return view('pages.User.pdf.all_report', compact('projects','timekeepers'));
    }
    
    public function search_module($request){
        $fromRoaster = $request->start_date;
        $fromRoaster = $fromRoaster ? Carbon::parse($fromRoaster) : Carbon::now()->startOfYear();
        $toRoaster = $request->end_date;
        $toRoaster = $toRoaster ? Carbon::parse($toRoaster) : Carbon::now()->endOfYear();

        $project_id = $request->project_id;
        $payment_status = $request->payment_status;

        Session::put('fromDate',$fromRoaster);
        Session::put('toDate',$toRoaster);
        Session::put('project_id',$project_id);
        Session::put('payment_status',$payment_status);

        $filter_payment = $payment_status != '' ? ['payment_status', $payment_status] : ['employee_id', '>', 0];

        $filter_project = $project_id ? ['project_id', $project_id] : ['employee_id', '>', 0];

        $timekeepers = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id],
            ['company_code', Auth::user()->employee->company],
            $filter_payment,
            $filter_project,
        ])->whereBetween('roaster_date', [$fromRoaster, $toRoaster])
        ->where(function ($q) {
            avoid_rejected_key($q);
        })
        ->orderBy('roaster_date', 'asc')
        ->get();

        return $timekeepers;
        // return redirect()->back()->with(['timekeepers',$timekeepers]);
    }

    public function payment_report(){
        $req = [
            'start_date'=>Carbon::now()->startOfWeek(),
            'end_date'=>Carbon::now()->endOfWeek(),
        ];
        $request = (object) $req;
        $payments = $this->payment_search_module($request);
        return view('pages.User.pdf.payment_report',compact('payments'));
    }

    public function search_payment_report(Request $request){
        $payments = $this->payment_search_module($request);
        return view('pages.User.pdf.payment_report',compact('payments'));
    }

    public function payment_search_module($request){
        $start_week = Carbon::now()->startOfWeek();
        $end_week = Carbon::now()->endOfWeek();

        $start_date = $request->start_date ? Carbon::parse($request->start_date):$start_week;
        $end_date = $request->end_date ? Carbon::parse($request->end_date):$end_week;
        Session::put('fromDate',$start_date);
        Session::put('toDate',$end_date);

        $payments = paymentmaster::whereBetween('Payment_Date', [$start_date,$end_date])
            ->where([
                ['employee_id',Auth::user()->employee->id],
            ])
            ->get();
        return $payments;
    }

    public function view_payment_report($id){
        $payment = paymentmaster::find($id);
        if($payment){
            $admin = User::find($payment->User_ID);
            $timekeepers = TimeKeeper::whereIn('id',unserialize($payment->details->timekeeper_ids))->get();
            return view('pages.User.pdf.payment_invoice',compact('payment','timekeepers','admin'));
        }else{
            return redirect()->back();
        }
    }
}
