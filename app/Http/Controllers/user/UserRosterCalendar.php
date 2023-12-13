<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\RoasterStatus;

class UserRosterCalendar extends Controller
{
    public function index(){
        Session::put('user_current_week', 0);

        return view('pages.User.schedule.index');
    }

    public function get_projects()
    {
        $week = Carbon::now()->addWeek(Session::get('user_current_week'));

        $shift = DB::table('time_keepers')
            ->select(DB::raw(
                'p.*'
            ))
            ->leftJoin('projects as p', 'p.id', 'time_keepers.project_id')
            // ->groupBy("p.pName")
            ->whereBetween('roaster_date', [$week->startOfWeek()->toDateString(), $week->endOfWeek()->toDateString()])
            ->where([
                ['employee_id', Auth::user()->employee->id],
                ['roaster_type', 'Schedueled'],
                ['roaster_status_id', Session::get('roaster_status')['Accepted']]
            ])
            ->where(function ($q) {
                avoid_rejected_key($q);
            })
            ->get();

        $inducted = DB::table('inductedsites')
            ->select(DB::raw(
                'p.*'
            ))
            ->leftJoin('projects as p', 'p.id', 'inductedsites.project_id')
            ->where([
                ['employee_id', Auth::user()->employee->id],
            ])
            ->get();

        $projects = $shift->merge($inducted);
        // Session::put('user_current_project',($projects->first()?$projects->first()->id:0));
        // return response()->json(['projects'=>$projects->unique('id')]);
        return $projects->unique('id');
    }

    private function C($w_d)
    {
        global $week;
        return Carbon::now()->dayOfWeek == $w_d && (Carbon::parse($week)->weekOfYear == Carbon::now()->weekOfYear) ? 'bg-light-secondary' : '';
    }

    public function get_shifts(Request $request)
    {
        global $week;
        $notification = "";
        $search_date = null;
        if ($request->go_to == 'previous') {
            $current_week = Session::get('s_current_week');
            Session::put('s_current_week', $current_week - 1);

            $week = Carbon::now()->addWeek(Session::get('s_current_week'));
        } elseif ($request->go_to == 'next') {
            $current_week = Session::get('s_current_week');
            Session::put('s_current_week', $current_week + 1);

            $week = Carbon::now()->addWeek(Session::get('s_current_week'));
        } elseif ($request->go_to == 'current') {
            $week = Carbon::now()->addWeek(Session::get('s_current_week'));
        } elseif ($request->go_to == 'search_date') {
            $search_date = Carbon::parse($request->search_date)->addDay();
            $week_diff = Carbon::now()->startOfWeek()->diffInWeekendDays(Carbon::parse($request->search_date)->startOfWeek(), false);
            Session::put('s_current_week', $week_diff / 2);
            $week = Carbon::now()->addWeek(Session::get('s_current_week'));
        } else {
            $week = Carbon::now();
        }

        $projects = $this->get_projects();

        $filter_project = $request->project ? ['project_id', $request->project] : ['project_id', '>', 0];

        $start_date = Carbon::parse($week)->startOfWeek();
        $end_date = Carbon::parse($week)->endOfWeek();

        $output = "";
        $employees = DB::table('time_keepers')
            ->select(DB::raw(
                'e.* ,
                e.fname as name,
                sum(time_keepers.duration) as total_hours,
                sum(time_keepers.amount) as total_amount ,
                count(time_keepers.id) as record'
            ))
            ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
            ->where([
                ['e.company', Auth::user()->company_roles->first()->company->id],
                // ['e.role', 3],
                $filter_project,
            ])
            ->where(function ($q) {
                // $q->where('roaster_type', 'Schedueled');
                // $q->where('roaster_status_id', Session::get('roaster_status')['Accepted']);
                $q->whereIn('roaster_status_id', [Session::get('roaster_status')['Published'], Session::get('roaster_status')['Accepted']]);
                // $q->orWhere(function ($q) {
                //     $q->where('roaster_type', 'Unschedueled');
                // });
            })
            ->groupBy("e.id")
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

                $timekeepers = TimeKeeper::where([
                    ['employee_id', $employee->id],
                    ['company_code', Auth::user()->company_roles->first()->company->id],
                    $filter_project
                ])
                    ->where(function ($q) {
                        // $q->where('roaster_type', 'Schedueled');
                        // $q->where('roaster_status_id', Session::get('roaster_status')['Accepted']);
                        $q->whereIn('roaster_status_id', [Session::get('roaster_status')['Published'], Session::get('roaster_status')['Accepted']]);
                        // $q->orWhere(function ($q) {
                        //     $q->where('roaster_type', 'Unschedueled');
                        // });
                    })->whereBetween('roaster_date', [$start_date, $end_date])
                    ->get();

                foreach ($timekeepers as $keyKeeper => $timekeeper) {
                    if ($timekeeper->roaster_status_id == Session::get('roaster_status')['Published'] && $timekeeper->shift_end < Carbon::now()) {
                        unset($timekeepers[$keyKeeper]);
                        continue;
                    }
                    // $check_is_applied = 0;

                    if ($timekeeper->roaster_type == 'Unschedueled') {
                        $check_is_applied = 1;
                    }elseif ($timekeeper->roaster_status_id == $this->roaster_status('Rejected')) {
                        $check_is_applied = 3;
                    } elseif ($timekeeper->roaster_status_id == $this->roaster_status('Accepted')) {
                        $check_is_applied = 1;
                    } else {
                        $check_is_applied = 0;
                    }

                    // if ($check_is_applied == 0) {
                    //     continue;
                    // }

                    $roaster_day = Carbon::parse($timekeeper->roaster_date)->format('D');
                    $json = json_encode($timekeeper->toArray(), false);

                    $status = "";
                    $symbol = "";
                    $colors = "style='width: 125px; color:#000 !important'";

                    if ($timekeeper->roaster_status_id == Session::get('roaster_status')['Published']) {
                        $status = "bg-gradient-warning";
                        $symbol = "<i data-feather='loader'></i>";
                    }elseif ($timekeeper->roaster_status_id == Session::get('roaster_status')['Accepted']) {
                        $status = "bg-gradient-success";
                        $symbol = "";
                    }

                    if ($timekeeper->sing_in == null && $timekeeper->shift_start <= Carbon::now() && $timekeeper->shift_end >= Carbon::now()) {
                        $status = "bg-gradient-danger";
                    }
                    if ($timekeeper->sing_in != null && $timekeeper->sing_out != null && $timekeeper->shift_start <= Carbon::now() && $timekeeper->shift_end >= Carbon::now()) {
                        $status = "bg-gradient-success";
                    }
                    if ($timekeeper->sing_in != null && $timekeeper->sing_out == null && $timekeeper->shift_start <= Carbon::now() && $timekeeper->shift_end >= Carbon::now()) {
                        $status = "bg-gradient-info";
                    }
                    if ($timekeeper->sing_in != null && $timekeeper->sing_out != null && $timekeeper->shift_end < Carbon::now()) {
                        $status = "bg-gradient-primary";
                        if($timekeeper->is_approved == 0) {
                            $symbol = "<i data-feather='clock'></i>";
                        }else{
                            $symbol = "";
                        }
                    }
                    if ($timekeeper->sing_in != null && $timekeeper->sing_out == null && $timekeeper->shift_start <= Carbon::now() && $timekeeper->shift_end < Carbon::now()) {
                        $status = "bg-gradient-danger";
                    }
                    // if ($timekeeper->roaster_type  == 'Unschedueled') {
                    //     $status = "bg-gradient-info";
                    // } elseif ($timekeeper->shift_start < Carbon::now() && $timekeeper->sing_in == null) {
                    //     $status = "bg-gradient-danger";
                    // } elseif ($timekeeper->sing_in != null && $timekeeper->sing_out == null && $timekeeper->shift_end < Carbon::now()) {
                    //     $status = "bg-gradient-danger";
                    // } else {
                    //     $status = "bg-gradient-success";
                    // }

                    if (!$request->project) {
                        $project_name = "<span class='font-small-2 font-weight-bolder'>" . $timekeeper->project->pName . "</span><br>";
                    } else {
                        $project_name = '';
                    }

                    $has_app = $timekeeper->payment_status || $timekeeper->is_approved ? true : false;
                    $val = "<div $colors  class='text-uppercase shadow p-50 roster mb-50 mt-50 editBtn font-weight-bolder " . $status . "' data-copy='false' data-row='$json'>
                    <i data-feather='" . ($timekeeper->payment_status ? 'dollar-sign' : 'check-circle') . "' class='float-right' " . ($has_app ? '' : 'hidden') . "></i>
                    <div>$project_name" . "<span class='font-small-2 font-weight-bold'>" . Carbon::parse($timekeeper->shift_start)->format('H:i') . "-" . Carbon::parse($timekeeper->shift_end)->format('H:i') . " (" . round($timekeeper->duration, 2) . ")</span><br>" . "<span class='font-small-2'>" . $timekeeper->job_type->name . "<br>" . $symbol . "</span></div></div>";

                    if ($roaster_day == 'Mon') {
                        $mon_ .= $val;
                    } elseif ($roaster_day == 'Tue') {
                        $tue_ .= $val;
                    } elseif ($roaster_day == 'Wed') {
                        $wed_ .= $val;
                    } elseif ($roaster_day == 'Thu') {
                        $thu_ .= $val;
                    } elseif ($roaster_day == 'Fri') {
                        $fri_ .= $val;
                    } elseif ($roaster_day == 'Sat') {
                        $sat_ .= $val;
                    } elseif ($roaster_day == 'Sun') {
                        $sun_ .= $val;
                    }
                }
                if(count($timekeepers) > 0) 
                {
                    if (!$mon_) {
                        $mon_ = "<div style='width:125px'></div>";
                    }
                    if (!$tue_) {
                        $tue_ = "<div style='width:125px'></div>";
                    }
                    if (!$wed_) {
                        $wed_ = "<div style='width:125px'></div>";
                    }
                    if (!$thu_) {
                        $thu_ = "<div style='width:125px'></div>";
                    }
                    if (!$fri_) {
                        $fri_ = "<div style='width:125px'></div>";
                    }
                    if (!$sat_) {
                        $sat_ = "<div style='width:125px'></div>";
                    }
                    if (!$sun_) {
                        $sun_ = "<div style='width:125px'></div>";
                    }

                    if (!$employee->image) {
                        $employee->image = 'images/app/no-image.png';
                    }

                    $bg = $key % 2 == 0 ? 'tdbg' : 'tdbglight';
                    $output .= '<tr class="">' .
                        '<td class="text-center font-weight-bold ' . $bg . '">
                            <div class="avatar-content">
                                <img class="rounded-circle mb-25" src="' . 'https://api.eazytask.au/' . $employee->image . '" alt="" width="35px" height="35px">
                            </div>
                            ' . $employee->fname . ' ' . $employee->mname . ' ' . $employee->lname . '<p class="font-weight-bold text-primary">Hours: ' . $timekeepers->sum('duration') . '</p></td>' .
                        '<td class="' . $bg . ' ' . $this->C(1) . '">' . $mon_ . '</td>' .
                        '<td class="' . $bg . ' ' . $this->C(2) . '">' . $tue_ . '</td>' .
                        '<td class="' . $bg . ' ' . $this->C(3) . '">' . $wed_ . '</td>' .
                        '<td class="' . $bg . ' ' . $this->C(4) . '">' . $thu_ . '</td>' .
                        '<td class="' . $bg . ' ' . $this->C(5) . '">' . $fri_ . '</td>' .
                        '<td class="' . $bg . ' ' . $this->C(6) . '">' . $sat_ . '</td>' .
                        '<td class="' . $bg . ' ' . $this->C(0) . '">' . $sun_ . '</td>' .
                        '</tr>';
                }
            }

