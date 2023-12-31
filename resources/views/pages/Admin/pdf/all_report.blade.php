@extends('layouts.Admin.master')
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">

@endpush
<meta name="csrf-token" content="{{ csrf_token() }}">
@php

function getTime($date){
return \Carbon\Carbon::parse($date)->format('H:i');
}
$curr_emp= Session::get('current_employee');

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
<div class="row">

    <div class="col-lg-12 col-md-12">
        <div class="card p-0">
            <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
                <h3 class="card-title text-primary d-inline">
                    Select Roster Dates
                </h3>
                <span class="float-right">
                    <i class="fa fa-chevron-up clickable"></i>
                </span>
            </div>

            <div class="card-body">

                <form action="/admin/home/all/report/search" method="POST" id="dates_form">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-lg-4 pl-25 pr-25 mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Start Date" value="{{$start_date}}" />
                        </div>
                        <div class="col-lg-4 pl-25 pr-25 mt-1">
                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="End Date" value="{{$end_date}}" />
                        </div>

                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="employee_id" id="employee_id">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{Session::get('employee_id')==$emp->id ?'selected':''}}>
                                    {{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="project_id" id="project_id">
                                <option value="">Select Venue</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{Session::get('project_id')==$project->id ?'selected':''}}>{{ $project->pName }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="client_id" id="client_id">
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{Session::get('client_id')==$client->id ?'selected':''}}>{{ $client->cname }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="schedule" id="roaster_type">
                                <option value="">Select Schedule</option>
                                <option value="All" {{Session::get('schedule')=='All'?'selected':''}}>All</option>
                                <option value="Schedueled" {{Session::get('schedule')=='Schedueled'?'selected':''}}>Scheduled</option>
                                <option value="Unschedueled" {{Session::get('schedule')=='Unschedueled'?'selected':''}}>Unscheduled</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="payment_status" id="payment_status">
                                <option value="">Payment Status</option>
                                <option value='1' {{Session::get('payment_status') == '1' ?'selected':'false'}}>Paid</option>
                                <option value='0' {{Session::get('payment_status') == '0' ?'selected':'false'}}>Pending</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="sort_by" id="sort_by">
                                <option value="">Group By</option>
                                <option {{Session::get('sort_by')=='Employee'?'selected':''}} value="Employee">Employee</option>
                                <option {{Session::get('sort_by')=='Venue'?'selected':''}} value="Venue">Venue</option>
                                <option {{Session::get('sort_by')=='Client'?'selected':''}} value="Client">Client</option>
                                <option {{Session::get('sort_by')=='Date'?'selected':''}} value="Date">Date</option>
                            </select>
                        </div>

                        <div class="offset-md-10 offset-lg-9 offset-6 col-md-2 col-lg-3 col-6 pl-25 pr-25 mt-1">
                            <button type="submit" class="btn btn btn-outline-primary btn-block" id="btn_search"><i data-feather='search'></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Button trigger modal -->

        </div>

        @if(count($timekeepers) >0)
        <div class="card">

            <div class="card-header pl-75 pr-75">
                <div class="btn-group">
                    <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button" onclick="pdf_preview('full_report')"><span>Full PDF</span></button>
                    <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button" onclick="pdf_preview('summery_report')"><span>Summery PDF</span></button>
                    <!-- <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button" onclick="email_modal()"><span>Send Mail</span></button> -->
                </div>

            </div>
        </div>
        @endif

        <div class="card">
            <div class="row" id="table-hover-animation">
                <div class="col-12">
                    <div class="card">
                        <div class="container">
                            <div class="table-responsive">
                                <!-- 2023-05-19 Button trigger for modal-->
                                <!-- 2023-05-19 end Button trigger for modal-->
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

    <!-- pdf preview -->
    <div class="modal fade left" id="pdf_preview_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary">
                    <h3 class="pull-left no-margin text-light">PDF Preview</h3>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <embed src="" id="pdf_viewer" type="application/pdf" width="100%" height="550px" />
                </div>
                <div class="modal-footer footer-fixed">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <a class="btn btn-success timekeer-btn generate_pdf" href="#">Download</a>
                    <button type="button" class="btn btn-primary" onclick="email_modal()">Send Mail</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade left" id="mail_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary">
                    <h3 class="pull-left no-margin text-light">Send Mail Form</h3>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--INSERT CONTACT FORM HERE-->
                    <!--NOTE: you will need to provide your own form processing script-->
                    <!--<form class="form-horizontal" role="form" method="post" action="">-->

                    <form class="form-horizontal" role="form" id="mail_form" method="post" action="javascript:void(0);">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">
                                <span class="required"></span>Name: </label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="First & Last" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">
                                <span class="required"></span>Email: </label>
                            <div class="col-12">
                                <input type="email" class="form-control" id="email" name="email" placeholder="you@domain.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">
                                <span class="required"></span>Subject: </label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="email subject" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="File" class="col-12 control-label">
                                File:
                                <a class="required text-primary generate_pdf h6">Roster-report.pdf </a></label>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">
                                <span class="required"></span>Message: </label>
                            <div class="col-12">
                                <textarea name="message" rows="4" class="form-control" id="message" placeholder="Comments"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex col-sm-offset-3 col-sm-6 col-sm-offset-3">
                                <button class="btn btn-danger mt-25 ml-25" type="button" data-dismiss="modal">Close</button>
                                <button type="submit" id="submit" name="submit" class="btn btn-submit btn-primary mt-25 ml-25">Send</button>&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <!--end Form-->
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        pdf_preview = function(rep) {
            if ((!window.fullReport && rep == 'full_report') || (!window.summeryReport && rep == 'summery_report')) {
                $("#pdf_viewer").attr('src', '')
                const element = document.getElementById(rep).innerHTML;
                var opt = {
                    margin: [0, 0],
                    filename: 'roster-report.pdf',
                    html2canvas: {
                        scale: 2,
                        scrollY: 0
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'tabloid',
                        orientation: 'portrait'
                    }
                };

                // html2pdf().set(opt).from(element).save();
                let genereatePDF = html2pdf().set(opt).from(element).toPdf();
                if (rep == 'full_report') {
                    window.fullReport = genereatePDF
                } else if (rep = 'summery_report') {
                    window.summeryReport = genereatePDF
                }
            }

            if (rep == 'full_report') {
                window.genereatedPDF = window.fullReport
            } else if (rep = 'summery_report') {
                window.genereatedPDF = window.summeryReport
            }

            $("#pdf_preview_modal").modal("show");
            window.genereatedPDF.output('datauristring').then(function(pdfAsString) {
                $("#pdf_viewer").attr('src', pdfAsString)
            })

        }

        $(document).on("click", ".generate_pdf", function() {
            window.genereatedPDF.save();
        })

        email_modal = function() {
            $("#mail_modal").modal("show");
        }



        $('#mail_form').submit(function(e) {
            e.preventDefault();
            $("#submit").attr("disabled", true);
            $("#submit").prepend('<span class="loader"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"> &nbsp;</span><span class="sr-only">Loading...</span></span>');
            var $form = $(this);

            // check if the input is valid using a 'valid' property
            if (!$form.valid) return false;


            window.genereatedPDF.output('datauristring').then(function(pdfAsString) {
                // The PDF has been converted to a Data URI string and passed to this function.
                // Use pdfAsString however you like (send as email, etc)! For instance:
                var name = $("input[name=name]").val();
                var subject = $("input[name=subject]").val();
                var email = $("input[name=email]").val();
                var message = $("#message").val();
                var fileName = 'roster-report.pdf';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                var dataToSubmit = {
                    name: name,
                    subject: subject,
                    email: email,
                    pdfHtml: pdfAsString,
                    message: message,
                    fileName: fileName
                };

                $.ajax({
                    type: 'POST',
                    url: "/admin/home/all/report/search/email",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Access-Control-Allow-Methods': 'POST',
                        'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization'
                    },
                    data: dataToSubmit,
                    success: function(data) {
                        console.log(data);
                        $('#form').each(function() {
                            this.reset();
                        });
                        $("#submit").find(".loader").remove();
                        $("#submit").removeAttr("disabled");
                        $('#myModal').modal('hide');
                        $('#notifications').attr('style', 'display:none !important')
                        $("#no_notification").attr('style', 'display:flex !important')
                        toastr['info'](data.status);
                    },
                    error: function(data) {
                        toastr['info']('Sorry, the email could not be sent.Please try again!');
                        $("#submit").find(".loader").remove();
                        $("#submit").removeAttr("disabled");
                    }

                });
            });
        });
    </script>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#dates_form').validate()
        })
    </script>
    @endpush

    @endsection

    @section('pdf_generator')
    <div class="d-none d-print-block" id="full_report">
        @include('pages.Admin.pdf.view_schedule_report')
    </div>
    <div class="d-none d-print-block" id="summery_report">
        @include('pages.Admin.pdf.summery_report')
    </div>
    @endsection