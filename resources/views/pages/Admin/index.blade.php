@extends('layouts.Admin.master')

@section('admincontent')
    @include('sweetalert::alert')
    <style type="text/css">
        .fc-list-event-time {
            display: none;
        }

        .mydate .flatpickr-wrapper {
            display: block;
        }

        .fc-h-event .fc-event-main {
            color: #000 !important;
        }

        /*.fc-timegrid-axis,.fc-timegrid-slot-label,.fc-scrollgrid-shrink{
                display: none;
            }*/
    </style>

    <!--<h1 class="text-center">Welcome to Admin Dashboard</h1>-->
    <!-- Dashboard Ecommerce Starts -->
    <section id="dashboard-ecommerce">
        <div class="row match-height">
            <!-- Medal Card -->
            <div class="col-xl-4 col-md-6 col-12">
                <div class="card">
                    <div class="container  mb-1 p-1 d-flex justify-content-center">
                        <div class="image d-flex flex-column justify-content-center align-items-center">
                            <span class="name mt-1"
                                style="font-size: 26px; font-weight: bold; color: #000;">{{ Auth::user()->name ?? '' }}
                                {{ Auth::user()->mname ?? '' }} {{ Auth::user()->lname ?? '' }}</span>
                            <p class="mt-1">{{ Auth::user()->license_no ?? '' }}</p>
                            <img src="https://api.eazytask.au/{{ Auth::user()->image }}" height="80" width="80"
                                alt="view sales" class="rounded-circle">
                            <a href="/admin/company/profile-settings/{{ Auth::user()->id }}"
                                class="btn btn-primary waves-effect waves-light mt-2">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Medal Card -->

            <!-- Statistics Card -->
            <div class="col-xl-8 col-md-6 col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Statistics</h4>
                        <div class="d-flex align-items-center">
                            <p class="card-text font-small-2 mr-25 mb-0">Weekend In
                                {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}</p>
                        </div>
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="media">
                                    <div class="avatar bg-light-primary mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="trending-up" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">{{ $data['total_hour'] }}</h4>
                                        <p class="card-text font-small-3 mb-0">Hours</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="media">
                                    <div class="avatar bg-light-info mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="dollar-sign" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">${{ $data['total_amount'] }}</h4>
                                        <p class="card-text font-small-3 mb-0">Amount</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="media">
                                    <div class="avatar bg-light-danger mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="user" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">{{ $data['total_client'] }}</h4>
                                        <p class="card-text font-small-3 mb-0">Clients</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="media">
                                    <div class="avatar bg-light-success mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="box" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">{{ $data['total_sites'] }}</h4>
                                        <p class="card-text font-small-3 mb-0">Sites</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics Card -->
        </div>

        <div class="row match-height">
            <!--/ Company Table Card -->
            <div class="col-lg-4 col-12">
                <div class="row match-height">
                    <!-- Bar Chart - Orders -->
                    <div class="col-lg-12 col-md-6 col-12">
                        <div class="card">
                            <div class="card-body pb-50">
                                <h6>Weekend In {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}</h6>
                                <h2 class="font-weight-bolder mb-0">{{ $data['weekly_hour'] }}</h2>
                                <div id="statistics-order-chart"></div>
                            </div>
                        </div>
                    </div>
                    <!--/ Bar Chart - Orders -->

                    <!-- Earnings Card -->
                    <div class="col-lg-12 col-md-6 col-12">
                        <div class="card earnings-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="card-title mb-1">Total Amount</h4>
                                        <div class="font-small-2">Weekend In
                                            {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d M, y') }}</div>
                                        <h5 class="mb-1">${{ $data['monthly_earning'] }}</h5>
                                        <!-- <p class="card-text text-muted font-small-2">
                                                <span> default earning percentage is 10%.</span>
                                            </p> -->
                                    </div>
                                    <div class="col-6">
                                        <div id="earnings-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Earnings Card -->
                </div>
            </div>
            <!-- Revenue Report Card -->
            <div class="col-lg-8 col-12">
                <div class="card card-revenue-budget">
                    <div class="row mx-0">
                        <div class="col-md-8 col-12 revenue-report-wrapper">
                            <div class="d-sm-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-50 mb-sm-0">Revenue Report</h4>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center mr-2">
                                        <span class="bullet bullet-primary font-small-3 mr-50 cursor-pointer"></span>
                                        <span>Revenue</span>
                                    </div>
                                    <div class="d-flex align-items-center ml-75">
                                        <span class="bullet bullet-warning font-small-3 mr-50 cursor-pointer"></span>
                                        <span>Expense</span>
                                    </div>
                                </div>
                            </div>
                            <div id="revenue-report-chart"></div>
                        </div>
                        <div class="col-md-4 col-12 budget-wrapper">
                            <div class="btn-group">
                            </div>
                            <p>Last six months amount and hours</p>
                            <h2 class="mb-25">${{ $data['amount_last_six'] }}</h2>
                            <div class="d-flex justify-content-center">
                                <span class="font-weight-bolder mr-25">Hours:</span>
                                <span>{{ $data['hours_last_six'] }}</span>
                            </div>
                            <div id="budget-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Revenue Report Card -->





        </div>

        <div class="row match-height">

            <!-- calendar Table Card -->
            <div class="col-12">
                <div class="card card-company-table">

                    <div class="card-header">
                        <div class=" mt-3 mt-md-0">
                            <a href="/admin/home/event/request" class="btn btn-gradient-primary "><i
                                    data-feather="calendar" class="avatar-icon font-medium-3"></i></a>
                        </div>
                    </div>
                    <div class="d-none">
                        <select name="" id="clientFilter" hidden>
                            <option value="">Select</option>
                        </select>
                        <select name="" id="projectFilter" hidden>
                            <option value="">Select</option>
                        </select>
                    </div>

                    <!-- Calendar -->
                    <!-- Calendar -->
                    <div class="col position-relative">
                        <div class="card shadow-none border-0 mb-0 rounded-0">
                            <div class="card-body pb-0">
                                <div id="calendar_event_request"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /Calendar -->
                    <div class="body-content-overlay"></div>
                </div>
            </div>
            @include('pages.Admin.event_request.modals.eventClickModal')
            @include('pages.Admin.event_request.modals.addUpcomingeventModal')
            <!-- /Calendar -->
            <!-- calendar Table Card -->

            <div class="col-lg-6 col-12">

                <div class="card plan-card" id="hasData">
                    <div class="card-header">
                        <div class="col mt-3 mt-md-0">
                            <span class="h4">Top Five Earners</span>
                            <span class="ml-25 font-small-2">Weekend In
                                {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d M, y') }}</span>
                            <form action="{{ route('searchData') }}" method="post">
                                @csrf
                                <input type="text" name="start_date" hidden
                                    value="{{ \Carbon\Carbon::now()->subWeeks(2)->startOfWeek() }}" />
                                <input type="text" name="end_date" hidden
                                    value="{{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek() }}" />

                                <button type="submit" class="btn btn-gradient-primary float-right">View All</button>
                            </form>
                            <!-- <a class="btn btn-gradient-primary float-right" href="/admin/home/view/schedule/company">View All</a> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Hours</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['employee_report'] as $i => $val)
                                        @php
                                            if (!file_exists($val->image)) {
                                                $image = 'images/app/no-image.png';
                                            } else {
                                                $image = $val->image;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-light-primary mr-1">
                                                        <div class="avatar-content">
                                                            <img class="img-fluid" src="{{ asset($image) }}"
                                                                alt="">
                                                        </div>
                                                    </div>
                                                    <span>{{ $val->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $val->hours }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="font-weight-bolder mr-1">${{ $val->amount }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--/ Company Table Card -->
            </div>
            <!-- Browser States Card -->
            <div class="col-lg-6 col-12">
                <div class="card card-browser-states">
                    <div class="card-header">
                        <div>
                            <span class="h4">Client Wise Job</span>
                            <span class="ml-25 font-small-2">Weekend In
                                {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d M, y') }}</span>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Hours</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['client_report'] as $i => $val)
                                        @php
                                            if (!file_exists($val->client->image)) {
                                                $image = 'images/app/no-image.png';
                                            } else {
                                                $image = $val->client->image;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-light-primary mr-1">
                                                        <div class="avatar-content">
                                                            <img class="img-fluid" src="{{ asset($image) }}"
                                                                alt="">
                                                        </div>
                                                    </div>
                                                    <span>{{ $val->client->cname }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $val->hours }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="font-weight-bolder mr-1">${{ $val->amount }}</span>
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
            <!--/ Browser States Card -->

            <!-- Goal Overview Card -->
            <!-- <div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Total Payment Status</h4>
                            <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                        </div>
                        <div class="card-body p-0">
                            <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                            <div class="row border-top text-center mx-0">
                                <div class="col-6 border-right py-1">
                                    <p class="card-text text-muted mb-0">Paid</p>
                                    <h3 class="font-weight-bolder mb-0">{{ $data['payment_status']['paid'] }}</h3>
                                </div>
                                <div class="col-6 py-1">
                                    <p class="card-text text-muted mb-0">Pending</p>
                                    <h3 class="font-weight-bolder mb-0">{{ $data['payment_status']['pending'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            <!--/ Goal Overview Card -->


            <!-- Developer Meetup Card -->
            <!-- <div class="col-lg-4 col-md-6 col-12">
                    <div class="card card-developer-meetup">
                        <div class="meetup-img-wrapper rounded-top text-center">
                            <img src="../../../app-assets/images/illustration/email.svg" alt="Meeting Pic" height="170" />
                        </div>
                        <div class="card-body">
                            @if ($data['next_event'])
    <div class="meetup-header d-flex align-items-center">
                                <div class="meetup-day">
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($data['next_event']['event_date'])->format('D') }}</h6>
                                    <h3 class="mb-0">{{ \Carbon\Carbon::parse($data['next_event']['event_date'])->format('d') }}</h3>
                                </div>
                                <div class="my-auto">
                                    <h4 class="card-title mb-25">Upcoming Event</h4>
                                    <p class="card-text mb-0">{{ $data['next_event']['remarks'] }}</p>
                                </div>
                            </div>
                            <div class="media">
                                <div class="avatar bg-light-primary rounded mr-1">
                                    <div class="avatar-content">
                                        <i data-feather="calendar" class="avatar-icon font-medium-3"></i>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($data['next_event']['event_date'])->format('D, M d, Y') }}</h6>
                                    <small>{{ \Carbon\Carbon::parse($data['next_event']['shift_start'])->format('H:i') }} to {{ \Carbon\Carbon::parse($data['next_event']['shift_end'])->format('H:i') }}</small>
                                </div>
                            </div>
                            <div class="media mt-2">
                                <div class="avatar bg-light-primary rounded mr-1">
                                    <div class="avatar-content">
                                        <i data-feather="map-pin" class="avatar-icon font-medium-3"></i>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h6 class="mb-0">{{ $data['next_event']->project->pName }}</h6>
                                    <small>Australia</small>
                                </div>
                            </div>
@else
    <div class="my-auto">
                                <h4 class="card-title mb-25">Upcoming Event</h4>
                                <p class="card-text mb-0">No event found!</p>
                            </div>
    @endif
                        </div>
                    </div>
                </div> -->
            <!--/ Developer Meetup Card -->
            <!-- Company Table Card -->


            <!-- Transaction Card -->
            <div class="col-lg-4 col-12">
                <div class="card card-transaction">
                    <div class="card-header">
                        <h4 class="card-title">Monthly Expense</h4>
                    </div>
                    <div class="card-body">

                        @foreach ($data['monthly_expense'] as $i => $val)
                            <div class="transaction-item">
                                <div class="media">
                                    <div class="avatar bg-light-{{ $val == 0 ? 'success' : 'danger' }} rounded">
                                        <div class="avatar-content">
                                            <i data-feather="{{ $val == 0 ? 'check' : 'dollar-sign' }}"
                                                class="avatar-icon font-medium-3"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="transaction-title">
                                            {{ \Carbon\Carbon::now()->subMonths($i)->format('F') }}</h6>
                                    </div>
                                </div>
                                <div class="font-weight-bolder text-{{ $val == 0 ? 'success' : 'danger' }}">
                                    ${{ $val }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!--/ Transaction Card -->

            <!-- Company Table Card -->
            <div class="col-lg-8 col-12">
                <div class="card card-company-table">
                    <div class="card-body">

                        <div class="card plan-card p-2" id="hasData">
                            <div class="row">
                                <button class="btn btn-outline-primary text-center border-primary mr-25"
                                    id="showAddModal"><i data-feather="plus"
                                        class="avatar-icon font-medium-3"></i></button>
                                <button class="btn btn-outline-danger text-center border-primary taskBtn mr-25"
                                    onclick="manageTask('delete')" disabled><i data-feather="trash-2"
                                        class="avatar-icon font-medium-3"></i></button>
                                <button class="btn btn-outline-success text-center border-primary taskBtn mr-25"
                                    onclick="manageTask('completed')" disabled><i data-feather="arrow-up"
                                        class="avatar-icon font-medium-3"></i></button>
                                <button class="btn btn-outline-secondary text-center border-primary taskBtn"
                                    onclick="manageTask('incomplete')" disabled><i data-feather="arrow-down"
                                        class="avatar-icon font-medium-3"></i></button>

                            </div>
                            @include('pages.Admin.modals.AddModal')
                        </div>
                        <div class="row" id="table-hover-animation">
                            <div class="table-responsive">
                                <table id="eventClickTable" class="table text-center table-bordered ">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onclick="taskcheckAllID()" id="taskcheckAllID">
                                            </th>
                                            <th>Task Description</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody id="taskTbody">
                                        <tr>
                                            <td colspan="4"> no data found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Company Table Card -->
    </section>
    <!-- Dashboard Ecommerce ends -->


    <!-- END: Content-->
@endsection

@push('scripts')
    <script src="{{ asset('app-assets/js/scripts/pages/app-calendar-event-request.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
    <script>
        $(document).ready(function() {
            var client_id = $('#client_id'),
                project_id = $('#project_id');


            client_id.wrap('<div class="position-relative"></div>').select2({
                    dropdownParent: client_id.parent()
                })
                .change(function() {
                    $(this).valid();
                });

            project_id.wrap('<div class="position-relative"></div>').select2({
                    dropdownParent: project_id.parent()
                })
                .change(function() {
                    $(this).valid();
                });


            $('#clientFilter').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Client',
                dropdownParent: $('#clientFilter').parent()
            });

            $('#projectFilter').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Project',
                dropdownParent: $('#projectFilter').parent()
            });

            $("#addEventForm").validate();
        });

        let task_ids = []
        let totalTaskId = []
        let event_id = null

        $(document).on("click", ".taskCheckID", function() {
            if ($(this).is(':checked')) {
                task_ids.push($(this).val())
            } else {
                let id = $(this).val()
                task_ids = jQuery.grep(task_ids, function(value) {
                    return value != id
                })
            }

            if (task_ids.length === 0) {
                $(".taskBtn").prop('disabled', true)
            } else {
                $(".taskBtn").prop('disabled', false)
            }

            if (task_ids.length == totalTaskId.length) {
                $('#taskcheckAllID').prop('checked', true)
            } else {
                $('#taskcheckAllID').prop('checked', false)
            }
        })
        taskcheckAllID = function() {
            if ($("#taskcheckAllID").is(':checked')) {
                task_ids = totalTaskId
                $('.taskCheckID').prop('checked', true)
            } else {
                task_ids = []
                $('.taskCheckID').prop('checked', false)
            }

            if (task_ids.length === 0) {
                $(".taskBtn").prop('disabled', true)
            } else {
                $(".taskBtn").prop('disabled', false)
            }

        }

        taskDescription = function() {
            $.ajax({
                url: '/admin/home/task/descriptions',
                type: 'GET',
                success: function(data) {
                    showTasks(data.data)
                },
            })
        }
        taskDescription()
        //show imployee by status
        function showTasks(all_tasks) {
            task_ids = []
            totalTaskId = []

            let taskRows = ''
            $.each(all_tasks, function(index, data) {
                insertThis(data)
            })

            function insertThis(data) {
                let status = ''
                if (data.status == 'completed') {
                    status = 'text-success'
                } else {
                    status = 'text-secondary'
                }
                totalTaskId.push(data.id)

                taskRows += `
                <tr>
                    <td><input type="checkbox" class="taskCheckID" value="` + data.id + `"></td>
                    <td>` + data.description + `</td>
                    <td class="` + status + `">` + data.status + `</td>
                    <td><button data-copy="true" edit-it="true" class="edit-btn btn-link btn" data-id="` + data.id +
                    `" data-description="` + data.description + `" data-status="` + data.status + `"><i data-feather='edit'></i></button></td>
                    
                </tr>
                `
            }
            if (totalTaskId == 0) {
                $("#taskcheckAllID").prop('disabled', true)
            } else {
                $("#taskcheckAllID").prop('disabled', false)
            }
            $('#taskTbody').html(taskRows);
            feather.replace();
        }

        $(document).on("click", "#showAddModal", function() {
            $("#description").val('')
            $("#status").val('incomplete')
            $('#updateBtn').prop('hidden', true);
            $('#addBtn').prop('hidden', false);

            $("#add").modal("show")
        })

        $(document).on("click", ".edit-btn", function() {
            var id = $(this).data("id");
            var status = $(this).data("status");
            var description = $(this).data("description");

            $("#description").val(description)
            $("#status").val(status)
            $("#taskId").val(id)

            $('#updateBtn').prop('hidden', false);
            $('#addBtn').prop('hidden', true);

            $("#add").modal("show")

        })
        // Add new event
        $('#addBtn').on('click', function() {
            if ($("#taskDesctioptionForm").valid()) {
                $.ajax({
                    data: $('#taskDesctioptionForm').serialize(),
                    url: "/admin/home/task/descriptions",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        taskDescription()
                        toastr.success('👋 Added Successfully!', 'Success')
                    },
                    error: function(data) {
                        toastr.error('👋 Not Added', 'Error')
                    }
                });
                $('#add').modal('hide')
                // resetValues()
            }
        });
        $('#updateBtn').on('click', function() {
            if ($("#taskDesctioptionForm").valid()) {
                $.ajax({
                    data: $('#taskDesctioptionForm').serialize(),
                    url: "/admin/home/task/descriptions/update",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        taskDescription()
                        toastr.success('👋 Updated Successfully!', 'Success')
                    },
                    error: function(data) {
                        toastr.error('👋 Not Updated', 'Error')
                    }
                });
                $('#add').modal('hide')
                // resetValues()
            }
        });

        manageTask = function($manage) {
            $.ajax({
                url: '/admin/home/task/descriptions/manage',
                data: {
                    'task_ids': task_ids,
                    'manage': $manage,
                },
                type: 'GET',
                success: function(data) {
                    taskDescription()
                    toastr.success('👋 seleted successfully!', 'Success')
                }
            })

            task_ids = []
            totalTaskId = []
            $('#taskcheckAllID').prop('checked', false)
            $(".taskBtn").prop('disabled', true)
        }
    </script>
@endpush
