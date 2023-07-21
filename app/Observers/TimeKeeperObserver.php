<?php

namespace App\Observers;

use App\Jobs\FirebaseShiftNotificationJob;
use App\Models\Job;
use App\Models\TimeKeeper;
use App\Notifications\NewShiftNotification;
use App\Notifications\UpdateShiftNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

class TimeKeeperObserver
{
    /**
     * Handle the TimeKeeper "creating" event.
     *
     * @param  \App\Models\TimeKeeper  $timeKeeper
     * @return void
     */
    public function creating(TimeKeeper $timeKeeper)
    {
        $timeKeeper->Approved_start_datetime = $timeKeeper->shift_start;
        $timeKeeper->Approved_end_datetime = $timeKeeper->shift_end;
        $timeKeeper->app_rate = $timeKeeper->ratePerHour;
        $timeKeeper->app_duration = $timeKeeper->duration;
        $timeKeeper->app_amount = $timeKeeper->amount;
    }

    /**
     * Handle the TimeKeeper "created" event.
     *
     * @param  \App\Models\TimeKeeper  $timeKeeper
     * @return void
     */
    public function created(TimeKeeper $shift)
    {
        if ($shift->shift_start >Carbon::now() && $shift->roaster_type == 'Schedueled' && $shift->roaster_status_id == Session::get('roaster_status')['Accepted'] && $shift->sing_in == null) {
            FirebaseShiftNotificationJob::dispatch($shift->employee->firebase, $shift->id)->delay(Carbon::parse($shift->shift_start)->addMinutes(10));
            FirebaseShiftNotificationJob::dispatch($shift->employee->firebase, $shift->id)->delay(Carbon::parse($shift->shift_start)->subMinutes(10));
            // FirebaseShiftNotificationJob::dispatch($shift->employee->firebase, $shift->id)->delay(Carbon::parse($shift->shift_start)->subHour());
        }
    }

    /**
     * Handle the TimeKeeper "updated" event.
     *
     * @param  \App\Models\TimeKeeper  $timeKeeper
     * @return void
     */
    public function updated(TimeKeeper $timeKeeper)
    {
    }

    /**
     * Listen to the TimeKeeper updating event.
     *
     * @param  \App\TimeKeeper  $timeKeeper
     * @return void
     */
    public function updating(TimeKeeper $timeKeeper)
    {
        // $timeKeeper->Approved_start_datetime = $timeKeeper->shift_start;
        // $timeKeeper->Approved_end_datetime = $timeKeeper->shift_end;
        // $timeKeeper->app_rate = $timeKeeper->ratePerHour;
        // $timeKeeper->app_duration = $timeKeeper->duration;
        // $timeKeeper->app_amount = $timeKeeper->amount;

        if ($timeKeeper->shift_start >Carbon::now() && $timeKeeper->roaster_type == 'Schedueled' && $timeKeeper->roaster_status_id == Session::get('roaster_status')['Accepted'] && $timeKeeper->sing_in == null) {
            $jobs = Job::where('payload', 'like', '%' . $timeKeeper->id . '%')->get();
            // if($jobs->count()){
            //     $pro = $timeKeeper->project;
            //     $msg = 'A shift has recently updated ' . Carbon::parse($timeKeeper->roaster_date)->format('d-m-Y') . '
            //     (' . Carbon::parse($timeKeeper->shift_start)->format('H:i') . '-' . Carbon::parse($timeKeeper->shift_end)->format('H:i') . ')
            //     , at "' . $pro->pName . '" ' . $pro->project_address . ' ' . $pro->suburb . ' ' . $pro->project_state . ', ' . $timeKeeper->job_type->name;
                
            //     push_notify('Shift Alert :',$msg,$timeKeeper->employee->firebase);
            // }
            foreach ($jobs as $row) {
                try {
                    $jsonpayload = json_decode($row->payload);
                    $data = unserialize($jsonpayload->data->command);
                    if ($data->timekeeper_id == $timeKeeper->id) {
                        $row->delete();
                    }
                } catch (Throwable $e) {
                }
            }
            FirebaseShiftNotificationJob::dispatch($timeKeeper->employee->firebase, $timeKeeper->id)->delay(Carbon::parse($timeKeeper->shift_start)->addMinutes(10));
            FirebaseShiftNotificationJob::dispatch($timeKeeper->employee->firebase, $timeKeeper->id)->delay(Carbon::parse($timeKeeper->shift_start)->subMinutes(10));
            // FirebaseShiftNotificationJob::dispatch($timeKeeper->employee->firebase, $timeKeeper->id)->delay(Carbon::parse($timeKeeper->shift_start)->subHour());
        }
    }

    /**
     * Handle the TimeKeeper "deleting" event.
     *
     * @param  \App\Models\TimeKeeper  $timeKeeper
     * @return void
     */
    public function deleting(TimeKeeper $timekeeper)
    {
        if ($timekeeper->roaster_type == 'Schedueled' && $timekeeper->roaster_status_id == Session::get('roaster_status')['Accepted']) {
            $pro = $timekeeper->project;
            $msg = 'one of your shift ' . $pro->pName . ' week ending ' . Carbon::parse($timekeeper->roaster_date)->endOfWeek()->format('d-m-Y') . ' has been deleted.';

            $timekeeper->employee->user->notify(new UpdateShiftNotification($msg,$timekeeper,'deleted'));
            push_notify('Shift Deleted :', $msg.' Please check eazytask for changes',$timekeeper->employee->employee_role, $timekeeper->employee->firebase,'upcoming-shift');
        }
        
        $jobs = Job::where('payload', 'like', '%' . $timekeeper->id . '%')->get();
        foreach ($jobs as $row) {
            try {
                $jsonpayload = json_decode($row->payload);
                $data = unserialize($jsonpayload->data->command);
                if ($data->timekeeper_id == $timekeeper->id) {
                    $row->delete();
                }
            } catch (Throwable $e) {
            }
        }
    }

    /**
     * Handle the TimeKeeper "restored" event.
     *
     * @param  \App\Models\TimeKeeper  $timeKeeper
     * @return void
     */
    public function restored(TimeKeeper $timeKeeper)
    {
        //
    }

    /**
     * Handle the TimeKeeper "force deleted" event.
     *
     * @param  \App\Models\TimeKeeper  $timeKeeper
     * @return void
     */
    public function forceDeleted(TimeKeeper $timeKeeper)
    {
        //
    }
}
