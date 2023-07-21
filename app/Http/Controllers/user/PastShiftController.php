<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\TimeKeeper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class PastShiftController extends Controller
{
    public function index(){
        $roasters = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id],
            ['company_code',Auth::user()->employee->company],
            // ['shift_end','<=',Carbon::now()]
        ])
        ->where(function ($q) {
            $q->where('sing_in', '!=', null);
            $q->where('sing_out', '!=', null);
            $q->orWhere(function ($q) {
                $q->where('shift_end', '<=', Carbon::now());
            });
        })
        ->where(function ($q) {
            avoid_rejected_key($q);
        })
          ->orderBy('roaster_date','desc')->get();
        //for unsheduled user
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        return view('pages.User.past_shift.index',compact('roasters', 'projects'));
    }

    public function search(Request $request)
    {
        $fromDate = $request->input('start_date');
        $fromDate = Carbon::parse($fromDate);

        $toDate = $request->input('end_date');
        $toDate = Carbon::parse($toDate);

        Session::put('pastFromDate',$fromDate);
        Session::put('pastToDate',$toDate);

        $roasters = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id],
            ['company_code',Auth::user()->employee->company],
            ['shift_end','<=',Carbon::now()]
        ])->orderBy('roaster_date','desc')
        ->where(function ($q) {
            avoid_rejected_key($q);
        })
        ->whereBetween('roaster_date', [$fromDate, $toDate])->get();
        //for unsheduled user
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        return view('pages.User.past_shift.index',compact('roasters', 'projects'));
    }
}
