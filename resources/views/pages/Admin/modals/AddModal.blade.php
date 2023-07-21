<div class="modal fade text-left p-md-1 p-0" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Add Task Description</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <section id="multiple-column-form">
                  <div class="row">
                      <div class="col-12">
                          <div class="card">

                              <div class="card-body">
                                  <form class="form" id="taskDesctioptionForm">
                                    @csrf
                                    <input type="text" name="id" id="taskId" hidden>
                                      <div class="row">
                                          <div class="col-12">
                                              <div class="form-group">
                                                  <label for="first-name-column">Description *</label>
                                                  <input type="text" id="description" class="form-control" placeholder="task description" name="description" required/>
                                              </div>
                                          </div>
                                          <div class="col-md-6 col-12">
                                                <label for="">Status *</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" name="status" id="status"
                                                        aria-label="Default select example" required>
                                                        <option value="incomplete" selected>incomplete</option>
                                                        <option value="completed">completed</option>
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
                <button type="button" class="btn btn-success" id="addBtn">Add</button>
                <button type="button" class="btn btn-success" id="updateBtn" hidden>Update</button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Discard</button>
            </div>
              </form>
        </div>
    </div>
</div>
