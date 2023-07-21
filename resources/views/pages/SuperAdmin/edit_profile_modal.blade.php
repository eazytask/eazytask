<div class="modal fade text-left p-md-1 p-0" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Edit Profile Photo</h4>
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
                                  <form class="form" action="{{route('super-admin-profile-photo-update')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{Auth::user()->id}}">
                                      <div class="row">

                                          <div class="col-12">
                                              <div class="form-group">
                                                  <label for="company-column">Choose Avatar</label>
                                                  <input type="file" id="image" class="form-control" name="file" placeholder="Admin Image" />
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
                <button type="submit" class="btn btn-success">Update Photo</button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Discard</button>
            </div>
              </form>
        </div>
    </div>
</div>
