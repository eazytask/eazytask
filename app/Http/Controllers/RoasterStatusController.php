<?php

namespace App\Http\Controllers;

use App\Models\RoasterStatus;
use Illuminate\Http\Request;
use Auth;

class RoasterStatusController extends Controller
{
    public function index()
    {
        // return 555;
        $data= RoasterStatus::where('company_code', Auth::user()->company_roles->first()->company->id)->get();
        return view("pages.Admin.roaster_status.index",compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        ]);

        $single = new RoasterStatus;
        $single->name= $request->name;
        $single->remarks= $request->remarks;
        $single->color= $request->color;
        $single->user_id = Auth::id();
        $single->optional = 1;
        $single->company_code = Auth::user()->company_roles->first()->company->id;

        $single->save();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        // $validated = $request->validate([
        // 'name' => 'required',
        // ]);
        $single = RoasterStatus::find($request->id);
        if($single){
        if($request->name)
            $single->name= $request->name;
        $single->color= $request->color;
        $single->remarks= $request->remarks;
        $single->user_id = Auth::id();
        $single->company_code = Auth::user()->company_roles->first()->company->id;

        $single->update();
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $single = RoasterStatus::find($id);
            if($single){
                $single->delete();
            }
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            // var_dump($e->errorInfo);
            return back()->with([
                'message' => 'Sorry! This roster-status used somewhere.',
                'alertType' => 'warning'
            ]);
        }
    }
}
