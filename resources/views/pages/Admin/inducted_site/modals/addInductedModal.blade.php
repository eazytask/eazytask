<div class="modal fade text-left" id="addInduction" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-2">
                <h5 class="modal-title" id="myModalLabel17">Add Induction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0">

                                <div class="card-body pb-0">
                                    <form class="form" action="{{ route('store-induction') }}" method="POST"
                                        id="newModalForm">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="row">

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="mb-3">
                                                    <label for="first-name-column">Employee Name *</label>
                                                    <select class="form-control select2" name="employee_id" id="employee_id"
                                                        aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($employees as $employee)
                                                        @php
                                                            $emp= $employee;
                                                        @endphp
                                                                <option value="{{ $employee->id }}">
                                                                {{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}
                                                                </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="mb-3">
                                                    <label for="first-name-column">Venue Name*</label>
                                                    <select class="form-control select2" name="project_id" id="project_id"
                                                        aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">
                                                    {{ $project->pName }}
                                                                </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="mb-3">
                                                    <label for="email-id-column">Induction Date *</label>
                                                    <input type="text" class="form-control format-picker"
                                                        name="induction_date" id="induction_date" placeholder="Select induction date"
                                                        required />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="mb-3">
                                                    <label for="company-column">Remarks</label>
                                                    <input type="text" class="form-control" name="remarks" id="remarks"
                                                        placeholder="Enter remarks"/>
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
                    Save
                </button>
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
