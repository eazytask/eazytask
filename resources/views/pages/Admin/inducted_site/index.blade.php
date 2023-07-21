@extends('layouts.Admin.master')


@section('admincontent')
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
                                        $emp= $row->employee;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}</td>
                                        <!-- <td>{{ $row->client->cname }}</td> -->
                                        <td>{{ $row->project->pName }}</td>
                                        <td>{{ $row->induction_date }}</td>
                                        <td>{{ $row->remarks }}</td>
                                        <td>
                                    <button class="edit-btn btn btn-gradient-primary mb-25" data-row="{{ $json }}"><i data-feather='edit'></i></button>
                                            <a class="btn btn-gradient-danger text-white del" url="/admin/home/inducted/site/delete/{{ $row->id }}"><i
                                                    data-feather='trash-2'></i></a>
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
@endsection
@push('scripts')

<script>
    $(document).ready(function() {
        
        $('.select2').select2({
            placeholder: 'Select Option',
            dropdownParent: $('#addInduction'),
            allowClear: true
        });

        $(document).on("click", ".del",  function() {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location  = $(this).attr('url')
                    }
                });
        })

        $("#newModalForm").validate()
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
