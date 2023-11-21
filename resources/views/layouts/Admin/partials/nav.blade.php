@php

    if (!Auth::user()->image) {
        Auth::user()->image = 'images/app/no-image.png';
    }
@endphp
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav bookmark-icons">

            </ul>

            <ul class="nav navbar-nav">
                <li>Company: <span
                        class="user-name font-weight-bolder text-uppercase">{{ Auth::user()->company_roles->first()->company->company_code }}</span>
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">
            @php
                $notifications = Auth::user()->notifications;

                $type = [];
                $type['App\Notifications\NewShiftNotification'] = '/home/unconfirmed/shift';
                $type['App\Notifications\UpdateShiftNotification'] = '/home/upcoming/shift';
                $type['App\Notifications\ConfirmShiftNotification'] = '/admin/home/report';
                $type['App\Notifications\NewEventNotification'] = '/user/home/upcomingevent/go';
                $type['App\Notifications\EventRequestNotification'] = '/admin/home/event/request';
                $type['App\Notifications\LicenseExpiredNotification'] = '/admin/home/employee/go';

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
            @endphp

            <li class="nav-item dropdown dropdown-notification mr-25"><a class="nav-link" onclick="markAsRead()"
                    href="javascript:void(0);" data-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span
                        class="badge badge-pill badge-danger badge-up"
                        id="notify_count">{{ Auth::user()->unreadNotifications->count() }}</span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                            <div class="badge badge-pill badge-light-primary">
                                {{ Auth::user()->unreadNotifications->count() }} New</div>
                        </div>
                    </li>
                    <li class="scrollable-container media-list" style="height:25rem !important">
                        <div class="{{ $notifications->count() ? 'd-block' : 'd-none' }}" id="notifications">
                            @foreach ($notifications as $k => $notification)
                                <a class="d-flex {{ bg($notification->data['status'] ?? '') }}"
                                    href="{{ $type[$notification->type] ?? 'javascript:void(0);' }}">
                                    <div
                                        class="media d-flex align-items-start {{ $notification->data['status'] == 'warning' ? 'bg-light-warning' : '' }}">
                                        <div class="media-left">
                                            @php
                                                $n_image = $notification->sender ? $notification->sender->image : null;
                                                if (!$n_image) {
                                                    $n_image = 'images/app/no-image.png';
                                                }
                                            @endphp
                                            <div class="avatar"><img src="{{ 'https://api.eazytask.au/' . $n_image }}"
                                                    alt="avatar" width="32" height="32"></div>
                                        </div>
                                        <div class="media-body">
                                            <p class="media-heading font-small-3"><span
                                                    class="{{ $notification->read_at ? '' : 'font-weight-bolder' }}">
                                                    {{ $notification->data['msg'] }}
                                            </p>
                                        </div>
                                        <div class="media-right">
                                            <label
                                                class="">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</label>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <a class="{{ $notifications->count() ? 'd-none' : 'd-flex' }}" href="javascript:void(0)"
                            id="no_notification">
                            <p class="p-2 m-auto" style="margin-top: 10rem !important;">No notifications</p>
                        </a>
                    </li>
                    <li class="dropdown-menu-footer"><a class="btn btn-primary btn-block" href="javascript:void(0)"
                            onclick="deleteAllNotifications()">Clear all notifications</a></li>

                </ul>
            </li>
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span
                            class="user-name font-weight-bolder">{{ Auth::user()->name }} {{ Auth::user()->mname }}
                            {{ Auth::user()->lname }}</span>
                        <span class="user-status">
                            @if (auth()->user()->company_roles->contains('role', 2))
                                Admin
                            @elseif(auth()->user()->company_roles->contains('role', 4))
                                Supervisor
                            @elseif(auth()->user()->company_roles->contains('role', 5))
                                Operator
                            @else
                                User
                            @endif
                        </span>
                    </div>
                    <span class="avatar"><img class="round"
                            src="{{ 'https://api.eazytask.au/' . Auth::user()->image }}" alt="avatar" height="40"
                            width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <!-- <a class="dropdown-item" href="/admin/company/profile-settings/{{ Auth::user()->id }}"><i class="mr-50" data-feather="user"></i> Profile</a> -->

                    @if (auth()->user()->company_roles->contains('role', 3))
                        <a class="dropdown-item" href="/home/time/off"><i class="mr-50" data-feather="clock"></i> Time
                            off</a>
                    @endif

                    <!-- <a class="dropdown-item" href="/admin/home/activity/log"><i class="mr-50" data-feather="activity"></i> Activity Log</a> -->

                    <div class="dropdown-divider"></div><a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        <i class="mr-50" data-feather="power"></i> Logout
                    </a>


                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
    let unRead = "{{ Auth::user()->unreadNotifications->count() }}"

    function markAsRead() {
        $("#notify_count").html('0')
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
                    $("#no_notification").attr('style', 'display:flex !important')
                    toastr['success']('notifications successfully cleared.');
                }
            },
        });
    }
</script>
