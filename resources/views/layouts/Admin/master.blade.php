<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Easytask/roster, a personalized and simple way to get more done. All projects and lists customized to you.">
    <meta name="keywords" content="Easytask.au Scheduler roster">
    <meta name="author" content="Ahsan">
    <title>Online Scheduler </title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
    <meta name="site_url" content="{{asset('/')}}">
    @include('layouts.Admin.partials.head-css')
</head>

@section('body')
    @include('layouts.Admin.partials.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.Admin.partials.nav')
        @include('layouts.Admin.partials.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('admin_page_content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            {{-- @include('layouts.Admin.partials.footer') --}}
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    
    <div>
        @yield('pdf_generator')
    </div>

    <!-- JAVASCRIPT -->
    {{-- @include('layouts.Admin.partials.scripts') --}}
    @include('layouts.Admin.partials.velzon-scripts')
</body>


</html>
