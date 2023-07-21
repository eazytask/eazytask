<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;

class UpcomingShiftController extends Controller
{
    public function index(){
        $roasters = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id],
            ['company_code',Auth::user()->employee->company],
            ['roaster_status_id',Session::get('roaster_status')['Accepted']],
            ['shift_end','>=',Carbon::now()],
            ['sing_in',null]
        ])->orderBy('roaster_date','asc')->get();

        //for unsheduled user
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        return view('pages.User.upcoming_shift.index',compact('roasters', 'projects'));
    }

    public function search(Request $request)
    {
        $fromDate=null;
        $toDate=null;
        if($request->input('start_date')){
            $fromDate = $request->input('start_date');
            $fromDate = Carbon::parse($fromDate);
        }

        if($request->input('end_date')){
            $toDate = $request->input('end_date');
            $toDate = Carbon::parse($toDate);
        }

        Session::put('upcomingFromDate',$fromDate);
        Session::put('upcomingToDate',$toDate);

        $roasters = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id],
            ['company_code',Auth::user()->employee->company],
            ['roaster_status_id',Session::get('roaster_status')['Accepted']],
            ['shift_end','>=',Carbon::now()],
            ['sing_in',null]
        ])->orderBy('roaster_date','desc')->whereBetween('roaster_date', [$fromDate, $toDate])->get();
        //for unsheduled user
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        return view('pages.User.upcoming_shift.index',compact('roasters', 'projects'));
    }
}
