<div class="modal fade text-left p-md-1 p-0" id="addCompliance" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-light" id="myModalLabel17">New Schedule</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="complianceForm" data-ajax="false">
                @csrf
                <div class="modal-body pb-5">
                    <section id="multiple-column-form" class="pb-5">
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
                                                <label for="">Compliance Name *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="compliance_id" id="compliance_id" aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($compliances as $compliance)
                                                        <option value="{{ $compliance->id }}">{{ $compliance->name }}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-25 pr-25">
                                                <label for="email-id-column">Licence NO. *</label>
                                                <div class="form-group">
                                                    <input type="text" name="certificate_no" id="certificate_no" class="form-control" placeholder="license number" required/>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-25 pr-25">
                                                <label for="email-id-column">Expire Date *</label>
                                                <div class="form-group">
                                                    <input type="date" id="expire_date" name="expire_date" placeholder="Expire Date" required class="form-control flatpickr-basic" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 pl-25 pr-25">
                                                <label for="email-id-column">Comment</label>
                                                <div class="form-group">
                                                    <input type="text" name="comment" id="comment" class="form-control" placeholder="comment" />
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
                    <button type="button" class="btn btn-success timekeer-btn" id="addComplianceSubmit" onclick="complianceAddFunc()"><i data-feather='save'></i></button>
                    <button type="button" class="btn btn-success timekeer-btn" id="editComplianceSubmit" onclick="complianceAddFunc()" hidden><i data-feather='check'></i></button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i data-feather='x'></i></button>
                </div>
            </form>
        </div>
    </div>
</div>