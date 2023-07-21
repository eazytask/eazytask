<!-- BEGIN: Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<!-- <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script> -->
{{-- <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script> --}}
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app.js') }}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<!-- <script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script> -->
<!-- END: Page JS-->
<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
<!-- <script src="{{ asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script> -->
<script src="{{ asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Page JS-->
<script src="{{ asset('app-assets/js/scripts/tables/table-datatables-basic.js') }}"></script>
<!-- BEGIN: Page JS-->
<script src="{{ asset('app-assets/js/scripts/tables/table-datatables-advanced.js') }}"></script>
<!-- END: Page JS-->
<!-- END: Page JS-->
<script src="{{asset('app-assets/vendors/js/extensions/moment.min.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>



<script src="{{ asset('backend') }}/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('backend') }}/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<!-- <script src="{{ asset('app-assets/js/custom.js') }}"></script> -->
<script src="{{ asset('app-assets/js/scripts/pages/page-account-settings.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    });
</script>
<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>


<script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
<!-- <script type="text/javascript" src="{{ asset('common') }}/jquery.form-validaton-min.js"></script> -->
<!-- <script>
    $.validate({
        lang: 'en'
    });
</script> -->

<script src="../../../app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>

<script>
    @if (Session::has('message'))
        var type ="{{ Session::get('alert-type', 'info') }}"
        switch(type){
        case 'info':
        toastr.info(" {{ Session::get('message') }} ");
        break;
        case 'success':
        toastr.success(" {{ Session::get('message') }} ");
        break;
        case 'warning':
        toastr.warning(" {{ Session::get('message') }} ");
        break;
        case 'error':
        toastr.error(" {{ Session::get('message') }} ");
        break;
        }
    @endif
</script>
