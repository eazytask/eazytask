@include('layouts.Admin.partials.header')
@php
function getTime($date)
{
return \Carbon\Carbon::parse($date)->format('H:i');
}

@endphp


<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> -->
<style>
    body {
        font-family: Helvetica, Arial, serif;
    }

    .picker {
        top: auto !important;
        margin-top: 1px;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content m-3">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="invoice-preview-wrapper">
                <div class="row invoice-preview">
                    <!-- Invoice -->
                    <div class="col-12" id="mainContent">
                        <div class="card invoice-preview-card">
                            <div class="card-body invoice-padding pb-0">

                                <div class="card-body invoice-padding pb-0">
                                    <!-- Header starts -->
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                        <div>
                                            <div class="logo-wrapper">
                                                <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                                    <defs>
                                                        <linearGradient id="invoice-linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                                            <stop stop-color="#000000" offset="0%"></stop>
                                                            <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="invoice-linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                                            <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                                            <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                        </linearGradient>
                                                    </defs>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-400.000000, -178.000000)">
                                                            <g transform="translate(400.000000, 178.000000)">
                                                                <path class="text-primary" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill: currentColor"></path>
                                                                <path d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#invoice-linearGradient-1)" opacity="0.2"></path>
                                                                <polygon fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                                                <polygon fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                                                <polygon fill="url(#invoice-linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span class="text-primary invoice-logo font-weight-bolder h2 ml-25" onclick="downloadPDF()">Roster</span>
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
                                            <h6 class="mb-2">Invoice To:</h6>
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
                </div>
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->
<script>
    window.print()
    setTimeout(window.close, 0);
</script>
@include('layouts.Admin.partials.scripts')