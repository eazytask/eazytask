<div class="modal fade text-left p-md-1 p-0" id="addCompany" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Add Company</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Company And Login Info</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{ route('company-store') }}" method="POST" enctype="multipart/form-data" id="company-store">
                                        @csrf
                                        <input type="hidden" name="id" id="id">

                                        <div class="row">
                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for="first-name-column">First Name</label>
                                                    <input type="text" id="name" class="form-control" placeholder="First Name" name="name" required />
                                                    @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for="first-name-column">Middle Name</label>
                                                    <input type="text" id="mname" class="form-control" placeholder="Middle name" name="mname" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for="last-name-column">Last Name</label>
                                                    <input type="text" id="lname" class="form-control" placeholder="Last Name" name="lname" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="email-id-column">Email</label>
                                                    <input type="email" id="email" class="form-control" name="email" placeholder="Email" required />
                                                    @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <label for="">Status</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="status" id="status" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                    @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>


                                            @php
                                            $is_required = Auth::user()->company->company_type->id==1?'required':'';
                                            @endphp
                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="company-column">Master License</label>
                                                    <input type="text" class="form-control" id="master_license" name="master_license" placeholder="Master License No" {{$is_required}} />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <label for="company-column">Master License Expire</label>
                                                <div class="form-group">
                                                    <input type="date" class="form-control flatpickr-basic" id="expire_date" name="expire_date" placeholder="Master Expire Date" {{$is_required}} />
                                                </div>
                                            </div>


                                            <div class="card-header col-12">
                                                <h4 class="card-title">Company Info</h4>
                                            </div>
                                            <hr>

                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="company-column">Company</label>
                                                    <input type="text" id="company" class="form-control" name="company" placeholder="Company" required />
                                                    @error('company')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="company-column">Company Contact Number</label>
                                                    <input type="number" minlength="10" maxlength="10" id="company_contact" class="form-control" name="company_contact" placeholder="Company" required />
                                                    @error('company_contact')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="company-column">Avatar</label>
                                                    <input type="file" id="image" class="form-control" name="file" placeholder="Company Image" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="company-column">Company Code</label>
                                                    <input type="text" id="company_code" class="form-control" name="company_code" placeholder="Company Code" required />
                                                    @error('company_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6">
                                                <label for="">Company Type</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="company_type_id" id="company_type_id" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach($company_types as $row)
                                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6" id="pass_div">
                                                <label for="company-column">Password *</label>

                                                <div class="input-group input-group-merge form-password-toggle">
                                                    <input type="password" minlength="8" class="form-control" id="password" name="password" placeholder="set password" autocomplete="current-password">

                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
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
                <button type="submit" class="btn btn-success" id="updatebtn"><i data-feather='check'></i></button>
                <button type="submit" class="btn btn-success" id="savebtn"><i data-feather='save'></i></button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i data-feather='x'></i></button>
            </div>
            </form>
        </div>
    </div>
</div>