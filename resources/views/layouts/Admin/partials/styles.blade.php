<link rel="apple-touch-icon" href="{{asset('app-assets/images/ico/apple-icon-120.png')}}">
<link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.min.css')}}">
<!-- END: Vendor CSS-->
<!-- BEGIN: Vendor CSS-->

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/vendors.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/calendars/fullcalendar.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/select/select2.min.css')}}">

<!-- END: Vendor CSS-->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/dark-layout.css')}}"> -->
<!-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/bordered-layout.css')}}"> -->
<!-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/semi-dark-layout.css')}}"> -->

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/dashboard-ecommerce.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/charts/chart-apex.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/ext-component-toastr.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/app-calendar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/form-wizard.css')}}">
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/google_map.css')}}">

<!-- END: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/pickers/form-pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/form-validation.css')}}">

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.1/html2pdf.bundle.js" integrity="sha512-CAlCnxBY4CJKhSvYNsnWtMxKjwLgRhHuJw7wUoOmjlrTDcRLQ5LD9P6YgWETJAmZTurlU86vBovlySF3KvjPeg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script src="https://unpkg.com/dynamsoft-camera-enhancer@2.1.0/dist/dce.js"></script>

<style>
    .select2-selection__clear{
        font-size: 1.4rem !important;
        color: #7367f0 !important;
    }
    .collapse-icon [data-toggle='collapse']:after {
        left: 1rem;
        background-image:url("{{asset('images/app/minus.svg')}}");
    }
    .collapse-icon [aria-expanded='false']:after {
        background-image:url("{{asset('images/app/plus.svg')}}");
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