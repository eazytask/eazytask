@extends('layouts.Admin.master')


@section('admincontent')
    @include('sweetalert::alert')
    <style>
        .img-fluid {
            height: 100% !important;
        }

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
    </style>
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Employees</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Employee Lists
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Basic Tables start -->
    <!-- Table Hover Animation start -->
    <div class="row" id="table-hover-animation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                </div>
                {{-- <center> --}}

                <div class="card-body">
                    <div class="container row row-xs">
                        <div class="col mt-md-0">
                            <button class="btn btn-default float-left" id="download" title="Download Report"><img
                                    src="{{ url('backend/img/download_icon.png') }}" class="img-responsive"
                                    style="width: 35px;"></button>
                            <button class="btn btn-default float-left" id="add" title="Add Employee"><img
                                    src="{{ url('backend/img/user_add.png') }}" class="img-responsive"
                                    style="width: 35px;"></button>
                            @include('pages.Admin.employee.modals.employeeaddmodal')
                        </div>

                        <div class="col-lg-2">
                            <select class="form-control select2" name="status_id" id="status_id">
                                <option>Select Status</option>
                                <option value="0">All</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-1 col-lg-1">
                            <button type="button" onclick="handleStatusChange(this)"
                                class="btn btn btn-outline-primary btn-block" id="btn_search"><i
                                    data-feather='search'></i></button>
                        </div>
                    </div>
                </div>
                {{-- </center> --}}

                <div class="container">

                    <div class="table-responsive">
                        <table id="example" class="table table-hover-animation table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Number</th>
                                    <th>License No</th>
                                    <th>First Aid</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="empBody">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Table head options end -->
    <!-- Basic Tables end -->
@endsection

@push('scripts')
    <script>
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
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    }
                                ]
                            });
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

            var documentUploadInput = document.querySelector('input#_document');
            var documentUploadInput2 = document.querySelector('input#document');

            if (dataIsRequired == "1") {
                documentUploadInput.setAttribute("required", true);
                documentUploadInput2.setAttribute("required", true);
            } else {
                documentUploadInput.removeAttribute("required");
                documentUploadInput2.removeAttribute("required");
            }
        }
    </script>

    <script>
        $(document).ready(function() {

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
                    $('#document').val(reader.result)
                    //   console.log(reader.result)
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
                                jQuery.each(data.compliances, function(i, val) {
                                    $('[data-repeater-create]').click();
                                    $("select[name='Compliance[" + i + "][compliance]']")
                                        .val(val.compliance_id).change()
                                    $("input[name='Compliance[" + i + "][certificate_no]']")
                                        .val(val.certificate_no)
                                    $("input[name='Compliance[" + i + "][comment]']").val(
                                        val.comment)
                                    $("input[name='Compliance[" + i + "][document]']").val(
                                        val
                                        .document)
                                    $("input[name='Compliance[" + i + "][expire_date]']")
                                        .val(val.expire_date)

                                    // var flatpickr = $("input[name='Compliance[" + i + "][expire_date]']").flatpickr({});
                                    // flatpickr.set('defaultDate', val.expire_date);
                                })
                            } else {
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
                    $('#checkCompliance').prop('checked', false)
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
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        exportOptions: {
                                            columns: [0, 2, 3, 4, 5, 6]
                                        }
                                    }
                                ]
                            });

                        }

                        $("#addEmployee").modal("hide")
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
            fetchEmployees()

            $(document).on("click", "#checkCompliance", function() {
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
                $("#email").prop('readonly', true)
                $("#date_of_birth").val(setDate(rowData.date_of_birth))
                $("#status").val(rowData.status).trigger('change')
                $("#license_no").val(rowData.license_no)
                $("#license_expire_date").val(setDate(rowData.license_expire_date))
                $("#first_aid_license").val(rowData.first_aid_license)
                $("#first_aid_expire_date").val(setDate(rowData.first_aid_expire_date))
                $("#role").val(rowData.role).trigger('change')
                // $('#pass_div').hide()

                window.formAction = "{{ route('update-employee') }}"
                // $("#savebtn").hide()
                // $("#updatebtn").show()
                setCompliance()
                $("#buttom_bar").attr('style', 'display:flex !important')
                $("#addEmployee").modal("show")
                $("#button_add_submit").css({
                    display: "none !important"
                });
            })

            $(document).on("click", "#add", function() {
                resetValue()
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
                window.StepperReset()
                // $(".timekeer-btn").html('Submit')
                // $("#updatebtn").prop('hidden','true')
                // $("#savebtn").prop('hidden','false')
                // $("#savebtn").show()
                // $("#updatebtn").hide()
                $('#checkCompliance').prop('checked', false)
                $('#compliance').addClass('disablediv')
                $("#button_add_submit").css({
                    display: "flex !important"
                });
            }

        })
    </script>
@endpush
