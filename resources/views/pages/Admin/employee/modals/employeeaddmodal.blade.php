<div class="modal fade text-left p-md-1 p-0" id="addEmployee" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Add Employee</h4>
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
                                                            <span class="bs-stepper-title">Address & License</span>
                                                            <span class="bs-stepper-subtitle">Add Info</span>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="line">
                                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                                </div>
                                                <div class="step" data-target="#address-step">
                                                    <button type="button" class="step-trigger">
                                                        <span class="bs-stepper-box">3</span>
                                                        <span class="bs-stepper-label">
                                                            <span class="bs-stepper-title">Compliance</span>
                                                            <span class="bs-stepper-subtitle">Add Employee
                                                                Compliance</span>
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
                                                    <form>
                                                        <div class="row">
                                                            <div class="col-md-4 col-6">
                                                                <div class="form-group">
                                                                    <label for="first-name-column">First Name *</label>
                                                                    <input type="text" id="name"
                                                                        class="form-control" placeholder="First Name"
                                                                        name="fname" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-6">
                                                                <div class="form-group">
                                                                    <label for="first-name-column">Middle Name</label>
                                                                    <input type="text" id="mname"
                                                                        class="form-control" placeholder="Middle name"
                                                                        name="mname" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-6">
                                                                <div class="form-group">
                                                                    <label for="last-name-column">Last Name *</label>
                                                                    <input type="text" id="lname"
                                                                        class="form-control" placeholder="Last Name"
                                                                        name="lname" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Email *</label>
                                                                    <input type="email" id="email"
                                                                        class="form-control" name="email"
                                                                        placeholder="Email" required />
                                                                    @error('email')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6" id="pass_div">
                                                                <label for="company-column">Password</label>

                                                                <div
                                                                    class="input-group input-group-merge form-password-toggle">
                                                                    <input type="password" minlength="8"
                                                                        class="form-control" name="password"
                                                                        placeholder="set password"
                                                                        autocomplete="current-password">

                                                                    @error('password')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <div class="input-group-append"><span
                                                                            class="input-group-text cursor-pointer"><i
                                                                                data-feather="eye"></i></span></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Contact Number
                                                                        *</label>
                                                                    <input type="number" id="contact_number"
                                                                        minlength="10" maxlength="10"
                                                                        class="form-control" name="contact_number"
                                                                        placeholder="Contact Number" required />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="company-column">Date of Birth</label>
                                                                    <input type="date"
                                                                        class="form-control flatpickr-basic"
                                                                        id="date_of_birth" name="date_of_birth"
                                                                        placeholder="Date Of Birth" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="company-column">Avatar</label>
                                                                    <input type="file" class="form-control"
                                                                        id="_image" placeholder="Avatar"
                                                                        onchange="encodeImageFileAsURL(this)" />
                                                                    <input type="text" id="image"
                                                                        name="file" hidden>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <label for="email-id-column">Select Role *</label>
                                                                <div class="form-group">
                                                                    <select class="form-control select2"
                                                                        name="role" id="role"
                                                                        aria-label="Default select example" required>
                                                                        <option value="" disabled selected
                                                                            hidden>Please Choose...
                                                                        </option>
                                                                        <option value="3" selected>Employee
                                                                        </option>
                                                                        <option value="4">Supervisor</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-6">
                                                                <label for="email-id-column">Select Status *</label>
                                                                <div class="form-group">
                                                                    <select class="form-control select2"
                                                                        name="status" id="status" required>
                                                                        <option value="" disabled selected
                                                                            hidden>Please Choose...
                                                                        </option>
                                                                        <option value="1" selected>Active</option>
                                                                        <option value="2">Inactive</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-prev" disabled>
                                                            <i data-feather="arrow-left"
                                                                class="align-middle mr-sm-25 mr-0"></i>
                                                            <span
                                                                class="align-middle d-sm-inline-block d-none">Previous</span>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-gradient-primary btn-next">
                                                            <span
                                                                class="align-middle d-sm-inline-block d-none">Next</span>
                                                            <i data-feather="arrow-right"
                                                                class="align-middle ml-sm-25 ml-0"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="personal-info" class="content">
                                                    <div class="content-header">
                                                        <h5 class="mb-0">Address & License</h5>
                                                        <small>Enter Info</small>
                                                    </div>
                                                    <form>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id">
                                                        <div class="row">
                                                            <div class="col-md-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Street*</label>
                                                                    <input type="text" id="address"
                                                                        class="form-control" name="address"
                                                                        placeholder="Address" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Suburb*</label>
                                                                    <input type="text" id="suburb"
                                                                        class="form-control" name="suburb"
                                                                        placeholder="Address" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">Post Code*</label>
                                                                    <input type="number" id="postal_code"
                                                                        minlength="4" maxlength="4"
                                                                        class="form-control" name="postal_code"
                                                                        value="3381" placeholder="Postal Code"
                                                                        required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="email-id-column">State*</label>
                                                                    <input type="text" id="state"
                                                                        class="form-control" name="state"
                                                                        placeholder="State" required />
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <hr class="invoice-spacing">
                                                            </div>

                                                            @php
                                                                $is_required = Auth::user()->company->company_type->id == 1 ? 'required' : '';
                                                            @endphp
                                                            <div class="col-md-6 col-6">
                                                                <div class="form-group">
                                                                    <label for="company-column">Security License
                                                                        No</label>
                                                                    <input type="text" class="form-control"
                                                                        id="license_no" name="license_no"
                                                                        placeholder="Security License No"
                                                                        {{ $is_required }} />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <label for="company-column">Security License
                                                                    Expire</label>
                                                                <div class="form-group">
                                                                    <input type="date"
                                                                        class="form-control flatpickr-basic"
                                                                        id="license_expire_date"
                                                                        name="license_expire_date"
                                                                        placeholder="License Expire Date"
                                                                        {{ $is_required }} />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-6">
                                                                <label for="company-column">First Aid License
                                                                    No</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"
                                                                        id="first_aid_license"
                                                                        name="first_aid_license"
                                                                        placeholder="First Aid License Number"
                                                                        {{ $is_required }} />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <label for="company-column">First Aid License
                                                                    Expire</label>
                                                                <div class="form-group">
                                                                    <input type="date"
                                                                        class="form-control flatpickr-basic"
                                                                        id="first_aid_expire_date"
                                                                        name="first_aid_expire_date"
                                                                        placeholder="Expire Date"
                                                                        {{ $is_required }} />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button"
                                                            class="btn btn-gradient-primary btn-prev">
                                                            <i data-feather="arrow-left"
                                                                class="align-middle mr-sm-25 mr-0"></i>
                                                            <span
                                                                class="align-middle d-sm-inline-block d-none">Previous</span>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-gradient-primary btn-next">
                                                            <span
                                                                class="align-middle d-sm-inline-block d-none">Next</span>
                                                            <i data-feather="arrow-right"
                                                                class="align-middle ml-sm-25 ml-0"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="address-step" class="content">
                                                    <div class="content-header">
                                                        <h5 class="mb-0">Compliance</h5>
                                                    </div>
                                                    <form>
                                                        <div class="row">
                                                            <div
                                                                class="custom-control custom-control-primary custom-checkbox col-12 mb-2 ml-1">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="checkCompliance" name="has_compliance" />
                                                                <label class="custom-control-label"
                                                                    for="checkCompliance">Add Compliance</label>
                                                            </div>

                                                            <div id="compliance" class="col-12 disablediv">
                                                                <div action="#" class="invoice-repeater">
                                                                    <div data-repeater-list="Compliance"
                                                                        id="compliance_list">
                                                                        <div data-repeater-item class="inner_repeat">
                                                                            <div class="row d-flex align-items-end">
                                                                                <div class="col-md-6 col-6">
                                                                                    <label
                                                                                        for="email-id-column">Compliance
                                                                                        Name *</label>
                                                                                    <div class="form-group">
                                                                                        <select
                                                                                            class="form-control comp"
                                                                                            name="compliance"
                                                                                            id="_compliance"
                                                                                            onchange="checkDocumentUploadRequired();">
                                                                                            <option value="">
                                                                                                Please Choose...
                                                                                            </option>
                                                                                            @foreach ($compliance as $row)
                                                                                                <option
                                                                                                    data-isrequired="{{ $row->is_required }}"
                                                                                                    value="{{ $row->id }}">
                                                                                                    {{ $row->name }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="company-column">Certificate
                                                                                            NO. *</label>
                                                                                        <input type="text"
                                                                                            class="form-control comp"
                                                                                            name="certificate_no"
                                                                                            placeholder="certificate no" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-6">
                                                                                    <label for="company-column">Expire
                                                                                        Date *</label>
                                                                                    <div class="form-group">
                                                                                        <input type="text"
                                                                                            class="form-control comp exp_date"
                                                                                            name="expire_date"
                                                                                            placeholder="expire date" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-6 m-auto">
                                                                                    <label
                                                                                        for="company-column">Comment</label>
                                                                                    <div class="form-group">
                                                                                        <input type="textarea"
                                                                                            class="form-control"
                                                                                            name="comment"
                                                                                            placeholder="comment" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="company-column">Document
                                                                                            Image Upload</label>
                                                                                        <input type="file"
                                                                                            class="form-control comp"
                                                                                            id="_document"
                                                                                            placeholder="Avatar"
                                                                                            onchange="encodeDocumentFileAsURL(this)" />
                                                                                        <input type="text"
                                                                                            id="document"
                                                                                            name="document" hidden>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="mb-50 ml-auto mr-1">
                                                                                    <div class="form-group">
                                                                                        <button style="display: none;"
                                                                                            class="btn btn-outline-danger text-nowrap px-1"
                                                                                            data-repeater-delete
                                                                                            type="button">
                                                                                            <i data-feather="x"
                                                                                                class="mr-25"></i>
                                                                                            <span>Delete</span>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <hr />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <button style="display: none;"
                                                                                class="btn btn-icon btn-gradient-primary mb-2"
                                                                                type="button" data-repeater-create>
                                                                                <i data-feather="plus"
                                                                                    class="mr-25"></i>
                                                                                <span>Add New</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button"
                                                            class="btn btn-gradient-primary btn-prev">
                                                            <i data-feather="arrow-left"
                                                                class="align-middle mr-sm-25 mr-0"></i>
                                                            <span
                                                                class="align-middle d-sm-inline-block d-none">Previous</span>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-success btn-submit">Submit</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <form enctype="multipart/form-data" id="full_form">
                                            </form>
                                            <div class="d-flex justify-content-between p-1" id="buttom_bar">
                                                <button type="button" class="btn btn-outline-dark"
                                                    data-dismiss="modal">
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
