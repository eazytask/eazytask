<?php

namespace App\Http\Controllers;

use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Auth;

class PaymentStatusController extends Controller
{
    public function index()
    {
        // return 555;
        $data= PaymentStatus::where('user_id', Auth::id())->get();
        return view("pages.Admin.payment_status.index",compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        ]);

        $single = new PaymentStatus;
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

        $single = PaymentStatus::find($request->id);
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
        $single = PaymentStatus::find($id);
        if($single){
            $single->delete();
        }
        return redirect()->back();
    }
}
