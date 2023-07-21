@extends('layouts.Admin.master')


@section('admincontent')
@include('sweetalert::alert')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Compliances</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Compliance Lists
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
            @include('pages.User.compliance.modals.complianceModal')


            <div class="container">
                <div class="table-responsive">
                    <table id="example" class="table table-hover-animation table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Certificate Number</th>
                                <th>expire Date</th>
                                <th>Comment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="complianceBody">

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

        $('.select2').select2({
            dropdownParent: $('#addCompliance')
        });

        fetchCompliances = function() {
            $.ajax({
                url: '/home/user/compliance/fetch',
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if (data.data) {
                        $('#example').DataTable().clear().destroy();
                        $('#complianceBody').html(data.data)
                        $('#example').DataTable();
                        feather.replace({
                            width: 14,
                            height: 14
                        });
                    }

                    $("#addCompliance").modal("hide")
                },
                error: function(err) {
                    console.log(err)
                }
            });
        }
        fetchCompliances()

        complianceAddFunc= function() {
            if ($("#complianceForm").valid()) {
                $.ajax({
                    data: $('#complianceForm').serialize(),
                    url: "/home/user/compliance/store",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        toastr[data.alertType](data.message, {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        fetchCompliances()
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            }
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
                        $.ajax({
                            url: '/home/user/compliance/delete/' + $(this).data("id"),
                            type: 'get',
                            dataType: 'json',
                            success: function(data) {
                                toastr[data.alertType](data.message, {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                                fetchCompliances()
                            },
                            error: function(err) {
                                console.log(err)
                            }
                        });
                    }
                });
        })

        $(document).on("click", ".edit-btn", function() {
            resetValue()
            var rowData = $(this).data("row");

            $("#compliance_id").val(rowData.compliance_id).trigger('change')
            $("#certificate_no").val(rowData.certificate_no)
            $("#expire_date").val(moment(rowData.expire_date).format('DD-MM-YYYY'))
            $("#comment").val(rowData.comment)

            $("#editComplianceSubmit").prop("hidden", true)
            $("#addComplianceSubmit").prop("hidden", false)
            $("#addCompliance").modal("show")
        })

        $(document).on("click", "#add", function() {
            resetValue()
            $("#addCompliance").modal("show")
        })

        function resetValue() {
            $("#editComplianceSubmit").prop("hidden", false)
            $("#addComplianceSubmit").prop("hidden", true)
            $("#compliance_id").val('').trigger('change')
            $("#certificate_no").val('')
            $("#expire_date").val()
            $("#comment").val('')
        }

    })
</script>
@endpush