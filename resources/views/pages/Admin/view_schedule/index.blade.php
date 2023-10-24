@extends('layouts.Admin.master')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endpush
<meta name="csrf-token" content="{{ csrf_token() }}">

@php

    function getTime($date)
    {
        return \Carbon\Carbon::parse($date)->format('H:i');
    }
    $curr_emp = Session::get('current_employee');

    $start_date = null;
    $end_date = null;
    if (Session::get('fromDate') && Session::get('toDate')) {
        $start_date = Session::get('fromDate')->format('d-m-Y');
        $end_date = Session::get('toDate')->format('d-m-Y');
    }

    //pdf
    $all_roaster = [];
@endphp
@section('admincontent')

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

                    <form action="{{ route('view-search') }}" method="POST" id="dates_form">
                        @csrf
                        <div class="row row-xs">
                            <input type="hidden" name="type_print_excel" id="type_print_excel">
                            <div class="col-lg-4 pl-25 pr-25 mt-1">
                                <input type="text" name="start_date" required class="form-control format-picker"
                                    placeholder="Start Date" value="{{ $start_date }}" />
                            </div>
                            <div class="col-lg-4 pl-25 pr-25 mt-1">
                                <input type="text" name="end_date" required class="form-control format-picker"
                                    placeholder="End Date" value="{{ $end_date }}" />
                            </div>

                            <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                                <select class="form-control select2" name="schedule" id="roaster_type">
                                    <option value="">Select Schedule</option>
                                    <option value="All" {{ Session::get('schedule') == 'All' ? 'selected' : '' }}>All
                                    </option>
                                    <option value="Schedueled"
                                        {{ Session::get('schedule') == 'Schedueled' ? 'selected' : '' }}>
                                        Scheduled</option>
                                    <option value="Unschedueled"
                                        {{ Session::get('schedule') == 'Unschedueled' ? 'selected' : '' }}>Unscheduled
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                                <select class="form-control select2" name="employee_id" id="employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ Session::get('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                                <select class="form-control select2" name="project_id" id="project_id">
                                    <option value="">Select Venue</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ Session::get('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->pName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                                <select class="form-control" name="sort_by" id="sort_by">
                                    <option value="">Group By</option>
                                    <option {{ Session::get('sort_by') == 'Employee' ? 'selected' : '' }} value="Employee">
                                        Employee</option>
                                    <option {{ Session::get('sort_by') == 'Venue' ? 'selected' : '' }} value="Venue">Venue
                                    </option>
                                    <option {{ Session::get('sort_by') == 'Client' ? 'selected' : '' }} value="Client">
                                        Client
                                    </option>
                                    <option {{ Session::get('sort_by') == 'Date' ? 'selected' : '' }} value="Date">Date
                                    </option>
                                </select>
                            </div>

                            <div class="offset-md-10 offset-lg-9 offset-6 col-md-2 col-lg-3 col-6 pl-25 pr-25 mt-1">
                                <button type="submit" class="btn btn btn-outline-primary btn-block" id="btn_search"><i
                                        data-feather='search'></i></button>
                            </div>

                            <div class="col-lg-12">

                                <h4>Download</h4>
                                <div class="dt-buttons btn-group">
                                    <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button"
                                        onclick="pdf_preview('full_report')"><span>Full
                                            PDF</span></button>
                                    <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button"
                                        onclick="pdf_preview('summery_report')"><span>Summary
                                            PDF</span></button>
                                    <a class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" href="#"
                                        onclick="fullExcelPrint();">
                                        Full Excel</a>
                                    <a class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" href="#"
                                        onclick="summaryExcelPrint();">
                                        Summary Excel</a>
                                    <!-- <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button" onclick="email_modal()"><span>Send Mail</span></button> -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Button trigger modal -->

            </div>

            @if (count($timekeepers))
                <section id="accordion-with-shadow">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="accordionWrapa10" role="tablist" aria-multiselectable="true">
                                <div class="card collapse-icon m-0">

                                    <div class="card-header pl-75 pr-75">


                                    </div>
                                    <div class="card-body pl-0 pr-0 text-center">

                                        <div class="collapse-shadow" id="accordion">
                                            @php
                                                $total_hours = 0;
                                                $total_amount = 0;
                                            @endphp


                                            <div class="card">
                                                <div id="heading11" class="bg-primary card-header text-white pb-0">
                                                    <span class="lead collapse-title" style="width:100%">
                                                        <div class="row" style="width:100%; margin: 4px">

                                                            <div class="col-4">
                                                                <p>{{ Session::get('sort_by') ? Session::get('sort_by') : 'Employee' }}
                                                                </p>
                                                            </div>

                                                            <div class="col-4">
                                                                <p>Total Hours</p>
                                                            </div>

                                                            <div class="col-4">
                                                                <p>Total Amount</p>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>

                                            @foreach ($timekeepers as $i => $timekeeper)
                                                @php

                                                    if (Session::get('sort_by')) {
                                                        $sort = Session::get('sort_by');
                                                        if ($sort == 'Venue') {
                                                            $filter_sort_by = ['project_id', $timekeeper->id];
                                                        } elseif ($sort == 'Date') {
                                                            $filter_sort_by = ['roaster_date', $timekeeper->id];
                                                        } elseif ($sort == 'Client') {
                                                            $filter_sort_by = ['client_id', $timekeeper->id];
                                                        } else {
                                                            $filter_sort_by = ['employee_id', $timekeeper->id];
                                                        }
                                                    } else {
                                                        $filter_sort_by = ['employee_id', $timekeeper->id];
                                                    }
                                                    $filter_roaster_type = Session::get('schedule') && Session::get('schedule') != 'All' ? ['roaster_type', Session::get('schedule')] : ['employee_id', '>', 0];
                                                    $filter_employee = Session::get('employee_id') ? ['employee_id', Session::get('employee_id')] : ['employee_id', '>', 0];
                                                    $filter_project = Session::get('project_id') ? ['project_id', Session::get('project_id')] : ['employee_id', '>', 0];
                                                    //$filter_client = Session::get('client_id') ? ['client_id',Session::get('client_id')]:['employee_id','>',0];

                                                    $fromDate = Session::get('fromDate');
                                                    $toDate = Session::get('toDate');

                                                    $timekeeperData = App\Models\TimeKeeper::where([
                                                        ['company_code', Auth::user()->company_roles->first()->company->id],
                                                        $filter_sort_by,
                                                        $filter_roaster_type,
                                                        $filter_employee,
                                                        $filter_project,
                                                        //$filter_client
                                                    ])
                                                        ->orderBy('roaster_date', 'asc')
                                                        ->orderBy('shift_start', 'asc')
                                                        ->whereBetween('roaster_date', [$fromDate, $toDate])
                                                        //->where(function ($q) {
                                                        //avoid_rejected_key($q);
                                                        //})
                                                        ->get();

                                                    $duration = $timekeeperData->sum('duration');
                                                    $amount = $timekeeperData->sum('amount');
                                                    $total_hours += floatval($duration);
                                                    $total_amount += floatval($amount);

                                                    $un_approved_ids = $timekeeperData->where('is_approved', 0)->pluck('id');

                                                    //pdf report
                                                    $all_roaster[$i]['name'] = Session::get('sort_by') == 'Date' ? \Carbon\Carbon::parse($timekeeper->roaster_date)->format('d-m-Y') : $timekeeper->name;
                                                    $all_roaster[$i]['roasters'] = $timekeeperData;
                                                    $all_roaster[$i]['total_hours'] = $duration;
                                                    $all_roaster[$i]['total_amount'] = $amount;
                                                @endphp

                                                <div class="card p-25">
                                                    <div id="heading11"
                                                        class="card-header card-click pb-0 pl-0 pr-0 {{ $timekeeper->id == $curr_emp ? 'curr' : '' }}"
                                                        totalIds="{{ $un_approved_ids->implode(',') }}"
                                                        data-toggle="collapse" role="button"
                                                        data-target="#accordion{{ $timekeeper->id }}"
                                                        aria-expanded="false" aria-controls="accordion10">
                                                        <span class="lead collapse-title" style="width:100%">
                                                            <div class="row" style="width:100%; margin: 4px">

                                                                <div class="col-4">
                                                                    <p>{{ Session::get('sort_by') == 'Date' ? \Carbon\Carbon::parse($timekeeper->roaster_date)->format('d-m-Y') : $timekeeper->name }}
                                                                    </p>
                                                                </div>

                                                                <div class="col-4">
                                                                    <p>{{ $duration }}</p>
                                                                </div>

                                                                <div class="col-4">
                                                                    <p>{{ $amount }}</p>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>

                                                    <div id="accordion{{ $timekeeper->id }}" role="tabpanel"
                                                        data-parent="#accordion" aria-labelledby="heading11"
                                                        class="collapse">
                                                        <div class="card-body p-0">

                                                            <!-- <div> -->
                                                            <button
                                                                class="btn btn-gradient-primary float-left m-50 taskBtn approve"
                                                                url="/admin/home/timekeeper/approve/" disabled>
                                                                <i data-feather="check-circle" class=""></i>
                                                                Approve
                                                            </button>
                                                            <!-- </div> -->
                                                            <div class="col-12 pl-25 pr-25 table-responsive">
                                                                <table class="table table-bordered table-striped"
                                                                    id="target_table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><input type="checkbox"
                                                                                    class="mt-75 taskcheckAllID"
                                                                                    onclick="taskcheckAllID()"></th>
                                                                            <th>Employee Name</th>
                                                                            <th>Venue</th>
                                                                            <th>Roster Date</th>
                                                                            <th>Shift Start</th>
                                                                            <th>Shift End</th>
                                                                            <th>Sign In</th>
                                                                            <th>Sign Out</th>
                                                                            <th>Rate</th>
                                                                            <th>App. Start</th>
                                                                            <th>App. End</th>
                                                                            <th>App. Rate</th>
                                                                            <th>App. Duration</th>
                                                                            <th>App. Amount</th>
                                                                            <th>Details</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                        @foreach ($timekeeperData as $k => $row)
                                                                            @php
                                                                                $json = json_encode($row->toArray(), false);

                                                                            @endphp
                                                                            <tr
                                                                                class="{{ $row->roaster_type == 'Unschedueled' ? 'bg-light-primary' : '' }} {{ $row->sing_in ? '' : 'bg-light-danger' }}">
                                                                                <td class="p-0">
                                                                                    @if ($row->is_approved)
                                                                                        <i data-feather="{{ $row->payment_status ? 'dollar-sign' : 'check-circle' }}"
                                                                                            class="text-primary"></i>
                                                                                    @else
                                                                                        <input type="checkbox"
                                                                                            class="taskCheckID"
                                                                                            value="{{ $row->id }}">
                                                                                    @endif
                                                                                    {{ $k + 1 }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $row->employee->fname }}
                                                                                </td>
                                                                                <td>
                                                                                    @if (isset($row->project->pName))
                                                                                        {{ $row->project->pName }}
                                                                                    @else
                                                                                        Null
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y') }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ getTime($row->shift_start) }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ getTime($row->shift_end) }}
                                                                                </td>
                                                                                <td class="">
                                                                                    {{ $row->sing_in ? getTime($row->sing_in) : 'unspecified' }}
                                                                                </td>
                                                                                <td class="">
                                                                                    {{ $row->sing_out ? getTime($row->sing_out) : 'unspecified' }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $row->ratePerHour }}
                                                                                </td>
                                                                                <td class="">
                                                                                    {{ getTime($row->Approved_start_datetime) }}
                                                                                </td>
                                                                                <td class="">
                                                                                    {{ getTime($row->Approved_end_datetime) }}
                                                                                </td>
                                                                                <td>{{ $row->app_rate }}</td>
                                                                                <td>{{ $row->app_duration }}</td>
                                                                                <td>{{ $row->app_amount }}</td>
                                                                                <td>
                                                                                    {{ $row->remarks }}
                                                                                </td>

                                                                                <td>
                                                                                    <div class="p-50">
                                                                                        @if ($row->is_approved == 0)
                                                                                            <button
                                                                                                class="edit-btn btn btn-gradient-primary mb-25"
                                                                                                data-row="{{ $json }}"><i
                                                                                                    data-feather='edit'></i></button>
                                                                                            <a class="btn btn-gradient-danger del text-white"
                                                                                                url="/admin/home/view/schedule/delete/{{ $row->id }}"><i
                                                                                                    data-feather='trash-2'></i></a>
                                                                                        @else
                                                                                            <button
                                                                                                class="edit-btn btn btn-gradient-primary mb-25"
                                                                                                data-row="{{ $json }}"><i
                                                                                                    data-feather='eye'></i></button>
                                                                                        @endif
                                                                                    </div>

                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot style="display:none;">
                                                                        <tr>
                                                                            <td colspan=12>
                                                                            </td>
                                                                            <td>Total Hours : {{ $duration }}</td>
                                                                            <td>Total Amount : {{ $amount }}</td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @include('pages.Admin.view_schedule.modals.timeKeeperEditModal')

                                            <div class="card">
                                                <div id="heading11" class="card-header pb-0" style="background: #ddd;">
                                                    <span class="lead collapse-title" style="width:100%">
                                                        <div class="row" style="width:100%">

                                                            <div class="col-4">
                                                                <p>{{ count($timekeepers) }}
                                                                    {{ Session::get('sort_by') ? Session::get('sort_by') : 'Employee' }}
                                                                </p>
                                                            </div>

                                                            <div class="col-4">
                                                                <p>{{ $total_hours }} Hours </p>
                                                            </div>

                                                            <div class="col-4">
                                                                <p>$ {{ $total_amount }} </p>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
        </div>
    @else
        <div class="card">
            <div id="heading11" class="card-header text-center">
                <span class="lead collapse-title" style="width:100%">
                    <h3>No data found!</h3>
                </span>
            </div>
        </div>
        @endif
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
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
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

                    <form class="form-horizontal" role="form" id="mail_form" method="post"
                        action="javascript:void(0);">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">
                                <span class="required"></span>Name: </label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="First & Last" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">
                                <span class="required"></span>Email: </label>
                            <div class="col-12">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="you@domain.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">
                                <span class="required"></span>Subject: </label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="subject" name="subject"
                                    placeholder="email subject" required>
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
                                <button class="btn btn-danger mt-25 ml-25" type="button"
                                    data-dismiss="modal">Close</button>
                                <button type="submit" id="submit" name="submit"
                                    class="btn btn-submit btn-primary mt-25 ml-25">Send</button>&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <!--end Form-->
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        fullExcelPrint = function() {
            $("#type_print_excel").val('full');
            $("#dates_form").submit();
        }

        summaryExcelPrint = function() {
            $("#type_print_excel").val('summary');
            $("#dates_form").submit();
        }

        pdf_preview = function(rep) {
            if ((!window.scheduleFullReport && rep == 'full_report') || (!window.scheduleSummeryReport && rep ==
                    'summery_report')) {
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
                    window.scheduleFullReport = genereatePDF
                } else if (rep = 'summery_report') {
                    window.scheduleSummeryReport = genereatePDF
                }
            }

            if (rep == 'full_report') {
                window.genereatedPDF = window.scheduleFullReport
            } else if (rep = 'summery_report') {
                window.genereatedPDF = window.scheduleSummeryReport
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
            $("#submit").prepend(
                '<span class="loader"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"> &nbsp;</span><span class="sr-only">Loading...</span></span>'
            );
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
            function modal_close(id) {
                // alert(id);
                if (id == 1) {
                    window.location.reload();
                    // $('.modal-backdrop').remove();  
                }

            }
            $(function() {

                $("#newModalForm").validate({
                    rules: {
                        pName: {
                            required: true,
                            minlength: 8
                        },
                        action: "required"
                    },
                    messages: {
                        pName: {
                            required: "Please enter some data",
                            minlength: "Your data must be at least 8 characters"
                        },
                        action: "Please provide some data"
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#dates_form').validate()
                let totalTaskId = []
                let task_ids = []
                // let event_id = null

                resetTaskId = function() {
                    task_ids = []
                    $('.open .taskCheckID').prop('checked', false)
                    $('.open .taskcheckAllID').prop('checked', false)
                    $(".open .taskBtn").prop('disabled', true)
                }
                @if ($curr_emp)
                    $('.curr').click()
                    totalTaskId = $('.curr').first().attr("totalIds").split(",")
                    resetTaskId()
                @endif

                $(document).on("click", ".card-click", function() {
                    totalTaskId = $(this).attr("totalIds").split(",")
                    resetTaskId()
                })
                $(document).on("click", ".open .taskCheckID", function() {
                    if ($(this).is(':checked')) {
                        task_ids.push($(this).val())
                    } else {
                        let id = $(this).val()
                        task_ids = jQuery.grep(task_ids, function(value) {
                            return value != id
                        })
                    }

                    if (task_ids.length === 0) {
                        $(".open .taskBtn").prop('disabled', true)
                    } else {
                        $(".open .taskBtn").prop('disabled', false)
                    }

                    if (task_ids.length == totalTaskId.length) {
                        $('.open .taskcheckAllID').prop('checked', true)
                    } else {
                        $('.open .taskcheckAllID').prop('checked', false)
                    }
                })
                taskcheckAllID = function() {
                    if ($(".open .taskcheckAllID").is(':checked')) {
                        task_ids = totalTaskId
                        $('.open .taskCheckID').prop('checked', true)
                    } else {
                        task_ids = []
                        $('.open .taskCheckID').prop('checked', false)
                    }

                    if (task_ids.length === 0) {
                        $(".open .taskBtn").prop('disabled', true)
                    } else {
                        $(".open .taskBtn").prop('disabled', false)
                    }

                }

                $('#employee-id').wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Select Employee',
                    dropdownParent: $('#employee-id').parent()
                });

                $(document).on("click", ".approve", function() {
                    window.location = $(this).attr('url') + task_ids
                    // console.log($(this).attr('url')+'['+task_ids+']')
                })
                $(document).on("click", ".del", function() {
                    swal({
                            title: "Are you sure?",
                            text: "Once deleted, you will not be able to recover this!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                window.location = $(this).attr('url')
                            }
                        });
                })

                // var roaster_date, roaster_end, shift_start, shift_end;
                function timeToSeconds(time) {
                    time = time.split(/:/);
                    return time[0] * 3600 + time[1] * 60;
                }
                $.time = function(dateObject) {
                    var t = dateObject.split(/[- :]/);
                    // Apply each element to the Date function
                    var actiondate = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
                    var d = new Date(actiondate);

                    // var d = new Date(dateObject);
                    var curr_hour = d.getHours();
                    var curr_min = d.getMinutes();
                    var date = curr_hour + ':' + curr_min;
                    return date;
                };

                function allCalculation() {
                    var start = $("#app_start").val();
                    var end = $("#app_end").val();
                    var rate = $("#app_rate").val();

                    if (start && end) {
                        // calculate hours
                        var diff = (timeToSeconds(end) - timeToSeconds(start)) / 3600
                        if (diff < 0) {
                            diff = 24 - Math.abs(diff)
                        }
                        if (diff) {
                            $("#app_duration").val(diff);
                            if (rate) {
                                $("#app_amount").val(parseFloat(rate) * diff);
                            }
                        }

                    } else {
                        $("#app_duration").val('');
                        $("#app_amount").val('');
                    }
                }

                var isValid = true;
                var modalToTarget = document.getElementById("addTimeKeeper");

                function roasterEndTimeInit() {
                    $("#app_end").change(function() {
                        allCalculation()
                    });

                }
                roasterEndTimeInit()

                function roasterStartTimeInit() {
                    $("#app_start").change(function() {
                        if ($(this).val()) {
                            $("#app_end").removeAttr("disabled")
                        } else {
                            $("#app_end").prop('disabled', true);
                        }

                        allCalculation()
                    });

                }
                roasterStartTimeInit()

                const initDatePicker = () => {
                    $("#roaster_date").change(function() {
                        if ($(this).val()) {
                            $("#app_start").removeAttr("disabled")
                        } else {
                            $(".picker__button--clear").removeAttr("disabled")

                            $(".picker__button--clear")[1].click()
                            $(".picker__button--clear")[2].click()

                            $("#app_start").prop('disabled', true)

                            $("#app_end").prop('disabled', true);
                            allCalculation()
                        }
                    });
                }

                initDatePicker();
                const initAllDatePicker = () => {
                    initDatePicker();
                    roasterStartTimeInit();
                    roasterEndTimeInit();
                }

                // Change modal to false to see that it doesn't happen there
                $("#dialog").modal({
                    autoOpen: true,
                    modal: true,
                });

                $(document).on("click", ".timekeer-btn", function() {
                    if (isValid) {
                        console.log($(this).closest("form").submit())
                    }
                })

                $(document).on("click", ".edit-btn", function() {
                    resetValue()
                    var rowData = $(this).data("row");

                    $("#timepeeper_id").val(rowData.id);
                    $("#employee_id").val(rowData.employee_id).trigger('change');
                    $(".employee_id").val(rowData.employee_id);
                    $("#project-select").val(rowData.project_id).trigger('change');
                    $("#roaster_date").val(moment(rowData.roaster_date).format('DD-MM-YYYY'))
                    $("#shift_start").val($.time(rowData.shift_start))
                    $("#shift_end").val($.time(rowData.shift_end))
                    if (rowData.sing_in) {
                        $("#sign_in").val($.time(rowData.sing_in))
                    } else {
                        $("#sign_in").val('unspecified')
                    }
                    if (rowData.sing_out) {
                        $("#sign_out").val($.time(rowData.sing_out))
                    } else {
                        $("#sign_out").val('unspecified')
                    }

                    if (rowData.is_approved == 1) {
                        $('.timekeer-btn').prop('hidden', true);
                    } else {
                        $('.timekeer-btn').prop('hidden', false);
                    }
                    $("#app_start").val($.time(rowData.Approved_start_datetime))
                    $("#app_end").val($.time(rowData.Approved_end_datetime))

                    $("#app_rate").val(rowData.app_rate)
                    $("#app_duration").val(rowData.app_duration)
                    $("#app_amount").val(rowData.app_amount)
                    // $("#job").val(rowData.job_type_id)
                    // $("#roster").val(rowData.roaster_status_id)

                    $("#remarks").val(rowData.remarks)

                    $("#editTimeKeeper").modal("show")


                    initAllDatePicker();
                    allCalculation()

                })

                $(document).on("input", ".reactive", function() {
                    allCalculation()
                })

                function resetValue() {
                    $("#timepeeper_id").val();
                    $('#timepeeper_id').attr('value', '');
                    $("#employee_id").val('');
                    // $("#client-select").val('').trigger('change');

                    $("#roaster_date").val('')
                    $("#shift_start").val('')
                    $("#shift_end").val('')
                    $("#sign_in").val('')
                    $("#sign_out").val('')
                    $("#app_start").val('')
                    $("#app_end").val('')

                    $("#app_rate").val('')
                    $("#app_duration").val('')
                    $("#app_amount").val('')
                    // $("#job").val('')
                    // $("#roster").val('')

                    $("#remarks").val('')
                    $("#project-select").val('');
                }
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
