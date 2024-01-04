<div class="modal fade text-left p-md-1 p-0" id="addTimeKeeper" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel17" aria-hidden="true" style="overflow: scroll;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-light" id="myModalLabel17">Roster Entry</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="timekeeperAddForm" data-ajax="false">
                <input type="text" id="timepeeper_id" name="timepeeper_id" value="" hidden>
                <!-- <input type="text" name="roaster_type" value="Schedueled" hidden> -->
                <input type="text" name="roaster_type" id="roster_type" hidden>
                <input type="text" name="schedule_roaster" value="true" hidden>
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
                                        <div class="row" style="margin: 0 !important;">
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="">Select Venue *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="project_id"
                                                        id="project-select" aria-label="Default select example"
                                                        required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->pName }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Roster Date *</label>
                                                <div class="form-group">
                                                    <input type="text" id="roaster_date" name="roaster_date" required
                                                        class="form-control format-picker" placeholder="Roster Date" />
                                                </div>
                                            </div>

                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift Start *</label>
                                                <div class="form-group">
                                                    <input type="text" disabled id="shift_start" name="shift_start"
                                                        required class="form-control pickatime-format"
                                                        placeholder="Shift Start Time" />

                                                    <span id="shift_start_error" class="text-danger text-small"></span>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift End *</label>
                                                <div class="form-group">
                                                    <input type="text" disabled id="shift_end" name="shift_end"
                                                        required class="form-control pickatime-format"
                                                        placeholder="Shift End Time" />
                                                    <span id="shift_end_error" class="text-danger"></span>
                                                </div>
                                            </div>

                                            <div class="col-4 pl-25 pr-25">
                                                <label for="email-id-column">Duration</label>
                                                <div class="form-group">
                                                    <input type="text" id="duration" name="duration"
                                                        class="form-control" placeholder="Duration" id="days"
                                                        readonly="readonly" required />
                                                </div>
                                            </div>

                                            <div class="col-4 pl-25 pr-25">
                                                <label for="email-id-column">Rate*</label>
                                                <div class="form-group">
                                                    <input type="number" id="rate" name="ratePerHour"
                                                        class="form-control reactive" placeholder="0" required />
                                                </div>
                                            </div>
                                            <div class="col-4 pl-25 pr-25">
                                                <label for="email-id-column">Amount</label>
                                                <div class="form-group">
                                                    <input type="text" id="amount" name="amount"
                                                        class="form-control" placeholder="0" readonly="readonly"
                                                        required />
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-6 pl-25 pr-25">

                                                <label for="">Select Employee *</label>
                                                <div class="demo-inline-spacing mb-1" style="margin-top: -16px;">
                                                    <div class="custom-control custom-control-primary custom-radio">
                                                        <input type="radio" id="available" name="filter_employee"
                                                            class="custom-control-input" value="available" checked />
                                                        <label class="custom-control-label"
                                                            for="available">Available</label>
                                                    </div>
                                                    <div class="custom-control custom-control-success custom-radio">
                                                        <input type="radio" id="inducted" name="filter_employee"
                                                            class="custom-control-input" value="inducted" disabled />
                                                        <label class="custom-control-label"
                                                            for="inducted">Inducted</label>
                                                    </div>
                                                    <div class="custom-control custom-control-info custom-radio">
                                                        <input type="radio" id="all" name="filter_employee"
                                                            class="custom-control-input" value="all" />
                                                        <label class="custom-control-label" for="all">All</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="employee_id"
                                                        id="employee_id" aria-label="Default select example" required>

                                                    </select>
                                                </div>
                                            </div> --}}
                                            <div class="col-6 pl-25 pr-25 mt-auto">
                                                <label for="">Select Job Type *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="job_type_id"
                                                        id="job" aria-label="Default select example" required>

                                                        @foreach ($job_types as $job_type)
                                                            <option value="{{ $job_type->id }}"
                                                                {{ $job_type->name == 'core' ? 'selected' : '' }}>
                                                                {{ $job_type->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>

                                            {{-- <div class="col-6 pl-25 pr-25">
                                                <label for="">Select Roster Status *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="roaster_status_id" id="roster" aria-label="Default select example">

                                                        @foreach ($roaster_status as $row)
                                                        <option value="{{ $row->id }}" {{$row->name =='Not published'?'selected':''}}>{{ $row->name }}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div> --}}
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Remarks</label>
                                                <div class="form-group">
                                                    <input type="text" name="remarks" id="remarks"
                                                        class="form-control" placeholder="remarks" />
                                                </div>
                                            </div>
                                            <span id="tableListEmployee">

                                                <div class="col-12 pl-25 pr-25">
                                                    <hr>
                                                    <label for="email-id-column">Select Employee</label>
                                                    <div class="form-group">
                                                        <select name="" id="filterStatus"
                                                            class="float-right form-control">
                                                            <option value="all" selected>All Employees</option>
                                                            <option value="available">Available</option>
                                                            <option value="inducted">Inducted</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-6 pl-25 pr-25">
                                                    <hr>
                                                </div>
                                                {{-- <div class="col-6 pl-25 pr-25 sing_body">
                                                        <label for="email-id-column">Sign Out</label>
                                                        <div class="form-group">
                                                            <input type="text" id="sing_out" name="sing_out" class="form-control pickatime-format sing_body" placeholder="Sign Out Time" />
                                                        </div>
                                                    </div> --}}


                                            </span>
                                        </div>
                                        <div class="col-12 pl-25 pr-25">
                                            <br>
                                            <div id="table-hover-animation">
                                                <div class="table-responsive">
                                                    <table id="eventClickTable"
                                                        class="table table-hover-animation table-bordered ">
                                                        <thead>
                                                            <tr>
                                                                <th><input type="checkbox" id="checkAllID">
                                                                </th>
                                                                <th>Employee Name</th>
                                                                <th>Contact Number</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="eventClickTbody">
                                                            <tr>
                                                                <td><input type="checkbox" class="checkID"
                                                                        value="` + employeeId + `" ` + checkbox_status
                                                                        + `></td>
                                                                <td>` + employee.fname + employee.mname +
                                                                    employee.lname + `</td>
                                                                <td>` + employee.contact_number + `</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
                    <button type="button" class="btn btn-success timekeer-btn" id="addTimekeeperSubmit" disabled><i
                            data-feather='save'></i></button>
                    <button type="button" class="btn btn-success timekeer-btn" id="editTimekeeperSubmit" hidden><i
                            data-feather='check'></i></button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i
                            data-feather='x'></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
