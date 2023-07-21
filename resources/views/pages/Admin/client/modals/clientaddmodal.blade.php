<div class="modal fade text-left p-md-1 p-0" id="addClient" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Add Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0">

                                <div class="pb-0">
                                    <!-- <form class="form" id="newModalForm"> -->
                                    <!-- Horizontal Wizard -->
                                    <section class="horizontal-wizard">
                                        <div class="bs-stepper horizontal-wizard-example">
                                            <div class="bs-stepper-header">
                                                <div class="step" data-target="#account-details">
                                                    <button type="button" class="step-trigger">
                                                        <span class="bs-stepper-box">1</span>
                                                        <span class="bs-stepper-label">
                                                            <span class="bs-stepper-title">Personal Info</span>
                                                            <span class="bs-stepper-subtitle">Add Personal Info</span>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="line">
                                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                                </div>
                                                <div class="step" data-target="#personal-info">
                                                    <button type="button" class="step-trigger">
                                                        <span class="bs-stepper-box">2</span>
                                                        <span class="bs-stepper-label">
                                                            <span class="bs-stepper-title">Address</span>
                                                            <span class="bs-stepper-subtitle">Add Address</span>
                                                        </span>
                                                    </button>
                                                </div>
                                                <!-- <div class="line">
                                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                                </div> -->
                                            </div>
                                            <div class="bs-stepper-content">
                                                <div id="account-details" class="content">
                                                    <div class="content-header">
                                                        <h5 class="mb-0">Personal Info</h5>
                                                        <small class="text-muted">Add Personal Info</small>
                                                    </div>
                                                    <form enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="first-name-column">Client Name *</label>
                                                                    <input type="text" class="form-control" placeholder="Client Name" name="cname" id="cname" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="first-name-column">Email *</label>
                                                                    <input type="email" class="form-control" placeholder="Email" name="cemail" id="cemail" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="last-name-column">Contact Number *</label>
                                                                    <input type="text" minlength="10" maxlength="10" class="form-control" placeholder="Contact Number" name="cnumber" id="cnumber" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="company-column">Avatar</label>
                                                                    <input type="file" class="form-control" placeholder="Avatar" onchange="encodeImageFileAsURL(this)" />
                                                                    <input type="text" id="cimage" name="file" hidden>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Contact Person *</label>
                                                                    <input type="text" class="form-control" name="cperson" id="cperson" placeholder="Contact Person" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <label for="email-id-column">Select Status *</label>
                                                                <div class="form-group">
                                                                    <select class="form-control select2" name="status" id="status" aria-label="Default select example" required>
                                                                        <option value="" disabled selected hidden>Please Choose...
                                                                        </option>
                                                                        <option value="1">Active</option>
                                                                        <option value="2">Inactive</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-outline-secondary btn-prev" disabled>
                                                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                        </button>
                                                        <button type="button" class="btn btn-gradient-primary btn-next">
                                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="personal-info" class="content">
                                                    <div class="content-header">
                                                        <h5 class="mb-0">Address</h5>
                                                        <small>Enter Address</small>
                                                    </div>
                                                    <form>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id">
                                                        <div class="row">

                                                            <div class="col-md-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Street*</label>
                                                                    <input type="text" class="form-control" name="caddress" id="caddress" placeholder="Address" required />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Suburb*</label>
                                                                    <input type="text" id="suburb" class="form-control" name="suburb" value="91 Swanston Street" placeholder="Address" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Post Code*</label>
                                                                    <input type="number" minlength="4" maxlength="4" class="form-control" name="cpostal_code" id="cpostal_code" placeholder="Postal Code" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">State *</label>
                                                                    <input type="text" class="form-control" name="cstate" id="cstate" placeholder="State" required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-gradient-primary btn-prev">
                                                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                        </button>
                                                        <button type="button" class="btn btn-success btn-submit">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <form enctype="multipart/form-data" id="full_form">
                                            </form>
                                            <div class="d-flex justify-content-between p-1" id="buttom_bar">
                                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">
                                                    <span class="align-middle d-sm-inline-block d-none">Cancel</span>
                                                    <i data-feather="x" class="align-middle mr-sm-25 mr-0"></i>
                                                </button>
                                                <button type="button" class="btn bg-gradient-success btn-submit">
                                                    <span class="align-middle d-sm-inline-block d-none">Update</span>
                                                    <i data-feather="check" class="align-middle mr-sm-25 mr-0"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- /Horizontal Wizard -->

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>