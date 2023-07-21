<div class="modal fade text-left p-md-1 p-0" id="photomodal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Take a Photo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <input type="text" name="timekeeper_id" id="timekeeperID" value="" hidden>
            <input type="text" name="url" id="url" value="" hidden>


            <div class="modal-body">
                <div>
                    <div id="my_camera" class="d-block mx-auto rounded overflow-hidden"></div>
                </div>
                <div id="results" class="d-block"></div>
            <input type="hidden" class="photoStore" name="image" value="">
            </div>

            <div class="modal-footer">
         <button type="button" class="btn btn-gradient-primary mx-auto text-white" id="takephoto">Capture Photo</button>
         <button type="button" class="btn btn-warning mx-auto text-white d-none" id="retakephoto">Retake</button>
         <button type="button" class="btn btn-success mx-auto text-white d-none" id="uploadphoto" data-dismiss="modal" aria-label="Close">Upload</button>
            </div>
            </form>

        </div>
    </div>
</div>