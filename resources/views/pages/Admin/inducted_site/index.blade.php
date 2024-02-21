@extends('layouts.Admin.master')

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Employee
        @endslot
        @slot('title')
            Inducted Site
        @endslot
    @endcomponent
    @component('components.employeeTab')
        @slot('active') inducted_site @endslot
    @endcomponent

    <div class="">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-info" id="add" disabled>
                    <i class="ri-add-fill me-1 align-bottom"></i> Add Inducted Site
                </button>
            </div>
            @include('pages.Admin.inducted_site.modals.addInductedModal')
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-hover-animation table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <!-- <th>Client Name</th> -->
                                <th>Venue Name</th>
                                <th>Induction Date</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inductions as $row)
                                @php
                                    $json = json_encode($row->toArray(), false);
                                    $emp = $row->employee;
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}</td>
                                    <!-- <td>{{ $row->client->cname }}</td> -->
                                    <td>{{ $row->project->pName }}</td>
                                    <td>{{ $row->induction_date }}</td>
                                    <td>{{ $row->remarks }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item edit-btn" data-row="{{ $json }}">Edit</button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item del" url="/admin/home/inducted/site/delete/{{ $row->id }}">Delete</a>
                                                </li>
                                            </ul>
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
@endsection
@section('')
    @include('sweetalert::alert')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Induction</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Induction Lists
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="table-hover-animation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" id="add"><i data-feather='plus'></i></button>
                </div>
                @include('pages.Admin.inducted_site.modals.addInductedModal')

                <div class="container">

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
    @include('components.select2')
    @include('components.datatablescript')
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
    <script src="{{asset('app-assets/velzon/libs/moment/moment.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#add').removeAttr('disabled');
            const dataTableTitle = 'Inducted Site';
            const dataTableOptions = {
                "drawCallback": function(settings) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                },
                dom: 'Blfrtip', // Include 'l' for length menu
                lengthMenu: [30, 50,
                    100, 200
                ], // Set the options for the number of records to display
                title: 'test',
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
                                    <input type="text" class="form-control form-control-sm search" placeholder="Search for Inducted Site">
                                    <i class="ri-search-line search-icon"></i>
                                </div>`;
                    $('#example_filter').html(search);
                    $('.search').on('keyup', function(){
                        table.search( this.value ).draw();
                    });
                },
            }
            $('#example').DataTable(dataTableOptions);

            $('.select2').select2({
                placeholder: 'Select Option',
                dropdownParent: $('#addInduction'),
                allowClear: true
            });

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

            $("#newModalForm").validate({
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
            });

            $(document).on("click", ".edit-btn", function() {
                resetValue()
                var rowData = $(this).data("row");

                $("#id").val(rowData.id);

                $("#employee_id").val(rowData.employee_id).trigger('change');
                // $("#client_id").val(rowData.client_id)
                $("#induction_date").val(moment(rowData.induction_date).format('DD-MM-YYYY'))
                $("#project_id").val(rowData.project_id).trigger('change');
                $("#remarks").val(rowData.remarks)

                $('#newModalForm').attr('action', "{{ route('update-induction') }}");
                $("#savebtn").hide()
                $("#updatebtn").show()

                $("#addInduction").modal("show")
            })

            $(document).on("click", "#add", function() {
                resetValue()
                $("#addInduction").modal("show")
            })

            function resetValue() {
                $("#id").val('');
                $("#employee_id").val('');
                // $("#client_id").val('')
                $("#induction_date").val('')
                $("#project_id").val('').trigger('change');
                $("#remarks").val('')

                $('#newModalForm').attr('action', "{{ route('store-induction') }}");
                $("#savebtn").show()
                $("#updatebtn").hide()
            }

        })
    </script>
@endpush
