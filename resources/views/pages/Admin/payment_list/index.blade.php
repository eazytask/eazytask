@extends('layouts.Admin.master')
@push('styles')
    <meta name="_token" content="{{ csrf_token() }}">
@endpush
@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Payment
        @endslot
        @slot('title')
            Payment List
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="searchForm">
                        @csrf
                        <div class="row row-xs g-2">
                            <div class="col-lg-4 mt-2">
                                <input type="text" name="start_date" required class="form-control format-picker" placeholder="Roster Date From" id="start_date">
                            </div>
                            <div class="col-lg-4 mt-2">
                                <input type="text" name="end_date" required class="form-control format-picker" placeholder="Roster Date To" id="end_date"/>
                            </div>

                            <div class="col-lg-4 mt-2">
                                <select class="form-control select2" name="employee_id" id="employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->fname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-lg-3 mt-2">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn btn-primary" id="btn_search" onclick="searchNow()">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-info text-center border-primary taskBtn" onclick="invoiceSend()" disabled>Send Invoice</button>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="table-hover-animation">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-bordered table-striped text-center text-capitalize">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" onclick="taskcheckAllID()" id="taskcheckAllID"></th>
                                        <th>Employee Name</th>
                                        <th>Date Issued</th>
                                        <th>Total Hours</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
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
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-left: 10px;
        }
        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-right: 20px;
        }
        .dataTables_wrapper .dt-buttons{
            margin-right: 20px;
        }
    </style>
@endpush

@push('scripts')

    @include('components.datatablescript')
    <script>
        let task_ids = []
        let totalTaskId = []
        let event_id = null

        const dataTableTitle = 'Payment List Report';
        const dataTableOptions = {
            "drawCallback": function(settings) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            },
            dom: 'Blfrtip',
            lengthMenu: [30, 50,
                100, 200
            ],
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
                    className: 'buttons-csv buttons-html5'
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: dataTableTitle
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: dataTableTitle
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible',
                    },
                    title: dataTableTitle
                }
            ],
            initComplete: function() {
                let table = this.api();

                let search = `<div class="search-box">
                                <input type="text" class="form-control form-control-sm search" placeholder="Search for Custom Report...">
                                <i class="ri-search-line search-icon"></i>
                            </div>`;
                $('#myTable_filter').html(search);
                $('.search').on('keyup', function(){
                    table.search( this.value ).draw();
                });
                $('select[name="myTable_length"]').addClass('form-control select2');
            },
        }
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
        function searchNow() {
            $.ajax({
                url: '/admin/home/payment/list/search',
                type: 'POST',
                dataType: 'json',
                data: $('#searchForm').serialize(),
                success: function(data) {
                    totalTaskId= data.totalTaskId
                    $('#myTable').DataTable().clear().destroy();
                    $('#tBody').html(data.data);
                    feather.replace();
                    // $("#myTable").dataTable();
                    $('#myTable').DataTable(dataTableOptions);
                },
                error:function(err){
                    console.log(err)
                }
            });
        }
        searchNow()

        function invoiceSend(timekeepers){
            $.ajax({
                url: '/admin/home/payment/invoice/send',
                type: 'POST',
                data: JSON.stringify({ ids: task_ids, _token: "{{ csrf_token() }}", }),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(data) {
                    // searchNow()
                    toastr['success']('👋 Send Successfully', 'Success!', {
                        closeButton: true,
                        tapToDismiss: false,
                    });
                }
        })
        }
    </script>
@endpush

