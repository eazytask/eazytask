<div class="modal fade text-left p-md-1 p-0" id="userAddTimeKeeper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-2">
                <h5 class="modal-title">New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="lat" class="lat" hidden>
                <input type="text" name="lon" class="lon" hidden>
                <section id="multiple-column-form2">
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

                        <div class="col-md-12 col-12">
                            <label for="">Select Venue *</label>
                            <div class="mb-3">
                                <select class="form-control select2" name="project_id" id="project-select"
                                    aria-label="Default select example" required>
                                    <option value="" disabled selected hidden>Please Choose...
                                    </option>
                                    @foreach ($projects as $project)
                                        @php
                                        $json = json_encode($project->toArray(), false);
                                        @endphp
                                        <option value="{{ $project->id }}" lat="{{ $project->lat }}" lon="{{ $project->lon }}">{{ $project->pName }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <label for="email-id-column">Amount Per Hour *</label>
                            <div class="mb-3">
                                <input type="number" id="rate" name="ratePerHour"
                                    class="form-control reactive" placeholder="0" required />
                            </div>
                        </div>

                        <div class="col-md-12 col-12">
                            <label for="">Select Job Type *</label>
                            <div class="mb-3">
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
                        <div class="col-md-12 col-12">
                            <label for="email-id-column">Remarks</label>
                            <div class="mb-3">
                                <input type="text" name="remarks" id="remarks"
                                    class="form-control" placeholder="remarks"/>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success timekeer-btn check-location" >Start Now</button>
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Discard</button>
            </div>
        </div>
    </div>
</div>
