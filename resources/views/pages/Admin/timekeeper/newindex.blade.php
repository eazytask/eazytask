@extends('layouts.Admin.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <style>
        .demo-inline-spacing {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: center;
        }
        .demo-inline-spacing>* {
            margin-right: 1.5rem;
            margin-top: 1.5rem;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/vendors.min.css')}}">
@endpush

@php
    function getTime($date){
        return \Carbon\Carbon::parse($date)->format('H:i');
    }

    $fromRoaster="Start Date";
    $toRoaster = "End Date";
    if(Session::get('fromRoaster')){
        $fromRoaster = \Carbon\Carbon::parse(Session::get('fromRoaster'))->format('d-m-Y');
    }
    if(Session::get('toRoaster')){
        $toRoaster = \Carbon\Carbon::parse(Session::get('toRoaster'))->format('d-m-Y');
    }
@endphp
@section('admin_page_content')

    @component('components.breadcrumb')
        @slot('li_1')
            Timesheet
        @endslot
        @slot('title')
            Add Timesheet
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-info" id="createShedule" disabled>
                        Add Timesheet
                    </button>
                    @include('pages.Admin.timekeeper.modals.newtimeKeeperAddModal')
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('search-timekeeper') }}" method="POST" id="dates_form">
                        @csrf
                        <div class="row g-2">
                            <div class="col-lg-4">
                                <input type="text" name="start_date" required class="form-control disable-picker" placeholder="{{$fromRoaster}}" />
                            </div>
                            <div class="col-lg-4">
                                <input type="text" name="end_date" required class="form-control disable-picker" placeholder="{{$toRoaster}}" />
                            </div>
                            <div class="col-md-2 col-lg-3">
                                <button type="submit" class="btn btn-primary btn-block" id="btn_search">
                                    Search Timesheet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mytable" class="table table-hover-animation table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <!-- <th>Client</th> -->
                                    <th>Venue</th>
                                    <th>Roster Date</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Duration</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Job Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timekeepers as $k => $row)
                                @php
                                $json = json_encode($row->toArray(), false);

                                @endphp
                                <tr data-copy="true" data-row="{{ $json }}">
                                    <td>
                                        @if($row->is_approved)
                                        <i data-feather="{{$row->payment_status?'dollar-sign':'check-circle'}}" class="text-primary"></i>
                                        @else
                                        <span class="pl-1 ml-25"></span>
                                        @endif
                                        {{ $k + 1 }}
                                    </td>
                                    <td>
                                        {{ $row->employee->fname }} {{ $row->employee->mname }} {{ $row->employee->lname }}

                                    </td>
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
                                        {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                    </td>
                                    <td>{{ getTime($row->shift_start) }}</td>
                                    <td>{{ getTime($row->shift_end) }}</td>
                                    <td>{{ $row->duration }}</td>
                                    <td>{{ $row->ratePerHour }}</td>
                                    <td>{{ $row->amount }}</td>
                                    <td>{{ $row->job_type->name }}</td>
                                    <td>
                                        <div class="row">
                                            <!--<a href="#" data-toggle="modal" data-target="#editTimeKeeper{{$row->id}}"><i data-feather='edit'></i></a>-->
                                            @if($row->is_approved == 0)
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-soft-info btn-sm" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button data-copy="true" edit-it="true" class="edit-btn btn-link btn dropdown-item" data-employee="{{$row->employee}}" data-row="{{ $json }}">
                                                                Edit
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button data-copy="true" class="edit-btn btn-link btn dropdown-item" data-row="{{ $json }}">
                                                                Copy
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <a class="del dropdown-item" url="/admin/home/new/timekeeper/delete/{{ $row->id }}">
                                                                Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @else
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-soft-info btn-sm" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button data-copy="true" edit-it="true" class="edit-btn dropdown-item" data-employee="{{$row->employee}}" data-row="{{ $json }}">View</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
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
@endsection

@push('scripts')
    @include('components.datatablescript')
    @include('components.select2')
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
    <script src="{{asset('app-assets/velzon/libs/moment/moment.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#createShedule').removeAttr('disabled');

            const dataTableTitle = 'Add Timesheet Report';
            const dataTableOptions = {
                dom: 'Bfrtip',
                paging: false,
                columnDefs: [{
                    targets: [9],
                    visible: false
                }],
                buttons: [
                    {
                        extend: 'colvis',
                        fade: 0,
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: ':visible'
                        },
                        title: dataTableTitle,
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible'
                        },
                        title: dataTableTitle,
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        },
                        title: dataTableTitle,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible',
                        },
                        title: dataTableTitle,
                    }
                ],
                initComplete: function() {
                    let table = this.api();
                    let search = `
                        <div class="search-box d-inline-block">
                            <input type="text" class="form-control form-control-sm search" placeholder="Search for Timesheet">
                            <i class="ri-search-line search-icon"></i>
                        </div>`;
                    $('#mytable_filter').html(search);

                    $('.search').on('keyup', function(){
                        table.search( this.value ).draw();
                    });
                }
            }
            $('#mytable').DataTable(dataTableOptions);
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
                    },
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
                });
            });
            $('#project-select').on('change', function() {
                filterEmployee()
                if ($(this).val()) {
                    $('#inducted').prop('disabled', false)
                } else {
                    $('#inducted').prop('disabled', true)
                }
            })

            $('input[name="filter_employee"]').on('change', function() {
                filterEmployee()
            })

            function filterEmployee() {
                $.ajax({
                    url: '/admin/home/filter/employee',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'filter': $('input[name="filter_employee"]:checked').val(),
                        'project_id': $("#project-select").val(),
                        'roster_date': $("#roaster_date").val(),
                        'shift_start': $("#shift_start").val(),
                        'shift_end': $("#shift_end").val(),
                    },
                    success: function(data) {
                        // console.log(data)
                        let html = '<option value="">please choose...</option>'
                        if (emp = window.current_emp) {
                            html += "<option value='" + emp.id + "' selected>" + emp.fname + " " + ((emp.mname) ? emp.mname : '') + "" + emp.lname + "</option>"
                        }
                        jQuery.each(data.employees, function(i, val) {
                            html += "<option value='" + val.id + "'>" + val.fname + " " + ((val.mname) ? val.mname : '') + "" + val.lname + "</option>"
                        })
                        // console.log(html)
                        $('#employee_id').html(html)
                        if (data.notification) {
                            toastr.success(data.notification)
                        }
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
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
                filterEmployee()
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
                    filterEmployee()
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

            // const initAllDatePicker = () => {
            //     initDatePicker();
            //     roasterStartTimeInit();
            //     roasterEndTimeInit();
            // }

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

            $(document).on('dblclick', '#mytable tbody tr', edit_or_copy)
            $(document).on("click", ".edit-btn", edit_or_copy)
            function edit_or_copy(){
                resetValue()
                window.current_emp = $(this).data("employee")
                var rowData = $(this).data("row");

                // window.roaster_date = rowData.roaster_date;
                // window.shift_start = rowData.shift_start;
                // window.shift_end = rowData.shift_end;

                if (!$(this).data("copy"))
                    $("#timepeeper_id").val(rowData.id);
                $('#timepeeper_id').attr('value', rowData.id);

                if(rowData.is_approved ==1){
                    $('.timekeer-btn').prop('hidden', true);
                }else{
                    $('.timekeer-btn').prop('hidden', false);
                }

                $("#employee_id").val(rowData.employee_id).trigger('change');
                // $("#client-select").val(rowData.client_id).trigger('change').trigger('change');

                $("#roaster_date").val(moment(rowData.roaster_date).format('DD-MM-YYYY'))
                $("#shift_start").val($.time(rowData.shift_start))
                $("#shift_end").val($.time(rowData.shift_end))
                $("#project-select").val(rowData.project_id).trigger('change');

                // $("#shift_start").val($.time(rowData.shift_start))
                // $("#shift_end").val($.time(rowData.shift_end))

                $("#shift_start").removeAttr("disabled")
                $("#shift_end").removeAttr("disabled")


                $("#rate").val(rowData.ratePerHour)
                $("#duration").val(rowData.duration)
                $("#amount").val(rowData.amount)
                $("#job").val(rowData.job_type_id).trigger('change')
                // $("#roster").val(rowData.roaster_status_id)

                $("#remarks").val(rowData.remarks)

                var update_it = $(this).attr('edit-it');
                if (update_it) {
                    $('#newModalForm').attr('action', "{{ route('update-new-timekeeper') }}");
                    $(".timekeer-btn").html('Update')
                }

                $("#addTimeKeeper").modal("show")

                // initAllDatePicker();
                allCalculation()

            }

            $(document).on("input", ".reactive", function() {
                allCalculation()
            })

            $(document).on("click", "#createShedule", function() {
                resetValue()
                $("#project-select").val('').trigger('change');
                $("#addTimeKeeper").modal("show")
            })

            function resetValue() {
                window.current_emp = ''
                $("#timepeeper_id").val();
                $('#timepeeper_id').attr('value', '');
                $("#employee_id").val('').trigger('change');
                // $("#client-select").val('').trigger('change');

                $("#roaster_date").val('')
                $("#shift_start").val('')
                $("#shift_end").val('')

                $("#rate").val('')
                $("#duration").val('')
                $("#amount").val('')
                // $("#job").val('').trigger('change')
                // $("#roster").val('')

                $("#remarks").val('')

                $('#newModalForm').attr('action', "{{ route('store-new-timekeeper') }}");
                $('.timekeer-btn').prop('hidden', false);
                $(".timekeer-btn").html('Submit')
            }

        })
    </script>
@endpush

<!-- @section('pdf_generator')
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
@endsection -->
