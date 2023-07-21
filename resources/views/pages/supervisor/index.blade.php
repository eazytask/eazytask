@extends('layouts.Admin.master')

@section('admincontent')
<style type="text/css">
    .fc-list-event-time {
        display: none;
    }

    .mydate .flatpickr-wrapper {
        display: block;
    }

    .fc-h-event .fc-event-main{
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
            <div class="card card-congratulation-medal">
                <div class="card-body">
                    <h5>Hello {{Auth::user()->name}}!</h5>
                    <p class="card-text font-small-3">Welcome to your dashboard</p>
                    <h3 class="mb-75 mt-2 pt-50">
                        <a href="javascript:void(0);">{{ \Carbon\Carbon::now()->format('d F, Y')}}</a>
                    </h3>
                    <!-- <button type="button" class="btn btn-primary">View Sales</button> -->
                    <img src="{{ asset('images/app/logo.png') }}" class="congratulation-medal mt-2" width="90px" alt="Medal Pic" />
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
                        <p class="card-text font-small-2 mr-25 mb-0">Weekend In {{\Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y')}}</p>
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
                                    <h4 class="font-weight-bolder mb-0">{{$data['total_hour']}}</h4>
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
                                    <h4 class="font-weight-bolder mb-0">${{$data['total_amount']}}</h4>
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
                                    <h4 class="font-weight-bolder mb-0">{{$data['total_client']}}</h4>
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
                                    <h4 class="font-weight-bolder mb-0">{{$data['total_sites']}}</h4>
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
                            <p>Weekend In {{\Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y')}}</p>
                            <h2 class="font-weight-bolder mb-0">{{$data['weekly_hour']}}</h2>
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
                                    <div class="font-small-2">Weekend In {{\Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d M, y')}}</div>
                                    <h5 class="mb-1">${{$data['monthly_earning']}}</h5>
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
                        <h2 class="mb-25">${{$data['amount_last_six']}}</h2>
                        <div class="d-flex justify-content-center">
                            <span class="font-weight-bolder mr-25">Hours:</span>
                            <span>{{$data['hours_last_six']}}</span>
                        </div>
                        <div id="budget-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Revenue Report Card -->





    </div>

    <div class="row match-height">

    <div class="col-lg-6 col-12">

        <div class="card plan-card" id="hasData">
            <div class="card-header">
                <div class="col mt-3 mt-md-0">
                    <span class="h4">Top Five Earners</span>
                    <span class="ml-25 font-small-2">Weekend In {{\Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d M, y')}}</span>
                    <form action="{{route('payment_search')}}" method="post">
                        @csrf
                        <input type="text" name="start_date" hidden value="{{\Carbon\Carbon::now()->subWeeks(2)->startOfWeek()}}" />
                        <input type="text" name="end_date" hidden value="{{\Carbon\Carbon::now()->subWeeks(2)->endOfWeek()}}" />

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
                            @foreach($data['employee_report'] as $i => $val)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-primary mr-1">
                                            <div class="avatar-content">
                                                <img class="img-fluid" src="{{'https://api.eazytask.au/'. $val->employee->image}}" alt="">
                                            </div>
                                        </div>
                                        <span>{{$val->employee->fname}}</span>
                                    </div>
                                </td>
                                <td>{{$val->hours}}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="font-weight-bolder mr-1">${{$val->amount}}</span>
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
                    <span class="ml-25 font-small-2">Weekend In {{\Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d M, y')}}</span>
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
                            @foreach($data['client_report'] as $i => $val)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-primary mr-1">
                                            <div class="avatar-content">
                                                <img class="img-fluid" src="{{$val->client->image}}" alt="">
                                            </div>
                                        </div>
                                        <span>{{$val->client->cname}}</span>
                                    </div>
                                </td>
                                <td>{{$val->hours}}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="font-weight-bolder mr-1">${{$val->amount}}</span>
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

    <!-- Transaction Card -->
    <div class="col-lg-4 col-12">
        <div class="card card-transaction">
            <div class="card-header">
                <h4 class="card-title">Monthly Expense</h4>
            </div>
            <div class="card-body">

                @foreach($data['monthly_expense'] as $i => $val)
                <div class="transaction-item">
                    <div class="media">
                        <div class="avatar bg-light-{{$val == 0 ?'success':'danger'}} rounded">
                            <div class="avatar-content">
                                <i data-feather="{{$val == 0 ?'check':'dollar-sign'}}" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="media-body">
                            <h6 class="transaction-title">{{ \Carbon\Carbon::now()->subMonths($i)->format('F')}}</h6>
                        </div>
                    </div>
                    <div class="font-weight-bolder text-{{$val == 0 ?'success':'danger'}}">${{ $val}}</div>
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
                        <button class="btn btn-outline-primary text-center border-primary mr-25" id="showAddModal"><i data-feather="plus" class="avatar-icon font-medium-3"></i></button>
                        <button class="btn btn-outline-danger text-center border-primary taskBtn mr-25" onclick="manageTask('delete')" disabled><i data-feather="trash-2" class="avatar-icon font-medium-3"></i></button>
                        <button class="btn btn-outline-success text-center border-primary taskBtn mr-25" onclick="manageTask('complete')" disabled><i data-feather="arrow-up" class="avatar-icon font-medium-3"></i></button>
                        <button class="btn btn-outline-secondary text-center border-primary taskBtn" onclick="manageTask('incomplete')" disabled><i data-feather="arrow-down" class="avatar-icon font-medium-3"></i></button>

                    </div>
                    @include('pages.Admin.modals.AddModal')
                </div>
                <div class="row" id="table-hover-animation">
                    <div class="table-responsive">
                        <table id="eventClickTable" class="table text-center table-bordered ">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" onclick="taskcheckAllID()" id="taskcheckAllID"></th>
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
<!-- <script src="{{asset('app-assets/js/scripts/pages/app-calendar-event-request.js')}}"></script> -->
<script src="{{asset('app-assets/js/scripts/pages/supervisor-dashboard-ecommerce.js')}}"></script>
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
            url: '/supervisor/home/task/descriptions',
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
            if (data.status == 'complete') {
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
                    <td><button data-copy="true" edit-it="true" class="edit-btn btn-link btn" data-id="` + data.id + `" data-description="` + data.description + `" data-status="` + data.status + `"><i data-feather='edit'></i></button></td>
                    
                </tr>
                `
        }
        if (totalTaskId == 0) {
            $("#taskcheckAllID").prop('disabled', true)
        }else{
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
                url: "/supervisor/home/task/descriptions",
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
                url: "/supervisor/home/task/descriptions/update",
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
            url: '/supervisor/home/task/descriptions/manage',
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