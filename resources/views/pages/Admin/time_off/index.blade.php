@php
    $status_ = [
        'pending' => 'warning',
        'approved' => 'success',
        'reject' => 'danger',
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

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Employee
        @endslot
        @slot('title')
            Time Off
        @endslot
    @endcomponent
    @component('components.employeeTab')
        @slot('active') time_off @endslot
    @endcomponent
    <div>
        <div class="card">
            <div class="card-header">
                <button class="btn btn-info" id="add" disabled>
                    <i class="ri-add-fill me-1 align-bottom"></i> Add Time Off
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        Opss! There have an error!
                    </div>
                @endif
            </div>
        </div>
        @include('pages.Admin.time_off.modals.AddModal')
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-custom nav-primary mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $session_start ? '' : 'active' }}" id="home-tab" data-bs-toggle="tab"
                            href="#home" aria-controls="home" role="tab" aria-selected="true">Current
                            time off days</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $session_start ? 'active' : '' }}" id="profile-tab" data-bs-toggle="tab"
                            href="#profile" aria-controls="profile" role="tab" aria-selected="false">See total
                            time off days</a>
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
                                        <th>Type</th>
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
                                                <p class="badge badge-light-{{ $status_[$row->status] }}">{{ $row->status }}</p>
                                            </td>
                                            <td>
                                                @if($row->is_leave)
                                                    Leave
                                                @else
                                                    Unavailabile
                                                @endif
                                            </td>
                                            <td>
                                                @if (!($row->start_date <= \Carbon\Carbon::now()))
                                                    <div class="dropdown">
                                                        <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <button class="dropdown-item edit-btn" data-row="{{ $json }}">
                                                                    Edit
                                                                </button>
                                                            </li>
                                                            <li> 
                                                                <a class="dropdown-item del" url="/admin/home/myavailability/approve/{{ $row->id }}">
                                                                    Approved
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item del" url="/admin/home/myavailability/delete/{{ $row->id }}">
                                                                    Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @elseif($row->status == 'pending')
                                                    <!-- <p class="badge badge-light-primary p-75 m-0">running</p> -->

                                                    <div class="dropdown">
                                                        <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item del"
                                                                url="/admin/home/myavailability/approve/{{ $row->id }}">
                                                                    Approve
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item del"
                                                                url="/admin/home/myavailability/delete/{{ $row->id }}">
                                                                    Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @else
                                                    {{-- <p class="badge badge-light-primary p-75">running</p> --}}

                                                    <div class="dropdown">
                                                        <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item del" url="/admin/home/myavailability/delete/{{ $row->id }}">
                                                                    Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
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

                        <div class="card">
                            <div class="card-header">
                                <div class="row justify-content-between align-items-center">
                                    <h5 class="col-md-4">All approved time off days</h5>
        
                                    <div class="col-md-6">
                                        <form action="{{ route('availability.search') }}" method="POST" id="dates_form">
                                            @csrf
                                            <div class="row g-2">
                                                <div class="col-lg-5 mt-2 mt-md-0">
                                                    <input type="text" name="start_date" required
                                                        class="form-control format-picker" placeholder="From"
                                                        value="{{ $start_date }}" />
                                                </div>
                                                <div class="col-lg-5 mt-2 mt-md-0 ">
                                                    <input type="text" name="end_date" required
                                                        class="form-control format-picker" placeholder="To"
                                                        value="{{ $end_date }}" />
                                                </div>
                                                <div class="col-md-2 col-lg-2 mt-2 mt-md-0">
                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn btn-primary btn-block" id="btn_search">
                                                            Search
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                                                        <div class="dropdown">
                                                            <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailModal{{ $row->id }}">Details</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        @include('pages.Admin.time_off.modals.DetailsModal')
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
@endsection

@push('scripts')
    @include('components.select2')
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
    <script src="{{asset('app-assets/velzon/libs/moment/moment.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#add').removeAttr('disabled');
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

            $("#newModalForm").validate({
                errorPlacement: function (error, element) {
                    if(element.hasClass('select2') && element.next('.select2-container').length) {
                        error.insertAfter(element.next('.select2-container'));
                    } else if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    }
                    else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                        error.insertAfter(element.parent().parent());
                    }
                    else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                        error.appendTo(element.parent().parent());
                    }
                    else {
                        error.insertAfter(element);
                    }
                }
            })

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
                $('#is_leave').val(rowData.is_leave).trigger('change');

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
                $('#is_leave').val('').trigger('change');
                $('#newModalForm').attr('action', "/admin/home/myavailability/store");
                $("#savebtn").show()
                $("#updatebtn").hide()
            }

        })
    </script>
@endpush
