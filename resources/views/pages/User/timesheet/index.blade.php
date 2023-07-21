@extends('layouts.Admin.master')

@php

function getTime($date){
return \Carbon\Carbon::parse($date)->format('H:i');
}

$start_date= null;
$end_date= null;
if(Session::get('fromRoaster') && Session::get('toRoaster')){
$start_date = Session::get('fromRoaster')->format('d-m-Y');
$end_date = Session::get('toRoaster')->format('d-m-Y');
}
@endphp

@section('admincontent')
<style>
    .dt-buttons {
        display: none !important;
    }
</style>

<div class="col-lg-12 col-md-12 d-block p-0">

    <div class="card">
        <div class="card-header">
            <h3>Add Timesheet</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mt-1">
                    <button class="btn btn-outline-primary mt-25 ml-25" type="button" onclick="openModal()">Add New</button>
                </div>
            </div>
        </div>
    </div>
    @include('pages.User.timesheet.modals.timeKeeperAddModal')
    <div class="card p-0">
        <div class="container p-0">
            <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
                <h3 class="card-title text-primary d-inline">
                    Roster Dates
                </h3>
                <span class="float-right">
                    <i class="fa fa-chevron-up clickable"></i>
                </span>
            </div>
            <div class="card-body">

                <form action="/home/timesheet/search" method="POST" id="dates_form">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-md-5 col-lg-4  mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Start Date" value="{{$start_date}}" />
                        </div>
                        <div class="col-md-5 col-lg-4  mt-1">

                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="End Date" value="{{$end_date}}" />
                        </div>
                        <div class="col-md-5 col-lg-4 mt-1">
                            <select class="form-control select2" name="project_id" id="project-select">
                                <option value="">Select Venue</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{Session::get('project_id')==$project->id ?'selected':''}}>{{ $project->pName }}
                                </option>
                                @endforeach
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

    @if(count($timekeepers)>0)
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
                                        <!-- <th>Duration</th> -->
                                        <th>Rate</th>
                                        <!-- <th>Amount</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timekeepers as $k => $row)
                                    @php
                                    $json = json_encode($row->toArray(), false);
                                    @endphp
                                    <tr>
                                        <td class="p-0 pl-50">
                                            @if($row->is_approved)
                                            <i data-feather='check-circle' class="text-primary"></i>
                                            @else
                                            <span class="pl-1 ml-25"></span>
                                            @endif
                                            {{ $k + 1 }}
                                        </td>
                                        <td>
                                            @if (isset($row->project->pName))
                                            {{ $row->project->pName }}
                                            @else
                                            Null
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-y')}}
                                        </td>
                                        <td>{{ getTime($row->shift_start) }}</td>
                                        <td>{{ getTime($row->shift_end) }}</td>
                                        <!-- <td>{{ $row->duration }}</td> -->
                                        <td>{{ $row->ratePerHour }}</td>
                                        <!-- <td>{{ $row->amount }}</td> -->
                                        <td>
                                            <div>
                                                <button data-copy="true" edit-it="true" class="btn edit-btn btn-gradient-primary pl-3 pr-3" data-row="{{ $json }}"><i data-feather='eye'></i></button>
                                            </div>
                                        </td>
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
</div>


