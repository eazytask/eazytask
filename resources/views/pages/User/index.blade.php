@extends('layouts.Admin.master')

@php
    $roster = null;
    if (!function_exists('getTime')) {
        function getTime($date)
        {
            return \Carbon\Carbon::parse($date)->format('H:i');
        }
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

@section('admin_page_content')
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="{{ URL::asset('app-assets/velzon/images/profile-bg.jpg') }}" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="@if (Auth::user()->image != ''){{ 'https://api.eazytask.au/' . Auth::user()->image }}@else{{ URL::asset('app-assets/velzon/images/users/avatar-1.jpg') }}@endif"
                        alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{ Auth::user()->name }} {{ Auth::user()->mname }} {{ Auth::user()->lname }}</h3>
                    <p class="text-white text-opacity-75">
                        @if (auth()->user()->company_roles->contains('role', 2))
                            Admin
                        @elseif(auth()->user()->company_roles->contains('role', 4))
                            Supervisor
                        @elseif(auth()->user()->company_roles->contains('role', 5))
                            Operation
                        @elseif(auth()->user()->company_roles->contains('role', 6))
                            Manager
                        @elseif(auth()->user()->company_roles->contains('role', 7))
                            Account
                        @else
                            User
                        @endif
                    </p>
                </div>
            </div>
            <!--end col-->
            {{-- <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1">24.3K</h4>
                            <p class="fs-14 mb-0">Followers</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1">1.3K</h4>
                            <p class="fs-14 mb-0">Following</p>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!--end col-->

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex profile-wrapper">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#compliances" role="tab">
                                <i class="ri-list-unordered d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Compliances</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Projects</span>
                            </a>
                        </li>
                    </ul>
                    <div class="flex-shrink-0">
                        <a href="/admin/company/profile-settings/{{ Auth::user()->id }}" class="btn btn-success"><i
                                class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Info</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Full Name :</th>
                                                        <td class="text-muted">
                                                            {{ Auth::user()->name }} {{ Auth::user()->mname }} {{ Auth::user()->lname }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">E-mail :</th>
                                                        <td class="text-muted">{{Auth::user()->email}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Joining Date</th>
                                                        <td class="text-muted">{{\Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y')}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

                                
                                {{-- 
                                Start Schedule Section
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="card">
                                            <div class="card-body pb-0">
                                                <div style="flex:1;">
                                                    <h3 class="text-center" id="clock"></h3>
                                                </div>
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
                            
                                                                <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
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
                                                            <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
                                                                <div class="card plan-card border-primary text-center">
                                                                    <div class="justify-content-between align-items-center pt-75">
                            
                                                                        <div class="card-body">
                                                                            <p class="text-black-50">You have no scheduled shift at this time</p>
                                                                            <button type="button"
                                                                                class="btn btn-gradient-primary text-center btn-block setForm"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#userAddTimeKeeper">Unscheduled</button>
                            
                                                                        </div>
                            
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                            
                                                        @if ($not_ready_sign_in)
                                                            <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
                                                                <div class="card plan-card border-primary text-center">
                                                                    <div class="justify-content-between align-items-center pt-75">
                            
                                                                        <div class="card-body">
                                                                            <p class="mb-0 text-muted">You have no scheduled shift at this time</p>
                                                                            <button type="button"
                                                                                class="btn btn-gradient-primary text-center btn-block"
                                                                                data-bs-toggle="modal" data-bs-target="#userAddTimeKeeper">Start unscheduled
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
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($not_ready_sign_in)
                                    @include('pages.User.signin.modals.timeKeeperAddModal')
                                @endif
                                @include('pages.User.signin.modals.takePhotoModal') --}}

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-4">
                                                    <div class="flex-grow-1">
                                                        <h5 class="card-title mb-0">Upcoming Events</h5>
                                                    </div>
                                                    {{-- div.flex-shrink-0>.dropdown --}}
                                                </div>
                                                <div>
                                                    @foreach($upcomingevents as $upcomingevent)
                                                    <div class="d-flex align-items-center py-3">
                                                        <div class="avatar-xs flex-shrink-0 me-3">
                                                            <img src="@if($upcomingevent->cimage) https://api.eazytask.au/{{$upcomingevent->cimage}} @else {{asset('app-assets/velzon/images/users/user-dummy-img.jpg')}} @endif"
                                                                alt="" class="img-fluid rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div>
                                                                <h5 class="fs-14 mb-1">{{$upcomingevent->project->pName}}</h5>
                                                                <p class="fs-12 text-muted mb-0 mt-2"><i class="ri-wallet-3-fill ms-2"></i> {{$upcomingevent->rate}}</p>
                                                                @if($upcomingevent->remarks)
                                                                <p class="fs-12 text-muted mb-0"><i class="ri-bookmark-line ms-2"></i> {{$upcomingevent->remarks}}</p>
                                                                @endif
                                                                <p class="fs-13 text-muted mb-0">
                                                                    <i class="ri-calendar-line ms-2"></i>
                                                                    {{\Carbon\Carbon::parse($upcomingevent->event_date)->format('M d, Y')}} ({{\Carbon\Carbon::parse($upcomingevent->shift_start)->format('H:m')}} to {{\Carbon\Carbon::parse($upcomingevent->shift_end)->format('H:m')}})
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-2">
        
                                                            <form action="{{ route('store-event') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="event_id"
                                                                    value="{{ $upcomingevent->id }}">
                                                                @if (App\Models\Eventrequest::where('event_id', $upcomingevent->id)->where('user_id', Auth::id())->count())
                                                                    <button class="btn btn-outline-success btn-sm" disabled>
                                                                        <i class="ri-star-fill"></i>
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-sm btn-outline-success">
                                                                        <i class="ri-star-line align-middle"></i>
                                                                    </button>
                                                                @endif
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @if(!count($upcomingevents))
                                                    <h4 class="text-muted text-center mb-4">Not Found!</h4>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <a href="/user/home/upcomingevent/go">More...</a>
                                                </div>
                                            </div><!-- end card body -->

                                        </div>
                                    </div>
                                    <!--end card-->
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-4">
                                                    <div class="flex-grow-1">
                                                        <h5 class="card-title mb-0">Upcoming shifts</h5>
                                                    </div>
                                                    {{-- div.flex-shrink-0>.dropdown --}}
                                                </div>
                                                <div>
                                                    @foreach($upcoming_roasters as $upcoming_roaster)
                                                    <div class="d-flex align-items-center py-3">
                                                        <div class="avatar-xs flex-shrink-0 me-3">
                                                            <img src="@if($upcoming_roaster->cimage) https://api.eazytask.au/{{$upcoming_roaster->cimage}} @else {{asset('app-assets/velzon/images/users/user-dummy-img.jpg')}} @endif"
                                                                alt="" class="img-fluid rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div>
                                                                <h5 class="fs-14 mb-1">{{$upcoming_roaster->project->pName}}</h5>
                                                                <p class="fs-12 text-muted mb-0 mt-2"><i class="ri-wallet-3-fill ms-2"></i> {{$upcoming_roaster->amount}} ({{$upcoming_roaster->ratePerHour}})</p>
                                                                @if($upcoming_roaster->remarks)
                                                                <p class="fs-12 text-muted mb-0"><i class="ri-bookmark-line ms-2"></i> {{$upcoming_roaster->roaster_type}}</p>
                                                                @endif
                                                                <p class="fs-13 text-muted mb-0">
                                                                    <i class="ri-calendar-line ms-2"></i>
                                                                    {{\Carbon\Carbon::parse($upcoming_roaster->roaster_date)->format('M d, Y')}} ({{\Carbon\Carbon::parse($upcoming_roaster->shift_start)->format('H:m')}} to {{\Carbon\Carbon::parse($upcoming_roaster->shift_end)->format('H:m')}})
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @if(!count($upcoming_roasters))
                                                    <h4 class="text-muted text-center mb-4">Not Found!</h4>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <a href="/home/upcoming/shift">More...</a>
                                                </div>
                                            </div><!-- end card body -->
                                        </div>
                                    </div>
                                    <!--end card-->
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-4">
                                                    <div class="flex-grow-1">
                                                        <h5 class="card-title mb-0">Assigned shifts</h5>
                                                    </div>
                                                    {{-- div.flex-shrink-0>.dropdown --}}
                                                    <div class="d-flex align-items-center">
                                                        <button class="btn btn-sm pl-2 pr-2 border-primary" onclick="checkAllID()">
                                                            <input type="checkbox" class="mr-50" id="checkAllID" onclick="checkAllID()">
                                                            <span>Check All</span>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger text-center mx-1 reject" disabled
                                                            onclick="multipleShift('reject')">
                                                            <span class="desktop-view">Reject</span>
                                                            <i data-feather='x-circle'></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-success text-center accept" disabled
                                                            onclick="multipleShift('accept')">
                                                            <span class="desktop-view">Accept</span>
                                                            <i data-feather='check-circle'></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div>
                                                    @foreach($unconfirm_roasters as $unconfirm_roaster)
                                                    <div class="d-flex align-items-center py-3">
                                                        <div class="avatar-xs flex-shrink-0 me-3">
                                                            <img src="@if($unconfirm_roaster->cimage) https://api.eazytask.au/{{$unconfirm_roaster->cimage}} @else {{asset('app-assets/velzon/images/users/user-dummy-img.jpg')}} @endif"
                                                                alt="" class="img-fluid rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div>
                                                                <h5 class="fs-14 mb-1">{{$unconfirm_roaster->project->pName}}</h5>
                                                                <p class="fs-12 text-muted mb-0 mt-2"><i class="ri-wallet-3-fill ms-2"></i> {{$unconfirm_roaster->amount}} ({{$unconfirm_roaster->ratePerHour}})</p>
                                                                @if($unconfirm_roaster->roaster_type)
                                                                <p class="fs-12 text-muted mb-0"><i class="ri-bookmark-line ms-2"></i> {{$unconfirm_roaster->roaster_type}}</p>
                                                                @endif
                                                                <p class="fs-13 text-muted mb-0">
                                                                    <i class="ri-calendar-line ms-2"></i>
                                                                    {{\Carbon\Carbon::parse($unconfirm_roaster->roaster_date)->format('M d, Y')}} ({{\Carbon\Carbon::parse($unconfirm_roaster->shift_start)->format('H:m')}} to {{\Carbon\Carbon::parse($unconfirm_roaster->shift_end)->format('H:m')}})
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @if(!count($unconfirm_roasters))
                                                    <h4 class="text-muted text-center mb-4">Not Found!</h4>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <a href="/home/unconfirmed/shift">More...</a>
                                                </div>
                                            </div><!-- end card body -->
                                        </div>
                                    </div>
                                    <!--end card-->
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-4">
                                                    <div class="flex-grow-1">
                                                        <h5 class="card-title mb-0">Past shifts</h5>
                                                    </div>
                                                    {{-- div.flex-shrink-0>.dropdown --}}
                                                </div>
                                                <div>
                                                    @foreach($past_roasters as $past_roaster)
                                                    <div class="d-flex align-items-center py-3">
                                                        <div class="avatar-xs flex-shrink-0 me-3">
                                                            <img src="@if($past_roaster->cimage) https://api.eazytask.au/{{$past_roaster->cimage}} @else {{asset('app-assets/velzon/images/users/user-dummy-img.jpg')}} @endif"
                                                                alt="" class="img-fluid rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div>
                                                                <h5 class="fs-14 mb-1">{{$past_roaster->project->pName}}</h5>
                                                                <p class="fs-12 text-muted mb-0 mt-2"><i class="ri-wallet-3-fill ms-2"></i> {{$past_roaster->amount}} ({{$past_roaster->ratePerHour}})</p>
                                                                @if($past_roaster->roaster_type)
                                                                <p class="fs-12 text-muted mb-0"><i class="ri-bookmark-line ms-2"></i> {{$past_roaster->roaster_type}}</p>
                                                                @endif
                                                                <p class="fs-13 text-muted mb-0">
                                                                    <i class="ri-calendar-line ms-2"></i>
                                                                    {{\Carbon\Carbon::parse($past_roaster->roaster_date)->format('M d, Y')}} ({{\Carbon\Carbon::parse($past_roaster->shift_start)->format('H:m')}} to {{\Carbon\Carbon::parse($past_roaster->shift_end)->format('H:m')}})
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @if(!count($past_roasters))
                                                    <h4 class="text-muted text-center mb-4">Not Found!</h4>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <a href="/home/past/shift">More...</a>
                                                </div>
                                            </div><!-- end card body -->
                                        </div>
                                        <!--end card-->
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-4">
                                                    <div class="flex-grow-1">
                                                        <h5 class="card-title mb-0">Payments</h5>
                                                    </div>
                                                    {{-- div.flex-shrink-0>.dropdown --}}
                                                </div>
                                                <div>
                                                    @foreach($payments as $payment)
                                                    <div class="d-flex align-items-center py-3">
                                                        <div class="avatar-xs flex-shrink-0 me-3">
                                                            <img src="@if($payment->image) https://api.eazytask.au/{{$payment->image}} @else {{asset('app-assets/velzon/images/users/user-dummy-img.jpg')}} @endif"
                                                                alt="" class="img-fluid rounded-circle" />
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div>
                                                                <h5 class="fs-14 mb-1">{{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d-m-Y') }}</h5>
                                                                <p class="fs-13 text-muted mb-0">
                                                                    <i class="ri-calendar-line ms-2"></i>
                                                                    {{\Carbon\Carbon::parse($payment->shift_start)->format('H:m')}} to {{\Carbon\Carbon::parse($payment->shift_end)->format('H:m')}}
                                                                </p>
                                                                <p class="fs-13 text-muted mb-0">
                                                                    <i class="ri-wallet-3-fill ms-2"></i>
                                                                    {{ $payment->details->total_pay }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @if(!count($payments))
                                                    <h4 class="text-muted text-center mb-4">Not Found!</h4>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <a href="/home/payment/report">More...</a>
                                                </div>
                                            </div><!-- end card body -->
                                        </div>
                                    </div>
                                    <!--end card-->
                                </div>

                            </div>
                            <!--end col-->
                            <div class="col-xxl-9">
                                

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3 text-primary">Schedule</h5>
                                                <div class="container p-0 mb-2">
                                                    <div class=" pt-0 pb-0">
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
                
                                                <div id="table-hover-animation">
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
                                    </div><!-- end col -->
                                    <div class="col-md-12">
                                        <div class="card card-company-table">
                            
                                            <div class="card-body px-0 pb-0">
                                                <div>
                                                    <h5 class="card-title mb-3 ms-3">Calendar</h5>
                                                </div>
                                                <div class="mt-3 mt-md-0">
                                                    <!--   <a href="/home/calender" class="btn btn-gradient-primary "><i data-feather="calendar" class="avatar-icon font-medium-3"></i></a> -->
                                                </div>
                                                <div class="app-calendar overflow-hidden border mb-0">
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
                                            </div>
                                        </div>
                                        <section>
                                            <!-- Calendar Add/Update/Delete event modal-->
                                            <div class="modal modal-slide-in event-sidebar fade" id="add-new-sidebar">
                                                <div class="modal-dialog sidebar-lg">
                                                    <div class="modal-content p-0">
                                                        <div class="modal-header mb-1">
                                                            <h5 class="modal-title">View Event</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                        
                                                        <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                            <section id="multiple-column-form">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="row">
                                                                            <div class="col-md-12 col-12">
                                                                                <label for=""> Venue</label>
                                                                                <div class="mb-3">
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
                                                                                <div class="mb-3">
                                                                                    <input type="text" id="roaster_date" name="roaster_date"
                                                                                        disabled class="form-control format-picker"
                                                                                        placeholder="Roster Date" />
                                                                                </div>
                                                                            </div>
                        
                                                                            <div class="col-md-12 col-12">
                                                                                <label for="email-id-column">Shift Start</label>
                                                                                <div class="mb-3">
                        
                                                                                    <input type="text" disabled id="shift_start"
                                                                                        name="shift_start" class="form-control pickatime-format"
                                                                                        placeholder="Shift Start Time" />
                        
                                                                                    <span id="shift_start_error"
                                                                                        class="text-danger text-small"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-12">
                                                                                <label for="email-id-column">Shift Ends Date & Time</label>
                                                                                <div class="mb-3">
                        
                                                                                    <input type="text" disabled id="shift_end"
                                                                                        name="shift_end" class="form-control pickatime-format"
                                                                                        placeholder="Shift End Time" />
                                                                                    <span id="shift_end_error" class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                        
                                                                            <div class="col-md-12 col-12">
                                                                                <label for="email-id-column">Duration</label>
                                                                                <div class="mb-3">
                                                                                    <input type="text" id="duration" name="duration"
                                                                                        class="form-control" placeholder="Duration"
                                                                                        id="days" disabled />
                                                                                </div>
                                                                            </div>
                        
                                                                            <div class="col-md-12 col-12">
                                                                                <label for="email-id-column">Amount Per Hour</label>
                                                                                <div class="mb-3">
                                                                                    <input type="number" id="rate" name="ratePerHour"
                                                                                        class="form-control reactive" placeholder="0" disabled />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-12">
                                                                                <label for="email-id-column">Amount</label>
                                                                                <div class="mb-3">
                                                                                    <input type="text" id="amount" name="amount"
                                                                                        class="form-control" placeholder="0" disabled />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-12">
                                                                                <label for="">Job Type</label>
                                                                                <div class="mb-3">
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
                                                                            <div class="col-md-12 col-12">
                                                                                <label for="email-id-column">Remarks</label>
                                                                                <div class="mb-3">
                                                                                    <input type="text" name="remarks" id="remarks"
                                                                                        class="form-control" placeholder="remarks" disabled />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </section>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn ms-1 btn-success" id="accept">Accept</button>
                                                            <button type="button" class="btn ms-1 btn-success" id="request" disabled>Requested</button>
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
                                    <div class="col-md-12">
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
            
                                                <div id="myModal" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content post-modal">
                                                            <div class="modal-header">
                                                                <button class="btn-close float-end" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">

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
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="card p-0">
                                            <div class="card-body">

                                                <h3 class="card-title mb-3">
                                                    Messages
                                                </h3>
    
                                                <div class="card-body pt-0 pb-0">
                                                    <div class="row row-xs">
    
                                                    </div>
                                                </div>
    
                                                <div id="postList" class="border-0 p-0 mb-2">
                                                    <!-- This is where the posts will be dynamically added -->
                                                </div>

                                                <div class="pagination" style="display: none;">
                                                    <!-- Updated pagination links with IDs -->
                                                    <!-- ... Your existing pagination links ... -->
                                                </div>
                                  
                                                <div class=" text-end">
                                                    <a href="/home/messages">More...</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end row -->

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Inducted Site</h5>
                                        <!-- Swiper -->
                                        <div class="swiper project-swiper">
                                            <div class="d-flex justify-content-end gap-2 mb-2">
                                                <div class="slider-button-prev">
                                                    <div class="avatar-title fs-18 rounded px-1">
                                                        <i class="ri-arrow-left-s-line"></i>
                                                    </div>
                                                </div>
                                                <div class="slider-button-next">
                                                    <div class="avatar-title fs-18 rounded px-1">
                                                        <i class="ri-arrow-right-s-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-wrapper">
                                                @foreach($inductions as $induction)
                                                <div class="swiper-slide">
                                                    <div class="card profile-project-card shadow-none profile-project-success mb-0">
                                                        <div class="card-body p-4">
                                                            <div class="d-flex">
                                                                <div class="flex-grow-1 text-muted overflow-hidden">
                                                                    <h5 class="text-truncate mb-2 fw-semibold">
                                                                        {{$induction->pName}}
                                                                    </h5>
                                                                    <p class="text-muted text-truncate mb-0">
                                                                        Last Update : <span class="fw-semibold text-body">{{\Carbon\Carbon::parse($induction->updated_at)->diffForHumans()}}</span>
                                                                    </p>
                                                                    <p class="text-muted text-truncate mb-0">
                                                                        Induction Date : <span class="fw-semibold text-body">{{$induction->induction_date}}</span>
                                                                    </p>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="d-flex mt-4">
                                                                <div class="flex-grow-1">
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <div>
                                                                            <h5 class="fs-12 text-muted mb-0">Employee :</h5>
                                                                        </div>
                                                                        <div class="avatar-group">
                                                                            <div class="avatar-group-item">
                                                                                <div class="avatar-xs">
                                                                                    @if($induction->image)
                                                                                        <img src="https://api.eazytask.au/{{$induction->image}}" alt="" class="rounded-circle shadow img-fluid" />
                                                                                    @else
                                                                                        <img src="{{asset('app-assets/velzon/images/users/user-dummy-img.jpg')}}" alt="" class="rounded-circle shadow img-fluid" />
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {{$induction->employee_name}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end card body -->
                                                    </div>
                                                    <!-- end card -->
                                                </div>
                                                <!-- end slide item -->
                                                @endforeach
                                            </div>
                                            @if (!count($inductions))
                                                <h4 class="text-center text-muted mb-4">Not Found!</h4>
                                            @endif

                                        </div>

                                    </div>
                                    <!-- end card body -->
                                </div><!-- end card -->

                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <div class="tab-pane fade" id="compliances" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Compliances</h5>
                                <div class="acitivity-timeline">
                                    @foreach($compliances as $compliance)
                                    <div class="acitivity-item py-3 d-flex">
                                        <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle shadow">
                                                <img src="@if($compliance->image) https://api.eazytask.au/{{$compliance->image}} @else {{asset('app-assets/velzon/images/users/user-dummy-img.jpg') }} @endif" alt=""
                                                class="avatar-xs rounded-circle acitivity-avatar" />
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3 py-2">
                                            <h6 class="mb-1">{{$compliance->employee_name}} <span class="text-muted">({{$compliance->email}})</span>
                                                {{-- <span class="badge bg-secondary-subtle text-secondary align-middle">In Progress</span></h6> --}}
                                            <p class="text-muted mb-1 mt-2"><i class="ri-file-text-line align-middle ms-2"></i>
                                                {{$compliance->compliance_name}}
                                            </p>
                                            @if($compliance->comment)
                                            <p class="mb-2 text-muted"><i class="ri-discuss-line ms-2"></i> {{$compliance->comment}}</p>
                                            @endif
                                            <p class="mb-2 text-muted"><i class="ri-hashtag ms-2"></i> {{$compliance->certificate_no}}</p>
                                            
                                            <p class="mb-2 text-muted ms-2">Expired At: {{$compliance->expire_date}}</p>
                                            @if($compliance->document)
                                            <div class="row">
                                                <div class="col-xxl-4">
                                                    <div class="row border border-dashed gx-2 p-2 mb-2">
                                                        <div class="col-4">
                                                            <img src="https://api.eazytask.au/{{$compliance->document}}" alt="" class="img-fluid rounded material-shadow">
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </div>
                                            </div>
                                            @endif
                                            {{-- <div class="avatar-group mb-2">
                                                <a href="javascript: void(0);" class="avatar-group-item"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Christi">
                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-4.jpg') }}"
                                                        alt="" class="rounded-circle avatar-xs" />
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Frank Hook">
                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                        alt="" class="rounded-circle avatar-xs" />
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title=" Ruby">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                            R
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="more">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle">
                                                            2+
                                                        </div>
                                                    </div>
                                                </a>
                                            </div> --}}
                                            <small class="mb-0 text-muted">{{\Carbon\Carbon::parse($compliance->updated_at)->diffForHumans()}}</small>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if (!count($compliances))
                                        <h4 class="text-center text-muted mb-4">Not Found!</h4>
                                    @endif
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane fade" id="projects" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="text-muted text-center my-4">Coming Soon!</h4>
                                {{-- <div class="row">
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-warning">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Chat App Update</a>
                                                        </h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">2 year
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-warning-subtle text-warning fs-10">
                                                            Inprogress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-1.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-light text-primary">
                                                                            J
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-success">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">ABC Project
                                                                Customization</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">2 month
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-primary-subtle text-primary fs-10">
                                                            Progress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-8.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-6.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-primary">
                                                                            2+
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-info">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Client - Frank
                                                                Hook</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">1 hr
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-4.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-light text-primary">
                                                                            M
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-primary">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Velzon Project</a>
                                                        </h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">11 hr
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-success-subtle text-success fs-10">
                                                            Completed</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-5.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-danger">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Brand Logo Design</a>
                                                        </h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">10 min
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-6.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-light text-primary">
                                                                            E
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-primary">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Chat App</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">8 hr
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-warning-subtle text-warning fs-10">
                                                            Inprogress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-light text-primary">
                                                                            R
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-8.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-warning">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Project Update</a>
                                                        </h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">48 min
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-warning-subtle text-warning fs-10">
                                                            Inprogress</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-6.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-5.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-4.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none profile-project-success">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Client - Jennifer</a>
                                                        </h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">30 min
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-primary-subtle text-primary fs-10">
                                                            Process</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-1.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div
                                            class="card profile-project-card shadow-none mb-xxl-0   profile-project-info">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Bsuiness Template -
                                                                UI/UX design</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">7 month
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-success-subtle text-success fs-10">
                                                            Completed</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-2.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-4.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-primary">
                                                                            2+
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div
                                            class="card profile-project-card shadow-none mb-xxl-0  profile-project-success">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Update Project</a>
                                                        </h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">1 month
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-light text-primary">
                                                                            A
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div
                                            class="card profile-project-card shadow-none mb-sm-0  profile-project-danger">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">Bank Management
                                                                System</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">10 month
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-success-subtle text-success fs-10">
                                                            Completed</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-6.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-5.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title rounded-circle bg-primary">
                                                                            2+
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="card profile-project-card shadow-none mb-0  profile-project-primary">
                                            <div class="card-body p-4">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                        <h5 class="fs-14 text-truncate"><a href="#"
                                                                class="text-body">PSD to HTML
                                                                Convert</a></h5>
                                                        <p class="text-muted text-truncate mb-0">Last
                                                            Update : <span class="fw-semibold text-body">29 min
                                                                Ago</span></p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <div class="badge bg-info-subtle text-info fs-10">New
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <h5 class="fs-12 text-muted mb-0">
                                                                    Members :</h5>
                                                            </div>
                                                            <div class="avatar-group">
                                                                <div class="avatar-group-item">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                            alt=""
                                                                            class="rounded-circle img-fluid" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mt-4">
                                            <ul class="pagination pagination-separated justify-content-center mb-0">
                                                <li class="page-item disabled">
                                                    <a href="javascript:void(0);" class="page-link"><i
                                                            class="mdi mdi-chevron-left"></i></a>
                                                </li>
                                                <li class="page-item active">
                                                    <a href="javascript:void(0);" class="page-link">1</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">2</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">3</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">4</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link">5</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="javascript:void(0);" class="page-link"><i
                                                            class="mdi mdi-chevron-right"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> --}}
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->
                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ URL::asset('app-assets/velzon/libs/swiper/swiper-bundle.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}"> --}}
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/calendars/fullcalendar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/app-calendar.css')}}">
    <style>
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active {
            background-color: #3577f1!important;
            border-color: #3577f1!important;
            color: #000 !important;
        }
        .fc .fc-list-event-title a {
            color: #000 !important;
        }
        .fc-list-event-title{
            text-align: left;
        }
        .fc-view-harness.fc-view-harness-active{
            margin-inline: 0px;
            margin-bottom: 20px;
        }
        .avatar-xs img{
            aspect-ratio: 1/1;
        }
        .avatar-lg img{
            aspect-ratio: 1/1;
        }
        
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

@push('scripts')
    {{-- <script src="{{ URL::asset('app-assets/velzon/libs/fullcalendar/index.global.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('app-assets/velzon/js/pages/calendar.init.js') }}"></script> --}}
    <script src="{{ URL::asset('app-assets/velzon/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('app-assets/velzon/js/pages/profile.init.js') }}"></script>
    <script src="{{ URL::asset('app-assets/velzon/js/app.js') }}"></script>
    @include('components.datatablescript')
    @include('components.select2')

    
    <script src="{{asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <script src="{{asset('app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/extensions/moment.min.js')}}"></script>
    {{-- <script src="{{asset('app-assets\js\scripts\pages\app-calendar-timekeeper.js')}}"></script> --}}

    <script>
            fetchCompliances = function() {
                $.ajax({
                    url: '/home/user/compliance/fetch?get=3',
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if (data.data) {
                            $('#exampleCompliance').DataTable().clear().destroy();
                            $('#complianceBody').html(data.data);
                            $('#exampleCompliance').DataTable({
                                "pageLength": 3, // Set the number of rows to display on each page to 3
                                "paging": false, // Disable pagination
                                "info": false, // Optional: Disable showing 'Showing x of y entries' info
                                "searching": false // Optional: Disable search box
                            });
                            feather.replace({
                                width: 14,
                                height: 14
                            });
                        }

                        $("#addCompliance").modal("hide");
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            };

            fetchCompliances();

            complianceAddFunc = function() {
                if ($("#complianceForm").valid()) {
                    $.ajax({
                        data: $('#complianceForm').serialize(),
                        url: "/home/user/compliance/store",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            toastr[data.alertType](data.message, {
                                closeButton: true,
                                tapToDismiss: false,
                            });
                            fetchCompliances()
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            }
            $(document).on("click", ".del-compliance", function() {
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '/home/user/compliance/delete/' + $(this).data("id"),
                                type: 'get',
                                dataType: 'json',
                                success: function(data) {
                                    toastr[data.alertType](data.message, {
                                        closeButton: true,
                                        tapToDismiss: false,
                                    });
                                    fetchCompliances()
                                },
                                error: function(err) {
                                    console.log(err)
                                }
                            });
                        }
                    });
            })

            $(document).on("click", ".edit-btn-compliance", function() {
                resetValueCompliance()
                var rowData = $(this).data("row");

                $("#compliance_id").val(rowData.compliance_id).trigger('change')
                $("#certificate_no").val(rowData.certificate_no)
                $("#expire_date").val(moment(rowData.expire_date).format('DD-MM-YYYY'))
                $("#comment").val(rowData.comment)

                $("#editComplianceSubmit").prop("hidden", true)
                $("#addComplianceSubmit").prop("hidden", false)
                $("#addCompliance").modal("show")
            })

            function resetValueCompliance() {
                $("#editComplianceSubmit").prop("hidden", false)
                $("#addComplianceSubmit").prop("hidden", true)
                $("#compliance_id").val('').trigger('change')
                $("#certificate_no").val('')
                $("#expire_date").val()
                $("#comment").val('')
            }

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
                var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                    keyboard: false
                });
                myModal.show();
                


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
                            `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg> Confirm`;
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
                                            // (async () => {
                                            //     enhancer = await Dynamsoft.DCE
                                            //         .CameraEnhancer.createInstance();
                                            //     $('#my_camera').html(enhancer
                                            //         .getUIElement())

                                            //     $(".dce-btn-close").hide()
                                            //     $(".dce-sel-resolution").hide()
                                            //     $(".dce-sel-camera").hide()

                                            //     await enhancer.open(true);

                                            //     let cameras = await enhancer
                                            //         .getAllCameras();
                                            //     if (cameras.length) {
                                            //         await enhancer.selectCamera(cameras[
                                            //             0]);
                                            //     }
                                            // })();
                                            // $('#photomodal').modal("show")
                                            form.submit()
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
                html = '<option value="">Select Venue</option>'
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
                console.log($('#addTimeKeeper'))
                initAllDatePicker();
                allCalculation()

            })
        });
    </script>
    <!-- unconfirm shifts -->
    <script type="text/javascript">
        let ids = []
        console.log('hello');
        let totalId = <?php echo json_encode($all_ids); ?>;//php

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
