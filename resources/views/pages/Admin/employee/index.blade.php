@extends('layouts.Admin.master')

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Employee
        @endslot
        @slot('title')
            Profiles
        @endslot
    @endcomponent
    @component('components.employeeTab')
        @slot('active') employee @endslot
    @endcomponent
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
        .dataTables_wrapper .dt-buttons{
            margin-right: 20px;
        }
        .disablediv {
            pointer-events: none;
            opacity: 0.4;
        }
        .select2-error-handle > div > div {
            display: flex;
            flex-direction: column;
        }
        .select2-error-handle > div > div > select {
            order: 2;
        }
        .select2-error-handle > div > div > span {
            order: 1;
        }
        .select2-error-handle label.error{
            order: 3;
        }
    </style>
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="flex-grow-1">
                        <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#addEmployee" id="add" disabled>
                            <i class="ri-add-fill me-1 align-bottom"></i>Add Employee
                        </button>
                        @include('pages.Admin.employee.modals.employeeaddmodal')
                    </div>
                </div>
            </div>
        </div>
    
            <!--end col-->
        <div class="card mb-0" id="employee_list">
            <div class="card-body">
                <div class="table-responsive mb-3">
                    <table class="display table table-bordered mt-3" id="example">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th>License No</th>
                                <th>First Aid</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all" id="empBody">

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
    <!-- end page title -->
@endsection

