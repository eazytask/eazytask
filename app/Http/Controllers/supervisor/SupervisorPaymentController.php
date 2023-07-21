<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\admin\PDFGeneratorController;
use App\Http\Controllers\Controller;
use App\Mail\UserPaymentInvoice;
use App\Models\Client;
use App\Models\Employee;
use App\Models\paymentdetails;
use App\Models\paymentmaster;
use App\Models\Project;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;

class SupervisorPaymentController extends Controller
{
    public function payment_index($id){
        if($id == 'search-again'){
            return $this->search_module();
        }
        Session::put('payment_project_id', null);
        Session::put('payment_employee_id', null);
        Session::put('fromPayment', null);
        Session::put('toPayment', null);

        $employees = Employee::where([
            ['company', Auth::user()->supervisor->company],
            ['role',3],
            ['status',1]
        ])->orderBy('fname','asc')->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        $timekeepers = [];
        return view('pages.supervisor.payment.index', compact('timekeepers','projects', 'employees'));
    }
    public function payment_search(Request $request)
    {
        $fromRoaster = $request->input('start_date');
        $toRoaster = $request->input('end_date');

        $project_id = $request->input('project_id');
        $client_id = $request->input('client_id');
        $employee_id = $request->input('employee_id');

        Session::put('fromPayment', Carbon::parse($fromRoaster));
        Session::put('toPayment', Carbon::parse($toRoaster));
        Session::put('payment_project_id', $project_id);
        Session::put('payment_employee_id', $employee_id);

        return $this->search_module();
    }

    public function DateUpdate(Request $request)
    { 
         $id=$request->id;
         $approved_start=$request->start_date;
         $approved_end=$request->end_date;
         $rate=$request->rate;
         $duration=$request->duration;
         $len=count($id);
         $amount=$request->amount;
         for($i=0;$i<$len;$i++)
         {
            $timekeeper = TimeKeeper::find($id[$i]);

            
        $roaster_date=  Carbon::parse($timekeeper->roaster_date)->format('d-m-Y');

        $shift_start= Carbon::parse($roaster_date. $approved_start[$i]);
        $shift_end= Carbon::parse($roaster_date. $approved_end[$i]);

            $timekeeper->Approved_start_datetime = $shift_start;
            $timekeeper->Approved_end_datetime= $shift_end;
            $timekeeper->ratePerHour = $rate[$i];
            $timekeeper->duration=$duration[$i];
            $timekeeper->amount=$amount[$i];
            $timekeeper->save();
         }
         
        return $this->search_module();
    }

    public function addpayment(Request $request)
    {
        $filter_project = Session::get('payment_project_id') ? ['project_id', Session::get('payment_project_id')] : ['employee_id', '>', 0];
        $filter_client = Session::get('payment_client_id') ? ['client_id', Session::get('payment_client_id')] : ['employee_id', '>', 0];

        $timekeeperData = TimeKeeper::where('employee_id', $request->id)
            ->where([
                ['payment_status', '=', 0],
                ['company_code', Auth::user()->supervisor->company],
                // ['sing_out','!=',null],
                $filter_project,
                $filter_client
            ])
            ->where(function ($q) {
                avoid_rejected_key($q);
            })
            ->whereBetween('roaster_date', [Carbon::parse(Session::get('fromPayment')), Carbon::parse(Session::get('toPayment'))])
            ->get();

        $employee = DB::table('employees')->where('id', '=', $request->id)->get();
        foreach ($employee as $employee) {
            $fname = $employee->fname;
            $lname = $employee->lname;
            $empname = $fname . $lname;
            $id = $employee->id;
            
            $employee= $employee;
        }
        $sdate = Session::get('fromPayment');
        $edate = Session::get('toPayment');
        return view('pages.supervisor.payment.modals.add_payment', compact('employee','empname', 'id', 'sdate', 'edate'))->with('timekeeperData', $timekeeperData);
        //print_r($timekeeperData);
    }
    public function storepaymentdetails(Request $request)
    {
        $timekeeper_ids= $request->timekeeper_ids;
        $timekeeper_ids= explode(',',$timekeeper_ids);
        
        $comment = $request->comment;
        $paydate = Carbon::parse($request->pay_date);

        $paymentmaster = paymentmaster::create([
            'Payment_Date' => $paydate,
            'User_ID' => Auth::user()->supervisor->user_id,
            'employee_id' => $request->employee_id,
            'Company_Code' => Auth::user()->supervisor->company,
            'Comments' => $comment,
            'ExtraDsscription' => '',
            'created_at' => Carbon::now()

        ]);


        foreach($timekeeper_ids as $id){
            $timekeeper = TimeKeeper::find((int)$id);
            $timekeeper->payment_status = 1;
            $timekeeper->save();
        }


        $paymentdetails = new paymentdetails;
        $paymentdetails->payment_master_id  = $paymentmaster->id;
        $paymentdetails->timekeeper_ids = serialize($timekeeper_ids);
        $paymentdetails->additional_pay = $request->additional_pay ?$request->additional_pay:0;
        $paymentdetails->total_pay = $request->amount;
        $paymentdetails->total_hours = $request->duration;
        $paymentdetails->Remarks = $request->comment;
        $paymentdetails->PaymentMethod = $request->payment_method;
        $paymentdetails->created_at =  Carbon::now();

        $paymentdetails->save();

        return redirect('supervisor/home/payment/search-again');

    }

