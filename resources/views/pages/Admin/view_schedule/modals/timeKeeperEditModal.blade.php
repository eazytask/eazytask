<div class="modal fade text-left p-md-1 p-0" data-backdrop="static" data-keyboard="false" id="editTimeKeeper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-2">
                <h5 class="modal-title" id="myModalLabel17">Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form action="{{ route('view-update-timekeeper') }}" method="POST" id="newModalForm">
                <input type="text" id="timepeeper_id" name="timepeeper_id" value="" hidden>
                <input type="text" class="employee_id" name="employee_id" value="" hidden>
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
                                            <div class="col-6 py-2">
                                                <label for="">Select Employee *</label>
                                                <div class="mb-3">
                                                    <select class="form-control select2" name="employee_id" id="employee_id" aria-label="Default select example" disabled>
                                                        <option value="{{ old('employee_id') }}" disabled selected hidden>Please Choose...</option>
                                                        @foreach ($employees as $employee)
                                                        @php
                                                            $emp= $employee;
                                                        @endphp
                                                        <option value="{{ $employee->id }}">
                                                        {{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-6 pl-25 pr-25">
                                                <label for="">Select Venue *</label>
                                                <div class="mb-3">
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
                                            
                                            <div class="col-12 pl-25 pr-25">
                                                <label for="email-id-column">Roster Date *</label>
                                                <div class="mb-3">
                                                    <input type="text" id="roaster_date" name="roaster_date" disabled required class="form-control format-picker" placeholder="Roster Date" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift Start *</label>
                                                <div class="mb-3">
                                                    <input type="text" disabled id="shift_start" class="form-control pickatime-format"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Shift End *</label>
                                                <div class="mb-3">
                                                    <input type="text" disabled id="shift_end" class="form-control pickatime-format"/>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Sign In</label>
                                                <div class="mb-3">
                                                    <input type="text" disabled id="sign_in" class="form-control pickatime-format"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">Sign Out</label>
                                                <div class="mb-3">
                                                    <input type="text" disabled id="sign_out" class="form-control pickatime-format"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">App. Start *</label>
                                                <div class="mb-3">
                                                    <input type="text" id="app_start" name="app_start" required class="form-control pickatime-format" placeholder="App Start Time" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">App. End *</label>
                                                <div class="mb-3">
                                                    <input type="text" id="app_end" name="app_end" required class="form-control pickatime-format" placeholder="Shift End Time" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">App. Duration</label>
                                                <div class="mb-3">
                                                    <input type="text" id="app_duration" name="app_duration" class="form-control" placeholder="App Duration" readonly="readonly" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <label for="email-id-column">App. Rate *</label>
                                                <div class="mb-3">
                                                    <input type="number" id="app_rate" name="app_rate" class="form-control reactive" placeholder="0" required />
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">App. Amount</label>
                                                <div class="mb-3">
                                                    <input type="text" id="app_amount" name="app_amount" class="form-control" placeholder="0" readonly="readonly" />
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Remarks</label>
                                                <div class="mb-3">
                                                    <input type="text" name="remarks" id="remarks" class="form-control" placeholder="remarks" readonly/>
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
                    <button type="button" class="btn btn-success timekeer-btn">
                        Update
                    </button>
                    <button type="button" class="btn btn-outline-dark edit-discard" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
