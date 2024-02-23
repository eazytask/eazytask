<div class="modal fade text-left p-md-1 p-0" id="eventClick" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" id="myModalLabel17">Employee List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card plan-card p-2">
                    <div class="row">
                        <div class="col-6">
                            <p class="m-0  text-danger h4" id="eventName">Event: Hilsha,</p>
                            <p class="m-0" id="eventShift">Shift-Time: 11-11-22 to 12-11-22</p>
                            <p class="m-0" id="eventRemarks"></p>
                        </div>
                    </div>
                </div>
                <div class="card plan-card p-2" id="hasData">
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-gradient-success text-center border-primary mb-1" disabled
                                id="addToRoaster">Add To Event</button>
                            <button class="btn btn-gradient-primary text-center border-primary mb-1"
                                id="sendNotification">Send Notification</button>
                        </div>
                        <div class="col-6">
                            <select name="" id="filterStatus" class="float-right form-control">
                                <option value="all">All Employees</option>
                                <option value="inducted">Inducted</option>
                                <option value="requested" selected>Requested</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="table-hover-animation">
                    <div class="table-responsive">
                        <table id="eventClickTable" class="table table-hover-animation table-bordered ">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" onclick="checkAllID()" id="checkAllID"></th>
                                    <th>Employee Name</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="eventClickTbody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
