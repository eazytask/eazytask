@extends('layouts.Admin.master')

@php

function getTime($date)
{
return \Carbon\Carbon::parse($date)->format('H:i');
}

$all_ids=[];

@endphp
@section('admincontent')



@if(count($roasters)>0)

<div class="card plan-card " id="hasData">
    <div class="m-1">
        <button class="btn pl-2 pr-2 border-primary" onclick="checkAllID()">

            <input type="checkbox" class="mr-50" id="checkAllID" onclick="checkAllID()"><span>Check All</span>
        </button>
        <button class="btn btn-gradient-danger text-center pl-4 pr-4 border-primary ml-1 reject" disabled onclick="multipleShift('reject')">
            <span class="desktop-view">Reject</span> 
            <i data-feather='x-circle'></i>
        </button>
        <button class="btn btn-gradient-success text-center pl-4 pr-4 border-primary accept" disabled onclick="multipleShift('accept')">
            <span class="desktop-view">Accept</span> 
            <i data-feather='check-circle'></i>
        </button>
    </div>
</div>
<div class="row">
    @foreach($roasters as $k => $roster)
    @php
    array_push($all_ids,$roster->id);
    @endphp
    <div class="col-md-12" id="roster{{$roster->id}}">
        <div class="card plan-card border-primary text-center">
            <div class="justify-content-between align-items-center row p-2">
                <div class="col-1">
                    <input type="checkbox" value="{{$roster->id}}" style="height:16px; width:16px" class="checkID">
                </div>
                <div class="col-5">
                    <div>
                        <h5>
                            {{$roster->project->pName}}
                        </h5>
                    </div>
                </div>
                <div class="col-5">
                    <h6>{{\Carbon\Carbon::parse($roster->roaster_date)->format('d/m/Y')}}, {{ getTime($roster->shift_start) }} - {{ getTime($roster->shift_end) }}</h6>
                </div>
                <!-- <div class="col-5">
                    <div class="mb-2">
                        <button class="btn btn-gradient-danger text-center pl-4 pr-4" onclick="rejectShift('{{$roster->id}}')">Reject</button>
                        <button class="btn btn-gradient-success text-center pl-4 pr-4" onclick="acceptShift('{{$roster->id}}')">Accept</button>
                    </div>
                </div> -->


            </div>
        </div>
    </div>
    @endforeach

</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card plan-card border-primary text-center">
            <div class="justify-content-between align-items-center pt-2">

                <div class="card-body">
                    <div class="mb-1">No Unconfirmed Shift</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endif
<div class="row" id="noData" hidden>
    <div class="col-12">
        <div class="card plan-card border-primary text-center">
            <div class="justify-content-between align-items-center pt-2">

                <div class="card-body">
                    <div class="mb-1">No Unconfirmed Shift</div>
                </div>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    let ids = []
    let totalId = <?php echo json_encode($all_ids); ?>;
    // function rejectShift(id) {
    //     $.ajax({
    //         url: '/home/unconfirmed/shift/reject/' + id,
    //         type: 'GET',
    //         dataType: "json",
    //         success: function(data) {
    //             toastr['success']('👋 Reject Successfully', 'Success!', {
    //                 closeButton: true,
    //                 tapToDismiss: false,
    //             });
    //             $("#roster" + id).attr("hidden", true)
    //         },
    //         error: function(err) {
    //             console.log(err)
    //         }
    //     })
    // }

    // function acceptShift(id) {
    //     $.ajax({
    //         url: '/home/unconfirmed/shift/accept/' + id,
    //         type: 'GET',
    //         dataType: "json",
    //         success: function(data) {
    //             console.log(data)
    //             toastr['success']('👋 Accept Successfully', 'Success!', {
    //                 closeButton: true,
    //                 tapToDismiss: false,
    //             });
    //             $("#roster" + id).attr("hidden", true)


    //         },
    //         error: function(err) {
    //             console.log(err)
    //         }
    //     })
    // }

    function multipleShift(action) {
        $.ajax({
            url: '/home/unconfirmed/multiple/shift/' + action + '/' + ids,
            type: 'GET',
            dataType: "json",
            success: function(data) {
                toastr['success']('👋 confirm Successfully', 'Success!', {
                    closeButton: true,
                    tapToDismiss: false,
                });

                if (ids.length === totalId.length) {
                    $('#hasData').prop('hidden', true)
                    $('#noData').prop('hidden', false)
                } else {
                    $(".accept").prop('disabled', true)
                    $(".reject").prop('disabled', true)
                    $('#checkAllID').prop('checked', false)
                }

                $.each(ids, function(index, id) {
                    $("#roster" + id).attr("hidden", true)
                    totalId = jQuery.grep(totalId, function(value) {
                        return value != id
                    })
                })
                ids = []
            },
            error: function(err) {
                console.log(err)
            }

        })

    }

    $(document).on("click", ".checkID", function() {
        if ($(this).is(':checked')) {
            ids.push($(this).val())
        } else {
            let id = $(this).val()
            ids = jQuery.grep(ids, function(value) {
                return value != id
            })
        }

        if (ids.length === 0) {
            $(".accept").prop('disabled', true)
            $(".reject").prop('disabled', true)
        } else {
            $(".accept").prop('disabled', false)
            $(".reject").prop('disabled', false)
        }

        if (ids.length == totalId.length) {
            $('#checkAllID').prop('checked', true)
        } else {
            $('#checkAllID').prop('checked', false)
        }
    })

    function checkAllID() {

        if ($("#checkAllID").is(':checked')) {
            $("#checkAllID").prop('checked', false)
            ids = []
            $('.checkID').prop('checked', false)
        } else {
            $("#checkAllID").prop('checked', true)
            ids = totalId
            $('.checkID').prop('checked', true)
        }

        if (ids.length === 0) {
            $(".accept").prop('disabled', true)
            $(".reject").prop('disabled', true)
        } else {
            $(".accept").prop('disabled', false)
            $(".reject").prop('disabled', false)
        }

    }
</script>

@endsection