<script src="{{ URL::asset('app-assets/velzon/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/js/plugins.js') }}"></script>
<script src="{{ URL::asset('app-assets/velzon/js/app.js') }}"></script>
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>
@yield('script')
@yield('script-bottom')
@stack('scripts')