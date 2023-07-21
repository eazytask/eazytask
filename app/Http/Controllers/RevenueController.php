<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Session;

class RevenueController extends Controller
{
    public function index($id)
    {
        Session::put('revenuefromRoaster', null);
        Session::put('revenuetoRoaster', null);
        Session::put('revenue_project_id', null);
        Session::put('revenue_client_id', null);

        $revenues = Revenue::where('user_id', Auth::id())->get();
        $clients = Client::where('user_id', Auth::id())->where('status', '1')->orderBy('cname', 'asc')->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        return view('pages.Admin.revenue.index', compact('revenues', 'clients', 'projects'));
    }

    public function store(Request $request)
    {
        $project = Project::find($request->project_name);

        $revenues = new Revenue();
        $revenues->user_id = Auth::id();
        $revenues->company_code = Auth::user()->company_roles->first()->company->id;
        $revenues->client_name = $project->clientName;
        $revenues->project_name = $request->project_name;
        $revenues->payment_date = Carbon::parse($request->payment_date);
        $revenues->roaster_date_from = Carbon::parse($request->roaster_date_from);
        $revenues->roaster_date_to = Carbon::parse($request->roaster_date_to);
        // $revenues->shift_start = $request->shift_start;
        // $revenues->shift_end = $request->shift_end;
        $revenues->rate = $request->rate;
        $revenues->hours = $request->hours;
        $revenues->amount = $request->amount;
        $revenues->remarks = $request->remarks;
        $revenues->created_at = Carbon::now();
        $revenues->save();

        // return Redirect()->back();
        return $this->search_module();
    }

    public function update(Request $request)
    {
        $project = Project::find($request->project_name);

        $revenues = Revenue::find($request->id);
        $revenues->client_name = $project->clientName;
        $revenues->project_name = $request->project_name;
        $revenues->payment_date = Carbon::parse($request->payment_date);
        $revenues->roaster_date_from = Carbon::parse($request->roaster_date_from);
        $revenues->roaster_date_to = Carbon::parse($request->roaster_date_to);
        // $revenues->shift_start = $request->shift_start;
        // $revenues->shift_end = $request->shift_end;
        $revenues->rate = $request->rate;
        $revenues->hours = $request->hours;
        $revenues->amount = $request->amount;
        $revenues->remarks = $request->remarks;
        $revenues->updated_at = Carbon::now();
        $revenues->save();

        // return Redirect()->back();
        return $this->search_module();
    }

    public function delete($id)
    {
        $revenues = Revenue::find($id);
        if ($revenues) {
            $revenues->delete();
        }
        // return Redirect()->back();
        return $this->search_module();
    }

    public function search(Request $request)
    {
        $fromRoaster = $request->input('start_date');
        $toRoaster = $request->input('end_date');

        $project_id = $request->input('project_id');
        $client_id = $request->input('client_id');

        Session::put('revenuefromRoaster', $fromRoaster);
        Session::put('revenuetoRoaster', $toRoaster);
        Session::put('revenue_project_id', $project_id);
        Session::put('revenue_client_id', $client_id);

        return $this->search_module();
    }

    public function search_module()
    {
        $filter_roaster_from = Session::get('revenuefromRoaster') ? ['roaster_date_from', '>=', Carbon::parse(Session::get('revenuefromRoaster'))] : ['id', '>', 0];
        $filter_roaster_to = Session::get('revenuetoRoaster') ? ['roaster_date_to', '<=', Carbon::parse(Session::get('revenuetoRoaster'))] : ['id', '>', 0];
        $filter_project = Session::get('revenue_project_id') ? ['project_name', Session::get('revenue_project_id')] : ['id', '>', 0];
        $filter_client = Session::get('revenue_client_id') ? ['client_name', Session::get('revenue_client_id')] : ['id', '>', 0];

        $revenues = Revenue::where('user_id', Auth::id())
            // ->whereBetween('roaster_date', [Carbon::parse(Session::get('revenuefromRoaster')), Carbon::parse(Session::get('revenuetoRoaster'))])
            ->where([
                ['company_code', Auth::user()->company_roles->first()->company->id],
                $filter_roaster_from,
                $filter_roaster_to,
                $filter_project,
                $filter_client
            ])
            ->get();
        $clients = Client::where('company_code', Auth::user()->company_roles->first()->company->id)->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        return view('pages.Admin.revenue.index', compact('revenues', 'clients', 'projects'));
    }
}
