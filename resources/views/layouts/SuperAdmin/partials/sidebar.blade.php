<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('super-admin.home') }}">
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
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('super-admin.home') }}"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Dashboard</span><span
                        class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('companies') }}"> <i
                        data-feather='briefcase'></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Companies</span><span
                        class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('compliance') }}"> <i
                        data-feather='cpu'></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Compliance</span><span
                        class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('company.type') }}"> <i
                        data-feather='columns'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Company
                        Type</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="/super-admin/status"> <i
                        data-feather='command'></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Statuses</span><span
                        class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

            </li>

            <!-- <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('roles.index') }}">  <i data-feather='shield-off'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Manage Roles</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>

            </li> -->


        </ul>
    </div>
</div>
