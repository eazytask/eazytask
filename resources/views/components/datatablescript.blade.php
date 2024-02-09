<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<style>
    .search-box .search-icon{
        left: 20px;
    }
    button.dt-button:active:not(.disabled), button.dt-button.active:not(.disabled), div.dt-button:active:not(.disabled), div.dt-button.active:not(.disabled), a.dt-button:active:not(.disabled), a.dt-button.active:not(.disabled), input.dt-button:active:not(.disabled), input.dt-button.active:not(.disabled) {
        background-color: none !important;
        box-shadow: none !important;
    }
    div.dt-button-collection button.dt-button:active:not(.disabled), div.dt-button-collection button.dt-button.active:not(.disabled), div.dt-button-collection div.dt-button:active:not(.disabled), div.dt-button-collection div.dt-button.active:not(.disabled), div.dt-button-collection a.dt-button:active:not(.disabled), div.dt-button-collection a.dt-button.active:not(.disabled) {
        background-color: none !important;
        background: none !important;
    }
    button.dt-button:hover:not(.disabled), div.dt-button:hover:not(.disabled), a.dt-button:hover:not(.disabled), input.dt-button:hover:not(.disabled){
        border-color: transparent !important;
    }
    .buttons-columnVisibility{
        text-align: left;
    }
    .buttons-columnVisibility:after {
        content: '\2714';
        left: 15px;
        top: 6px;
        position: absolute;
        font-size: 12px;
        display: inline-block;
    }
    .buttons-columnVisibility:not(.active):after{
        display: none;
    }
    .buttons-columnVisibility span{
        vertical-align:top;
    }
    .buttons-columnVisibility:before {
        content: '\25a2';
        display: inline-block;
        font-size: 20px;
        margin-right: 5px;
    }

</style>
<script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>

{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script> --}}


{{-- for temporary use only --}}
<script src="{{asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/jszip.min.js')}}"></script>
<!-- <script src="{{asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js')}}"></script> -->
<script src="{{asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js')}}"></script>




<script src="{{asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/buttons.colVis.js')}}"></script>