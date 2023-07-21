@extends('layouts.Admin.master')

@php
    $roster = null;
    function getTime($date)
    {
        return \Carbon\Carbon::parse($date)->format('H:i');
    }
    
    if ($roasters->where('sing_in', '!=', null)->count()) {
        $already_sign_in = true;
    } else {
        $already_sign_in = false;
    }
    $not_ready_sign_in = true;
    $form_action = '';
    
    //unconfirm shift
    $all_ids = [];
    
    //payments
    $total_durations = 0;
    $total_amount = 0;
    
@endphp
@section('admincontent')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .dce-msg-poweredby {
            display: none !important;
        }

        #my_camera div {
            background: #ddd0 !important;
        }

        th {
            border: 1px solid #83818a !important;
        }

        td {
            border: 1px solid #83818a !important;
        }

        /* CSS to remove hover effect */
        .table-hover-animation tbody tr:hover {
            background-color: unset;
        }
    </style>

    <div class="row match-height">
        <!-- profile -->

        <div class="col-lg-4 col-xl-4 col-md-6">
            <div class="card">
                <div class="container  mb-1 p-1 d-flex justify-content-center">
                    <div class="image d-flex flex-column justify-content-center align-items-center">
                        <span class="name mt-1"
                            style="font-size: 26px; font-weight: bold; color: #000;">{{ Auth::user()->employee->fname }}
                            {{ Auth::user()->employee->mname }} {{ Auth::user()->employee->lname }}</span>
                        <p class="mt-1">{{ Auth::user()->employee->license_no }}</p>
                        <img src="https://api.eazytask.au/{{ Auth::user()->image }}" height="80" width="80"
                            alt="view sales" class="rounded-circle">
                        <a href="/admin/company/profile-settings/{{ Auth::user()->id }}"
                            class="btn btn-primary waves-effect waves-light mt-2">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>



        <!-- static Data -->
        <div class="col-lg-4 col-xl-4 col-md-6">
            <div class="card">
                <div class="container  mb-2 p-3 d-flex justify-content-start">
                    <div class="image d-flex flex-column justify-content-start ">
                        <span class="name mt-1" style="font-size: 20px;">Hours Worked : 0</span>
                        <span class="name mt-1" style="font-size: 20px;">Payment Recived : 0</span>
                        <span class="name mt-1" style="font-size: 20px;">Compliance Reminder : 0</span>
                        <span class="name mt-1" style="font-size: 20px;">Leave Entitled : 0</span>
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
                    <input type="number" id="total_entry" value="{{ $roasters->count() }}" hidden>
                    <form action="" id="mainForm" method="post">
                        @csrf
                        <div class="row">

                            <input type="text" name="lat" class="lat" hidden>
                            <input type="text" name="lon" class="lon" hidden>
                            <input type="text" name="timekeeper_id" id="timekeeperID" hidden>
                            @if ($roasters)
                                @foreach ($roasters as $k => $roster)
                                    @if ($roster->sing_out == null && $roster->shift_start <= \Carbon\Carbon::now()->addMinutes(15))
                                        @php
                                            $not_ready_sign_in = false;
                                        @endphp
                                    @endif


                                    <input type="datetime" id="shift_start{{ $k }}"
                                        value="{{ $roster->shift_start }}" hidden>
                                    <input type="datetime" id="shift_end{{ $k }}"
                                        value="{{ $roster->shift_end }}" hidden>
                                    <input type="datetime" id="sing_in{{ $k }}" value="{{ $roster->sing_in }}"
                                        hidden>
                                    <input type="datetime" id="sing_out{{ $k }}"
                                        value="{{ $roster->sing_out }}" hidden>

                                    <div class="col-xl-6 col-lg-4 col-md-6 m-auto">
                                        <div class="card plan-card border-primary text-center">
                                            <div class="justify-content-between align-items-center p-75">
                                                <p id="countdown{{ $k }}" class="mb-1"
                                                    {{ $roster->sing_in == null ? '' : 'hidden' }}></p>
                                                <h3 id="working{{ $k }}" class="mb-0"
                                                    {{ $roster->sing_in == null ? 'hidden' : '' }}></h3>

                                                <p id="shift-end-in{{ $k }}" class="mb-1"
                                                    {{ $roster->sing_in == null ? 'hidden' : '' }}></p>


                                                <div class="badge badge-light-primary text-uppercase">
                                                    <h6>{{ $roster->project->pName }}</h6>
                                                </div>
                                                <p class="mb-1">Shift time, {{ getTime($roster->shift_start) }} -
                                                    {{ getTime($roster->shift_end) }} </p>

                                                <div class="d-none">

                                                    <select class="form-control" name="project_id" id="project-select"
                                                        hidden>
                                                        <option selected>{{ $roster->project->pName }}</option>
                                                    </select>
                                                </div>
                                                <button type="button" shiftId="{{ $roster->id }}"
                                                    lat="{{ $roster->project->lat }}" lon="{{ $roster->project->lon }}"
                                                    class="btn btn-gradient-primary text-center btn-block check-location"
                                                    {{ $already_sign_in == $roster->sing_in ? '' : 'disabled' }}
                                                    {{ $roster->sing_out == null && $roster->shift_start <= \Carbon\Carbon::now()->addMinutes(15) ? '' : 'disabled' }}>
                                                    {{ $roster->sing_in == null ? 'Start Shift' : 'Sign Out' }}
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
                                                <button type="button"
                                                    class="btn btn-gradient-primary text-center btn-block setForm"
                                                    data-toggle="modal"
                                                    data-target="#userAddTimeKeeper">Unscheduled</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($not_ready_sign_in)
                                <div class="col-xl-6 col-lg-4 col-md-6 m-auto">
                                    <div class="card plan-card border-primary text-center">
                                        <div class="justify-content-between align-items-center pt-75">

                                            <div class="card-body">
                                                <p class="mb-0 text-muted">You have no scheduled shift at this time</p>
                                                <button type="button"
                                                    class="btn btn-gradient-primary text-center btn-block"
                                                    data-toggle="modal" data-target="#userAddTimeKeeper">Start unscheduled
                                                    shift</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @php
                                
                                $form_action = $already_sign_in == false ? '/home/sign/in/timekeeper' : '/home/sign/out/timekeeper';
                                
                                if ($not_ready_sign_in) {
                                    $form_action = '/home/user/store/timekeeper';
                                }
                                
                            @endphp

                            @if ($not_ready_sign_in)
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
                                <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">Ã—</button>
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
                                                            <select class="form-control select2" name="project_id"
                                                                id="project-select" aria-label="Default select example"
                                                                disabled>
                                                                <option value="" disabled selected hidden>Please
                                                                    Choose...
                                                                </option>
                                                                @foreach ($projects as $project)
                                                                    <option value="{{ $project->id }}">
                                                                        {{ $project->pName }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-12">
                                                        <label for="email-id-column">Roster Date</label>
                                                        <div class="form-group">
                                                            <input type="text" id="roaster_date" name="roaster_date"
                                                                disabled class="form-control format-picker"
                                                                placeholder="Roster Date" />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-12">
                                                        <label for="email-id-column">Shift Start</label>
                                                        <div class="form-group">

                                                            <input type="text" disabled id="shift_start"
                                                                name="shift_start" class="form-control pickatime-format"
                                                                placeholder="Shift Start Time" />

                                                            <span id="shift_start_error"
                                                                class="text-danger text-small"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-12">
                                                        <label for="email-id-column">Shift Ends Date & Time</label>
                                                        <div class="form-group">

                                                            <input type="text" disabled id="shift_end"
                                                                name="shift_end" class="form-control pickatime-format"
                                                                placeholder="Shift End Time" />
                                                            <span id="shift_end_error" class="text-danger"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-12">
                                                        <label for="email-id-column">Duration</label>
                                                        <div class="form-group">
                                                            <input type="text" id="duration" name="duration"
                                                                class="form-control" placeholder="Duration"
                                                                id="days" disabled />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-12">
                                                        <label for="email-id-column">Amount Per Hour</label>
                                                        <div class="form-group">
                                                            <input type="number" id="rate" name="ratePerHour"
                                                                class="form-control reactive" placeholder="0" disabled />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-12">
                                                        <label for="email-id-column">Amount</label>
                                                        <div class="form-group">
                                                            <input type="text" id="amount" name="amount"
                                                                class="form-control" placeholder="0" disabled />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-12">
                                                        <label for="">Job Type</label>
                                                        <div class="form-group">
                                                            <select class="form-control select2" name="job_type_id"
                                                                id="job" aria-label="Default select example"
                                                                disabled>
                                                                <option value="" disabled selected hidden>Please
                                                                    Choose...
                                                                </option>
                                                                @foreach ($job_types as $job_type)
                                                                    <option value="{{ $job_type->id }}">
                                                                        {{ $job_type->name }}
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
                                                            <input type="text" name="remarks" id="remarks"
                                                                class="form-control" placeholder="remarks" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <div class="form-group d-flex">
                                        <button type="button" class="btn btn-outline-secondary btn-cancel"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn ml-1 bg-gradient-success"
                                            id="accept">Accept</button>
                                        <button type="button" class="btn ml-1 bg-gradient-success" id="request"
                                            disabled>Requested</button>
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

                                                <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25"
                                                    id="prev"><i data-feather='arrow-left'></i></button>
                                                <button type="button" class="btn bg-light-primary pt-50 pb-50 mt-25"
                                                    id="currentWeek">{{ \Carbon\Carbon::now()->startOfWeek()->format('d M, Y') }}
                                                    - {{ \Carbon\Carbon::now()->endOfWeek()->format('d M, Y') }}</button>
                                                <button type="button"
                                                    class="btn bg-light-primary pt-50 pb-50 mr-50 mt-25" id="next"><i
                                                        data-feather='arrow-right'></i></button>

                                                <button class="btn p-0 pt-50 pb-50 mr-50 mt-25">
                                                    <select id="project" class="form-control"
                                                        style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;"
                                                        name="project_id">
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
                                                    <table id="myTable"
                                                        class="myTable table table-bordered table-striped ">
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
                                            @for ($i = 0; $i < 3; $i++)
                                                @php
                                                    $row = isset($upcoming_roasters[$i]) ? $upcoming_roasters[$i] : null;
                                                @endphp
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>
                                                        @if ($row && isset($row->project->pName))
                                                            {{ $row->project->pName }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y') }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ getTime($row->shift_start) }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ getTime($row->shift_end) }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ $row->duration }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ $row->ratePerHour }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ $row->amount }}
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endfor
                                            <!-- <tr class="text-center" {{ $upcoming_roasters->count() == 0 ? '' : 'hidden' }}>
                                                                                                                                                                                                                                                    <td colspan="8">No data found!</td>
                                                                                                                                                                                                                                                </tr> -->
                                            <tr class="bg-light" {{ $upcoming_roasters->count() == 0 ? 'hidden' : '' }}>
                                                <td>#</td>
                                                <td colspan="4" class="text-center">Total</td>
                                                <td>{{ $upcoming_roasters->sum('duration') }} hours</td>
                                                <td></td>
                                                <td>$ {{ $upcoming_roasters->sum('amount') }}</td>
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
    margin-top: 20px;font-size: 18px;"
                                    class=" text-right">
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
                    <div class="row">
                        <div class="col-md-4">
                            <p class="card-title text-primary d-inline">Assigned shifts</p>
                        </div>
                        <div class="col-md-8">
                            <div class="card plan-card" id="hasData">
                                <div class="d-flex align-items-center">
                                    <button class="btn pl-2 pr-2 border-primary" onclick="checkAllID()">
                                        <input type="checkbox" class="mr-50" id="checkAllID" onclick="checkAllID()">
                                        <span>Check All</span>
                                    </button>
                                    <button class="btn btn-gradient-danger text-center border-primary ml-1 reject" disabled
                                        onclick="multipleShift('reject')">
                                        <span class="desktop-view">Reject</span>
                                        <i data-feather='x-circle'></i>
                                    </button>
                                    <button class="btn btn-gradient-success text-center border-primary accept" disabled
                                        onclick="multipleShift('accept')">
                                        <span class="desktop-view">Accept</span>
                                        <i data-feather='check-circle'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="mt-3 mt-md-0">
                                                                                                                                                                                                                                                                                        <a href="/home/unconfirmed/shift" class="btn btn-gradient-primary ">See more</a>
                                                                                                                                                                                                                                                                                    </div> -->
                </div>

                <div class="card-body">
                    <div class="row">
                        <table class="table table-hover-animation table-bordered height-300">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Date and Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 3; $i++)
                                    @php
                                        $roster = isset($unconfirm_roasters[$i]) ? $unconfirm_roasters[$i] : null;
                                    @endphp
                                    <tr id="roster{{ $roster ? $roster->id : '' }}">
                                        <td>
                                            @if ($roster)
                                                <input type="checkbox" value="{{ $roster->id }}"
                                                    style="height:16px; width:16px" class="checkID">
                                            @endif
                                        </td>
                                        <td>
                                            @if ($roster)
                                                {{ $roster->project->pName }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($roster)
                                                {{ \Carbon\Carbon::parse($roster->roaster_date)->format('d/m/Y') }},
                                                {{ getTime($roster->shift_start) }} - {{ getTime($roster->shift_end) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>

                    </div>

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
                                            @for ($i = 0; $i < 3; $i++)
                                                @php
                                                    $row = isset($past_roasters[$i]) ? $past_roasters[$i] : null;
                                                @endphp
                                                <tr
                                                    class="{{ $row && $row->roaster_type == 'Unschedueled' ? 'bg-light-primary' : '' }}">
                                                    <td class="p-0 pl-50">
                                                        @if ($row && $row->is_approved)
                                                            <i data-feather='check-circle' class="text-primary"></i>
                                                        @else
                                                            <span class="pl-1 ml-25"></span>
                                                        @endif
                                                        {{ $i + 1 }}
                                                    </td>
                                                    <td>
                                                        @if ($row && isset($row->project->pName))
                                                            {{ $row->project->pName }}
                                                        @else
                                                            Null
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y') }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ getTime($row->shift_start) }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ getTime($row->shift_end) }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ $row->duration }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ $row->ratePerHour }}
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row)
                                                            {{ $row->amount }}
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endfor
                                            <!--<tr>-->
                                            <!--    <td colspan="8" class="bg-light-primary text-center">-->
                                            <!--        <a href="/home/past/shift">See more</a>-->
                                            <!--    </td>-->
                                            <!--</tr>-->
                                        </tbody>

                                    </table>

                                </div>
                                <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;"
                                    class=" text-right">
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
                                        <table class="table table-hover-animation table-bordered height-300">
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
                                                @php
                                                    $totalRows = count($upcomingevents);
                                                    $maxRows = 3;
                                                @endphp

                                                @for ($i = 0; $i < $maxRows; $i++)
                                                    <tr>
                                                        @if ($i < $totalRows)
                                                            @php
                                                                $row = $upcomingevents[$i];
                                                            @endphp
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>{{ $row->project->pName }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($row->event_date)->format('d-m-Y') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($row->shift_start)->format('H:i') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($row->shift_end)->format('H:i') }}
                                                            </td>
                                                            <td>{{ $row->rate }}</td>
                                                            <td>{{ $row->remarks }}</td>
                                                            <td>
                                                                <form action="{{ route('store-event') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="event_id"
                                                                        value="{{ $row->id }}">
                                                                    @if (App\Models\Eventrequest::where('event_id', $row->id)->where('user_id', Auth::id())->count())
                                                                        <button class="btn btn-gradient-primary btn-sm"
                                                                            disabled>Requested</button>
                                                                    @else
                                                                        <button class="btn btn-gradient-primary btn-sm"
                                                                            type="submit">Interested</button>
                                                                    @endif
                                                                </form>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        @endif
                                                    </tr>
                                                @endfor

                                                <!-- <tr>
                                                                                                                                                                                                                                                                                                                        <td colspan="8" class="bg-light-primary text-center">
                                                                                                                                                                                                                                                                                                                            <a href="/user/home/upcomingevent/go">See more</a>
                                                                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                                                                    </tr> -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;"
                                        class=" text-right">
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
                                                    @for ($i = 0; $i < 3; $i++)
                                                        @php
                                                            $row = isset($timesheets[$i]) ? $timesheets[$i] : null;
                                                            $json = $row ? json_encode($row->toArray(), false) : null;
                                                        @endphp
                                                        <tr>
                                                            <td class="p-0 pl-50">
                                                                @if ($row && $row->is_approved)
                                                                    <i data-feather='check-circle'
                                                                        class="text-primary"></i>
                                                                @else
                                                                    <span class="pl-1 ml-25"></span>
                                                                @endif
                                                                {{ $i + 1 }}
                                                            </td>
                                                            <td>
                                                                @if ($row && isset($row->project->pName))
                                                                    {{ $row->project->pName }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row)
                                                                    {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-y') }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row)
                                                                    {{ getTime($row->shift_start) }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row)
                                                                    {{ getTime($row->shift_end) }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row)
                                                                    {{ $row->ratePerHour }}
                                                                @else
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                    <!--<tr>-->
                                                    <!--    <td colspan="6" class="bg-light-primary text-center">-->
                                                    <!--        <a href="/home/timesheet">See more</a>-->
                                                    <!--    </td>-->
                                                    <!--</tr>-->
                                                </tbody>

                                            </table>

                                        </div>
                                        <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;"
                                            class=" text-right">
                                            <a href="/home/timesheet">More...</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
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
                                                    @for ($i = 0; $i < 3; $i++)
                                                        @php
                                                            $payment = isset($payments[$i]) ? $payments[$i] : null;
                                                            $total_durations = $payment ? $total_durations + $payment->details->total_hours : 0;
                                                            $total_amount = $payment ? $total_amount + $payment->details->total_pay : 0;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>
                                                                @if ($payment)
                                                                    {{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d-m-Y') }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($payment)
                                                                    {{ $payment->details->total_hours }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($payment)
                                                                    {{ $payment->details->total_pay }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($payment)
                                                                    <a class="edit-btn btn-link btn"
                                                                        href="/home/payment/report/{{ $payment->id }}"
                                                                        target="_blank">
                                                                        <i data-feather="eye"></i>
                                                                    </a>
                                                                @else
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                </tbody>

                                            </table>
                                        </div>
                                        <div style="    padding: 10px 0;
    margin-top: 20px;font-size: 18px;"
                                            class=" text-right">
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


                @php
                    $status_ = [
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    ];
                    
                @endphp

                <div class="row" id="table-hover-animation">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <p class="card-title text-primary d-inline">Time Off</p>
                                </div>
                                <!-- <div class="mt-3 mt-md-0">
                                                                                                                                                                                                                                                                                                    <a href="/home/payment/report" class="btn btn-gradient-primary ">See more</a>
                                                                                                                                                                                                                                                                                                </div> -->
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" onclick="changeMode('availabity')" id="home-tab"
                                                data-toggle="tab" href="#home" aria-controls="home" role="tab"
                                                aria-selected="true">Unavailability</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" onclick="changeMode('leave')" id="profile-tab"
                                                data-toggle="tab" href="#profile" aria-controls="profile" role="tab"
                                                aria-selected="false">Leave Day</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="home" aria-labelledby="home-tab"
                                            role="tabpanel">
                                            <div class="card">

                                                <div class="card-header">
                                                    <button class="btn btn-primary" href="#"
                                                        onclick="openModal()">Add Unavailable</button>
                                                    {{-- <h4 class="bg-light-primary p-1 badge">Total Unavailable:
                                                        {{ $unavailabilities->where('status', 'approved')->sum('total') }}
                                                        Days
                                                    </h4> --}}
                                                </div>

                                                <div class="card-body pt-0">
                                                    <div class="container">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <!-- <th>Company Name</th> -->
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        <th>Total</th>
                                                                        <th>Leave Type</th>
                                                                        <th>Leave Reason</th>
                                                                        <th>status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @for ($i = 1; $i <= 3; $i++)
                                                                        @php
                                                                            $row = isset($unavailabilities[$i - 1]) ? $unavailabilities[$i - 1] : null;
                                                                            $json = $row ? json_encode($row->toArray(), false) : null;
                                                                        @endphp
                                                                        <tr
                                                                            class="{{ $row && $row->start_date <= \Carbon\Carbon::now() ? 'text-black-50' : '' }}">
                                                                            <td>{{ $i }}</td>
                                                                            <!-- <td>{{ Auth::user()->employee->company }}</td> -->
                                                                            <td>{{ $row ? \Carbon\Carbon::parse($row->start_date)->format('d-m-Y') : '' }}
                                                                            </td>
                                                                            <td>{{ $row ? \Carbon\Carbon::parse($row->end_date)->format('d-m-Y') : '' }}
                                                                            </td>
                                                                            <td>{{ $row ? $row->total : '' }}</td>
                                                                            <td>{{ $row && $row->leave_type ? $row->leave_type->name : '' }}
                                                                            </td>
                                                                            <td>{{ $row ? $row->remarks : '' }}</td>
                                                                            <td>
                                                                                @if ($row)
                                                                                    <p
                                                                                        class="badge badge-light-{{ $status_[$row->status] }}">
                                                                                        {{ $row->status }}</p>
                                                                                @else
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($row && $row->start_date < \Carbon\Carbon::now()->toDateString())
                                                                                    <p
                                                                                        class="badge badge-light-success p-75">
                                                                                        Ended</p>
                                                                                @elseif ($row && $row->status == 'approved')
                                                                                    <p
                                                                                        class="badge badge-light-primary p-75">
                                                                                        approved</p>
                                                                                @elseif (
                                                                                    $row &&
                                                                                        $row->start_date >=
                                                                                            \Carbon\Carbon::now()->addDay(7)->toDateString())
                                                                                    <button data-copy="true"
                                                                                        edit-it="true"
                                                                                        class="btn edit-btn btn-gradient-primary"
                                                                                        data-row="{{ $json }}"><i
                                                                                            data-feather='edit'></i></button>
                                                                                    <a url="/myavailability/delete/{{ $row->id }}"
                                                                                        class="btn btn-gradient-danger text-white del mt-md-25"><i
                                                                                            data-feather='trash-2'></i></a>
                                                                                @else
                                                                                    @if ($row && $row->status == 'pending')
                                                                                        <!-- <a url="/myavailability/delete/{{ $row->id }}" class="btn btn-gradient-danger text-white del mt-md-25"><i data-feather='trash-2'></i></a> -->
                                                                                        <p
                                                                                            class="badge badge-light-primary p-75">
                                                                                            Pending</p>
                                                                                    @else
                                                                                        {{-- <p class="badge badge-light-primary p-75">Running</p> --}}
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endfor
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="profile" aria-labelledby="profile-tab"
                                            role="tabpanel">
                                            <div class="card">
                                                <div class="card-header">
                                                    <button class="btn btn-primary" type="button"
                                                        onclick="openModal()">Add Leave</button>
                                                    {{-- <h4 class="bg-light-primary p-1 badge">Total Leave:
                                                        {{ $unavailabilities->where('status', 'approved')->sum('total') }}
                                                        Days
                                                    </h4> --}}
                                                </div>

                                                <div class="card-body">
                                                    <div class="container">
                                                        <div class="table-responsive">
                                                            <table class="table  table-bordered mb-4">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <!-- <th>Company Name</th> -->
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        <th>Total</th>
                                                                        <th>Leave Type</th>
                                                                        <th>Leave Reason</th>
                                                                        <th>status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @for ($i = 1; $i <= 3; $i++)
                                                                        @php
                                                                            $row = isset($leaves[$i - 1]) ? $leaves[$i - 1] : null;
                                                                            $json = $row ? json_encode($row->toArray(), false) : null;
                                                                        @endphp
                                                                        <tr
                                                                            class="{{ $row && $row->start_date <= \Carbon\Carbon::now() ? 'text-black-50' : '' }}">
                                                                            <td>{{ $i }}</td>
                                                                            <!-- <td>{{ Auth::user()->employee->company }}</td> -->
                                                                            <td>{{ $row ? \Carbon\Carbon::parse($row->start_date)->format('d-m-Y') : '' }}
                                                                            </td>
                                                                            <td>{{ $row ? \Carbon\Carbon::parse($row->end_date)->format('d-m-Y') : '' }}
                                                                            </td>
                                                                            <td>{{ $row ? $row->total : '' }}</td>
                                                                            <td>{{ $row && $row->leave_type ? $row->leave_type->name : '' }}
                                                                            </td>
                                                                            <td>{{ $row ? $row->remarks : '' }}</td>
                                                                            <td>
                                                                                @if ($row)
                                                                                    <p
                                                                                        class="badge badge-light-{{ $status_[$row->status] }}">
                                                                                        {{ $row->status }}</p>
                                                                                @else
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($row && $row->start_date < \Carbon\Carbon::now()->toDateString())
                                                                                    <p
                                                                                        class="badge badge-light-success p-75">
                                                                                        Ended</p>
                                                                                @elseif ($row && $row->status == 'approved')
                                                                                    <p
                                                                                        class="badge badge-light-primary p-75">
                                                                                        approved</p>
                                                                                @elseif (
                                                                                    $row &&
                                                                                        $row->start_date >=
                                                                                            \Carbon\Carbon::now()->addDay(7)->toDateString())
                                                                                    <button data-copy="true"
                                                                                        edit-it="true"
                                                                                        class="btn edit-btn btn-gradient-primary"
                                                                                        data-row="{{ $json }}"><i
                                                                                            data-feather='edit'></i></button>
                                                                                    <a url="/myavailability/delete/{{ $row->id }}"
                                                                                        class="btn btn-gradient-danger text-white del mt-md-25"><i
                                                                                            data-feather='trash-2'></i></a>
                                                                                @else
                                                                                    @if ($row && $row->status == 'pending')
                                                                                        <!-- <a url="/myavailability/delete/{{ $row->id }}" class="btn btn-gradient-danger text-white del mt-md-25"><i data-feather='trash-2'></i></a> -->
                                                                                        <p
                                                                                            class="badge badge-light-primary p-75">
                                                                                            Pending</p>
                                                                                    @else
                                                                                        {{-- <p
                                                                                            class="badge badge-light-primary p-75">
                                                                                            Running</p> --}}
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endfor
                                                                </tbody>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    @include('pages.User.myavailability.modals.AddModal')
                                </div>

                                <div class="container">

                                    <div style="    padding: 10px 0;
margin-top: 0px;font-size: 18px;"
                                        class=" text-right">
                                        <a href="/home/time/off">More...</a>
                                    </div>
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
                            <div class="container d-flex align-content-center ">
                                <div class="table-responsive">
                                    <div id="myModalDetail" class="modal">
                                        <div class="modal-content">
                                            <span class="close" onclick="closeModal()">&times;</span>
                                            <h2>Create New Post</h2>
                                            <hr>
                                            <form action="{{ url('home/messages') }}" method="POST">
                                                @csrf
                                                <label for="postTitle">Heading:</label><br>
                                                <input type="text" class="form-control" id="postTitle" name="heading"
                                                    required><br>
                                                <label for="postContent">Text:</label><br>
                                                <textarea id="postContent" class="form-control" name="text" rows="5" required></textarea><br>
                                                <label for="postTitle">Need Confirm:</label><br>
                                                <select name="need_confirm" id="need_confirm" class="form-control">
                                                    <option value="N">No</option>
                                                    <option value="Y">Yes</option>
                                                </select><br>
                                                <label for="selectVenue">Select Venue/Sites:</label>
                                                <select data-placeholder="Begin typing a name to filter" multiple
                                                    class="chosen-select" name="list_venue[]">
                                                    <option value="all" selected>All Venue</option>
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}">{{ $project->pName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <br>
                                                <br>
                                                <input type="submit" class="btn btn-primary" value="Create Post">
                                            </form>
                                        </div>
                                    </div>

                                    <div id="myModal" class="modal">
                                        <div class="modal-content post-modal">
                                            <span class="close" onclick="closeModal()">&times;</span>
                                            <div class="post-content">
                                                <h2 id="modalTitle"></h2>
                                                <p id="modalDescription"></p>
                                            </div>

                                            <div class="horizontal-line"></div>
                                            <br>
                                            <h5>Comments:</h5>
                                            <ul id="modalReplies"></ul>
                                            <div class="reply-form">
                                                <h5>Reply:</h5>
                                                <form id="replyForm" action="{{ url('home/messages/reply') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="message_id" id="reply_message_id">
                                                    <textarea name="text" id="replyContent" class="form-control" rows="3" required></textarea><br><br>
                                                    <input type="submit" class="btn btn-primary" value="Submit Reply">

                                                    <button type="button" style="float: right;" id="confirmationButton"
                                                        class="btn btn-info">
                                                        <i data-feather="check-circle"></i> Confirm
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card p-0">
                                        <div class="container p-0">
                                            <div
                                                class="card-header text-primary border-top-0 border-left-0 border-right-0">
                                                <h3 class="card-title text-primary d-inline">
                                                    Messages
                                                </h3>

                                            </div>
                                            <div class="card-body pb-0">

                                            </div>
                                            <div class="card-body pt-0 pb-0">
                                                <div class="row row-xs">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="table-hover-animation">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="container">
                                                        <div id="postList">
                                                            <!-- This is where the posts will be dynamically added -->
                                                        </div>

                                                        <div class="pagination" style="display: none;">
                                                            <!-- Updated pagination links with IDs -->
                                                            <!-- ... Your existing pagination links ... -->
                                                        </div>
                                                    </div>
                                                    <div class="container">

                                                        <div style="    padding: 10px 0;
                    margin-top: 10px;font-size: 18px;"
                                                            class=" text-right">
                                                            <a href="/home/messages">More...</a>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />

    <script>
        // Function to handle confirmation button click event
        function handleConfirmation() {
            var button = document.getElementById('confirmationButton');
            var the_message_id = document.getElementById('reply_message_id').value;

            // Check if the button is already confirmed
            if (button.classList.contains('confirmed')) {
                // Send AJAX request to unconfirm
                $.ajax({
                    url: "{{ url('home/messages/unconfirm') }}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include the CSRF token in headers
                    },
                    data: {
                        message_id: the_message_id,
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button appearance
                            button.classList.remove('confirmed');
                            button.classList.remove('btn-success');
                            button.classList.add('btn-info');
                            button.innerHTML =
                                `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Confirm`;
                            window.location.reload();
                        }
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            } else {
                // Send AJAX request to confirm
                $.ajax({
                    url: "{{ url('home/messages/confirm') }}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include the CSRF token in headers
                    },
                    data: {
                        message_id: the_message_id,
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button appearance
                            button.classList.add('confirmed');
                            button.classList.remove('btn-info');
                            button.classList.add('btn-success');
                            button.textContent = 'Confirmed';
                            window.location.reload();
                        }
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
        }

        // Function to send AJAX request
        function ajaxRequest(method, url, callback) {
            var xhr = new XMLHttpRequest();
            xhr.open(method, url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    callback(response);
                }
            };
            xhr.send();
        }

        // Attach click event listener to the button
        document.getElementById('confirmationButton').addEventListener('click', handleConfirmation);


        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
        // This is just a dummy data for demonstration purposes
        var posts = @json($messages)

        // Function to generate the HTML for each post
        function generatePostHTML(post) {
            return `<div class="post">
                <h3>${post.heading}</h3>
                <p>${post.text}</p>
              </div>`;
        }
        console.log(posts)

        function generatePostRowHTML(post) {
            var initials = post.fullname.match(/\b(\w)/g).join('').toUpperCase();
            var description = post.text.length > 100 ? post.text.substring(0, 100) + '...' : post.text;

            if (post.purposes && post.purposes.length > 0) {
                purpose = post.purposes.join(", ");
            } else {
                purpose = "All Venue";
            }

            return `<div class="post-row" onclick="openPostModal('${post.need_confirm}', '${post.my_confirm}', '${post.id}', '${post.heading}', '${post.text}', '${encodeURIComponent(JSON.stringify(post.replies))}')">
                <div class="initials">${initials}</div>
                <div class="post-details">
                  <div class="fullname">${post.fullname}</div>
                  <div class="timestamp-purpose">
                    <div class="timestamp">${post.publish_date} to <b>${purpose}</b></div>
                  </div>
                    ${description}
                </div>
                <div class="replies">Replies: ${post.replies.length}</div>
              </div>`;
        }

        // Function to render the posts on the page
        var postsPerPage = 5;

        function renderPosts(page) {

            var startIndex = (page - 1) * postsPerPage;
            var endIndex = startIndex + postsPerPage;

            var postList = document.getElementById("postList");
            postList.innerHTML = "";

            for (var i = startIndex; i < endIndex && i < posts.length; i++) {
                var postRowHTML = generatePostRowHTML(posts[i]);
                postList.innerHTML += postRowHTML;
            }
        }

        // Function to open the post modal
        function openPostModal(need_confirm, my_confirm, id, title, description, encodedReplies) {
            const decodedReplies = decodeURIComponent(encodedReplies);
            const replies = JSON.parse(decodedReplies);
            var modal = document.getElementById("myModal");
            var modalTitle = document.getElementById("modalTitle");
            var modalDescription = document.getElementById("modalDescription");
            var modalReplies = document.getElementById("modalReplies");
            var replyForm = document.getElementById("replyForm");
            var button = document.getElementById('confirmationButton');

            document.getElementById('reply_message_id').value = id;
            modalTitle.textContent = title;
            modalDescription.textContent = description;

            // Clear previous replies
            modalReplies.innerHTML = "";

            // Populate the list of replies
            for (var i = 0; i < replies.length; i++) {
                var replyItemHTML = generateReplyItemHTML(replies[i]);
                modalReplies.innerHTML += replyItemHTML;
            }

            // Show the reply form
            replyForm.style.display = "block";

            modal.style.display = "block";

            if (need_confirm == 'Y') {
                button.style.display = 'block';
                if (my_confirm == 'true') {
                    button.classList.add('confirmed');
                    button.classList.remove('btn-info');
                    button.classList.add('btn-success');
                    button.textContent = 'Confirmed';
                } else {
                    var button = document.getElementById('confirmationButton');
                    button.classList.remove('confirmed');
                    button.classList.remove('btn-success');
                    button.classList.add('btn-info');
                    button.innerHTML =
                        `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Confirm`;
                }
            } else {
                button.style.display = 'none';
            }
        }

        function generateReplyItemHTML(reply) {
            var initials = reply.fullname.match(/\b(\w)/g).join('').toUpperCase();

            return `<div class="post-row">
                <div class="initials">${initials}</div>
                <div class="post-details">
                  <div class="fullname">${reply.fullname}</div>
                  <div class="timestamp-purpose">
                    <div class="timestamp">${reply.publish_date}</div>
                  </div>
                    ${reply.text}
                </div>
              </div>`;
        }

        // Submit form to reply to a post
        var replyForm = document.getElementById("replyForm");
        replyForm.addEventListener("submit", function(event) {
            // event.preventDefault();

            // var replyContent = document.getElementById("replyContent").value;

            // Add the new reply to the post's replies array
            // var postIndex = 0; // Replace this with the actual index of the post
            // posts[postIndex].replies.push({
            //     fullname: "Your Full Name", // Replace with the actual full name
            //     content: replyContent,
            //     timestamp: new Date().toLocaleString() // Replace with the appropriate timestamp format
            // });

            // // Clear the reply form field
            // document.getElementById("replyContent").value = "";

            // // Re-render the posts to update the list
            // renderPosts(1);
        });

        // Initially render the first page
        renderPosts(1);

        function calculatePageCount() {
            return Math.ceil(posts.length / postsPerPage);
        }

        // Update the pagination links based on the number of pages
        function updatePaginationLinks() {
            var pageCount = calculatePageCount();
            var paginationLinks = "";

            for (var i = 1; i <= pageCount; i++) {
                paginationLinks += `<a href="#" id="page${i}" onclick="handlePageClick(${i})">${i}</a>`;
            }

            var paginationContainer = document.getElementsByClassName("pagination")[0];
            paginationContainer.innerHTML = paginationLinks;
        }

        // Initially render the posts and update the pagination links
        renderPosts(1);
        updatePaginationLinks();
        // Modal related functions
        var modal = document.getElementById("myModalDetail");

        var modal_create = document.getElementById("myModal");

        function openModal() {
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
            modal_create.style.display = "none";
        }

        // Submit form to create a new post
        // var createPostForm = document.getElementById("createPostForm");
        // createPostForm.addEventListener("submit", function(event) {
        //     event.preventDefault();

        //     var postTitle = document.getElementById("postTitle").value;
        //     var postContent = document.getElementById("postContent").value;

        //     // Create a new post object
        //     var newPost = {
        //         title: postTitle,
        //         content: postContent,
        //     };

        //     // Add the new post to the posts array
        //     posts.push(newPost);

        //     // Clear the form fields
        //     document.getElementById("postTitle").value = "";
        //     document.getElementById("postContent").value = "";

        //     // Close the modal
        //     closeModal();

        //     // Re-render the posts to update the list
        //     renderPosts(1);
        //     updatePaginationLinks();
        // });

        function handlePageClick(page) {
            renderPosts(page);

            // Update active class for pagination links
            var paginationLinks = document.getElementsByClassName("pagination")[0].getElementsByTagName("a");
            for (var i = 0; i < paginationLinks.length; i++) {
                paginationLinks[i].classList.remove("active");
            }
            var activeLink = document.getElementById("page" + page);
            activeLink.classList.add("active");
        }

        // Add click event listeners to pagination links
        var paginationLinks = document.getElementsByClassName("pagination")[0].getElementsByTagName("a");
        for (var i = 0; i < paginationLinks.length; i++) {
            paginationLinks[i].addEventListener("click", function(event) {
                event.preventDefault();
                var page = parseInt(this.innerHTML);
                handlePageClick(page);
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            let currentMode = 'availabity'
            changeMode = function(x) {
                currentMode = x
            }

            $(document).on("click", ".del", function() {
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location = $(this).attr('url')
                        }
                    });
            })
            $("#availabilityForm").validate()

            function myDateFormat(time) {
                time = time.split('-');
                return time[2] + '/' + time[1] + '/' + time[0];
            }

            allCalculation = function() {
                var start = myDateFormat($("#start_date").val());
                var end = myDateFormat($("#end_date").val());

                if (start && end) {
                    // calculate hours
                    const diffInMs = new Date(end) - new Date(start)
                    let diff = diffInMs / (1000 * 60 * 60 * 24);
                    if (diff >= 0) {
                        diff = diff + 1
                    } else {
                        diff = diff - 1
                    }
                    if (diff) {
                        $("#total").val(diff);
                    }

                } else {
                    $("#total").val('');
                }
            }

            openModal = function() {
                resetValue()
                $("#add").modal("show")
            }
            $(document).on("click", ".edit-btn", function() {
                resetValue()
                var rowData = $(this).data("row");

                $("#id").val(rowData.id);
                $("#start_date").val(moment(rowData.start_date).format('DD-MM-YYYY'))
                $("#end_date").val(moment(rowData.end_date).format('DD-MM-YYYY'))
                $("#leave_type_id").val(rowData.leave_type_id);
                $("#remarks").val(rowData.remarks)
                $("#total").val(rowData.total)

                if (currentMode == 'availabity') {
                    $('#availabilityForm').attr('action', "{{ route('myAvailability.update') }}");
                } else {
                    $('#availabilityForm').attr('action', "{{ route('leave.update') }}");
                }
                $('#addBtn').hide()
                $('#updateBtn').show()

                $("#add").modal("show")
            })

            function resetValue() {
                $('#addBtn').show()
                $('#updateBtn').hide()

                $("#id").val('');
                $("#start_date").val('')
                $("#end_date").val('')
                $("#total").val('')
                $("#leave_type_id").val('');
                $("#remarks").val('')

                if (currentMode == 'availabity') {
                    $('#myModalLabel17').html('Add Unavailability')
                    $('#availabilityForm').attr('action', "{{ route('myAvailability.store') }}");
                } else {
                    $('#myModalLabel17').html('Add Leave')
                    $('#availabilityForm').attr('action', "{{ route('leave.store') }}");
                }
            }
        });
    </script>
    <!-- sign-in script  -->
    <script>
        $(document).ready(function() {
            $('#mainForm').attr('action', '{{ $form_action }}')
            $('#mainForm').validate()
            let enhancer = null;

            measure = function(lat1 = -26.753044, lon1 = 136.050351, lat2,
                lon2) { // generally used geo measurement function
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
                            let distance = measure(pLat, pLon, position.coords.latitude, position
                                .coords.longitude)
                            if (distance > 500) {
                                swal({
                                        title: "Are you sure?",
                                        text: "You are " + Math.round(distance) +
                                            " meters away from the work place",
                                        icon: "warning",
                                        buttons: ["Cancel", "process"],
                                        dangerMode: true,
                                    })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            (async () => {
                                                enhancer = await Dynamsoft.DCE
                                                    .CameraEnhancer.createInstance();
                                                $('#my_camera').html(enhancer
                                                    .getUIElement())

                                                $(".dce-btn-close").hide()
                                                $(".dce-sel-resolution").hide()
                                                $(".dce-sel-camera").hide()

                                                await enhancer.open(true);

                                                let cameras = await enhancer
                                                    .getAllCameras();
                                                if (cameras.length) {
                                                    await enhancer.selectCamera(cameras[
                                                        0]);
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
                    document.getElementById('result').innerHTML = '<img src="' + imgUrl +
                        '" width="100%" height="100%"/>'
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

                indexes["hours" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 *
                    60));
                indexes["minutes" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));

                document.getElementById("countdown" + i).innerHTML = "Shift Starting in " + indexes["hours" + i] +
                    " hours, " +
                    indexes["minutes" + i] + " minutes ";

                if (indexes["distance" + i] < 0) {
                    // clearInterval(indexes["x" + i]);
                    indexes["distance" + i] = now - indexes["countDownDate" + i];
                    indexes["hours" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 *
                        60 * 60));
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
                shift_end_in_indexes["hours" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 * 60 *
                    60 * 24)) / (1000 * 60 * 60));
                shift_end_in_indexes["minutes" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 *
                    60 * 60)) / (1000 * 60));
                // Output the result in an element with id="demo"
                document.getElementById("shift-end-in" + i).innerHTML = "Shift ending in " + shift_end_in_indexes[
                        "hours" + i] + " hours, " +
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

                indexes["h" + i] = Math.floor((indexes["totalDistance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 *
                    60));
                indexes["m" + i] = Math.floor((indexes["totalDistance" + i] % (1000 * 60 * 60)) / (1000 * 60));

                document.getElementById("working" + i).innerHTML = indexes["h" + i] + "h, " +
                    indexes["m" + i] + "min.";
            }, 1000);
        }
    </script>

    <!-- calendar script  -->
    <script src="{{ asset('app-assets/js/scripts/pages/user-calendar-timekeeper.js') }}"></script>
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
        var searchEL = $(
            '<label class="float-left">Search a Date:<input type="text" id="search_date" name="search_date" class="form-control format-picker form-control-sm" placeholder="dd-mm-yyyy"></label>'
        );

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
                html += "<option value='" + val.id + "' " + (val.id == data.current_project ? 'selected' : '') +
                    ">" + val.pName + "</option>"
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
                setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1)
                    .addClass('modal-stack'));
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
    <style>
        /* Styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            animation: modalOpenAnimation 0.3s ease-out;
        }

        @keyframes modalOpenAnimation {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .close:hover,
        .close:focus {
            color: #333;
            text-decoration: none;
        }

        /* end */

        /* Other styles */
        #postList {
            height: auto;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .post {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .pagination {
            margin-top: 10px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #333;
        }

        .pagination a.active {
            background-color: #ccc;
        }

        .create-post-btn {
            margin-bottom: 10px;
        }

        .post-row {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .post-row:hover {
            background-color: #f1f1f1;
        }

        .post-row .initials {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: #ccc;
            border-radius: 50%;
            line-height: 40px;
            text-align: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .post-row .fullname {
            font-weight: bold;
        }

        .post-row .timestamp {
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .post-row .purpose {
            font-weight: bold;
        }

        .post-row .replies {
            color: #888;
            font-size: 12px;
        }

        .post-row .description {
            margin-top: 10px;
        }

        .post-modal .post-content {
            margin-bottom: 20px;
        }

        .post-modal .reply-form {
            margin-top: 20px;
        }

        .reply-item .initials {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: #ccc;
            border-radius: 50%;
            line-height: 40px;
            text-align: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .post-row {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .post-row:hover {
            background-color: #f1f1f1;
        }

        .initials {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background-color: #ccc;
            border-radius: 50%;
            line-height: 40px;
            text-align: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .post-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .fullname {
            font-weight: bold;
        }

        .timestamp-purpose {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #888;
            font-size: 12px;
            margin-top: 5px;
        }

        .horizontal-line {
            border-top: 1px solid #000;
            width: 100%;
        }

        .chosen-container {
            display: block;
            width: 100% !important;
        }
    </style>
@endpush
