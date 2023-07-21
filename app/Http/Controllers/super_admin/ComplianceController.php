<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Compliance;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    public function index()
    {
        // return 555;
        $data= Compliance::all();
        return view("pages.SuperAdmin.Compliance.index",compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        ]);

        $single = new Compliance;
        $single->name= $request->name;
        $single->remarks= $request->remarks;
        $single->save();
        
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        ]);

        $single = Compliance::find($request->id);
        if($single){
        $single->name= $request->name;
        $single->remarks= $request->remarks;

        $single->save();
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $single = Compliance::find($id);
        if($single){
            $single->delete();
        }
        return redirect()->back();
    }
}
