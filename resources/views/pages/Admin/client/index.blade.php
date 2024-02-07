@extends('layouts.Admin.master')

@section('')
    @component('components.breadcrumb')
    @slot('li_1')
        Client
    @endslot
    @slot('title')
        Profiles
    @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i
                                    class="ri-add-fill me-1 align-bottom"></i> Add Company</button>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="hstack text-nowrap gap-2">
                                <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i
                                        class="ri-delete-bin-2-line"></i></button>
                                <button class="btn btn-danger"><i
                                        class="ri-filter-2-line me-1 align-bottom"></i> Filters</button>
                                <button class="btn btn-soft-success">Import</button>
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
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="search-box">
                                <input type="text" class="form-control search" placeholder="Search for company...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted">Sort by: </span>
                                <select class="form-control mb-0" data-choices data-choices-search-false
                                    id="choices-single-default">
                                    <option value="Owner">Owner</option>
                                    <option value="Company">Company</option>
                                    <option value="location">Location</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example" class="display table table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Extn.</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                        </thead>
                        <tbody id="clientBody"></tbody>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Extn.</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="close-modal"></button>
                                </div>
                                <form class="tablelist-form" autocomplete="off">
                                    <div class="modal-body">
                                        <input type="hidden" id="id-field" />
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="text-center">
                                                    <div class="position-relative d-inline-block">
                                                        <div class="position-absolute bottom-0 end-0">
                                                            <label for="company-logo-input" class="mb-0"
                                                                data-bs-toggle="tooltip" data-bs-placement="right"
                                                                title="Select Image">
                                                                <div class="avatar-xs cursor-pointer">
                                                                    <div
                                                                        class="avatar-title bg-light border rounded-circle text-muted">
                                                                        <i class="ri-image-fill"></i>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <input class="form-control d-none" value=""
                                                                id="company-logo-input" type="file"
                                                                accept="image/png, image/gif, image/jpeg">
                                                        </div>
                                                        <div class="avatar-lg p-1">
                                                            <div class="avatar-title bg-light rounded-circle">
                                                                <img src="{{ URL::asset('build/images/users/multi-user.jpg') }}"
                                                                    alt="" id="companylogo-img"
                                                                    class="avatar-md rounded-circle object-fit-cover">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h5 class="fs-13 mt-3">Company Logo</h5>
                                                </div>
                                                <div>
                                                    <label for="companyname-field" class="form-label">Name</label>
                                                    <input type="text" id="companyname-field" class="form-control"
                                                        placeholder="Enter company name" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="owner-field" class="form-label">Owner
                                                        Name</label>
                                                    <input type="text" id="owner-field" class="form-control"
                                                        placeholder="Enter owner name" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="industry_type-field" class="form-label">Industry
                                                        Type</label>
                                                    <select class="form-select" id="industry_type-field">
                                                        <option value="">Select industry type</option>
                                                        <option value="Computer Industry">Computer
                                                            Industry</option>
                                                        <option value="Chemical Industries">Chemical
                                                            Industries</option>
                                                        <option value="Health Services">Health Services
                                                        </option>
                                                        <option value="Telecommunications Services">
                                                            Telecommunications Services</option>
                                                        <option value="Textiles: Clothing, Footwear">
                                                            Textiles: Clothing, Footwear</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="star_value-field" class="form-label">Rating</label>
                                                    <input type="text" id="star_value-field" class="form-control"
                                                        placeholder="Enter rating" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="location-field" class="form-label">location</label>
                                                    <input type="text" id="location-field" class="form-control"
                                                        placeholder="Enter location" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="employee-field" class="form-label">Employee</label>
                                                    <input type="text" id="employee-field" class="form-control"
                                                        placeholder="Enter employee" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="website-field" class="form-label">Website</label>
                                                    <input type="text" id="website-field" class="form-control"
                                                        placeholder="Enter website" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="contact_email-field" class="form-label">Contact
                                                        Email</label>
                                                    <input type="text" id="contact_email-field" class="form-control"
                                                        placeholder="Enter contact email" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="since-field" class="form-label">Since</label>
                                                    <input type="text" id="since-field" class="form-control"
                                                        placeholder="Enter since" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" id="add-btn">Add
                                                Company</button>
                                            {{-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end add modal-->

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
        <!--end col-->
        <div class="col-xxl-3">
            <div class="card" id="company-view-detail">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <div class="avatar-md">
                            <div class="avatar-title bg-light rounded-circle">
                                <img src="{{ URL::asset('build/images/brands/mail_chimp.png') }}" alt=""
                                    class="avatar-sm rounded-circle object-fit-cover">
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-3 mb-1">Syntyce Solution</h5>
                    <p class="text-muted">Michael Morris</p>

                    <ul class="list-inline mb-0">
                        <li class="list-inline-item avatar-xs">
                            <a href="javascript:void(0);"
                                class="avatar-title bg-success-subtle text-success fs-15 rounded">
                                <i class="ri-global-line"></i>
                            </a>
                        </li>
                        <li class="list-inline-item avatar-xs">
                            <a href="javascript:void(0);" class="avatar-title bg-danger-subtle text-danger fs-15 rounded">
                                <i class="ri-mail-line"></i>
                            </a>
                        </li>
                        <li class="list-inline-item avatar-xs">
                            <a href="javascript:void(0);"
                                class="avatar-title bg-warning-subtle text-warning fs-15 rounded">
                                <i class="ri-question-answer-line"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Information</h6>
                    <p class="text-muted mb-4">A company incurs fixed and variable costs such as the
                        purchase of raw materials, salaries and overhead, as explained by
                        AccountingTools, Inc. Business owners have the discretion to determine the
                        actions.</p>
                    <div class="table-responsive table-card">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium" scope="row">Industry Type</td>
                                    <td>Chemical Industries</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row">Location</td>
                                    <td>Damascus, Syria</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row">Employee</td>
                                    <td>10-50</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row">Rating</td>
                                    <td>4.0 <i class="ri-star-fill text-warning align-bottom"></i></td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row">Website</td>
                                    <td>
                                        <a href="javascript:void(0);"
                                            class="link-primary text-decoration-underline">www.syntycesolution.com</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row">Contact Email</td>
                                    <td>info@syntycesolution.com</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row">Since</td>
                                    <td>1995</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
@endsection
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
                            <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#addClient" id="add">
                                <i class="ri-add-fill me-1 align-bottom"></i>Add Client
                            </button>
                            @include('pages.Admin.client.modals.clientaddmodal')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card" id="client_list">
                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table class="display table table-bordered dt-responsive mt-3" id="example">
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
    {{-- <script src="{{ asset('/app-assets/velzon/libs/list.js/list.min.js') }}"></script>
    <script src="{{ asset('/app-assets/velzon/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ asset('/app-assets/velzon/js/pages/crm-companies.init.js') }}"></script>
    <script src="{{ asset('/app-assets/velzon/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/app-assets/velzon/js/app.js') }}"></script> --}}
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
        .buttons-columnVisibility{
            text-align: left;
        }
        .buttons-columnVisibility:after {
            content: '\2714';
            left: 15px;
            top: 6px;
            position: absolute;
            font-size: 12px;
            display: inline-block;
        }
        .buttons-columnVisibility:not(.active):after{
            display: none;
        }
        .buttons-columnVisibility span{
            vertical-align:top;
        }
        .buttons-columnVisibility:before {
            content: '\25a2';
            display: inline-block;
            font-size: 20px;
            margin-right: 5px;
        }

    </style>
@endpush
