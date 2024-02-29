@extends('layouts.Admin.master')

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Employee
        @endslot
        @slot('title')
            Compliance
        @endslot
    @endcomponent
    @component('components.employeeTab')
        @slot('active') compliance @endslot
    @endcomponent
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="flex-grow-1">
                        <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#addCompliance" id="add" disabled>
                            <i class="ri-add-fill me-1 align-bottom"></i>Add Compliance
                        </button>
                        @include('pages.Admin.compliance.modals.compliance_add')
                    </div>
                </div>
            </div>
        </div>
    
            <!--end col-->
        <div class="card mb-0" id="compliance">
            <div class="card-body">
                <div class="table-responsive mb-3">
                    <table class="display table table-bordered mt-3" id="compliance_table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Compliance Name</th>
                                <th>Certificate NO.</th>
                                <th>Expire Date</th>
                                <th>Comment</th>
                                <th>Document</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all" id="compliance_body">

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
    <script>
        $(document).ready(function(){
            $('#add').removeAttr('disabled');

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
                                    <input type="text" class="form-control form-control-sm search" placeholder="Search for Compliance...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>`;
                    $('#compliance_table_filter').html(search);
                    $('.search').on('keyup', function(){
                        table.search( this.value ).draw();
                    });
                    $('select[name="example_length"]').addClass('form-control select2');
                },
            }

            var compliances = {};
            function get_compliance(){
                $.ajax({
                    url: "{{route('get_compliance')}}",
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(result){
                        compliances = result.data;
                        if(result.status){
                            $('#compliance_table').DataTable().clear().destroy();
                            compliance_entries(result.data);
                            $('#compliance_table').DataTable(dataTableOptions);
                        }
                    }
                });
            }
            get_compliance();
            function compliance_entries(data){
                let html = '';
                let container = $('#compliance_body');
                if(data.length){
                    data.forEach((compliance, index)=>{
                        html += `
                            <tr>
                                <td>${index+1}</td>
                                <td>${compliance.employee_name}</td>
                                <td>${compliance.email}</td>
                                <td>${compliance.contact_number}</td>
                                <td>${compliance.compliance_name}</td>
                                <td>${compliance.certificate_no}</td>
                                <td>${compliance.expire_date}</td>
                                <td>${compliance.comment ? compliance.comment : ''}</td>
                                <td>${compliance.document ? '<img style="max-height: 100px; max-width: 100px;" src="https://www.api.eazytask.au/'+compliance.document+'"/>': ''}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button data-id="${compliance.id}" class="edit-btn dropdown-item">Edit</button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item del" data-id="${compliance.id}">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }else{
                    html += '<tr><td colspan="10"><h5 class="text-center text-muted">Not Found!</h5></td><tr>'
                }
                container.html(html);
            }

            $(document).on('click', '.del', function(){
                let id = $(this).data('id');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        $.ajax({
                            url: '{{route("delete_compliance")}}/'+id,
                            type: 'get',
                            dataType: 'json',
                            success: function(result){
                                console.log(result);
                                if(result.status){
                                    get_compliance();
                                    toastr.success(result.message);
                                }
                            }
                        });
                    }
                });
            });
            let date = $('[name="expire_date"]');
            flatpickr(date);

            $(document).on('click', '.edit-btn', function(){
                $('#update').css('display', 'inline-block');
                $('#store').css('display', 'none');
                resetValue('#compliance_add_form');
                let data_id = $(this).data('id');
                let data = compliances.filter((compliance)=> compliance.id == data_id)[0];
                insertFormData(data);
                $('#addCompliance').modal('show');
            });
            $('#add').on('click', ()=>{
                $('#update').css('display', 'none');
                $('#store').css('display', 'inline-block');
                resetValue('#compliance_add_form');
            });
            function resetValue(selector) {
                $(selector).trigger("reset");
                $(selector+' select').trigger("change");
            }
            function insertFormData(data){
                $('#id').val(data.id);
                $('#compliance').val(data.compliance_id).trigger('change');
                $('#certificate_num').val(data.certificate_no);
                $('#emp_name').val(data.emp_id).trigger('change');
                $('#expire').val(data.expire_date);
                $('#comment').val(data.comment);
                console.log($('#compliance').val());
            }
            $('#compliance_add_form').validate({
                highlight: function (element, errorClass, validClass) {
                    $(element).parents('.mb-3').removeClass('has-success').addClass('has-error');     
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents('.mb-3').removeClass('has-error').addClass('has-success');
                },
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
            $('#store').on('click', function(){
                var form = $('form#compliance_add_form');
                var isValid = form.valid();
                var fullFormIsValid = true;
                if(isValid){
                    $.ajax({
                        url: "{{route('store_compliance')}}",
                        type: 'POST',
                        dataType: 'JSON',
                        data: new FormData(form[0]),
                        processData: false,
                        contentType: false,
                        success: function(result){
                            console.log(result);
                            if(result.status){
                                get_compliance();
                                $('#addCompliance').modal('hide');
                                toastr.success(result.message);
                            }
                        },
                        error: function(error){
                            console.warn(error);
                        }
                    });
                }
            });
            $('#update').on('click', function(){
                var form = $('form#compliance_add_form');
                var isValid = form.valid();
                var fullFormIsValid = true;
                if(isValid){
                    $.ajax({
                        url: "{{route('update_compliance')}}",
                        type: 'POST',
                        dataType: 'JSON',
                        data: new FormData(form[0]),
                        processData: false,
                        contentType: false,
                        success: function(result){
                            console.log(result);
                            if(result.status){
                                get_compliance();
                                $('#addCompliance').modal('hide');
                                toastr.success(result.message);
                            }
                        },
                        error: function(error){
                            console.warn(error);
                        }
                    });
                }
            });
            $('form#compliance_add_form').on('submit', (e)=>{
                e.preventDefault();
            });
        });
    </script>
@endpush