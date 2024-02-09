<div id="add" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Task
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="taskDescriptionForm">
                    @csrf
                    <input type="text" name="id" id="taskId" hidden>

                    <div class="row g-4">
                        <div class="col-xxl-12">
                            <label for="description" class="form-label">
                                Description <span class="text-danger">*</span> 
                            </label>
                            <input id="description" type="text" class="form-control" name="description" required>
                        </div>
                        
                        <div class="col-xxl-12">
                            <label for="status" class="form-label">
                                Status
                            </label>
    
                            <select class="form-control" data-choices name="status" id="status">
                                <option value="incomplete" selected>Incomplete</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-xxl-12">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" id="addBtn">Add</button>
                                <button type="button" class="btn btn-primary" id="updateBtn" hidden>Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>