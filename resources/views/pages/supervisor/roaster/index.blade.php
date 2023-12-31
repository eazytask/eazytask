@extends('layouts.Admin.master')
@section('admincontent')
<style>
    .dt-buttons {
        display: none !important;
    }

    #myTable {
        width: 1000px !important;
    }

    .font-small-2 {
        font-size: 0.7rem !important;
    }
</style>

<div class="col-lg-12 col-md-12 p-0">
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
            <div class="card-body pb-0">

            </div>
            <div class="card-body pt-0 pb-0">
                <div class="row row-xs">
                    <div class="col-12 mb-1">
                        <span class="font-weight-bolder float-left mt-25 mr-25 text-capitalize">Search a date:</span>
                        <input type="text" id="search_date" name="search_date" class="form-control format-picker form-control-sm float-left text-center bg-light-info" placeholder="dd-mm-yyyy" style="width:135px">
                        <button class="btn btn-gradient-primary float-right mt-50 pt-50 pb-50" id="addTimekeeperModal"><i data-feather='plus'></i></button>
                    </div>
                    <div class="col-12 text-center">

                        <!-- <div id="editor"> -->

                        <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25" id="prev"><i data-feather='arrow-left'></i></button>
                        <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25" id="currentWeek">{{\Carbon\Carbon::now()->startOfWeek()->format('d M, Y')}} - {{\Carbon\Carbon::now()->endOfWeek()->format('d M, Y')}}</button>
                        <button type="button" class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="next"><i data-feather='arrow-right'></i></button>
                        <button type="button" class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="copyWeek"><i data-feather='copy' class="mr-50"></i>Copy All</button>
                        <button type="button" class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="publishAll"><i data-feather='calendar' class="mr-50"></i>Publish All</button>

                        <button class="btn p-0 pt-50 pb-50 mr-50 mt-25">
                            <select id="project" class="form-control" style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;" name="project_id">
                                <option value="">Select Venue</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->pName }}
                                </option>
                                @endforeach
                            </select>
                        </button>
                        <button id="download" disabled class="btn text-white bg-primary pt-50 pb-50 mr-50 mr-25"><i data-feather='download' class="mr-25"></i>Download</button>
                        <!-- </div> -->

                    </div>

                    @include('pages.Admin.report.modals.timeKeeperAddModal')
                </div>
            </div>
        </div>

        <div class="row" id="table-hover-animation">
            <div class="col-12">
                <div class="card">
                    <div class="container">
                        <div class="table-responsive">

                            <div class="mt-2 total-display p-75 bg-light-primary font-weight-bold border-primary rounded" style="margin-bottom: -46px; width: 308px;">
                                <span class="mr-1" id="total_hours"></span>
                                <span class="" id="total_amount"></span>
                            </div>
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
    </div>

