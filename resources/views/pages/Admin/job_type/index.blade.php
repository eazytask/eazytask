@extends('layouts.Admin.master')
@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Settings
        @endslot
        @slot('title')
            Job Type
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-header">
            <button class="btn btn-info" href="#" id="add" disabled>Add Job-Type</button>
        </div>
        @include('pages.Admin.job_type.modals.AddModal')
        <div class="card-body">
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
                                <div class="dropdown">
                                    <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu" >
                                        <li><button class="dropdown-item edit-btn" data-row="{{ $json }}">Edit</button></li>
                                        <li><a class="dropdown-item del" url="/job/type/delete/{{$row->id}}" >Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
    <script>
        $(document).ready(function() {
            $('#add').removeAttr('disabled');
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