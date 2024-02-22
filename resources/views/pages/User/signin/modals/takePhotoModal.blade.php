<div class="modal fade" id="photomodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info-subtle py-3">
          <h5 class="modal-title" id="exampleModalLabel">Capture Photo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
        <div class="modal-body" id="cam-body">
          <div id="my_camera" style="height: 60vh;"></div>
          <div id="result" class="d-none" style="height: 60vh;"></div>
          <input type="text" name="image" id="image" hidden>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning mx-auto text-white" id="capture">Capture Photo</button>
          <button type="button" class="btn btn-warning mx-auto text-white d-none" id="retakephoto">Retake</button>
          <button type="button" class="btn btn-warning mx-auto text-white d-none" id="uploadphoto" form="photoForm">Upload</button>
        </div>
      </div>
    </div>
  </div>