    public function search_module($request=null)
    {
        if(!$request){
            $req = [
                'start_date'=>Session::get('fromPayment'),
                'end_date'=>Session::get('toPayment'),
                'project_id'=>Session::get('payment_project_id'),
                'client_id'=>'',
                'employee_id'=>Session::get('payment_employee_id'),
                'roaster_status'=>'',
                'payment_status'=>0,
                'schedule'=>''
            ];
            $request = (object) $req;
        }
        $pdf_generator = new PDFReportController;
        $employee_wise_report = $pdf_generator->employee_wise($request);
        $client_wise_report = $pdf_generator->client_wise($request);

        Session::put('employee_wise_report',$employee_wise_report);
        Session::put('client_wise_report',$client_wise_report);

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
            ->where('e.user_id', Auth::user()->supervisor->user_id)
            ->groupBy("e.id")
            ->orderBy('e.fname','asc')
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
            ->get();
        // return $timekeepers;
        $clients = Client::where('company_code', Auth::user()->supervisor->company)->get();
        $projects = Project::whereHas('client', function ($query) {
            $query->where('status', 1);
        })->where([
            ['company_code', Auth::user()->company_roles->first()->company->id],
            ['Status', '1'],
        ])->orderBy('pName', 'asc')->get();
        $employees = Employee::where([
            ['company', Auth::user()->supervisor->company],
            ['role',3],
            ['status',1]
        ])->get();

        return view('pages.supervisor.payment.index', compact('timekeepers', 'clients', 'projects', 'employees'));
    }

    
    public function index()
    {
        // return Auth::user()->supervisor->admin;
        $employees = Employee::where([
            ['company', Auth::user()->supervisor->company],
            ['role',3],
            ['status',1]
        ])->get();
        return view('pages.supervisor.payment_list.index',compact('employees'));
    }
    public function search(Request $request)
    {
        $start_week = Carbon::now()->startOfWeek();
        $end_week = Carbon::now()->endOfWeek();

        $start_date = $request->start_date ? Carbon::parse($request->start_date):$start_week;
        $end_date = $request->end_date ? Carbon::parse($request->end_date):$end_week;

        $filter_employee = $request->employee_id ? ['employee_id',$request->employee_id]:['employee_id','>',0];

        $output = "";
        $payments = paymentmaster::whereBetween('Payment_Date', [$start_date,$end_date])
            ->where([
                ['user_id',Auth::user()->supervisor->user_id],
                $filter_employee
            ])
            ->orderBy('Payment_Date','desc')
            ->get();
        if ($payments) {
            $totalTaskId=[];
            foreach ($payments as $payment) {
                array_push($totalTaskId,$payment->id);
                $output .= '<tr>' .
                    '<td><input type="checkbox" class="taskCheckID" value="' . $payment->id . '"></td>' .
                    '<td>' . $payment->employee->fname . '</td>' .
                    '<td>' .Carbon::parse($payment->Payment_Date)->format('d-m-Y').'</td>' .
                    '<td>' .$payment->details->total_hours.'</td>' .
                    '<td>' .$payment->details->total_pay.'</td>' .
                    '<td><a class="edit-btn btn-link btn" href="/supervisor/home/payment/list/'.$payment->id.'" target="_blank"><i data-feather="eye"></i></a></td>' .
                    '</tr>';
            }

            return Response()->json([
                'data'=>$output,
                'totalTaskId'=>$totalTaskId,
            ]);
        }
        // }
    }

    public function view($id){
        $payment = paymentmaster::find($id);
        if($payment){
            $timekeepers = TimeKeeper::whereIn('id',unserialize($payment->details->timekeeper_ids))->get();
            return view('pages.supervisor.payment_list.modals.view',compact('payment','timekeepers'));
        }else{
            return redirect()->back();
        }
    }

    public function download($id){
        $payment = paymentmaster::find($id);
        if($payment){
            $timekeepers = TimeKeeper::whereIn('id',unserialize($payment->details->timekeeper_ids))->get();
            return view('pages.Admin.pdf.user_invoice',compact('payment','timekeepers'));
        }else{
            return redirect('/');
        }
    }

    public function invoice_send(Request $request){
        foreach($request->ids as $id){
            $payment = paymentmaster::find($id);
            Mail::to($payment->employee->email)->send(new UserPaymentInvoice($payment->employee,$payment->id));
        }
        return response()->json(['status'=>'success']);
    }
}
