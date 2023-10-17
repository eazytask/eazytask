@extends('layouts.SuperAdmin.master')


@section('super_admincontent')
    @include('sweetalert::alert')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Companies</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('super-admin.home') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Company Lists
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
                    <a class="btn btn-primary" id="add" href="#">Add Company</a>
                </div>
                @include('pages.SuperAdmin.Company.modals.add_company_modal')
                <div class="container">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover-animation table-bordered">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Company</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Admin Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Master License</th>
                                    <th>Expire Date</th>
                                    <!-- <th>Sub Domain</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($companies as $row)
                                    @php

                                        $json = json_encode($row->toArray(), false);
                                        $user = json_encode($row->user->toArray(), false);

                                        if (!$row->image) {
                                            $image = 'https://eazytask.au/public/images/app/no-image.png';
                                        } else {
                                            if ($row->sub_domain) {
                                                //$image= 'http://localhost:8888/'.$row->image;
                                                $image = 'https://' . $row->sub_domain . '.easytask.com.au/' . $row->image;
                                            } else {
                                                $image = 'https://eazytask.au/public/' . $row->image;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="avatar bg-light-primary">
                                                <div class="avatar-content">
                                                    <img class="img-fluid" src="{{ $image }}" style="height: 100%">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $row->company }}
                                        </td>
                                        <td> {{ $row->company_code }}</td>
                                        <td> {{ $row->company_type->name }}</td>
                                        <td> {{ $row->user->name }}</td>
                                        <td> {{ $row->company_contact }}</td>
                                        <td>{{ $row->user->email }}</td>
                                        <td>
                                            @if ($row->status == 1)
                                                <span class="badge badge-pill badge-light-success mr-1">Active</span>
                                            @else
                                                <span class="badge badge-pill badge-light-danger mr-1">Inactive</span>
                                            @endif
                                        </td>
                                        <td> {{ $row->master_license }}</td>
                                        <td> {{ $row->expire_date ? \Carbon\Carbon::parse($row->expire_date)->format('d-m-Y') : 'null' }}
                                        </td>
                                        <!-- <td>
                                                @if ($row->sub_domain)
    <span class="badge badge-pill badge-light-success mr-1">.{{ $row->sub_domain }}</span>
@else
    <span class="badge badge-pill badge-light-danger mr-1">No Sub</span>
    @endif
                                            </td> -->
                                        <td>
                                            <button class="edit-btn btn btn-gradient-primary mb-25"
                                                data-row="{{ $json }}" data-user="{{ $user }}"><i
                                                    data-feather='edit'></i></button>
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
    <script>
        $(document).ready(function() {
            $("#company-store").validate()
            jQuery.validator.setDefaults({
                errorPlacement: function(error, element) {
                    if (element.hasClass('select2') && element.next('.select2-container').length) {
                        error.insertAfter(element.next('.select2-container'));
                    } else if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if (element.prop('type') === 'radio' && element.parent('.radio-inline')
                        .length) {
                        error.insertAfter(element.parent().parent());
                    } else if (element.prop('type') === 'checkbox' || element.prop('type') ===
                        'radio') {
                        error.appendTo(element.parent().parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $(document).on("click", ".edit-btn", function() {
                resetValue()
                var rowData = $(this).data("row");
                var user = $(this).data("user");

                $("#id").val(rowData.id);
                $("#name").val(user.name)
                $("#mname").val(user.mname)
                $("#lname").val(user.lname)
                $("#email").val(user.email)
                $("#status").val(rowData.status)
                $("#master_license").val(rowData.master_license)
                $("#expire_date").val(moment(rowData.expire_date).format('DD-MM-YYYY'))
                $("#company").val(rowData.company)

                $("#company_contact").val(rowData.company_contact)
                $("#company_code").val(rowData.company_code)
                $("#company_type_id").val(rowData.company_type_id)

                $('#company-store').attr('action', "{{ route('company-update') }}");
                $("#savebtn").hide()
                $("#updatebtn").show()

                $("#addCompany").modal("show")
            })

            $(document).on("click", "#add", function() {
                resetValue()
                $("#addCompany").modal("show")
            })

            function resetValue() {
                $("#id").val('');
                $("#name").val('')
                $("#mname").val('')
                $("#lname").val('')
                $("#email").val('')
                $("#status").val('')
                $("#master_license").val('')
                $("#expire_date").val('')
                $("#company").val('')

                $("#company_contact").val('')
                $("#company_code").val('')
                $("#company_type_id").val('')

                $('#company-store').attr('action', "{{ route('company-store') }}");
                $("#savebtn").show()
                $("#updatebtn").hide()
            }
        })
    </script>
@endsection
