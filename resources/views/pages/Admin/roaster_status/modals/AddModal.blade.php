<div class="modal fade text-left p-md-1 p-0" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" id="myModalLabel17">Add Roster-Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form" action="{{route('roasterStatus.store')}}" method="POST" id="modalForm">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Name*</label>
                                                    <input type="text" class="form-control" placeholder="Name" name="name" id="name" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Remarks</label>
                                                    <input type="text" class="form-control" placeholder="remarks" id="remarks" name="remarks" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Color</label>
                                                    <input type="color" class="form-control" id="color" name="color" />
                                                </div>
                                            </div>
                                            <!-- <input type="text" id="box" hidden> -->

                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="savebtn">Add</button>
                <button type="submit" class="btn btn-primary" id="updatebtn">Update</button>
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Discard</button>
            </div>
            </form>
        </div>
    </div>
</div>