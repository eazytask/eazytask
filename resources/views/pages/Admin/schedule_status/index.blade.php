@extends('layouts.Admin.master')
@section('title') Schedule @endsection

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1') Dashboard @endslot
        @slot('title') Schedule @endslot
    @endcomponent
    <style>

        #myTable {
            /* width: 1000px !important; */
        }

        .font-small-2 {
            font-size: 0.7rem !important;
        }

        .button-unstyled {
            background: none;
            color: inherit;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-4 mb-1 d-none">
                            <span class="font-weight-bolder float-left mt-1 mr-25 text-capitalize">Search a date:</span>
                            <input type="text" id="search_date" name="search_date"
                                class="form-control format-picker mt-75 form-control-sm float-left text-center bg-light-info"
                                placeholder="dd-mm-yyyy" style="width:135px">
                        </div>
                        {{-- <div class="col-md-8 text-right"> --}}
                        <div class="col-md-12 text-right">

                            <button type="button" class="btn bg-light-secondary mt-1" id="prev"><i
                                    data-feather='arrow-left'></i></button>
                            <button type="button" class="btn bg-light-secondary mt-1"
                                id="currentWeek">{{ \Carbon\Carbon::now()->startOfWeek()->format('d M, Y') }} -
                                {{ \Carbon\Carbon::now()->endOfWeek()->format('d M, Y') }}</button>
                            <button type="button" class="btn bg-light-secondary mt-1" id="next"><i
                                    data-feather='arrow-right'></i></button>

                            <button class="p-0 me-2 button-unstyled mt-1">
                                <select id="project" class="form-control select2"
                                    style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;"
                                    name="project_id" onchange="handleProjectChange(this)">
                                    <option value="">Select Venue</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->pName }}
                                        </option>
                                    @endforeach
                                </select>
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="myTable" class="myTable table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                    <th>Sunday</th>
                                </tr>
                            </thead>
                            <tbody id="tBody">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('pages.Admin.schedule_status.modals.timeKeeperAddModal')
@endsection


