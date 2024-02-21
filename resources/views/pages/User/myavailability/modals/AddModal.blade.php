<div class="modal fade text-left p-md-1 p-0" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" id="myModalLabel17">Add Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <section id="multiple-column-form">
                  <div class="row">
                      <div class="col-12">
                          <div class="card">

                              <div class="card-body">
                                  <form class="form" id="availabilityForm" action="{{route('myAvailability.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" id="id">
                                      <div class="row">

                                          <div class="col-md-6 col-12">
                                              <div class="form-group">
                                                  <label for="start_date">Start Date *</label>
                                                  <input type="text" id="start_date" name="start_date" onchange="allCalculation()" required class="form-control format-picker" placeholder="Start Date"/>
                                            </div>
                                          </div>

                                          <div class="col-md-6 col-12">
                                              <div class="form-group">
                                                  <label for="end_date">End Date *</label>
                                                  <input type="text" id="end_date" name="end_date" onchange="allCalculation()" required class="form-control format-picker" placeholder="End Date" />
                                            </div>
                                          </div>
                                          
                                          <div class="col-md-6 col-12">
                                              <div class="form-group">
                                                  <label for="end_date">Total Day</label>
                                                  <input type="text" id="total" class="form-control" placeholder="total" disabled/>
                                            </div>
                                          </div>

                                            <div class="col-md-6 col-12">
                                                <label for="">Leave Type *</label>
                                                <div class="form-group">
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
                                          <div class="col-md-6 col-12">
                                              <div class="form-group">
                                                  <label for="first-name-column">Leave Reason *</label>
                                                  <input type="text" id="remarks" class="form-control" placeholder="remarks" name="remarks" required/>
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
                <button type="submit" class="btn btn-success" id="addBtn">Add</button>
                <button type="submit" class="btn btn-success" id="updateBtn">Update</button>
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Discard</button>
            </div>
              </form>
        </div>
    </div>
</div>
