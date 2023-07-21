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

<div class="col-lg-12 col-md-12 d-block">
    <div class="card p-0">
        <div class="container">
            <div class="card-header">
                <h3 class="card-title text-primary d-inline mb-2">
                    Last Week Report
                </h3>
                <span class="mb-2 font-small-2">{{\Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y')}}</span>

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
    </div>
    <div class="card p-0">
        <div class="container">
            <div class="card-header">
                <h3 class="card-title text-primary d-inline mb-2">
                    Last Month Report
                </h3>

            </div>
            
            <div class="card-body">
                
            <div class="row">
                    <div class="col-md-6 mt-1">
                        <select class="form-control select2" id="month_report_type">
                            <option value="">Select Report Type</option>
                            <option value='month_emloyee_summery'>Employee Wise Summery</option>
                            <option value='month_emloyee_wise'>Employee Wise Details</option>
                            <option value='month_client_summery'>Client Wise Summery</option>
                            <option value='month_client_wise'>Client Wise Details</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-1">
                        <button class="btn btn-outline-primary mt-25 ml-25" type="button" onclick="month_show_data()">Show</button>
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
    client_summery = function() {
        const element = document.getElementById('client_summery_pdf').innerHTML;
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

    // last month report 
    month_show_data = function() {
        let report_type = $('#month_report_type').val()
        switch (report_type) {
            case "month_emloyee_summery":
                month_emloyee_summery()
                break;
            case "month_emloyee_wise":
                month_emloyee_wise()
                break;
            case "month_client_wise":
                month_client_wise()
                break;
            case "month_client_summery":
                month_client_summery()
                break;
        }
    }
    month_emloyee_summery = function() {
        const element = document.getElementById('month_employee_summery_pdf').innerHTML;
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
    month_emloyee_wise = function() {
        const element = document.getElementById('month_employee_pdf').innerHTML;
        var opt = {
            filename: 'employee-details.pdf',
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
    month_client_wise = function() {
        const element = document.getElementById('month_client_pdf').innerHTML;
        var opt = {
            filename: 'client-details.pdf',
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
    month_client_summery = function() {
        const element = document.getElementById('month_client_summery_pdf').innerHTML;
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
    $all_roaster= $data['last_week']['employee_wise_summery'];
    @endphp
    @include('pages.Admin.pdf.employee_wise_summery')
</div>

<div class="d-none d-print-block" id="employee_pdf">
    @php
    $all_roaster= $data['last_week']['employee_wise_report'];
    @endphp
    @include('pages.Admin.pdf.employee_wise_html')
</div>

<div class="d-none d-print-block" id="client_pdf">
    @php
    $all_roaster= $data['last_week']['client_wise_report'];
    @endphp
    @include('pages.Admin.pdf.client_wise_html')
</div>

<div class="d-none d-print-block" id="client_summery_pdf">
    @php
    $all_roaster= $data['last_week']['client_wise_summery'];
    @endphp
    @include('pages.Admin.pdf.client_wise_summery')
</div>

<!-- last month report -->
<div class="d-none d-print-block" id="month_employee_summery_pdf">
    @php
    $all_roaster= $data['last_month']['employee_wise_summery'];
    @endphp
    @include('pages.Admin.pdf.employee_wise_summery')
</div>

<div class="d-none d-print-block" id="month_employee_pdf">
    @php
    $all_roaster= $data['last_month']['employee_wise_report'];
    @endphp
    @include('pages.Admin.pdf.employee_wise_html')
</div>

<div class="d-none d-print-block" id="month_client_pdf">
    @php
    $all_roaster= $data['last_month']['client_wise_report'];
    @endphp
    @include('pages.Admin.pdf.client_wise_html')
</div>

<div class="d-none d-print-block" id="month_client_summery_pdf">
    @php
    $all_roaster= $data['last_month']['client_wise_summery'];
    @endphp
    @include('pages.Admin.pdf.client_wise_summery')
</div>
@endsection