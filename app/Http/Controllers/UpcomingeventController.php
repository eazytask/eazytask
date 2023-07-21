<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Upcomingevent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Eventrequest;
use App\Models\Project;
use App\Notifications\EventRequestNotification;
use App\Notifications\NewEventNotification;
use App\Models\User;

class UpcomingeventController extends Controller
{
    //user
    public function userIndex($id)
    {
        $upcomingevents = Upcomingevent::where([
            ['company_code', Auth::user()->employee->company],
            ['event_date', '>', Carbon::now()]
        ])
            ->orderBy('event_date', 'asc')
            ->get();

        return view('pages.User.upcoming_event.index', compact('upcomingevents'));
    }

    public function eventStore(Request $request)
    {
        $eventrequests = new Eventrequest();
        $eventrequests->event_id = $request->event_id;
        $eventrequests->employee_id = Auth::user()->employee->id;
        $eventrequests->user_id = Auth::id();
        $eventrequests->company_code = Auth::user()->employee->company;
        $eventrequests->created_at = Carbon::now();
        $eventrequests->save();

        $event = $eventrequests->upcomingevent;
        $pro = $event->project;
        $msg = Auth::user()->name . ' is interested in "'.$pro->pName.'" event on ' . Carbon::parse($event->event_date)->format('d-m-Y') . '(' . Carbon::parse($event->shift_start)->format('H:i') . '-' . Carbon::parse($event->shift_end)->format('H:i') . ') near "'.$pro->project_address.' '.$pro->suburb.' '.$pro->project_state.'"';

        $admin = User::find($event->user_id);
        Auth::user()->employee->admin->notify(new EventRequestNotification($msg));
        push_notify('New Event Request :', $msg,$admin->admin_role, Auth::user()->employee->admin->firebase,'admin-event',$event->id);

        return Redirect()->back();
    }


    public function store(Request $request)
    {
        if (Carbon::parse($request->event_date) < Carbon::now()->toDateString()) {
            return response()->json(['status' => false,'msg' => 'previous date not supported!']);
        }

        $project = Project::find($request->project_name);

        $shift_start = Carbon::parse($request->event_date . $request->shift_start);
        $shift_end = Carbon::parse($request->event_date . $request->shift_end);

        $event = new Upcomingevent();
        $event->user_id = Auth::id();
        $event->company_code = Auth::user()->company_roles->first()->company->id;
        $event->client_name = $project->clientName;
        $event->project_name = $request->project_name;
        $event->job_type_name = $request->job_type_name;
        $event->event_date = Carbon::parse($request->event_date);
        $event->shift_start = $shift_start;
        $event->shift_end = $shift_end;
        $event->rate = $request->rate;
        $event->remarks = $request->remarks;
        $event->save();

        return response()->json(['status' => true,'msg' => 'successfully added.']);
    }

    public function update(Request $request)
    {
        if (Carbon::parse($request->event_date) < Carbon::now()->toDateString()) {
            return response()->json(['status' => false,'msg' => 'previous date not supported!']);
        }
        
        $project = Project::find($request->project_name);
        $totalDuration = Carbon::parse($request->shift_start)->diffInMinutes(Carbon::parse($request->shift_end),false);
        if($totalDuration < 0) $totalDuration += 1440;

        $shift_start = Carbon::parse($request->event_date . $request->shift_start);
        $shift_end = Carbon::parse($request->event_date . $request->shift_start)->addMinutes($totalDuration);

        $event = Upcomingevent::find($request->id);
        $event->client_name = $project->clientName;
        $event->project_name = $request->project_name;
        $event->job_type_name = $request->job_type_name;
        $event->event_date = Carbon::parse($request->event_date);
        $event->shift_start = $shift_start;
        $event->shift_end = $shift_end;
        $event->rate = $request->rate;
        $event->remarks = $request->remarks;
        $event->updated_at = Carbon::now();
        $event->save();

        return response()->json(['status' => true,'msg' => 'successfully updated.']);
    }

    public function delete($id)
    {
        $upcomingevents = Upcomingevent::find($id);
        $upcomingevents->delete();
        // return Redirect()->back();
        return response()->json(['status' => 'successfully deleted']);
    }

}
