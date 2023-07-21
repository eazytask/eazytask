@extends('layouts.Admin.master')

@php
$roster=null;
function getTime($date)
{
return \Carbon\Carbon::parse($date)->format('H:i');
}

if($roasters->where('sing_in','!=',null)->count()){
    $already_sign_in=true;
}else{
    $already_sign_in=false;
}
$not_ready_sign_in=true;
$form_action = '';
@endphp
@section('admincontent')
<style>
    .dce-msg-poweredby {
        display: none !important;
    }

    #my_camera div {
        background: #ddd0 !important;
    }
</style>

<input type="number" id="total_entry" value="{{$roasters->count()}}" hidden>
<h3 class="mb-3 text-center" id="clock"></h3>

<form action="" id="mainForm" method="post">
    @csrf
    <div class="row">

    <input type="text" name="lat" class="lat" hidden>
                        <input type="text" name="lon" class="lon" hidden>
                        <input type="text" name="timekeeper_id" id="timekeeperID" hidden>
        @if($roasters)
        @foreach($roasters as $k => $roster)

        @if($roster->sing_out == null && ($roster->shift_start <= \Carbon\Carbon::now()->addMinutes(15)))
            @php
            $not_ready_sign_in= false;
            @endphp
            @endif


            <input type="datetime" id="shift_start{{$k}}" value="{{$roster->shift_start}}" hidden>
            <input type="datetime" id="shift_end{{$k}}" value="{{$roster->shift_end}}" hidden>
            <input type="datetime" id="sing_in{{$k}}" value="{{$roster->sing_in}}" hidden>
            <input type="datetime" id="sing_out{{$k}}" value="{{$roster->sing_out}}" hidden>

            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="card plan-card border-primary text-center">
                    <div class="justify-content-between align-items-center p-75">
                        <p id="countdown{{$k}}" class="mb-1" {{$roster->sing_in == null ? '':'hidden'}}></p>
                        <h3 id="working{{$k}}" class="mb-0" {{$roster->sing_in == null ? 'hidden':''}}></h3>

                        <p id="shift-end-in{{$k}}" class="mb-1" {{$roster->sing_in == null ? 'hidden':''}}></p>


                        <div class="badge badge-light-primary text-uppercase">
                            <h6>{{$roster->project->pName}}</h6>
                        </div>
                        <p class="mb-1">Shift time, {{ getTime($roster->shift_start) }} - {{ getTime($roster->shift_end) }} </p>

                        <div class="d-none">

                            <select class="form-control" name="project_id" id="project-select" hidden>
                                <option selected>{{ $roster->project->pName }}</option>
                            </select>
                        </div>
                        <button type="button" shiftId="{{ $roster->id }}" lat="{{ $roster->project->lat }}" lon="{{ $roster->project->lon }}" class="btn btn-gradient-primary text-center btn-block check-location" {{$already_sign_in == $roster->sing_in ? '':'disabled'}} {{$roster->sing_out == null && ($roster->shift_start <= \Carbon\Carbon::now()->addMinutes(15)) ? '':'disabled'}}>
                            {{$roster->sing_in == null ? 'Start Shift':'Sign Out'}}
                        </button>

                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="card plan-card border-primary text-center">
                    <div class="justify-content-between align-items-center pt-75">

                        <div class="card-body">
                            <p class="text-black-50">You have no scheduled shift at this time</p>
                            <button type="button" class="btn btn-gradient-primary text-center btn-block setForm" data-toggle="modal" data-target="#userAddTimeKeeper">Unscheduled</button>

                        </div>

                    </div>
                </div>
            </div>
            @endif

            @if($not_ready_sign_in)

            <div class="col-xl-3 col-lg-4 col-md-5 ">
                <div class="card plan-card border-primary text-center">
                    <div class="justify-content-between align-items-center pt-75">

                        <div class="card-body">
                            <p class="mb-0 text-muted">You have no scheduled shift at this time</p>
                            <button type="button" class="btn btn-gradient-primary text-center btn-block" data-toggle="modal" data-target="#userAddTimeKeeper">Start unscheduled shift</button>

                        </div>

                    </div>
                </div>
            </div>
            @endif
            @php

            $form_action = $already_sign_in == false ? "/home/sign/in/timekeeper":"/home/sign/out/timekeeper";

            if($not_ready_sign_in){
            $form_action = "/home/user/store/timekeeper";
            }

            @endphp

            @if($not_ready_sign_in)
            @include('pages.User.signin.modals.timeKeeperAddModal')
            @endif

            @include('pages.User.signin.modals.takePhotoModal')
</form>
</div>

