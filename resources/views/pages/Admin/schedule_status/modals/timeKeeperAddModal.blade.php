<div class="modal fade text-left p-md-1 p-0" id="addTimeKeeper" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel17" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" id="myModalLabel17">Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="timekeeperAddForm" data-ajax="false">
                <input type="text" id="timepeeper_id" name="timepeeper_id" value="" hidden>
                @csrf
                <div class="modal-body">
                    <section id="multiple-column-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-0">
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
                                                                <label for="">Select Employee</label>
                                                                <div class="mb-3">
                                                                    <select class="form-control select2"
                                                                        name="employee_id" id="employee_id"
                                                                        aria-label="Default select example"
                                                                        disabled>
                                                                        <option value="{{ old('employee_id') }}"
                                                                            disabled selected hidden>Please
                                                                            Choose...</option>
                                                                        @foreach ($employees as $employee)
                                                                            @php
                                                                                $emp = $employee;
                                                                            @endphp
                                                                            <option value="{{ $employee->id }}">
                                                                                {{ $emp->fname }}
                                                                                {{ $emp->mname }}
                                                                                {{ $emp->lname }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-6 pl-25 pr-25">
                                                                <label for="">Select Venue</label>
                                                                <div class="mb-3">
                                                                    <select class="form-control select2"
                                                                        name="project_id" id="project-select"
                                                                        aria-label="Default select example"
                                                                        disabled>
                                                                        <option value="" disabled selected
                                                                            hidden>Please Choose...
                                                                        </option>
                                                                        @foreach ($projects as $project)
                                                                            <option value="{{ $project->id }}">
                                                                                {{ $project->pName }}
                                                                            </option>
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-6 pl-25 pr-25">
                                                                <label for="email-id-column">Roster Date</label>
                                                                <div class="mb-3">
                                                                    <input type="text" id="roaster_date"
                                                                        name="roaster_date" disabled
                                                                        class="form-control format-picker"
                                                                        placeholder="Roster Date" />
                                                                </div>
                                                            </div>

                                                            <div class="col-6 pl-25 pr-25 mt-auto">
                                                                <label for="">Select Job Type</label>
                                                                <div class="mb-3">
                                                                    <select class="form-control select2"
                                                                        name="job_type_id" id="job"
                                                                        aria-label="Default select example"
                                                                        disabled>

                                                                        @foreach ($job_types as $job_type)
                                                                            <option value="{{ $job_type->id }}"
                                                                                {{ $job_type->name == 'core' ? 'selected' : '' }}>
                                                                                {{ $job_type->name }}
                                                                            </option>
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">Shift Start</label>
                                                                <div class="mb-3">
                                                                    <input type="text" disabled id="shift_start"
                                                                        class="form-control pickatime-format"
                                                                        readonly />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">Shift End</label>
                                                                <div class="mb-3">
                                                                    <input type="text" disabled id="shift_end"
                                                                        class="form-control pickatime-format"
                                                                        readonly />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">Sign In</label>
                                                                <div class="mb-3">
                                                                    <input type="text" disabled id="sign_in"
                                                                        class="form-control pickatime-format" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">Sign Out</label>
                                                                <div class="mb-3">
                                                                    <input type="text" id="sign_out"
                                                                        class="form-control pickatime-format" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">App. Start *</label>
                                                                <div class="mb-3">
                                                                    <input type="text" id="app_start"
                                                                        name="app_start" required
                                                                        class="form-control pickatime-format"
                                                                        placeholder="App Start Time" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">App. End *</label>
                                                                <div class="mb-3">
                                                                    <input type="text" id="app_end"
                                                                        name="app_end" required
                                                                        class="form-control pickatime-format"
                                                                        placeholder="Shift End Time" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">App. Duration</label>
                                                                <div class="mb-3">
                                                                    <input type="text" id="app_duration"
                                                                        name="app_duration" class="form-control"
                                                                        placeholder="App Duration"
                                                                        readonly="readonly" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                                <label for="email-id-column">App. Rate *</label>
                                                                <div class="mb-3">
                                                                    <input type="number" id="app_rate"
                                                                        name="app_rate"
                                                                        class="form-control reactive"
                                                                        placeholder="0" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-6 pl-25 pr-25">
                                                                <label for="email-id-column">App. Amount</label>
                                                                <div class="mb-3">
                                                                    <input type="text" id="app_amount"
                                                                        name="app_amount" class="form-control"
                                                                        placeholder="0" readonly="readonly" />
                                                                </div>
                                                            </div>
                                                            <div class="col-6 pl-25 pr-25">
                                                                <label for="email-id-column">Remarks</label>
                                                                <div class="mb-3">
                                                                    <input type="text" name="remarks"
                                                                        id="remarks" class="form-control"
                                                                        placeholder="remarks" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel <i
                            data-feather='x'></i></button>
                    <button type="button" class="btn btn-success timekeer-btn" id="editTimekeeperSubmit"
                        onclick="timekeeperEditFunc()">Approved <i data-feather='check-circle'></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