@push('scripts')
    @include('components.datatablescript')
    @include('components.stepper')
    <script src="{{asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
    <script src="{{asset('app-assets/velzon/libs/moment/moment.js')}}"></script>
    <script>
        const dataTableTitle = 'Employee Report';
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
                                <input type="text" class="form-control form-control-sm search" placeholder="Search for Employee...">
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
                url: '/admin/home/fetch/employee?status=' + status,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if (data.employees) {
                        if (data.employees.length > 100) {
                            $('#example').DataTable().clear().destroy();
                        }
                        $('#empBody').html(data.employees)

                        if (data.employees.length > 100) {
                            $('#example').DataTable(dataTableOptions);
                        }
                    }

                    $("#addEmployee").modal("hide")
                },
                error: function(err) {
                    console.log(err)
                }
            });
        }

        // Function to check if document image upload is required
        function checkDocumentUploadRequired() {
            var dataIsRequired = $('#_compliance option:selected').data('isrequired');
            console.log(dataIsRequired)

            var documentUploadInput2 = document.querySelector('input#document');

            if (dataIsRequired == "1") {
                documentUploadInput2.setAttribute("required", true);
            } else {
                documentUploadInput2.removeAttribute("required");
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#add').removeAttr('disabled');
            encodeImageFileAsURL = function(element) {
                var file = element.files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    $('#image').val(reader.result)
                    //   console.log(reader.result)
                }
                reader.readAsDataURL(file);
            }

            encodeDocumentFileAsURL = function(element) {
                var file = element.files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    const image = element.nextElementSibling;
                    image.value = reader.result;
                }
                reader.readAsDataURL(file);
            }


            $(document).on("change", "#email", function() {
                setCompliance();
            })
            setCompliance = function() {
                if ($("#email").val()) {
                    $.ajax({
                        url: '/admin/home/filter/employee/compliance',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            'email': $("#email").val(),
                        },
                        success: function(data) {
                            
                            if (data.user) {
                                $("#name").val(data.user.name);
                                // $("#name").prop('readonly', true)
                                $("#mname").val(data.user.mname)
                                // $("#mname").prop('readonly', true)
                                $("#lname").val(data.user.lname)
                                // $("#lname").prop('readonly', true)
                            } else {
                                $("#name").prop('readonly', false)
                                $("#mname").prop('readonly', false)
                                $("#lname").prop('readonly', false)
                            }
                            $('[data-repeater-list]').empty();
                            if (data.compliances.length) {
                                $('#check_compliance').prop('checked', true);
                                $('#compliance').removeClass('disablediv');
                                jQuery.each(data.compliances, function(i, val) {
                                    $('[data-repeater-create]').click();
                                    $("select[name='Compliance[" + i + "][compliance]']")
                                        .val(val.compliance_id).change()
                                    $("input[name='Compliance[" + i + "][certificate_no]']")
                                        .val(val.certificate_no)
                                    $("input[name='Compliance[" + i + "][comment]']").val(
                                        val.comment)
                                    // $("input[name='Compliance[" + i + "][document]']").val(
                                    //     val
                                    //     .document)
                                    $("input[name='Compliance[" + i + "][expire_date]']")
                                        .val(val.expire_date)

                                    // var flatpickr = $("input[name='Compliance[" + i + "][expire_date]']").flatpickr({});
                                    // flatpickr.set('defaultDate', val.expire_date);
                                })
                            } else {
                                $('#check_compliance').prop('checked', false);
                                $('[data-repeater-create]').click();
                                var flatpickr = $("input[name='Compliance[0][expire_date]']")
                                    .flatpickr({});
                            }

                        },
                        error: function(err) {
                            console.log(err)
                        }
                    });

                } else {
                    $('#check_compliance').prop('checked', false)
                    $('#compliance').addClass('disablediv')
                }
            }
            window.fetchData = fetchEmployees = function() {
                $.ajax({
                    url: '/admin/home/fetch/employee',
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if (data.employees) {
                            $('#example').DataTable().clear().destroy();
                            $('#empBody').html(data.employees)

                            $('#example').DataTable(dataTableOptions);

                        }

                        $("#addEmployee").modal("hide")
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
            fetchEmployees()

            $(document).on("click", "#check_compliance", function() {
                if ($(this).is(':checked')) {
                    $('#compliance').removeClass('disablediv')
                    $(".comp").prop('required', true)
                } else {
                    $('#compliance').addClass('disablediv')
                    $(".comp").prop('required', false)
                }
            })

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
                                url: '/admin/home/employee/delete/' + $(this).data("id"),
                                type: 'get',
                                dataType: 'json',
                                success: function(data) {
                                    toastr[data.alertType](data.message, {
                                        closeButton: true,
                                        tapToDismiss: false,
                                    });
                                    fetchEmployees()
                                },
                                error: function(err) {
                                    console.log(err)
                                }
                            });
                        }
                    });
            })

            // $("#newModalForm").validate()
            function setDate(date = '') {
                if (date) {
                    return moment(date).format('DD-MM-YYYY');
                } else {
                    return '';
                }
            }
            $(document).on("click", ".edit-btn", function() {
                resetValue()
                var rowData = $(this).data("row");

                $("#id").val(rowData.id);
                $("#name").val(rowData.fname);
                // $("#name").prop('readonly', true)
                $("#mname").val(rowData.mname)
                // $("#mname").prop('readonly', true)
                $("#lname").val(rowData.lname)
                // $("#lname").prop('readonly', true)
                // $("#_image").prop('disabled', true)

                $("#contact_number").val(rowData.contact_number)
                $("#address").val(rowData.address)
                $("#suburb").val(rowData.suburb)
                $("#state").val(rowData.state)
                $("#postal_code").val(rowData.postal_code)
                $("#email").val(rowData.email)
                $("#document").val(rowData.document)
                // $("#email").prop('readonly', true)
                $("#status").val(rowData.status).trigger('change')
                $("#license_no").val(rowData.license_no)
                $("#first_aid_license").val(rowData.first_aid_license)
                $("#role").val(rowData.role).trigger('change')
                $("#license_expire_date").val(setDate(rowData.license_expire_date))
                $("#first_aid_expire_date").val(setDate(rowData.first_aid_expire_date))
                $("#date_of_birth").val(setDate(rowData.date_of_birth))
                // $('#pass_div').hide()

                window.formAction = "{{ route('update-employee') }}"
                // $("#savebtn").hide()
                // $("#updatebtn").show()
                setCompliance()
                $("#compliance_tab").attr('style', 'display:list-item !important')
                $("#addEmployee").modal("show")
            })

            $(document).on("click", "#add", function() {
                resetValue()
                $("#compliance_tab").attr('style', 'display:none !important')
                $("#addEmployee").modal("show")
            })

            $(document).on("click", "#download", function() {
                $(".dt-buttons .buttons-copy").toggle()
                $(".dt-buttons .buttons-csv").toggle()
                $(".dt-buttons .buttons-excel").toggle()
                $(".dt-buttons .buttons-pdf").toggle()
                $(".dt-buttons .buttons-print").toggle()
            })

            function resetValue() {
                $("#buttom_bar").attr('style', 'display:none !important')
                $("#id").val('');
                $("#name").val('');
                $("#name").prop('readonly', false)
                $("#mname").val('')
                $("#mname").prop('readonly', false)
                $("#lname").val('')
                $("#lname").prop('readonly', false)
                // $("#_image").prop('disabled', false)

                $("#contact_number").val('')
                $("#address").val('')
                $("#state").val('')
                $("#postal_code").val('')
                $("#email").val('')
                $("#password").val('')
                $("#email").prop('readonly', false)
                $("#date_of_birth").val('')
                $("#status").val('').trigger('change')
                $("#rsa_number").val('')
                $("#suburb").val('')
                $("#rsa_expire_date").val('')
                $("#license_no").val('')
                $("#license_expire_date").val('')
                $("#first_aid_license").val('')
                $("#first_aid_expire_date").val('')
                $("#document").val('');
                $("#role").val('').trigger('change')
                // $('#pass_div').show()

                window.formAction = "{{ route('store-employee') }}"
                // $(".timekeer-btn").html('Submit')
                // $("#updatebtn").prop('hidden','true')
                // $("#savebtn").prop('hidden','false')
                // $("#savebtn").show()
                // $("#updatebtn").hide()
                $('#check_compliance').prop('checked', false)
                $('#compliance').addClass('disablediv')
            }

        })
    </script>
@endpush
