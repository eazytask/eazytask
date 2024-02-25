@extends('layouts.Admin.master')

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Home
        @endslot
        @slot('title')
            Event Calendar
        @endslot
    @endcomponent

    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <button id="addEvent" class="btn btn-success" disabled><i class="ri-add-line align-bottom me-1"></i> Add Event</button>
            </div>
        </div>
        <div class="col-sm" id="days">
            <label for="sun" class="btn btn-outline-secondary active btn-sm mt-1">
                Sun
                <input type="checkbox" class="d-none day" checked id="sun" value="sun">
            </label>
            <label for="mon" class="btn btn-outline-secondary active btn-sm mt-1">
                Mon
                <input type="checkbox" class="d-none day" checked id="mon" value="mon">
            </label>
            <label for="tue" class="btn btn-outline-secondary active btn-sm mt-1">
                Tue
                <input type="checkbox" class="d-none day" checked id="tue" value="tue">
            </label>
            <label for="wed" class="btn btn-outline-secondary active btn-sm mt-1">
                Wed
                <input type="checkbox" class="d-none day" checked id="wed" value="wed">
            </label>
            <label for="thu" class="btn btn-outline-secondary active btn-sm mt-1">
                Thu
                <input type="checkbox" class="d-none day" checked id="thu" value="thu">
            </label>
            <label for="fri" class="btn btn-outline-secondary active btn-sm mt-1">
                Fri
                <input type="checkbox" class="d-none day" checked id="fri" value="fri">
            </label>
            <label for="sat" class="btn btn-outline-secondary active btn-sm mt-1">
                Sat
                <input type="checkbox" class="d-none day" checked id="sat" value="sat">
            </label>
        </div>
        <div class="col-sm">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="flex-grow-1 text-end">
                    <button type="button" class="btn bg-light-secondary" id="prev">
                        <i data-feather='arrow-left'></i>
                    </button>
                    <label for="search_date" class="btn bg-light-secondary fw-bolder m-0" id="currentWeek">
                        {{ \Carbon\Carbon::now()->startOfWeek()->format('d M, Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('d M, Y') }}
                    </label>
                    <button type="button" class="btn bg-light-secondary" id="next">
                        <i data-feather='arrow-right'></i>
                    </button>
                </div>
                <input type="hidden" id="search_date" name="search_date" class="form-control form-control-sm visually-hidden" placeholder="dd-mm-yyyy">
            </div>
        </div>
    </div>
    
    <div class="row" id="events">

        <!-- end col -->
    </div>
    <!-- end row -->
    @include('pages.Admin.event_request.modals.eventClickModal')
    @include('pages.Admin.event_request.modals.addUpcomingeventModal')
@endsection

@push('styles')
    <style>
        .btn-outline-secondary.active{
            background: #82868b !important;
            color: white !important;
        }
        .sun .card{
            background: #53629541;
        }
        .mon .card{
            background: #4884f241;
        }
        .tue .card{
            background: #1bb8a341;
        }
        .wed .card{
            background: #f7b84b41;
        }
        .thu .card{
            background: #f0654841;
        }
        .fri .card{
            background: #38a3dd41;
        }
        .sat .card{
            background: #66ff0041;
        }
    </style>
@endpush

@push('scripts')
    @include('components.select2')
    @include('components.datatablescript');
    <script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('app-assets/velzon/libs/moment/moment.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>


    <script type="text/javascript">

        var auth_id = "{{Auth::user()->id}}";
        $(document).ready(function() {
            $('#currentWeek').on('click', function() {
                $('#search_date').flatpickr({
                    mode: "range"
                }).open();
            });

            $('#addEvent').removeAttr('disabled');
            var project_id = $('#project_id');

            project_id.wrap('<div class="position-relative"></div>').select2({
                dropdownParent: project_id.parent()
            })
            .change(function() {
                $(this).valid();
            });

            $('#projectFilter').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Project',
                dropdownParent: $('#projectFilter').parent(),
                allowClear: true
            });
            $('#project_name').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Project',
                dropdownParent: $('#project_name').parent(),
                allowClear: true
            });
            $('#job_type_name').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Job-type',
                dropdownParent: $('#job_type_name').parent(),
                allowClear: true
            });
            //print event
            // $('#buttons-print').click(function(){
            //     $(".modern-nav-toggle").click()
            //      window.print();
                
            //     $(".modern-nav-toggle").click()
            //  });

            $("#addEventForm").validate();
            $(document).on('change', '.day', function(){
                let parent = $(this).parent();
                let val = $(this).val();
                let cls = '.'+val;
                let element = $(cls);
                var styleSheet = document.createElement("style");
                styleSheet.setAttribute('for', val);
                let styles = '';
                if($(this).prop('checked')){
                    let styleEl = $('style[for="'+val+'"]')
                    styleEl.remove();
                    parent.addClass('active');
                }else{
                    styles = ` 
                        ${cls} {
                            display:none;
                        }
                    `;
                    parent.removeClass('active');
                    styleSheet.innerText = styles;
                    document.head.appendChild(styleSheet);
                }
            });
        });
    </script>
    <script src="{{asset('app-assets/js/scripts/pages/app-calendar-event-request.js')}}"></script>
@endpush