<div class="modal fade text-left p-md-1 p-0" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="event-modal-title text-white" id="myModalLabel17">Add Event</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0">

                                <div class="card-body pb-0">
                                    <form class="form" id="addEventForm">
                                        @csrf
                                        
                                        <input type="hidden" name="id" id="event_id" value="">
                                        <div class="row">

                                            <div class="col-12 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="event_date">Event Date *</label>
                                                    <input type="text" id="event_date" class="form-control disable-back-picker" placeholder="Event date" name="event_date" required />
                                                </div>
                                            </div>

                                            <div class="col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="shift_start">Shift Start *</label>
                                                    <input type="text" id="shift_start" name="shift_start" required class="form-control pickatime-format" placeholder="Shift Start Time" />
                                                </div>
                                            </div>

                                            <div class="col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="shift_end">Shift End *</label>
                                                    <input type="text" id="shift_end" name="shift_end" required class="form-control pickatime-format" placeholder="Shift Start Time" />
                                                </div>
                                            </div>

                                            <div class="col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="project_name">Select Venue *</label>
                                                    <select class="form-control select2" id="project_name" name="project_name" aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($projects as $project)
                                                        @if ($project->Status == 1)
                                                        <option value="{{ $project->id }}">
                                                            {{ $project->pName }}
                                                        </option>
                                                        @else
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="job_type_name">Job Type *</label>
                                                    <select class="form-control select2" id="job_type_name" name="job_type_name" aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($job_types as $job_type)
                                                        <option value="{{ $job_type->id }}">
                                                            {{ $job_type->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="rate">Rate *</label>
                                                    <input type="number" class="form-control" id="rate" name="rate" placeholder="Enter rate" required />
                                                </div>
                                            </div>

                                            <div class="col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label>
                                                    <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Enter remarks" />
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="editEventBtn" hidden><i data-feather='check'></i></button>
                <button type="button" class="btn btn-success" id="addEventBtn"><i data-feather='save'></i></button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i data-feather='x'></i></button>
            </div>
            </form>
        </div>
    </div>
</div>