@extends('layouts.Admin.master')


@section('admincontent')
@include('sweetalert::alert')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Job-Type</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Job-Type Lists
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
                <a class="btn btn-primary" href="#" id="add">Add Job-Type</a>
            </div>
            @include('pages.Admin.job_type.modals.AddModal')


            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover-animation table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            @php
                            $json = json_encode($row->toArray(), false);
                            @endphp
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->remarks}}</td>
                                <td>
                                    <button class="edit-btn btn btn-primary mb-25" data-row="{{ $json }}"><i data-feather='edit'></i></button>
                                    <a url="/job/type/delete/{{$row->id}}" class=" btn btn-gradient-danger text-white del"><i data-feather='trash-2'></i></a>
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
<!-- Table head options end -->
<!-- Basic Tables end -->
@endsection
@push('scripts')

<script>
    $(document).ready(function() {

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

        $("#job_type_form").validate()
        $(document).on("click", ".edit-btn", function() {
            resetValue()
            var rowData = $(this).data("row");

            $("#id").val(rowData.id);
            $("#name").val(rowData.name)
            $("#remarks").val(rowData.remarks)

            $('#job_type_form').attr('action', "{{ route('jobType.update') }}");
            $("#savebtn").hide()
            $("#updatebtn").show()

            $("#addModal").modal("show")
        })

        $(document).on("click", "#add", function() {
            resetValue()
            $("#addModal").modal("show")
        })

        function resetValue() {
            $("#id").val('');
            $("#name").val('')
            $("#remarks").val('')

            $('#job_type_form').attr('action', "{{ route('jobType.store') }}");
            $("#savebtn").show()
            $("#updatebtn").hide()
        }

    })
</script>
@endpush