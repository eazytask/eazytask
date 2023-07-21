<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>camera</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
  <script src="https://unpkg.com/dynamsoft-camera-enhancer@2.1.0/dist/dce.js"></script>
  <style>
    .dce-msg-poweredby {
      display: none !important;
    }
    #my_camera div{
      background: #ddd0 !important;
    }
  </style>
</head>

<body>
  <section class="bg-dark">
    <div class="container-fluid">
      <div class="row text-center align-items-center justify-content-center" style="height: 100vh;">
        <div class="col-sm-12 col-md-6 mx-auto">
          <h1 class="text-white mb-5">
            Capture a photo from mobile
          </h1>
          <button class="btn btn-warning text-white" id="accesscamera" data-toggle="modal" data-target="#photomodal">
            Capture Photo
          </button>
        </div>
      </div>
    </div>

  </section>

  <!----Modal--->

  <div class="modal fade" id="photomodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="exampleModalLabel">Capture Photo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="cam-body">
          <div id="my_camera" style="height: 60vh;"></div>
          <div id="result" class="d-none" style="height: 60vh;"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning mx-auto text-white" id="capture">Capture Photo</button>
          <button type="button" class="btn btn-warning mx-auto text-white d-none" id="retakephoto">Retake</button>
          <button type="submit" class="btn btn-warning mx-auto text-white d-none" id="uploadphoto" form="photoForm">Upload</button>

        </div>
      </div>
    </div>
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
  <script>
    let enhancer = null;
    $('#accesscamera').on('click', function() {
      (async () => {
        enhancer = await Dynamsoft.DCE.CameraEnhancer.createInstance();
        $('#my_camera').html(enhancer.getUIElement())
        // document.getElementById("my_camera").append(enhancer.getUIElement());
        // document.getElementsByClassName("dce-btn-close")[0].style.display = "none";
        $(".dce-btn-close").hide()
        $(".dce-sel-resolution").hide()
        $(".dce-sel-camera").hide()
        
        await enhancer.open(true);

        let cameras = await enhancer.getAllCameras();
        if (cameras.length) {
          await enhancer.selectCamera(cameras[0]);
        }
      })();
    });
    document.getElementById('capture').onclick = () => {
      if (enhancer) {
        let frame = enhancer.getFrame();
        let imgUrl = frame.canvas.toDataURL("image/png")
        document.getElementById('result').innerHTML = '<img src="' + imgUrl + '" width="100%" height="100%"/>'

        $('#my_camera').removeClass('d-block');
        $('#my_camera').addClass('d-none');

        $('#result').removeClass('d-none');

        $('#capture').removeClass('d-block');
        $('#capture').addClass('d-none');

        $('#retakephoto').removeClass('d-none');
        $('#retakephoto').addClass('d-block');

        $('#uploadphoto').removeClass('d-none');
        $('#uploadphoto').addClass('d-block');
      }
    };

    $('#retakephoto').on('click', function() {
      $('#my_camera').addClass('d-block');
      $('#my_camera').removeClass('d-none');

      $('#result').addClass('d-none');

      $('#capture').addClass('d-block');
      $('#capture').removeClass('d-none');

      $('#retakephoto').addClass('d-none');
      $('#retakephoto').removeClass('d-block');

      $('#uploadphoto').addClass('d-none');
      $('#uploadphoto').removeClass('d-block');
    });
  </script>

</body>

</html>