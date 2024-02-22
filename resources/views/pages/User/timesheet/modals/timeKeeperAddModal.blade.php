<div class="modal fade text-left p-md-1 p-0" id="userAddTimeKeeper" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle">
                <h4 class="modal-title" id="myModalLabel17">New Timesheet</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form action="{{ route('store-timesheet') }}" method="POST" id="newModalForm">
                <input type="text" id="timekeeper_id" name="timekeeper_id" value="" hidden>
                <input type="text" id="url" value="" hidden>
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
                                            
                                        <div class="col-12 pl-25 pr-25">
                                                <label for="email-id-column">Roster Date *</label>
                                                <div class="form-group">
                                                    <input type="text" id="roaster_date" name="roaster_date" required class="form-control disable-picker" placeholder="Roster Date" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift Start *</label>
                                                <div class="form-group">
                                                    <!-- <input type="text" readonly id="shift_start" name="shift_start"
                                                        class="form-control reactive" placeholder="Start" required /> -->

                                                    <input type="text" id="shift_start" name="shift_start" required class="form-control pickatime-format" placeholder="Shift Start Time" />

                                                    <span id="shift_start_error" class="text-danger text-small"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift End *</label>
                                                <div class="form-group">
                                                    <!-- <input type="text" readonly id="shift_end" name="shift_end"
                                                        class="form-control reactive" placeholder="End" required /> -->

                                                    <input type="text" id="shift_end" name="shift_end" required class="form-control pickatime-format" placeholder="Shift End Time" />
                                                    <span id="shift_end_error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Duration</label>
                                                <div class="form-group">
                                                    <input type="text" id="duration" name="duration" class="form-control" placeholder="Duration" id="days" readonly="readonly" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Rate *</label>
                                                <div class="form-group">
                                                    <input type="number" id="rate" name="ratePerHour"
                                                        class="form-control reactive" placeholder="0" required />
                                                </div>
                                            </div>
                                            
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Amount</label>
                                                <div class="form-group">
                                                    <input type="text" id="amount" name="amount" class="form-control" placeholder="0" readonly="readonly" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="first-name-column">Venue Name*</label>
                                                    <select class="form-control select2" name="project_id" id="project_id"
                                                        aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">
                                                    {{ $project->pName }}
                                                                </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="">Select Job Type *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="job_type_id" id="job"
                                                        aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($job_types as $job_type)
                                                            <option value="{{ $job_type->id }}">{{ $job_type->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Remarks</label>
                                                <div class="form-group">
                                                    <input type="text" name="remarks" id="remarks"
                                                        class="form-control" placeholder="remarks"/>
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
                    <button type="button" class="btn btn-gradient-danger" id="deleteBtn"><i data-feather='trash-2'></i></button>
                    <button type="button" class="btn btn-gradient-info" id="copyBtn"><i data-feather='copy'></i></button>
                    <button type="button" class="btn btn-gradient-warning" id="editBtn"><i data-feather='edit'></i></button>
                    <button type="submit" class="btn btn-gradient-success" id="updateBtn"><i data-feather='check'></i></button>
                    <button type="submit" class="btn btn-gradient-primary" id="addBtn"><i data-feather='save'></i></button>
                    <!-- <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Discard</button> -->
                </div>
            </form>
        </div>
    </div>
</div>
