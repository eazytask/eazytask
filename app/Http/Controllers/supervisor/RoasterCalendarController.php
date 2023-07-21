<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use App\Models\TimeKeeper;
use App\Models\Payment;
use App\Models\RoasterType;
use App\Models\RoasterStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Session;
use App\Jobs\MailUserJob;
use App\Mail\NotifyAdmin;
use App\Mail\NotifyUser;
use App\Models\Myavailability;
use App\Notifications\NewShiftNotification;

class RoasterCalendarController extends Controller
{
    public function filter_emoployee(Request $request)
    {
        $employees = [];
        $roster = $request->roster_date;
        $start = $request->shift_start ? $request->shift_start : Carbon::now()->format('h:i');
        $end = $request->shift_end ? $request->shift_end : Carbon::now()->format('h:i');

        $shift_start = Carbon::parse($request->roster_date . $start);
        $shift_end = Carbon::parse($request->roster_date . $end);

        if ($request->filter == 'all') {
            $employees = Employee::where([
                ['company', Auth::user()->supervisor->company],
                ['status', 1],
                ['role', 3]
            ])
            ->where(function ($q) {
              avoid_expired_license($q);
            })
            ->orderBy('fname', 'asc')->get();
        } elseif ($request->filter == 'inducted') {
            if ($request->project_id) {
                $roster_date = $roster ? Carbon::parse($roster) : Carbon::now()->format('Y-m-d');
                $employees = DB::table('inductedsites')
                    ->select(DB::raw(
                        'e.* ,
                        e.fname as name'
                    ))
                    ->leftJoin('employees as e', 'e.id', 'inductedsites.employee_id')
                    ->where([
                        ['e.company', Auth::user()->supervisor->company],
                        ['e.role', 3],
                        ['e.status', 1],
                        ['project_id', $request->project_id]
                    ])
                    ->where(function ($q) {
                        e_avoid_expired_license($q);
                    })
                    ->groupBy("e.id")
                    ->orderBy('e.fname','asc')
                    ->where('induction_date', $roster_date)
                    ->get();
            }
        } elseif ($request->filter == 'available') {
            $all_employees = Employee::where([
                ['company', Auth::user()->supervisor->company],
                ['role', 3],
                ['status', 1]
            ])
            ->where(function ($q) {
              avoid_expired_license($q);
            })
                ->groupBy("fname")
                ->get();

            $avail_employees = [];
            foreach ($all_employees as $row) {
                $availity = Myavailability::where([
                    ['company_code', Auth::user()->supervisor->company],
                    ['employee_id', $row->id],
                ])
                    ->where(function ($q) use ($shift_start, $shift_end) {
                        $q->where('start_date', '<=', $shift_start);
                        $q->where('end_date', '>=', $shift_start);
                        $q->orWhere(function ($q) use ($shift_end) {
                            $q->where('start_date', '<=', $shift_end);
                            $q->where('end_date', '>=', $shift_end);
                        });
                    })
                    ->first();

                if (!$availity) {
                    array_push($avail_employees, $row);
                }
            }

            $employees = [];
            foreach ($avail_employees as $row) {
                $timekeeper = TimeKeeper::where([
                    ['company_code', Auth::user()->supervisor->company],
                    ['employee_id', $row->id]
                ])
                    ->where(function ($q) use ($shift_start, $shift_end) {
                        $q->where('shift_start', '<=', $shift_start);
                        $q->where('shift_end', '>=', $shift_start);
                        $q->orWhere(function ($q) use ($shift_end) {
                            $q->where('shift_start', '<=', $shift_end);
                            $q->where('shift_end', '>=', $shift_end);
                        });
                    })
                    ->first();

                if (!$timekeeper) {
                    array_push($employees, $row);
                }
            }
        }

        return response()->json([
            'employees' => $employees
        ]);
    }

    public function index()
    {
        Session::put('current_week', 0);
        $employees = Employee::where([
            ['company', Auth::user()->supervisor->company],
            ['role', 3],
            ['status', 1]
        ])
        ->where(function ($q) {
          avoid_expired_license($q);
        })->orderBy('fname', 'asc')->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        $job_types = JobType::where('company_code', Auth::user()->supervisor->company)->get();
        $roaster_status = RoasterStatus::where('company_code', Auth::user()->supervisor->company)->orderBy('name', 'asc')->get();

        return view('pages.supervisor.roaster.index', compact('employees', 'projects', 'job_types', 'roaster_status'));
    }

