@php
    if (!Auth::user()->image) {
        Auth::user()->image = 'images/app/no-image.png';
    }
@endphp

<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <span class="px-3 header-item">
                    <span class="user-name-sub-text">Company:</span>
                    <span class="user-name px-2 fw-medium text-uppercase">
                        {{ Auth::user()->company_roles->first()->company->company_code }}
                    </span>
                </span>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item" onclick="markAsRead()">
                    @php
                        $notifications = Auth::user()->notifications;

                        $type = [];
                        $type['App\Notifications\NewShiftNotification'] = '/home/unconfirmed/shift';
                        $type['App\Notifications\UpdateShiftNotification'] = '/home/upcoming/shift';
                        $type['App\Notifications\ConfirmShiftNotification'] = '/admin/home/report';
                        $type['App\Notifications\NewEventNotification'] = '/user/home/upcomingevent/go';
                        $type['App\Notifications\EventRequestNotification'] = '/admin/home/event/request';
                        $type['App\Notifications\LicenseExpiredNotification'] = '/admin/home/employee/go';

                        if (!function_exists('bg')) {
                            function bg($status)
                            {
                                if ($status == 'success') {
                                    return 'bg-light-success';
                                } elseif ($status == 'danger') {
                                    return 'bg-light-danger';
                                } elseif ($status == 'warning') {
                                    return 'bg-light-warning';
                                } else {
                                    return '';
                                }
                            }
                        }
                    @endphp

                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-bell fs-22'></i>
                        <span id="notify_count" class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        <span id="new_notify_count" class="badge bg-light-subtle text-body fs-13"> {{ Auth::user()->unreadNotifications->count() }} New</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content position-relative" id="notificationItemsTabContent">
                            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                <div data-simplebar style="max-height: 300px;" class="pe-2 bg">
                                    <div class="{{ $notifications->count() ? 'd-block' : 'd-none' }}" id="notifications">
                                        @foreach($notifications as $key => $notification)
                                            <div class="text-reset notification-item d-block dropdown-item position-relative {{ bg($notification->data['status'] ?? '') }}">
                                                <div class="d-flex">
                                                    @php
                                                        $n_image = $notification->sender ? $notification->sender->image : null;
                                                        if (!$n_image) {
                                                            $n_image = 'images/app/no-image.png';
                                                        }
                                                    @endphp
                    
                                                    <img class="me-3 rounded-circle avatar-xs" src="{{ 'https://api.eazytask.au/' . $n_image }}" alt="user-pic">

                                                    <div class="flex-grow-1 ">
                                                        <a href="{{ $type[$notification->type] ?? 'javascript:void(0);' }}" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">
                                                                {{ $notification->data['type'] }}
                                                            </h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">
                                                                {{ $notification->data['msg'] }}
                                                            </p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span>
                                                                <i class="mdi mdi-clock-outline"></i> 
                                                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                    </div>

                                    @if ($notifications->count() > 0)
                                        <div class="my-3 text-center">
                                            <button type="button" class="btn btn-soft-success waves-effect waves-light" onclick="deleteAllNotifications()">
                                                Clear All Notifications
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="@if (Auth::user()->image != ''){{ 'https://api.eazytask.au/' . Auth::user()->image }}@else{{ URL::asset('app-assets/velzon/images/users/avatar-1.jpg') }}@endif" alt="Header Avatar">

                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{Auth::user()->name}}</span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
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
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">
                            Welcome <span>{{ Auth::user()->name }} {{ Auth::user()->mname }} {{ Auth::user()->lname }}!</span>
                        </h6>

                        <a class="dropdown-item" href="/admin/company/profile-settings/{{ Auth::user()->id }}"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> Profile</a>

                        @if (auth()->user()->company_roles->contains('role', 3) || auth()->user()->company_roles->contains('role', 7))
                            <a class="dropdown-item" href="/home/time/off"><i class="mdi mdi-clock-time-eight-outline text-muted fs-16 align-middle me-1"></i> Time off</a>
                        @endif
                        
                        <a class="dropdown-item " href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span key="t-logout">@lang('translation.logout')</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    let unRead = "{{ Auth::user()->unreadNotifications->count() }}"

    function markAsRead() {
        $("#notify_count").html('0')
        $("#new_notify_count").html('0 New')

        if (unRead > 0) {
            $.ajax({
                url: '/notification/mark/as/read',
                type: 'get',
                dataType: 'json',
            });
        }

        unRead = 0
    }

    function deleteAllNotifications() {
        $.ajax({
            url: '/notification/delete',
            type: 'get',
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $('#notifications').attr('style', 'display:none !important')
                    toastr['success']('notifications successfully cleared.')
                }
            },
        });
    }
</script>
