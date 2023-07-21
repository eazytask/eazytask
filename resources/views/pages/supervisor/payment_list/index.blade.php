@extends('layouts.Admin.master')
@push('styles')
<meta name="_token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

@endpush

@section('admincontent')

<div class="col-lg-12 col-md-12">
    <div class="card p-0">
        <div class="container">
            <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
                <h3 class="card-title text-primary d-inline">
                    Select Venue Dates
                </h3>
                <span class="float-right">
                    <i class="fa fa-chevron-up clickable"></i>
                </span>
            </div>
            <div class="card-body">

                <form id="searchForm">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-md-5 col-lg-4 mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Roster Date From" id="start_date">
                        </div>
                        <div class="col-md-5 col-lg-4 mt-1">
                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="Roster Date To" id="end_date"/>
                        </div>

                        <div class="col-md-5 col-lg-4 mt-1">
                            <select class="form-control select2" name="employee_id">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->fname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-lg-3 mt-1">
                            <button type="button" class="btn btn btn-outline-primary btn-block" id="btn_search" onclick="searchNow()"><i data-feather="search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <div class="row" id="table-hover-animation">
            <div class="col-12">
                <div class="card">
                    <div class="container">
                        <div class="card plan-card p-2" id="hasData">
                    <div class="row">
                        <!-- <button class="btn btn-outline-primary text-center border-primary mr-25" id="showAddModal"><i data-feather="plus" class="avatar-icon font-medium-3"></i></button> -->
                        <button class="btn btn-gradient-primary text-center border-primary taskBtn" onclick="invoiceSend()" disabled><i data-feather="send" class="avatar-icon font-medium-3"></i></button>

                    </div>
                </div>
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

</div>


<script type="text/javascript">
</script>
@push('scripts')
<script>
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
    function searchNow() {
        $.ajax({
            url: '/supervisor/home/payment/list/search',
            type: 'POST',
            dataType: 'json',
            data: $('#searchForm').serialize(),
            success: function(data) {
                totalTaskId= data.totalTaskId
                $('#myTable').DataTable().clear().destroy();
                $('#tBody').html(data.data);
                feather.replace();
                // $("#myTable").dataTable();
                $('#myTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    // "bDestroy": true
                });
            },
            error:function(err){
                console.log(err)
            }
        });
    }
    searchNow()

    function invoiceSend(timekeepers){
        $.ajax({
            url: '/supervisor/home/payment/invoice/send',
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

@endsection