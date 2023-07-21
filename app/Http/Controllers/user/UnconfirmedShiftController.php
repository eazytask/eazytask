<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TimeKeeper;
use App\Models\User;
use App\Notifications\ConfirmShiftNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class UnconfirmedShiftController extends Controller
{
    public function index(){
        $roasters = TimeKeeper::where([
            ['employee_id',Auth::user()->employee->id],
            ['company_code',Auth::user()->employee->company],
            ['roaster_status_id',Session::get('roaster_status')['Published']],
            ['shift_end','>=',Carbon::now()],
        ])->get();
        //for unsheduled user
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        return view('pages.User.unconfirmed_shift.index',compact('roasters', 'projects'));
    }
    
    public function multiple($action,$ids){
        $status = $action=='accept'? Session::get('roaster_status')['Accepted']:Session::get('roaster_status')['Rejected'];

        $all_id = explode(',',$ids);
        foreach($all_id as $id){
            $roster = TimeKeeper::find($id);
            $roster->roaster_status_id = $status;
            $roster->save();

            // $admin = User::find($roster->user_id);
            // $admin->notify(new ConfirmShiftNotification($roster));
        }
        
        if ($all_id) {
            if($action=='reject'){
                $confirm = 'Rejected';
                $status = 'danger';
            }else{
                $confirm = 'Accepted';
                $status = 'success';
            }
            if(count($all_id)==1){
                $msg = Auth::user()->name.' '.$confirm.' a shift of week ending '. Carbon::parse($roster->roaster_date)->endOfWeek()->format('d-m-Y');
            }else{
                $msg = Auth::user()->name.' '.$confirm.' shifts of week ending '. Carbon::parse($roster->roaster_date)->endOfWeek()->format('d-m-Y');
            }
            $admin = User::find($roster->user_id);
            $admin->notify(new ConfirmShiftNotification($msg,$action,$status));
            push_notify($action.' Shift :',$msg. ' Please check eazytask for changes',$admin->admin_role,$admin->firebase,'admin-scheduele-entry');
        }
        return response()->json(['status'=>'success']);
    }
}
