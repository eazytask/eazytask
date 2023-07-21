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

<h1 class="mb-1">welcome to user dashboard</h1>
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">

    <div class="row match-height">
        <!-- Revenue Report Card -->
        <div class="col-12">
            <div class="col-lg-12 col-md-12 p-0">
                <div class="card p-0">
                    <div class="container p-0">
                        <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
                            <h3 class="card-title text-primary d-inline">
                            Schedule
                            </h3>
                            <span class="float-right">
                                <i class="fa fa-chevron-up clickable"></i>
                            </span>
                        </div>
                        <div class="card-body pb-0">

                        </div>
                        <div class="card-body pt-0 pb-0">
                            <div class="row row-xs">
                                <!-- <div id="loadSearchADate">click</div> -->
                                <div class="col-12 text-center">

                                    <!-- <div id="editor"> -->

                                    <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25" id="prev"><i data-feather='arrow-left'></i></button>
                                    <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25" id="currentWeek">{{\Carbon\Carbon::now()->startOfWeek()->format('d M, Y')}} - {{\Carbon\Carbon::now()->endOfWeek()->format('d M, Y')}}</button>
                                    <button type="button" class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="next"><i data-feather='arrow-right'></i></button>

                                    <button class="btn p-0 pt-50 pb-50 mr-50 mt-25">
                                        <select id="project" class="form-control" style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;" name="project_id">
                                            <option value="">Select Venue</option>
                                        </select>
                                    </button>
                                    <!-- <button id="download" disabled class="btn text-white bg-primary pt-50 pb-50 mr-50 mr-25"><i data-feather='download' class="mr-25"></i>Download</button> -->
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="table-hover-animation">
                        <div class="col-12">
                            <div class="card">
                                <div class="container">
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
                </div>

            </div>
        </div>
        <!--/ Revenue Report Card -->
    </div>
</section>
<!-- Dashboard Ecommerce ends -->


<!-- END: Content-->
<script type="text/javascript">
    let searchADate = ''
    var searchEL = $('<label class="float-left">Search a Date:<input type="text" id="search_date" name="search_date" class="form-control format-picker form-control-sm" placeholder="dd-mm-yyyy"></label>');

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

    $('#prev').on('click', function() {
        searchADate = ''
        searchNow('previous')
    })
    $('#next').on('click', function() {
        searchADate = ''
        searchNow('next')
    })

    $('#project').on('change', function() {
        // alert($(this).val())
        // if ($(this).val()) {
        //     $('#download').prop('disabled', false)
        // } else {
        //     $('#download').prop('disabled', true)
        // }
        searchNow('current')
    })

    function searchNow(goTo = '', search_date = null) {
        $.ajax({
            url: '/user/roster/calendar/shifts',
            type: 'get',
            dataType: 'json',
            data: {
                'go_to': goTo,
                'project': $('#project').val(),
                'search_date': search_date,
            },
            success: function(data) {
                // $("#myTable").DataTable();
                // if (data.search_date) {
                //     $("#search_date").val(moment(data.search_date).format('DD-MM-YYYY'))
                // } else {
                //     $("#search_date").val('')
                // }
                get_projects(data)

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
                $('#logo').html('<img src="' + data.logo + '" alt="" class="ml-1" height="45px">');

                if (data.notification) {
                    toastr.success(data.notification)
                }

                $('#myTable_filter').append(searchEL)
                $(searchEL).find("#search_date").flatpickr({
                    dateFormat: "d-m-Y"
                });
                $(searchEL).find("#search_date").val(searchADate)
            },
            error: function(err) {
                console.log(err)
            }
        });
    }
    function get_projects(data) {
                    console.log(Object.keys(data.projects).length)
                    let html = '<option value="">Select Venue</option>'
                    if(Object.keys(data.projects).length){
                        html = ''
                    }
                    // if (emp = window.current_emp) {
                    //     html += "<option value='" + emp.id + "' selected>" + emp.fname + " " + ((emp.mname) ? emp.mname : '') + "" + emp.lname + "</option>"
                    // }
                    // window.first_project = data.projects[0].id
                    jQuery.each(data.projects, function(i, val) {
                        html += "<option value='" + val.id + "' "+(val.id==data.current_project?'selected':'')+">" + val.pName+ "</option>"
                    })
                    // console.log(html)
                    $('#project').html(html)
                    // if (data.notification) {
                    //     toastr.success(data.notification)
                    // }
        }
</script>

@push('scripts')
<script>
    $(document).ready(function() {
        searchNow()

        searchEL.on('change', function() {
            searchADate = $('#search_date').val()
            console.log($('#search_date').val())
            searchNow('search_date', $('#search_date').val())
        })

        $(document).on('show.bs.modal', '.modal', function() {
            const zIndex = 1040 + 10 * $('.modal:visible').length;
            $(this).css('z-index', zIndex);
            setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
        });

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
            $("#client-select").val(rowData.client_id).trigger('change');

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
            $("#roster").val(rowData.roaster_status_id).trigger('change')

            $("#remarks").val(rowData.remarks)

            $("#project-select").val(rowData.project_id).trigger('change');
            $("#addTimeKeeper").modal("show")

            initAllDatePicker();
            allCalculation()

        })
    });
</script>
@endpush
@endsection