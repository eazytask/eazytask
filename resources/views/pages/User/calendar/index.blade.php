@extends('layouts.Admin.master')


@section('admincontent')
<style type="text/css">
    .fc-list-event-time {
        display: none;
    }

    .mydate .flatpickr-wrapper {
        display: block;
    }

    .custom-control-purple .custom-control-input:disabled:checked ~ .custom-control-label::before{
        background-color: #800080a6 !important;
    }
</style>

<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Roster Calender</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home/{{ Auth::id() }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Roster Calender
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>
<section>
    <div class="app-calendar overflow-hidden border">
        <div class="row no-gutters">
            <!-- Sidebar -->
            <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
                <div class="sidebar-wrapper">

                    <!-- <div class="card-body justify-content-center">
                        
                        <button class="btn btn-secondary btn-block" type="button" id="buttons-print"><span>Print</span></button>
                        
                    </div> -->

                    <div class="card-body pb-0">
                        <h2 class="section-label mb-1">
                            <span class="align-middle">Filter</span>
                        </h2>
                        <div class="form-group">
                            <label for="projectFilter" class="form-label">Select Venue</label>
                            <select class="select2 select-label form-control w-100" id="projectFilter" name="projectFilter[]" multiple="multiple">
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->pName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <div class="calendar-events-filter">
                                <div class="custom-control custom-control-info custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input input-filter" id="family" data-value="family" checked disabled/>
                                    <label class="custom-control-label" for="family">Unschedueled</label>
                                </div>
                                <div class="custom-control custom-control-danger custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input input-filter" id="family" data-value="family" checked disabled/>
                                    <label class="custom-control-label" for="family">Waiting For Confirmation</label>
                                </div>
                                <div class="custom-control custom-control-success custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input input-filter" id="holiday" data-value="holiday" checked disabled/>
                                    <label class="custom-control-label" for="holiday">Upcoming</label>
                                </div>
                                <div class="custom-control custom-control-primary custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input input-filter" id="etc" data-value="etc" checked disabled/>
                                    <label class="custom-control-label" for="etc">Past</label>
                                </div>
                                <div class="custom-control custom-control-purple custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input input-filter" id="etc" data-value="etc" checked disabled/>
                                    <label class="custom-control-label" for="etc">Event</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-auto">
                    <img src="{{asset('app-assets/images/pages/calendar-illustration.png')}}" alt="Calendar illustration" class="img-fluid" />
                </div>
            </div>
            <!-- /Sidebar -->

            <!-- Calendar -->
            <div class="col position-relative">
                <div class="card shadow-none border-0 mb-0 rounded-0">
                    <div class="card-body pb-0">
                        <div id="user_calendar_timekeeper"></div>
                    </div>
                </div>
            </div>
            <!-- /Calendar -->
            <div class="body-content-overlay"></div>
        </div>
    </div>
    <!-- Calendar Add/Update/Delete event modal-->
    <div class="modal modal-slide-in event-sidebar fade" id="add-new-sidebar">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">View Event</h5>
                </div>

                <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                    <section id="multiple-column-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label for=""> Venue</label>
                                        <div class="form-group">
                                            <select class="form-control select2" name="project_id" id="project-select" aria-label="Default select example" disabled>
                                                <option value="" disabled selected hidden>Please Choose...
                                                </option>
                                                @foreach ($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->pName }}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <label for="email-id-column">Roster Date</label>
                                        <div class="form-group">
                                            <input type="text" id="roaster_date" name="roaster_date" disabled class="form-control format-picker" placeholder="Roster Date" />
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <label for="email-id-column">Shift Start</label>
                                        <div class="form-group">

                                            <input type="text" disabled id="shift_start" name="shift_start" class="form-control pickatime-format" placeholder="Shift Start Time" />

                                            <span id="shift_start_error" class="text-danger text-small"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <label for="email-id-column">Shift Ends Date & Time</label>
                                        <div class="form-group">

                                            <input type="text" disabled id="shift_end" name="shift_end" class="form-control pickatime-format" placeholder="Shift End Time" />
                                            <span id="shift_end_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <label for="email-id-column">Duration</label>
                                        <div class="form-group">
                                            <input type="text" id="duration" name="duration" class="form-control" placeholder="Duration" id="days" disabled />
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <label for="email-id-column">Amount Per Hour</label>
                                        <div class="form-group">
                                            <input type="number" id="rate" name="ratePerHour" class="form-control reactive" placeholder="0" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <label for="email-id-column">Amount</label>
                                        <div class="form-group">
                                            <input type="text" id="amount" name="amount" class="form-control" placeholder="0" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <label for="">Job Type</label>
                                        <div class="form-group">
                                            <select class="form-control select2" name="job_type_id" id="job" aria-label="Default select example" disabled>
                                                <option value="" disabled selected hidden>Please Choose...
                                                </option>
                                                @foreach ($job_types as $job_type)
                                                <option value="{{ $job_type->id }}">{{ $job_type->name }}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12 ">
                                        <label for="">Roster Status</label>
                                        <div class="form-group">
                                            <select class="form-control select2" name="roaster_status_id" id="roster" aria-label="Default select example" disabled>
                                                <option value="" disabled selected hidden>Please Choose...
                                                </option>
                                                @foreach ($roaster_status as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <label for="roaster_type" class="form-label">Roster Type</label>
                                        <div class="form-group">
                                            <select class="form-control select2" id="roasterType" disabled>
                                                <option value="">Select Roster Type</option>
                                                <option value="Unschedueled">Unschedueled</option>
                                                <option value="Schedueled">Schedueled</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="col-md-12 col-12">
                                        <label for="email-id-column">Remarks</label>
                                        <div class="form-group">
                                            <input type="text" name="remarks" id="remarks" class="form-control" placeholder="remarks" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="form-group d-flex">
                        <button type="button" class="btn btn-outline-secondary btn-cancel" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn ml-1 bg-gradient-success" id="accept">Accept</button>
                        <button type="button" class="btn ml-1 bg-gradient-success" id="request" disabled>Requested</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="post" id="signInFrom" hidden>
        @csrf
        <input type="text" id="event_id" name="event_id">
    </form>
    <!--/ Calendar Add/Update/Delete event modal-->
</section>

<script src="{{asset('app-assets/js/scripts/pages/user-calendar-timekeeper.js')}}"></script>
<script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        var project_id = $('#project_id')

        $('#projectFilter').wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select Project',
            dropdownParent: $('#projectFilter').parent()
        });

        // $('#buttons-print').click(function(){
        //     $(".modern-nav-toggle").click()
        //      window.print();

        //     $(".modern-nav-toggle").click()
        //  });
    });
</script>
@endsection