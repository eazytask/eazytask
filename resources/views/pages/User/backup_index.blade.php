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

//unconfirm shift
$all_ids=[];

//payments
$total_durations=0;
$total_amount = 0;

@endphp
@section('admincontent')
<style>
    .dce-msg-poweredby {
        display: none !important;
    }

    #my_camera div {
        background: #ddd0 !important;
    }
    th{
        border: 1px solid #83818a !important;
    }
    
    td{
        border: 2px solid #ebe9f1 !important;
    }
</style>

<div class="row match-height">
    <!-- profile -->
    
    <div class="col-lg-4 col-xl-4 col-md-6">
        <div class="card">
            <div class="container  mb-1 p-1 d-flex justify-content-center">
                <div class="image d-flex flex-column justify-content-center align-items-center">
                    <span class="name mt-1" style="font-size: 26px; font-weight: bold; color: #000;">{{ Auth::user()->employee->fname }} {{ Auth::user()->employee->mname }} {{ Auth::user()->employee->lname }}</span>
                    <p class="mt-1">{{Auth::user()->employee->license_no}}</p>
                    <img src="https://api.eazytask.au/{{Auth::user()->image}}" height="80" width="80" alt="view sales" class="rounded-circle">
                    <a href="/admin/company/profile-settings/{{ Auth::user()->id }}" class="btn btn-primary waves-effect waves-light mt-2">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <!-- static Data -->
    <div class="col-lg-4 col-xl-4 col-md-6">
        <div class="card">
            <div class="container  mb-2 p-3 d-flex justify-content-start">
                <div class="image d-flex flex-column justify-content-start ">
                    <span class="name mt-1" style="font-size: 20px;">Hours Worked        : 0</span>
                    <span class="name mt-1" style="font-size: 20px;">Payment Recived     : 0</span>
                    <span class="name mt-1" style="font-size: 20px;">Compliance Reminder : 0</span>
                    <span class="name mt-1" style="font-size: 20px;">Leave Entitled      : 0</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- static Data end -->

    <!-- sign-in  -->
    <div class="col-lg-4 col-xl-4 col-md-6">
        <div class="card">

            <div class="card-header">
                <div style="flex:1;">
                    <h3 class="text-center" id="clock"></h3>
                </div>
                <div class="mt-3 mt-md-0">
                   <!-- <a href="/home/sign/in" class="btn btn-gradient-primary ">See More</a> -->
                </div>
            </div>
            <div class="card-body pb-0">
                <input type="number" id="total_entry" value="{{$roasters->count()}}" hidden>
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

                            <div class="col-xl-6 col-lg-4 col-md-6 m-auto">
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
                            <div class="col-xl-6 col-lg-4 col-md-6 m-auto">
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

                            <div class="col-xl-6 col-lg-4 col-md-6 m-auto">
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
        </div>
    </div>
</div>
</div>

