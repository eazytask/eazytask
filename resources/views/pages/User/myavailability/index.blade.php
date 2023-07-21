@php
    $status_ = [
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
    ];
    
@endphp

@extends('layouts.Admin.master')
@section('admincontent')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">My Time Off</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/home">Home</a>
                            </li>
                            <li class="breadcrumb-item active">My Time Off Lists
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Basic Tables start -->
    <!-- Table Hover Animation start -->
    <div class="row" id="">
        <div class="col-12">

            <div class="">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" onclick="changeMode('availabity')" id="home-tab" data-toggle="tab"
                            href="#home" aria-controls="home" role="tab" aria-selected="true">Unavailability</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="changeMode('leave')" id="profile-tab" data-toggle="tab" href="#profile"
                            aria-controls="profile" role="tab" aria-selected="false">Leave Day</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
                        <div class="card">

                            <div class="card-header">
                                <button class="btn btn-primary" href="#" onclick="openModal()">Add
                                    Unavailable</button>
                                {{-- <h4 class="bg-light-primary p-1 badge">Total Unavailable: --}}
                                {{-- {{ $data->where('status', 'approved')->sum('total') }} Days</h4> --}}
                            </div>

                            <div class="card-body pt-0">
                                <div class="container">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-4">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <!-- <th>Company Name</th> -->
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Total</th>
                                                    <th>Leave Type</th>
                                                    <th>Leave Reason</th>
                                                    <th>status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $row)
                                                    @php
                                                        $json = json_encode($row->toArray(), false);
                                                    @endphp
                                                    <tr
                                                        class="{{ $row->start_date <= \Carbon\Carbon::now() ? 'text-black-50' : '' }}">
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <!-- <td>{{ Auth::user()->employee->company }}</td> -->
                                                        <td>{{ \Carbon\Carbon::parse($row->start_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($row->end_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ $row->total }}</td>
                                                        <td>{{ $row->leave_type ? $row->leave_type->name : 'unspecified' }}
                                                        </td>
                                                        <td>{{ $row->remarks }}</td>
                                                        <td>
                                                            <p class="badge badge-light-{{ $status_[$row->status] }}">
                                                                {{ $row->status }}</p>
                                                        </td>
                                                        <td>
                                                            @if ($row->start_date < \Carbon\Carbon::now()->toDateString())
                                                                <p class="badge badge-light-success p-75">Ended</p>
                                                            @elseif($row->status == 'approved')
                                                                <p class="badge badge-light-primary p-75">approved</p>
                                                            @elseif(
                                                                $row->start_date >=
                                                                    \Carbon\Carbon::now()->addDay(7)->toDateString())
                                                                <button data-copy="true" edit-it="true"
                                                                    class="btn edit-btn btn-gradient-primary"
                                                                    data-row="{{ $json }}"><i
                                                                        data-feather='edit'></i></button>
                                                                <a url="/myavailability/delete/{{ $row->id }}"
                                                                    class="btn btn-gradient-danger text-white del mt-md-25"><i
                                                                        data-feather='trash-2'></i></a>
                                                            @else
                                                                @if ($row->status == 'pending')
                                                                    <!-- <a url="/myavailability/delete/{{ $row->id }}" class="btn btn-gradient-danger text-white del mt-md-25"><i data-feather='trash-2'></i></a> -->
                                                                    <p class="badge badge-light-primary p-75">Pending</p>
                                                                @else
                                                                    <p class="badge badge-light-primary p-75">Running</p>
                                                                @endif
                                                            @endif
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

                    <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary" type="button" onclick="openModal()">Add Leave</button>
                                {{-- <h4 class="bg-light-primary p-1 badge">Total Leave: --}}
                                {{-- {{ $data->where('status', 'approved')->sum('total') }} Days</h4> --}}
                            </div>

                            <div class="card-body">
                                <div class="container">
                                    <div class="table-responsive">
                                        <table class="table  table-bordered mb-4">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <!-- <th>Company Name</th> -->
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Total</th>
                                                    <th>Leave Type</th>
                                                    <th>Leave Reason</th>
                                                    <th>status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($leaves as $row)
                                                    @php
                                                        $json = json_encode($row->toArray(), false);
                                                    @endphp
                                                    <tr
                                                        class="{{ $row->start_date <= \Carbon\Carbon::now() ? 'text-black-50' : '' }}">
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <!-- <td>{{ Auth::user()->employee->company }}</td> -->
                                                        <td>{{ \Carbon\Carbon::parse($row->start_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($row->end_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ $row->total }}</td>
                                                        <td>{{ $row->leave_type ? $row->leave_type->name : 'unspecified' }}
                                                        </td>
                                                        <td>{{ $row->remarks }}</td>
                                                        <td>
                                                            <p class="badge badge-light-{{ $status_[$row->status] }}">
                                                                {{ $row->status }}</p>
                                                        </td>
                                                        <td>
                                                            @if ($row->start_date < \Carbon\Carbon::now()->toDateString())
                                                                <p class="badge badge-light-success p-75">Ended</p>
                                                            @elseif($row->status == 'approved')
                                                                <p class="badge badge-light-primary p-75">approved</p>
                                                            @elseif(
                                                                $row->start_date >=
                                                                    \Carbon\Carbon::now()->addDay(7)->toDateString())
                                                                <button data-copy="true" edit-it="true"
                                                                    class="btn edit-btn btn-gradient-primary"
                                                                    data-row="{{ $json }}"><i
                                                                        data-feather='edit'></i></button>
                                                                <a url="/myavailability/delete/{{ $row->id }}"
                                                                    class="btn btn-gradient-danger text-white del mt-md-25"><i
                                                                        data-feather='trash-2'></i></a>
                                                            @else
                                                                @if ($row->status == 'pending')
                                                                    <!-- <a url="/myavailability/delete/{{ $row->id }}" class="btn btn-gradient-danger text-white del mt-md-25"><i data-feather='trash-2'></i></a> -->
                                                                    <p class="badge badge-light-primary p-75">Pending</p>
                                                                @else
                                                                    <p class="badge badge-light-primary p-75">Running</p>
                                                                @endif
                                                            @endif
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

                @include('pages.User.myavailability.modals.AddModal')
            </div>
        </div>
        <!-- Table head options end -->
        <!-- Basic Tables end -->
        <script>
            $(document).ready(function() {
                let currentMode = 'availabity'
                changeMode = function(x) {
                    currentMode = x
                }

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
                $("#availabilityForm").validate()

                function myDateFormat(time) {
                    time = time.split('-');
                    return time[2] + '/' + time[1] + '/' + time[0];
                }

                allCalculation = function() {
                    var start = myDateFormat($("#start_date").val());
                    var end = myDateFormat($("#end_date").val());

                    if (start && end) {
                        // calculate hours
                        const diffInMs = new Date(end) - new Date(start)
                        let diff = diffInMs / (1000 * 60 * 60 * 24);
                        if (diff >= 0) {
                            diff = diff + 1
                        } else {
                            diff = diff - 1
                        }
                        if (diff) {
                            $("#total").val(diff);
                        }

                    } else {
                        $("#total").val('');
                    }
                }

                openModal = function() {
                    resetValue()
                    $("#add").modal("show")
                }
                $(document).on("click", ".edit-btn", function() {
                    resetValue()
                    var rowData = $(this).data("row");

                    $("#id").val(rowData.id);
                    $("#start_date").val(moment(rowData.start_date).format('DD-MM-YYYY'))
                    $("#end_date").val(moment(rowData.end_date).format('DD-MM-YYYY'))
                    $("#leave_type_id").val(rowData.leave_type_id);
                    $("#remarks").val(rowData.remarks)
                    $("#total").val(rowData.total)

                    if (currentMode == 'availabity') {
                        $('#availabilityForm').attr('action', "{{ route('myAvailability.update') }}");
                    } else {
                        $('#availabilityForm').attr('action', "{{ route('leave.update') }}");
                    }
                    $('#addBtn').hide()
                    $('#updateBtn').show()

                    $("#add").modal("show")
                })

                function resetValue() {
                    $('#addBtn').show()
                    $('#updateBtn').hide()

                    $("#id").val('');
                    $("#start_date").val('')
                    $("#end_date").val('')
                    $("#total").val('')
                    $("#leave_type_id").val('');
                    $("#remarks").val('')

                    if (currentMode == 'availabity') {
                        $('#myModalLabel17').html('Add Unavailability')
                        $('#availabilityForm').attr('action', "{{ route('myAvailability.store') }}");
                    } else {
                        $('#myModalLabel17').html('Add Leave')
                        $('#availabilityForm').attr('action', "{{ route('leave.store') }}");
                    }
                }
            });
        </script>
    @endsection
