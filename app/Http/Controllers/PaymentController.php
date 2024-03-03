<?php

namespace App\Http\Controllers;

use App\Models\paymentmaster;
use App\Models\paymentdetails;
use App\Models\TimeKeeper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Controllers\admin\PDFGeneratorController;
use App\Models\Employee;
use App\Models\Project;
use Session;

class PaymentController extends Controller
{
    public function index($id)
    {
        if ($id == 'search-again') {
            return $this->search_module();
        }
        Session::put('payment_project_id', null);
        Session::put('payment_employee_id', null);
        Session::put('fromPayment', null);
        Session::put('toPayment', null);

        $employees = Employee::where([
            ['company', Auth::user()->company_roles->first()->company->id],
            ['role', 3],
            ['status', '1']
        ])->orderBy('fname', 'asc')->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        $timekeepers = [];
        return view('pages.Admin.payment.index', compact('timekeepers', 'projects', 'employees'));
    }

    public function search(Request $request)
    {
        $fromRoaster = $request->input('start_date');
        $toRoaster = $request->input('end_date');

        $project_id = $request->input('project_id');
        $employee_id = $request->input('employee_id');

        Session::put('fromPayment', Carbon::parse($fromRoaster));
        Session::put('toPayment', Carbon::parse($toRoaster));
        Session::put('payment_project_id', $project_id);
        Session::put('payment_employee_id', $employee_id);

        return $this->search_module();
    }

    public function search_module($request = null)
    {
        if (!$request) {
            $req = [
                'start_date' => Session::get('fromPayment'),
                'end_date' => Session::get('toPayment'),
                'project_id' => Session::get('payment_project_id'),
                'client_id' => '',
                'employee_id' => Session::get('payment_employee_id'),
                'roaster_status' => '',
                'payment_status' => 0,
                'schedule' => ''
            ];
            $request = (object) $req;
        }
        $pdf_generator = new PDFGeneratorController;
        $employee_wise_report = $pdf_generator->employee_wise($request);
        $client_wise_report = $pdf_generator->client_wise($request);

        Session::put('employee_wise_report', $employee_wise_report);
        Session::put('client_wise_report', $client_wise_report);

        $filter_project = Session::get('payment_project_id') ? ['project_id', Session::get('payment_project_id')] : ['employee_id', '>', 0];
        $filter_employee = Session::get('payment_employee_id') ? ['employee_id', Session::get('payment_employee_id')] : ['employee_id', '>', 0];

        $timekeepers = DB::table('time_keepers')
            ->select(DB::raw(
                '*',
                'e.* ,
                e.fname as name,
                sum(time_keepers.duration) as total_hours,
                sum(time_keepers.amount) as total_amount ,
                count(time_keepers.id) as record'

            ))
            ->leftJoin('employees as e', 'e.id', 'time_keepers.employee_id')
            ->where([
                ['e.company', Auth::user()->company_roles->first()->company->id],
                ['e.role', 3]
            ])
            ->groupBy("e.id")
            ->orderBy('e.fname', 'asc')
            ->whereBetween('roaster_date', [Carbon::parse(Session::get('fromPayment')), Carbon::parse(Session::get('toPayment'))])
            ->where([
                ['payment_status', 0],
                // ['sing_out','!=',null],
                $filter_project,
                $filter_employee
            ])
            ->where(function ($q) {
                avoid_rejected_key($q);
            })
            ->orderBy("e.fname", 'ASC')
            ->get();
        // return $timekeepers;
        $employees = Employee::where([
            ['company', Auth::user()->company_roles->first()->company->id],
            ['role', 3],
            ['status', '1']
        ])->orderBy('fname', 'asc')->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();

        return view('pages.Admin.payment.index', compact('timekeepers', 'projects', 'employees'));
    }

