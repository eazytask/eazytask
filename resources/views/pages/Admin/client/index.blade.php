@extends('layouts.Admin.master')


@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Client
        @endslot
        @slot('title')
            Profiles
        @endslot
    @endcomponent
    <!-- start page title -->
    <div class="content-header row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#addClient" id="add"><i
                                    class="ri-add-fill me-1 align-bottom"></i>Add Client</button>
                                @include('pages.Admin.client.modals.clientaddmodal')
                        </div>
                        <div class="flex-shrink-0">
                            <div class="hstack text-nowrap gap-2">
                                <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i
                                        class="ri-delete-bin-2-line"></i></button>
                                <button class="btn btn-danger"><i
                                        class="ri-filter-2-line me-1 align-bottom"></i> Filters</button>
                                <button class="btn btn-soft-success" id="download">Import</button>
                                <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown"
                                    aria-expanded="false" class="btn btn-soft-info"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                    <li><a class="dropdown-item" href="#">All</a></li>
                                    <li><a class="dropdown-item" href="#">Last Week</a></li>
                                    <li><a class="dropdown-item" href="#">Last Month</a></li>
                                    <li><a class="dropdown-item" href="#">Last Year</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card" id="client_list">
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="example">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sort" data-sort="serial" scope="col">#</th>
                                        <th class="sort" data-sort="name" scope="col">Name</th>
                                        <th class="sort" data-sort="c_person" scope="col">Contact Person</th>
                                        <th class="sort" data-sort="c_no" scope="col">Contact No</th>
                                        <th class="sort" data-sort="email" scope="col">Email</th>
                                        <th class="sort" data-sort="site" scope="col">No of Sites</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all" id="clientBody">

                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ companies
                                        We did not find any
                                        companies for you search.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
                        aria-labelledby="deleteRecordLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="btn-close deleteRecord-close"></button>
                                </div>
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                                    </lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4 class="fs-semibold">You are about to delete a company ?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your company will
                                            remove all of your information from our database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i>
                                                Close</button>
                                            <button class="btn btn-danger" id="delete-record">Yes,
                                                Delete It!!</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end delete modal -->

                </div>
            </div>
            <!--end card-->
        </div>
    </div>
    <!-- end page title -->
@endsection

@push('scripts')
    @include('components/datatablescript')
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
    <script src="{{asset('app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/forms/form-wizard.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/app-calendar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/form-wizard.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">

    <script>
        function handleStatusChange() {
            var status = $('#status_id').val();
            $.ajax({
                url: '/admin/home/fetch/client?status=' + status,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if (data.data) {
                        if (data.data.length > 100) {
                            $('#example').DataTable().clear().destroy();
                        }
                        $('#clientBody').html(data.data)
                        if (data.data.length > 100) {
                            $('#example').DataTable({
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
                                buttons: [{
                                        extend: 'copy',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    }
                                ]
                            });
                        }

                    }

                    $("#addClient").modal("hide")
                },
                error: function(err) {
                    console.log(err)
                }
            });
        }

        $(document).on("click", "#download", function() {
            $(".dt-buttons .buttons-copy").toggle()
            $(".dt-buttons .buttons-csv").toggle()
            $(".dt-buttons .buttons-excel").toggle()
            $(".dt-buttons .buttons-pdf").toggle()
            $(".dt-buttons .buttons-print").toggle()
        })

        $(document).ready(function() {
            encodeImageFileAsURL = function(element) {
                var file = element.files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    $('#cimage').val(reader.result)
                    //   console.log(reader.result)
                }
                reader.readAsDataURL(file);
            }

            window.fetchData = fetchClients = function() {
                $.ajax({
                    url: '/admin/home/fetch/client',
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if (data.data) {
                            console.log($('#example'));
                            $('#example').DataTable().clear().destroy();
                            $('#clientBody').html(data.data);
                            console.log($('#example'));
                            $('#example').DataTable({
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
                                buttons: [{
                                        extend: 'copy',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        exportOptions: {
                                            columns: [0, 1, 2, 3, 4, 5]
                                        }
                                    }
                                ]
                            });
                        }

                        $("#addClient").modal("hide")
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
            fetchClients()

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
                            $.ajax({
                                url: '/admin/home/client/delete/' + $(this).data("id"),
                                type: 'get',
                                dataType: 'json',
                                success: function(data) {
                                    toastr[data.alertType](data.message, {
                                        closeButton: true,
                                        tapToDismiss: false,
                                    });
                                    fetchClients()
                                },
                                error: function(err) {
                                    console.log(err)
                                }
                            });
                        }
                    });
            })

            // $("#newModalForm").validate()
            $(document).on("click", ".edit-btn", function() {
                resetValue()
                var rowData = $(this).data("row");

                $("#id").val(rowData.id);
                $("#cname").val(rowData.cname);
                $("#cemail").val(rowData.cemail)

                $("#cnumber").val(rowData.cnumber)
                $("#cstate").val(rowData.cstate)
                $("#caddress").val(rowData.caddress)
                $("#suburb").val(rowData.suburb)
                $("#cpostal_code").val(rowData.cpostal_code)
                $("#cperson").val(rowData.cperson)
                $("#status").val(rowData.status).trigger('change')

                // $('#newModalForm').attr('action', "{{ route('update-client') }}");
                window.formAction = "{{ route('update-client') }}"
                // $("#savebtn").hide()
                // $("#updatebtn").show()

                $("#buttom_bar").attr('style', 'display:flex !important')
                $("#addClient").modal("show")
            })

            $(document).on("click", "#add", function() {
                resetValue()
                $("#addClient").modal("show")
            })

            function resetValue() {
                $("#buttom_bar").attr('style', 'display:none !important')
                $("#id").val('');
                $("#cname").val('')
                $("#cemail").val('')

                $("#cnumber").val('')
                $("#cstate").val('')
                $("#caddress").val('')
                $("#suburb").val('')
                $("#cpostal_code").val('')
                $("#cperson").val('')
                $("#status").val('').trigger('change')

                // $('#newModalForm').attr('action', "{{ route('store-client') }}");
                window.formAction = "{{ route('store-client') }}"
                // $(".timekeer-btn").html('Submit')
                // $("#updatebtn").prop('hidden','true')
                // $("#savebtn").prop('hidden','false')
                // $("#savebtn").show()
                // $("#updatebtn").hide()
                window.StepperReset()
            }

        })
    </script>


    

    <style>
        .dt-buttons .buttons-copy {
            display: none;
        }

        .dt-buttons .buttons-csv {
            display: none;
        }

        .dt-buttons .buttons-excel {
            display: none;
        }

        .dt-buttons .buttons-pdf {
            display: none;
        }

        .dt-buttons .buttons-print {
            display: none;
        }

        /* Custom styles for DataTables search and length menu alignment */
        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-left: 10px;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-right: 20px;
        }
        div.dt-buttons {
            padding:1rem;
            padding-bottom: 0;
        }
        table.dataTable>thead .sorting:before, table.dataTable>thead .sorting_asc:before, table.dataTable>thead .sorting_desc:before, table.dataTable>thead .sorting_asc_disabled:before, table.dataTable>thead .sorting_desc_disabled:before {
            content: "" !important;
        }
        table.dataTable>thead .sorting:after, table.dataTable>thead .sorting_asc:after, table.dataTable>thead .sorting_desc:after, table.dataTable>thead .sorting_asc_disabled:after, table.dataTable>thead .sorting_desc_disabled:after {
            content: "" !important;
        }
        .dropdown-item.del{
            cursor: pointer;
        }
    </style>
@endpush
