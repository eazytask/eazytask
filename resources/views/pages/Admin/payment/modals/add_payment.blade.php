<?php

use Illuminate\Support\Facades\DB;
?>
@extends('layouts.Admin.master')

@php
function getTime($date)
{
return \Carbon\Carbon::parse($date)->format('H:i');
}
$all_ids=[];

$duration =$timekeeperData->sum('app_duration');
$amount =$timekeeperData->sum('app_amount');

$all_amount=[];
$all_duration=[];

@endphp
@section('admincontent')
<style>
    .picker {
        top: 50% !important;
        left: 24%;
    }
</style>
<!-- BEGIN: Content-->
<div class="app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="invoice-preview-wrapper">
                <div class="row invoice-preview">
                    <!-- Invoice -->
                    <div class="col-12">
                        <div class="card invoice-preview-card">
                            <div class="card-body invoice-padding pb-0">

                                <!-- Address and Contact starts -->
                                <form action="{{ route('storepaymentdetails') }}" method="post">
                                    @csrf
                                    <div class="card-body invoice-padding pt-0">
                                        <div class="row invoice-spacing">
                                            <div class="col-xl-8 p-0">
                                                <h6 class="mb-2">Invoice To:</h6>
                                                <h6 class="mb-25">{{$employee->fname}}{{$employee->mname}}{{$employee->lname}}</h6>
                                                <p class="card-text mb-25">{{$employee->address}}</p>
                                                <p class="card-text mb-25">{{$employee->state}}, {{$employee->postal_code}}</p>
                                                <p class="card-text mb-0">{{$employee->contact_number}}</p>
                                                <p class="card-text mb-0">{{$employee->email}}</p>
                                            </div>
                                            <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                                <h6 class="mb-2">Payment Details:</h6>
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td class="pr-1">Date Issued:</td>
                                                            <td>
                                                                <input type="text" name="pay_date" required class="form-control invoice-edit-input format-picker" placeholder="Payment Date" value="{{\Carbon\Carbon::now()->format('d-m-Y')}}" />

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pr-1">Duration:</td>
                                                            <td id="total-duration" class="font-weight-bold">{{$timekeeperData->sum('app_duration')}} Hours</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pr-1">Total:</td>
                                                            <td id="total-amount" class="font-weight-bold">${{$timekeeperData->sum('app_amount')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pr-1">Payment Method:</td>
                                                            <td class="payment_method font-weight-bold">Cash</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Address and Contact ends -->

                                    <!-- Invoice Description starts -->
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr class="text-light">
                                                    <th class="p-1 bg-primary"><input type="checkbox" name="" id="checkAllID" checked></th>
                                                    <th class="p-75 bg-primary">Roster Date</th>
                                                    <th class="p-75 bg-primary">site</th>
                                                    <th class="p-75 bg-primary">Roster Start</th>
                                                    <th class="p-75 bg-primary">Roster End</th>
                                                    <th class="p-75 bg-primary">Clock In</th>
                                                    <th class="p-75 bg-primary">Clock Out</th>
                                                    <th class="p-75 bg-primary">Approved Start</th>
                                                    <th class="p-75 bg-primary">Approved End</th>
                                                    <th class="p-75 bg-primary">Duration</th>
                                                    <th class="p-75 bg-primary">Rate</th>
                                                    <th class="p-75 bg-primary">Amount</th>
                                                    <th class="p-75 bg-primary">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($timekeeperData as $k => $row)
                                                @php
                                                array_push($all_ids,$row->id);
                                                $all_duration[$row->id] = round($row->app_duration,3);
                                                $all_amount[$row->id] = round($row->app_amount,3);

                                                @endphp
                                                <tr class="{{ $row->roaster_type=='Unschedueled'?'bg-light-primary':'' }}">
                                                    <td class="p-1"><input type="checkbox" value="{{$row->id}}" checked class="checkID"></td>
                                                    <td class="pr-0 pl-0">{{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}</td>
                                                    <td class="p-75">{{$row->project->pName}}</td>
                                                    <td class="p-75">{{getTime($row->shift_start)}}</td>
                                                    <td class="p-75">{{getTime($row->shift_end)}}</td>
                                                    <td class="p-75">{{$row->sing_in ? getTime($row->sing_in): 'none'}}</td>
                                                    <td class="p-75">{{$row->sing_out ? getTime($row->sing_out): 'none'}}</td>
                                                    <td class="p-75">{{getTime($row->Approved_start_datetime)}}</td>
                                                    <td class="p-75">{{getTime($row->Approved_end_datetime)}}</td>
                                                    <td class="p-75">{{$row->app_duration}}</td>
                                                    <td class="p-75">{{$row->app_rate}}</td>
                                                    <td class="p-75">{{$row->app_amount}}</td>
                                                    <td class="p-75">{{$row->remarks}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <hr class="invoice-spacing" />


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group mb-2">
                                                <label for="note" class="form-label font-weight-bold">Note:</label><textarea class="form-control" rows="2" id="note" name="comment">Thanks for being part of our team and it was a pleaser working with you.</textarea>
                                            </div>
                                        </div>
                                        <!-- Invoice Actions -->
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="mb-75">
                                                        <input class="form-control" type="number" min="0.00" step="0.00001" name="additional_pay" id="additional_pay" placeholder="Additional Amount">
                                                    </div>
                                                    <div class="mb-75">
                                                        <select class="form-control select2" name="payment_method" id="payment_method">
                                                            <option value="cash">Cash</option>
                                                            <option value="cheque">Cheque</option>
                                                            <option value="bank_transfer">Bank Transfer</option>
                                                        </select>
                                                    </div>

                                                    <input type="text" name="timekeeper_ids" value="" id="timekeeper_ids" hidden>
                                                    <input type="text" name="employee_id" value="{{$employee->id}}" hidden>
                                                    <input type="text" name="duration" id="duration" hidden>
                                                    <input type="text" name="amount" id="amount" hidden>
                                                    <button class="btn btn-success btn-block" id="addPayment">
                                                        Add Payment
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <!-- <button class="btn btn-outline-secondary btn-block btn-download-invoice mb-75" id="download">Download</button> -->
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice Actions -->
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

<script type="text/javascript">
    let totalId = <?php echo json_encode($all_ids); ?>;

    let duration = <?php echo $duration; ?>;
    let amount = <?php echo $amount; ?>;

    let total_duration = duration;
    let total_amount = amount;
    let additional_pay = 0;

    let all_amount = <?php echo json_encode($all_amount); ?>;
    let all_duration = <?php echo json_encode($all_duration); ?>;

    let ids = totalId
    $('#timekeeper_ids').val(ids)
    $('#duration').val(total_duration)
    $('#amount').val(total_amount)

    $(document).on("change", "#payment_method", function() {
        $('.payment_method').html($(this).val())
    })
    $(document).on("input", "#additional_pay", function() {
        total_amount = total_amount - additional_pay
        amount = amount - additional_pay

        if($(this).val()){
            add = parseFloat($(this).val())
            total_amount = total_amount + add
            amount = amount + add
        }else{
            add = 0
        }

        additional_pay=add
        $('#total-amount').html("$" + total_amount)
        $('#amount').val(total_amount)
    })
    
    $(document).on("click", ".checkID", function() {
        console.log(all_amount)
        if ($(this).is(':checked')) {
            let id = $(this).val()
            total_duration = total_duration + all_duration[id]
            total_amount = total_amount + all_amount[id]
            ids.push($(this).val())
        } else {
            let id = $(this).val()
            total_duration = total_duration - all_duration[id]
            total_amount = total_amount - all_amount[id]
            ids = jQuery.grep(ids, function(value) {
                return value != id
            })
        }

        $('#total-duration').html(total_duration + " Hours")
        $('#total-amount').html("$" + total_amount)

        $('#duration').val(total_duration)
        $('#amount').val(total_amount)
        $('#timekeeper_ids').val(ids)

        if (ids.length === 0) {
            $("#addPayment").prop('disabled', true)
            $("#download").prop('disabled', true)
        } else {
            $("#addPayment").prop('disabled', false)
            $("#download").prop('disabled', false)
        }

        if (ids.length == totalId.length) {
            $('#checkAllID').prop('checked', true)
        } else {
            $('#checkAllID').prop('checked', false)
        }
    })

    $(document).on("click", "#checkAllID", function() {
        if ($("#checkAllID").is(':checked')) {
            ids = totalId
            $('.checkID').prop('checked', true)

            $('#total-duration').html(duration + " Hours")
            $('#total-amount').html("$" + amount)
            total_duration = duration
            total_amount = amount

            $('#duration').val(duration)
            $('#amount').val(amount)
        } else {
            ids = []
            $('.checkID').prop('checked', false)

            $('#total-duration').html("0 Hours")
            $('#total-amount').html("$"+additional_pay)
            total_duration = 0
            total_amount = additional_pay
            $('#duration').val(0)
            $('#amount').val(additional_pay)
        }

        $('#timekeeper_ids').val(ids)

        if (ids.length === 0) {
            $("#addPayment").prop('disabled', true)
            $("#download").prop('disabled', true)
        } else {
            $("#addPayment").prop('disabled', false)
            $("#download").prop('disabled', false)
        }

    })
</script>
@endsection