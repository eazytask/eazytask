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
    @component('components.breadcrumb')
        @slot('li_1')
            User
        @endslot
        @slot('title')
            Dashboard
        @endslot
    @endcomponent
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
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Documents</span>
                            </a>
                        </li>
                    </ul>
                    <div class="flex-shrink-0">
                        <a href="pages-profile-settings" class="btn btn-success"><i
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

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Portfolio</h5>
                                        <div class="d-flex flex-wrap gap-2">
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span class="avatar-title rounded-circle fs-16 bg-body text-light">
                                                        <i class="ri-github-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span class="avatar-title rounded-circle fs-16 bg-primary">
                                                        <i class="ri-global-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span class="avatar-title rounded-circle fs-16 bg-success">
                                                        <i class="ri-dribbble-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0);" class="avatar-xs d-block">
                                                    <span class="avatar-title rounded-circle fs-16 bg-danger">
                                                        <i class="ri-pinterest-fill"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Skills</h5>
                                        <div class="d-flex flex-wrap gap-2 fs-15">
                                            <a href="javascript:void(0);"
                                                class="badge bg-primary-subtle text-primary">Photoshop</a>
                                            <a href="javascript:void(0);"
                                                class="badge bg-primary-subtle text-primary">illustrator</a>
                                            <a href="javascript:void(0);"
                                                class="badge bg-primary-subtle text-primary">HTML</a>
                                            <a href="javascript:void(0);"
                                                class="badge bg-primary-subtle text-primary">CSS</a>
                                            <a href="javascript:void(0);"
                                                class="badge bg-primary-subtle text-primary">Javascript</a>
                                            <a href="javascript:void(0);"
                                                class="badge bg-primary-subtle text-primary">Php</a>
                                            <a href="javascript:void(0);"
                                                class="badge bg-primary-subtle text-primary">Python</a>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

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
                                    </div><!-- end card body -->
                                </div>
                                <!--end card-->

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
                                    </div><!-- end card body -->
                                </div>
                                <!--end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Assigned shifts</h5>
                                            </div>
                                            {{-- div.flex-shrink-0>.dropdown --}}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <button class="btn pl-2 pr-2 border-primary" onclick="checkAllID()">
                                                <input type="checkbox" class="mr-50" id="checkAllID" onclick="checkAllID()">
                                                <span>Check All</span>
                                            </button>
                                            <button class="btn btn-danger text-center mx-1 reject" disabled
                                                onclick="multipleShift('reject')">
                                                <span class="desktop-view">Reject</span>
                                                <i data-feather='x-circle'></i>
                                            </button>
                                            <button class="btn btn-success text-center accept" disabled
                                                onclick="multipleShift('accept')">
                                                <span class="desktop-view">Accept</span>
                                                <i data-feather='check-circle'></i>
                                            </button>
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
                                    </div><!-- end card body -->
                                </div>
                                <!--end card-->

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
                                    </div><!-- end card body -->
                                </div>
                                <!--end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Popular Posts</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a href="#" role="button" id="dropdownMenuLink1"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-2-fill fs-14"></i>
                                                    </a>

                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuLink1">
                                                        <li><a class="dropdown-item" href="#">View</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#">Edit</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('app-assets/velzon/images/small/img-4.jpg') }}" alt=""
                                                    height="50" class="rounded" />
                                            </div>
                                            <div class="flex-grow-1 ms-3 overflow-hidden">
                                                <a href="javascript:void(0);">
                                                    <h6 class="text-truncate fs-14">Design your apps in
                                                        your own way</h6>
                                                </a>
                                                <p class="text-muted mb-0">15 Dec 2021</p>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('app-assets/velzon/images/small/img-5.jpg') }}" alt=""
                                                    height="50" class="rounded" />
                                            </div>
                                            <div class="flex-grow-1 ms-3 overflow-hidden">
                                                <a href="javascript:void(0);">
                                                    <h6 class="text-truncate fs-14">Smartest
                                                        Applications for Business</h6>
                                                </a>
                                                <p class="text-muted mb-0">28 Nov 2021</p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('app-assets/velzon/images/small/img-6.jpg') }}" alt=""
                                                    height="50" class="rounded" />
                                            </div>
                                            <div class="flex-grow-1 ms-3 overflow-hidden">
                                                <a href="javascript:void(0);">
                                                    <h6 class="text-truncate fs-14">How to get creative
                                                        in your work</h6>
                                                </a>
                                                <p class="text-muted mb-0">21 Nov 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-body-->
                                </div>
                                <!--end card-->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">About</h5>
                                        <p>Hi I'm Anna Adame, It will be as simple as Occidental; in
                                            fact, it will be Occidental. To an English person, it will
                                            seem like simplified English, as a skeptical Cambridge
                                            friend of mine told me what Occidental is European languages
                                            are members of the same family.</p>
                                        <p>You always want to make sure that your fonts work well
                                            together and try to limit the number of fonts you use to
                                            three or less. Experiment and play around with the fonts
                                            that you already have in the software you’re working with
                                            reputable font websites. This may be the most commonly
                                            encountered tip I received from the designers I spoke with.
                                            They highly encourage that you use different fonts in one
                                            design, but do not over-exaggerate and go overboard.</p>
                                        <div class="row">
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-user-2-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Designation :</p>
                                                        <h6 class="text-truncate mb-0">Lead Designer /
                                                            Developer</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-global-line"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Website :</p>
                                                        <a href="#" class="fw-semibold">www.velzon.com</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0  me-2">Recent Activity</h4>
                                                <div class="flex-shrink-0 ms-auto">
                                                    <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0"
                                                        role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-bs-toggle="tab"
                                                                href="#today" role="tab">
                                                                Today
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#weekly"
                                                                role="tab">
                                                                Weekly
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#monthly"
                                                                role="tab">
                                                                Monthly
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content text-muted">
                                                    <div class="tab-pane active" id="today" role="tabpanel">
                                                        <div class="profile-timeline">
                                                            <div class="accordion accordion-flush" id="todayExample">
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="headingOne">
                                                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-2.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Jacqueline Steve
                                                                                    </h6>
                                                                                    <small class="text-muted">We
                                                                                        has changed 2
                                                                                        attributes on
                                                                                        05:16PM</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapseOne"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="headingOne"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            In an awareness campaign, it
                                                                            is vital for people to begin
                                                                            put 2 and 2 together and
                                                                            begin to recognize your
                                                                            cause. Too much or too
                                                                            little spacing, as in the
                                                                            example below, can make
                                                                            things unpleasant for the
                                                                            reader. The goal is to make
                                                                            your text as comfortable to
                                                                            read as possible. A
                                                                            wonderful serenity has taken
                                                                            possession of my entire
                                                                            soul, like these sweet
                                                                            mornings of spring which I
                                                                            enjoy with my whole heart.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="headingTwo">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapseTwo"
                                                                            aria-expanded="false">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0 avatar-xs">
                                                                                    <div
                                                                                        class="avatar-title bg-light text-success rounded-circle">
                                                                                        M
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Megan Elmore
                                                                                    </h6>
                                                                                    <small class="text-muted">Adding
                                                                                        a new event with
                                                                                        attachments -
                                                                                        04:45PM</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapseTwo"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="headingTwo"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            <div class="row g-2">
                                                                                <div class="col-auto">
                                                                                    <div
                                                                                        class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                        <div class="flex-shrink-0">
                                                                                            <i
                                                                                                class="ri-image-2-line fs-17 text-danger"></i>
                                                                                        </div>
                                                                                        <div class="flex-grow-1 ms-2">
                                                                                            <h6><a href="javascript:void(0);"
                                                                                                    class="stretched-link">Business
                                                                                                    Template
                                                                                                    -
                                                                                                    UI/UX
                                                                                                    design</a>
                                                                                            </h6>
                                                                                            <small>685
                                                                                                KB</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <div
                                                                                        class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                        <div class="flex-shrink-0">
                                                                                            <i
                                                                                                class="ri-file-zip-line fs-17 text-info"></i>
                                                                                        </div>
                                                                                        <div class="flex-grow-1 ms-2">
                                                                                            <h6 class="mb-0">
                                                                                                <a href="javascript:void(0);"
                                                                                                    class="stretched-link">Bank
                                                                                                    Management System -
                                                                                                    PSD</a>
                                                                                            </h6>
                                                                                            <small>8.78
                                                                                                MB</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="headingThree">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse"
                                                                            href="#collapsethree" aria-expanded="false">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-5.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        New ticket
                                                                                        received</h6>
                                                                                    <small class="text-muted mb-2">User
                                                                                        <span
                                                                                            class="text-secondary">Erica245</span>
                                                                                        submitted a
                                                                                        ticket -
                                                                                        02:33PM</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="headingFour">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapseFour"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0 avatar-xs">
                                                                                    <div
                                                                                        class="avatar-title bg-light text-muted rounded-circle">
                                                                                        <i class="ri-user-3-fill"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Nancy Martino
                                                                                    </h6>
                                                                                    <small class="text-muted">Commented
                                                                                        on
                                                                                        12:57PM</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapseFour"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="headingFour"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5 fst-italic">
                                                                            " A wonderful serenity has
                                                                            taken possession of my
                                                                            entire soul, like these
                                                                            sweet mornings of spring
                                                                            which I enjoy with my whole
                                                                            heart. Each design is a new,
                                                                            unique piece of art birthed
                                                                            into this world, and while
                                                                            you have the opportunity to
                                                                            be creative and make your
                                                                            own style choices. "
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="headingFive">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapseFive"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Lewis Arnold
                                                                                    </h6>
                                                                                    <small class="text-muted">Create
                                                                                        new project
                                                                                        buildng product
                                                                                        -
                                                                                        10:05AM</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapseFive"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="headingFive"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            <p class="text-muted mb-2">
                                                                                Every team project can
                                                                                have a velzon. Use the
                                                                                velzon to share
                                                                                information with your
                                                                                team to understand and
                                                                                contribute to your
                                                                                project.</p>
                                                                            <div class="avatar-group">
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title="Christi">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-4.jpg') }}"
                                                                                        alt=""
                                                                                        class="rounded-circle avatar-xs">
                                                                                </a>
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title="Frank Hook">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                                                        alt=""
                                                                                        class="rounded-circle avatar-xs">
                                                                                </a>
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title=" Ruby">
                                                                                    <div class="avatar-xs">
                                                                                        <div
                                                                                            class="avatar-title rounded-circle bg-light text-primary">
                                                                                            R
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title="more">
                                                                                    <div class="avatar-xs">
                                                                                        <div
                                                                                            class="avatar-title rounded-circle">
                                                                                            2+
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end accordion-->
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="weekly" role="tabpanel">
                                                        <div class="profile-timeline">
                                                            <div class="accordion accordion-flush" id="weeklyExample">
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading6">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse6"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Joseph Parker
                                                                                    </h6>
                                                                                    <small class="text-muted">New
                                                                                        people joined
                                                                                        with our company
                                                                                        -
                                                                                        Yesterday</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse6"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="heading6"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            It makes a statement, it’s
                                                                            impressive graphic design.
                                                                            Increase or decrease the
                                                                            letter spacing depending on
                                                                            the situation and try, try
                                                                            again until it looks right,
                                                                            and each letter has the
                                                                            perfect spot of its own.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading7">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse7"
                                                                            aria-expanded="false">
                                                                            <div class="d-flex">
                                                                                <div class="avatar-xs">
                                                                                    <div
                                                                                        class="avatar-title rounded-circle bg-light text-danger">
                                                                                        <i
                                                                                            class="ri-shopping-bag-line"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Your order is
                                                                                        placed <span
                                                                                            class="badge bg-success-subtle text-success align-middle">Completed</span>
                                                                                    </h6>
                                                                                    <small class="text-muted">These
                                                                                        customers can
                                                                                        rest assured
                                                                                        their order has
                                                                                        been placed - 1
                                                                                        week Ago</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading8">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse8"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0 avatar-xs">
                                                                                    <div
                                                                                        class="avatar-title bg-light text-success rounded-circle">
                                                                                        <i class="ri-home-3-line"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Velzon admin
                                                                                        dashboard
                                                                                        templates layout
                                                                                        upload</h6>
                                                                                    <small class="text-muted">We
                                                                                        talked about a
                                                                                        project on
                                                                                        linkedin - 1
                                                                                        week Ago</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse8"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="heading8"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5 fst-italic">
                                                                            Powerful, clean & modern
                                                                            responsive bootstrap 5 admin
                                                                            template. The maximum file
                                                                            size for uploads in this
                                                                            demo :
                                                                            <div class="row mt-2">
                                                                                <div class="col-xxl-6">
                                                                                    <div
                                                                                        class="row border border-dashed gx-2 p-2">
                                                                                        <div class="col-3">
                                                                                            <img src="{{ URL::asset('app-assets/velzon/images/small/img-3.jpg') }}"
                                                                                                alt=""
                                                                                                class="img-fluid rounded" />
                                                                                        </div>
                                                                                        <!--end col-->
                                                                                        <div class="col-3">
                                                                                            <img src="{{ URL::asset('app-assets/velzon/images/small/img-5.jpg') }}"
                                                                                                alt=""
                                                                                                class="img-fluid rounded" />
                                                                                        </div>
                                                                                        <!--end col-->
                                                                                        <div class="col-3">
                                                                                            <img src="{{ URL::asset('app-assets/velzon/images/small/img-7.jpg') }}"
                                                                                                alt=""
                                                                                                class="img-fluid rounded" />
                                                                                        </div>
                                                                                        <!--end col-->
                                                                                        <div class="col-3">
                                                                                            <img src="{{ URL::asset('app-assets/velzon/images/small/img-9.jpg') }}"
                                                                                                alt=""
                                                                                                class="img-fluid rounded" />
                                                                                        </div>
                                                                                        <!--end col-->
                                                                                    </div>
                                                                                    <!--end row-->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading9">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse9"
                                                                            aria-expanded="false">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-6.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        New ticket
                                                                                        created <span
                                                                                            class="badge bg-info-subtle text-info align-middle">Inprogress</span>
                                                                                    </h6>
                                                                                    <small class="text-muted mb-2">User
                                                                                        <span
                                                                                            class="text-secondary">Jack365</span>
                                                                                        submitted a
                                                                                        ticket - 2 week
                                                                                        Ago</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading10">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse10"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-5.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Jennifer Carter
                                                                                    </h6>
                                                                                    <small class="text-muted">Commented
                                                                                        - 4 week
                                                                                        Ago</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse10"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="heading10"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            <p class="text-muted fst-italic mb-2">
                                                                                " This is an awesome
                                                                                admin dashboard
                                                                                template. It is
                                                                                extremely well
                                                                                structured and uses
                                                                                state of the art
                                                                                components (e.g. one of
                                                                                the only templates using
                                                                                boostrap 5.1.3 so far).
                                                                                I integrated it into a
                                                                                Rails 6 project. Needs
                                                                                manual integration work
                                                                                of course but the
                                                                                template structure made
                                                                                it easy. "</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end accordion-->
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="monthly" role="tabpanel">
                                                        <div class="profile-timeline">
                                                            <div class="accordion accordion-flush" id="monthlyExample">
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading11">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse11"
                                                                            aria-expanded="false">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0 avatar-xs">
                                                                                    <div
                                                                                        class="avatar-title bg-light text-success rounded-circle">
                                                                                        M
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Megan Elmore
                                                                                    </h6>
                                                                                    <small class="text-muted">Adding
                                                                                        a new event with
                                                                                        attachments - 1
                                                                                        month
                                                                                        Ago.</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse11"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="heading11"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            <div class="row g-2">
                                                                                <div class="col-auto">
                                                                                    <div
                                                                                        class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                        <div class="flex-shrink-0">
                                                                                            <i
                                                                                                class="ri-image-2-line fs-17 text-danger"></i>
                                                                                        </div>
                                                                                        <div class="flex-grow-1 ms-2">
                                                                                            <h6 class="mb-0">
                                                                                                <a href="javascript:void(0);"
                                                                                                    class="stretched-link">Business
                                                                                                    Template
                                                                                                    -
                                                                                                    UI/UX
                                                                                                    design</a>
                                                                                            </h6>
                                                                                            <small>685
                                                                                                KB</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <div
                                                                                        class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                        <div class="flex-shrink-0">
                                                                                            <i
                                                                                                class="ri-file-zip-line fs-17 text-info"></i>
                                                                                        </div>
                                                                                        <div class="flex-grow-1 ms-2">
                                                                                            <h6 class="mb-0">
                                                                                                <a href="javascript:void(0);"
                                                                                                    class="stretched-link">Bank
                                                                                                    Management
                                                                                                    System
                                                                                                    -
                                                                                                    PSD</a>
                                                                                            </h6>
                                                                                            <small>8.78
                                                                                                MB</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <div
                                                                                        class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                        <div class="flex-shrink-0">
                                                                                            <i
                                                                                                class="ri-file-zip-line fs-17 text-info"></i>
                                                                                        </div>
                                                                                        <div class="flex-grow-1 ms-2">
                                                                                            <h6 class="mb-0">
                                                                                                <a href="javascript:void(0);"
                                                                                                    class="stretched-link">Bank
                                                                                                    Management
                                                                                                    System
                                                                                                    -
                                                                                                    PSD</a>
                                                                                            </h6>
                                                                                            <small>8.78
                                                                                                MB</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading12">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse12"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-2.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Jacqueline Steve
                                                                                    </h6>
                                                                                    <small class="text-muted">We
                                                                                        has changed 2
                                                                                        attributes on 3
                                                                                        month
                                                                                        Ago</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse12"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="heading12"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            In an awareness campaign, it
                                                                            is vital for people to begin
                                                                            put 2 and 2 together and
                                                                            begin to recognize your
                                                                            cause. Too much or too
                                                                            little spacing, as in the
                                                                            example below, can make
                                                                            things unpleasant for the
                                                                            reader. The goal is to make
                                                                            your text as comfortable to
                                                                            read as possible. A
                                                                            wonderful serenity has taken
                                                                            possession of my entire
                                                                            soul, like these sweet
                                                                            mornings of spring which I
                                                                            enjoy with my whole heart.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading13">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse13"
                                                                            aria-expanded="false">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-5.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        New ticket
                                                                                        received</h6>
                                                                                    <small class="text-muted mb-2">User
                                                                                        <span
                                                                                            class="text-secondary">Erica245</span>
                                                                                        submitted a
                                                                                        ticket - 5 month
                                                                                        Ago</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading14">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse14"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0 avatar-xs">
                                                                                    <div
                                                                                        class="avatar-title bg-light text-muted rounded-circle">
                                                                                        <i class="ri-user-3-fill"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Nancy Martino
                                                                                    </h6>
                                                                                    <small class="text-muted">Commented
                                                                                        on 24 Nov,
                                                                                        2021.</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse14"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="heading14"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5 fst-italic">
                                                                            " A wonderful serenity has
                                                                            taken possession of my
                                                                            entire soul, like these
                                                                            sweet mornings of spring
                                                                            which I enjoy with my whole
                                                                            heart. Each design is a new,
                                                                            unique piece of art birthed
                                                                            into this world, and while
                                                                            you have the opportunity to
                                                                            be creative and make your
                                                                            own style choices. "
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item border-0">
                                                                    <div class="accordion-header" id="heading15">
                                                                        <a class="accordion-button p-2 shadow-none"
                                                                            data-bs-toggle="collapse" href="#collapse15"
                                                                            aria-expanded="true">
                                                                            <div class="d-flex">
                                                                                <div class="flex-shrink-0">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-7.jpg') }}"
                                                                                        alt=""
                                                                                        class="avatar-xs rounded-circle" />
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h6 class="fs-14 mb-1">
                                                                                        Lewis Arnold
                                                                                    </h6>
                                                                                    <small class="text-muted">Create
                                                                                        new project
                                                                                        buildng product
                                                                                        - 8 month
                                                                                        Ago</small>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse15"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="heading15"
                                                                        data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body ms-2 ps-5">
                                                                            <p class="text-muted mb-2">
                                                                                Every team project can
                                                                                have a velzon. Use the
                                                                                velzon to share
                                                                                information with your
                                                                                team to understand and
                                                                                contribute to your
                                                                                project.</p>
                                                                            <div class="avatar-group">
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title="Christi">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-4.jpg') }}"
                                                                                        alt=""
                                                                                        class="rounded-circle avatar-xs">
                                                                                </a>
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title="Frank Hook">
                                                                                    <img src="{{ URL::asset('app-assets/velzon/images/users/avatar-3.jpg') }}"
                                                                                        alt=""
                                                                                        class="rounded-circle avatar-xs">
                                                                                </a>
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title=" Ruby">
                                                                                    <div class="avatar-xs">
                                                                                        <div
                                                                                            class="avatar-title rounded-circle bg-light text-primary">
                                                                                            R
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                                <a href="javascript: void(0);"
                                                                                    class="avatar-group-item"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover"
                                                                                    data-bs-placement="top" title=""
                                                                                    data-bs-original-title="more">
                                                                                    <div class="avatar-xs">
                                                                                        <div
                                                                                            class="avatar-title rounded-circle">
                                                                                            2+
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end accordion-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->
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
                                                                    <h5 class="fs-14 text-truncate mb-1">
                                                                        <a href="#" class="text-body">
                                                                            {{$induction->pName}}
                                                                        </a>
                                                                    </h5>
                                                                    <p class="text-muted text-truncate mb-0">
                                                                        Last Update : <span class="fw-semibold text-body">{{\Carbon\Carbon::parse($induction->updated_at)->diffForHumans()}}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex mt-4">
                                                                <div class="flex-grow-1">
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <div>
                                                                            <h5 class="fs-12 text-muted mb-0">Members :</h5>
                                                                        </div>
                                                                        <div class="avatar-group">
                                                                            <div class="avatar-group-item">
                                                                                <div class="avatar-xs">
                                                                                    @if($induction->image)
                                                                                        <img src="upcomingevents{{$induction->image}}" alt="" class="rounded-circle shadow img-fluid" />
                                                                                    @else
                                                                                        <img src="{{asset('app-assets/velzon/images/users/user-dummy-img.jpg')}}" alt="" class="rounded-circle shadow img-fluid" />
                                                                                    @endif
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
                                                <!-- end slide item -->
                                                @endforeach
                                            </div>

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
                                <div class="row">
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
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4">
                                    <h5 class="card-title flex-grow-1 mb-0">Documents</h5>
                                    <div class="flex-shrink-0">
                                        <input class="form-control d-none" type="file" id="formFile">
                                        <label for="formFile" class="btn btn-danger"><i
                                                class="ri-upload-2-fill me-1 align-bottom"></i> Upload
                                            File</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-borderless align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">File Name</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Size</th>
                                                        <th scope="col">Upload Date</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div
                                                                        class="avatar-title bg-primary-subtle text-primary rounded fs-20">
                                                                        <i class="ri-file-zip-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a
                                                                            href="javascript:void(0)">Artboard-documents.zip</a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Zip File</td>
                                                        <td>4.57 MB</td>
                                                        <td>12 Dec 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-light btn-icon"
                                                                    id="dropdownMenuLink15" data-bs-toggle="dropdown"
                                                                    aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    aria-labelledby="dropdownMenuLink15">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                    </li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div
                                                                        class="avatar-title bg-danger-subtle text-danger rounded fs-20">
                                                                        <i class="ri-file-pdf-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a
                                                                            href="javascript:void(0);">Bank
                                                                            Management System</a></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>PDF File</td>
                                                        <td>8.89 MB</td>
                                                        <td>24 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-light btn-icon"
                                                                    id="dropdownMenuLink3" data-bs-toggle="dropdown"
                                                                    aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    aria-labelledby="dropdownMenuLink3">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                    </li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div
                                                                        class="avatar-title bg-secondary-subtle text-secondary rounded fs-20">
                                                                        <i class="ri-video-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a
                                                                            href="javascript:void(0);">Tour-video.mp4</a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>MP4 File</td>
                                                        <td>14.62 MB</td>
                                                        <td>19 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-light btn-icon"
                                                                    id="dropdownMenuLink4" data-bs-toggle="dropdown"
                                                                    aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    aria-labelledby="dropdownMenuLink4">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                    </li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div
                                                                        class="avatar-title bg-success-subtle text-success rounded fs-20">
                                                                        <i class="ri-file-excel-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a
                                                                            href="javascript:void(0);">Account-statement.xsl</a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>XSL File</td>
                                                        <td>2.38 KB</td>
                                                        <td>14 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-light btn-icon"
                                                                    id="dropdownMenuLink5" data-bs-toggle="dropdown"
                                                                    aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    aria-labelledby="dropdownMenuLink5">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                    </li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div
                                                                        class="avatar-title bg-info-subtle text-info rounded fs-20">
                                                                        <i class="ri-folder-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a
                                                                            href="javascript:void(0);">Project
                                                                            Screenshots Collection</a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Floder File</td>
                                                        <td>87.24 MB</td>
                                                        <td>08 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-light btn-icon"
                                                                    id="dropdownMenuLink6" data-bs-toggle="dropdown"
                                                                    aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    aria-labelledby="dropdownMenuLink6">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-eye-fill me-2 align-middle"></i>View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-download-2-fill me-2 align-middle"></i>Download</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div
                                                                        class="avatar-title bg-danger-subtle text-danger rounded fs-20">
                                                                        <i class="ri-image-2-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a
                                                                            href="javascript:void(0);">Velzon-logo.png</a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>PNG File</td>
                                                        <td>879 KB</td>
                                                        <td>02 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-light btn-icon"
                                                                    id="dropdownMenuLink7" data-bs-toggle="dropdown"
                                                                    aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    aria-labelledby="dropdownMenuLink7">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-eye-fill me-2 align-middle"></i>View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-download-2-fill me-2 align-middle"></i>Download</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="javascript:void(0);" class="text-success "><i
                                                    class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i>
                                                Load more </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