@push('scripts')
    @include('components.datatablescript')
    @include('components.select2')
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">

        function handleProjectChange(selectElement) {
            if ($(selectElement).val()) {
                $('#download').prop('disabled', false)
                $('#copyWeek').prop('disabled', false)
            } else {
                $('#download').prop('disabled', true)
                $('#copyWeek').prop('disabled', true)
            }

            searchNow('current')
        }

        $('#prev').on('click', function() {
            searchNow('previous')
        })
        $('#next').on('click', function() {
            searchNow('next')
        })

        // $('#project').on('change', function() {
        //     // alert($(this).val())
        //     if ($(this).val()) {
        //         $('#download').prop('disabled', false)
        //     } else {
        //         $('#download').prop('disabled', true)
        //     }
        //     searchNow('current')
        // })

        function searchNow(goTo = '', search_date = null) {
            $.ajax({
                url: '/admin/home/schedule/status/search',
                type: 'get',
                dataType: 'json',
                data: {
                    'go_to': goTo,
                    'project': $('#project').val(),
                    'search_date': search_date,
                },
                success: function(data) {
                    // $("#myTable").DataTable();
                    if (data.search_date) {
                        $("#search_date").val(moment(data.search_date).format('DD-MM-YYYY'))
                    } else {
                        $("#search_date").val('')
                    }
                    const dataTableTitle = 'Schedule Report';
                    $('#myTable').DataTable().clear().destroy();
                    $('#tBody').html(data.data);
                    $('#myTable').DataTable({
                        dom: 'Bfrtip',
                        paging: false,
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
                                <button class="mb-1 btn btn-primary btn-sm d-inline-block approve" onclick="approveAllFunc()">
                                    Approve All <i data-feather='check-circle'></i>
                                </button>

                                <div class="mb-1 btn bg-light-secondary btn-sm d-inline-block font-weight-bold rounded" id="info_label" >
                                    <span class="mr-1" id="total_hours"></span>
                                    <span class="" id="total_amount"></span>
                                </div>
                                <div class="mb-1 search-box d-inline-block">                
                                    <input type="text" class="form-control form-control-sm search" placeholder="Search for Schedule">
                                    <i class="ri-search-line search-icon"></i>
                                </div>`;
                            $('#myTable_filter').html(search);

                            $('.search').on('keyup', function(){
                                table.search( this.value ).draw();
                            });
                        },
                        autoWidth: false, //step 1
                        columnDefs: [
                            // { width: '140px', targets: 0 }, //step 2, column 1 out of 4
                            {
                                width: '125px',
                                targets: 1
                            }, //step 2, column 2 out of 4
                            {
                                width: '125px',
                                targets: 2
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 3
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 4
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 5
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 6
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 7
                            }, //step 2, column 3 out of 4
                        ]
                        // "bDestroy": true
                    });
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                    $('#currentWeek').text(data.week_date)
                    $('#total_hours').html('Total Hours: ' + data.hours);
                    $('#total_amount').html('Total Amount: $' + data.amount);

                    if (data.notification) {
                        toastr.success(data.notification)
                    }
                },
                error: function(err) {
                    console.log(err)
                }
            });
        }

        // function timekeeperEditFunc() {
        //     if ($("#timekeeperAddForm").valid()) {
        //         $.ajax({
        //             data: $('#timekeeperAddForm').serialize(),
        //             url: "/admin/home/sign/in/status/change",
        //             type: "POST",
        //             dataType: 'json',
        //             success: function(data) {
        //                 console.log(data)
        //                 $("#addTimeKeeper").modal("hide")
        //                 // $("#roasterClick").modal("hide")
        //                 searchNow('current')
        //                 toastr['success']('👋 Update Successfully', 'Success!', {
        //                     closeButton: true,
        //                     tapToDismiss: false,
        //                 });
        //             },
        //             error: function(data) {
        //                 console.log(data)
        //             }
        //         });
        //     }
        // }
    </script>

    <script>
        $(document).ready(function() {
            searchNow()

            $('#search_date').on('change', function() {
                searchNow('search_date', $('#search_date').val())
            })

            $(document).on('show.bs.modal', '.modal', function() {
                const zIndex = 1040 + 10 * $('.modal:visible').length;
                $(this).css('z-index', zIndex);
                setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1)
                    .addClass('modal-stack'));
            });

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
            $(document).on("click", ".editBtn", function() {
                resetValue()
                var rowData = $(this).data("row");

                $("#timepeeper_id").val(rowData.id);
                $("#employee_id").val(rowData.employee_id).trigger('change');
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
                    $('.timekeer-btn').hide();
                } else {
                    $('.timekeer-btn').show();
                }

                $("#app_start").val($.time(rowData.Approved_start_datetime))
                $("#app_end").val($.time(rowData.Approved_end_datetime))

                $("#app_rate").val(rowData.app_rate)
                $("#app_duration").val(rowData.app_duration)
                $("#app_amount").val(rowData.app_amount)
                $("#job").val(rowData.job_type_id).trigger('change');
                // $("#job").val(rowData.job_type_id)
                // $("#roster").val(rowData.roaster_status_id)

                $("#remarks").val(rowData.remarks)

                initAllDatePicker();
                allCalculation()
                $("#addTimeKeeper").modal("show")
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
                $("#job").val('').trigger('change');
                // $("#job").val('')
                // $("#roster").val('')

                $("#remarks").val('')
                $("#project-select").val('');
            }


            timekeeperEditFunc = function() {
                if ($("#timekeeperAddForm").valid()) {
                    $.ajax({
                        data: $('#timekeeperAddForm').serialize(),
                        url: "/admin/home/shift/approve",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $("#addTimeKeeper").modal("hide")
                            // $("#roasterClick").modal("hide")
                            searchNow('current')
                            toastr['success']('👋 Successfully Approved', 'Success!', {
                                closeButton: true,
                                tapToDismiss: false,
                            });
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            }
            approveAllFunc = function() {
                $.ajax({
                    url: "/admin/home/shift/approve/week",
                    type: "get",
                    dataType: 'json',
                    data: {
                        'project': $('#project').val(),
                    },
                    success: function(data) {
                        $("#addTimeKeeper").modal("hide")
                        // $("#roasterClick").modal("hide")
                        searchNow('current')
                        toastr['success']('👋 All Approved Successfully', 'Success!', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            }
        });

        $(window).on('load', function() {
            $(".approve").prop('hidden', false)
        });
    </script>
@endpush