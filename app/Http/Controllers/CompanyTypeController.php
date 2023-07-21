<?php

namespace App\Http\Controllers;

use App\Models\CompanyType;
use Illuminate\Http\Request;
use Auth;

class CompanyTypeController extends Controller
{
    public function index()
    {
        // return 555;
        $data= CompanyType::get();;
        return view("pages.SuperAdmin.company_type.index",compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        ]);

        $single = new CompanyType;
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

        $single = CompanyType::find($request->id);
        if($single){

        $single->name= $request->name;
        $single->remarks= $request->remarks;
        $single->save();
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $single = CompanyType::find($id);
        if($single){
            $single->delete();
        }
        return redirect()->back();
    }
}
