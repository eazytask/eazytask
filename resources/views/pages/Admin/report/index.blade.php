@extends('layouts.Admin.master')
@section('admincontent')
    <style>
        .dt-buttons {
            display: none !important;
        }

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

    <div class="col-lg-12 col-md-12 p-0">
        <div class="card p-0">
            <div class="container p-0">
                <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
                    <h3 class="card-title text-primary d-inline">
                        Roster Entry
                    </h3>
                    <span class="float-right">
                        <div class=" text-center">
                            {{-- <button class="p-50 btn">
                                <span class="bullet mr-50" style='background:#00cfe8 !important'></span>Unscheduled
                            </button> --}}
                            {{-- @foreach ($roaster_status as $row)
                                <button class="p-50 btn">
                                    <span class="bullet mr-50"
                                        style='background: {{ $row->color }} !important'></span>{{ $row->name }}
                                </button>
                            @endforeach --}}
                        </div>
                    </span>
                </div>
                <div class="card-body pb-0">

                </div>
                <div class="card-body pt-0 pb-0">
                    <div class="row row-xs">
                        <div class="col-12 mb-1">
                            <span class="font-weight-bolder float-left mt-25 mr-25 text-capitalize">Search a date:</span>
                            <input type="text" id="search_date" name="search_date"
                                class="form-control format-picker form-control-sm float-left text-center bg-light-info"
                                placeholder="dd-mm-yyyy" style="width:135px">
                            <button class="btn btn-gradient-primary float-right mt-50 pt-50 pb-50"
                                id="addTimekeeperModal"><i data-feather='plus'></i></button>
                        </div>
                        <div class="col-12 text-center">

                            <!-- <div id="editor"> -->

                            <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25" id="prev"><i
                                    data-feather='arrow-left'></i></button>
                            <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25"
                                id="currentWeek">{{ \Carbon\Carbon::now()->startOfWeek()->format('d M, Y') }} -
                                {{ \Carbon\Carbon::now()->endOfWeek()->format('d M, Y') }}</button>
                            <button type="button" class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="next"><i
                                    data-feather='arrow-right'></i></button>
                            <button type="button" class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="publishAll"><i
                                    data-feather='calendar' class="mr-50"></i>Publish All</button>
                            {{-- <button type="button" disabled class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" --}}
                            {{-- id="copyWeek"><i data-feather='copy' class="mr-50"></i>Copy All</button> --}}

                            <button class="p-0 pt-50 pb-50 mr-50 mt-25 button-unstyled">
                                <select id="client" class="form-control select2"
                                    style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;"
                                    name="client_id" onchange="handleClientChange(this)">
                                    <option value="">Select Client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->cname }}
                                        </option>
                                    @endforeach
                                </select>
                            </button>

                            <button class="p-0 pt-50 pb-50 mr-50 mt-25 button-unstyled">
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
                            <button id="download" disabled
                                class="btn text-white bg-gradient-primary pt-50 pb-50 mr-50 mr-25"><i
                                    data-feather='download' class="mr-25"></i>Download</button>
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

                                <div class="mt-2 total-display p-25 pb-75 pt-75 bg-light-primary font-weight-bold border-primary rounded"
                                    style="margin-bottom: -46px; width: 308px;">
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

    <!-- <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'csrftoken': '{{ csrf_token() }}'
            }
        });
    </script> -->
    @push('scripts')
        <script type="text/javascript"></script>
        <script>
            let ids = []
            let info = null
            let reload;


            $(document).on("click", "#checkAllID", function() {
                let remainingCheckboxes = 9999999;

                if ($("#checkAllID").is(':checked')) {
                    let checkboxesToCheck = $('.checkID:not(:checked)').slice(0, remainingCheckboxes);
                    checkboxesToCheck.prop('checked', true);
                    ids = ids.concat(checkboxesToCheck.map(function() {
                        return $(this).val();
                    }).get());
                } else {
                    ids = [];
                    $('.checkID').prop('checked', false);
                }

                updateButtonState();
            });

            //show imployee by status
            function showImployees(status, info) {
                // eventToUpdate = info;

                ids = []
                totalId = []
                $('#checkAllID').prop('checked', false)
                // event_id = info.extendedProps.id
                let employees = info.employees

                let rows = ''
                $.each(employees, function(index, employee) {
                    if (status == 'available') {
                        insertThis(employee)
                    } else if (status == 'inducted') {
                        insertThis(employee)
                    } else if (status == 'all') {
                        insertThis(employee)
                    }

                })

                function insertThis(employee) {
                    let status = ''
                    let employeeId = null
                    let checkbox_status = ''
                    if (employee.status == 'Added') {
                        status = 'badge badge-pill badge-light-success mr-1'
                        checkbox_status = 'disabled'
                    } else {
                        totalId.push(employee.id)
                        employeeId = employee.id
                    }

                    rows += `
                        <tr>
                            <td><input type="checkbox" class="checkID" value="` + employeeId +
                        `" ` + checkbox_status + `></td>
                            <td>` + employee.fname + `</td>
                            <td>` + employee.contact_number + `</td>
                            <td>` + employee.email + `</td>
                            <td class="` + status + `">` + 'Waiting' + `</td>
                        </tr>
            `
                }
                if (totalId == 0) {
                    $("#checkAllID").prop('disabled', true)
                } else {
                    $("#checkAllID").prop('disabled', false)
                }
                $('#eventClickTable').DataTable().clear().destroy();
                $('#eventClickTbody').html(rows);
                $('#eventClickTable').DataTable();
            }

            $(document).on("click", ".checkID", function() {
                if ($(this).is(':checked')) {
                    // if (ids.length < info.extendedProps.no_employee_required) {
                    ids.push($(this).val());
                    // } else {
                    // If more than 5 checkboxes are checked, uncheck the current checkbox
                    // $(this).prop('checked', false);
                    // }
                } else {
                    let id = $(this).val();
                    ids = jQuery.grep(ids, function(value) {
                        return value != id;
                    });
                }

                updateButtonState();
                updateCheckAll();
            });

            function updateButtonState() {
                if (ids.length === 0) {
                    $("#addTimekeeperSubmit").prop('disabled', true);
                } else {
                    $("#addTimekeeperSubmit").prop('disabled', false);
                }
            }

            function updateCheckAll() {
                if (ids.length == totalId.length) {
                    $('#checkAllID').prop('checked', true);
                } else {
                    $('#checkAllID').prop('checked', false);
                }
            }

            $('#filterStatus').on('change', function() {
                showImployees($('#filterStatus').val(), info)
            });


            // Add change event listener to client dropdown
            $('#client').change(function() {
                var client_id = $(this).val();

                // Make AJAX request to get related projects
                $.ajax({
                    url: "{{ url('/get-projects') }}/" + client_id,
                    type: 'GET',
                    success: function(data) {
                        // Clear existing options in the project dropdown
                        $('#project').empty();

                        // Add a default option
                        $('#project').append(
                            '<option value="">Select Venue</option>');

                        // Add fetched projects to the project dropdown
                        $.each(data, function(key, value) {
                            $('#project').append('<option value="' + value
                                .id +
                                '">' +
                                value.pName + '</option>');
                        });
                    }
                });
            });
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
                // console.log(timekeeper_id)
                // console.log(emp_id)
                // console.log(date)
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

            function handleClientChange(selectElement) {
                $('#project').empty();
                if ($(selectElement).val()) {
                    $('#download').prop('disabled', false)
                    $('#copyWeek').prop('disabled', false)
                } else {
                    $('#download').prop('disabled', true)
                    $('#copyWeek').prop('disabled', true)
                }

                searchNow('current')
            }

            $('#addTimekeeperSubmit').on('click', function() {
                if ($("#timekeeperAddForm").valid()) {
                    var serializedData = $('#timekeeperAddForm').serialize();

                    // Convert ids array to a serialized format
                    var employeeIdsSerialized = $.param({
                        "employee_ids[]": ids
                    }, true);

                    // Append employeeIdsSerialized to the original serialized data
                    var newData = serializedData + '&' + employeeIdsSerialized;

                    // Now, newData contains the updated serialized data with employee_ids
                    console.log(newData);

                    $.ajax({
                        data: newData,
                        url: "/admin/home/new/timekeeper/store",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            searchNow('current')
                            $("#addTimeKeeper").modal("hide")
                            // $("#roasterClick").modal("hide")
                            searchNow('current')
                            if (data.notification) {
                                toastr.success(data.notification)
                            }
                        },
                        error: function(data) {
                            console.log(data)
                            searchNow('current')
                        }
                    });
                }
            })

            $('#editTimekeeperSubmit').on('click', function() {
                if ($("#timekeeperAddForm").valid()) {
                    $.ajax({
                        data: $('#timekeeperAddForm').serialize(),
                        url: "/admin/home/new/timekeeper/update",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $("#addTimeKeeper").modal("hide")
                            // $("#roasterClick").modal("hide")
                            searchNow('current')
                            if (data.notification) {
                                toastr.success(data.notification)
                            }
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            })

            searchNow()

            $('#search_date').on('change', function() {
                searchNow('search_date', $('#search_date').val())
            })
            $("#timekeeperAddForm").validate({
                errorPlacement: function(error, element) {
                    if (element.hasClass('select2') && element.next('.select2-container').length) {
                        error.insertAfter(element.next('.select2-container'));
                    } else if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if (element.prop('type') === 'radio' && element.parent('.radio-inline')
                        .length) {
                        error.insertAfter(element.parent().parent());
                    } else if (element.prop('type') === 'checkbox' || element.prop('type') ===
                        'radio') {
                        error.appendTo(element.parent().parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            })

            $('#project-select').on('change', function() {
                console.log('project')
                filterEmployee()
                if ($(this).val()) {
                    $('#inducted').prop('disabled', false)
                } else {
                    $('#inducted').prop('disabled', true)
                }
            })

            $('#filterStatus').on('change', function() {

                console.log('filter')
                filterEmployee()
            })

            function filterEmployee() {
                $.ajax({
                    url: '/admin/home/filter/employee',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'filter': $('#filterStatus').val(),
                        'project_id': $("#project-select").val(),
                        'roster_date': $("#roaster_date").val(),
                        'shift_start': $("#shift_start").val(),
                        'shift_end': $("#shift_end").val(),
                    },
                    success: function(data) {
                        // console.log(data)
                        info = data;
                        showImployees($('#filterStatus').val(), info)
                        // let html = '<option value="">please choose...</option>'
                        // if (emp = window.current_emp) {
                        //     html += "<option value='" + emp.id + "' selected>" + emp.fname + " " + ((emp
                        //         .mname) ? emp.mname : '') + " " + emp.lname + "</option>"
                        // }
                        // jQuery.each(data.employees, function(i, val) {
                        //     html += "<option value='" + val.id + "'>" + val.fname + " " + ((val
                        //         .mname) ? val.mname : '') + "" + val.lname + "</option>"
                        // })
                        // // console.log(html)
                        // $('#employee_id').html(html)
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
                setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1)
                    .addClass('modal-stack'));
            });

            $(document).on("click", "#addTimekeeperModal", function() {
                resetData()
                $("#project-select").val("").trigger('change');
                $("#editTimekeeperSubmit").prop("hidden", true)
                $("#addTimekeeperSubmit").prop("hidden", false)
                $("#tableListEmployee").show();
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
                    console.log('end')
                    filterEmployee()
                    allCalculation()
                });

            }
            roasterEndTimeInit()

            function roasterStartTimeInit() {
                $("#shift_start").change(function() {
                    console.log('start')
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
                    console.log('roster date')
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

            $(document).on("click", ".editBtn", function() {
                // $("#roasterClick").modal("hide")
                resetData()
                window.current_emp = $(this).data("employee")

                let rowData = $(this).data("row")
                if ($(this).data("copy")) {
                    $("#editTimekeeperSubmit").prop("hidden", true)
                    $("#addTimekeeperSubmit").prop("hidden", false)
                    $("#roster").val("{{ Session::get('roaster_status')['Not published'] }}").trigger(
                        'change')
                } else {
                    $("#editTimekeeperSubmit").prop("hidden", false)
                    $("#addTimekeeperSubmit").prop("hidden", true)
                    $("#timepeeper_id").val(rowData.id);
                    $('#timepeeper_id').attr('value', rowData.id);
                    $("#roster").val(rowData.roaster_status_id).trigger('change')
                }
                $("#employee_id").val(rowData.employee_id).trigger('change');
                $("#client-select").val(rowData.client_id).trigger('change');

                $("#roaster_date").val(moment(rowData.roaster_date).format('DD-MM-YYYY'))
                $("#shift_start").val($.time(rowData.shift_start))
                $("#shift_end").val($.time(rowData.shift_end))

                $("#shift_start").removeAttr("disabled")
                $("#shift_end").removeAttr("disabled")


                $("#rate").val(rowData.ratePerHour)
                $("#duration").val(rowData.duration)
                $("#amount").val(rowData.amount)
                $("#job").val(rowData.job_type_id).trigger('change');
                $("#roster_type").val(rowData.roaster_type).trigger('change');

                $("#remarks").val(rowData.remarks)

                $("#project-select").val(rowData.project_id).trigger('change');
                $("#tableListEmployee").hide();
                $("#addTimeKeeper").modal("show")

                // initAllDatePicker();
                allCalculation()

            })
            $(document).on("input", ".reactive", function() {
                allCalculation()
            })

            function resetData() {
                window.current_emp = null
                window.rowData = ""
                $("#timepeeper_id").val("");
                $('#timepeeper_id').attr('value', "");
                $("#employee_id").val("").trigger('change');

                $("#roaster_date").val("")
                $("#shift_start").val("")
                $("#shift_end").val("")

                $("#shift_start").val("")
                $("#shift_end").val("")
                $("#sing_in").val("")
                $("#sing_out").val("")
                $(".sing_body").hide()

                $("#rate").val("")
                $("#duration").val("")
                $("#amount").val("")
                $("#roster_type").val('Schedueled').trigger('change');
                // $("#job").val("")
                // $("#roster").val("")
                $("#roster").val("{{ Session::get('roaster_status')['Not published'] }}").trigger('change')
                $("#remarks").val("")
            }

            function searchNow(goTo = '', search_date = null) {
                $.ajax({
                    url: '/admin/home/report/search',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'go_to': goTo,
                        'project': $('#project').val(),
                        'client': $('#client').val(),
                        'search_date': search_date,
                    },
                    success: function(data) {
                        console.log(data);
                        // $("#myTable").DataTable();
                        if (data.search_date) {
                            $("#search_date").val(moment(data.search_date).format('DD-MM-YYYY'))
                        } else {
                            $("#search_date").val('')
                        }
                        $('#myTable').DataTable().clear().destroy();
                        $('#tBody').html(data.data);
                        $('#wrapper_print').empty();

                        data.project.forEach(function(element) {
                            if (data.report[element.id] == "")
                                return;

                            $('#wrapper_print').append(`
                        <div class="card-header bg-primary m-1 p-1">
                            <h6 id="print_client" class="text-uppercase text-light">Client Name: ${data.client}</h6>
                            <h6 id="print_project" class="text-uppercase text-light">Venue Name: ${element.pName}</h6>
                            <h6 id="print_hours" class="text-light">Total Hours: ${data.final_hours[element.id]}</h6>
                            <h6 id="print_amount" class="text-light">Total Amount: ${data.final_amount[element.id]}$</h6>
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
                                        ${data.report[element.id]}
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        `)
                        });

                        // $('#print_tBody').html(data.report);
                        // $('#print_client').html('Client: ' + data.client);
                        // $('#print_project').html('Venue: ' + data.project);
                        // $('#print_hours').html('Total Hours: ' + data.hours);
                        // $('#print_amount').html('Total Amount: $' + data.amount);
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
                        $('#logo').html('<img src="' + data.logo +
                            '" alt="" class="ml-1" height="45px">');

                        if (data.notification) {
                            toastr.success(data.notification)
                        }

                        clearInterval(reload)
                        reload = setInterval(() => {
                            searchNow('current')
                        }, 300000);
                    },
                    error: function(err) {
                        console.log(err)

                        clearInterval(reload)
                        reload = setInterval(() => {
                            searchNow('current')
                        }, 300000);
                    }
                });
            }

            function deleteRoaster(roasterId) {
                // let roasterId = $("#deleteBtn").attr("roasterId");
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '/admin/home/report/delete/' + roasterId,
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
                    });

            }

            function publishRoaster(roasterId) {
                $.ajax({
                    url: '/admin/home/report/publish/' + roasterId,
                    type: 'GET',
                    success: function(data) {
                        // $("#roasterClick").modal("hide")
                        searchNow('current')
                        if (data.status == true) {
                            toastr.success(data.notification)
                        } else {
                            toastr.info(data.notification)
                        }
                    }
                });

            }
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
                        <div id="wrapper_print">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
