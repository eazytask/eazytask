@yield('css')
<!-- Layout config Js -->
<script src="{{ URL::asset('app-assets/velzon/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('app-assets/velzon/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('app-assets/velzon/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('app-assets/velzon/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ URL::asset('app-assets/velzon/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/ext-component-toastr.css')}}">
{{-- @yield('css') --}}