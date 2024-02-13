@php
    $isRoleAdmin = auth()->user()->company_roles->contains('role', 2);
    $isRoleSupervisor = auth()->user()->company_roles->contains('role', 4);
    $isRoleOperation = auth()->user()->company_roles->contains('role', 5);
    $isRoleManager = auth()->user()->company_roles->contains('role', 6);
    $isRoleAccount = auth()->user()->company_roles->contains('role', 7);

    $isRequestAdminDashboard = request()->is('admin/home/dashboard');
    $isRequestUserDashboard = request()->is('user/home');
    $isRequestSupervisorDashboard = request()->is('supervisor/home'); 
    $isRequestSchedule = request()->is('admin/home/schedule/status');
    $isRequestRosterEntry = request()->is('admin/home/report');
    $isRequestEventCalendar = request()->is('admin/home/event/request');
    $isRequestNewTimesheet = request()->is('admin/home/new/timekeeper/*');
    $isRequestViewTimesheet = request()->is('admin/home/view/schedule/*');
    $isRequestTimeKeeperApprove = request()->is('admin/home/timekeeper/approve/*');
    $isRequestEmployee = request()->is('admin/home/employee/*');
    $isRequestMyAvailability = request()->is('admin/home/myavailability/*');
    $isRequestLeave = request()->is('admin/home/leave/*');
    $isRequestInductedSite = request()->is('admin/home/inducted/site/*');
    $isRequestClient = request()->is('admin/home/client/*');
    $isRequestProject = request()->is('admin/home/project/*');
    $isRequestRevenue = request()->is('admin/home/revenue/*');
    $isRequestPaymentAdd = request()->is('admin/home/payment/add');
    $isRequestPaymentList = request()->is('admin/home/payment/list');
    $isRequestEventReport = request()->is('admin/home/event-report');
    $isRequestSignInStatus = request()->is('admin/home/sign/in/status');
    $isRequestCustomReport = request()->is('admin/home/all/report');
    $isRequestMessages = request()->is('home/messages');
    $isRequestJobType = request()->is('admin/home/job/type');
    $isRequestRosterStatus = request()->is('admin/home/roster/status');
    $isRequestActivityLog = request()->is('admin/home/activity/log');
    
    $companyId = Auth::user()->company_roles->first()->company->id;
    $companyCode = auth()->user()->user_roles->unique('company_code');
@endphp

