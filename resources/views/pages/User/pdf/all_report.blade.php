@extends('layouts.Admin.master')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endpush

@php

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
<style>
    .dt-buttons {
        display: none !important;
    }
</style>

<div class="col-lg-12 col-md-12 d-block p-0">
    
@if(count($timekeepers) >0)
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
                    Roster Dates
                </h3>
                <span class="float-right">
                    <i class="fa fa-chevron-up clickable"></i>
                </span>
            </div>
            <div class="card-body">

                <form action="/home/all/report/search" method="POST" id="dates_form">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-md-5 col-lg-4  mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Start Date" value="{{$start_date}}" />
                        </div>
                        <div class="col-md-5 col-lg-4  mt-1">

                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="End Date" value="{{$end_date}}" />
                        </div>
                        <div class="col-md-5 col-lg-4 mt-1">
                            <select class="form-control select2" name="project_id">
                                <option value="">Select Venue</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{Session::get('project_id')==$project->id ?'selected':''}}>{{ $project->pName }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 col-lg-4 mt-1">
                            <select class="form-control select2" name="payment_status">
                                <option value="">Payment Status</option>
                                <option value='1' {{Session::get('payment_status') == '1' ?'selected':'false'}}>Paid</option>
                                <option value='0' {{Session::get('payment_status') == '0' ?'selected':'false'}}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-5 col-lg-4 mt-1">
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
                    <div class="container">
                        <div class="table-responsive">
                            <table id="example" class="table table-hover-animation table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Venue</th>
                                        <th>Roster Date</th>
                                        <th>Shift Start</th>
                                        <th>Shift Etart</th>
                                        <th>Duration</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timekeepers as $k => $row)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>
                                            @if (isset($row->project->pName))
                                            {{ $row->project->pName }}
                                            @else
                                            Null
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                        </td>
                                        <td>{{ getTime($row->shift_start) }}</td>
                                        <td>{{ getTime($row->shift_end) }}</td>
                                        <td>{{ $row->duration }}</td>
                                        <td>{{ $row->ratePerHour }}</td>
                                        <td>{{ $row->amount }}</td>
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
            filename: 'roasters.pdf',
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
@if(count($timekeepers)>0)
<div id="htmlContent" class="d-none d-print-block">
    <div class="row">
        <div class="col-12">
            <div class="card card-browser-states border-primary m-2 text-center">
                <div class="card-header p-75">
                    <div class="row pb-1 pt-1 ml-1 mr-1 bg-primary" style="width: 100%;">
                        <div class="col-4 ">
                            <p class="h6 text-light" style="color: white;">Roster Report</p>
                        </div>
                        <div class="col-4 ">
                            <p class="h6 text-light">Total Hours: {{$timekeepers->sum('hours')}}</p>
                        </div>
                        <div class="col-4 ">
                            <p class="h6 text-light">Total Amount: ${{$timekeepers->sum('amount')}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped">

                            <tbody>
                                <tr class="">
                                    <th>#</th>
                                    <th>Venue</th>
                                    <th>Roster Date</th>
                                    <th>Shift Start</th>
                                    <th>Shift Etart</th>
                                    <th>Duration</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                                @foreach ($timekeepers as $k => $row)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>
                                        @if (isset($row->project->pName))
                                        {{ $row->project->pName }}
                                        @else
                                        Null
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                    </td>
                                    <td>{{ getTime($row->shift_start) }}</td>
                                    <td>{{ getTime($row->shift_end) }}</td>
                                    <td>{{ $row->duration }}</td>
                                    <td>{{ $row->ratePerHour }}</td>
                                    <td>{{ $row->amount }}</td>
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