            return Response()->json([
                'current_project' => $request->project,
                'projects' => $projects,
                'data' => $output,
                'week_date' => $start_date->format('d M, Y') . ' -  ' . $end_date->format('d M, Y'),
                'notification' => $notification,
                'search_date' => $search_date
            ]);
        }
        return Response()->json([
            'current_project' => $request->project,
            'projects' => $projects,
            'data' => '',
            'week_date' => $start_date->format('d M, Y') . ' -  ' . $end_date->format('d M, Y'),
            'notification' => '',
            'search_date' => $search_date
        ]);

        // PREVIOUS CODE
        // return $this->search_report($request);
        // $notification = "";
        // $search_date = null;
        // if ($request->go_to == 'previous') {
        //     $current_week = Session::get('user_current_week');
        //     Session::put('user_current_week', $current_week - 1);

        //     $week = Carbon::now()->addWeek(Session::get('user_current_week'));
        // } elseif ($request->go_to == 'next') {
        //     $current_week = Session::get('user_current_week');
        //     Session::put('user_current_week', $current_week + 1);

        //     $week = Carbon::now()->addWeek(Session::get('user_current_week'));
        // } elseif ($request->go_to == 'current') {
        //     // $curerent_project = $request->project;
        //     $week = Carbon::now()->addWeek(Session::get('user_current_week'));
        // } elseif ($request->go_to == 'search_date') {
        //     $search_date = Carbon::parse($request->search_date)->addDay();
        //     $week_diff = Carbon::now()->startOfWeek()->diffInWeekendDays(Carbon::parse($request->search_date)->startOfWeek(), false);
        //     Session::put('user_current_week', $week_diff / 2);
        //     $week = Carbon::now()->addWeek(Session::get('user_current_week'));
        // } else {
        //     $week = Carbon::now();
        // }

        // $projects = $this->get_projects();

        // if (!$request->project || $request->go_to != 'current') {
        //     $request->project = $projects->first() ? $projects->first()->id : '';
        // }

        // $filter_project = $request->project ? ['project_id', $request->project] : ['employee_id', '>', 0];

        // $start_date = Carbon::parse($week)->startOfWeek();
        // $end_date = Carbon::parse($week)->endOfWeek();

        // $output = "";

        // if ($request->project) {
        //     $employees = DB::table('time_keepers')
        //         ->select(DB::raw(
        //             'e.* ,
        //         e.fname as name,
        //         sum(time_keepers.duration) as total_hours,
        //         sum(time_keepers.amount) as total_amount ,
        //         count(time_keepers.id) as record'

        //         ))
        //         ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
        //         ->where([
        //             ['e.company', Auth::user()->company_roles->first()->company->id],
        //             ['e.role', 3],
        //             $filter_project,
        //             ['roaster_type', 'Schedueled'],
        //             ['roaster_status_id', Session::get('roaster_status')['Accepted']]
        //         ])
        //         ->where(function ($q) {
        //             avoid_rejected_key($q);
        //         })
        //         ->groupBy("e.id")
        //         ->orderBy('e.fname', 'asc')
        //         ->whereBetween('roaster_date', [$start_date, $end_date])
        //         ->get();

        //     if ($employees->count() > 0) {
        //         foreach ($employees as $key => $employee) {
        //             $mon_ = '';
        //             $tue_ = '';
        //             $wed_ = '';
        //             $thu_ = '';
        //             $fri_ = '';
        //             $sat_ = '';
        //             $sun_ = '';

        //             $timekeepers = TimeKeeper::where([
        //                 ['employee_id', $employee->id],
        //                 ['company_code', Auth::user()->company_roles->first()->company->id],
        //                 ['roaster_type', 'Schedueled'],
        //                 ['roaster_status_id', Session::get('roaster_status')['Accepted']],
        //                 $filter_project
        //             ])->whereBetween('roaster_date', [$start_date, $end_date])
        //                 ->where(function ($q) {
        //                     avoid_rejected_key($q);
        //                 })
        //                 ->get();

        //             foreach ($timekeepers as $timekeeper) {
        //                 $roaster_day = Carbon::parse($timekeeper->roaster_date)->format('D');
        //                 $json = json_encode($timekeeper->toArray(), false);

        //                 $status = "";
        //                 $colors = "style='width: 125px'";
        //                 if ($timekeeper->roaster_type == 'Unschedueled') {
        //                     $status = "bg-light-info";
        //                 } else {
        //                     $colors = "style='border-radius: 5px; width:125px; color:" . $timekeeper->roaster_status->color . " !important; background:" . $timekeeper->roaster_status->color . "1f !important'";
        //                 }

        //                 if (!$request->project) {
        //                     $project_name = "<span class='font-small-2 font-weight-bolder'>" . $timekeeper->project->pName . "</span><br>";
        //                 } else {
        //                     $project_name = '';
        //                 }

        //                 $val = "<div $colors class='text-uppercase shadow p-50 mb-50 mt-50 " . $status . "'>
        //             <div class='dropdown-items-wrapper'>

        //         <div>$project_name" . "<span class='font-small-2 font-weight-bold'>" . Carbon::parse($timekeeper->shift_start)->format('H:i') . "-" . Carbon::parse($timekeeper->shift_end)->format('H:i') . " (" . round($timekeeper->duration, 2) . ")</span><br>" . "<span class='font-small-2'>" . $timekeeper->job_type->name . "</span></div></div></div>";

        //                 if ($roaster_day == 'Mon') {
        //                     $mon_ .= $val;
        //                 } elseif ($roaster_day == 'Tue') {
        //                     $tue_ .= $val;
        //                 } elseif ($roaster_day == 'Wed') {
        //                     $wed_ .= $val;
        //                 } elseif ($roaster_day == 'Thu') {
        //                     $thu_ .= $val;
        //                 } elseif ($roaster_day == 'Fri') {
        //                     $fri_ .= $val;
        //                 } elseif ($roaster_day == 'Sat') {
        //                     $sat_ .= $val;
        //                 } elseif ($roaster_day == 'Sun') {
        //                     $sun_ .= $val;
        //                 }
        //             }
        //             if (!$mon_) {
        //                 $mon_ = "<div style='width:125px'></div>";
        //             }
        //             if (!$tue_) {
        //                 $tue_ = "<div style='width:125px'></div>";
        //             }
        //             if (!$wed_) {
        //                 $wed_ = "<div style='width:125px'></div>";
        //             }
        //             if (!$thu_) {
        //                 $thu_ = "<div style='width:125px'></div>";
        //             }
        //             if (!$fri_) {
        //                 $fri_ = "<div style='width:125px'></div>";
        //             }
        //             if (!$sat_) {
        //                 $sat_ = "<div style='width:125px'></div>";
        //             }
        //             if (!$sun_) {
        //                 $sun_ = "<div style='width:125px'></div>";
        //             }

        //             if (!$employee->image) {
        //                 $employee->image = 'images/app/no-image.png';
        //             }

        //             $w = Carbon::parse($week)->startOfWeek();
        //             $bg = $key % 2 == 0 ? 'tdbg' : 'tdbglight';
        //             $output .= '<tr class="">' .
        //                 '<td class="text-center font-weight-bold ' . $bg . '">
        //                 <div class="avatar-content">
        //                     <img class="rounded-circle mb-25" src="' . 'https://api.eazytask.au/' . $employee->image . '" alt="" width="35px" height="35px">
        //                 </div>
        //                 ' . $employee->fname . ' ' . $employee->mname . ' ' . $employee->lname . '<p class="font-weight-bold text-primary">Hours: ' . $timekeepers->sum('duration') . '</p></td>' .
        //                 '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $mon_ . '</td>' .
        //                 '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $tue_ . '</td>' .
        //                 '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $wed_ . '</td>' .
        //                 '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $thu_ . '</td>' .
        //                 '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $fri_ . '</td>' .
        //                 '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $sat_ . '</td>' .
        //                 '<td class="' . $bg . '" ondrop="drop(event,' . $employee->id . ',`' . $w->addDay()->format('d-m-Y') . '`)" ondragover="allowDrop(event)">' . $sun_ . '</td>' .
        //                 '</tr>';
        //         }

        //         return Response()->json([
        //             'current_project' => $request->project,
        //             'projects' => $projects,
        //             'data' => $output,
        //             'week_date' => $start_date->format('d M, Y') . ' -  ' . $end_date->format('d M, Y'),
        //             'notification' => $notification,
        //             'search_date' => $search_date
        //         ]);
        //     }
        // }
        // // }
        // return Response()->json([
        //     'current_project' => $request->project,
        //     'projects' => $projects,
        //     'data' => '',
        //     'week_date' => $start_date->format('d M, Y') . ' -  ' . $end_date->format('d M, Y'),
        //     'notification' => '',
        //     'search_date' => $search_date
        // ]);
    }
    
    private function roaster_status($name)
    {
        $roaster_status = RoasterStatus::where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['name',$name]
        ])->first();
        return $roaster_status->id;
    }
}
