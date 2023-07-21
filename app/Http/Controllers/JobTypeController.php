<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use Illuminate\Http\Request;
use Auth;

class JobTypeController extends Controller
{
    public function index()
    {
        // return 555;
        $data= JobType::where('company_code', Auth::user()->company_roles->first()->company->id)->get();
        return view("pages.Admin.job_type.index",compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        ]);

        $single = new JobType;
        $single->name= $request->name;
        $single->remarks= $request->remarks;
        $single->user_id = Auth::id();
        $single->company_code = Auth::user()->company_roles->first()->company->id;
        $single->save();
        
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        ]);

        $single = JobType::find($request->id);
        if($single){

        $single->name= $request->name;
        $single->remarks= $request->remarks;
        $single->user_id = Auth::id();
        $single->company_code = Auth::user()->company_roles->first()->company->id;

        $single->save();
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $single = JobType::find($id);
            if($single){
                $single->delete();
            }
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            // var_dump($e->errorInfo);
            return back()->with([
                'message' => 'Sorry! This roster-status used somewhere.',
                'alert-type' => 'warning'
            ]);
        }
    }
    
}
