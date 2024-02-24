@extends('layouts.Admin.master')

@php
function getTime($date)
{
return \Carbon\Carbon::parse($date)->format('H:i');
}

@endphp
@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Payments
        @endslot
        @slot('title')
            Payment Invoice
        @endslot
    @endcomponent

    <!-- BEGIN: Content-->
    <div class="">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="invoice-preview-wrapper">
                    <div class="row invoice-preview">
                        <!-- Invoice -->
                        <div class="col-md-9 col-12" id="mainContent">
                            <div class="card invoice-preview-card">
                                <div class="card-body invoice-padding pb-0">

                                    <div class="card-body invoice-padding pb-0">
                                        <!-- Header starts -->
                                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                            <div>
                                                <div class="logo-wrapper">
                                                    <img src="{{asset('images/app/logo.png')}}" alt="" class="mb-50" height="36px">
                                                </div>
                                            </div>
                                            <div class="mt-md-0 mt-2">
                                                <span class="pr-1">Date Issued:</span>
                                                <span class="font-weight-bolder ml-2 mr-2">
                                                    {{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d-m-Y')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Header ends -->
                                </div>

                                <hr class="invoice-spacing">

                                <!-- Address and Contact starts -->
                                <div class="card-body invoice-padding pb-0">
                                    <!-- Header starts -->
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                        <div>
                                            <div class="logo-wrapper">
                                                <h6 class="mb-2">Pay Slip:</h6>
                                                <h6 class="mb-25">{{$payment->employee->fname}}{{$payment->employee->mname}}{{$payment->employee->lname}}</h6>
                                                <p class="card-text mb-25">{{$payment->employee->address}}</p>
                                                <p class="card-text mb-25">{{$payment->employee->state}}, {{$payment->employee->postal_code}}</p>
                                                <p class="card-text mb-0">{{$payment->employee->contact_number}}</p>
                                                <p class="card-text mb-0">{{$payment->employee->email}}</p>
                                            </div>
                                        </div>
                                        <div class="mt-md-0 mt-2">
                                            <h6 class="mb-2">From:</h6>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="pr-1">Name:</td>
                                                        <td class="font-weight-bolder">
                                                            {{$admin->name}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pr-1">Company:</td>
                                                        <td class="font-weight-bolder">
                                                            {{$admin->company->company}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pr-1">Email:</td>
                                                        <td class="font-weight-bolder">
                                                            {{$admin->email}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Address and Contact ends -->

                                <!-- Invoice Description starts -->
                                <div class="table-responsive mt-3">
                                    <table class="table" id="test">
                                        <thead>
                                            <tr>
                                                <th class="p-1">#</th>
                                                <th class="p-75">Roster Date</th>
                                                <th class="p-75">site</th>
                                                <th class="p-75">Roster Start</th>
                                                <th class="p-75">Roster End</th>
                                                <th class="p-75">Clock In</th>
                                                <th class="p-75">Clock Out</th>
                                                <th class="p-75">Approved Start</th>
                                                <th class="p-75">Approved End</th>
                                                <th class="p-75">Duration</th>
                                                <th class="p-75">Rate</th>
                                                <th class="p-75">Amount</th>
                                                <th class="p-75">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($timekeepers as $k => $row)
                                            <tr class="{{$k % 2 == 0?'':'bg-light-primary'}}">
                                                <td class="p-1">{{$k +1}}</td>
                                                <td class="pr-0 pl-0">{{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}</td>
                                                <td class="p-75">{{$row->project->pName}}</td>
                                                <td class="p-75">{{getTime($row->shift_start)}}</td>
                                                <td class="p-75">{{getTime($row->shift_end)}}</td>
                                                <td class="p-75">{{$row->sing_in ? getTime($row->sing_in): 'none'}}</td>
                                                <td class="p-75">{{$row->sing_out ? getTime($row->sing_out): 'none'}}</td>
                                                <td class="p-75">{{getTime($row->Approved_start_datetime)}}</td>
                                                <td class="p-75">{{getTime($row->Approved_end_datetime)}}</td>
                                                <td class="p-75">{{$row->duration}}</td>
                                                <td class="p-75">{{$row->ratePerHour}}</td>
                                                <td class="p-75">{{$row->amount}}</td>
                                                <td class="p-75">{{$row->remarks}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <hr class="invoice-spacing" />

                                <div class="card-body invoice-padding pb-0">
                                    <div class="row invoice-sales-total-wrapper">
                                        <div class="col-md-12 d-flex justify-content-end order-md-2 order-1">
                                            <div class="invoice-total-wrapper" style="width: 200px;">
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Duration: <span class="float-lg-right font-weight-bolder" id="total-duration">{{$payment->details->total_hours}} Hours</span></p>
                                                </div>
                                                <hr class="my-50">
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Total: <span class="float-lg-right font-weight-bolder" id="total-amount">${{$payment->details->total_pay}}</span></p>
                                                    <p class="invoice-total-title">Payment Method: <span class="float-lg-right font-weight-bolder" id="total-amount">{{$payment->details->PaymentMethod}}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="invoice-spacing" />

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group mb-2 pl-2">
                                            <label for="note" class="form-label font-weight-bold"></label>
                                            <span>{{$payment->details->Remarks}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice -->

                        <!-- Invoice Actions -->
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-outline-primary btn-block btn-download-invoice mb-75" id="download">Download Invoice</button>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice Actions -->
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection

@push('styles')
    <style>
        body {
            font-family: Helvetica, Arial, serif;
        }

        .picker {
            top: auto !important;
            margin-top: 1px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).on("click", "#download", function() {

            const element = document.getElementById('mainContent').innerHTML;
            var opt = {
                filename: 'payment-invoice.pdf',
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'tabloid',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        })
    </script>
@endpush

@section('pdf_generator')
<!-- Invoice -->
<div class="col-12 d-print-block d-none" id="mainContent">
    <div class="card invoice-preview-card">
        <div class="card-body invoice-padding pb-0">

            <div class="card-body invoice-padding pb-0">
                <!-- Header starts -->
                <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                    <div>
                        <div class="logo-wrapper">
                            <img src="{{asset('images/app/logo.png')}}" alt="" class="mb-50" height="32px">
                        </div>
                    </div>
                    <div class="mt-md-0 mt-2">
                        <span class="pr-1">Date Issued:</span>
                        <span class="font-weight-bolder ml-2 mr-2">
                            {{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d-m-Y')}}
                        </span>
                    </div>
                </div>
            </div>
            <!-- Header ends -->
        </div>

        <hr class="invoice-spacing">

        <!-- Address and Contact starts -->
        <div class="card-body invoice-padding pb-0">
            <!-- Header starts -->
            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                <div>
                    <div class="logo-wrapper">
                        <h6 class="mb-2">Pay Slip:</h6>
                        <h6 class="mb-25">{{$payment->employee->fname}}{{$payment->employee->mname}}{{$payment->employee->lname}}</h6>
                        <p class="card-text mb-25">{{$payment->employee->address}}</p>
                        <p class="card-text mb-25">{{$payment->employee->state}}, {{$payment->employee->postal_code}}</p>
                        <p class="card-text mb-0">{{$payment->employee->contact_number}}</p>
                        <p class="card-text mb-0">{{$payment->employee->email}}</p>
                    </div>
                </div>
                <div class="mt-md-0 mt-2">
                    <h6 class="mb-2">From:</h6>
                    <table>
                        <tbody>
                            <tr>
                                <td class="pr-1">Name:</td>
                                <td class="font-weight-bolder">
                                    {{$admin->name}}
                                </td>
                            </tr>
                            <tr>
                                <td class="pr-1">Company:</td>
                                <td class="font-weight-bolder">
                                    {{$admin->company->company}}
                                </td>
                            </tr>
                            <tr>
                                <td class="pr-1">Email:</td>
                                <td class="font-weight-bolder">
                                    {{$admin->email}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Address and Contact ends -->

        <!-- Invoice Description starts -->
        <div class="table-responsive mt-3">
            <table class="table" id="test">
                <thead>
                    <tr>
                        <th class="p-1">#</th>
                        <th class="p-75">Roster Date</th>
                        <th class="p-75">site</th>
                        <th class="p-75">Roster Start</th>
                        <th class="p-75">Roster End</th>
                        <th class="p-75">Clock In</th>
                        <th class="p-75">Clock Out</th>
                        <th class="p-75">Approved Start</th>
                        <th class="p-75">Approved End</th>
                        <th class="p-75">Duration</th>
                        <th class="p-75">Rate</th>
                        <th class="p-75">Amount</th>
                        <th class="p-75">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timekeepers as $k => $row)
                    <tr class="{{$k % 2 == 0?'':'bg-light-primary'}}">
                        <td class="p-1">{{$k +1}}</td>
                        <td class="pr-0 pl-0">{{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}</td>
                        <td class="p-75">{{$row->project->pName}}</td>
                        <td class="p-75">{{getTime($row->shift_start)}}</td>
                        <td class="p-75">{{getTime($row->shift_end)}}</td>
                        <td class="p-75">{{$row->sing_in ? getTime($row->sing_in): 'none'}}</td>
                        <td class="p-75">{{$row->sing_out ? getTime($row->sing_out): 'none'}}</td>
                        <td class="p-75">{{getTime($row->Approved_start_datetime)}}</td>
                        <td class="p-75">{{getTime($row->Approved_end_datetime)}}</td>
                        <td class="p-75">{{$row->duration}}</td>
                        <td class="p-75">{{$row->ratePerHour}}</td>
                        <td class="p-75">{{$row->amount}}</td>
                        <td class="p-75">{{$row->remarks}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="invoice-spacing" />

        <div class="card-body invoice-padding pb-0">
            <div class="row invoice-sales-total-wrapper">
                <div class="col-md-12 d-flex justify-content-end order-md-2 order-1">
                    <div class="invoice-total-wrapper" style="width: 200px;">
                        <div class="invoice-total-item">
                            <p class="invoice-total-title">Duration: <span class="float-lg-right font-weight-bolder" id="total-duration">{{$payment->details->total_hours}} Hours</span></p>
                        </div>
                        <hr class="my-50">
                        <div class="invoice-total-item">
                            <p class="invoice-total-title">Total: <span class="float-lg-right font-weight-bolder" id="total-amount">${{$payment->details->total_pay}}</span></p>
                            <p class="invoice-total-title">Payment Method: <span class="float-lg-right font-weight-bolder" id="total-amount">{{$payment->details->PaymentMethod}}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="invoice-spacing" />

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group mb-2 pl-2">
                    <label for="note" class="form-label font-weight-bold"></label>
                    <span>{{$payment->details->Remarks}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Invoice -->
@endsection