@section('')
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
                            style="font-size: 26px; font-weight: bold; color: #000;">{{ Auth::user()->employee->fname ?? Auth::user()->name }}
                            {{ Auth::user()->employee->mname ?? Auth::user()->mname }}
                            {{ Auth::user()->employee->lname ?? Auth::user()->lname }}</span>
                        <p class="mt-1">{{ optional(Auth::user()->employee)->license_no }}</p>
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
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#userAddTimeKeeper">Unscheduled</button>

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


                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    @if ($not_ready_sign_in)
        @include('pages.User.signin.modals.timeKeeperAddModal')
    @endif
    @include('pages.User.signin.modals.takePhotoModal')
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
                                <div class="modal-header bg-info-subtle py-2 mb-1">
                                    <h5 class="modal-title">View Event</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <div class="mb-3 d-flex">
                                        <button type="button" class="btn btn-outline-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
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
                                <div style="adding: 10px 0; margin-top: 20px;font-size: 18px;" class=" text-right">
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
                                <div style="padding: 10px 0; margin-top: 20px;font-size: 18px;" class=" text-right">
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

                                    <div style="padding: 10px 0; margin-top: 20px;font-size: 18px;"
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
                                        <div style="    padding: 10px 0; margin-top: 20px;font-size: 18px;" class=" text-right">
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
                                        <div style="padding: 10px 0; margin-top: 20px;font-size: 18px;"  class=" text-right">
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
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" onclick="changeMode('availabity')" id="home-tab"
                                                data-bs-toggle="tab" href="#home" aria-controls="home" role="tab"
                                                aria-selected="true">Unavailability</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" onclick="changeMode('leave')" id="profile-tab"
                                                data-bs-toggle="tab" href="#profile" aria-controls="profile" role="tab"
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

                                        <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header">
                                                    <button class="btn btn-primary" type="button"
                                                        onclick="openModal()">Add Leave</button>
                                                </div>

                                                <div class="card-body">
                                                    <div class="container">
                                                        <div class="table-responsive">
                                                            <table class="table  table-bordered mb-4">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
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

                                    <div style="padding: 10px 0; margin-top: 0px;font-size: 18px;" class=" text-right">
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
                                        <div class="modal-dialog">
                                            <div class="modal-content post-modal">
                                                <div class="modal-body">
                                                    <span class="btn-close float-end" onclick="closeModal()"></span>
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

                                                        <div style="padding: 10px 0;  margin-top: 10px;font-size: 18px;" class=" text-right">
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

        <!-- Compliances -->
        <div class="col-md-6">
            <div class="card">

                <div class="card-header">
                    <div>
                        <p class="card-title text-primary d-inline">Compliances</p>
                    </div>
                </div>
                <div class="card-body">

                    <div class="card">
                        <div class="row" id="table-hover-animation">
                            <div class="col-12">
                                <div class="card">
                                    <div class="container">
                                        <div class="table-responsive">
                                            <table id="exampleCompliance"
                                                class="table table-hover-animation table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Certificate Number</th>
                                                        <th>expire Date</th>
                                                        <th>Comment</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="complianceBody">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="padding: 10px 0; margin-top: 20px;font-size: 18px;" class=" text-right">
                                            <a href="/home/compliance">More...</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.User.compliance.modals.complianceModal')

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ URL::asset('app-assets/velzon/libs/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/calendars/fullcalendar.min.css')}}">

    <style>
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
    <script src="{{asset('app-assets\js\scripts\pages\app-calendar-timekeeper.js')}}"></script>

    <script>
        $(document).ready(function(){

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
            
        });
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
