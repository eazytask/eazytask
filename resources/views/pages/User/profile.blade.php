@extends('layouts.Admin.master')
@php
if(!Auth::user()->employee->image){
    Auth::user()->employee->image = 'images/app/no-image.png';
}
@endphp

@section('admincontent')
@include('sweetalert::alert')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Profile Settings</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home/dashboard/{{ Auth::user()->employee->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Settings</a>
                        </li>
                        <li class="breadcrumb-item active"> Profile Settings
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="content-body">
    <!-- account setting page -->
    <section id="page-account-settings">
        <div class="row">
            <!-- left menu section -->
            <div class="col-md-3 mb-2 mb-md-0">
                <ul class="nav nav-pills flex-column nav-left">
                    <!-- general -->
                    <li class="nav-item">
                        <a class="nav-link active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                            <i data-feather="user" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">General</span>
                        </a>
                    </li>
                    <!-- change password -->
                    <li class="nav-item">
                        <a class="nav-link" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
                            <i data-feather="lock" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">Change Password</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="account-pill-pin" data-toggle="pill" href="#account-vertical-pin" aria-expanded="false">
                            <i data-feather="key" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">Change Pin</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!--/ left menu section -->

            <!-- right content section -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- general tab -->
                            <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
                                <!-- header media -->
                                <div class="media">
                                    <a href="javascript:void(0);" class="mr-25">
                                        <img src="{{ 'https://api.eazytask.au/'.Auth::user()->employee->image }}" alt="Avatar" height="80" width="80" />

                                    </a>

                                    <!-- upload and reset button -->
                                    <div class="media-body mt-75 ml-1">
                                        <!-- <label for="account-upload" class="btn btn-sm btn-gradient-primary mb-75 mr-75">Upload</label>
                                                                                                                                                                                                        <input type="file" id="account-upload" hidden accept="image/*" /> -->
                                        <a href="#" data-toggle="modal" data-target="#editProfile" class="btn btn-sm btn-outline-primary mb-75">Edit Profile Photo</a>
                                        @include('pages.User.profileEditModal')
                                        <p>Allowed JPG, GIF or PNG. Max size of 800kB</p>
                                    </div>
                                    <!--/ upload and reset button -->
                                </div>
                                <!--/ header media -->

                                <!-- form -->
                                <form action="{{ route('user-profile-update') }}" method="POST" class="mt-2" id="userProfile">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-username">First Name</label>
                                                <input type="text" class="form-control" id="account-username" value="{{ Auth::user()->employee->fname }}" name="name" placeholder="Middle Name" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-username">Middle Name</label>
                                                <input type="text" class="form-control" name="mname" value="{{ Auth::user()->employee->mname }}" placeholder="Middle Name" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-username">Last Name</label>
                                                <input type="text" class="form-control" name="lname" value="{{ Auth::user()->employee->lname }}" placeholder="Last Name" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">E-mail</label>
                                                <input type="email" value="{{ Auth::user()->employee->email }}" class="form-control" id="account-e-mail" name="email" placeholder="Email" disabled />
                                            </div>
                                        </div>
                                        <!-- <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="account-e-mail">Company Code</label>
                                                    <input type="text" value="{{ Auth::user()->employee->company_code }}"
                                                        class="form-control" id="account-e-mail" name="company_code"
                                                        placeholder="Company Name" required />
                                                </div>
                                            </div> -->
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-e-mail">Contact Number</label>
                                                <input type="number" minlength="10" value="{{ Auth::user()->employee->contact_number }}" class="form-control" id="account-e-mail" name="contact_number" placeholder="Contact Number" required />
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <button type="submit" class="btn btn-gradient-primary mt-2 mr-1">Update
                                                Profile</button>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- change password -->
                            <div class="tab-pane fade" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                
                                <form action="{{ route('user-change-password-store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-old-password">Old Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input name="old_password" class="form-control" type="password" value="">
                                                    @error('old_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer">
                                                            <i data-feather="eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-new-password">New Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input name="new_password" class="form-control" type="password" value="">
                                                    @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer">
                                                            <i data-feather="eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-retype-new-password">Retype New Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input name="password_confirmation" class="form-control" type="password" value="">
                                                    @error('password_confirmation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-gradient-primary mr-1 mt-1">Update
                                                Password</button>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="account-vertical-pin" role="tabpanel" aria-labelledby="account-pill-pin" aria-expanded="false">
                                <!-- form -->
                                <form action="{{ route('user-change-pin-store') }}" method="POST" id="pinForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-old-password">Current Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input name="old_password" class="form-control" type="password" required value="">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer">
                                                            <i data-feather="eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-new-pin">New Pin (4 digit)</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input name="new_pin" class="form-control" type="password" minlength="4" maxlength="4" pattern="[0-9]*" inputmode="numeric" value="" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer">
                                                            <i data-feather="eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-retype-new-pin">Retype New Pin</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input name="pin_confirmation" class="form-control" pattern="[0-9]*" minlength="4" maxlength="4" inputmode="numeric" type="password" value="" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-gradient-primary mr-1 mt-1">Update
                                                Pin</button>

                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--/ right content section -->
        </div>
    </section>
    <!-- / account setting page -->

</div>
</div>
<script>
    $(document).ready(function() {
        $('#userProfile').validate()
        $('#pinForm').validate({
            errorPlacement: function(error, element) {
                // Don't show error
            },
        });
    })
</script>
@endsection