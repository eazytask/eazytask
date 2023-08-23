<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Project;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SignInStatusController extends Controller
{
    public function index()
    {
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        $employees = Employee::where('company', Auth::user()->company_roles->first()->company->id)->get();

        return view('pages.Admin.sign_in_status.index', compact('projects', 'employees'));
    }

    public function search(Request $request)
    {
        $filter_project = $request->project != 'all' ? ['project_id', $request->project] : ['employee_id', '>', 0];
        $filter_employee = $request->employee != 'all' ? ['employee_id', $request->employee] : ['employee_id', '>', 0];

        $start = $request->start;
        $end = $request->end;
        $start_date = $start ? Carbon::parse($start)->toDateString() : Carbon::now()->startOfWeek();
        $end_date = $end ? Carbon::parse($end)->toDateString() : Carbon::now()->endOfWeek();

        // function avoid_expired_license($q) use($request){
        // }

        $dates = TimeKeeper::where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            // ['sing_in', '!=', null],
            $filter_project,
            $filter_employee
        ])
            ->where(function ($q) {
                $q->where('roaster_type', 'Schedueled');
                // $q->where('roaster_status_id', Session::get('roaster_status')['Accepted']);
                $q->orWhere(function ($q) {
                    $q->where('roaster_type', 'Unschedueled');
                    $q->where('sing_in', '!=', null);
                });
            })
            ->whereBetween('roaster_date', [$start_date, $end_date])
            ->orderBy('shift_start', 'desc');

        if ($request->type == 'sign_in') {
            $dates->where('sing_in', '!=', null);
        } elseif ($request->type == 'not_sign_in') {
            $dates->where('sing_in', null);
        }
        $all_dates =$dates
        ->get()
        ->groupBy(function ($data) {
            return $data->roaster_date;
        });
        
        $html = '';
        function img_($img)
        {
            return $img ? $img : 'images/app/no-image.png';
        }
        function activity_($row, $field)
        {
            return $row->user_activity ? $row->user_activity->$field : null;
        }
        foreach ($all_dates as $loop => $timekeepers) {
            foreach ($timekeepers as $key => $row) {
                if ($key == 0) {
                    $rowspan = "<td class='bg-light text-dark' rowspan='" . $timekeepers->count() . "'>" . Carbon::parse($row->roaster_date)->format('d-m-Y') . "</td>";
                } else {
                    $rowspan = '';
                }

                $full_name = $row->employee->fname . ' ' . $row->employee->mname . ' ' . $row->employee->lname;
                $address = $row->sing_in?((activity_($row, 'lat') ?? $row->project->lat) . "," . (activity_($row, 'lon') ?? $row->project->lon)):'null';

                $distance = $this->haversineDistance(activity_($row, 'lat'), activity_($row, 'lon'), $row->project->lat, $row->project->lon);
                $distance = $row->sing_in? round($distance, 2) : '0'; //Kilometer
                
                $html .= "
                        <tr class='".($row->sing_in && Carbon::parse($row->shift_start)>=Carbon::parse($row->sing_in)?'':'bg-light-danger')."'>
                        $rowspan
                            <td>
                                <div class='avatar bg-light-primary'>
                                    <div class='avatar-content'>
                                        <img src='" . 'https://api.eazytask.au/' . img_($row->employee->image) . "' alt='' height='32px' width='32px'>
                                    </div>
                                </div>
                            <td>$full_name</td>
                            <td>" . Carbon::parse($row->shift_start)->format('H:i') . "</td>
                            <td>" . Carbon::parse($row->shift_end)->format('H:i') . "</td>
                            <td>" . ($row->sing_in ? Carbon::parse($row->sing_in)->format('H:i') : 'null') . "</td>
                            <td>" . ($row->sing_out ? Carbon::parse($row->sing_out)->format('H:i') : 'null') . "</td>
                            <td>
                                <img src='" . 'https://api.eazytask.au/' . img_(activity_($row, 'sign_in')) . "' alt='' height=60px' width='70px'>
                            </td>
                            <td>
                                <img src='" . 'https://api.eazytask.au/' . img_(activity_($row, 'sign_out')) . "' alt='' height='60px' width='70px'>
                            </td>
                            <td>" . $row->signin_comment . "</td>
                            <td>" . $row->signout_comment . "</td>
                            <td>" . $row->project->pName . "</td>
                            <td><a href='https://www.google.com/maps/search/$address' target='blank'>$address</a> <br>Distance: ($distance) KM</td>
                          </tr>
            ";
            }
        }
        return response()->json(['data' => $html]);
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Radius of the Earth in kilometers
    
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);
    
        $latDiff = $lat2Rad - $lat1Rad;
        $lonDiff = $lon2Rad - $lon1Rad;
    
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($lonDiff / 2) * sin($lonDiff / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earthRadius * $c; // Distance in kilometers
    
        return $distance;
    }
}
