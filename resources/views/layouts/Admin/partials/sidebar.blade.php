<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand"
                    href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">
                    <span class="brand-logo">
                        <!-- <img src="{{ asset('images/app/logo.png') }}" alt="" style="margin-top:-17px"> -->
                    </span>
                    <!--<h2 class="brand-text">Roster</h2>-->
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main pb-2" id="main-menu-navigation" data-menu="menu-navigation">
            @if (auth()->user()->company_roles->contains('role', 2))
                <!----------------------------------------------- all admin menus --------------------------------------------------->
                <li class="nav-item {{ request()->is('admin/home/dashboard') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/admin/home/dashboard"><i data-feather="home"></i><span
                            class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>

                <li class="nav-item {{ request()->is('admin/home/schedule/status') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/admin/home/schedule/status"><i
                            data-feather='alert-circle'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Live operation</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>


                <li class="nav-item {{ request()->is('admin/home/report') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/admin/home/report"><i
                            data-feather='file-text'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Roster Entry</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>


                </li>
                <li class="nav-item {{ request()->is('admin/home/event/request') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/admin/home/event/request"><i
                            data-feather='calendar'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Event
                            Calendar</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>

                <li class="nav-item"><a class="d-flex align-items-center" href="#"><i
                            data-feather='clock'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Timesheet</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    <ul>
                        <li class="nav-item {{ request()->is('admin/home/new/timekeeper/*') ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="/admin/home/new/timekeeper/{{ Auth::user()->company_roles->first()->company->id }}"><i
                                    data-feather='users'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Add Timesheet</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>
                        <li
                            class="nav-item {{ request()->is('admin/home/view/schedule/*') || request()->is('admin/home/timekeeper/approve/*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center"
                                href="/admin/home/view/schedule/{{ Auth::user()->company_roles->first()->company->id }}"><i
                                    data-feather='eye'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">View Timesheet</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        </li>
                    </ul>

                </li>


                <li class="nav-item {{ request()->is('admin/home/employee/*') ? 'active' : '' }}"><a
                        class="d-flex align-items-center"
                        href="/admin/home/employee/{{ Auth::user()->company_roles->first()->company->id }}"><i
                            data-feather='user-check'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Employee</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    <ul>
                        <li class="nav-item {{ request()->is('admin/home/employee/*') ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="/admin/home/employee/{{ Auth::user()->company_roles->first()->company->id }}"><i
                                    data-feather='users'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Profile</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        </li>
                        <!-- <li class="nav-item {{ request()->is('admin/home/myavailability/*') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/admin/home/myavailability/{{ Auth::user()->company_roles->first()->company->id }}"><i data-feather='battery-charging'></i><span class="menu-title text-truncate" data-i18n="Dashboards">
                                Availavility</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    </li> -->

                        <li><a class="d-flex align-items-center" href="#"><i
                                    data-feather="battery-charging"></i><span class="menu-item text-truncate">Time
                                    off</span></a>
                            <ul class="menu-content">
                                <li class="{{ request()->is('admin/home/myavailability/*') ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="/admin/home/myavailability/go"><span
                                            class="menu-item text-truncate"
                                            data-i18n="Third Level">Unavailavility</span></a>
                                </li>
                                <li class="{{ request()->is('admin/home/leave/*') ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="/admin/home/leave/go"><span
                                            class="menu-item text-truncate" data-i18n="Third Level">Leave</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('admin/home/inducted/site/*') ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="/admin/home/inducted/site/{{ Auth::user()->company_roles->first()->company->id }}"><i
                                    data-feather='alert-octagon'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Inducted Site</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        </li>
                    </ul>

                </li>

                <li class="nav-item"><a class="d-flex align-items-center" href="#"><i
                            data-feather='users'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Client</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    <ul>
                        <li class="nav-item {{ request()->is('admin/home/client/*') ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="/admin/home/client/{{ Auth::user()->company_roles->first()->company->id }}"><i
                                    data-feather='users'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Profile</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

                        </li>
                        <li class="nav-item {{ request()->is('admin/home/project/*') ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="/admin/home/project/{{ Auth::user()->company_roles->first()->company->id }}"><i
                                    data-feather='briefcase'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Venue/Site</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>
                        <li class="nav-item {{ request()->is('admin/home/revenue/*') ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="/admin/home/revenue/{{ Auth::user()->company_roles->first()->company->id }}"><i
                                    data-feather='briefcase'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Invoice</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>
                    </ul>

                </li>

                <li class="nav-item {{ request()->is('admin/home/contractor/*') ? 'active' : '' }}"><a
                        class="d-flex align-items-center"
                        href="/admin/home/contractor/{{ Auth::user()->company_roles->first()->company->id }}"><i
                            data-feather="users"></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Contractor</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>

                <li class="nav-item"><a class="d-flex align-items-center"
                        href="/admin/home/project/{{ Auth::user()->company_roles->first()->company->id }}"><i
                            data-feather='dollar-sign'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Payment</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    <ul>
                        <li
                            class="nav-item {{ request()->is('admin/home/payment/add') || request()->is('admin/home/payment/search') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="/admin/home/payment/add"><i
                                    data-feather='plus-circle'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Add</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        <li class="nav-item {{ request()->is('admin/home/payment/list') ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="/admin/home/payment/list"><i
                                    data-feather='list'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">List</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

                    </ul>
                </li>

                <li class="nav-item"><a class="d-flex align-items-center" href="/admin/home/project"><i
                            data-feather='file-plus'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Report</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    <ul>
                        <li class="nav-item {{ request()->is('admin/home/sign/in/status') ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="/admin/home/sign/in/status"><i
                                    data-feather='alert-triangle'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Sign In Report</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>

                        <li class="nav-item {{ request()->is('admin/home/all/report') ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="/admin/home/all/report"><i
                                    data-feather='filter'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Custom Report</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('home/messages') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/home/messages"><i
                            data-feather="message-square"></i><span class="menu-title text-truncate"
                            data-i18n="Messages">Messages</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>

                <li class="nav-item mb-2"><a class="d-flex align-items-center" href="#"><i
                            data-feather='users'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Settings</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    <ul>
                        <li class="nav-item {{ request()->is('admin/home/job/type') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="/admin/home/job/type">
                                <i data-feather='columns'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Job Types</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('admin/home/roster/status') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="/admin/home/roster/status">
                                <i data-feather='command'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Roster status</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('admin/home/activity/log') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="/admin/home/activity/log">
                                <i data-feather='activity'></i>
                                <span class="menu-title text-truncate" data-i18n="Dashboards">Activity Log</span>
                                <span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        </li>
                    </ul>

                </li>

                <li class="nav-item {{ request()->is('admin/kisok') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="/admin/kisok" target="blank">
                        <i data-feather='target'></i>
                        <span class="menu-title text-truncate" data-i18n="Dashboards">Kiosk</span>
                        <span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>
            @endif

            @if (auth()->user()->company_roles->contains('role', 4) &&
                    !auth()->user()->company_roles->contains('role', 2))
                <!---------------------------------------- all supervisor menus ------------------------------------>

                <li class="navigation-header"><span data-i18n="Apps &amp; Pages">Supervisor</span><i
                        data-feather="more-horizontal"></i>
                </li>
                <li class="nav-ite {{ request()->is('supervisor/home') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/supervisor/home"><i data-feather="home"></i><span
                            class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>

                <li class="nav-item {{ request()->is('supervisor/home/roster/calender') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/supervisor/home/roster/calender"><i
                            data-feather="calendar"></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Roster Calendar</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

                </li>

                <li class="nav-item"><a class="d-flex align-items-center" href="/admin/home/project"><i
                            data-feather='dollar-sign'></i><span class="menu-title text-truncate"
                            data-i18n="Dashboards">Payment</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                    <ul>
                        <li class="nav-item {{ request()->is('supervisor/home/payment/sup') ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="/supervisor/home/payment/sup"><i
                                    data-feather='plus-circle'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">Add</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        <li class="nav-item {{ request()->is('supervisor/home/payslip/list') ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="/supervisor/home/payslip/list"><i
                                    data-feather='list'></i><span class="menu-title text-truncate"
                                    data-i18n="Dashboards">List</span><span
                                    class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

                    </ul>
                </li>
                <!-- <li class="nav-item mb-2"><a class="d-flex align-items-center" href="/admin/home/project"><i data-feather='file-plus'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Report</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul>
                    <li class="nav-item {{ request()->is('supervisor/home/date/wise/report') }} "><a class="d-flex align-items-center" href="/supervisor/home/date/wise/report"><i data-feather='file'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Quick Report</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>
                    <li class="nav-item {{ request()->is('supervisor/home/all/report') }} "><a class="d-flex align-items-center" href="/supervisor/home/all/report"><i data-feather='filter'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Custom Report</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a></li>

                </ul>
            </li> -->
            @endif

            @if (auth()->user()->company_roles->contains('role', 3))
                <!---------------------------------------- all employee menus ------------------------------------>

                <li class="navigation-header"><span data-i18n="Apps &amp; Pages">Employee</span><i
                        data-feather="more-horizontal"></i>
                    <!-- <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="menu"></i><span class="menu-title text-truncate" data-i18n="Menu Levels">Employee</span></a>
                <ul class="menu-content">

            </li> -->

                <li class="nav-item {{ request()->is('user/home') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/user/home"><i data-feather="home"></i><span
                            class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>

                <li class="nav-item {{ request()->is('home/messages') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/home/messages"><i
                            data-feather="message-square"></i><span class="menu-title text-truncate"
                            data-i18n="Messages">Messages</span><span
                            class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                </li>
                <li class="nav-item {{ request()->is('home/compliance') ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="/home/compliance">
                        <i data-feather="folder-plus"></i>
                        <span class="menu-title text-truncate" data-i18n="Dashboards">
                            Compliance</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                    </a>
                </li>


                <!-- <li class="nav-item {{ request()->is('user/roster/schedule') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/user/roster/schedule">
                    <i data-feather="briefcase"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Schedule</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
            </li>

            <li class="nav-item {{ request()->is('home/sign/in') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/sign/in">
                    <i data-feather="briefcase"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">SignIn</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
            </li>

            <li class="nav-item {{ request()->is('home/calender') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/calender">
                    <i data-feather="calendar"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Calendar</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
            </li>

            <li class="nav-item {{ request()->is('home/timesheet') || request()->is('home/timesheet/*') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/timesheet">
                    <i data-feather="clock"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">My Timesheet</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
            </li>

            <li class="nav-item {{ request()->is('user/home/upcomingevent/*') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/user/home/upcomingevent/{{ Auth::user()->employee->company }}">
                    <i data-feather='circle'></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Upcomming Event</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
            </li>
            <li class="nav-item {{ request()->is('home/upcoming/shift') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/upcoming/shift">

                    <i data-feather="check-circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">All
                        upcoming shift</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('home/unconfirmed/shift') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/unconfirmed/shift">
                    <i data-feather="folder-plus"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">
                        Unconfirmed shift</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                </a>
            </li>

            <li><a class="d-flex align-items-center" href="#">
                    <i data-feather="file-plus"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Report</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul>

                    <li class="nav-item {{ request()->is('home/past/shift') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/past/shift">

                            <span class="menu-title text-truncate" data-i18n="Dashboards">
                                All past shift</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('home/payment/report') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/payment/report">

                            <span class="menu-title text-truncate" data-i18n="Dashboards">Payment Report</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('home/all/report') ? 'active' : '' }}"><a class="d-flex align-items-center" href="/home/all/report">

                            <span class="menu-title text-truncate" data-i18n="Dashboards">Roster Report</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span>
                        </a>
                    </li>
                </ul>
            </li> -->
            @endif

            <li class="nav-item {{ auth()->user()->user_roles->unique('company_code')->count() - 1? '': 'hidden' }}">
                <a class="d-flex align-items-center" href="/admin/home/project"><i
                        data-feather='refresh-cw'></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Switch Company</span><span
                        class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul>

                    @php
                        $comp = auth()
                            ->user()
                            ->user_roles->unique('company_code');
                    @endphp

                    @foreach ($comp as $company)
                        @if (
                            $company->status == 1 &&
                                $company->company->status == 1 &&
                                \Carbon\Carbon::parse($company->company->expire_date) > \Carbon\Carbon::now()->toDateString())
                            <li
                                class="nav-item {{ $company->company_code == Auth::user()->company_roles->first()->company->id ? 'bg-light-danger disabled' : '' }}">
                                <a class="d-flex align-items-center"
                                    href="/home/switch/company/{{ $company->company_code }}"><i
                                        data-feather='circle'></i><span class="menu-title text-truncate"
                                        data-i18n="Dashboards"> {{ $company->company->company_code }}</span><span
                                        class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                            </li>
                        @endif
                    @endforeach

                </ul>
            </li>
        </ul>
    </div>
</div>
