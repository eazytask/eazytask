<div class="modal fade text-left p-md-1 p-0" id="userAddTimeKeeper" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">New Schedule</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- <form action="{{ route('user-store-timekeeper') }}" method="POST" id="newModalForm"> -->
                <!-- <input type="text" id="timekeeper_id" name="timekeeper_id" value="" hidden> -->
                <!-- @csrf -->
                <div class="modal-body">
                    <section id="multiple-column-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <label for="email-id-column">Amount Per Hour<span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input type="number" id="rate" name="ratePerHour"
                                                        class="form-control reactive" placeholder="0" required />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 col-12">
                                                <label for="">Select Job Type</label>
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

                                            <div class="col-md-12 col-12">
                                                <label for="email-id-column">Remarks<span
                                                        class="text-danger">*</span></label>
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
                    <button type="button" class="btn btn-success timekeer-btn">Start Now</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Discard</button>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>
