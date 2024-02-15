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
                <button id="addEvent" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i> Add Event</button>
            </div>
        </div>
        <div class="col-sm">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="flex-grow-1 text-end">
                    <button type="button" class="btn bg-light-secondary" id="prev">
                        <i data-feather='arrow-left'></i>
                    </button>
                    <button type="button" class="btn bg-light-secondary" id="currentWeek">
                        {{ \Carbon\Carbon::now()->startOfWeek()->format('d M, Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('d M, Y') }}
                    </button>
                    <button type="button" class="btn bg-light-secondary" id="next">
                        <i data-feather='arrow-right'></i>
                    </button>
                </div>
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


@section('')
    <style type="text/css">
        .fc-list-event-time {
            display: none;
        }

        .mydate .flatpickr-wrapper {
            display: block;
        }

        /*.fc-timegrid-axis,.fc-timegrid-slot-label,.fc-scrollgrid-shrink{
            display: none;
        }*/
    </style>

    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Event Calender</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Event Calender
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <section>
        
        <div class="app-calendar overflow-hidden border">
            <div class="row g-0">
                <!-- Sidebar -->
                <div class="col-0 col-md-4 app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
                    <div class="sidebar-wrapper">
                        <div class="card-body justify-content-center">
                            
                            <!-- <button class="btn btn-secondary btn-block" type="button" id="buttons-print"><span>Print</span></button> -->
                            
                            <button class="btn btn-gradient-primary btn-toggle-sidebar btn-block" id="addEvent">
                                <span class="align-middle">Add Event</span>
                            </button>
                        </div>
                        <div class="card-body pb-0">
                            <h5 class="section-label mb-1">
                                <span class="align-middle">Filter</span>
                            </h5>
                            
                            <div class="form-group">
                                <label for="projectFilter" class="form-label">Select Venue</label>
                                <select class="select2 select-label form-control w-100" id="projectFilter" name="projectFilter[]" multiple="multiple">
                                    @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->pName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <img src="{{asset('app-assets/images/pages/calendar-illustration.png')}}" alt="Calendar illustration" class="img-fluid" />
                    </div>
                </div>
                <!-- /Sidebar -->

                <!-- Calendar -->
                <div class="col-md-8 position-relative">
                    <div class="card shadow-none border-0 mb-0 rounded-0">
                        <div class="card-body pb-0">
                            <div id="calendar_event_request"></div>
                        </div>
                    </div>
                </div>
                <!-- /Calendar -->
                <div class="body-content-overlay"></div>
            </div>
        </div>
        @include('pages.Admin.event_request.modals.eventClickModal')
        @include('pages.Admin.event_request.modals.addUpcomingeventModal')

    </section>
@endsection

@push('scripts')
    @include('components.select2')
    @include('components.datatablescript');
    <script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('app-assets/velzon/libs/moment/moment.js')}}"></script>


    <script type="text/javascript">

        var auth_id = "{{Auth::user()->id}}";
        $(document).ready(function() {
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
        });
    </script>
    <script src="{{asset('app-assets/js/scripts/pages/app-calendar-event-request.js')}}"></script>
@endpush