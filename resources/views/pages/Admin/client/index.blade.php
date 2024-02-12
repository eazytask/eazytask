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
                            <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#addClient" id="add" disabled>
                                <i class="ri-add-fill me-1 align-bottom"></i>Add Client
                            </button>
                            @include('pages.Admin.client.modals.clientaddmodal')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-12">
            <div class="card" id="client_list">
                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table class="display table table-bordered mt-3" id="example">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact No</th>
                                    <th>Email</th>
                                    <th>No of Sites</th>
                                    <th>Action</th>
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
    @include('components.stepper')
    @include('components.datatablescript')
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>

    <script>
        const dataTableTitle = 'Client Report';
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
                                <input type="text" class="form-control form-control-sm search" placeholder="Search for client...">
                                <i class="ri-search-line search-icon"></i>
                            </div>`;
                $('#example_filter').html(search);
                $('.search').on('keyup', function(){
                    table.search( this.value ).draw();
                });
                $('select[name="example_length"]').addClass('form-control select2');
            },
        }
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
                            $('#example').DataTable(dataTableOptions);
                        }

                    }

                    $("#addClient").modal("hide")
                },
                error: function(err) {
                    console.log(err)
                }
            });
        }


        $(document).ready(function() {
            $('#add').removeAttr('disabled');
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
                            $('#example').DataTable(dataTableOptions);
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
            padding-right:1rem;
        }
        .dropdown-item.del{
            cursor: pointer;
        }

    </style>
@endpush