<div class="row match-height">
    <!-- calendar -->
    <div class="col-md-6">
        <div class="card card-company-table">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Calendar</p>
                </div>
                <div class="mt-3 mt-md-0">
                 <!--   <a href="/home/calender" class="btn btn-gradient-primary "><i data-feather="calendar" class="avatar-icon font-medium-3"></i></a> -->
                </div>
            </div>
            <section>
                <div class="app-calendar overflow-hidden border">
                    <div class="row no-gutters">
                        <div class="d-none">
                            <select name="" id="projectFilter" hidden>
                                <option value="">Select</option>
                            </select>
                        </div>

                        <!-- Calendar -->
                        <div class="col position-relative">
                            <div class="card shadow-none border-0 mb-0 rounded-0">
                                <div class="card-body pb-0">
                                    <div id="user_calendar_timekeeper"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /Calendar -->
                        <div class="body-content-overlay"></div>
                    </div>
                </div>
                <!-- Calendar Add/Update/Delete event modal-->
                <div class="modal modal-slide-in event-sidebar fade" id="add-new-sidebar">
                    <div class="modal-dialog sidebar-lg">
                        <div class="modal-content p-0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">Ã—</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title">View Event</h5>
                            </div>

                            <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                <section id="multiple-column-form">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <label for=""> Venue</label>
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="project_id" id="project-select" aria-label="Default select example" disabled>
                                                            <option value="" disabled selected hidden>Please Choose...
                                                            </option>
                                                            @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->pName }}
                                                            </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12">
                                                    <label for="email-id-column">Roster Date</label>
                                                    <div class="form-group">
                                                        <input type="text" id="roaster_date" name="roaster_date" disabled class="form-control format-picker" placeholder="Roster Date" />
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-12">
                                                    <label for="email-id-column">Shift Start</label>
                                                    <div class="form-group">

                                                        <input type="text" disabled id="shift_start" name="shift_start" class="form-control pickatime-format" placeholder="Shift Start Time" />

                                                        <span id="shift_start_error" class="text-danger text-small"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12">
                                                    <label for="email-id-column">Shift Ends Date & Time</label>
                                                    <div class="form-group">

                                                        <input type="text" disabled id="shift_end" name="shift_end" class="form-control pickatime-format" placeholder="Shift End Time" />
                                                        <span id="shift_end_error" class="text-danger"></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-12">
                                                    <label for="email-id-column">Duration</label>
                                                    <div class="form-group">
                                                        <input type="text" id="duration" name="duration" class="form-control" placeholder="Duration" id="days" disabled />
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-12">
                                                    <label for="email-id-column">Amount Per Hour</label>
                                                    <div class="form-group">
                                                        <input type="number" id="rate" name="ratePerHour" class="form-control reactive" placeholder="0" disabled />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12">
                                                    <label for="email-id-column">Amount</label>
                                                    <div class="form-group">
                                                        <input type="text" id="amount" name="amount" class="form-control" placeholder="0" disabled />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12">
                                                    <label for="">Job Type</label>
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="job_type_id" id="job" aria-label="Default select example" disabled>
                                                            <option value="" disabled selected hidden>Please Choose...
                                                            </option>
                                                            @foreach ($job_types as $job_type)
                                                            <option value="{{ $job_type->id }}">{{ $job_type->name }}
                                                            </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-12 ">
                                        <label for="">Roster Status</label>
                                        <div class="form-group">
                                            <select class="form-control select2" name="roaster_status_id" id="roster" aria-label="Default select example" disabled>
                                                <option value="" disabled selected hidden>Please Choose...
                                                </option>
                                                @foreach ($roaster_status as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <label for="roaster_type" class="form-label">Roster Type</label>
                                        <div class="form-group">
                                            <select class="form-control select2" id="roasterType" disabled>
                                                <option value="">Select Roster Type</option>
                                                <option value="Unschedueled">Unschedueled</option>
                                                <option value="Schedueled">Schedueled</option>
                                            </select>
                                        </div>
                                    </div> -->
                                                <div class="col-md-12 col-12">
                                                    <label for="email-id-column">Remarks</label>
                                                    <div class="form-group">
                                                        <input type="text" name="remarks" id="remarks" class="form-control" placeholder="remarks" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="form-group d-flex">
                                    <button type="button" class="btn btn-outline-secondary btn-cancel" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn ml-1 bg-gradient-success" id="accept">Accept</button>
                                    <button type="button" class="btn ml-1 bg-gradient-success" id="request" disabled>Requested</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="post" id="signInFrom" hidden>
                    @csrf
                    <input type="text" id="event_id" name="event_id">
                </form>
                <!--/ Calendar Add/Update/Delete event modal-->
            </section>
        </div>
    </div>

    <!-- schedule calendar -->
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Schedule</p>
                </div>
                <div class="mt-3 mt-md-0">
                 <!--   <a href="/user/roster/schedule" class="btn btn-gradient-primary "><i data-feather="calendar" class="avatar-icon font-medium-3"></i></a> -->
                </div>
            </div>
            <div class="row">
                <!-- Revenue Report Card -->
                <div class="col-12">
                    <div class="col-lg-12 col-md-12 p-0 border-top">
                        <div class="card p-0">
                            <div class="container p-0">
                                <div class="card-body pt-0 pb-0">
                                    <div class="row row-xs">
                                        <!-- <div id="loadSearchADate">click</div> -->
                                        <div class="col-12 text-center">

                                            <!-- <div id="editor"> -->

                                            <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25" id="prev"><i data-feather='arrow-left'></i></button>
                                            <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25" id="currentWeek">{{\Carbon\Carbon::now()->startOfWeek()->format('d M, Y')}} - {{\Carbon\Carbon::now()->endOfWeek()->format('d M, Y')}}</button>
                                            <button type="button" class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="next"><i data-feather='arrow-right'></i></button>

                                            <button class="btn p-0 pt-50 pb-50 mr-50 mt-25">
                                                <select id="project" class="form-control" style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;" name="project_id">
                                                    <option value="">Select Venue</option>
                                                </select>
                                            </button>
                                            <!-- <button id="download" disabled class="btn text-white bg-primary pt-50 pb-50 mr-50 mr-25"><i data-feather='download' class="mr-25"></i>Download</button> -->
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="table-hover-animation">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="container">
                                            <div class="table-responsive">
                                                <table id="myTable" class="myTable table table-bordered table-striped ">
                                                    <thead>
                                                        <tr>
                                                            <th>Employee Name</th>
                                                            <th>Monday</th>
                                                            <th>Tuesday</th>
                                                            <th>Wednesday</th>
                                                            <th>Thursday</th>
                                                            <th>Friday</th>
                                                            <th>Saturday</th>
                                                            <th>Sunday</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tBody">
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--/ Revenue Report Card -->
            </div>
        </div>
    </div>
</div>

<div class="row match-height">
    <!-- upcoming shifts -->
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Upcoming shifts</p>
                </div>
                <!-- <div class="mt-3 mt-md-0">
                    <a href="/home/upcoming/shift" class="btn btn-gradient-primary ">See more</a>
                </div> -->
            </div>

            <div class="row" id="table-hover-animation">
                <div class="col-12">
                    <div class="card">
                        <div class="container">
                            <div class="table-responsive">
                                <table class="table table-hover-animation height-300">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Venue</th>
                                            <th>Roster Date</th>
                                            <th>Shift Start</th>
                                            <th>Shift End</th>
                                            <th>Duration</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($upcoming_roasters as $k => $row)

                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>
                                                @if (isset($row->project->pName))
                                                {{ $row->project->pName }}
                                                @else
                                                Null
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                            </td>
                                            <td>{{ getTime($row->shift_start) }}</td>
                                            <td>{{ getTime($row->shift_end) }}</td>
                                            <td>{{ $row->duration }}</td>
                                            <td>{{ $row->ratePerHour }}</td>
                                            <td>{{ $row->amount }}</td>

                                        </tr>
                                        @endforeach
                                        <!-- <tr class="text-center" {{$upcoming_roasters->count()==0?'':'hidden'}}>
                                            <td colspan="8">No data found!</td>
                                        </tr> -->
                                        <tr class="bg-light" {{$upcoming_roasters->count()==0?'hidden':''}}>
                                            <td>#</td>
                                            <td colspan="4" class="text-center">Total</td>
                                            <td>{{$upcoming_roasters->sum('duration')}} hours</td>
                                            <td></td>
                                            <td>$ {{$upcoming_roasters->sum('amount')}}</td>
                                        </tr>
                                        <!--<tr>-->
                                        <!--    <td colspan="8" class="bg-light-primary text-center">-->
                                        <!--        <a href="/home/upcoming/shift">See more</a>-->
                                        <!--    </td>-->
                                        <!--</tr>-->
                                    </tbody>
                                </table>

                            </div>
                              <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;" class=" text-right">
                                                        <a href="/home/upcoming/shift">More...</a>
                                                    </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- unconfirmed shifts  -->
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Assigned shifts</p>
                </div>
                <!-- <div class="mt-3 mt-md-0">
                    <a href="/home/unconfirmed/shift" class="btn btn-gradient-primary ">See more</a>
                </div> -->
            </div>

            @if(count($unconfirm_roasters)>0)

            <div class="card-body">
                <div class="card plan-card " id="hasData">
                    <div class="m-1">
                        <button class="btn pl-2 pr-2 border-primary" onclick="checkAllID()">

                            <input type="checkbox" class="mr-50" id="checkAllID" onclick="checkAllID()"><span>Check All</span>
                        </button>
                        <button class="btn btn-gradient-danger text-center border-primary ml-1 reject" disabled onclick="multipleShift('reject')">
                            <span class="desktop-view">Reject</span>
                            <i data-feather='x-circle'></i>
                        </button>
                        <button class="btn btn-gradient-success text-center border-primary accept" disabled onclick="multipleShift('accept')">
                            <span class="desktop-view">Accept</span>
                            <i data-feather='check-circle'></i>
                        </button>
                    </div>
                </div>
                <div class="row">
                    @foreach($unconfirm_roasters as $k => $roster)
                    @php
                    array_push($all_ids,$roster->id);
                    @endphp
                    <div class="col-md-12" id="roster{{$roster->id}}">
                        <div class="card plan-card border-primary text-center">
                            <div class="justify-content-between align-items-center row p-2">
                                <div class="col-1">
                                    <input type="checkbox" value="{{$roster->id}}" style="height:16px; width:16px" class="checkID">
                                </div>
                                <div class="col-4">
                                    <div>
                                        <h5>
                                            {{$roster->project->pName}}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6>{{\Carbon\Carbon::parse($roster->roaster_date)->format('d/m/Y')}}, {{ getTime($roster->shift_start) }} - {{ getTime($roster->shift_end) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
                @else
                <div class="row card-body" style="    align-items: end;">
                    <div class="col-12">
                        <div class="card text-center">
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
                        <div class="card text-center">
                            <div class="justify-content-between align-items-center pt-2">

                                <div class="card-body">
                                    <div class="mb-1">No Unconfirmed Shift</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class=" card-body text-right" style="font-size: 18px;">
                        <a href="/home/unconfirmed/shift">More...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row match-height">
    <!-- past shifts -->
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Past shifts</p>
                </div>
                <!-- <div class="mt-3 mt-md-0">
                    <a href="/home/past/shift" class="btn btn-gradient-primary ">See more</a>
                </div> -->
            </div>

            <div class="row" id="table-hover-animation">
                <div class="col-12">
                    <div class="card">
                        <div class="container">
                            <div class="table-responsive">
                                <table class="table table-hover-animation height-300">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Venue</th>
                                            <th>Roster Date</th>
                                            <th>Shift Start</th>
                                            <th>Shift End</th>
                                            <th>Duration</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($past_roasters as $k => $row)

                                        <tr class="{{$row->roaster_type=='Unschedueled'?'bg-light-primary':''}}">
                                            <td class="p-0 pl-50">
                                                @if($row->is_approved)
                                                <i data-feather='check-circle' class="text-primary"></i>
                                                @else
                                                <span class="pl-1 ml-25"></span>
                                                @endif
                                                {{ $k + 1 }}
                                            </td>
                                            <td>
                                                @if (isset($row->project->pName))
                                                {{ $row->project->pName }}
                                                @else
                                                Null
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                            </td>
                                            <td>{{ getTime($row->shift_start) }}</td>
                                            <td>{{ getTime($row->shift_end) }}</td>
                                            <td>{{ $row->duration }}</td>
                                            <td>{{ $row->ratePerHour }}</td>
                                            <td>{{ $row->amount }}</td>

                                        </tr>
                                        @endforeach

                                        <!--<tr>-->
                                        <!--    <td colspan="8" class="bg-light-primary text-center">-->
                                        <!--        <a href="/home/past/shift">See more</a>-->
                                        <!--    </td>-->
                                        <!--</tr>-->

                                    </tbody>
                                </table>

                            </div>
                             <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;" class=" text-right">
                                                        <a href="/home/past/shift">More...</a>
                                                    </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- upcoming Events -->
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Upcoming Events</p>
                </div>
                <!-- <div class="mt-3 mt-md-0">
                    <a href="/user/home/upcomingevent/go" class="btn btn-gradient-primary ">See more</a>
                </div> -->
            </div>
            <div class="card-body">

                <div class="row" id="table-hover-animation">
                    <div class="col-12">
                        <div class="card">

                            <div class="container">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation table-bordered  height-300">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Venue Name</th>
                                                <th>Event Date</th>
                                                <th>Shift Start</th>
                                                <th>Shift End</th>
                                                <th>Rate</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            
                                            
                                            @foreach ($upcomingevents as $row)
                                            <tr>

                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $row->project->pName }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->event_date)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->shift_start)->format('H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->shift_end)->format('H:i') }}</td>
                                                <td>{{ $row->rate }}</td>

                                                <td>{{ $row->remarks }}</td>

                                                <td>
                                                    <form action="{{ route('store-event') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="event_id" value="{{ $row->id }}">



                                                        @if (App\Models\Eventrequest::where('event_id',$row->id)->where('user_id',Auth::id())->count())

                                                        <button class="btn btn-gradient-primary btn-sm" disabled>Requested</button>
                                                        @else
                                                        <button class="btn btn-gradient-primary btn-sm" type="submit">Interested</button>
                                                        @endif
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                            <!--<tr>-->
                                            <!--    <td colspan="8" class="bg-light-primary text-center">-->
                                            <!--        <a href="/user/home/upcomingevent/go">See more</a>-->
                                            <!--    </td>-->
                                            <!--</tr>-->
                                            
                                            
                                        </tbody>
                                    </table>
                                </div>
                                 <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;" class=" text-right">
                                                        <a href="/user/home/upcomingevent/go">More...</a>
                                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row match-height">
    <!-- Timesheet -->
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Timesheet</p>
                </div>
                <!-- <div class="mt-3 mt-md-0">
                    <a href="/home/timesheet" class="btn btn-gradient-primary ">See more</a>
                </div> -->
            </div>
            <div class="card-body">
                @if(count($timesheets)>0)
                <div class="card">
                    <div class="row" id="table-hover-animation">
                        <div class="col-12">
                            <div class="card">
                                <div class="container">
                                    <div class="table-responsive">
                                        <table class="table table-hover-animation table-bordered height-300">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Venue</th>
                                                    <th>Roster Date</th>
                                                    <th>Shift Start</th>
                                                    <th>Shift Etart</th>
                                                    <th>Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($timesheets as $k => $row)
                                                @php
                                                $json = json_encode($row->toArray(), false);
                                                @endphp
                                                <tr>
                                                    <td class="p-0 pl-50">
                                                        @if($row->is_approved)
                                                        <i data-feather='check-circle' class="text-primary"></i>
                                                        @else
                                                        <span class="pl-1 ml-25"></span>
                                                        @endif
                                                        {{ $k + 1 }}
                                                    </td>
                                                    <td>
                                                        @if (isset($row->project->pName))
                                                        {{ $row->project->pName }}
                                                        @else
                                                        Null
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-y')}}
                                                    </td>
                                                    <td>{{ getTime($row->shift_start) }}</td>
                                                    <td>{{ getTime($row->shift_end) }}</td>
                                                    <td>{{ $row->ratePerHour }}</td>
                                                </tr>
                                                @endforeach
                                                <!--<tr>-->
                                                <!--    <td colspan="6" class="bg-light-primary text-center">-->
                                                <!--        <a href="/home/timesheet">See more</a>-->
                                                <!--    </td>-->
                                                <!--</tr>-->

                                            </tbody>
                                        </table>

                                    </div>
                                     <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;" class=" text-right">
                                                        <a href="/home/timesheet">More...</a>
                                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Payments -->
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">
                <div>
                    <p class="card-title text-primary d-inline">Payments</p>
                </div>
                <!-- <div class="mt-3 mt-md-0">
                    <a href="/home/payment/report" class="btn btn-gradient-primary ">See more</a>
                </div> -->
            </div>
            <div class="card-body">

                <div class="card">
                    <div class="row" id="table-hover-animation">
                        <div class="col-12">
                            <div class="card">
                                <div class="container">
                                    <div class="table-responsive">
                                        <table class="table table-hover-animation table-bordered height-300">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Issue Date</th>
                                                    <th>Total Hours</th>
                                                    <th>Total Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payments as $k => $payment)
                                                @php
                                                $total_durations += $payment->details->total_hours;
                                                $total_amount += $payment->details->total_pay;
                                                @endphp
                                                <tr>
                                                    <td>{{ $k + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d-m-Y')}}</td>
                                                    <td>{{$payment->details->total_hours}}</td>
                                                    <td>{{ $payment->details->total_pay }}</td>
                                                    <td><a class="edit-btn btn-link btn" href="/home/payment/report/{{$payment->id}}" target="_blank"><i data-feather="eye"></i></a></td>
                                                </tr>
                                                @endforeach
                                               

                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;" class=" text-right">
                                                        <a href="/home/payment/report">More...</a>
                                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row match-height">
    <!-- last row1 -->
    <div class="col-md-6">
        <div class="card">

            

            <div class="row" id="table-hover-animation">
                <div class="col-12">
                    <div class="card">
                        <div class="container mt-4 mb-4 p-3 d-flex align-content-center ">
                            <div class="table-responsive">
                                <span class="table table-hover-animation height-300">
                                    <div class="border">
                                        <div class="image d-flex flex-column justify-content-center align-items-center">
                                            <h1 class="p-3">Under Construction</h1>
                                        </div>
                                    </div>
                                </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- last row2 -->
    <div class="col-md-6">
        <div class="card">

            

            <div class="row" id="table-hover-animation">
                <div class="col-12">
                    <div class="card">
                        <div class="container mt-4 mb-4 p-3 d-flex align-content-center ">
                            <div class="table-responsive">
                                <span class="table table-hover-animation height-300">
                                    <div class="border">
                                        <div class="image d-flex flex-column justify-content-center align-items-center">
                                            <h1 class="p-3">Under Construction</h1>
                                        </div>
                                    </div>
                                </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- sign-in script  -->
<script>
    $(document).ready(function() {
        $('#mainForm').attr('action', '{{$form_action}}')
        $('#mainForm').validate()
        let enhancer = null;

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

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {

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
                                    }
                                });
                        } else {
                            form.submit()
                        }
                    }
                });
            } else {
                if (form.valid()) {
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
        shift_end_in_indexes["shift_end" + i] = shift_end_in_indexes["shift_end" + i].split("-").join("/")

        shift_end_in_indexes["countDownDate" + i] = new Date(shift_end_in_indexes["shift_end" + i]).getTime();
        shift_end_in_indexes["x" + i] = setInterval(function() {
            // Get today's date and time
            shift_end_in_indexes["timeNow" + i] = new Date().toLocaleString('en-US', {
                timeZone: 'Australia/Sydney'
            });
            shift_end_in_indexes["timeNow" + i] = new Date(shift_end_in_indexes["timeNow" + i])
            var now = shift_end_in_indexes["timeNow" + i].getTime();

            // Find the distance between now and the count down date
            shift_end_in_indexes["distance" + i] = shift_end_in_indexes["countDownDate" + i] - now;
            // Time calculations for days, hours, minutes and seconds
            shift_end_in_indexes["hours" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            shift_end_in_indexes["minutes" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));
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

<!-- calendar script  -->
<script src="{{asset('app-assets/js/scripts/pages/user-calendar-timekeeper.js')}}"></script>
<script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        var project_id = $('#project_id')

        $('#projectFilter').wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select Project',
            dropdownParent: $('#projectFilter').parent()
        });

        // $('#buttons-print').click(function(){
        //     $(".modern-nav-toggle").click()
        //      window.print();

        //     $(".modern-nav-toggle").click()
        //  });
    });
