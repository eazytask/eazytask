<div class="modal fade text-left p-md-1 p-0" id="addClient" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" id="myModalLabel17">Add Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <div class="bs-stepper horizontal-wizard-example linear">
                                            <div class="bs-stepper-header">
                                                <div class="step active" data-target="#account-details">
                                                    <button type="button" class="step-trigger" aria-selected="true">
                                                        <span class="bs-stepper-box">1</span>
                                                        <span class="bs-stepper-label">
                                                            <span class="bs-stepper-title">Personal Info</span>
                                                            <span class="bs-stepper-subtitle">Add Personal Info</span>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="line">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right font-medium-2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                                </div>
                                                <div class="step" data-target="#personal-info">
                                                    <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
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
                                                <div id="account-details" class="content active">
                                                    <div class="content-header">
                                                        <h5 class="mb-0">Personal Info</h5>
                                                        <small class="text-muted">Add Personal Info</small>
                                                    </div>
                                                    <form enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col-md-6 col-6">
                                                                <div class="mb-3">
                                                                    <label for="first-name-column">Client Name *</label>
                                                                    <input type="text" class="form-control" placeholder="John Deo" name="cname" id="cname" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="mb-3">
                                                                    <label for="first-name-column">Email *</label>
                                                                    <input type="email" class="form-control" placeholder="john@site.com" name="cemail" id="cemail" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="mb-3">
                                                                    <label for="last-name-column">Contact Number *</label>
                                                                    <input type="text" minlength="10" maxlength="10" class="form-control" placeholder="+12016601294" name="cnumber" id="cnumber" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="mb-3">
                                                                    <label for="company-column">Avatar</label>
                                                                    <input type="file" class="form-control" placeholder="Avatar" onchange="encodeImageFileAsURL(this)" />
                                                                    <input type="text" id="cimage" name="file" hidden>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="mb-3">
                                                                    <label for="email-id-column">Contact Person *</label>
                                                                    <input type="text" class="form-control" name="cperson" id="cperson" placeholder="John Wick" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <label for="email-id-column">Select Status *</label>
                                                                <div class="mb-3" id="status_div">
                                                                    <select class="form-control select2" name="status" id="status" aria-label="Default select example" required>
                                                                        <option value="" disabled selected hidden>Please Choose...</option>
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
                                                        <button type="button" class="btn btn-success btn-next">
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
                                                                <div class="mb-3">
                                                                    <label for="email-id-column">Street*</label>
                                                                    <input type="text" class="form-control" name="caddress" id="caddress" placeholder="203 Grayson Village Apt. 051" required />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-12">
                                                                <div class="mb-3">
                                                                    <label for="email-id-column">Suburb*</label>
                                                                    <input type="text" id="suburb" class="form-control" name="suburb" value="91 Swanston Street" placeholder="91 Swanston Street" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="mb-3">
                                                                    <label for="email-id-column">Post Code*</label>
                                                                    <input type="number" minlength="4" maxlength="4" class="form-control" name="cpostal_code" id="cpostal_code" placeholder="5025" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <div class="mb-3">
                                                                    <label for="email-id-column">State *</label>
                                                                    <input type="text" class="form-control" name="cstate" id="cstate" placeholder="South Carolina" required />
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
                                            <div class="d-flex justify-content-between p-1" id="buttom_bar" style="display:none !important">
                                                <button type="button" class="btn btn-outline-dark waves-effect" data-dismiss="modal">
                                                    <span class="align-middle d-sm-inline-block d-none">Cancel</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-sm-25 mr-0"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                                <button type="button" class="btn bg-success btn-submit waves-effect waves-float waves-light">
                                                    <span class="align-middle d-sm-inline-block d-none">Update</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check align-middle mr-sm-25 mr-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
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
@push('scripts')
    @include('components.select2')
@endpush
@push('styles')
    <style>
        .horizontal-wizard{
            margin-bottom: 0px !important;
        }
        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: 2.458rem;
            padding: 5px;
            border: 1px solid #D8D6DE;
        }
        .select2-container--classic .select2-selection--single .select2-selection__arrow b, .select2-container--default .select2-selection--single .select2-selection__arrow b {
            background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23d8d6de\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' class=\'feather feather-chevron-down\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E');
            background-size: 18px 14px, 18px 14px;
            background-repeat: no-repeat;
            height: 1rem;
            padding-right: 1.5rem;
            margin-left: 0;
            margin-top: 0;
            left: -8px;
            border-style: none;
        }
        #status_div > div > div{
            display: flex;
            flex-direction: column;
        }
        #status_div > div > div > span{
            order: 1;
        }
        #status_div > div > div > select{
            order: 2;
        }
        label#status-error{
            order: 3;
        }
    </style>
@endpush