@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#mainForm').attr('action', '{{$form_action}}')
        $('#mainForm').validate()
        let enhancer = null;


        // $('.setForm').on('click', function() {
        // $('#mainForm').attr('action','{{$form_action}}')
        // console.log('{{$form_action}}')
        // })

        measure = function(lat1 = -26.753044, lon1 = 136.050351, lat2, lon2) { // generally used geo measurement function
            var R = 6378.137; // Radius of earth in KM
            var dLat = lat2 * Math.PI / 180 - lat1 * Math.PI / 180;
            var dLon = lon2 * Math.PI / 180 - lon1 * Math.PI / 180;
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;
            return d * 1000; // meters
        }

        $('.check-location').on('click', function() {
            var form = $(this).parents('form');
                    pLat = $(this).attr('lat')
                    pLon = $(this).attr('lon')
                    shiftId = $(this).attr('shiftId')
            // console.log(form)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    // alert(measure(23.866101,90.270133,position.coords.latitude,position.coords.longitude))

                    // console.log($('option:selected', "#project-select").attr('venue').id)

                    $('.lat').val(position.coords.latitude)
                    $('.lon').val(position.coords.longitude)
                    $('#timekeeperID').val(shiftId)
console.log(shiftId)
console.log(pLon)
                    if (form.valid()) {
                        let distance = measure(pLat, pLon, position.coords.latitude, position.coords.longitude)
                        if (distance > 500) {
                            swal({
                                    title: "Are you sure?",
                                    text: "You are " + Math.round(distance) + " meters away from the work place",
                                    icon: "warning",
                                    buttons: ["Cancel", "process"],
                                    dangerMode: true,
                                })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        (async () => {
                                            enhancer = await Dynamsoft.DCE.CameraEnhancer.createInstance();
                                            $('#my_camera').html(enhancer.getUIElement())
                                            // document.getElementById("my_camera").append(enhancer.getUIElement());
                                            // document.getElementsByClassName("dce-btn-close")[0].style.display = "none";

                                            $(".dce-btn-close").hide()
                                            $(".dce-sel-resolution").hide()
                                            $(".dce-sel-camera").hide()

                                            await enhancer.open(true);

                                            let cameras = await enhancer.getAllCameras();
                                            if (cameras.length) {
                                                await enhancer.selectCamera(cameras[0]);
                                            }
                                        })();
                                        $('#photomodal').modal("show")
                                        // form.submit()
                                    }
                                });
                        } else {
                            // $('#photomodal').modal("show")
                            form.submit()
                        }
                    }
                });
            } else {
                if (form.valid()) {
                    // $('#photomodal').modal("show")
                    form.submit()
                }
            }
        })

        document.getElementById('capture').onclick = () => {
            if (enhancer) {
                let frame = enhancer.getFrame();
                let imgUrl = frame.canvas.toDataURL("image/png")
                document.getElementById('result').innerHTML = '<img src="' + imgUrl + '" width="100%" height="100%"/>'
                $('#image').val(imgUrl);

                $('#my_camera').removeClass('d-block');
                $('#my_camera').addClass('d-none');

                $('#result').removeClass('d-none');

                $('#capture').removeClass('d-block');
                $('#capture').addClass('d-none');

                $('#retakephoto').removeClass('d-none');
                $('#retakephoto').addClass('d-block');

                $('#uploadphoto').removeClass('d-none');
                $('#uploadphoto').addClass('d-block');
            }
        };

        $('#retakephoto').on('click', function() {
            $('#my_camera').addClass('d-block');
            $('#my_camera').removeClass('d-none');

            $('#result').addClass('d-none');

            $('#capture').addClass('d-block');
            $('#capture').removeClass('d-none');

            $('#retakephoto').addClass('d-none');
            $('#retakephoto').removeClass('d-block');

            $('#uploadphoto').addClass('d-none');
            $('#uploadphoto').removeClass('d-block');
        });

        $('#uploadphoto').on('click', function() {
            var form = $(this).parents('form');
            console.log(form)
            form.submit()
        })

    });


    function startTime() {
        let timeNow = new Date().toLocaleString('en-US', {
            timeZone: 'Australia/Sydney'
        });
        timeNow = new Date(timeNow)

        const today = timeNow;
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }; // add zero in front of numbers < 10
        return i;
    }
    startTime()

    let indexes = {};
    let total_entry = document.getElementById("total_entry").value

    for (let i = 0; i < total_entry; i++) {
        // Set the date we're counting down to
        indexes["shift_start" + i] = document.getElementById("shift_start" + i).value
        // indexes["shift_start" + i] = new Date(indexes["shift_start" + i])
        indexes["shift_start" + i] = indexes["shift_start" + i].split("-").join("/")
        indexes["countDownDate" + i] = new Date(indexes["shift_start" + i]).getTime();

        indexes["x" + i] = setInterval(function() {
            // Get today's date and time
            indexes["timeNow" + i] = new Date().toLocaleString('en-US', {
                timeZone: 'Australia/Sydney'
            });
            // alert(indexes["timeNow" + i])
            indexes["timeNow" + i] = new Date(indexes["timeNow" + i])
            var now = indexes["timeNow" + i].getTime();

            indexes["distance" + i] = indexes["countDownDate" + i] - now;

            indexes["hours" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            indexes["minutes" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));

            document.getElementById("countdown" + i).innerHTML = "Shift Starting in " + indexes["hours" + i] + " hours, " +
                indexes["minutes" + i] + " minutes ";

            if (indexes["distance" + i] < 0) {
                // clearInterval(indexes["x" + i]);
                indexes["distance" + i] = now - indexes["countDownDate" + i];
                indexes["hours" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                indexes["minutes" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));

                document.getElementById("countdown" + i).innerHTML = indexes["hours" + i] + "h, " +
                    indexes["minutes" + i] + "minutes ";

                document.getElementById("countdown" + i).classList.add('text-danger')
            }
        }, 1000);
    }

    // shift ending in
    let shift_end_in_indexes = {};
    for (let i = 0; i < total_entry; i++) {
        // Set the date we're counting down to
        shift_end_in_indexes["shift_end" + i] = document.getElementById("shift_end" + i).value
        // shift_end_in_indexes["shift_end" + i] = new Date(shift_end_in_indexes["shift_end" + i])
        shift_end_in_indexes["shift_end" + i] = shift_end_in_indexes["shift_end" + i].split("-").join("/")

        shift_end_in_indexes["countDownDate" + i] = new Date(shift_end_in_indexes["shift_end" + i]).getTime();
        shift_end_in_indexes["x" + i] = setInterval(function() {
            // Get today's date and time
            shift_end_in_indexes["timeNow" + i] = new Date().toLocaleString('en-US', {
                timeZone: 'Australia/Sydney'
            });
            shift_end_in_indexes["timeNow" + i] = new Date(shift_end_in_indexes["timeNow" + i])
            var now = shift_end_in_indexes["timeNow" + i].getTime();
            // var now = new Date().getTime();

            // Find the distance between now and the count down date
            shift_end_in_indexes["distance" + i] = shift_end_in_indexes["countDownDate" + i] - now;
            // Time calculations for days, hours, minutes and seconds
            shift_end_in_indexes["hours" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            shift_end_in_indexes["minutes" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));
            // var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            // Output the result in an element with id="demo"
            document.getElementById("shift-end-in" + i).innerHTML = "Shift ending in " + shift_end_in_indexes["hours" + i] + " hours, " +
                shift_end_in_indexes["minutes" + i] + " minutes ";
            // If the count down is over, write some text 
            if (shift_end_in_indexes["distance" + i] < 0) {
                clearInterval(shift_end_in_indexes["x" + i]);
                document.getElementById("shift-end-in" + i).innerHTML = "end";
            }
        }, 1000);
    }

    //working
    for (let i = 0; i < total_entry; i++) {
        // total working time
        indexes["workingTimeNow" + i] = ''
        indexes["sing_in" + i] = document.getElementById("sing_in" + i).value
        indexes["sing_out" + i] = document.getElementById("sing_out" + i).value
        // indexes["sing_in" + i] = new Date(indexes["sing_in" + i])
        indexes["sing_in" + i] = indexes["sing_in" + i].split("-").join("/")
        indexes["sing_out" + i] = indexes["sing_out" + i].split("-").join("/")
        indexes["workingTime" + i] = new Date(indexes["sing_in" + i]).getTime();

        var y = setInterval(function() {
            if (indexes["sing_out" + i]) {
                indexes["workingTimeNow" + i] = new Date(indexes["sing_out" + i])
            } else {
                indexes["workingTimeNow" + i] = new Date().toLocaleString('en-US', {
                    timeZone: 'Australia/Sydney'
                });
                indexes["workingTimeNow" + i] = new Date(indexes["workingTimeNow" + i])
            }

            indexes["atNow" + i] = indexes["workingTimeNow" + i].getTime();
            indexes["totalDistance" + i] = indexes["atNow" + i] - indexes["workingTime" + i];

            indexes["h" + i] = Math.floor((indexes["totalDistance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            indexes["m" + i] = Math.floor((indexes["totalDistance" + i] % (1000 * 60 * 60)) / (1000 * 60));

            document.getElementById("working" + i).innerHTML = indexes["h" + i] + "h, " +
                indexes["m" + i] + "min.";
        }, 1000);
    }
</script>
@endpush