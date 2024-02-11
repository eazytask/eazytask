@extends('layouts.Admin.master')

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Settings
        @endslot
        @slot('title')
            Roster Status
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <button class="btn btn-info" id="add" disabled>Add Roster-Status</button>
        </div>
        @include('pages.Admin.roaster_status.modals.AddModal')
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
                        <tr style="color: {{$row->color}} ">
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
                                        <li><a class="dropdown-item del  {{$row->optional?'':'disabled'}}" url="/roster/status/delete/{{$row->id}}" >Delete</a></li>
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
        // function myColor() {
        //     var color = document.getElementById('color').value;
        //     // Take the hex code
        //     document.getElementById('color').value = color;
        // }

        // // When user clicks over color picker,
        // // myColor() function is called
        // document.getElementById('color')
        //     .addEventListener('input', myColor);

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

        $("#modalForm").validate()
        $(document).on("click", ".edit-btn", function() {
            resetValue()
            var rowData = $(this).data("row");

            $("#id").val(rowData.id);
            $("#color").val(rowData.color)
            $("#name").val(rowData.name)
            if(rowData.optional){
                $("#name").prop('disabled',false)
            }else{
                $("#name").prop('disabled',true)
            }
            $("#remarks").val(rowData.remarks)

            $('#modalForm').attr('action', "{{ route('roasterStatus.update') }}");
            $("#savebtn").hide()
            $("#updatebtn").show()

            $("#addModal").modal("show")
        })

        $(document).on("click", "#add", function() {
            resetValue()
            $("#addModal").modal("show")
        })

        function resetValue() {
            $("#name").prop('disabled',false)
            $("#id").val('');
            $("#name").val('')
            $("#remarks").val('')

            $('#modalForm').attr('action', "{{ route('roasterStatus.store') }}");
            $("#savebtn").show()
            $("#updatebtn").hide()
        }

    })
</script>
@endpush
