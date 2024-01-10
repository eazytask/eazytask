<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityPhotoController;
use App\Jobs\AutoSignOutJob;
use App\Jobs\FirebaseShiftNotificationJob;
use App\Models\JobType;
use App\Models\RoasterStatus;
use App\Models\UserActivityPhoto;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SignInController extends Controller
{
    public function index(){
        $project=null;
        $roasters = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id],
            ['company_code',Auth::user()->employee->company],
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
        ->orderBy('shift_start','asc')->get();
            //for unsheduled user
            $projects = Project::whereHas('client', function ($query) {
                $query->where('status', 1);
            })->where([
                ['company_code', Auth::user()->company_roles->first()->company->id],
                ['Status', '1'],
            ])->orderBy('pName', 'asc')->get();
            $job_types = JobType::where('company_code', Auth::user()->employee->company)->get();
            $roaster_status= RoasterStatus::where('company_code', Auth::user()->employee->company)->orderBy('name','asc')->get();

            // return $roster;
            return view('pages.User.signin.index',compact('roasters', 'projects','job_types','roaster_status'));
    }

    public function signIn(Request $request){
        if($this->check_license()){
            return $this->check_license();
        }
        $roster = TimeKeeper::find($request->timekeeper_id);
        $roster->sing_in = Carbon::now();
        $roster->save();

        if($request->lat && $request->lon){
            $user_activity= new UserActivityPhoto();
            $user_activity->lat  = $request->lat;
            $user_activity->lon  = $request->lon;
            $user_activity->timekeeper_id  = $roster->id;
            $user_activity->save();
        }
        if($request->image){
            $user_activity= new UserActivityPhotoController;
                Log::alert($request->image);
                $user_activity->store($request->image,$roster->id);
        }

        AutoSignOutJob::dispatch($roster->id)->delay(now()->addHours(6));
        FirebaseShiftNotificationJob::dispatch($roster->employee->firebase,$roster->id)->delay(Carbon::parse($roster->shift_end));
        FirebaseShiftNotificationJob::dispatch($roster->employee->firebase,$roster->id)->delay(Carbon::parse($roster->shift_end)->addMinutes(15));

        $notification = array(
            'message' => 'Thanks For Sign In',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    public function signOut(Request $request){
       $this->addSignOut($request->timekeeper_id);

        if($request->image){
            $user_activity= new UserActivityPhotoController;
                Log::alert($request->image);
                $user_activity->store($request->image,$request->timekeeper_id,'sign_out');
        }

        $notification = array(
            'message' => 'Signed out successfully',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    public function storeTimekeeper(Request $request){
        if($this->check_license()){
            return $this->check_license();
        }

        $project= Project::find($request->project_id);
        // $job = JobType::where('company_code',Auth::user()->employee->company)->first();

        $duration = 4;
        $shift_start= Carbon::now();
        $shift_end= Carbon::now()->addHours(4);

        $timekeeper = new TimeKeeper();
        $timekeeper->user_id = Auth::user()->employee->user_id;
        $timekeeper->employee_id = Auth::user()->employee->id;
        $timekeeper->client_id = $project->clientName;
        // $timekeeper->client_id = 99999;
        $timekeeper->project_id = $request->project_id;
        // $timekeeper->project_id = 99999;
        $timekeeper->company_id = Auth::user()->employee->company;
        $timekeeper->roaster_date = Carbon::today()->toDateString();
        $timekeeper->shift_start = $shift_start;
        $timekeeper->shift_end = $shift_end;

        $timekeeper->sing_in = $shift_start;

        $timekeeper->company_code = Auth::user()->employee->company;
        $timekeeper->duration = $duration;
        $timekeeper->ratePerHour = $request->ratePerHour;
        $timekeeper->amount = $duration * $request->ratePerHour;
        $timekeeper->job_type_id = $request->job_type_id;
        // $timekeeper->ratePerHour = 20;
        // $timekeeper->amount = $duration * 20;
        // $timekeeper->job_type_id = $job->id;
        // $timekeeper->roaster_id = Auth::id();
        $timekeeper->roaster_status_id = Session::get('roaster_status')['Accepted'];
        $timekeeper->roaster_type = 'Unschedueled';
        $timekeeper->remarks = $request->remarks;
        $timekeeper->created_at = Carbon::now();
        $timekeeper->save();

        AutoSignOutJob::dispatch($timekeeper->id)->delay(now()->addHours(6));

        if($request->lat && $request->lon){
            $user_activity= new UserActivityPhoto();
            $user_activity->lat  = $request->lat;
            $user_activity->lon  = $request->lon;
            $user_activity->timekeeper_id  = $timekeeper->id;
            $user_activity->save();
        }
        if($request->image){
        Log::alert($request->image);
            $user_activity= new UserActivityPhotoController;
            // if($request->device == 'mobile'){
            //     $user_activity->storeLocalImage($request->image,$timekeeper->id,'sign_in');
            // }else{
                $user_activity->store($request->image,$timekeeper->id,'sign_in');
            // }
        }

        $notification = array(
            'message' => 'roster successfully added.',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    public function addSignOut($timekeeper_id){
        $roster = TimeKeeper::find($timekeeper_id);
        if(!$roster->sing_out){
            $roster->sing_out = Carbon::now();
            
            if($roster->roaster_type=='Unschedueled'){
                $now = Carbon::now();
                $total_hour = $now->floatDiffInRealHours($roster->sing_in);
    
                $roster->shift_end = $now;
                $roster->duration = round($total_hour, 2);
                $roster->amount = round($total_hour * $roster->ratePerHour);
                
                $roster->Approved_end_datetime = $now;
                $roster->app_duration = round($total_hour, 2);
                $roster->app_amount = round($total_hour * $roster->ratePerHour);
            }
        }
        
        $roster->save();
    }

    private function check_license(){
        $emp = Auth::user()->employee;
        if($emp->license_expire_date < Carbon::now()->toDateString()||$emp->first_aid_expire_date < Carbon::now()->toDateString()){
            $notification = array(
                'message' => 'sorry! your license is expired.',
                'alert-type' => 'warning'
            );
        }elseif($comp = $emp->compliances->where('expire_date','<',Carbon::now()->toDateString())->first()){
            $notification = array(
                'message' => "sorry! your compliance ".$comp->compliance->name." is expired.",
                'alert-type' => 'warning'
            );
        }else{
            return false;
        }
        return Redirect()->back()->with($notification);
    }
}
