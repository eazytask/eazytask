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
{{-- date picker --}}
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/pickers/form-pickadate.css')}}">
{{-- @yield('css') --}}

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/select/select2.min.css')}}">

<style>
    .form-control[readonly] {
        background-color: #efefef;
        opacity: 1;
    }
    .select2-selection__clear{
        font-size: 1.4rem !important;
        color: #7367f0 !important;
    }
    .collapse-icon [data-bs-toggle='collapse']:after {
        display: inline;
        content: '';        
        position: absolute;
        top: 1em;
        left: 10px;
        padding: 12px;
        background-image:url("{{asset('images/app/minus.svg')}}");
    }
    .collapse-icon [aria-expanded='false']:after {
        display: inline;
        content: '';
        top: 35%;
        background-image: url("http://localhost:8000/images/app/plus.svg");
    }
    .collapse-shadow .card.open {
        border: 2px solid #7367f0 !important;
        /* padding: 0.25rem !important; */
    }

    .roster {
        cursor: pointer;
    }

    .fc-list-event-title {
        color: #000 !important;
        font-weight: bolder !important;
    }

    #myTable th {
        padding: 0.75rem !important;
        text-align: center;
    }

    #myTable td {
        padding: 0.75rem !important;
    }

    /* @media print { */
    /* body {
            -webkit-print-color-adjust: exact;
        } */
    body {
        font-family: Helvetica, Arial, serif;
    }

    .border-t {
        border-top: 2px solid #7367f0 !important;
    }

    .border-b {
        border-bottom: 2px solid #7367f0 !important;
    }

    .border-l {
        border-left: 2px solid #7367f0 !important;
    }

    .border-r {
        border-right: 2px solid #7367f0 !important;
    }

    .bg-primary {
        background: #7367f0 !important;
        font-weight: bolder !important;
    }

    /* } */

    .picker {
        width: 373px !important;
        margin-top: -11px !important;
    }

    /* #myTable th{
        width: 100px !important;
    } */
    .myTable td {
        /* width: 100px !important; */
        padding: 3px !important;
        font-size: 0.8rem !important;
    }

    .myTable th {
        /* width: 100px !important; */
        padding: 0.90rem;
    }

    .table-striped th,
    .table-striped td {
        padding: 0.30rem 1.4rem;
        font-size: 0.9rem !important;
        text-align: center !important;
    }

    .no-arrow {
        -moz-appearance: textfield;
    }

    .no-arrow::-webkit-inner-spin-button {
        display: none;
    }

    .no-arrow::-webkit-outer-spin-button,
    .no-arrow::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }


    .desktop-view {
        display: inline-block;
    }

    .mobile-view {
        display: none;
    }
    .fc-event-main-frame {
        /* height: 35px; */
        text-align: center;
        white-space: pre-line;
    }
    @media (max-width: 768px) {
        .collapse-icon [data-toggle='collapse']:after {
            margin-left: -12px;
        }

        .total-display {
            margin-bottom: 0 !important;
            text-align: center;
            margin: 0 auto;
        }

        .kiosk-div {
            padding: 16px !important;
        }
    }
    @media (max-width: 768px) {
        .desktop-view {
            display: none !important;
        }

        .mobile-view {
            display: inline-block !important;
        }

        /* full calendar */
        .fc-event-main-frame {
            text-align: center;
            white-space: inherit !important;
        }
    }
    
</style>