    public function search(Request $request)
    {
        // return $this->search_report($request);
        $search_date = null;
        $notification = "";
        if ($request->go_to == 'previous') {
            $current_week = Session::get('current_week');
            Session::put('current_week', $current_week - 1);

            $week = Carbon::now()->addWeek(Session::get('current_week'));
        } elseif ($request->go_to == 'next') {
            $current_week = Session::get('current_week');
            Session::put('current_week', $current_week + 1);

            $week = Carbon::now()->addWeek(Session::get('current_week'));
        } elseif ($request->go_to == 'copy') {
            $this->copy_week($request);
            Session::put('current_week', 1);

            $week = Carbon::now()->addWeeks();
        } elseif ($request->go_to == 'publish') {
            $this->publish($request);

            $notification = "Roster Successfully Published.";
            $week = Carbon::now()->addWeek(Session::get('current_week'));
        } elseif ($request->go_to == 'current') {
            $week = Carbon::now()->addWeek(Session::get('current_week'));
        } elseif ($request->go_to == 'search_date') {
            $search_date = Carbon::parse($request->search_date)->addDay();
            $week_diff = Carbon::now()->startOfWeek()->diffInWeekendDays(Carbon::parse($request->search_date)->startOfWeek(), false);
            Session::put('current_week', $week_diff / 2);
            $week = Carbon::now()->addWeek(Session::get('current_week'));
        } else {
            $week = Carbon::now();
        }

        $filter_project = $request->project ? ['project_id', $request->project] : ['employee_id', '>', 0];

        $start_date = Carbon::parse($week)->startOfWeek();
        $end_date = Carbon::parse($week)->endOfWeek();

        $output = "";
        $report = "";
        $employees = DB::table('time_keepers')
            ->select(DB::raw(
                'e.* ,
                e.fname as name,
                sum(time_keepers.duration) as total_hours,
                sum(time_keepers.amount) as total_amount ,
                count(time_keepers.id) as record'

            ))
            ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
            ->where('e.user_id', Auth::user()->supervisor->user_id)
            ->where([
                $filter_project,
                // ['roaster_type', 'Schedueled']
            ])
            ->groupBy("e.id")
            ->orderBy('e.fname','asc')
            ->whereBetween('roaster_date', [$start_date, $end_date])
            ->get();

        if ($employees->count() > 0) {
            foreach ($employees as $key => $employee) {
                $mon_ = '';
                $tue_ = '';
                $wed_ = '';
                $thu_ = '';
                $fri_ = '';
                $sat_ = '';
                $sun_ = '';

                $mon_r = '';
                $tue_r = '';
                $wed_r = '';
                $thu_r = '';
                $fri_r = '';
                $sat_r = '';
                $sun_r = '';
                $timekeepers = TimeKeeper::where([
                    ['employee_id', $employee->id],
                    ['company_code', Auth::user()->supervisor->company],
                    // ['roaster_type', 'Schedueled'],
                    $filter_project
                ])->whereBetween('roaster_date', [$start_date, $end_date])
                    ->get();

                foreach ($timekeepers as $timekeeper) {
                    $roaster_day = Carbon::parse($timekeeper->roaster_date)->format('D');
                    $json = json_encode($timekeeper->toArray(), false);

                    $status = "bg-light-warning";
                    if ($timekeeper->roaster_type == 'Unschedueled') {
                        $status = "bg-light-info";
                    } elseif ($timekeeper->roaster_status->name == 'Published') {
                        $status = "bg-light-primary";
                    } elseif ($timekeeper->roaster_status->name == "Accepted") {
                        $status = "bg-light-success";
                    } elseif ($timekeeper->roaster_status->name == 'Rejected') {
                        $status = "bg-light-danger";
                    }

                    $unique_id = 'drag' . $timekeeper->id;
                    if (!$request->project) {
                        $project_name = "<span class='font-small-2 font-weight-bolder'>" . $timekeeper->project->pName . "</span><br>";
                    } else {
                        $project_name = '';
                    }

                    $val = "<div id='$unique_id' draggable='true' ondragstart='drag(event,$timekeeper->id)' style='border-radius: 5px; width:125px' class='text-uppercase shadow p-50 roster mb-50 mt-50 " . $status . "'>
                    <div class='dropdown-items-wrapper'>
                    <i data-feather='more-vertical' id='dropdownMenuLink1' role='button' data-toggle='dropdown' aria-expanded='false' style='margin-left:-5px;' class='float-right'></i>
                    <div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuLink1'>
                        <a class='dropdown-item editBtn' href='javascript:void(0)' data-employee='" . $timekeeper->employee . "' data-copy='false' data-row='$json'>
                            <i data-feather='edit' class='mr-25'></i>
                            <span class='align-middle'>Edit</span>
                        </a>
                        <a class='dropdown-item editBtn' href='javascript:void(0)' data-copy='true' data-row='$json'>
                            <i data-feather='copy' class='mr-25'></i>
                            <span class='align-middle'>Copy</span>
                        </a>
                        <a class='dropdown-item' href='javascript:void(0)' onclick='deleteRoaster($timekeeper->id)'>
                            <i data-feather='trash' class='mr-25'></i>
                            <span class='align-middle'>Delete</span>
                        </a>
                    </div>
                </div>
                <div>$project_name" . "<span class='font-small-2 font-weight-bold'>" . Carbon::parse($timekeeper->shift_start)->format('H:i') . "-" . Carbon::parse($timekeeper->shift_end)->format('H:i') . " (" . round($timekeeper->duration, 2) . ")</span><br>" . "<span class='font-small-2'>" . $timekeeper->job_type->name . "</span></div></div>";
                    $val_r = "<div style='border-radius: 5px;' class='text-uppercase p-50 roster " . $status . "'><span class='font-small-2 font-weight-bolder'>" . Carbon::parse($timekeeper->shift_start)->format('H:i') . "-" . Carbon::parse($timekeeper->shift_end)->format('H:i') . " (" . round($timekeeper->duration, 2) . ")</span><br>" . "<span class='font-small-2 font-weight-bold'>" . $timekeeper->job_type->name . "</span></div><br>";

                    if ($roaster_day == 'Mon') {
                        $mon_ .= $val;
                        $mon_r .= $val_r;
                    } elseif ($roaster_day == 'Tue') {
                        $tue_ .= $val;
                        $tue_r .= $val_r;
                    } elseif ($roaster_day == 'Wed') {
                        $wed_ .= $val;
                        $wed_r .= $val_r;
                    } elseif ($roaster_day == 'Thu') {
                        $thu_ .= $val;
                        $thu_r .= $val_r;
                    } elseif ($roaster_day == 'Fri') {
                        $fri_ .= $val;
                        $fri_r .= $val_r;
                    } elseif ($roaster_day == 'Sat') {
                        $sat_ .= $val;
                        $sat_r .= $val_r;
                    } elseif ($roaster_day == 'Sun') {
                        $sun_ .= $val;
                        $sun_r .= $val_r;
                    }
                }
                if (!$mon_) {
                    $mon_ = "<div style='width:125px'></div>";
                    $mon_r = "<div style=''></div>";
                }
                if (!$tue_) {
                    $tue_ = "<div style='width:125px'></div>";
                    $tue_r = "<div style=''></div>";
                }
                if (!$wed_) {
                    $wed_ = "<div style='width:125px'></div>";
                    $wed_r = "<div style=''></div>";
                }
                if (!$thu_) {
                    $thu_ = "<div style='width:125px'></div>";
                    $thu_r = "<div style=''></div>";
                }
                if (!$fri_) {
                    $fri_ = "<div style='width:125px'></div>";
                    $fri_r = "<div style=''></div>";
                }
                if (!$sat_) {
                    $sat_ = "<div style='width:125px'></div>";
                    $sat_r = "<div style=''></div>";
                }
                if (!$sun_) {
                    $sun_ = "<div style='width:125px'></div>";
                    $sun_r = "<div style=''></div>";
                }

                if(!$employee->image){
                    $employee->image= 'images/app/no-image.png';
                }

                $w = Carbon::parse($week)->startOfWeek();
                $bg = $key % 2 == 0 ? 'tdbg' : 'tdbglight';
                $output .= '<tr class="">' .
                    '<td class="text-center font-weight-bold ' . $bg . '">
                        <div class="avatar-content">
                        <img class="img-fluid rounded-circle mb-25" src="'.'https://api.eazytask.au/'.$employee->image.'" alt="" width="35px" height="35px">
                        </div>
                        ' . $employee->fname . ' ' . $employee->mname . ' ' . $employee->lname . '<p class="font-weight-bold text-primary">Hours: ' . $timekeepers->sum('duration') . '</p></td>' .
                    '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $mon_ . '</td>' .
                    '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $tue_ . '</td>' .
                    '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $wed_ . '</td>' .
                    '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $thu_ . '</td>' .
                    '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $fri_ . '</td>' .
                    '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $sat_ . '</td>' .
                    '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $sun_ . '</td>' .
                    '</tr>';

                $report .= '<tr class="">' .
                    '<td class="text-center font-weight-bold ' . $bg . '">
                        <div class="avatar-content">
                        <img class="img-fluid rounded-circle mb-25" src="'.'https://api.eazytask.au/'.$employee->image.'" alt="" width="35px" height="35px">
                        </div>
                    ' . $employee->fname . ' ' . $employee->mname . ' ' . $employee->lname . '<p class="font-weight-bold text-primary">Hours: ' . $timekeepers->sum('duration') . '</p></td>' .
                    '<td class="' . $bg . '">' . $mon_r . '</td>' .
                    '<td class="' . $bg . '">' . $tue_r . '</td>' .
                    '<td class="' . $bg . '">' . $wed_r . '</td>' .
                    '<td class="' . $bg . '">' . $thu_r . '</td>' .
                    '<td class="' . $bg . '">' . $fri_r . '</td>' .
                    '<td class="' . $bg . '">' . $sat_r . '</td>' .
                    '<td class="' . $bg . '">' . $sun_r . '</td>' .
                    '</tr>';
            }

            // $project  = $employees ? $timekeeper->project->pName: '';

            if($timekeeper->project->client->cimage){
                $image = $timekeeper->project->client->cimage;
            } else {
                $image = 'images/app/logo.png';
            }
            return Response()->json([
                'logo' => 'https://api.eazytask.au/'.$image,
                'report' => $report,
                'client' => $timekeeper->project->cName,
                'project' => $timekeeper->project->pName,
                'hours' => $employees->sum('total_hours'),
                'amount' => round($employees->sum('total_amount'), 2),
                'data' => $output,
                'week_date' => $start_date->format('d M, Y') . ' -  ' . $end_date->format('d M, Y'),
                'notification' => $notification,
                'search_date' => $search_date
            ]);
        }
        // }
        if ($request->project) {
            $project  = Project::find($request->project);
            if($project->client->cimage){
                $image = $project->client->cimage;
            } else {
                $image = 'images/app/logo.png';
            }

            $client = $project->cName;
            $project = $project->pName;
        } else {
            $client = '';
            $project = '';
            $image = 'images/app/logo.png';
        }



        $no_report = '<tr class="">' .
            '<td class="text-center" colspan="8">No data found!</td>' .
            '</tr>';
        return Response()->json([
            'logo' => 'https://api.eazytask.au/'.$image,
            'report' => $no_report,
            'client' => $client,
            'project' => $project,
            'hours' => 0,
            'amount' => 0,
            'data' => '',
            'week_date' => $start_date->format('d M, Y') . ' -  ' . $end_date->format('d M, Y'),
            'notification' => '',
            'search_date' => $search_date
        ]);
    }

