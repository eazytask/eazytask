<div class="modal fade text-left p-md-1 p-0" id="addTimeKeeper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-light" id="myModalLabel17">Timesheet</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('store-new-timekeeper') }}" method="POST" id="newModalForm">
                <input type="text" id="timepeeper_id" name="timepeeper_id" value="" hidden>
                @csrf
                <div class="modal-body">
                    <section id="multiple-column-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-0">

                                    <div class="card-body pb-0">

                                        @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                        <div class="row">

                                            <div class="col-6 pl-25 pr-25">
                                                <label for="">Select Venue *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="project_id" id="project-select" aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}">{{ $project->pName }}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-25 pr-25">
                                                <label for="email-id-column">Roster Date *</label>
                                                <div class="form-group">
                                                    <!-- <input type="text" readonly id="roaster_date" name="roaster_date"
                                                        class="form-control" placeholder="DD-MM-YYYY" required /> -->

                                                    <input type="text" id="roaster_date" name="roaster_date" required class="form-control disable-picker" placeholder="Roster Date" />
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift Start *</label>
                                                <div class="form-group">
                                                    <!-- <input type="text" readonly id="shift_start" name="shift_start"
                                                        class="form-control reactive" placeholder="Start" required /> -->

                                                    <input type="text" disabled id="shift_start" name="shift_start" required class="form-control pickatime-format" placeholder="Shift Start Time" />

                                                    <span id="shift_start_error" class="text-danger text-small"></span>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift End *</label>
                                                <div class="form-group">
                                                    <!-- <input type="text" readonly id="shift_end" name="shift_end"
                                                        class="form-control reactive" placeholder="End" required /> -->

                                                    <input type="text" disabled id="shift_end" name="shift_end" required class="form-control pickatime-format" placeholder="Shift End Time" />
                                                    <span id="shift_end_error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="col-md-6 pl-25 pr-25">

                                                <label for="">Select Employee *</label>
                                                <div class="demo-inline-spacing mb-1" style="margin-top: -16px;">
                                                    <div class="custom-control custom-control-primary custom-radio">
                                                        <input type="radio" id="available" name="filter_employee" class="custom-control-input" value="available" checked />
                                                        <label class="custom-control-label" for="available">Available</label>
                                                    </div>
                                                    <div class="custom-control custom-control-success custom-radio">
                                                        <input type="radio" id="inducted" name="filter_employee" class="custom-control-input" value="inducted" disabled />
                                                        <label class="custom-control-label" for="inducted">Inducted</label>
                                                    </div>
                                                    <div class="custom-control custom-control-info custom-radio">
                                                        <input type="radio" id="all" name="filter_employee" class="custom-control-input" value="all" />
                                                        <label class="custom-control-label" for="all">All</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="employee_id" id="employee_id" aria-label="Default select example" required>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-6 pl-25 pr-25 mt-auto">
                                                <label for="email-id-column">Duration</label>
                                                <div class="form-group">
                                                    <input type="text" id="duration" name="duration" class="form-control" placeholder="Duration" id="days" readonly="readonly" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Amount Per Hour *</label>
                                                <div class="form-group">
                                                    <input type="number" id="rate" name="ratePerHour" class="form-control reactive" placeholder="0" required />
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Amount</label>
                                                <div class="form-group">
                                                    <input type="text" id="amount" name="amount" class="form-control" placeholder="0" readonly="readonly" />
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="">Select Job Type *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="job_type_id" id="job" aria-label="Default select example" required>

                                                        @foreach ($job_types as $job_type)
                                                        <option value="{{ $job_type->id }}" {{$job_type->name =='core'?'selected':''}}>{{ $job_type->name }}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25 d-none">
                                                <label for="">Select Roster Status *</label>
                                                <div class="form-group">
                                                    <select hidden class="form-control" name="roaster_status_id" id="roster" aria-label="Default select example" >
                                                      
                                                        @foreach ($roaster_status as $row)
                                                        <option value="{{ $row->id }}" {{$row->name =='Not published'?'selected=""':''}}>{{ $row->name }}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Remarks</label>
                                                <div class="form-group">
                                                    <input type="text" name="remarks" id="remarks" class="form-control" placeholder="remarks" />
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
                    <button type="button" class="btn btn-success timekeer-btn">Submit</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Discard</button>
                </div>
            </form>
        </div>
    </div>
</div>