<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('app-assets/images/ico/favicon.ico') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                {{-- <img src="{{ URL::asset('app-assets/images/ico/favicon.ico') }}" alt="" height="17"> --}}
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>

            <ul class="navbar-nav" id="navbar-nav">
                <!-- Admin Menus -->
                @if($isRoleAdmin || $isRoleOperation || $isRoleManager || $isRoleAccount)
                    <li class="menu-title">
                        @if ($isRoleAdmin)
                            <span>Admin</span>
                        @elseif($isRoleOperation)
                            <span>Operation</span>
                        @elseif($isRoleManager)
                            <span>Manager</span>
                        @elseif($isRoleAccount);
                            <span>Account</span>
                        @endif    
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestAdminDashboard ? 'active' : '' }}" href="/admin/home/dashboard">
                            <i data-feather="home"></i>
                            <span>
                                @if ($isRoleAdmin)
                                    Admin
                                @elseif($isRoleOperation)
                                    Operation
                                @elseif($isRoleManager)
                                    Manager
                                @elseif($isRoleAccount)
                                    Account
                                @endif

                                Dashboard    
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestSchedule ? 'active' : '' }}" href="/admin/home/schedule/status">
                            <i data-feather="alert-circle"></i>
                            <span>Schedule</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestRosterEntry ? 'active' : '' }}" href="/admin/home/report">
                            <i data-feather="file-text"></i>
                            <span>Roster Entry</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestEventCalendar ? 'active' : '' }}" href="/admin/home/event/request">
                            <i data-feather="calendar"></i>
                            <span>Event Calendar</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestNewTimesheet || $isRequestViewTimesheet || $isRequestViewTimesheet ? 'active' : '' }}" 
                            href="#timesheet" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="timesheet"
                        >
                            <i data-feather='clock'></i> 
                            <span>Timesheet</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestNewTimesheet || $isRequestViewTimesheet || $isRequestViewTimesheet ? 'show' : '' }}" id="timesheet">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/new/timekeeper/{{ $companyId }}" class="nav-link {{ $isRequestNewTimesheet ? 'active' : '' }}">
                                        Add Timesheet
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/view/schedule/{{ $companyId }}" class="nav-link {{ $isRequestViewTimesheet || $isRequestViewTimesheet ? 'active' : '' }}">
                                        View Timesheet
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a  class="nav-link menu-link {{ $isRequestEmployee || $isRequestMyAvailability || $isRequestInductedSite ? 'active' : '' }}" 
                            href="/admin/home/employee/{{ $companyId }}" >
                            <i data-feather='user-check'></i> 
                            <span>Employee</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestClient || $isRequestProject || $isRequestRevenue ? 'active' : '' }}" 
                            href="#client" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="client"
                        >
                            <i data-feather='users'></i> 
                            <span>Client</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestClient || $isRequestProject || $isRequestRevenue ? 'show' : '' }}" id="client">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/client/{{ $companyId }}" class="nav-link {{ $isRequestClient ? 'active' : '' }}">
                                        Profile
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/project/{{ $companyId }}" class="nav-link {{ $isRequestProject ? 'active' : '' }}">
                                        Venue / Site
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/revenue/{{ $companyId }}" class="nav-link {{ $isRequestRevenue ? 'active' : '' }}">
                                        Invoice
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestPaymentAdd || $isRequestPaymentList ? 'active' : '' }}" 
                            href="#payment" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="payment"
                        >
                            <i data-feather='dollar-sign'></i> 
                            <span>Payment</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestPaymentAdd || $isRequestPaymentList ? 'show' : '' }}" id="payment">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/payment/add" class="nav-link {{ $isRequestPaymentAdd ? 'active' : '' }}">
                                        Add
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/payment/list" class="nav-link {{ $isRequestPaymentList ? 'active' : '' }}">
                                        List
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestEventReport || $isRequestSignInStatus || $isRequestCustomReport  ? 'active' : '' }}" 
                            href="#report" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="report"
                        >
                            <i data-feather='file-plus'></i> 
                            <span>Report</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestEventReport || $isRequestSignInStatus || $isRequestCustomReport ? 'show' : '' }}" id="report">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/event-report" class="nav-link {{ $isRequestEventReport ? 'active' : '' }}">
                                        Event Report
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/sign/in/status" class="nav-link {{ $isRequestSignInStatus ? 'active' : '' }}">
                                        Sign In Report
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/all/report" class="nav-link {{ $isRequestCustomReport ? 'active' : '' }}">
                                        Custom Report
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestMessages ? 'active' : '' }}" href="/home/messages">
                            <i data-feather="message-square"></i>
                            <span>Messages</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestJobType || $isRequestRosterStatus || $isRequestActivityLog  ? 'active' : '' }}" 
                            href="#settings" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="settings"
                        >
                            <i data-feather='users'></i> 
                            <span>Settings</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestJobType || $isRequestRosterStatus || $isRequestActivityLog ? 'show' : '' }}" id="settings">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/job/type" class="nav-link {{ $isRequestJobType ? 'active' : '' }}">
                                        Job Types
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/roster/status" class="nav-link {{ $isRequestRosterStatus ? 'active' : '' }}">
                                        Roster Status
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/activity/log" class="nav-link {{ $isRequestActivityLog ? 'active' : '' }}">
                                        Activity Log
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- Admin Menus End -->

                <!-- Supervisor Menus -->
                @if ($isRoleSupervisor && !$isRoleAdmin && !$isRoleOperation)
                    <li class="menu-title">
                        <span>Supervisor</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestSupervisorDashboard ? 'active' : '' }}" href="/supervisor/home">
                            <i data-feather="home"></i>
                            <span>Supervisor Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestSchedule ? 'active' : '' }}" href="/admin/home/schedule/status">
                            <i data-feather="alert-circle"></i>
                            <span>Schedule</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestRosterEntry ? 'active' : '' }}" href="/admin/home/report">
                            <i data-feather="file-text"></i>
                            <span>Roster Entry</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestEventCalendar ? 'active' : '' }}" href="/admin/home/event/request">
                            <i data-feather="calendar"></i>
                            <span>Event Calendar</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestNewTimesheet || $isRequestViewTimesheet || $isRequestViewTimesheet ? 'active' : '' }}" 
                            href="#timesheet" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="timesheet"
                        >
                            <i data-feather='clock'></i> 
                            <span>Timesheet</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestNewTimesheet || $isRequestViewTimesheet || $isRequestViewTimesheet ? 'show' : '' }}" id="timesheet">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/new/timekeeper/{{ $companyId }}" class="nav-link {{ $isRequestNewTimesheet ? 'active' : '' }}">
                                        Add Timesheet
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/view/schedule/{{ $companyId }}" class="nav-link {{ $isRequestViewTimesheet || $isRequestViewTimesheet ? 'active' : '' }}">
                                        View Timesheet
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestEmployee || $isRequestMyAvailability || $isRequestLeave || $isRequestInductedSite ? 'active' : '' }}" 
                            href="#employee" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="employee"
                        >
                            <i data-feather='user-check'></i> 
                            <span>Employee</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestEmployee || $isRequestMyAvailability || $isRequestLeave || $isRequestInductedSite ? 'show' : '' }}" id="employee">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/employee/{{ $companyId }}" class="nav-link {{ $isRequestEmployee ? 'active' : '' }}">
                                        Profile
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a 
                                        href="#timeOff" 
                                        class="nav-link" 
                                        data-bs-toggle="collapse" 
                                        role="button" 
                                        aria-expanded="false" 
                                        aria-controls="timeOff"
                                    >
                                        Time Off
                                    </a>
        
                                    <div class="collapse menu-dropdown {{ $isRequestMyAvailability || $isRequestLeave ? 'show' : '' }}" id="timeOff">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="/admin/home/myavailability/go" class="nav-link {{ $isRequestMyAvailability ? 'active' : '' }}">
                                                    Unavailability
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="/admin/home/leave/go" class="nav-link {{ $isRequestLeave ? 'active' : '' }}">
                                                    Leave
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/inducted/site/{{ $companyId }}" class="nav-link {{ $isRequestInductedSite ? 'active' : '' }}">
                                        Inducted Site
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestClient || $isRequestProject || $isRequestRevenue ? 'active' : '' }}" 
                            href="#client" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="client"
                        >
                            <i data-feather='users'></i> 
                            <span>Client</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestClient || $isRequestProject || $isRequestRevenue ? 'show' : '' }}" id="client">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/client/{{ $companyId }}" class="nav-link {{ $isRequestClient ? 'active' : '' }}">
                                        Profile
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/project/{{ $companyId }}" class="nav-link {{ $isRequestProject ? 'active' : '' }}">
                                        Venue / Site
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/revenue/{{ $companyId }}" class="nav-link {{ $isRequestRevenue ? 'active' : '' }}">
                                        Invoice
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestPaymentAdd || $isRequestPaymentList ? 'active' : '' }}" 
                            href="#payment" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="payment"
                        >
                            <i data-feather='dollar-sign'></i> 
                            <span>Payment</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestPaymentAdd || $isRequestPaymentList ? 'show' : '' }}" id="payment">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/payment/add" class="nav-link {{ $isRequestPaymentAdd ? 'active' : '' }}">
                                        Add
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/payment/list" class="nav-link {{ $isRequestPaymentList ? 'active' : '' }}">
                                        List
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestEventReport || $isRequestSignInStatus || $isRequestCustomReport  ? 'active' : '' }}" 
                            href="#report" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="report"
                        >
                            <i data-feather='file-plus'></i> 
                            <span>Report</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestEventReport || $isRequestSignInStatus || $isRequestCustomReport ? 'show' : '' }}" id="report">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/sign/in/status" class="nav-link {{ $isRequestSignInStatus ? 'active' : '' }}">
                                        Sign In Report
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/all/report" class="nav-link {{ $isRequestCustomReport ? 'active' : '' }}">
                                        Custom Report
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRequestMessages ? 'active' : '' }}" href="/home/messages">
                            <i data-feather="message-square"></i>
                            <span>Messages</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link {{ $isRequestJobType || $isRequestRosterStatus || $isRequestActivityLog  ? 'active' : '' }}" 
                            href="#settings" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="settings"
                        >
                            <i data-feather='users'></i> 
                            <span>Settings</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRequestJobType || $isRequestRosterStatus || $isRequestActivityLog ? 'show' : '' }}" id="settings">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/admin/home/job/type" class="nav-link {{ $isRequestJobType ? 'active' : '' }}">
                                        Job Types
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/roster/status" class="nav-link {{ $isRequestRosterStatus ? 'active' : '' }}">
                                        Roster Status
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/admin/home/activity/log" class="nav-link {{ $isRequestActivityLog ? 'active' : '' }}">
                                        Activity Log
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- Supervisor Menus End -->

                <!-- All Employees Menu -->
                <li class="menu-title">
                    <span>Employee</span>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ $isRequestUserDashboard ? 'active' : '' }}" href="/user/home">
                        <i data-feather="home"></i>
                        <span>User Dashboard</span>
                    </a>
                </li>
                <!-- All Employees Menu End -->

                @if ($companyCode->count() - 1) 
                    <li class="menu-title">
                        <span>Switch Company</span>
                    </li>

                    <li class="nav-item">
                        <a 
                            class="nav-link menu-link" 
                            href="#switchcompany" 
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="switchcompany"
                        >
                            <i data-feather='refresh-cw'></i> 
                            <span>Switch Company</span>
                        </a>

                        <div class="collapse menu-dropdown" id="switchcompany">
                            <ul class="nav nav-sm flex-column">
                                @foreach ($companyCode as $c)
                                    @if ($c->status == 1 && $c->company->status == 1 
                                        && \Carbon\Carbon::parse($c->company->expire_date) > \Carbon\Carbon::now()->toDateString())
                                        <li class="nav-item">
                                            <a href="/home/switch/company/{{ $c->company_code }}" class="nav-link {{ $c->company_code == Auth::user()->company_roles->first()->company->id ? 'text-danger disabled' : '' }}">
                                                {{ $c->company->company_code }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
