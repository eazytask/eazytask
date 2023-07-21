<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;
use Auth;
use App\Models\Inductedsite;
use Carbon\Carbon;

class InductedsiteController extends Controller
{
    public function index($id)
    {
        $employees = Employee::where([
            ['company', Auth::user()->company_roles->first()->company->id],
            ['role', 3],
            ['status', 1]
        ])
        ->orderBy('fname', 'asc')->get();

        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        $inductions = Inductedsite::where([
            ['company_code', Auth::user()->company_roles->first()->company->id]
        ])->orderBy('employee_id', 'asc')->get();
        return view('pages.Admin.inducted_site.index', compact('inductions', 'projects', 'employees'));
    }

    public function store(Request $request)
    {
        $already_has = Inductedsite::where([
            ['employee_id', $request->employee_id],
            ['project_id', $request->project_id],
            ['company_code', Auth::user()->company_roles->first()->company->id]
        ])->first();

        if ($already_has) {
            $inductedsites = $already_has;
        } else {
            $project = Project::find($request->project_id);

            $inductedsites = new Inductedsite();
            $inductedsites->user_id = Auth::id();
            $inductedsites->company_code = Auth::user()->company_roles->first()->company->id;
            $inductedsites->employee_id = $request->employee_id;
            $inductedsites->client_id = $project->clientName;
            $inductedsites->project_id = $request->project_id;
        }

        $inductedsites->induction_date = Carbon::parse($request->induction_date);
        $inductedsites->remarks = $request->remarks;
        $inductedsites->save();

        return Redirect()->back();
    }

    public function update(Request $request)
    {
        // return Carbon::parse($request->induction_date);
        $already_has = Inductedsite::where([
            ['employee_id', $request->employee_id],
            ['project_id', $request->project_id],
            ['company_code', Auth::user()->company_roles->first()->company->id]
        ])->first();

        if ($already_has) {
            if ($already_has->id == $request->id) {
                $inductedsites = $already_has;
            } else {
                $notification = array(
                    'message' => 'this inductiction already exist!',
                    'alert-type' => 'warning'
                );
                return Redirect()->back()->with($notification);
            }
        } else {
            $project = Project::find($request->project_id);

            $inductedsites = Inductedsite::find($request->id);
            $inductedsites->employee_id = $request->employee_id;
            $inductedsites->client_id = $project->clientName;
            $inductedsites->project_id = $request->project_id;
        }

        $inductedsites->induction_date = Carbon::parse($request->induction_date);
        $inductedsites->remarks = $request->remarks;
        $inductedsites->save();

        $notification = array(
            'message' => 'Inductiction updated',
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    public function delete($id)
    {
        $inductedsites = Inductedsite::find($id);
        $inductedsites->delete();
        return Redirect()->back();
    }
}
