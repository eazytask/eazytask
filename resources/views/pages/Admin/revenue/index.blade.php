@extends('layouts.Admin.master')

@php

$fromRoaster=null;
$toRoaster = null;
if(Session::get('revenuefromRoaster')){
$fromRoaster = \Carbon\Carbon::parse(Session::get('revenuefromRoaster'))->format('d-m-Y');
}
if(Session::get('revenuetoRoaster')){
$toRoaster = \Carbon\Carbon::parse(Session::get('revenuetoRoaster'))->format('d-m-Y');
}
@endphp
@section('admincontent')
@include('sweetalert::alert')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Revenue</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Revenue Lists
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
            <div class="card-body">

                <form action="{{ route('search-revenue') }}" method="POST" id="dates_form">
                    @csrf
                    <div class="row row-xs">
                        <div class="col-lg-4 mt-1">
                            <input type="text" name="start_date" required class="form-control format-picker" placeholder="Roster Date From" value="{{$fromRoaster}}" />
                        </div>
                        <div class="col-lg-4 mt-1">
                            <input type="text" name="end_date" required class="form-control format-picker" placeholder="Roster Date To" value="{{$toRoaster}}" />
                        </div>
                        
                        <div class="col-lg-4 col-6 mt-1">
                            <select class="form-control select2" name="client_id" id="client_id">
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{Session::get('revenue_client_id')==$client->id ?'selected':''}}>{{ $client->cname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4 col-6 mt-1">
                            <select class="form-control select2" name="project_id" id="project_id">
                                <option value="">Select Venue</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" {{Session::get('revenue_project_id')==$project->id ?'selected':''}}>{{ $project->pName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-lg-3 mt-1">
                            <button type="submit" class="btn btn btn-outline-primary btn-block" id="btn_search"><i data-feather='search'></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="row row-xs">
                    <div class="col mt-3 mt-md-0">
                        <button class="btn btn-gradient-primary float-right" id="add"><i data-feather='plus'></i></button>
                        
            @include('pages.Admin.revenue.modals.addRevenueModal')
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="table-responsive">
                    <table id="example" class="table table-hover-animation table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Venue Name</th>
                                <th>Roster Date From</th>
                                <th>Roster Date To</th>
                                <th>Hours</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($revenues as $row)
                                    @php
                                        $json = json_encode($row->toArray(), false);
                                    @endphp
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $row->client->cname }}</td>
                                <td>{{ $row->project->pName }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->roaster_date_from)->format('d-m-Y')}}</td>
                                <td>{{ \Carbon\Carbon::parse($row->roaster_date_to)->format('d-m-Y')}}</td>
                                <td>{{ $row->hours }}</td>
                                <td>{{ $row->rate }}</td>
                                <td>{{ $row->amount }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->payment_date)->format('d-m-Y')}}</td>
                                <td>{{ $row->remarks }}</td>
                                <td>
                                    <button class="edit-btn btn btn-gradient-primary mb-25" data-row="{{ $json }}"><i data-feather='edit'></i></button>
                                    <a class="btn btn-gradient-danger text-white del" url="/admin/home/revenue/delete/{{ $row->id }}"><i data-feather='trash-2'></i></a>
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
        
        // $('.select2').select2({
        //     dropdownParent: $('#addRevenue'),
        //     placeholder: 'Select Option',
        //     // dropdownParent: $('#project_name').parent()
        // });
        $('#project_name').wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select venue',
            dropdownParent: $('#project_name').parent(),
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

            // $("#client_name").val(rowData.client_name);
            $("#project_name").val(rowData.project_name).trigger('change')
            $("#roaster_date_from").val(moment(rowData.roaster_date_from).format('DD-MM-YYYY'))
            $("#roaster_date_to").val(moment(rowData.roaster_date_to).format('DD-MM-YYYY'))
            $("#payment_date").val(moment(rowData.payment_date).format('DD-MM-YYYY'))
            $("#rate").val(rowData.rate)
            $("#amount").val(rowData.amount)
            $("#hours").val(rowData.hours)
            $("#remarks").val(rowData.remarks)

            $('#newModalForm').attr('action', "{{ route('update-revenue') }}");
            $("#savebtn").hide()
            $("#updatebtn").show()

            $("#addRevenue").modal("show")
        })

        $(document).on("click", "#add", function() {
            resetValue()
            $("#addRevenue").modal("show")
        })

        function resetValue() {
            $("#id").val('');
            // $("#client_name").val('');
            $("#project_name").val('').trigger('change')
            $("#roaster_date_from").val('')
            $("#roaster_date_to").val('')
            $("#payment_date").val('')
            $("#rate").val('')
            $("#amount").val('')
            $("#hours").val('')
            $("#remarks").val('')

            $('#newModalForm').attr('action', "{{ route('store-revenue') }}");
            $("#savebtn").show()
            $("#updatebtn").hide()
        }

    })
</script>
@endpush