</script>

<!-- schedule calendar -->
<script type="text/javascript">
    let searchADate = ''
    var searchEL = $('<label class="float-left">Search a Date:<input type="text" id="search_date" name="search_date" class="form-control format-picker form-control-sm" placeholder="dd-mm-yyyy"></label>');

    $('#download').click(function() {
        const element = document.getElementById('htmlContent').innerHTML;
        var opt = {
            filename: 'Schedule-Roster.pdf',
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
    });

    $('#prev').on('click', function() {
        searchADate = ''
        searchNow('previous')
    })
    $('#next').on('click', function() {
        searchADate = ''
        searchNow('next')
    })

    $('#project').on('change', function() {
        // alert($(this).val())
        // if ($(this).val()) {
        //     $('#download').prop('disabled', false)
        // } else {
        //     $('#download').prop('disabled', true)
        // }
        searchNow('current')
    })

    function searchNow(goTo = '', search_date = null) {
        $.ajax({
            url: '/user/roster/calendar/shifts',
            type: 'get',
            dataType: 'json',
            data: {
                'go_to': goTo,
                'project': $('#project').val(),
                'search_date': search_date,
            },
            success: function(data) {
                // $("#myTable").DataTable();
                // if (data.search_date) {
                //     $("#search_date").val(moment(data.search_date).format('DD-MM-YYYY'))
                // } else {
                //     $("#search_date").val('')
                // }
                get_projects(data)

                $('#myTable').DataTable().clear().destroy();
                $('#tBody').html(data.data);
                $('#print_tBody').html(data.report);
                $('#print_client').html('Client: ' + data.client);
                $('#print_project').html('Venue: ' + data.project);
                $('#print_hours').html('Total Hours: ' + data.hours);
                $('#print_amount').html('Total Amount: $' + data.amount);
                $('#print_current_week').text('Date: ' + data.week_date)
                // $("#myTable").DataTable();
                $('#myTable').DataTable({
                    dom: 'Bfrtip',
                    paging: false,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    autoWidth: false, //step 1
                    columnDefs: [
                        // { width: '140px', targets: 0 }, //step 2, column 1 out of 4
                        {
                            width: '125px',
                            targets: 1
                        }, //step 2, column 2 out of 4
                        {
                            width: '125px',
                            targets: 2
                        }, //step 2, column 3 out of 4
                        {
                            width: '125px',
                            targets: 3
                        }, //step 2, column 3 out of 4
                        {
                            width: '125px',
                            targets: 4
                        }, //step 2, column 3 out of 4
                        {
                            width: '125px',
                            targets: 5
                        }, //step 2, column 3 out of 4
                        {
                            width: '125px',
                            targets: 6
                        }, //step 2, column 3 out of 4
                        {
                            width: '125px',
                            targets: 7
                        }, //step 2, column 3 out of 4
                    ]
                    // "bDestroy": true
                });
                feather.replace({
                    width: 14,
                    height: 14
                });
                $('#currentWeek').text(data.week_date)
                $('#logo').html('<img src="' + data.logo + '" alt="" class="ml-1" height="45px">');

                if (data.notification) {
                    toastr.success(data.notification)
                }

                $('#myTable_filter').append(searchEL)
                $(searchEL).find("#search_date").flatpickr({
                    dateFormat: "d-m-Y"
                });
                $(searchEL).find("#search_date").val(searchADate)
            },
            error: function(err) {
                console.log(err)
            }
        });
    }

    function get_projects(data) {
        console.log(Object.keys(data.projects).length)
        let html = '<option value="">Select Venue</option>'
        if (Object.keys(data.projects).length) {
            html = ''
        }
        jQuery.each(data.projects, function(i, val) {
            html += "<option value='" + val.id + "' " + (val.id == data.current_project ? 'selected' : '') + ">" + val.pName + "</option>"
        })
        $('#project').html(html)
    }
</script>
<script>
    $(document).ready(function() {
        searchNow()

        searchEL.on('change', function() {
            searchADate = $('#search_date').val()
            console.log($('#search_date').val())
            searchNow('search_date', $('#search_date').val())
        })

        $(document).on('show.bs.modal', '.modal', function() {
            const zIndex = 1040 + 10 * $('.modal:visible').length;
            $(this).css('z-index', zIndex);
            setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
        });

        $.time = function(dateObject) {
            var t = dateObject.split(/[- :]/);
            // Apply each element to the Date function
            var actiondate = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            var d = new Date(actiondate);

            // var d = new Date(dateObject);
            var curr_hour = d.getHours();
            var curr_min = d.getMinutes();
            var date = curr_hour + ':' + curr_min;
            return date;
        };

        $(document).on("click", ".editBtn", function() {
            // $("#roasterClick").modal("hide")
            window.current_emp = $(this).data("employee")

            let rowData = $(this).data("row")
            if ($(this).data("copy")) {
                $("#editTimekeeperSubmit").prop("hidden", true)
                $("#addTimekeeperSubmit").prop("hidden", false)
            } else {
                $("#editTimekeeperSubmit").prop("hidden", false)
                $("#addTimekeeperSubmit").prop("hidden", true)
                $("#timepeeper_id").val(rowData.id);
                $('#timepeeper_id').attr('value', rowData.id);
            }
            $("#employee_id").val(rowData.employee_id).trigger('change');
            $("#client-select").val(rowData.client_id).trigger('change');

            $("#roaster_date").val(moment(rowData.roaster_date).format('DD-MM-YYYY'))
            $("#shift_start").val($.time(rowData.shift_start))
            $("#shift_end").val($.time(rowData.shift_end))

            $("#shift_start").val($.time(rowData.shift_start))
            $("#shift_end").val($.time(rowData.shift_end))

            $("#shift_start").removeAttr("disabled")
            $("#shift_end").removeAttr("disabled")

            $("#rate").val(rowData.ratePerHour)
            $("#duration").val(rowData.duration)
            $("#amount").val(rowData.amount)
            $("#job").val(rowData.job_type_id).trigger('change');
            $("#roster_type").val(rowData.roaster_type).trigger('change');
            $("#roster").val(rowData.roaster_status_id).trigger('change')

            $("#remarks").val(rowData.remarks)

            $("#project-select").val(rowData.project_id).trigger('change');
            $("#addTimeKeeper").modal("show")

            initAllDatePicker();
            allCalculation()

        })
    });
</script>

<!-- unconfirm shifts -->
<script type="text/javascript">
    let ids = []
    let totalId = <?php echo json_encode($all_ids); ?>;

    function multipleShift(action) {
        $.ajax({
            url: '/home/unconfirmed/multiple/shift/' + action + '/' + ids,
            type: 'GET',
            dataType: "json",
            success: function(data) {
                toastr['success']('ðŸ‘‹ confirm Successfully', 'Success!', {
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
@endpush