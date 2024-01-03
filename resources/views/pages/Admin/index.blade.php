@extends('layouts.Admin.master')

@section('admincontent')
    @include('sweetalert::alert')
    @if (auth()->user()->company_roles->contains('role', 5))
        <h1>Welcome to Operation Dashboard</h1>
    @elseif(auth()->user()->company_roles->contains('role', 6))
        <h1>Welcome to Manager Dashboard</h1>
    @elseif(auth()->user()->company_roles->contains('role', 7))
        <h1>Welcome to Account Dashboard</h1>
    @else
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
                                    <h6>Weekend In {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                                    </h6>
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
                                                {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d M, y') }}
                                            </div>
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
                @include('pages.Admin.event_report.modals.eventClickModal')
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
                                                        <span>{{ $val->fname }} {{ $val->mname }}
                                                            {{ $val->lname }}</span>
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
                                                <th><input type="checkbox" onclick="taskcheckAllID()"
                                                        id="taskcheckAllID">
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
    @endif
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

    {{-- // Event Report --}}
    <script type="text/javascript">
        let eventTitle = $('#title');
        let editEventBtn = $('#editEventBtn');
        var addEventBtn = $('#addEventBtn');
        let info = null;
        let ids = []

        //add event modal
        $('#addEvent').on('click', function() {
            resetValues()
            $('.event-modal-title').html('Add Event')
            $('#editEventBtn').prop('hidden', true)
            $('#addEventBtn').prop('hidden', false)

            $('#addEventModal').modal('show')
        });

        $(document).on("click", ".checkID", function() {
            if ($(this).is(':checked')) {
                if (ids.length < info.extendedProps.no_employee_required) {
                    ids.push($(this).val());
                } else {
                    // If more than 5 checkboxes are checked, uncheck the current checkbox
                    $(this).prop('checked', false);
                }
            } else {
                let id = $(this).val();
                ids = jQuery.grep(ids, function(value) {
                    return value != id;
                });
            }

            updateButtonState();
            updateCheckAll();
        });

        checkAllID = function() {
            let remainingCheckboxes = info.extendedProps.no_employee_required - ids.length;

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
        };



        function updateButtonState() {
            if (ids.length === 0) {
                $("#addToRoaster").prop('disabled', true);
            } else {
                $("#addToRoaster").prop('disabled', false);
            }
        }

        function updateCheckAll() {
            if (ids.length == totalId.length) {
                $('#checkAllID').prop('checked', true);
            } else {
                $('#checkAllID').prop('checked', false);
            }
        }

        $("#addToRoaster").on('click', function() {
            $.ajax({
                url: '/admin/home/event/publish',
                data: {
                    'event_id': info.extendedProps.id,
                    'employee_ids': ids,
                },
                type: 'GET',
                success: function(data) {

                    searchNow('current')
                    toastr.success('👋 Added Successfully', 'Success!', {
                        closeButton: true,
                        tapToDismiss: false,
                    })
                }
            })
            $("#eventClick").modal("hide")

            ids = []
            totalId = []
            $('#checkAllID').prop('checked', false)
            $("#addToRoaster").prop('disabled', true)
        });

        $("#completeEvent").on('click', function() {
            $.ajax({
                url: '/admin/home/event/complete',
                data: {
                    'event_id': info.extendedProps.id,
                },
                type: 'GET',
                success: function(data) {

                    searchNow('current')
                    toastr.success('👋 Completed Successfully', 'Success!', {
                        closeButton: true,
                        tapToDismiss: false,
                    })
                }
            })
            $("#eventClick").modal("hide")

            ids = []
            totalId = []
            $('#checkAllID').prop('checked', false)
            $("#addToRoaster").prop('disabled', true)
        });

        // Add new event
        $(addEventBtn).on('click', function() {
            if ($("#addEventForm").valid()) {
                $.ajax({
                    data: $('#addEventForm').serialize(),
                    url: "/admin/home/upcomingevent/store",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        if (data.status) {
                            toastr.success(data.msg)
                            searchNow('current')
                        } else {
                            toastr.info(data.msg)
                        }
                    },
                    error: function(data) {
                        toastr.error('Add Event Failed', 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        })
                    }
                });
                $('#addEventModal').modal('hide')
                resetValues()
            }
        });

        $(editEventBtn).on('click', function() {
            if ($("#addEventForm").valid()) {
                $.ajax({
                    data: $('#addEventForm').serialize(),
                    url: "/admin/home/upcomingevent/update",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        if (data.status) {
                            toastr.success(data.msg)
                            searchNow('current')
                        } else {
                            toastr.info(data.msg)
                        }
                    },
                    error: function(data) {
                        toastr.error('Failed Update Event', 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        })
                    }
                });
                $('#addEventModal').modal('hide')
                resetValues()
            }
        });

        //event update modal
        $('#editEvent').on('click', function() {
            $('.event-modal-title').html('Update Event')
            $('#editEventBtn').prop('hidden', false)
            $('#addEventBtn').prop('hidden', true)

            $("#eventClick").modal("hide")
            $('#addEventModal').modal('show')
        });

        //delete event
        $('#deleteEvent').on('click', function() {
            event_id = info.extendedProps.id
            $.ajax({
                url: '/admin/home/upcomingevent/delete/' + event_id,
                type: 'GET',
                success: function(data) {

                    searchNow('current')
                    toastr.success('Delete Successfully', 'Success!', {
                        closeButton: true,
                        tapToDismiss: false,
                    })
                }
            })
            $("#eventClick").modal("hide")
        });

        $('#filterStatus').on('change', function() {
            showImployees($('#filterStatus').val(), info)
        });

        //show imployee by status
        function showImployees(status, info) {
            eventToUpdate = info;

            ids = []
            totalId = []
            $('#checkAllID').prop('checked', false)
            event_id = info.extendedProps.id
            let employees = info.extendedProps.employees

            let rows = ''
            $.each(employees, function(index, employee) {
                if (status == 'requested' && employee.requested) {
                    insertThis(employee)
                } else if (status == 'inducted' && employee.inducted) {
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
                    <td><input type="checkbox" class="checkID" value="` + employeeId + `" ` + checkbox_status + `></td>
                    <td>` + employee.fname + `</td>
                    <td>` + employee.contact_number + `</td>
                    <td>` + employee.email + `</td>
                    <td class="` + status + `">` + employee.status + `</td>
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
        // Reset sidebar input values
        function resetValues() {
            //form filed reset
            $('#project_name').val('').trigger('change');
            $('#event_date').val('');
            $('#shift_start').val('');
            $('#shift_end').val('');
            $('#rate').val('');
            $('#remarks').val('');
            $('#no_employee_required').val('');
            $('#job_type_name').val('').trigger('change');
            //form filed reset
        }

        function openEvent(id) {
            console.log(id);
            $.ajax({
                url: "{{ url('/open-event') }}/" + id,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    info = data.events[0];
                    resetValues()
                    $('#event_id').val(info.extendedProps.id);
                    $('#project_name').val(info.extendedProps.project_name).trigger('change');
                    $('#event_date').val(info.extendedProps.event_date);
                    $('#shift_start').val($.time(info.extendedProps.shift_start));
                    $('#shift_end').val($.time(info.extendedProps.shift_end));
                    $('#rate').val(info.extendedProps.rate);
                    $('#remarks').val(info.extendedProps.remarks);
                    $('#no_employee_required').val(info.extendedProps.no_employee_required);
                    $('#job_type_name').val(info.extendedProps.job_type_name).trigger('change');

                    //event click modal
                    $('#eventName').html(info.extendedProps.project.pName).trigger('change');
                    $('#eventShift').html("Shift-Time: " + $.time(info.extendedProps.shift_start) +
                        " to " + $.time(info
                            .extendedProps.shift_end));
                    $('#eventRemarks').html(info.extendedProps.remarks);
                    $('#eventEmployeeRequired').html('Employee Required: ' +
                        info.extendedProps.no_employee_required);

                    //add to roaster
                    window.info = info
                    $('#filterStatus').val('all')
                    showImployees('all', info)

                    $("#eventClick").modal("show")

                    if (eventToUpdate.url) {
                        info.jsEvent.preventDefault();
                        window.open(eventToUpdate.url, '_blank');
                    }
                    eventTitle.val(eventToUpdate.title);
                }
            });
        }

        $(document).ready(function() {

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
                        $('#project').append('<option value="">Select Venue</option>');

                        // Add fetched projects to the project dropdown
                        $.each(data, function(key, value) {
                            $('#project').append('<option value="' + value.id + '">' +
                                value.pName + '</option>');
                        });
                    }
                });
            });

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
                url: '/admin/home/event-report/drag/keeper',
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
            searchNow('current')
        }

        let reload;

        function searchNow(goTo = '', search_date = null) {
            $.ajax({
                url: '/admin/home/event-report/search',
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
                    // $('#myTable').DataTable().clear().destroy();
                    $('#tBody').html(data.data);
                    $('#print_tBody').html(data.report);
                    $('#print_client').html('Client: ' + data.client);
                    $('#print_project').html('Venue: ' + data.project);
                    $('#print_hours').html('Total Hours: ' + data.hours);
                    $('#print_amount').html('Total Amount: $' + data.amount);
                    $('#print_current_week').text('Date: ' + data.week_date)
                    // $("#myTable").DataTable();

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

        function deleteEvent(roasterId) {
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
                            url: '/admin/home/event-report/delete/' + roasterId,
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
                url: '/admin/home/event-report/publish/' + roasterId,
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

        function timekeeperAddFunc() {
            if ($("#timekeeperAddForm").valid()) {
                $.ajax({
                    data: $('#timekeeperAddForm').serialize(),
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
        }

        function timekeeperEditFunc() {
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
        }
    </script>

    <script>
        $(document).ready(function() {
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

            $('input[name="filter_employee"]').on('change', function() {

                console.log('filter')
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
                            html += "<option value='" + emp.id + "' selected>" + emp.fname + " " + ((emp
                                .mname) ? emp.mname : '') + " " + emp.lname + "</option>"
                        }
                        jQuery.each(data.employees, function(i, val) {
                            html += "<option value='" + val.id + "'>" + val.fname + " " + ((val
                                .mname) ? val.mname : '') + "" + val.lname + "</option>"
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
                setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1)
                    .addClass('modal-stack'));
            });

            $(document).on("click", "#addTimekeeperModal", function() {
                resetData()
                $("#project-select").val("").trigger('change');
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
        });
    </script>
@endpush
