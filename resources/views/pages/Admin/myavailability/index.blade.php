@php
    $status_ = [
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
    ];

    $start_date = '';
    $end_date = '';
    $session_start = Session::get('availity_start_date');
    $session_end = Session::get('availity_end_date');

    if ($session_start) {
        $start_date = \Carbon\Carbon::parse($session_start)->format('d-m-Y');
    } else {
        $start_date = \Carbon\Carbon::now()
            ->startOfYear()
            ->format('d-m-Y');
    }

    if ($session_end) {
        $end_date = \Carbon\Carbon::parse($session_end)->format('d-m-Y');
    } else {
        $end_date = \Carbon\Carbon::now()->format('d-m-Y');
    }

@endphp
@extends('layouts.Admin.master')

@section('admincontent')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">My Availavility</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">My Availavility Lists
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Basic Tables start -->
    <!-- Table Hover Animation start -->
    <div class="row" id="table-hover-animation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" id="add"><i data-feather='plus'></i></button>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            Opss! There have an error!
                        </div>
                    @endif
                </div>
                @include('pages.Admin.myavailability.modals.AddModal')

                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $session_start ? '' : 'active' }}" id="home-tab" data-toggle="tab"
                                href="#home" aria-controls="home" role="tab" aria-selected="true">Current
                                unavailability days</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $session_start ? 'active' : '' }}" id="profile-tab" data-toggle="tab"
                                href="#profile" aria-controls="profile" role="tab" aria-selected="false">See total
                                unavailability days</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{ $session_start ? '' : 'active' }}" id="home" aria-labelledby="home-tab"
                            role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover-animation table-bordered mb-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Total</th>
                                            <th>Unavailable Type</th>
                                            <th>Unavailable Reason</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $row)
                                            @php
                                                $json = json_encode($row->toArray(), false);
                                                $emp = $row->employee;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->start_date)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->end_date)->format('d-m-Y') }}</td>
                                                <td>{{ $row->total }}</td>
                                                <td>{{ $row->leave_type ? $row->leave_type->name : 'unspecified' }}</td>
                                                <td>{{ $row->remarks }}</td>
                                                <td>
                                                    <p class="badge badge-light-{{ $status_[$row->status] }}">
                                                        {{ $row->status }}</p>
                                                </td>
                                                <td>
                                                    @if (!($row->start_date <= \Carbon\Carbon::now()))
                                                        <button class="btn edit-btn btn-gradient-primary"
                                                            data-row="{{ $json }}"><i
                                                                data-feather='edit'></i></button>
                                                        <a class="btn btn-gradient-success text-white del mt-lg-25"
                                                            url="/admin/home/myavailability/approve/{{ $row->id }}"><i
                                                                data-feather='check-circle'></i></a>
                                                        <a class="btn btn-gradient-danger text-white del mt-lg-25"
                                                            url="/admin/home/myavailability/delete/{{ $row->id }}"><i
                                                                data-feather='trash-2'></i></a>
                                                    @elseif($row->status == 'pending')
                                                        <!-- <p class="badge badge-light-primary p-75 m-0">running</p> -->
                                                        <a class="btn btn-gradient-success text-white del mt-lg-25"
                                                            url="/admin/home/myavailability/approve/{{ $row->id }}"><i
                                                                data-feather='check-circle'></i></a>
                                                        <a class="btn btn-gradient-danger text-white del mt-lg-25"
                                                            url="/admin/home/myavailability/delete/{{ $row->id }}"><i
                                                                data-feather='trash-2'></i></a>
                                                    @else
                                                        {{-- <p class="badge badge-light-primary p-75">running</p> --}}
                                                        <a class="btn btn-gradient-danger text-white del mt-lg-25"
                                                            url="/admin/home/myavailability/delete/{{ $row->id }}"><i
                                                                data-feather='trash-2'></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach



                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane {{ $session_start ? 'active' : '' }}" id="profile"
                            aria-labelledby="profile-tab" role="tabpanel">
                            <div class="card-header pb-0 pt-0">
                                <h4>All approved Unavailable days</h4>

                                <div>
                                    <form action="{{ route('availability.search') }}" method="POST" id="dates_form">
                                        @csrf
                                        <div class="row row-xs">
                                            <div class="col-lg-4">
                                                <input type="text" name="start_date" required
                                                    class="form-control format-picker" placeholder="From"
                                                    value="{{ $start_date }}" />
                                            </div>
                                            <div class="col-lg-4 mt-25 mt-md-0 ">
                                                <input type="text" name="end_date" required
                                                    class="form-control format-picker" placeholder="To"
                                                    value="{{ $end_date }}" />
                                            </div>
                                            <div class="col-md-2 col-lg-3 mt-25 mt-md-0">
                                                <button type="submit" class="btn btn btn-outline-primary btn-block"
                                                    id="btn_search"><i data-feather='search'></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-4">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Employee Name</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($total_employee as $row)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $row->fname }} {{ $row->mname }} {{ $row->lname }}
                                                        </td>
                                                        <td>{{ $row->total_day }} days</td>
                                                        <td>
                                                            <a class="btn bg-gradient-primary" href="#"
                                                                data-toggle="modal"
                                                                data-target="#detailModal{{ $row->id }}">Details</a>
                                                            @include('pages.Admin.myavailability.modals.DetailsModal')
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr class="font-weight-bolder">
                                                    <td></td>
                                                    <td>Total Unavailable day</td>
                                                    <td>{{ $total_employee->sum('total_day') }} days</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Table head options end -->
    <!-- Basic Tables end -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('.select2').select2({
                dropdownParent: $('#addModal')
            });
            $('#leave_type_id').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Leave-type',
                dropdownParent: $('#leave_type_id').parent()
            });

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

            $("#newModalForm").validate()

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
                    let diff = (diffInMs / (1000 * 60 * 60 * 24));

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

            $(document).on("click", "#add", function() {
                resetValue()
                $("#addModal").modal("show")
            })

            $(document).on("click", ".edit-btn", function() {
                resetValue()
                var rowData = $(this).data("row");

                $("#id").val(rowData.id);

                $("#employee_id").val(rowData.employee_id).trigger('change');
                $("#start_date").val(moment(rowData.start_date).format('DD-MM-YYYY'))
                $("#end_date").val(moment(rowData.end_date).format('DD-MM-YYYY'))
                $("#leave_type_id").val(rowData.leave_type_id).trigger('change');
                $("#status").val(rowData.status).trigger('change');
                $("#remarks").val(rowData.remarks)
                $("#total").val(rowData.total)

                $('#newModalForm').attr('action', "/admin/home/myavailability/update");
                $("#savebtn").hide()
                $("#updatebtn").show()

                $("#addModal").modal("show")
            })

            function resetValue() {
                $("#id").val('');
                $("#employee_id").val('').trigger('change');
                $("#start_date").val('')
                $("#end_date").val('')
                $("#total").val('')
                $("#leave_type_id").val('').trigger('change');
                $("#status").val('pending').trigger('change');
                $("#remarks").val('')

                $('#newModalForm').attr('action', "/admin/home/myavailability/store");
                $("#savebtn").show()
                $("#updatebtn").hide()
            }

        })
    </script>
@endpush
