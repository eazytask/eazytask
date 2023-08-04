@extends('layouts.Admin.master')
@push('styles')
<meta name="_token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
@endpush

@php
$total_durations=0;
$total_amount = 0;

function getTime($date){
return \Carbon\Carbon::parse($date)->format('H:i');
}

$start_date= null;
$end_date= null;
if(Session::get('fromDate') && Session::get('toDate')){
$start_date = Session::get('fromDate')->format('d-m-Y');
$end_date = Session::get('toDate')->format('d-m-Y');
}
@endphp

@section('admincontent')

<div class="col-lg-12 col-md-12 p-0">
    @if(count($payments) >0)
    <div class="card">
        <div class="card-header">
            <h3>View Report</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mt-1">
                    <button class="btn btn-outline-primary mt-25 ml-25" type="button" onclick="show_data()">Show Report</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card p-0">
        <div class="container">
            <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
                <h3 class="card-title text-primary d-inline">
                    Select Venue Dates
                </h3>
                <span class="float-right">
                    <i class="fa fa-chevron-up clickable"></i>
                </span>
            </div>
            <div class="card-body">

                <form action="/home/payment/report/search" method="post">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-md-5 col-lg-4  mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Start Date" value="{{$start_date}}" />
                        </div>
                        <div class="col-md-5 col-lg-4  mt-1">

                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="End Date" value="{{$end_date}}" />
                        </div>

                        <div class="col-md-2 col-lg-3 mt-1">
                            <button type="submit" class="btn btn btn-outline-primary btn-block" id="btn_search"><i data-feather="search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="card">
    <div class="row" id="table-hover-animation">
            <div class="col-12">
                <div class="card">
                    <div class="container pt-2">
                        <div class="table-responsive">
                            <table id="example" class="table table-hover-animation table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Issue Date</th>
                                        <th>Total Hours</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $k => $payment)
                                    @php
                                    $total_durations += $payment->details->total_hours;
                                    $total_amount += $payment->details->total_pay;
                                    @endphp
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d-m-Y')}}</td>
                                        <td>{{$payment->details->total_hours}}</td>
                                        <td>{{ $payment->details->total_pay }}</td>
                                        <td><a class="edit-btn btn-link btn" href="/home/payment/report/{{$payment->id}}" target="_blank"><i data-feather="eye"></i></a></td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<script>
    show_data = function() {
        const element = document.getElementById('htmlContent').innerHTML;
        var opt = {
            filename: 'payments.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>
@endsection

@section('pdf_generator')
@if(count($payments)>0)
<div id="htmlContent" class="d-none d-print-block">
    <div class="row">
        <div class="col-12">
            <div class="card card-browser-states border-primary m-2 text-center">
                <div class="card-header p-75">
                    <div class="row pb-1 pt-1 ml-1 mr-1 bg-primary" style="width: 100%;">
                        <div class="col-4 ">
                            <p class="h6 text-light" style="color: white;">Payment Report</p>
                        </div>
                        <div class="col-4 ">
                            <p class="h6 text-light">Total Hours: {{$total_durations}}</p>
                        </div>
                        <div class="col-4 ">
                            <p class="h6 text-light">Total Amount: ${{$total_amount}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                            <table id="example" class="table table-hover-animation table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Issue Date</th>
                                        <th>Total Hours</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $k => $payment)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d-m-Y')}}</td>
                                        <td>{{$payment->details->total_hours}}</td>
                                        <td>{{ $payment->details->total_pay }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection