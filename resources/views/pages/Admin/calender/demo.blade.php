@extends('layouts.Admin.master')


@section('admincontent')
<style type="text/css">
    .fc-list-event-time{
        display: none;
    }
    .mydate .flatpickr-wrapper {        
        display: block;
    }
</style>

<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Roster Calender</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
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
                    <div class="card-body justify-content-center">
                        <button class="btn btn-gradient-primary btn-toggle-sidebar btn-block" data-toggle="modal" data-target="#add-new-sidebar">
                            <span class="align-middle">Add Event</span>
                        </button>
                    </div>
                    <div class="card-body pb-0">
                        <h5 class="section-label mb-1">
                            <span class="align-middle">Filter</span>
                        </h5>
                        <div class="form-group">
                            <label for="employeeFilter" class="form-label">Select Employee</label>
                            <select class="select2 select-label form-control w-100" id="employeeFilter" name="employeeFilter[]" multiple="multiple">  
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->fname }}</option>
                                @endforeach
                            </select>
                        </div>
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
            <div class="col position-relative">
                <div class="card shadow-none border-0 mb-0 rounded-0">
                    <div class="card-body pb-0">
                        <div id="calendar_timekeeper"></div>
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
                    <h5 class="modal-title">Add Event</h5>
                </div>
                <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                    <form class="event-form needs-validation" id="myCalendarForm" data-ajax="false" novalidate>
                        @csrf                        
                        <div class="form-group">
                            <label for="employee_id" class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select class="select2 select-label form-control w-100" id="employee_id" name="employee_id" required>  
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->fname }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="project_id" class="form-label">Select Venue<span class="text-danger">*</span></label>
                            <select class="select2 select-label form-control w-100" id="project_id" name="project_id" required>  
                                <option value="">Select Venue</option> 
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->pName }}</option>
                                @endforeach                               
                            </select>
                        </div>                         

                        <div class="form-group position-relative">
                            <label for="roaster_date" class="form-label">Roster Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control format-picker" id="roaster_date" name="roaster_date" placeholder="Roster Date" readonly required />
                        </div>

                        <div class="form-group position-relative mydate">
                            <label for="shift_start" class="form-label">Shift Start Date & Time <span class="text-danger">*</span></label>
                            <!-- <input type="text" id="shift_start" name="shift_start" class="form-control"  required placeholder="DD-MM-YYYY HH:MM"   /> -->
                            <input type="text" disabled id="shift_start" name="shift_start" required class="form-control pickatime-format" placeholder="Shift Start Time" />
                        </div>

                        <div class="form-group position-relative mydate">
                            <label for="shift_end" class="form-label">Shift End Date & Time <span class="text-danger">*</span></label>
                            <!-- <input type="text" id="shift_end" name="shift_end" class="form-control"  required placeholder="DD-MM-YYYY HH:MM"   /> -->
                            <input type="text" disabled id="shift_end" name="shift_end" required class="form-control pickatime-format" placeholder="Shift End Time" />
                        </div>

                        <div class="form-group position-relative">
                            <label for="duration" class="form-label">Duration <span class="text-danger">*</span></label>
                            <input type="text" name="duration" class="form-control" placeholder="Duration" id="duration" readonly="readonly" required />
                        </div>

                        <div class="form-group position-relative">
                            <label for="ratePerHour" class="form-label">Amount Per Hour <span class="text-danger">*</span></label>
                            <input type="number" name="ratePerHour" class="form-control reactive" placeholder="0" id="ratePerHour" required />
                        </div>

                        <div class="form-group position-relative">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="text" name="amount" class="form-control" placeholder="0" id="amount" readonly="readonly" required />
                        </div>

                        <div class="form-group">
                            <label for="job_type_id" class="form-label">Select Job Type <span class="text-danger">*</span></label>
                            <select class="select2 select-label form-control w-100" id="job_type_id" name="job_type_id" required>  
                                <option value="">Select Job Type</option>
                                @foreach ($job_types as $job_type)
                                    <option value="{{ $job_type->id }}">{{ $job_type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roaster_type" class="form-label">Select Roster Type <span class="text-danger">*</span></label>
                            <select class="select2 select-label form-control w-100" id="roaster_type" name="roaster_type" required>  
                                <option value="">Select Roster Type</option>
                                <option value="Unschedueled">Unschedueled</option>
                                <option value="Schedueled">Schedueled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roaster_status_id" class="form-label">Select Roster Status <span class="text-danger">*</span></label>
                            <select class="select2 select-label form-control w-100" id="roaster_status_id" name="roaster_status_id" required>  
                                <option value="">Select Roster Status</option>
                                @foreach ($roaster_status as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group position-relative">
                            <label for="remarks" class="form-label">Remarks <span class="text-danger">*</span></label>
                            <input type="text" name="remarks" class="form-control" id="remarks" placeholder="remarks" />
                        </div>

                        <div class="form-group d-flex">
                            <button type="submit" class="btn btn-gradient-primary add-event-btn mr-1"><i data-feather='save'></i></button>
                            <button type="button" class="btn btn-outline-secondary btn-cancel" data-dismiss="modal"><i data-feather='x'></i></button>                            
                            <button type="submit" class="btn btn-gradient-primary update-event-btn d-none mr-1"><i data-feather='check'></i></button>
                            <button type="button" class="btn btn-gradient-primary copy-event-btn d-none mr-1"><i data-feather='copy'></i></button>
                            <button class="btn btn-outline-danger btn-delete-event d-none"><i data-feather='trash-2'></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Calendar Add/Update/Delete event modal-->
</section>
@endsection
@push('scripts')
    <script src="{{asset('app-assets/js/scripts/pages/app-calendar-timekeeper.js')}}"></script>

<script type="text/javascript" src="{{ asset('backend') }}/lib/toastr/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){


});
</script>
<script type="text/javascript">
    var auth_id  = "{{Auth::user()->id}}";
  
    $(document).ready(function() {
        var employee_id = $('#employee_id'),
            project_id = $('#project_id'),
            job_type_id = $('#job_type_id'),
            roaster_type = $('#roaster_type'),
            roaster_status_id = $('#roaster_status_id');
    
        
        employee_id.wrap('<div class="position-relative"></div>').select2({            
            dropdownParent: employee_id.parent()
        })
        .change(function () {
            $(this).valid();
        });


        project_id.wrap('<div class="position-relative"></div>').select2({            
            dropdownParent: project_id.parent()
        })
        .change(function () {
            $(this).valid();
        });    
        job_type_id.wrap('<div class="position-relative"></div>').select2({            
            dropdownParent: job_type_id.parent()
        })
        .change(function () {
            $(this).valid();
        });
        roaster_type.wrap('<div class="position-relative"></div>').select2({            
            dropdownParent: roaster_type.parent()
        })
        .change(function () {
            $(this).valid();
        });
        roaster_status_id.wrap('<div class="position-relative"></div>').select2({            
            dropdownParent: roaster_status_id.parent()
        })
        .change(function () {
            $(this).valid();
        });

        $('#employeeFilter').wrap('<div class="position-relative"></div>').select2({   
            placeholder: 'Select Employee',         
            dropdownParent: $('#employeeFilter').parent()
        });

        $('#projectFilter').wrap('<div class="position-relative"></div>').select2({  
            placeholder: 'Select Project',          
            dropdownParent: $('#projectFilter').parent()
        });        
    });
</script>
@endpush


