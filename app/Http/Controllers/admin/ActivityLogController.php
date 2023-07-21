<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Session;

class ActivityLogController extends Controller
{
    public function index()
    {
        Session::put('fromRoaster', '');
        Session::put('toRoaster', '');
        $activity = Activity::where('log_name', auth()->user()->company_roles->first()->company_code)->latest('id')->get();
        // foreach($activity as $log){
        //     return $log->properties['attributes'];
        // }
        return view('pages.Admin.activity_log.index',compact('activity'));
    }

    public function search(Request $request)
    {
        
        $fromRoaster = Carbon::parse($request->start_date);
        $toRoaster = Carbon::parse($request->end_date);

        Session::put('fromRoaster', $fromRoaster);
        Session::put('toRoaster', $toRoaster);

        $activity = Activity::where([
            ['log_name', auth()->user()->company_roles->first()->company_code],
        ])
        ->whereBetween('created_at', [$fromRoaster->startOfDay(), $toRoaster->endOfDay()])
        ->latest('id')->get();
        return view('pages.Admin.activity_log.index',compact('activity'));
    }
}
