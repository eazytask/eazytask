<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Project;
use Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Project View File
    public function index($id)
    {
        $clients = Client::where('company_code', Auth::user()->company_roles->first()->company->id)->where('status', '1')->orderBy('cname', 'asc')->get();
        $projects = Project::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('pName', 'asc')->get();

        return view('pages.Admin.project.index', compact('clients'));
    }
    public function fetch()
    {
        $projects = Project::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('pName', 'asc')->get();

        $html = '';
        foreach ($projects as $loop => $project) {
            if ($project->Status == 1) {
                $status = "<span class='badge badge-pill badge-light-success mr-1'>Active</span>";
            } else {
                $status = "<span class='badge badge-pill badge-light-danger mr-1'>Inactive</span>";
            }

            if ($project->client->cname) {
                $cn = $project->client->cname;
            } else {
                $cn = 'not available';
            }
            $json = json_encode($project->toArray(), false);
            $html .= "<tr>
            <td>" . $loop + 1 . "</td>
            <td>$project->pName</td>
            <td>$project->cName</td>
            <td>$project->cNumber</td>
            <td>$status</td>
            <td>
            $cn</td>
            <td>
        <button class='edit-btn btn btn-gradient-primary mb-25' data-row='$json'><i data-feather='edit'></i></button>
                <a class='btn btn-gradient-danger text-white del'  data-id='$project->id'><i
                        data-feather='trash-2'></i></a>
            </td>
        </tr>";
        }
        return response()->json(['data' => $html]);
    }
    public function store(Request $request)
    {
        $project = new Project();
        $project->user_id = Auth::id();
        $project->pName = $request->pName;
        $project->cName = $request->cName;
        $project->Status = $request->Status;
        $project->cNumber = $request->cNumber;
        $project->suburb = $request->suburb;
        $project->project_address = $request->project_address;
        // $project->project_venue = $request->project_venue;
        $project->postal_code = $request->postal_code;
        $project->project_state = $request->project_state;
        $project->lat = $request->lat;
        $project->lon = $request->lon;
        $project->company_code = Auth::user()->company_roles->first()->company->id;
        $project->clientName = $request->clientName;

        $project->save();
        return response()->json([
            'message' => 'Project Added Successfully.',
            'alertType' => 'success'
        ]);
    }
    public function update(Request $request)
    {
        // return $request;
        $project = Project::find($request->id);

        $project->user_id = Auth::id();
        $project->pName = $request->pName;
        $project->cName = $request->cName;
        $project->Status = $request->Status;
        $project->cNumber = $request->cNumber;
        $project->suburb = $request->suburb;
        $project->project_address = $request->project_address;
        // $project->project_venue = $request->project_venue;
        $project->postal_code = $request->postal_code;
        $project->project_state = $request->project_state;
        $project->clientName = $request->clientName;
        $project->lat = $request->lat;
        $project->lon = $request->lon;

        $project->save();
        return response()->json([
            'message' => 'Project Updated Successfully.',
            'alertType' => 'success'
        ]);
    }
    public function delete($id)
    {
        try {
            $project = Project::find($id);

            $project->delete();

            return response()->json([
                'message' => 'Project Deleted Successfully.',
                'alertType' => 'success'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // var_dump($e->errorInfo);
            return response()->json([
                'message' => 'Sorry! This venue used somewhere.',
                'alertType' => 'warning'
            ]);
        }
    }
}
