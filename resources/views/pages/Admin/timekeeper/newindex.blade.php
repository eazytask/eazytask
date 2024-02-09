@extends('layouts.Admin.master')
@section('title') Timesheet @endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('app-assets/velzon/libs/gridjs/theme/mermaid.min.css') }}">
@endsection

@php
    function getTime($date) {
        return \Carbon\Carbon::parse($date)->format('H:i');
    }

    $fromRoaster="Start Date";
    $toRoaster = "End Date";
    
    if(Session::get('fromRoaster')) {
        $fromRoaster = \Carbon\Carbon::parse(Session::get('fromRoaster'))->format('d-m-Y');
    }

    if(Session::get('toRoaster')) {
        $toRoaster = \Carbon\Carbon::parse(Session::get('toRoaster'))->format('d-m-Y');
    }
@endphp

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1') Timesheet @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <div class="card">
        <div class="card-body p-5">
            <div class="row g-4">
                <div class="col-xxl-12">
                    <form action="{{ route('search-timekeeper') }}" method="POST" id="dates_form">
                        @csrf
                        <div class="row g-4">
                            <div class="col-xxl-5 col-md-5">
                                <label class="form-label mb-0">Start Date:</label>
                                <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-deafult-date="{{ $fromRoaster }}" required name="start_date">
                            </div>
            
                            <div class="col-xxl-5 col-md-5">
                                <label class="form-label mb-0">End Date:</label>
                                <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-deafult-date="{{ $toRoaster }}" required name="end_date">
                            </div>
            
                            <div class="col-xxl-2 col-md-2">
                                <label class="form-label mb-0">Search:</label>
                                <div>
                                    <button type="submit" class="btn btn-sm btn-primary w-100" id="btn_search"><i data-feather='search'></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-xxl-12">
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('app-assets/velzon/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('app-assets/velzon/libs/gridjs/gridjs.umd.js') }}"></script>

    <script>
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

            $(document).on("click", ".edit-btn", function() {
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

            })


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

    <script>
        const timeKeepers = '<?php echo $timekeepers; ?>';

        console.log(timeKeepers)

        if (document.getElementById("table-gridjs")) {
                new gridjs.Grid({
                columns: [{
                        name: 'ID',
                        width: '80px',
                        formatter: (function (cell) {
                            return gridjs.html('<span class="fw-semibold">' + cell + '</span>');
                        })
                    },
                    {
                        name: 'Name',
                        width: '150px',
                    },
                    {
                        name: 'Email',
                        width: '220px',
                        formatter: (function (cell) {
                            return gridjs.html('<a href="">' + cell + '</a>');
                        })
                    },
                    {
                        name: 'Position',
                        width: '250px',
                    },
                    {
                        name: 'Company',
                        width: '180px',
                    },
                    {
                        name: 'Country',
                        width: '180px',
                    },
                    {
                        name: 'Actions',
                        width: '150px',
                        formatter: (function (cell) {
                            return gridjs.html("<a href='#' class='text-reset text-decoration-underline'>" +
                                "Details" +
                                "</a>");
                        })
                    },
                ],
                pagination: {
                    limit: 5
                },
                sort: true,
                search: true,
                data: [
                    ["01", "Jonathan", "jonathan@example.com", "Senior Implementation Architect", "Hauck Inc", "Holy See"],
                    ["02", "Harold", "harold@example.com", "Forward Creative Coordinator", "Metz Inc", "Iran"],
                    ["03", "Shannon", "shannon@example.com", "Legacy Functionality Associate", "Zemlak Group", "South Georgia"],
                    ["04", "Robert", "robert@example.com", "Product Accounts Technician", "Hoeger", "San Marino"],
                    ["05", "Noel", "noel@example.com", "Customer Data Director", "Howell - Rippin", "Germany"],
                    ["06", "Traci", "traci@example.com", "Corporate Identity Director", "Koelpin - Goldner", "Vanuatu"],
                    ["07", "Kerry", "kerry@example.com", "Lead Applications Associate", "Feeney, Langworth and Tremblay", "Niger"],
                    ["08", "Patsy", "patsy@example.com", "Dynamic Assurance Director", "Streich Group", "Niue"],
                    ["09", "Cathy", "cathy@example.com", "Customer Data Director", "Ebert, Schamberger and Johnston", "Mexico"],
                    ["10", "Tyrone", "tyrone@example.com", "Senior Response Liaison", "Raynor, Rolfson and Daugherty", "Qatar"],
                ]
            }).render(document.getElementById("table-gridjs"));
        }
    </script>
@endpush