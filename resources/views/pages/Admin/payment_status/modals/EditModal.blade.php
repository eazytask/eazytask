<div class="modal fade text-left p-md-1 p-0" id="edit{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Edit Payment-Status</h4>
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
                                  <form class="form" action="{{route('paymentStatus.update')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$row->id}}">
                                      <div class="row">
                                          <div class="col-md-6 col-12">
                                              <div class="form-group">
                                                  <label for="first-name-column">Name *</label>
                                                  <input type="text"  class="form-control" placeholder="Name" name="name" required value="{{$row->name}}"/>
                                              </div>
                                          </div>
                                          <div class="col-md-6 col-12">
                                              <div class="form-group">
                                                  <label for="first-name-column">Remarks</label>
                                                  <input type="text"  class="form-control" placeholder="remarks" name="remarks" value="{{$row->remarks}}"/>
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
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Discard</button>
            </div>
              </form>
        </div>
    </div>
</div>
