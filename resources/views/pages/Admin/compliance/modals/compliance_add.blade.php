<div class="modal fade" id="addCompliance">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" id="compliance_add_form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="data_id" id="id">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" >Add Compliance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row d-flex align-items-end">
                    <div class="col-md-6 col-6">
                        <label for="_compliance">
                            Compliance Name *
                        </label>
                        <div class="mb-3">
                            <select class="form-control comp select2" name="compliance" id="compliance" required>
                                <option value="">
                                    Please Choose...
                                </option>
                                @foreach ($compliances as $row)
                                    <option data-isrequired="{{ $row->is_required }}" value="{{ $row->id }}">
                                        {{ $row->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="mb-3">
                            <label for="certificate_num">
                                Certificate NO. *
                            </label>
                            <input type="text" id="certificate_num" class="form-control comp" required name="certificate_no" placeholder="certificate no" />
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <label for="emp_name">
                            Employee Name *
                        </label>
                        <div class="mb-3">
                            <select class="form-control comp select2" required name="emp_id" id="emp_name">
                                <option value="">
                                    Please Choose...
                                </option>
                                @foreach ($employees as $row)
                                    <option
                                        value="{{ $row->id }}">
                                        {{ $row->fname.' '.$row->mname.' '.$row->lname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <label for="expire">Expire Date *</label>
                        <div class="mb-3">
                            <input type="text" id="expire" required class="form-control comp exp_date" name="expire_date" placeholder="expire date" />
                        </div>
                    </div>
                    <div class="col-md-6 col-6 m-auto">
                        <label for="comment">Comment</label>
                        <div class="mb-3">
                            <input type="textarea" class="form-control" id="comment" name="comment" placeholder="comment" />
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="mb-3">
                            <label for="company-column">
                                Document Image Upload
                            </label>
                            <input type="file" class="form-control" name="document" id="document" placeholder="Avatar"  />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="update" class="btn btn-success btn-submit">Update changes</button>
                <button type="submit" id="store" class="btn btn-success btn-submit">Save changes</button>
            </div>
        </form>
    </div>
</div>