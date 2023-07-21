@extends('layouts.Admin.master')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
@endpush
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <button class="btn btn-outline-primary mt-25 ml-25" id="emailBtn" type="button" onclick="email_data()">Email</button>
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
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{Session::get('employee_id')==$employee->id ?'selected':''}}>
                                    {{ $employee->fname }}
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
                            <select class="form-control select2" name="payment_status" id="payment_status">
                                <option value="">Payment Status</option>
                                <option value='1' {{Session::get('payment_status') == '1' ?'selected':'false'}}>Paid</option>
                                <option value='0' {{Session::get('payment_status') == '0' ?'selected':'false'}}>Pending</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="schedule" id="roaster_type">
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
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script >
    
    </script>

<!-- 2023-05-19 Modal Form-->
 <div class="modal fade left" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="pull-left no-margin">E-mail Form</h3>
                  <span class="glyphicon glyphicon-remove"></span>
                </button>
              </div>
              <div class="modal-body">
                <!--INSERT CONTACT FORM HERE-->
                <!--NOTE: you will need to provide your own form processing script-->
                <!--<form class="form-horizontal" role="form" method="post" action="">--> 
                    
                <form class="form-horizontal" role="form" id="form" method="post" action="javascript:void(0);"> 
                 @csrf
                  <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">
                      <span class="required"></span>Name: </label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="name" placeholder="First & Last" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">
                      <span class="required"></span>Email: </label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" id="email" name="email" placeholder="you@domain.com" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">
                      <span class="required"></span>Subject: </label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="subject" name="subject" placeholder="email subject" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="File" class="col-sm-3 control-label">
                      <span class="required"></span>File: .pdf </label>
                      <span class="filePreview"></span>
                  </div>
                  <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">
                      <span class="required"></span>Message: </label>
                    <div class="col-sm-9">
                      <textarea name="message" rows="4"  class="form-control" id="message" placeholder="Comments"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="d-flex col-sm-offset-3 col-sm-6 col-sm-offset-3">
                      <button type="submit" id="submit" name="submit" class="btn-submit btn-lg btn-primary mt-25 ml-25">SUBMIT</button>&nbsp;&nbsp;&nbsp;
                      <input type="hidden" value="" id="pdfAsString">
                      <input type="hidden" value="" id="fileName">
                      <button class="btn-lg btn-primary mt-25 ml-25" type="button" data-dismiss="modal">Close</button> 
                    </div>
                  </div>
                  <!--end Form-->
                </form>
              </div>
  
            </div>
          </div>
        </div>
<!-- 2023-05-19 End Modal Form-->

<script>
    email_data = function() {
        let report_type = $('#report_type').val();
        if (report_type == "") {
            alert("Please select report");
            return false;
        }
        $("#myModal").modal("show");
    }
    
    $(document).ready(function(){
        $('#myModal').on('show.bs.modal', function () {
            generatePdf();
        });
    });
    
    generatePdf = function() {
        let report_type = $('#report_type').val();
        if (report_type == "") {
            alert("Please select report");
            $("#emailBtn").find(".spinner-border").remove();
            return false;
        }
        
        var pdfId = "";
        switch (report_type) {
            case "emloyee_summery":
                pdfId = "employee_summery_pdf";
                break;
            case "emloyee_wise":
                pdfId = "employee_pdf";
                break;
            case "client_wise":
                pdfId = "client_pdf";
                break;
            case "client_summery":
                pdfId = "client_summery_pdf";
                break;
        }

        var pdfHtml = document.getElementById(pdfId).innerHTML;
        
        const element = pdfHtml;
        var opt = {
            filename: pdfId+'.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'tabloid',
                orientation: 'portrait'
            }
        };

        document.getElementById("fileName").value = pdfId+".pdf";
        html2pdf().from(element).toPdf().output('datauristring').then(function (pdfAsString) {
            // The PDF has been converted to a Data URI string and passed to this function.
            // Use pdfAsString however you like (send as email, etc)! For instance:
            document.getElementById("pdfAsString").value = pdfAsString;
            $(".filePreview").html("<a download='"+report_type+".pdf' href='"+$("#pdfAsString").val()+"'>"+report_type+".pdf</a>");
        });
    }
    
    $('#form').submit(function (e) {
        e.preventDefault();
        $("#submit").attr("disabled", true);
        $("#submit").prepend('<span class="loader"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"> &nbsp;</span><span class="sr-only">Loading...</span></span>');
        var $form = $(this);
    
        // check if the input is valid using a 'valid' property
        if (!$form.valid) return false;
    
        var name = $("input[name=name]").val();
        var subject = $("input[name=subject]").val();
        var email = $("input[name=email]").val();
        var pdfHtml = $("#pdfAsString").val();
        var message = $("#message").val();
        var fileName = $("#fileName").val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        var dataToSubmit = {name:name, subject:subject, email:email, pdfHtml: pdfHtml, message: message, fileName: fileName};
        
        $.ajax({
           type:'POST',
           url:"/admin/home/all/report/search/email",
           headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Access-Control-Allow-Methods' : 'POST',
                'Access-Control-Allow-Headers' : 'X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization'
           },
           data: dataToSubmit,
           success:function(data){
                $( '#form' ).each(function(){
                   this.reset();
                });
               $("#submit").find(".loader").remove();
               $("#submit").removeAttr("disabled");
               $('#myModal').modal('hide');
                $('#notifications').attr('style','display:none !important')
                $("#no_notification").attr('style','display:flex !important')
                toastr['success']('Email sent successfully.');
           }
        });
    });
 
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
<!--2023-05-19 generate pdf -->
<script>
    
</script>
<!--2023-05-19 end generate pdf -->


<!-- pdf

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