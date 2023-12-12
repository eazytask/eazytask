<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\TimeKeeper;
use App\Models\RoasterStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\JobType;
use App\Notifications\NewShiftNotification;
use App\Notifications\UpdateShiftNotification;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class NewTimeKeeperController extends Controller
{
    // public function delete_month($from)
    // {
    //     $copy_week = Carbon::parse($from);
    //     $start_date = Carbon::parse($copy_week)->startOfMonth();
    //     $end_date = Carbon::parse($copy_week)->endOfMonth();

    //     $timekeepers = TimeKeeper::where('user_id', Auth::id())
    //         ->whereBetween('roaster_date', [$start_date, $end_date])
    //         ->get();

    //     foreach ($timekeepers as $timekeeper) {
    //         $timekeeper->delete();
    //     }
    //     return 'deleted';
    // }
    // public function copy_week($from, $to)
    // {
    //     $copy_week = Carbon::parse($from);
    //     $start_date = Carbon::parse($copy_week)->startOfWeek();
    //     $end_date = Carbon::parse($copy_week)->endOfWeek();

    //     $difference = Carbon::parse($to)->startOfWeek()->diffInDays($start_date);

    //     $timekeepers = TimeKeeper::where('user_id', Auth::id())
    //         ->whereBetween('roaster_date', [$start_date, $end_date])
    //         ->get();

    //     // $roaster_status  = RoasterStatus::where([
    //     //     ['name', 'Not published'],
    //     //     ['company_code',Auth::user()->company_roles->first()->company->id]
    //     // ])->first();
    //     $roaster_statuses = RoasterStatus::where([
    //         ['user_id', Auth::id()],
    //     ])->get();
    //     $status = [];
    //     foreach ($roaster_statuses as $st) {
    //         $status[$st->name] = $st->id;
    //     }

    //     foreach ($timekeepers as $timekeeper) {
    //         $roster = new TimeKeeper;
    //         $roster->roaster_date = Carbon::parse($timekeeper->roaster_date)->addDay($difference);
    //         $roster->shift_start = Carbon::parse($timekeeper->shift_start)->addDay($difference);
    //         $roster->shift_end = Carbon::parse($timekeeper->shift_end)->addDay($difference);
    //         $roster->sing_in = null;
    //         $roster->sing_out = null;
    //         $roster->payment_status = 0;

    //         $roster->user_id = Auth::id();
    //         $roster->employee_id = $timekeeper->employee_id;
    //         $roster->client_id = $timekeeper->client_id;
    //         $roster->project_id = $timekeeper->project_id;
    //         $roster->company_id = $timekeeper->company_id;
    //         $roster->company_code = Auth::user()->company_roles->first()->company->id;
    //         $roster->duration = $timekeeper->duration + rand(1, 5);
    //         $roster->ratePerHour = $timekeeper->ratePerHour + rand(1, 10);
    //         $roster->amount = $roster->duration * $roster->ratePerHour;
    //         $roster->job_type_id = $timekeeper->job_type_id;
    //         // $roster->roaster_id = Auth::id();
    //         $roster->roaster_status_id = $status['Accepted'];

    //         $roster->roaster_type = $timekeeper->roaster_type;

    //         $roster->remarks = $timekeeper->remarks;
    //         $roster->created_at = Carbon::now();
    //         $roster->save();

    //         //=============Payment Store=============//
    //         // $payment = new Payment();
    //         // $payment->roaster_id = $roster->id;
    //         // $payment->save();

    //         //=============Roster Type Store=========//
    //         // $roast = new RoasterType;
    //         // $roast->roaster_id = $roster->id;
    //         // $roast->save();
    //     }
    //     return 'successfull';
    // }
    // public function copy_month($from, $to)
    // {
    //     set_time_limit(300);
    //     $copy_week = Carbon::parse($from);
    //     $start_date = Carbon::parse($copy_week)->startOfMonth();
    //     $end_date = Carbon::parse($copy_week)->endOfMonth();

    //     $difference = Carbon::parse($to)->startOfMonth()->diffInDays($start_date);

    //     $timekeepers = TimeKeeper::where('user_id', Auth::id())
    //         ->whereBetween('roaster_date', [$start_date, $end_date])
    //         ->get();

    //     $roaster_statuses = RoasterStatus::where([
    //         ['user_id', Auth::id()],
    //     ])->get();
    //     $status = [];
    //     foreach ($roaster_statuses as $st) {
    //         $status[$st->name] = $st->id;
    //     }

    //     foreach ($timekeepers as $timekeeper) {
    //         $roster = new TimeKeeper;
    //         $roster->roaster_date = Carbon::parse($timekeeper->roaster_date)->addDay($difference);
    //         $roster->shift_start = Carbon::parse($timekeeper->shift_start)->addDay($difference);
    //         $roster->shift_end = Carbon::parse($timekeeper->shift_end)->addDay($difference);
    //         $roster->sing_in = null;
    //         $roster->sing_out = null;
    //         $roster->payment_status = 0;

    //         $roster->user_id = Auth::id();
    //         $roster->employee_id = $timekeeper->employee_id;
    //         $roster->client_id = $timekeeper->client_id;
    //         $roster->project_id = $timekeeper->project_id;
    //         $roster->company_id = $timekeeper->company_id;
    //         $roster->company_code = Auth::user()->company_roles->first()->company->id;
    //         $roster->duration = $timekeeper->duration + rand(1, 5);
    //         $roster->ratePerHour = $timekeeper->ratePerHour + rand(1, 10);
    //         $roster->amount = $roster->duration * $roster->ratePerHour;
    //         $roster->job_type_id = $timekeeper->job_type_id;
    //         // $roster->roaster_id = Auth::id();
    //         $roster->roaster_status_id = $status['Accepted'];

    //         $roster->roaster_type = $timekeeper->roaster_type;

    //         $roster->remarks = $timekeeper->remarks;
    //         $roster->created_at = Carbon::now();
    //         $roster->save();

    //         //=============Payment Store=============//
    //         // $payment = new Payment();
    //         // $payment->roaster_id = $roster->id;
    //         // $payment->save();

    //         //=============Roster Type Store=========//
    //         // $roast = new RoasterType;
    //         // $roast->roaster_id = $roster->id;
    //         // $roast->save();
    //     }
    //     return 'successfull';
    // }

    // public function change()
    // {
    //     set_time_limit(300);
    //     $timekeepers = TimeKeeper::all();

    //     foreach ($timekeepers as $timekeeper) {
    //         $shift_end = Carbon::parse($timekeeper->shift_start)->addMinute($timekeeper->duration * 60);
    //         $timekeeper->shift_end = $shift_end;
    //         $timekeeper->Approved_end_datetime = $shift_end;
    //         $timekeeper->save();
    //     }
    //     return 'successfully changed';
    // }

    public function index($id)
    {
        $fromRoaster = Carbon::now()->subDays(10);
        $toRoaster = Carbon::now();
        Session::put('fromRoaster', $fromRoaster);
        Session::put('toRoaster', $toRoaster);

        return $this->search_module();
    }

    public function search(Request $request)
    {
        $fromRoaster = $request->input('start_date');
        // $fromRoaster = Carbon::parse($fromRoaster);

        $toRoaster = $request->input('end_date');
        // $toRoaster = Carbon::parse($toRoaster);

        Session::put('fromRoaster', $fromRoaster);
        Session::put('toRoaster', $toRoaster);

        return $this->search_module();
    }
    //=============================Timekeeper Store=============================//
    public function storeTimeKeeper(Request $request)
    {
        // return Auth::user()->firebase;
        $project = Project::find($request->project_id);
        $shift_start = Carbon::parse($request->roaster_date . $request->shift_start);
        $shift_end = Carbon::parse($shift_start)->addMinute($request->duration * 60);
        $msg = "shift successfully added.";
        $continue = true;

        $timekeeper = new TimeKeeper();

        if ($request->roaster_type) {
            if ($request->roaster_type == 'Schedueled') {
                $timekeeper->roaster_type = 'Schedueled';
            } elseif ($request->roaster_type == 'Unschedueled' && Carbon::parse($request->roaster_date) > Carbon::now()) {
                $msg = "advance date not support for unschedule!";
                $continue = false;
            } else {
                $timekeeper->roaster_type = 'Unschedueled';
            }
        } else {
            $timekeeper->roaster_type = 'Unschedueled';
        }
        if ($continue) {
            $timekeeper->user_id = Auth::id();
            $timekeeper->employee_id = $request->employee_id;
            $timekeeper->client_id = $project->clientName;
            $timekeeper->project_id = $request->project_id;
            $timekeeper->employee_id = $request->employee_id;
            $timekeeper->company_id = Auth::id();
            $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
            $timekeeper->shift_start = $shift_start;
            $timekeeper->shift_end = $shift_end;
            $timekeeper->company_code = Auth::user()->company_roles->first()->company->id;
            $timekeeper->duration = $request->duration;
            $timekeeper->ratePerHour = $request->ratePerHour;
            $timekeeper->amount = $request->amount;
            $timekeeper->job_type_id = $request->job_type_id;
            // $timekeeper->roaster_id = Auth::id();
            // if ($request->roaster_status_id) {
            //     $timekeeper->roaster_status_id = $request->roaster_status_id;
            // } else {
            //     $timekeeper->roaster_status_id = Session::get('roaster_status')['Not published'];
            // }
            $timekeeper->roaster_status_id = roaster_status('Published');
            $timekeeper->remarks = $request->remarks;
            $timekeeper->created_at = Carbon::now();
            $timekeeper->save();
            
            if ($request->roaster_type == 'Schedueled') {
                $pro = $timekeeper->project;
                if($timekeeper->roaster_status_id == roaster_status("Accepted")){
                    $noty = 'one of your shift ' . $pro->pName . ' week ending ' . Carbon::parse($timekeeper->roaster_date)->endOfWeek()->format('d-m-Y') . ' has been updated.';
                    push_notify('Shift Update:', $noty.' Please check eazytask for changes.',$timekeeper->employee->employee_role, $timekeeper->employee->firebase,'upcoming-shift');
                    $timekeeper->employee->user->notify(new UpdateShiftNotification($noty,$timekeeper));
                }elseif($timekeeper->roaster_status_id == roaster_status("Published")){
                    $noty = 'There is an shift at '.$pro->pName.' for week ending ' . Carbon::parse($timekeeper->roaster_date)->endOfWeek()->format('d-m-Y');
                    push_notify('Shift Alert :',$noty.' Please log on to eazytask to accept / declined it.',$timekeeper->employee->employee_role,$timekeeper->employee->firebase,'unconfirmed-shift');
                    $timekeeper->employee->user->notify(new NewShiftNotification($noty,$timekeeper));
                }
            }
            
            $notification = array(
                'message' => $msg,
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
        }

        if ($request->schedule_roaster) {
            return response()->json(['notification' => $msg]);
        }
        if (Session::get('fromRoaster'))
            return $this->search_module($request)->with($notification);
        return Redirect()->back()->with($notification);
    }

    // =============================Timekeeper Update=============================//
    public function update(Request $request)
    {
        $project = Project::find($request->project_id);
        $msg = "shift updated successfully.";
        $continue = true;

        $timekeeper = TimeKeeper::find($request->timepeeper_id);

        if ($request->roaster_type) {
            if ($request->roaster_type == 'Schedueled') {
                $timekeeper->roaster_type = 'Schedueled';
            } elseif ($request->roaster_type == 'Unschedueled' && Carbon::parse($request->roaster_date) > Carbon::now()) {
                $msg = "advance date not support for unschedule!";
                $continue = false;
            } else {
                $timekeeper->roaster_type = 'Unschedueled';
            }
        } else {
            $timekeeper->roaster_type = 'Unschedueled';
        }
        if ($continue) {

            $request->roaster_date =  Carbon::parse($request->roaster_date)->format('d-m-Y');
            $shift_start = Carbon::parse($request->roaster_date . $request->shift_start);
            $shift_end = Carbon::parse($shift_start)->addMinute($request->duration * 60);

            $timekeeper->employee_id = $request->employee_id;
            $timekeeper->client_id = $project->clientName;
            $timekeeper->project_id = $request->project_id;
            $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
            $timekeeper->shift_start = $shift_start;
            $timekeeper->shift_end = $shift_end;
            $timekeeper->duration = $request->duration;
            $timekeeper->ratePerHour = $request->ratePerHour;
            $timekeeper->amount = $request->amount;
            $timekeeper->job_type_id = $request->job_type_id;

            if ($request->roaster_status_id) {
                $timekeeper->roaster_status_id = $request->roaster_status_id;
            } else {
                $timekeeper->roaster_status_id = Session::get('roaster_status')['Not published'];
            }

            $timekeeper->remarks = $request->remarks;
            $timekeeper->updated_at = Carbon::now();
            $timekeeper->save();

            if ($request->roaster_type == 'Schedueled') {
                $pro = $timekeeper->project;
                if($timekeeper->roaster_status_id == Session::get('roaster_status')['Accepted']){
                    $noty = 'one of your shift ' . $pro->pName . ' week ending ' . Carbon::parse($timekeeper->roaster_date)->endOfWeek()->format('d-m-Y') . ' has been updated.';
                    push_notify('Shift Update:', $noty.' Please check eazytask for changes.',$timekeeper->employee->employee_role, $timekeeper->employee->firebase,'upcoming-shift');
                    $timekeeper->employee->user->notify(new UpdateShiftNotification($noty,$timekeeper));
                }elseif($timekeeper->roaster_status_id == Session::get('roaster_status')['Published']){
                    $noty = 'There is an shift at '.$pro->pName.' for week ending ' . Carbon::parse($timekeeper->roaster_date)->endOfWeek()->format('d-m-Y');
                    push_notify('Shift Alert :',$noty.' Please log on to eazytask to accept / declined it.',$timekeeper->employee->employee_role,$timekeeper->employee->firebase,'unconfirmed-shift');
                    $timekeeper->employee->user->notify(new NewShiftNotification($noty,$timekeeper));
                }
            }
            $notification = array(
                'message' => $msg,
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
        }

        if ($request->schedule_roaster) {
            return response()->json(['notification' => $msg]);
        }
        if (Session::get('fromRoaster'))
            return $this->search_module()->with($notification);
        return Redirect()->back()->with($notification);
    }

    //Timekeeper delete
    public function delete($id)
    {
        $timekeeper = TimeKeeper::find($id);
        if ($timekeeper) {
            Session::put('current_employee', $timekeeper->employee_id);
            $timekeeper->delete();
            $notification = array(
                'message' => 'Timekeeper deleted successfully.',
                'alert-type' => 'error'
            );
        } else {
            $notification = [];
        }

        if (Session::get('fromRoaster'))
            return $this->search_module()->with($notification);
        return Redirect()->back()->with($notification);
    }

    public function search_module($request = null)
    {
        if (!$request) {
            $req = [
                'start_date' => Carbon::parse(Session::get('fromRoaster')),
                'end_date' => Carbon::parse(Session::get('toRoaster')),
                'project_id' => Session::get(''),
                'client_id' => Session::get(''),
                'roaster_status' => Session::get(''),
                'employee_id' => Session::get(''),
                'schedule' => Session::get('Unschedueled'),
                'payment_status' => ''
            ];
            $request = (object) $req;
        }

        // $pdf_generator = new PDFGeneratorController;
        // $employee_wise_report = $pdf_generator->employee_wise($request);
        // $client_wise_report = $pdf_generator->client_wise($request);

        // Session::put('employee_wise_report', $employee_wise_report);
        // Session::put('client_wise_report', $client_wise_report);

        $employees = Employee::where([
            ['company', Auth::user()->company_roles->first()->company->id],
            ['role', 3],
            ['status', '1']
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
        $roaster_status = RoasterStatus::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('name', 'asc')->get();

        $timekeepers = TimeKeeper::where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['roaster_type', 'Unschedueled']
        ])
            ->whereBetween('roaster_date', [Carbon::parse(Session::get('fromRoaster')), Carbon::parse(Session::get('toRoaster'))])
            ->orderBy('roaster_date', 'desc')->get();

        return view('pages.Admin.timekeeper.newindex', compact('employees', 'projects', 'timekeepers', 'job_types', 'roaster_status'));
    }
}
