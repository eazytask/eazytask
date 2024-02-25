@extends('layouts.Admin.master')
@php
if(!Auth::user()->image){
    Auth::user()->image= 'images/app/no-image.png';
}
@endphp

@section('admin_page_content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ asset('app-assets/velzon/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="@if (Auth::user()->image != ''){{ 'https://api.eazytask.au/' . Auth::user()->image }}@else{{ URL::asset('app-assets/velzon/images/users/avatar-1.jpg') }}@endif"
                                class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <form class="form" id="image-form" action="{{route('admin-profile-photo-update')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{Auth::user()->id}}">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </label>
                                </form>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">
                            {{ Auth::user()->name }} {{ Auth::user()->mname }} {{ Auth::user()->lname }}
                        </h5>
                        <p class="text-muted mb-0">
                            @if (auth()->user()->company_roles->contains('role', 2))
                                Admin
                            @elseif(auth()->user()->company_roles->contains('role', 4))
                                Supervisor
                            @elseif(auth()->user()->company_roles->contains('role', 5))
                                Operation
                            @elseif(auth()->user()->company_roles->contains('role', 6))
                                Manager
                            @elseif(auth()->user()->company_roles->contains('role', 7))
                                Account
                            @else
                                User
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Change Password
                            </a>
                        </li>
                        @if(auth()->user()->company_roles->contains('role',3))
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePin" role="tab">
                                <i class="far fa-user"></i>
                                Change Pin
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form id="profile-form" action="{{ route('admin-profile-update') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ Auth::user()->company_roles->first()->company->id }}">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-e-mail">E-mail *</label>
                                            <input type="email" value="{{ Auth::user()->email }}" class="form-control" id="account-e-mail" name="email" placeholder="Email" required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-username">First Name *</label>
                                            <input type="text" class="form-control" id="account-username" value="{{ Auth::user()->name }}" name="name" placeholder="Middle Name" required />
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-username">Middle Name</label>
                                            <input type="text" class="form-control" name="mname" value="{{ Auth::user()->mname }}" placeholder="Middle Name" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-username">Last Name *</label>
                                            <input type="text" class="form-control" name="lname" value="{{ Auth::user()->lname }}" placeholder="Last Name" required />
                                        </div>
                                    </div>
                                    
                                    @if(auth()->user()->company_roles->contains('role',2))
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-e-mail">Company Name *</label>
                                            <input type="text" value="{{ Auth::user()->company_roles->first()->company->company }}" class="form-control" id="account-e-mail" name="company" placeholder="Company Name" required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-e-mail">Company Contact Number *</label>
                                            <input type="number" value="{{ Auth::user()->company_roles->first()->company->company_contact }}" class="form-control" id="account-e-mail" name="company_contact" placeholder="Company Contact Number" required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-e-mail">Company Code *</label>
                                            <input type="text" value="{{ Auth::user()->company_roles->first()->company->company_code }}" class="form-control" disabled />
                                        </div>
                                    </div>

                                    @else
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-e-mail">Company Name *</label>
                                            <input type="text" value="{{ Auth::user()->company_roles->first()->company->company }}" class="form-control" id="account-e-mail" name="company" placeholder="Company Name" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="account-e-mail">Company Contact Number *</label>
                                            <input type="number" value="{{ Auth::user()->company_roles->first()->company->company_contact }}" class="form-control" id="account-e-mail" name="company_contact" placeholder="Company Contact Number" disabled />
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="contact">Contact *</label>
                                            <input type="number" name="contact" value="{{Auth::user()->employee->contact_number}}" id="contact" class="form-control" placeholder="Enter contact number" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="birth_date">Date of Birth *</label>
                                            <input type="text" name="date_of_birth" value="{{Auth::user()->employee->date_of_birth}}" id="birth_date" class="form-control form-picker" placeholder="Enter date of birth" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="street">Street *</label>
                                            <input type="text" name="street" value="{{Auth::user()->employee->address}}" id="street" class="form-control" placeholder="Enter street" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="suburb">Suburb *</label>
                                            <input type="text" name="suburb" value="{{Auth::user()->employee->suburb}}" id="suburb" class="form-control" placeholder="Enter suburb" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="state">State *</label>
                                            <input type="text" name="state" value="{{Auth::user()->employee->state}}" id="state" class="form-control" placeholder="Enter state" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-2">
                                            <label for="postal_code">Post Code *</label>
                                            <input type="number" name="postal_code" value="{{Auth::user()->employee->postal_code}}" id="postal_code" class="form-control" placeholder="Enter postal code" required>
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Updates</button>
                                            <a href="{{url()->previous()}}" type="button" class="btn btn-soft-success">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form id="change-password-form" action="{{ url('/admin/company/user-password/change-password-store') }}" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="account-old-password">Old Password *</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input name="old_password" class="form-control" placeholder="Enter current password" type="password" required>
                                                @error('old_password')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="account-new-password">New Password *</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input name="new_password" class="form-control" type="password" placeholder="Enter new password" required>
                                                @error('new_password')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="account-retype-new-password">Retype New Password *</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input name="password_confirmation" class="form-control" type="password" placeholder="Confirm password" required>
                                                @error('password_confirmation')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Change Password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePin" role="tabpanel">
                            <form id="change-pin-form" action="{{ route('user-change-pin-store') }}" method="POST" id="pinForm">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="account-old-password">Current Password *</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input name="old_password" class="form-control" type="password" required placeholder="Enter Current Password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="account-new-pin">New Pin (4 digit) *</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input name="new_pin" class="form-control" placeholder="Enter New Pin" type="password" minlength="4" maxlength="4" pattern="[0-9]*" inputmode="numeric" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-2">
                                            <label for="account-retype-new-pin">Retype New Pin *</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input name="pin_confirmation" class="form-control" placeholder="Confirm Pin" pattern="[0-9]*" minlength="4" maxlength="4" inputmode="numeric" type="password" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Change Pin</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!--end tab-pane-->

                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@push('scripts')

    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("#image-form").validate()
            $("#profile-form").validate()
            $("#change-password-form").validate()
            $("#change-pin-form").validate()
            $("#profile-img-file-input").on('change', function(){
                $('#image-form').submit();
            });
            $("#birth_date").flatpickr({
                dateFormat: "Y-m-d"
            });
        })
    </script>
    <script src="{{ URL::asset('app-assets/velzon/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('app-assets/velzon/js/app.js') }}"></script>
@endpush