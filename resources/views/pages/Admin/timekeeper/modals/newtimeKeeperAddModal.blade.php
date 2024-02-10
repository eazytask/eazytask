<div id="addTimeKeeper" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Timesheet
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('store-new-timekeeper') }}" method="POST" id="newModalForm">
                <input type="text" id="timepeeper_id" name="timepeeper_id" value="" hidden>
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
    
                        <div class="col-6">
                            <label for=""> 
                                Select Venue <span class="text-danger">*</span> 
                            </label>
    
                            <select class="form-control" data-choices name="project_id" id="project-select" required>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->pName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="col-6">
                            <label for=""> 
                                Roaster Date <span class="text-danger">*</span> 
                            </label>
    
                            <input type="text" id="roaster_date" name="roaster_date" required class="form-control disable-picker" placeholder="Roster Date" />
                        </div>
    
                        <div class="col-6">
                            <label for=""> 
                                Shift Start <span class="text-danger">*</span> 
                            </label>
    
                            <input type="text" disabled id="shift_start" name="shift_start" required class="form-control pickatime-format" placeholder="Shift Start Time" />
                            <span id="shift_start_error" class="text-danger text-small"></span>
                        </div>
    
                        <div class="col-6">
                            <label for=""> 
                                Shift End <span class="text-danger">*</span> 
                            </label>
    
                            <input type="text" disabled id="shift_end" name="shift_end" required class="form-control pickatime-format" placeholder="Shift End Time" />
                            <span id="shift_end_error" class="text-danger"></span>
                        </div>
    
                        <div class="col-12">
                            <label for="">
                                Select Employee <span class="text-danger">*</span>
                            </label>
    
                            <div class="row g-3 mb-2">
                                <div class="col-4">
                                    <input type="radio" id="available" name="filter_employee" class="btn-check" value="available" checked />
                                    <label class="btn btn-outline-primary w-100" for="available">Available</label>
                                </div>
    
                                <div class="col-4">
                                    <input type="radio" id="inducted" name="filter_employee" class="btn-check" value="inducted" disabled />
                                    <label class="btn btn-outline-danger w-100" for="inducted">Inducted</label>
                                </div>
    
                                <div class="col-4">
                                    <input type="radio" id="all" name="filter_employee" class="btn-check" value="all" />
                                    <label class="btn btn-outline-info w-100" for="all">All</label>
                                </div>
                            </div>
    
                            <div>
                                <select class="form-control select2" name="employee_id" id="employee_id" aria-label="Default select example" required>
                                </select>
                            </div>
                        </div>
    
                        <div class="col-12">
                            <label for="">
                                Duration
                            </label>
    
                            <input type="text" id="duration" name="duration" class="form-control" placeholder="Duration" id="days" readonly="readonly" />
                        </div>
    
                        <div class="col-6">
                            <label for="">
                                Amount Per Hour <span class="text-danger">*</span>
                            </label>
    
                            <input type="number" id="rate" name="ratePerHour" class="form-control reactive" placeholder="0" required />
                        </div>
    
                        <div class="col-6">
                            <label for="">
                                Amount
                            </label>
    
                            <input type="text" id="amount" name="amount" class="form-control" placeholder="0" readonly="readonly" />
                        </div>
    
                        <div class="col-6">
                            <label for="">
                                Select Job Type <span class="text-danger">*</span>
                            </label>
    
                            <select class="form-control" data-choices name="job_type_id" id="job" required>
                                @foreach ($job_types as $job_type)
                                    <option value="{{ $job_type->id }}" {{$job_type->name =='core'?'selected':''}}>{{ $job_type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="col-6 d-none">
                            <label for="">
                                Selected Roaster Status <span class="text-danger">*</span>
                            </label>
    
                            <select hidden class="form-control" data-choices name="roaster_status_id" id="roster">                
                                @foreach ($roaster_status as $row)
                                    <option value="{{ $row->id }}" {{$row->name =='Not published'?'selected=""':''}}>{{ $row->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="col-6">
                            <label for="">
                                Remarks
                            </label>
    
                            <input type="text" name="remarks" id="remarks" class="form-control" placeholder="remarks" />
                        </div>
                    </div>
                </div>
    
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary timekeer-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>