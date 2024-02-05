@extends('layouts.Admin.master')
@section('title') Dashboard @endsection

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1') Admin @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">
                                        Hours
                                    </p>

                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                        <span class="counter-value" data-target="{{ $data['total_hour'] }}">0</span>
                                    </h2>

                                    <p class="mb-0 text-muted">
                                        Weekend In
                                        {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                                    </p>
                                </div>

                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-primary-subtle rounded-circle fs-2">
                                            <i data-feather="trending-up" class="text-primary"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">
                                        Earnings
                                    </p>

                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                        <span class="counter-value" data-target="{{ $data['total_amount'] }}">0</span>$
                                    </h2>

                                    <p class="mb-0 text-muted">
                                        Weekend In
                                        {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                                    </p>
                                </div>

                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                            <i data-feather="dollar-sign" class="text-info"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">
                                        Clients
                                    </p>

                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                        <span class="counter-value" data-target="{{ $data['total_client'] }}">0</span>
                                    </h2>

                                    <p class="mb-0 text-muted">
                                        Weekend In
                                        {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                                    </p>
                                </div>

                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-danger-subtle rounded-circle fs-2">
                                            <i data-feather="user" class="text-danger"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">
                                        Sites
                                    </p>

                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                        <span class="counter-value" data-target="{{ $data['total_sites'] }}">0</span>
                                    </h2>

                                    <p class="mb-0 text-muted">
                                        Weekend In
                                        {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                                    </p>
                                </div>

                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success-subtle rounded-circle fs-2">
                                            <i data-feather="box" class="text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Weekly Hour - ({{ $data['weekly_hour'] }})
                    </h4>
                </div>

                <div class="card-body p-0 pb-2">
                    <div class="pt-3 d-flex justify-content-center">
                        <p class="mb-0 text-muted">
                            Weekend In
                            {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                        </p>
                    </div>

                    <div>
                        <div id="statistics-order-chart" data-colors='["--vz-success", "--vz-light"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Client Portions
                    </h4>
                </div>

                <div class="card-body">
                    <div id="client-portions-chart" data-colors='["--vz-primary", "--vz-warning", "--vz-info"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-8">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Revenue & Expenses
                    </h4>
                </div>

                <div class="card-body px-0">
                    <div id="revenue-expenses-report-chart" data-colors='["--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Last 6 months Earnings & Hours
                    </h4>
                </div>

                <div class="card-body px-5">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="row g-5">
                            <div class="md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">
                                            Hours
                                        </p>
    
                                        <h2 class="mt-4 ff-secondary fw-semibold">
                                            <span class="counter-value" data-target="{{ $data['hours_last_six'] }}">0</span>
                                        </h2>
                                    </div>
    
                                    <div class="mt-3">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded-circle fs-2">
                                                <i data-feather="trending-up" class="text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">
                                            Earnings
                                        </p>
    
                                        <h2 class="mt-4 ff-secondary fw-semibold">
                                            <span class="counter-value" data-target="{{ $data['amount_last_six'] }}">0</span>$
                                        </h2>
                                    </div>
    
                                    <div class="mt-3">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                <i data-feather="dollar-sign" class="text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Top Five Earners
                    </h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col">Name</th>
                                    <th scope="col">Hours</th>
                                    <th scope="col">Amount</th>
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
                                            <img class="avatar-xs rounded-circle me-2" src="{{ asset($image) }}" alt=""/>
                                            <span>{{ $val->fname }} {{ $val->mname }} {{ $val->lname }}</span>
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

                    <div class="mt-5 p-2 d-flex justify-content-between align-items-center">
                        <p class="mb-0 text-muted me-3">
                            Weekend In
                            {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                        </p>

                        <form action="{{ route('searchData') }}" method="post">
                            @csrf
    
                            <input type="text" name="start_date" hidden value="{{ \Carbon\Carbon::now()->subWeeks(2)->startOfWeek() }}" />
                            <input type="text" name="end_date" hidden value="{{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek() }}" />
    
                            <button type="submit" class="btn btn-primary">
                                View All
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Client Wise Job
                    </h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col">Name</th>
                                    <th scope="col">Hours</th>
                                    <th scope="col">Amount</th>
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
                                            <img class="avatar-xs rounded-circle me-2" src="{{ asset($image) }}" alt=""/>
                                            <span>{{ $val->client->cname }}</span>
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

                    <div class="mt-5 p-2 d-flex justify-content-between align-items-center">
                        <p class="mb-0 text-muted me-3">
                            Weekend In
                            {{ \Carbon\Carbon::now()->subWeeks(2)->endOfWeek()->format('d F, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Monthly Expenses
                    </h4>
                </div>

                <div class="card-body px-3">
                    <div class="row g-3">
                        @foreach ($data['monthly_expense'] as $i => $val)
                            <div class="xxl-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6>
                                            {{ \Carbon\Carbon::now()->subMonths($i)->format('F') }}
                                        </h6>
                                    </div>
                                    

                                    <div>
                                        <h6 class="text-{{ $val == 0 ? 'danger' : 'success' }}">
                                            ${{ $val }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-8">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Tasks
                    </h4>
                </div>

                <div class="card-body px-3">
                    <div class="mb-3">
                        <div class="d-flex justify-content-end gap-2">
                            <button id="showAddModal" type="button" class="btn btn-sm btn-primary">
                                <i data-feather="plus"></i> 
                            </button>
    
                            <button type="button" class="btn btn-sm btn-danger taskBtn" onclick="manageTask('delete')" disabled>
                                <i data-feather="trash-2"></i> 
                            </button>
    
                            <button type="button" class="btn btn-sm btn-success taskBtn" onclick="manageTask('completed')" disabled>
                                <i data-feather="arrow-up"></i> 
                            </button>
    
                            <button type="button" class="btn btn-sm btn-secondary taskBtn" onclick="manageTask('incomplete')" disabled>
                                <i data-feather="arrow-down"></i> 
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-hover table-nowrap align-middle mb-0" id="eventClickTable">
                                <thead class="table-light">
                                    <tr class="text-muted">
                                        <th>
                                            <input type="checkbox" class="form-check-input ms-0" onclick="taskcheckAllID()" id="taskcheckAllID">
                                        </th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
    
                                <tbody id="taskTbody">
                                    <tr>
                                        <td colspan="4"> 
                                            <div class="d-flex justify-content-center align-items-center">
                                                <h4 class="mt-4 ff-secondary fw-semibold">No Data Found</h2>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.Admin.modals.task-modal')
@endsection

@section('script')
    <script src="{{ URL::asset('app-assets/velzon/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('app-assets/velzon/js/pages/dashboard-analytics.init.js') }}"></script>
    <script src="{{ URL::asset('app-assets/velzon/js/pages/dashboard-crm.init.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

    <script>
        let task_ids = []
        let totalTaskId = []

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

        function showTasks(allTasks) {
            task_ids = []
            totalTaskId = []

            let taskRows = ''
            $.each(allTasks, function(index, data) {
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
                    <td><input type="checkbox" class="taskCheckID form-check-input ms-0" value="` + data.id + `"></td>
                    <td>` + data.description + `</td>
                    <td class="` + status + `">` + data.status.toUpperCase() + `</td>
                    <td><button data-copy="true" edit-it="true" class="edit-btn btn btn-sm btn-primary" data-id="` + data.id +
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

        $('#addBtn').on('click', function() {
            console.log("#taskDescriptionForm")
            if ($("#taskDescriptionForm").valid()) {
                $.ajax({
                    data: $('#taskDescriptionForm').serialize(),
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
        })

        $('#updateBtn').on('click', function() {
            if ($("#taskDescriptionForm").valid()) {
                $.ajax({
                    data: $('#taskDescriptionForm').serialize(),
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
        })

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
@endsection