    public function copy_week($request)
    {
        set_time_limit(300);
        $copy_week = Carbon::now()->addWeek(Session::get('current_week'));
        $start_date = Carbon::parse($copy_week)->startOfWeek();
        $end_date = Carbon::parse($copy_week)->endOfWeek();

        $filter_project = $request->project ? ['project_id', $request->project] : ['employee_id', '>', 0];
        $timekeepers = TimeKeeper::where([
            ['company_code', Auth::user()->supervisor->company],
            ['roaster_type', 'Schedueled'],
            $filter_project
        ])->whereBetween('roaster_date', [$start_date, $end_date])
            ->get();

        $roaster_status  = RoasterStatus::where([
            ['name', 'Not published'],
            ['company_code', Auth::user()->supervisor->company]
        ])->first();

        foreach ($timekeepers as $timekeeper) {
            $roster = new TimeKeeper;
            $roster->roaster_date = Carbon::parse($timekeeper->roaster_date)->subWeek(Session::get('current_week'))->addWeeks();
            $roster->shift_start = Carbon::parse($timekeeper->shift_start)->subWeek(Session::get('current_week'))->addWeeks();
            $roster->shift_end = Carbon::parse($timekeeper->shift_end)->subWeek(Session::get('current_week'))->addWeeks();
            $roster->sing_in = null;
            $roster->sing_out = null;
            $roster->payment_status = 0;

            $roster->user_id = Auth::user()->supervisor->user_id;
            $roster->employee_id = $timekeeper->employee_id;
            $roster->client_id = $timekeeper->client_id;
            $roster->project_id = $timekeeper->project_id;
            $roster->company_id = $timekeeper->company_id;
            $roster->company_code = Auth::user()->supervisor->company;
            $roster->duration = $timekeeper->duration;
            $roster->ratePerHour = $timekeeper->ratePerHour;
            $roster->amount = $timekeeper->amount;
            $roster->job_type_id = $timekeeper->job_type_id;
            // $roster->roaster_id = Auth::user()->supervisor->user_id;
            $roster->roaster_status_id = $roaster_status->id;

            $roster->roaster_type = $timekeeper->roaster_type;

            $roster->remarks = $timekeeper->remarks;
            $roster->created_at = Carbon::now();
            $roster->save();

            //=============Payment Store=============//
            // $payment = new Payment();
            // $payment->roaster_id = $roster->id;
            // $payment->save();

            //=============Roster Type Store=========//
            // $roast = new RoasterType;
            // $roast->roaster_id = $roster->id;
            // $roast->save();
        }
    }

