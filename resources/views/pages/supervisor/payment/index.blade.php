@extends('layouts.Admin.master')
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">

@endpush
@php

function getTime($date){
return \Carbon\Carbon::parse($date)->format('H:i');
}

$fromRoaster=null;
$toRoaster = null;
if(Session::get('fromPayment')){
$fromRoaster = \Carbon\Carbon::parse(Session::get('fromPayment'))->format('d-m-Y');
}
if(Session::get('toPayment')){
$toRoaster = \Carbon\Carbon::parse(Session::get('toPayment'))->format('d-m-Y');
}
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

            <div class="card-body pb-0">

                <form action="{{ route('payment_search') }}" method="POST" id="dates_form">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-lg-4 col-12 pl-25 pr-25 mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Event Date From" value="{{$fromRoaster}}" />
                        </div>
                        <div class="col-lg-4 col-12 pl-25 pr-25 mt-1">
                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="Event Date To" value="{{$toRoaster}}" />
                        </div>

                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="project_id">
                                <option value="">Select Venue</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{Session::get('payment_project_id')==$project->id ?'selected':''}}>{{ $project->pName }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="employee_id">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{Session::get('payment_employee_id')==$employee->id ?'selected':''}}>{{ $employee->fname }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-lg-3 col-6 pl-25 pr-25 mt-1 mb-1">
                            <button type="submit" class="btn btn btn-outline-primary btn-block" id="btn_search"><i data-feather='search'></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Button trigger modal -->

        </div>

        @if(count($timekeepers))
        <section id="accordion-with-shadow">
            <div class="row">
                <div class="col-sm-12">
                    <div id="accordionWrapa10" role="tablist" aria-multiselectable="true">
                        <div class="card m-0 collapse-icon">
                            @if(count($timekeepers) >0)
                            <div class="card-header">
                                <div class=" btn-group">
                                    <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button" id="emloyee_wise"><span>Employee Wise PDF</span></button>
                                    <button class="btn btn-outline-secondary buttons-pdf buttons-html5 ml-25" type="button" id="client_wise"><span>Client Wise PDF</span></button>
                                </div>

                            </div>
                            @endif
                            <div class="card-body p-0 text-center">

                                <div class="collapse-shadow">
                                    @php
                                    $total_hours = 0;
                                    $total_amount = 0;
                                    @endphp


                                    <div class="card m-0">
                                        <div id="heading11" class="bg-primary card-header pl-0 pr-0 text-white pb-0">
                                            <span class="lead collapse-title" style="width:100%">
                                                <div class="row" style="width:100%; margin: 4px">

                                                    <div class="col-4">
                                                        <p>Employee Name</p>
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

                                    $filter_project = Session::get('payment_project_id') ? ['project_id',Session::get('payment_project_id')]:['employee_id','>',0];
                                    $filter_employee = Session::get('payment_employee_id')? ['employee_id',Session::get('payment_employee_id')]:['employee_id','>',0];

                                    $fromDate = Session::get('fromPayment');
                                    $toDate = Session::get('toPayment');

                                    $timekeeperData = App\Models\TimeKeeper::where([
                                    ['employee_id', $timekeeper->id],
                                    ['company_code', Auth::user()->supervisor->company],
                                    ['payment_status', 0],
                                    $filter_employee,
                                    $filter_project,
                                    ])
                                    ->orderBy('roaster_date','asc')
                                    ->orderBy('shift_start','asc')
                                    ->whereBetween('roaster_date', [$fromDate, $toDate])
                                    ->where(function ($q) {
                                    avoid_rejected_key($q);
                                    })
                                    ->get();

                                    $duration= $timekeeperData->sum("duration");
                                    $amount= $timekeeperData->sum("amount");
                                    $total_hours += floatval($duration);
                                    $total_amount += floatval($amount);
                                    @endphp

                                    <div class="card m-0">
                                        <div id="heading11" class="card-header pb-0 pl-0 pr-0 " data-toggle="collapse" role="button" data-target="#accordion{{$timekeeper->id}}" aria-expanded="false" aria-controls="accordion10">
                                            <span class="lead collapse-title" style="width:100%">
                                                <div class="row" style="width:100%; margin: 4px">

                                                    <div class="col-4">
                                                        <p>{{ $timekeeper->fname }}</p>
                                                    </div>

                                                    <div class="col-4">
                                                        <p>{{ $duration}}</p>
                                                    </div>

                                                    <div class="col-4">
                                                        <p>{{ $amount }}</p>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div id="accordion{{$timekeeper->id}}" role="tabpanel" data-parent="#accordionWrapa10" aria-labelledby="heading11" class="collapse bg-light">
                                            <div class="card-body  p-0">

                                                <form action="{{ route('supervisorDateUpdate') }}" method="POST" id="newModalForm">
                                                    <input type="hidden" id="timepeeper_id" name="timepeeper_id">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <section id="multiple-column-form">
                                                            <div class="row">

                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-responsive table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <!-- <th>Client</th> -->
                                                                                <th>Venue</th>
                                                                                <th>Roster Date</th>
                                                                                <th>Roster Start</th>
                                                                                <th>Roster End</th>
                                                                                <th>Clock In</th>
                                                                                <th>Clock Out</th>
                                                                                <th>Approved Start</th>
                                                                                <th>Approved End</th>
                                                                                <th>Duration</th>
                                                                                <th>Rate</th>
                                                                                <th>Amount</th>
                                                                                <th>Remarks</th>
                                                                                <!-- <th>Action</th>-->
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                            @foreach ($timekeeperData as $k => $row)
                                                                            {{-- {{ dd($row) }} --}}
                                                                            @php
                                                                            $json = json_encode($row->toArray(), false);
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $k + 1 }}</td>
                                                                                <!-- <td>
                                                                                @if (isset($row->client->cname))
                                                                                {{ $row->client->cname }}
                                                                                @else
                                                                                Null
                                                                                @endif
                                                                            </td> -->
                                                                                <td>
                                                                                    @if (isset($row->project->pName))
                                                                                    {{ $row->project->pName }}
                                                                                    @else
                                                                                    Null
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    {{\Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                                                                </td>
                                                                                <td>{{getTime($row->shift_start)}}</td>
                                                                                <td>{{getTime($row->shift_end)}}</td>
                                                                                <td>{{$row->sing_in ? getTime($row->sing_in): 'none'}}</td>
                                                                                <td>{{$row->sing_out ? getTime($row->sing_out): 'none'}}</td>
                                                                                <td class="">
                                                                                    <input type="text" style="width: 60px;" class="form-control pickatime-format text-center form-control-sm p-0" value="{{getTime($row->Approved_start_datetime)}}" name="start_date[]" />
                                                                                </td>
                                                                                <td class="">
                                                                                    <input type="text" style="width: 60px;" class="form-control pickatime-format text-center form-control-sm p-0" value="{{getTime($row->Approved_end_datetime)}}" name="end_date[]" />

                                                                                    <input type="text" style="width: 60px; display:none;" value="{{$row->id}}" name="id[]" />
                                                                                    <!-- <input type="text" style="width: 60px;" style="display:none;" value="{{$fromRoaster}}" name="fromdate" />
                                                                                    <input type="text" style="width: 60px;" style="display:none;" value="{{$toRoaster}}" name="todate" /> -->
                                                                                </td>
                                                                                <td>

                                                                                    <input type="text" style="width: 60px;" class="form-control form-control-sm text-center p-0" value="{{ $row->duration }}" name="duration[]" readonly />
                                                                                </td>
                                                                                <td>

                                                                                    <input type="number" min="0.00" step="0.00001" style="width: 60px;" class="form-control form-control-sm text-center p-0" value="{{ $row->ratePerHour }}" name="rate[]" />

                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" style="width: 60px;" readonly class="form-control form-control-sm text-center p-0" value="{{ $row->amount }}" name="amount[]" />
                                                                                </td>
                                                                                <td>
                                                                                    <p>{{$row->remarks}}</p>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </section>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" class="btn btn-primary" value="UPDATE" />

                                                </form>
                                                <form action="{{ route('supervisoraddpayment') }}" method="POST">
                                                    @csrf
                                                    <input type="text" name="id" value="{{$timekeeper->id}}" style="display:none;" />
                                                    <button type="submit" class="btn btn-success timekeer-btn"> Pay Now </button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <!-- modal address -->

                                <div class="card">
                                    <div id="heading11" class="card-header pb-0" style="background: #ddd;">
                                        <span class="lead collapse-title" style="width:100%">
                                            <div class="row" style="width:100%">

                                                <div class="col-4">
                                                    <p>{{count($timekeepers)}} Employees</p>
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
        </section>
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
</div>




<script>
    $(document).on("click", "#emloyee_wise", function() {
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
    })
    $(document).on("click", "#client_wise", function() {
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
    })
</script>
@push('scripts')

<script>
    function timeToSeconds(time) {
        time = time.split(/:/);
        return time[0] * 3600 + time[1] * 60;
    }

    calculation = function(event) {
        let end = $(this).closest('tr').find("input[name='start_date[]']").val()
        let start = $(this).closest('tr').find("input[name='end_date[]']").val()
        var rate = $(this).closest('tr').find("input[name='rate[]']").val()


        if (start && end) {
            // calculate hours
            var diff = (timeToSeconds(start) - timeToSeconds(end)) / 3600
            if (diff < 0) {
                diff = 24 - Math.abs(diff)
            }
            if (diff) {
                $(this).closest('tr').find("input[name='duration[]']").val(diff);
                if (rate) {
                    $(this).closest('tr').find("input[name='amount[]']").val(parseFloat(rate) * diff);
                }
            }

        } else {
            $(this).closest('tr').find("input[name='duration[]']").val('');
            $(this).closest('tr').find("input[name='amount[]']").val('');
        }
    }
    $('input[name="start_date[]"]').change(function(event) {
        let end = $(this).closest('tr').find("input[name='start_date[]']").val()
        let start = $(this).closest('tr').find("input[name='end_date[]']").val()
        var rate = $(this).closest('tr').find("input[name='rate[]']").val()


        if (start && end) {
            // calculate hours
            var diff = (timeToSeconds(start) - timeToSeconds(end)) / 3600
            if (diff < 0) {
                diff = 24 - Math.abs(diff)
            }
            if (diff) {
                $(this).closest('tr').find("input[name='duration[]']").val(diff);
                if (rate) {
                    $(this).closest('tr').find("input[name='amount[]']").val(parseFloat(rate) * diff);
                }
            }

        } else {
            $(this).closest('tr').find("input[name='duration[]']").val('');
            $(this).closest('tr').find("input[name='amount[]']").val('');
        }
    });
    $('input[name="end_date[]"]').change(function(event) {
        let end = $(this).closest('tr').find("input[name='start_date[]']").val()
        let start = $(this).closest('tr').find("input[name='end_date[]']").val()
        var rate = $(this).closest('tr').find("input[name='rate[]']").val()


        if (start && end) {
            // calculate hours
            var diff = (timeToSeconds(start) - timeToSeconds(end)) / 3600
            if (diff < 0) {
                diff = 24 - Math.abs(diff)
            }
            if (diff) {
                $(this).closest('tr').find("input[name='duration[]']").val(diff);
                if (rate) {
                    $(this).closest('tr').find("input[name='amount[]']").val(parseFloat(rate) * diff);
                }
            }

        } else {
            $(this).closest('tr').find("input[name='duration[]']").val('');
            $(this).closest('tr').find("input[name='amount[]']").val('');
        }
    });
    $('input[name="rate[]"]').on('input', function(event) {
        let end = $(this).closest('tr').find("input[name='start_date[]']").val()
        let start = $(this).closest('tr').find("input[name='end_date[]']").val()
        var rate = $(this).closest('tr').find("input[name='rate[]']").val()


        if (start && end) {
            // calculate hours
            var diff = (timeToSeconds(start) - timeToSeconds(end)) / 3600
            if (diff < 0) {
                diff = 24 - Math.abs(diff)
            }
            if (diff) {
                console.log($(this).closest('tr').find("input[name='duration[]']"))
                $(this).closest('tr').find("input[name='duration[]']").val(diff);
                if (rate) {
                    $(this).closest('tr').find("input[name='amount[]']").val(parseFloat(rate) * diff);
                }
            }

        } else {
            $(this).closest('tr').find("input[name='duration[]']").val('');
            $(this).closest('tr').find("input[name='amount[]']").val('');
        }
    });
</script>
@endpush
@endsection

@section('pdf_generator')
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
@endsection