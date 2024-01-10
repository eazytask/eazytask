<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserActivityPhotoController;
use App\Jobs\AutoSignOutJob;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Project;
use App\Models\RoasterStatus;
use App\Models\TimeKeeper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class KioskController extends Controller
{
    public function index()
    {
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        $job_types = JobType::where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
        ])->get();

        return view('pages.Admin.kiosk.index', compact('projects', 'job_types'));
    }

    public function search_employees(Request $request)
    {
        $project = Project::find($request->project_id);
        if ($project) {
            if ($request->empFilter == 'shift') {
                $employees = DB::table('time_keepers')
                    ->select(DB::raw(
                        'e.*'
                    ))
                    ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
                    ->where([
                        ['e.company', Auth::user()->company_roles->first()->company->id],
                        ['e.role', 3]
                    ])
                    ->where(function ($q) {
                        e_avoid_expired_license($q);
                    })
                    ->groupBy("e.id")
                    ->orderBy('e.fname', 'asc')
                    ->where([
                        ['company_code', Auth::user()->company_roles->first()->company->id],
                        ['roaster_date', Carbon::now()->toDateString()],
                        ['project_id', $project->id],
                        ['roaster_status_id', Session::get('roaster_status')['Accepted']]
                    ])
                    ->get();
            } elseif ($request->empFilter == 'inducted') {
                $employees = DB::table('inductedsites')
                    ->select(DB::raw(
                        'e.*'
                    ))
                    ->leftJoin('employees as e', 'e.id', 'inductedsites.employee_id')
                    ->where([
                        ['e.company', Auth::user()->company_roles->first()->company->id],
                        ['e.role', 3],
                        ['e.status', 1]
                    ])
                    ->where(function ($q) {
                        e_avoid_expired_license($q);
                    })
                    ->groupBy("e.id")
                    ->orderBy('e.fname', 'asc')
                    ->where([
                        ['company_code', Auth::user()->company_roles->first()->company->id],
                        ['project_id', $project->id],
                    ])
                    ->get();
            } else {
                $employees = Employee::where([
                    ['company', Auth::user()->company_roles->first()->company->id],
                    ['role', 3],
                    ['status', 1]
                ])
                    ->where(function ($q) {
                        avoid_expired_license($q);
                    })
                    ->orderBy('fname', 'asc')
                    ->get();
            }

            $data = '';
            foreach ($employees as $k => $row) {

                if (!$row->image) {
                    $row->image = 'images/app/no-image.png';
                }
                $json = json_encode($row, false);

                $data .= "<tr>
                <td>" . $k + 1 . "</td>
                <td>
                    <div class='avatar bg-light-primary'>
                        <div class='avatar-content'>
                            <img class='img-fluid' src='https://api.eazytask.au/$row->image' alt=''>
                        </div>
                    </div>
                </td>
                <td>$row->fname</td>
                <td>
                <button class='click-btn btn bg-primary text-white' style='width: 109px;' data-row='$json'>Sign In</button>
            </td>

            </tr>";
            }

            return response()->json(['status' => true, 'data' => $data, 'empFilter' => $request->empFilter]);
        } else {
            return redirect()->back();
        }
    }

    public function check_pin(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            if ($user->pin) {
                if ($user->pin == $request->pin) {
                    $data  = $this->all_shifts($request->employee_id, $request->project_id);
                    return $data;
                } else {
                    return response()->json(['status' => 'wrong_pin']);
                }
            } else {
                return response()->json(['status' => 'no_pin']);
            }
        }
        return response()->json(['status' => 'error']);
    }

    public function all_shifts($employee_id, $project_id)
    {
        function getTime($date)
        {
            return Carbon::parse($date)->format('H:i');
        }

        $not_ready_sign_in = true;
        $hasShift = false;
        $output = '';

        $roasters = TimeKeeper::where([
            ['employee_id', $employee_id],
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['sing_out', null],
            ['roaster_status_id', Session::get('roaster_status')['Accepted']],
            ['project_id', $project_id]
        ])->where(function ($q) {
            $q->where('sing_in', '!=', null);
            $q->orWhere(function ($q) {
                $q->where('shift_end', '>', Carbon::now());
            });
        })->where(function ($q) {
            $q->where('roaster_date', Carbon::now()->format("Y-m-d"));
            $q->orWhere(function ($q) {
                $q->where('roaster_date', Carbon::now()->subDay()->format("Y-m-d"));
                $q->where('shift_end', '>', Carbon::now()->format("Y-m-d"));
            });
        })
            ->orderBy('shift_start', 'asc')->get();

        if ($roasters->count() > 0) {
            if ($roasters->where('sing_in', '!=', null)->count()) {
                $already_sign_in = true;
            } else {
                $already_sign_in = false;
            }
            $output = '<input type="number" id="total_entry" value="' . $roasters->count() . '" hidden>';
            foreach ($roasters as $k => $roster) {
                if ($roster->sing_out == null && ($roster->shift_start <= Carbon::now()->addMinutes(15))) {
                    $not_ready_sign_in = false;
                }

                $output .= '
                <input type="datetime" id="shift_start' . $k . '" value="' . $roster->shift_start . '" hidden>
                <input type="datetime" id="shift_end' . $k . '" value="' . $roster->shift_end . '" hidden>
                <input type="datetime" id="sing_in' . $k . '" value="' . $roster->sing_in . '" hidden>
                <input type="datetime" id="sing_out' . $k . '" value="' . $roster->sing_out . '" hidden>

                <div class="col-lg-4 col-md-6 m-auto">
                <div class="card plan-card border-primary text-center">
                <div class="justify-content-between align-items-center p-75">
                    <p id="countdown' . $k . '" class="mb-1" ' . ($roster->sing_in == null ? "" : "hidden") . '></p>
                    <h3 id="working' . $k . '" class="mb-0" ' . ($roster->sing_in == null ? "hidden" : "") . '></h3>

                    <p id="shift-end-in' . $k . '" class="mb-1" ' . ($roster->sing_in == null ? "hidden" : "") . '></p>


                    <div class="badge badge-light-primary">
                        <h6>' . $roster->project->pName . '</h6>
                    </div>
                    <p class="mb-1">Shift time, ' . getTime($roster->shift_start) . ' - ' . getTime($roster->shift_end) . ' </p>

                    <button class="btn btn-gradient-primary text-center btn-block"
                    ' . ($already_sign_in == $roster->sing_in ? "" : "disabled") . '
                    onclick="openPhotoModal(' . $roster->id . ',`' . ($roster->sing_in == null ? "/admin/sign/in/timekeeper" : "/admin/sign/out/timekeeper") . '`)"
                     ' . ($roster->sing_out == null && ($roster->shift_start <= Carbon::now()->addMinutes(15)) ? "" : "disabled") . '>' . ($roster->sing_in == null ? 'Start Shift' : 'Sign Out') . '</button>

                    </div>
                </div>
                </div>
                ';
            }

            // return response()->json([
            //     'data' => $output,
            //     'status' => 'success',
            //     'hasShift' => true,
            // ]);
            $hasShift = true;
        }
        if ($not_ready_sign_in) {
            $output .= '
                <div class="col-lg-4 col-md-6 m-auto">
                <div class="card plan-card border-primary text-center pt-1 pb-1">
                    <div class="justify-content-between align-items-center pt-75">
    
                        <div class="card-body">
                            <p class="mb-0 text-muted">You have no scheduled shift at this time</p>
                            <button class="btn btn-gradient-primary text-center btn-block" data-toggle="modal" data-target="#userAddTimeKeeper">Start unscheduled shift</button>
                            
                        </div>
    
                    </div>
                </div>
            </div>
                ';
        }

        return response()->json([
            'data' => $output,
            'status' => 'success',
            'hasShift' => $hasShift,
        ]);
    }

    public function storeTimekeeper(Request $request)
    {
        $shift = TimeKeeper::where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['employee_id', $request->employee_id],
            ['sing_out', null],
        ])
            ->where(function ($q) {
                $q->where('sing_in', '!=', null);
                $q->orWhere(function ($q) {
                    $q->where('shift_start', '<', Carbon::now());
                    $q->where('shift_end', '>', Carbon::now());
                });
            })
            ->where(function ($q) {
                $q->where('roaster_date', Carbon::now()->format("Y-m-d"));
                $q->orWhere(function ($q) {
                    $q->where('roaster_date', Carbon::now()->subDay()->format("Y-m-d"));
                    $q->where('shift_end', '>', Carbon::now()->format("Y-m-d"));
                });
            })
            ->first();
        if ($shift) {
            return response()->json([
                'status' => "you're already signed in " . $shift->project->pName
            ]);
        }

        $project = Project::find($request->project_id);
        $duration = 4;
        $shift_start = Carbon::now();
        $shift_end = Carbon::now()->addHours(4);

        $timekeeper = new TimeKeeper();
        $timekeeper->user_id = Auth::id();
        $timekeeper->employee_id = $request->employee_id;
        $timekeeper->client_id = $project->clientName;
        $timekeeper->project_id = $request->project_id;
        $timekeeper->company_id = Auth::user()->company_roles->first()->company->id;
        $timekeeper->roaster_date = Carbon::today()->toDateString();
        $timekeeper->shift_start = $shift_start;
        $timekeeper->shift_end = $shift_end;
        $timekeeper->sing_in = $shift_start;
        $timekeeper->company_code = Auth::user()->company_roles->first()->company->id;
        $timekeeper->duration = $duration;
        $timekeeper->ratePerHour = $request->ratePerHour;
        $timekeeper->amount = $duration * $request->ratePerHour;
        $timekeeper->job_type_id = $request->job_type_id;
        $timekeeper->roaster_status_id = Session::get('roaster_status')['Accepted'];
        $timekeeper->roaster_type = 'Unschedueled';
        $timekeeper->remarks = $request->remarks;
        $timekeeper->save();

        AutoSignOutJob::dispatch($timekeeper->id)->delay(now()->addHours(6));

        if ($request->image) {
            $user_activity = new UserActivityPhotoController;
            $user_activity->store($request->image, $timekeeper->id, 'sign_in');
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function signIn(Request $request)
    {
        $roster = TimeKeeper::find($request->timekeeper_id);
        $roster->sing_in = Carbon::now();
        $roster->save();

        if ($request->image) {
            $user_activity = new UserActivityPhotoController;
            $user_activity->store($request->image, $roster->id);
        }

        AutoSignOutJob::dispatch($roster->id)->delay(now()->addHours(6));


        return response()->json([
            'status' => 'success',
        ]);
    }
    public function signOut(Request $request)
    {
        $roster = TimeKeeper::find($request->timekeeper_id);
        $roster->sing_out = Carbon::now();

        if (!$roster->sing_out) {
            $roster->sing_out = Carbon::now();
            if ($roster->roaster_type == 'Unschedueled') {
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

        if ($request->image) {
            $user_activity = new UserActivityPhotoController;
            $user_activity->store($request->image, $request->timekeeper_id, 'sign_out');
        }

        return response()->json([
            'status' => 'success',
        ]);
    }
}
