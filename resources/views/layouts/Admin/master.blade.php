@include('layouts.Admin.partials.header')

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

<div class="d-print-none">
    <!-- BEGIN: Header-->
  @include('layouts.Admin.partials.nav')

    <!-- END: Header-->
    <!-- @include('layouts.Admin.partials.search') -->


    <!-- BEGIN: Main Menu-->
  @include('layouts.Admin.partials.sidebar')
    <!-- END: Main Menu-->
    </div>
    <!-- BEGIN: Content-->
    <div class="app-content content d-block d-print-none">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
    @yield('admincontent')

  </div>
  </div>
  </div>

    <!-- END: Content-->
    <div>
    @yield('pdf_generator')
    </div>
  <div class="d-print-none">
      
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    @include('layouts.Admin.partials.footer')
    <!-- END: Footer-->
    </div>

    @include('layouts.Admin.partials.scripts')
</body>
<!-- END: Body-->

</html>
