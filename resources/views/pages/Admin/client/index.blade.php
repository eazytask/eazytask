@extends('layouts.Admin.master')


@section('admincontent')
    @include('sweetalert::alert')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Clients</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Client Lists
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
                    <button class="btn btn-primary" id="add"><i data-feather='plus'></i></button>
                </div>
                @include('pages.Admin.client.modals.clientaddmodal')


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
                                    <th>Contact Person</th>
                                    <th>Address</th>
                                    <th>State</th>
                                    <th>Post</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="clientBody">

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
                            $('#example').DataTable().clear().destroy();
                            $('#clientBody').html(data.data)
                            $('#example').DataTable({
                                "drawCallback": function(settings) {
                                    feather.replace({
                                        width: 14,
                                        height: 14
                                    });
                                }
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
@endpush