    public function publish($request)
    {
        set_time_limit(300);
        $roaster_statuses = RoasterStatus::where([
            ['company_code', Auth::user()->supervisor->company],
        ])->get();
        $status = [];
        foreach ($roaster_statuses as $st) {
            $status[$st->name] = $st->id;
        }

        $copy_week = Carbon::now()->addWeek(Session::get('current_week'));
        $start_date = Carbon::parse($copy_week)->startOfWeek();
        $end_date = Carbon::parse($copy_week)->endOfWeek();
        $filter_project = $request->project ? ['project_id', $request->project] : ['employee_id', '>', 0];

        $employees = DB::table('time_keepers')
            ->select(DB::raw(
                'e.* ,
                e.fname as name'
            ))
            ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
            ->where([
                ['e.user_id', Auth::user()->supervisor->user_id],
                $filter_project
            ])
            ->groupBy("e.id")
            ->orderBy('e.fname','asc')
            ->whereBetween('roaster_date', [$start_date, $end_date])
            ->get();

        if ($employees) {
            foreach ($employees as $employee) {
                $data = [];
                $k = 0;
                $timekeepers = TimeKeeper::where([
                    ['company_code', Auth::user()->supervisor->company],
                    ['employee_id', $employee->id],
                    ['roaster_type', 'Schedueled'],
                    $filter_project
                ])
                    ->whereBetween('roaster_date', [$start_date, $end_date])
                    ->get();

                foreach ($timekeepers as $roster) {
                    if ($roster->roaster_status_id == $status['Not published']) {
                        if (Carbon::parse($roster['shift_start']) >= Carbon::now()) {
                            $roster = TimeKeeper::find($roster['id']);
                            $roster->roaster_status_id = $status['Published'];
                            $roster->save();

                            $project = Project::find($roster->project_id);
                            $data[$k]['project_name'] = $project->pName;
                            $data[$k]['roaster_id'] = $roster->id;
                            $data[$k]['roaster_date'] = $roster->roaster_date;
                            $data[$k]['shift_start'] = $roster->shift_start;
                            $data[$k]['shift_end'] = $roster->shift_end;
                            $k++;
                        }
                    }
                    if ($roster->roaster_status_id == $status['Accepted'] || $roster->roaster_status_id == $status['Rejected']) {
                        if (Carbon::parse($roster['shift_start']) >= Carbon::now()) {
                            $project = Project::find($roster['project_id']);
                            $data[$k]['project_name'] = $project->pName;
                            $data[$k]['roaster_id'] = $roster['id'];
                            $data[$k]['roaster_date'] = $roster['roaster_date'];
                            $data[$k]['shift_start'] = $roster['shift_start'];
                            $data[$k]['shift_end'] = $roster['shift_end'];
                            $k++;
                        }
                    }
                }
                // return $data;
                if ($data) {
                    $employee = Employee::find($timekeepers[0]['employee_id']);
                    $user = User::find($employee->userID);

                    $user->notify(new NewShiftNotification('a new shift assigned for you'));
                    // dispatch(new MailUserJob($user,$data));
                    // Mail::to($user->email)->send(new NotifyUser($user, $data));
                }
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function delete($id)
    {
        $timekeeper = TimeKeeper::find($id);
        if ($timekeeper) {
            $timekeeper->delete();
            $notification = 'Timekeeper deleted successfully.';
        } else {
            $notification = '';
        }

        return response()->json(['notification' => $notification]);
    }

    //=============================Timekeeper Store=============================//
    public function store(Request $request)
    {
        $project = Project::find($request->project_id);

        $shift_start = Carbon::parse($request->roaster_date . $request->shift_start);
        $shift_end = Carbon::parse($shift_start)->addMinute($request->duration * 60);

        $timekeeper = new TimeKeeper();
        $timekeeper->user_id = Auth::user()->supervisor->user_id;
        $timekeeper->employee_id = $request->employee_id;
        $timekeeper->client_id = $project->clientName;
        $timekeeper->project_id = $request->project_id;
        $timekeeper->employee_id = $request->employee_id;
        $timekeeper->company_id = Auth::user()->supervisor->user_id;
        $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
        $timekeeper->shift_start = $shift_start;
        $timekeeper->shift_end = $shift_end;
        $timekeeper->company_code = Auth::user()->supervisor->company;
        $timekeeper->duration = $request->duration;
        $timekeeper->ratePerHour = $request->ratePerHour;
        $timekeeper->amount = $request->amount;
        $timekeeper->job_type_id = $request->job_type_id;
        // $timekeeper->roaster_id = Auth::user()->supervisor->user_id;
        $timekeeper->roaster_status_id = $request->roaster_status_id;
        //   $timekeeper->roaster_type = 'Schedueled';

        if ($request->roaster_type) {
            $timekeeper->roaster_type = $request->roaster_type;
        } else {
            $timekeeper->roaster_type = 'Unschedueled';
        }

        $timekeeper->remarks = $request->remarks;
        $timekeeper->created_at = Carbon::now();
        $timekeeper->save();

        //=============Payment Store=============//
        // $payment = new Payment;
        // $payment->roaster_id = $timekeeper->id;
        // $payment->save();

        //=============Roster Type Store=========//
        // $roster = new RoasterType;
        // $roster->roaster_id = $timekeeper->id;
        // $roster->save();

        $notification = array(
            'message' => 'Roster Successfully added.',
            'alert-type' => 'success'
        );

        return response()->json($notification);
    }
    // =============================Timekeeper Update=============================//
    public function update(Request $request)
    {
        $project = Project::find($request->project_id);

        $timekeeper = TimeKeeper::find($request->timepeeper_id);
        $request->roaster_date =  Carbon::parse($request->roaster_date)->format('d-m-Y');
        $shift_start = Carbon::parse($request->roaster_date . $request->shift_start);
        $shift_end = Carbon::parse($shift_start)->addMinute($request->duration * 60);


        // $timekeeper->user_id = Auth::user()->supervisor->user_id;
        $timekeeper->employee_id = $request->employee_id;
        $timekeeper->client_id = $project->clientName;
        $timekeeper->project_id = $request->project_id;
        // $timekeeper->employee_id = Auth::user()->supervisor->id;
        // $timekeeper->company_id = Auth::user()->supervisor->user_id;
        $timekeeper->roaster_date = Carbon::parse($request->roaster_date);
        $timekeeper->shift_start = $shift_start;
        $timekeeper->shift_end = $shift_end;
        // $timekeeper->company_code = 'mim';
        $timekeeper->duration = $request->duration;
        $timekeeper->ratePerHour = $request->ratePerHour;
        $timekeeper->amount = $request->amount;
        $timekeeper->job_type_id = $request->job_type_id;
        // $timekeeper->roaster_id = Auth::user()->supervisor->user_id;
        $timekeeper->roaster_status_id = $request->roaster_status_id;
        // $timekeeper->roaster_type = $request->roaster_type;
        if ($request->roaster_type) {
            $timekeeper->roaster_type = $request->roaster_type;
        }
        $timekeeper->remarks = $request->remarks;
        $timekeeper->updated_at = Carbon::now();
        $timekeeper->save();

        $notification = array(
            'message' => 'Scheduler Updated Successfully.',
            'alert-type' => 'success'
        );

        return response()->json($notification);
    }
}
