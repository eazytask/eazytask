<div class="modal fade text-left p-md-1 p-0" id="addModal" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" id="myModalLabel17">Add Time Off</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0">

                                <div class="card-body pb-0">
                                    <form class="form" action="/admin/home/myavailability/store" method="POST" id="newModalForm">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
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
                                            <div class="col-md-6 col-12">
                                                    <label for="">Select Employee *</label>
                                                    <div class="mb-3">
                                                        <select class="form-control select2" name="employee_id" id="employee_id"
                                                            aria-label="Default select example" required>
                                                            <option value="" disabled selected hidden>Please Choose...
                                                            </option>
                                                            @foreach ($employees as $employee)
                                                            @php
                                                                $emp= $employee;
                                                            @endphp
                                                            <option value="{{ $employee->id }}">{{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <label for="">Unavailable Type *</label>
                                                    <div class="mb-3">
                                                        <select class="form-control select2" name="leave_type_id" id="leave_type_id"
                                                            aria-label="Default select example" required>
                                                            <option value="">Select Type
                                                            </option>
                                                            @foreach ($leave_types as $row)
                                                                <option value="{{ $row->id }}">{{ $row->name }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                            <!--<div class="col-md-6 col-12">-->
                                            <!--    <div class="mb-3">-->
                                            <!--        <label for="last-name-column">Company Code *</label>-->
                                            <!--        <input type="number"  class="form-control" placeholder="Company Code" name="company_code" required />-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label for="email-id-column">Start Date *</label>
                                                    <input type="text" id="start_date" onchange="allCalculation()" class="format-picker form-control " placeholder="DD-MM-YYYY" name="start_date" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label for="email-id-column">End Date *</label>
                                                    <input type="text" id="end_date" onchange="allCalculation()" class="format-picker form-control" placeholder="DD-MM-YYYY" name="end_date" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label for="end_date">Total Day</label>
                                                    <input type="text" id="total" class="form-control" placeholder="total" disabled/>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label for="first-name-column">Unavailable Reason *</label>
                                                    <input type="text" id="remarks" class="form-control" placeholder="remarks" name="remarks" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <label for="">Status</label>
                                                <div class="mb-3">
                                                    <select class="form-control select2" name="status" id="status"
                                                        aria-label="Default select example" required>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="reject">Reject</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label for="">Time Off Type *</label>
                                                <div class="mb-3">
                                                    <select name="is_leave" required id="is_leave" class="form-control select2">
                                                        <option value="">Select Type</option>
                                                        <option value="0">Unavailability</option>
                                                        <option value="1">Leave</option>
                                                    </select>
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
                <button type="submit" class="btn btn-success" id="updatebtn">
                    Update
                </button>
                <button type="submit" class="btn btn-success" id="savebtn">
                    Submit
                </button>
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
              </form>
        </div>
    </div>
</div>
