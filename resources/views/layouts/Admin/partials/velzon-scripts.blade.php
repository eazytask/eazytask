<script src="{{ URL::asset('app-assets/velzon/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/js/plugins.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/js/app.js') }}"></script>
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('app-assets/velzon/js/html2pdf.bundle.min.js')}}" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- start external --}}
<script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('app-assets/js/scripts/forms/pickers/jquery.timepicker.min.js')}}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{asset('app-assets/vendors/js/extensions/moment.min.js')}}"></script>
<script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
<script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
{{-- toastr --}}
<script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>
<script>
    @if(Session::has('message'))
    var type = "{{Session::get('alert-type','info')}}"
    switch (type) {
        case 'info':
            toastr.info(" {{Session::get('message')}} ");
            break;
        case 'success':
            toastr.success(" {{Session::get('message')}} ");
            break;
        case 'warning':
            toastr.warning(" {{Session::get('message')}} ");
            break;
        case 'error':
            toastr.error(" {{Session::get('message')}} ");
            break;
    }
    @endif
</script>

@yield('script')
@yield('script-bottom')
@stack('scripts')