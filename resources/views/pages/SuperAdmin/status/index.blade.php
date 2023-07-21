@extends('layouts.SuperAdmin.master')

@section('super_admincontent')
@include('sweetalert::alert')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Status</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/super-admin/home">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Status Lists
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
                <a class="btn btn-primary" href="#" id="add">Add Status</a>
            </div>
            @include('pages.SuperAdmin.status.modals.StatusAddModal')


            <div class="container">
              <div class="table-responsive">
                  <table class="table table-hover-animation table-bordered mb-4">
                      <thead>
                          <tr>
                            <th>#</th>
                              <th>Status Name</th>
                              <th>Remarks</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach($statuses as $row)
                        @php
                            $json = json_encode($row->toArray(), false);
                        @endphp

                          <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{$row->status_name}}</td>
                            <td>{{$row->remarks}}</td>

                              <td>
                                <a class="edit-btn btn btn-primary" href="#" data-row="{{ $json }}"><i data-feather='edit'></i></a>
                                  <a class="btn btn-gradient-danger del" url="/status/delete/{{$row->id}}"><i data-feather='trash-2'></i></a>
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
<script>
    $(document).ready(function() {
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
            $("#name").val(rowData.status_name)
            $("#remarks").val(rowData.remarks)

            $('#modalForm').attr('action', "{{ route('status.update') }}");
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

            $('#modalForm').attr('action', "{{ route('status.store') }}");
            $("#savebtn").show()
            $("#updatebtn").hide()
        }

    })
</script>
@endsection