    public function addpayment(Request $request)
    {
        $filter_project = Session::get('payment_project_id') ? ['project_id', Session::get('payment_project_id')] : ['employee_id', '>', 0];

        $timekeeperData = TimeKeeper::where('employee_id', $request->id)
            ->where([
                ['payment_status', '=', 0],
                // ['sing_out','!=',null],
                $filter_project,
            ])
            ->where(function ($q) {
                avoid_rejected_key($q);
            })
            ->whereBetween('roaster_date', [Carbon::parse(Session::get('fromPayment')), Carbon::parse(Session::get('toPayment'))])
            ->orderBy('roaster_date','asc')
            ->get();

        $employee = DB::table('employees')->where('id', '=', $request->id)->get();
        foreach ($employee as $employee) {
            $fname = $employee->fname;
            $lname = $employee->lname;
            $empname = $fname . $lname;
            $id = $employee->id;

            $employee = $employee;
        }
        $sdate = Session::get('fromPayment');
        $edate = Session::get('toPayment');

        return view('pages.Admin.payment.modals.add_payment', compact('employee', 'empname', 'id', 'sdate', 'edate'))->with('timekeeperData', $timekeeperData);
        //print_r($timekeeperData);

    }

    public function storepaymentdetails(Request $request)
    {
        // return $request;
        $timekeeper_ids = $request->timekeeper_ids;
        $timekeeper_ids = explode(',', $timekeeper_ids);

        $comment = $request->comment;
        $paydate = Carbon::parse($request->pay_date);

        $paymentmaster = paymentmaster::create([
            'Payment_Date' => $paydate,
            'User_ID' => Auth::id(),
            'employee_id' => $request->employee_id,
            'Company_Code' => Auth::user()->company_roles->first()->company->id,
            'Comments' => $comment,
            'ExtraDsscription' => '',
            'created_at' => Carbon::now()

        ]);

        // foreach ($timekeeper_ids as $id) {
        //     $timekeeper = TimeKeeper::find((int)$id);
        //     $timekeeper->payment_status = 1;
        //     $timekeeper->save();
        // }
        TimeKeeper::whereIn('id', $timekeeper_ids)->update(['payment_status' => 1, 'is_approved'=>1]);

        $paymentdetails = new paymentdetails;
        $paymentdetails->payment_master_id  = $paymentmaster->id;
        $paymentdetails->timekeeper_ids = serialize($timekeeper_ids);
        $paymentdetails->additional_pay = $request->additional_pay ? $request->additional_pay : 0;
        $paymentdetails->total_pay = $request->amount;
        $paymentdetails->total_hours = $request->duration;
        $paymentdetails->Remarks = $request->comment;
        $paymentdetails->PaymentMethod = $request->payment_method;
        $paymentdetails->created_at =  Carbon::now();

        $paymentdetails->save();


        return redirect('admin/home/payment/search-again');
    }

    //function for updating approved start and end
    public function DateUpdate(Request $request)
    {
        $id = $request->id;
        $approved_start = $request->start_date;
        // $approved_end = $request->end_date;
        $rate = $request->rate;
        $duration = $request->duration;
        $len = count($id);
        $amount = $request->amount;
        for ($i = 0; $i < $len; $i++) {
            $timekeeper = TimeKeeper::find($id[$i]);

            $roaster_date =  Carbon::parse($timekeeper->roaster_date)->format('d-m-Y');

            $shift_start = Carbon::parse($roaster_date . $approved_start[$i]);
            // $shift_end = Carbon::parse($roaster_date . $approved_end[$i]);
            $shift_end = Carbon::parse($shift_start)->addMinute($duration[$i] * 60);

            $timekeeper->Approved_start_datetime = $shift_start;
            $timekeeper->Approved_end_datetime = $shift_end;
            $timekeeper->app_rate = $rate[$i];
            $timekeeper->app_duration = $duration[$i];
            $timekeeper->app_amount = $amount[$i];
            $timekeeper->is_approved = 1;

            $timekeeper->save();
        }

        return $this->search_module();
    }
}