@endsection
@push('scripts')
<script>
    $(document).ready(function() {

        $("#newModalForm").validate()

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
            var start = $("#shift_start").val();
            var end = $("#shift_end").val();
            var rate = $("#rate").val();

            if (start && end) {
                // calculate hours
                var diff = (timeToSeconds(end) - timeToSeconds(start)) / 3600
                if (diff < 0) {
                    diff = 24 - Math.abs(diff)
                }
                if (diff) {
                    $("#duration").val(diff);
                    if (rate) {
                        $("#amount").val(parseFloat(rate) * diff);
                    }
                }

            } else {
                $("#duration").val('');
                $("#amount").val('');
            }
        }
        var isValid = true;
        var modalToTarget = document.getElementById("addTimeKeeper");

        function roasterEndTimeInit() {
            $("#shift_end").change(function() {
                allCalculation()
            });

        }
        roasterEndTimeInit()

        function roasterStartTimeInit() {
            $("#shift_start").change(function() {
                if ($(this).val()) {
                    $("#shift_end").removeAttr("disabled")
                } else {
                    $("#shift_end").prop('disabled', true);
                }

                allCalculation()
            });

        }
        roasterStartTimeInit()

        const initDatePicker = () => {
            $("#roaster_date").change(function() {
                if ($(this).val()) {
                    $("#shift_start").removeAttr("disabled")
                } else {
                    $(".picker__button--clear").removeAttr("disabled")

                    $(".picker__button--clear")[1].click()
                    $(".picker__button--clear")[2].click()

                    $("#shift_start").prop('disabled', true)

                    $("#shift_end").prop('disabled', true);
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

        $(document).on("input", ".reactive", function() {
            allCalculation()
        })

        openModal = function() {
            resetValue()
            $('#newModalForm').attr('action', "{{ route('store-timesheet') }}");
            // $(".timekeer-btn").html('Add')
            $('#userAddTimeKeeper').modal('show')
            $('#deleteBtn').hide()
            $('#editBtn').hide()
            $('#copyBtn').hide()
            $('#addBtn').show()
            $('#updateBtn').hide()
        }

        $(document).on("click", ".edit-btn", function() {
            resetValue()
            var rowData = $(this).data("row");
            window.is_approved=rowData.is_approved

            $("#timekeeper_id").val(rowData.id);
            $("#roaster_date").val(moment(rowData.roaster_date).format('DD-MM-YYYY'))
            $("#shift_start").val($.time(rowData.shift_start))
            $("#shift_end").val($.time(rowData.shift_end))
            $("#project_id").val(rowData.project_id).trigger('change');

            // $("#shift_start").val($.time(rowData.shift_start))
            // $("#shift_end").val($.time(rowData.shift_end))

            $("#shift_start").removeAttr("disabled")
            $("#shift_end").removeAttr("disabled")


            $("#rate").val(rowData.ratePerHour)
            $("#duration").val(rowData.duration)
            $("#amount").val(rowData.amount)
            $("#job").val(rowData.job_type_id).change()

            $("#url").val('/home/timesheet/delete/' + rowData.id)
            // $("#roster").val(rowData.roaster_status_id)

            $("#remarks").val(rowData.remarks)

            if (rowData.is_approved==1) {
                $('#deleteBtn').hide()
                $('#updateBtn').hide()
            } else {
                $('#newModalForm').attr('action', "{{ route('update-timesheet') }}");
                $('#deleteBtn').show()
                $('#updateBtn').show()
            }
            $('#addBtn').hide()
            $('#copyBtn').show()
            $('#editBtn').hide()
            // $(".timekeer-btn").html('Update')


            $("#userAddTimeKeeper").modal("show")

            initAllDatePicker();
            allCalculation()

        })

        $(document).on("click", "#copyBtn", function() {
            $('#newModalForm').attr('action', "{{ route('store-timesheet') }}");
            // $(".timekeer-btn").html('Add')
            // $('#userAddTimeKeeper').modal('show')
            if (window.is_approved==1) {
                $('#editBtn').hide()
            } else {
                $('#editBtn').show()
            }
            $('#deleteBtn').hide()
            $('#copyBtn').hide()
            $('#addBtn').show()
            $('#updateBtn').hide()
        })
        $(document).on("click", "#editBtn", function() {
            $('#newModalForm').attr('action', "{{ route('update-timesheet') }}");
            // $(".timekeer-btn").html('Add')
            // $('#userAddTimeKeeper').modal('show')
            $('#deleteBtn').show()
            $('#editBtn').hide()
            $('#copyBtn').show()
            $('#addBtn').hide()
            $('#updateBtn').show()
        })
        $(document).on("click", "#deleteBtn", function() {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location = $('#url').val()
                    }
                });
        })

        function resetValue() {
            $('#deleteBtn').show()
            $('#editBtn').show()
            $('#copyBtn').show()
            $('#addBtn').hide()

            $('#timekeeper_id').attr('value', '');
            $("#employee_id").val('');

            $("#roaster_date").val('')
            $("#shift_start").val('')
            $("#shift_end").val('')

            $("#rate").val('')
            $("#duration").val('')
            $("#amount").val('')
            // $("#job").val('')

            $("#remarks").val('')

            $('#newModalForm').attr('action', "{{ route('store-timesheet') }}");
            // $(".timekeer-btn").html('Submit')
            $("#project_id").val('').change();
        }
    });
</script>
@endpush