<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\UserPaymentInvoice;
use App\Models\Employee;
use App\Models\paymentdetails;
use App\Models\paymentmaster;
use App\Models\TimeKeeper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PaymentListcontroller extends Controller
{
    public function index()
    {
        $employees = Employee::where([
            ['company',Auth::user()->company_roles->first()->company->id],
            ['role',3],
            ['status', 1]
        ])->orderBy('fname','asc')->get();
        return view('pages.Admin.payment_list.index',compact('employees'));
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
                ['user_id',Auth::id()],
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
                    '<td><a class="btn btn-gradient-primary" href="/admin/home/payment/list/'.$payment->id.'" target="_blank"><i data-feather="eye"></i></a></td>' .
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
            return view('pages.Admin.payment_list.modals.view',compact('payment','timekeepers'));
        }else{
            return redirect()->back();
        }
    }

    public function download($id){
        $payment = paymentmaster::find($id);
        if($payment){
            $admin = User::find($payment->User_ID);
            $timekeepers = TimeKeeper::whereIn('id',unserialize($payment->details->timekeeper_ids))->get();
            return view('pages.Admin.pdf.user_invoice',compact('payment','timekeepers','admin'));
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
