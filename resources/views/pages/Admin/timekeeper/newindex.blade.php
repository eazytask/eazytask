@extends('layouts.Admin.master')
@section('title') Timesheet @endsection
@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

@php
    function getTime($date) {
        return \Carbon\Carbon::parse($date)->format('H:i');
    }

    $fromRoaster="Start Date";
    $toRoaster = "End Date";
    
    if (Session::get('fromRoaster')) {
        $fromRoaster = \Carbon\Carbon::parse(Session::get('fromRoaster'))->format('d-m-Y');
    }

    if (Session::get('toRoaster')) {
        $toRoaster = \Carbon\Carbon::parse(Session::get('toRoaster'))->format('d-m-Y');
    }
@endphp

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1') Timesheet @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <div class="card">
        <div class="card-header">
            <form action="{{ route('search-timekeeper') }}" method="POST" id="dates_form">
                @csrf
                <div class="d-flex justify-content-start align-items-start gap-2">
                    <input type="text" name="start_date" required class="form-control disable-picker" placeholder="{{$fromRoaster}}" />
    
                    <input type="text" name="end_date" required class="form-control disable-picker" placeholder="{{$toRoaster}}" />
    
                    <button type="submit" class="btn btn-sm btn-primary" id="btn_search"><i data-feather='search'></i></button>

                    <button type="button" id="createShedule" class="btn btn-sm btn-primary">
                        <i data-feather='plus'></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body p-3">
            <div class="row g-4">
                <div class="col-xxl-12">
                    <div class="table-responsive">
                        <table id="data-table" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th width='150px'>#</th>
                                    <th width='150px'>Employee</th>
                                    <!-- <th>Client</th> -->
                                    <th width='150px'>Venue</th>
                                    <th width='150px'>Roster Date</th>
                                    <th width='150px'>Shift Start</th>
                                    <th width='150px'>Shift End</th>
                                    <th width='150px'>Duration</th>
                                    <th width='150px'>Rate</th>
                                    <th width='150px'>Amount</th>
                                    <th width='150px'>Action</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                @foreach ($timekeepers as $k => $row)
                                    @php
                                        $json = json_encode($row->toArray(), false);
                                    @endphp
                                    <tr>
                                        <td>
                                            @if ($row->is_approved)
                                                <i class="{{ $row->payment_status ? 'mdi mdi-currency-usd' : 'mdi mdi-checkbox-marked-circle-outline'}} text-primary fs-5"></i>
                                            @else
                                                <span class="pl-1 ml-25"></span>
                                            @endif

                                            <span>{{ $k + 1 }}</span>
                                        </td>
    
                                        <td>
                                            {{ $row->employee->fname }} {{ $row->employee->mname }} {{ $row->employee->lname }}
                                        </td>
    
                                        <td>
                                            @if (isset($row->project->pName))
                                                {{ $row->project->pName }}
                                            @else
                                                Null
                                            @endif
                                        </td>
    
                                        <td>
                                            {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                        </td>
    
                                        <td>{{ getTime($row->shift_start) }}</td>
    
                                        <td>{{ getTime($row->shift_end) }}</td>
    
                                        <td>{{ $row->duration }}</td>
    
                                        <td>{{ $row->ratePerHour }}</td>
    
                                        <td>{{ $row->amount }}</td>
    
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center gap-2">
                                                @if($row->is_approved == 0)
                                                <button data-copy="true" edit-it="true" class="edit-btn btn btn-sm btn-info" data-employee="{{$row->employee}}" data-row="{{ $json }}"><i data-feather='edit'></i></button>
                                                
                                                <button data-copy="true" class="edit-btn btn btn-sm btn-info" data-row="{{ $json }}">
                                                    <i data-feather='copy'></i>
                                                </button>
                                                
                                                <button class="edit-btn btn btn-sm btn-danger text-white" >
                                                    <a class="del text-white" url="/admin/home/new/timekeeper/delete/{{ $row->id }}"><i data-feather='trash-2'></i></a>
                                                </button>
                                                
                                                @else
                                                    <button data-copy="true" edit-it="true" class="edit-btn btn btn-sm btn-primary" data-employee="{{$row->employee}}" data-row="{{ $json }}"><i data-feather='eye'></i></button>
                                                @endif
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
    </div>

    <div>
        @include('pages.Admin.timekeeper.modals.newtimeKeeperAddModal')
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on("click", "#emloyee_wise", function() {
            const element = document.getElementById('employee_pdf').innerHTML;
            var opt = {
                filename: 'unschedule-employee.pdf',
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
        })
        $(document).on("click", "#client_wise", function() {
            const element = document.getElementById('client_pdf').innerHTML;
            var opt = {
                filename: 'unschedule-client.pdf',
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
        })
    </script>

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
                    if (data.notification) {
                        toastr.success(data.notification);
                    }

                    // Destroy existing Choices instance
                    let employeeSelect = $('#employee_id').get(0);
                    if (employeeSelect && employeeSelect.choices) {
                        employeeSelect.choices.destroy();
                    }

                    // Clear existing options
                    $('#employee_id').empty();

                    // Add new options without replacing existing ones
                    jQuery.each(data.employees, function(i, val) {
                        let optionHtml = "<option value='" + val.id + "'>" + val.fname + " " + ((val.mname) ? val.mname : '') + "" + val.lname + "</option>";
                        $('#employee_id').append(optionHtml);
                    });

                    // Reinitialize Choices
                    new Choices(employeeSelect);

                    // You can also trigger the 'change' event if needed
                    // $('#employee_id').trigger('change');
                },
                error: function(err) {
                    console.log(err);
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
                console.log(rowData.project_id);
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
@endpush