</div>
<script>
    //margins.left, // x coord   margins.top, { // y coord
    $('#download').click(function() {
        const element = document.getElementById('htmlContent').innerHTML;
        var opt = {
            filename: 'Schedule-Roster.pdf',
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
    });
</script>

<script type="text/javascript">
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function noAllowDrop(ev) {
        ev.stopPropagation();
    }

    function drag(ev, timekeeper_id) {
        ev.dataTransfer.setData("text", ev.target.id);
        ev.dataTransfer.setData("timekeeper_id", timekeeper_id);
    }

    function drop(ev, emp_id, date) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var timekeeper_id = ev.dataTransfer.getData("timekeeper_id");
        ev.currentTarget.appendChild(document.getElementById(data));

        $.ajax({
            url: '/admin/home/report/drag/keeper',
            type: 'get',
            dataType: 'json',
            data: {
                'timekeeper_id': timekeeper_id,
                'employee_id': emp_id,
                'date_': date,
            },
            success: function(data) {
                if (data.notification) {
                    toastr.success(data.notification)
                }
                searchNow('current')
            },
            error: function(err) {
                console.log(err)
                toastr.warning('something went wrong!')
            }
        });
    }


    $('#prev').on('click', function() {
        searchNow('previous')
    })
    $('#next').on('click', function() {
        searchNow('next')
    })
    $('#copyWeek').on('click', function() {
        searchNow('copy')
    })
    $('#publishAll').on('click', function() {
        searchNow('publish')
    })

    $('#project').on('change', function() {
        // alert($(this).val())
        if ($(this).val()) {
            $('#download').prop('disabled', false)
        } else {
            $('#download').prop('disabled', true)
        }
        searchNow('current')
    })

    function searchNow(goTo = '', search_date = null) {
        $.ajax({
            url: '/supervisor/roster/calender/search',
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
                $('#myTable').DataTable().clear().destroy();
                $('#tBody').html(data.data);
                $('#print_tBody').html(data.report);
                $('#print_client').html('Client: ' + data.client);
                $('#print_project').html('Venue: ' + data.project);
                $('#print_hours').html('Total Hours: ' + data.hours);
                $('#print_amount').html('Total Amount: $' + data.amount);
                $('#print_current_week').text('Date: ' + data.week_date)
                // $("#myTable").DataTable();
                $('#myTable').DataTable({
                    dom: 'Bfrtip',
                    paging: false,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
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
                $('#logo').html('<img src="' + data.logo + '" alt="" class="ml-1" height="45px">');

                if (data.notification) {
                    toastr.success(data.notification)
                }
            },
            error: function(err) {
                console.log(err)
            }
        });
    }

    function deleteRoaster(roasterId) {
        // let roasterId = $("#deleteBtn").attr("roasterId");
        $.ajax({
            url: '/supervisor/home/roster/calender/delete/' + roasterId,
            type: 'GET',
            success: function(data) {
                // $("#roasterClick").modal("hide")
                searchNow('current')
                if (data.notification) {
                    toastr.success(data.notification)
                }
            }
        });
    }

    function timekeeperAddFunc() {
        if ($("#timekeeperAddForm").valid()) {
            $.ajax({
                data: $('#timekeeperAddForm').serialize(),
                url: "/supervisor/home/roster/calender/store",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    searchNow('current')
                    $("#addTimeKeeper").modal("hide")
                    // $("#roasterClick").modal("hide")
                    searchNow('current')
                    toastr['success']('👋 Added Successfully', 'Success!', {
                        closeButton: true,
                        tapToDismiss: false,
                    });
                },
                error: function(data) {
                    console.log(data)
                    searchNow('current')
                }
            });
        }
    }

    function timekeeperEditFunc() {
        if ($("#timekeeperAddForm").valid()) {
            $.ajax({
                data: $('#timekeeperAddForm').serialize(),
                url: "/supervisor/home/roster/calender/update",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $("#addTimeKeeper").modal("hide")
                    // $("#roasterClick").modal("hide")
                    searchNow('current')
                    toastr['success']('👋 Update Successfully', 'Success!', {
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
</script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
</script>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#search_date').on('change', function() {
            searchNow('search_date', $('#search_date').val())
        })
        searchNow()
        $("#timekeeperAddForm").validate({
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
        $('#project-select').on('change', function() {
            filterEmployee()
            if ($(this).val()) {
                $('#inducted').prop('disabled', false)
            } else {
                $('#inducted').prop('disabled', true)
            }
        })
        $('#roaster_date').on('change', function() {
            filterEmployee()
        })
        $('input[name="filter_employee"]').on('change', function() {
            filterEmployee()
        })
    function filterEmployee() {
        $.ajax({
            url: '/supervisor/home/filter/employee',
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
                if(emp = window.current_emp){
                    html += "<option value='"+emp.id+"' selected>"+emp.fname +" "+((emp.mname)?emp.mname:'')+""+emp.lname+"</option>"
                }
                jQuery.each(data.employees, function( i, val ){
                    html += "<option value='"+val.id+"'>"+val.fname +" "+((val.mname)?val.mname:'')+""+val.lname+"</option>"
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
        $(document).on('show.bs.modal', '.modal', function() {
            const zIndex = 1040 + 10 * $('.modal:visible').length;
            $(this).css('z-index', zIndex);
            setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
        });

        $(document).on("click", "#addTimekeeperModal", function() {
            resetData()
            $("#editTimekeeperSubmit").prop("hidden", true)
            $("#addTimekeeperSubmit").prop("hidden", false)
            $("#addTimeKeeper").modal("show")
        })


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

        function roasterEndTimeInit() {
            $("#shift_end").change(function() {
                filterEmployee()
                allCalculation()
            });

        }
        roasterEndTimeInit()

        function roasterStartTimeInit() {
            $("#shift_start").change(function() {
                filterEmployee()
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

        $(document).on("click", ".editBtn", function() {
            // $("#roasterClick").modal("hide")
            window.current_emp = $(this).data("employee")
            let rowData = $(this).data("row")
            if ($(this).data("copy")) {
                $("#editTimekeeperSubmit").prop("hidden", true)
                $("#addTimekeeperSubmit").prop("hidden", false)
            } else {
                $("#editTimekeeperSubmit").prop("hidden", false)
                $("#addTimekeeperSubmit").prop("hidden", true)
                $("#timepeeper_id").val(rowData.id);
                $('#timepeeper_id').attr('value', rowData.id);
            }
            $("#employee_id").val(rowData.employee_id).trigger('change');
            $("#client-select").val(rowData.client_id).trigger('change')

            $("#roaster_date").val(moment(rowData.roaster_date).format('DD-MM-YYYY'))
            $("#shift_start").val($.time(rowData.shift_start))
            $("#shift_end").val($.time(rowData.shift_end))

            $("#shift_start").val($.time(rowData.shift_start))
            $("#shift_end").val($.time(rowData.shift_end))

            $("#shift_start").removeAttr("disabled")
            $("#shift_end").removeAttr("disabled")


            $("#rate").val(rowData.ratePerHour)
            $("#duration").val(rowData.duration)
            $("#amount").val(rowData.amount)
            $("#job").val(rowData.job_type_id).trigger('change');
            $("#roster_type").val(rowData.roaster_type).trigger('change');
            // $("#roster").val(rowData.roaster_status_id)

            $("#remarks").val(rowData.remarks)

            $("#project-select").val(rowData.project_id).trigger('change');
            $("#addTimeKeeper").modal("show")

            initAllDatePicker();
            allCalculation()

        })
        $(document).on("input", ".reactive", function() {
            allCalculation()
        })

        function resetData() {
            window.rowData = "" 
            window.current_emp = null
            $("#timepeeper_id").val("");
            $('#timepeeper_id').attr('value', "");
            $("#employee_id").val("").trigger('change');
            $("#client-select").val("").trigger('change')

            $("#roaster_date").val("")
            $("#shift_start").val("")
            $("#shift_end").val("")

            $("#shift_start").val("")
            $("#shift_end").val("")

            $("#rate").val("")
            $("#duration").val("")
            $("#amount").val("")
            $("#roster_type").val('Schedueled').trigger('change');
            // $("#job").val("")
            // $("#roster").val("")
            $("#remarks").val("")
            $("#project-select").val("").trigger('change');
        }
    });
</script>
@endpush
@endsection

@section('pdf_generator')
<style>
    #printTable td {
        font-size: 0.8rem !important;
    }

    #printTable th,
    #printTable td {
        padding: 0.40rem 0.6rem;
        /* padding: 0; */
        font-size: 0.9rem !important;
        text-align: center !important;
    }

    @media print {
        #printTable .tdbg {
            /* background-color: #1a4567 !important; */
            background-color: #7367f01f !important;
            -webkit-print-color-adjust: exact;
        }

        #printTable .tdbglight {
            /* background-color: #1a4567 !important; */
            background-color: #f6f6f6 !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
<div id="htmlContent" class="d-none">
    <div id="content">
        <div class="row" id="table-hover-animation">
            <div class="col-12">
                <div class="card border-primary m-2 pb-1">
                    <div class="card-header text-center">
                        <div class="" id="logo">
                            <!-- <img src="" alt="" class="ml-1" height="42px"> -->
                        </div>
                        <div class="">
                            <h6 id="print_current_week" class="mr-1"></h6>
                        </div>
                    </div>
                    <div class="card-header bg-primary m-1 p-1">
                        <h6 id="print_client" class="text-uppercase text-light"></h6>
                        <h6 id="print_project" class="text-uppercase text-light"></h6>
                        <h6 id="print_hours" class="text-light"></h6>
                        <h6 id="print_amount" class="text-light"></h6>
                    </div>
                    <div class="container">
                        <div class="">
                            <table class="table-bordered text-center" id="printTable" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th style='width:10%'>Employee Name</th>
                                        <th style='width:12%'>Monday</th>
                                        <th style='width:12%'>Tuesday</th>
                                        <th style='width:12%'>Wednesday</th>
                                        <th style='width:12%'>Thursday</th>
                                        <th style='width:12%'>Friday</th>
                                        <th style='width:12%'>Saturday</th>
                                        <th style='width:12%'>Sunday</th>
                                    </tr>
                                </thead>
                                <tbody id="print_tBody">
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
    </div>
</div>
@endsection