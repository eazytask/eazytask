<div class="modal fade text-left p-md-1 p-0" id="pinModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title  text-white" id="myModalLabel17">Sign In</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mb-0" id="pin_div">
                    <div class="card-body">
                        <h4 class="card-title mb-1" id="employee-name"></h4>
                        <p class="card-text mb-2">Enter your personal pin code.</p>

                        <form class="auth-forgot-password-form mt-2" id="pinForm">
                            @csrf
                            <div class="form-group">
                                <!-- <label for="pin" class="form-label">Pin Code</label> -->
                                <input type="text" id="user_id" name="user_id" hidden />
                                <input type="text" class="employee_id" name="employee_id" hidden />
                                <input type="text" class="project_id" name="project_id" hidden />
                                <input type="password" class="form-control" minlength="4" maxlength="4" id="pin" name="pin" placeholder="1234" required />
                            </div>
                            <button class="btn btn-gradient-primary btn-block" type="button" onclick="checkPin()">Confirm</button>
                        </form>
                    </div>
                </div>
                <div class="card mb-0" id="sign_div" style="display: none;">
                    <h3 class="mb-3 text-center" id="clock"></h3>
                    <div class="row" id="roaster_div">

                        <!-- <div class="col-lg-4 col-md-4">
                            <div class="card plan-card border-primary text-center">
                                <div class="justify-content-between align-items-center pt-75">

                                    <div class="card-body">
                                        <p class="mb-0 text-muted">You have no scheduled shift at this time</p>
                                        <button class="btn btn-gradient-primary text-center btn-block waves-effect waves-float waves-light" data-toggle="modal" data-target="#userAddTimeKeeper">Start unscheduled shift</button>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="card plan-card border-primary text-center">
                                <div class="justify-content-between align-items-center p-75">
                                    <p id="countdown0" class="mb-1 text-danger" hidden="">0h, 50minutes </p>
                                    <h3 id="working0" class="mb-0">0h, 50min.</h3>

                                    <p id="shift-end-in0" class="mb-1">Shift ending in 3 hours, 9 minutes </p>


                                    <div class="badge badge-light-primary text-uppercase">
                                        <h6>Auburn central</h6>
                                    </div>
                                    <p class="mb-1">Shift time, 15:58 - 19:58 </p>


                                    <form action="/home/sign/out/timekeeper" method="post">
                                        <input type="hidden" name="_token" value="xkYyMKZDfZO8io58sknVcHwGOFhxZnHZXcJxmK2U"> <input type="text" name="lat" class="lat" hidden="">
                                        <input type="text" name="lon" class="lon" hidden="">
                                        <select class="form-control select2" name="project_id" id="project-select" hidden="">
                                            <option lat="" lon="" selected="">Auburn central</option>
                                        </select>
                                        <input type="text" name="timekeeper_id" id="timekeeperID" value="4382" hidden="">
                                        <button type="button" class="btn btn-gradient-primary text-center btn-block check-location waves-effect waves-float waves-light">Sign Out</button>
                                    </form>

                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>