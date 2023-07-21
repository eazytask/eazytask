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

@if(count($timekeepers) >0)
    <div class="card">
        <div class="card-header">
            <h3>View Report</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mt-1">
                    <select class="form-control select2" id="report_type">
                        <option value="">Select Report Type</option>
                        <option value='emloyee_summery'>Employee Wise Summery</option>
                        <option value='emloyee_wise'>Employee Wise Details</option>
                        <option value='client_summery'>Client Wise Summery</option>
                        <option value='client_wise'>Client Wise Details</option>
                    </select>
                </div>
                <div class="col-md-6 mt-1">
                    <button class="btn btn-outline-primary mt-25 ml-25" type="button" onclick="show_data()">Show</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
<div class="col-lg-12 col-md-12 d-block p-0">
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

                <form action="/supervisor/home/all/report/search" method="POST" id="dates_form">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-lg-4 pl-25 pr-25 mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Start Date" value="{{$start_date}}" />
                        </div>
                        <div class="col-lg-4 pl-25 pr-25 mt-1">

                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="End Date" value="{{$end_date}}" />
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="employee_id">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{Session::get('employee_id')==$employee->id ?'selected':''}}>
                                    {{ $employee->fname }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="project_id">
                                <option value="">Select Venue</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{Session::get('project_id')==$project->id ?'selected':''}}>{{ $project->pName }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="client_id">
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{Session::get('client_id')==$client->id ?'selected':''}}>{{ $client->cname }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="payment_status">
                                <option value="">Payment Status</option>
                                <option value='1' {{Session::get('payment_status') == '1' ?'selected':'false'}}>Paid</option>
                                <option value='0' {{Session::get('payment_status') == '0' ?'selected':'false'}}>Pending</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="schedule">
                                <option value="">Select Schedule</option>
                                <option value='Schedueled' {{Session::get('schedule') == 'Schedueled' ?'selected':''}}>Scheduled</option>
                                <option value='Unschedueled' {{Session::get('schedule') == 'Unschedueled' ?'selected':''}}>Unscheduled</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
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
                                        <th>Client</th>
                                        <th>Venue</th>
                                        <th>Employee</th>
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
                                            @if (isset($row->client->cname))
                                            {{ $row->client->cname }}
                                            @else
                                            Null
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($row->project->pName))
                                            {{ $row->project->pName }}
                                            @else
                                            Null
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->employee->fname }} {{ $row->employee->mname }} {{ $row->employee->lname }}

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
        let report_type = $('#report_type').val()

        switch (report_type) {
            case "emloyee_summery":
                emloyee_summery()
                break;
            case "emloyee_wise":
                emloyee_wise()
                break;
            case "client_wise":
                client_wise()
                break;
            case "client_summery":
                client_summery()
                break;
        }
    }
    emloyee_summery = function() {
        const element = document.getElementById('employee_summery_pdf').innerHTML;
        var opt = {
            filename: 'employee-summery.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'tabloid',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save();
    }
    emloyee_wise = function() {
        const element = document.getElementById('employee_pdf').innerHTML;
        var opt = {
            filename: 'employee.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'tabloid',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save();
    }
    client_wise = function() {
        const element = document.getElementById('client_pdf').innerHTML;
        var opt = {
            filename: 'client.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'tabloid',
                orientation: 'portrait'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
    client_summery = function() {
        const element = document.getElementById('client_summery_pdf').innerHTML;
        var opt = {
            filename: 'client-summery.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'tabloid',
                orientation: 'portrait'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
@endsection

@section('pdf_generator')
<div class="d-none d-print-block" id="employee_summery_pdf">
    @php
    $all_roaster= Session::get('employee_wise_summery') ? Session::get('employee_wise_summery'): [];
    @endphp
    @include('pages.Admin.pdf.employee_wise_summery')
</div>

<div class="d-none d-print-block" id="employee_pdf">
    @php
    $all_roaster= Session::get('employee_wise_report') ? Session::get('employee_wise_report'): [];
    @endphp
    @include('pages.Admin.pdf.employee_wise_html')
</div>

<div class="d-none d-print-block" id="client_pdf">
    @php
    $all_roaster= Session::get('client_wise_report') ? Session::get('client_wise_report'): [];
    @endphp
    @include('pages.Admin.pdf.client_wise_html')
</div>

<div class="d-none d-print-block" id="client_summery_pdf">
    @php
    $all_roaster= Session::get('client_wise_summery') ? Session::get('client_wise_summery'): [];
    @endphp
    @include('pages.Admin.pdf.client_wise_summery')
</div>
@endsection