@include('layouts.Admin.partials.header')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/page-auth.css')}}">
<style>
    .dt-buttons {
        display: none !important;
    }

    .img-fluid {
        height: 100% !important;
    }
</style>

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    @include('sweetalert::alert')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-v2">
                    <div class="auth-inner row m-0">
                        <!-- /Brand logo-->
                        <!-- Left Text-->
                        <div class="col-lg-12 align-items-center p-5 kiosk-div">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <img src="{{asset('images/app/logo.png')}}" alt="" height="100px">
                                    <h2>{{\Carbon\Carbon::now()->format('d M, Y')}}</h2>
                                </div>

                                <div class="container">
                                    <div class="table-responsive">
                                        <label class="float-left mt-1 mr-1">Projects
                                            <select id="projectFilter" style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;" class="form-control text-uppercase">
                                                @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->pName}}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                        <table id="myTable" class="table table-hover-animation table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tBody">

                                            </tbody>
                                        </table>
                                    </div>
                                    @include('pages.Admin.kiosk.modals.PinModal')

                                    <form id="sign-in-form">
                                        <input type="text" class="employee_id" name="employee_id" hidden />
                                        <input type="hidden" class="photoStore" name="image" value="">
                                        <input type="hidden" name="project_id" value="" id="set_project_id">
                                        @csrf
                                        @include('pages.Admin.kiosk.modals.timeKeeperAddModal')
                                    </form>
                                </div>

                            </div>
                        </div>
                        <!-- /Left Text-->
                    </div>
                </div>
            </div>
        </div>

        <form id="formId">
            @csrf
            @include('pages.Admin.kiosk.modals.takePhotoModal')
        </form>
    </div>
    <!-- END: Content-->

    @include('layouts.Admin.partials.scripts')
    <script src="{{asset('js/camera.js')}}"></script>
    <script>
        $(document).ready(function() {
            let searchEL = $('<label class="float-left">Employee:<select id="empFilter" style="width:150px; color:#7367f0 !important; display: inline; font-size: 12px; height: 30px;" class="form-control"><option value="shift">On Shift</option><option value="inducted">Inducted</option><option value="all">All</option></select></label>');

            function fetchEmployee(empFilter = 'shift') {
                $.ajax({
                    url: '/admin/kisok/employees',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'project_id': $("#projectFilter").val(),
                        'empFilter': empFilter,
                    },
                    success: function(data) {
                        $('#myTable').DataTable().clear().destroy();
                        $('#tBody').html(data.data);
                        $('#myTable').DataTable({
                            dom: 'Bfrtip',
                            paging: false,
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ]
                        });
                        feather.replace({
                            width: 14,
                            height: 14
                        });

                        $('#myTable_filter').append(searchEL)
                        $(searchEL).find("#empFilter").val(data.empFilter)
                        $('#set_project_id').val($("#projectFilter").val())

                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
            fetchEmployee()

            $("#projectFilter").on('change', function() {
                fetchEmployee()
            })

            searchEL.on('change', function() {
                fetchEmployee($('#empFilter').val())
            })

            $(document).on("click", ".click-btn", function() {
                $('#pin').val('')
                $('#pin_div').show()
                $('#sign_div').hide()
                $('#results').html('')
                $('.photoStore').val('')
                $('#project-select').val('')
                $('#rate').val('')
                $('#remarks').val('')
                window.employee_id = null

                const interval_id = window.setInterval(function() {}, Number.MAX_SAFE_INTEGER);
                for (let i = 1; i < interval_id; i++) {
                    window.clearInterval(i);
                }
                startTime()

                let rowData = $(this).data("row")
                $('#employee-name').html(rowData.fname)
                $('.employee_id').val(rowData.id)
                $('.project_id').val($("#projectFilter").val())
                $('#user_id').val(rowData.userID)
                $('#retakephoto').click()
                $("#pinModal").modal("show")

                window.employee_id = rowData.id
            })

            checkPin = function() {
                if ($("#pinForm").valid()) {
                    $.ajax({
                        data: $('#pinForm').serialize(),
                        url: "/admin/kisok/check/pin",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            // $("#roasterClick").modal("hide")
                            if (data.status == 'success') {
                                toastr['success']("You're correct.", {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });

                                $('#roaster_div').html(data.data)
                                if (data.hasShift) {
                                    all_timer()
                                }
                                $('#pin_div').hide()
                                $('#sign_div').show()
                            } else if (data.status == 'wrong_pin') {
                                toastr['warning']("Wrong pin code!", {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                            } else if (data.status == 'no_pin') {
                                toastr['warning']("You didn't save any pin!", {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                            } else {
                                toastr['warning']("something went wrong!", {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                            }
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            }
            all_shifts = function() {
                $.ajax({
                    url: "/admin/kisok/all/shifts/" + window.employee_id + '/' + $("#projectFilter").val(),
                    type: "get",
                    dataType: 'json',
                    success: function(data) {
                        // $("#roasterClick").modal("hide")
                        if (data.status == 'success') {
                            $('#roaster_div').html(data.data)

                            const interval_id = window.setInterval(function() {}, Number.MAX_SAFE_INTEGER);
                            for (let i = 1; i < interval_id; i++) {
                                window.clearInterval(i);
                            }

                            startTime()
                            if (data.hasShift) {
                                all_timer()
                            }
                            $('#results').html('')
                            $('.photoStore').val('')
                        } else {
                            toastr['warning']("something went wrong!", {
                                closeButton: true,
                                tapToDismiss: false,
                            });
                        }
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            }
            storeTimekeeper = function() {
                if ($("#sign-in-form").valid()) {
                    $.ajax({
                        data: $('#sign-in-form').serialize(),
                        url: "/admin/kisok/store/timekeeper",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            // $("#roasterClick").modal("hide")
                            if (data.status == 'success') {
                                toastr['success']("Roster created successfully.", {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });

                                all_shifts()
                                $('#photomodal').modal('hide')
                                $('#userAddTimeKeeper').modal('hide')
                                retakePhoto()
                            } else {
                                toastr['warning'](data.status, {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                                // $('#userAddTimeKeeper').modal('hide')
                            }
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            }
            $('.timekeer-btn').on('click', function() {
                $('#url').val('/admin/kisok/store/timekeeper')
                if ($("#sign-in-form").valid()) {

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
                    $("#photomodal").modal("show")
                }
            })

            openPhotoModal = function(roaster_id, action) {
                $("#timekeeperID").val(roaster_id).change()
                $("#url").val(action).change();

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
                $("#photomodal").modal("show")

            }
            $('#uploadphoto').on('click', function() {
                if ($("#url").val() == '/admin/kisok/store/timekeeper') {
                    storeTimekeeper()
                } else {
                    console.log($("#url").val())
                    $.ajax({
                        data: $('#formId').serialize(),
                        url: $("#url").val(),
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            // $("#roasterClick").modal("hide")
                            if (data.status == 'success') {
                                toastr['success']("Success.", {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                                all_shifts()
                                $('#photomodal').modal('hide')
                                retakePhoto()
                            } else {
                                toastr['warning']("something went wrong!", {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                            }
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            })

            all_timer = function() {
                let indexes = {};
                let total_entry = document.getElementById("total_entry").value

                for (let i = 0; i < total_entry; i++) {
                    // Set the date we're counting down to
                    indexes["shift_start" + i] = document.getElementById("shift_start" + i).value
                    // indexes["shift_start" + i] = new Date(indexes["shift_start" + i])
                    indexes["shift_start" + i] = indexes["shift_start" + i].split("-").join("/")
                    indexes["countDownDate" + i] = new Date(indexes["shift_start" + i]).getTime();

                    indexes["x" + i] = setInterval(function() {
                        // Get today's date and time
                        indexes["timeNow" + i] = new Date().toLocaleString('en-US', {
                            timeZone: 'Australia/Sydney'
                        });
                        // alert(indexes["timeNow" + i])
                        indexes["timeNow" + i] = new Date(indexes["timeNow" + i])
                        var now = indexes["timeNow" + i].getTime();

                        indexes["distance" + i] = indexes["countDownDate" + i] - now;

                        indexes["hours" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        indexes["minutes" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));

                        document.getElementById("countdown" + i).innerHTML = "Shift Starting in " + indexes["hours" + i] + " hours, " +
                            indexes["minutes" + i] + " minutes ";

                        if (indexes["distance" + i] < 0) {
                            // clearInterval(indexes["x" + i]);
                            indexes["distance" + i] = now - indexes["countDownDate" + i];
                            indexes["hours" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            indexes["minutes" + i] = Math.floor((indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));

                            document.getElementById("countdown" + i).innerHTML = indexes["hours" + i] + "h, " +
                                indexes["minutes" + i] + "minutes ";

                            document.getElementById("countdown" + i).classList.add('text-danger')
                        }
                    }, 1000);
                }

                // shift ending in
                let shift_end_in_indexes = {};
                for (let i = 0; i < total_entry; i++) {
                    // Set the date we're counting down to
                    shift_end_in_indexes["shift_end" + i] = document.getElementById("shift_end" + i).value
                    // shift_end_in_indexes["shift_end" + i] = new Date(shift_end_in_indexes["shift_end" + i])
                    shift_end_in_indexes["shift_end" + i] = shift_end_in_indexes["shift_end" + i].split("-").join("/")

                    shift_end_in_indexes["countDownDate" + i] = new Date(shift_end_in_indexes["shift_end" + i]).getTime();
                    shift_end_in_indexes["x" + i] = setInterval(function() {
                        // Get today's date and time
                        shift_end_in_indexes["timeNow" + i] = new Date().toLocaleString('en-US', {
                            timeZone: 'Australia/Sydney'
                        });
                        shift_end_in_indexes["timeNow" + i] = new Date(shift_end_in_indexes["timeNow" + i])
                        var now = shift_end_in_indexes["timeNow" + i].getTime();
                        // var now = new Date().getTime();

                        // Find the distance between now and the count down date
                        shift_end_in_indexes["distance" + i] = shift_end_in_indexes["countDownDate" + i] - now;
                        // Time calculations for days, hours, minutes and seconds
                        shift_end_in_indexes["hours" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        shift_end_in_indexes["minutes" + i] = Math.floor((shift_end_in_indexes["distance" + i] % (1000 * 60 * 60)) / (1000 * 60));
                        // var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        // Output the result in an element with id="demo"
                        document.getElementById("shift-end-in" + i).innerHTML = "Shift ending in " + shift_end_in_indexes["hours" + i] + " hours, " +
                            shift_end_in_indexes["minutes" + i] + " minutes ";
                        // If the count down is over, write some text 
                        if (shift_end_in_indexes["distance" + i] < 0) {
                            clearInterval(shift_end_in_indexes["x" + i]);
                            document.getElementById("shift-end-in" + i).innerHTML = "end";
                        }
                    }, 1000);
                }

                //working
                for (let i = 0; i < total_entry; i++) {
                    // total working time
                    indexes["workingTimeNow" + i] = ''
                    indexes["sing_in" + i] = document.getElementById("sing_in" + i).value
                    indexes["sing_out" + i] = document.getElementById("sing_out" + i).value
                    // indexes["sing_in" + i] = new Date(indexes["sing_in" + i])
                    indexes["sing_in" + i] = indexes["sing_in" + i].split("-").join("/")
                    indexes["sing_out" + i] = indexes["sing_out" + i].split("-").join("/")
                    indexes["workingTime" + i] = new Date(indexes["sing_in" + i]).getTime();

                    var y = setInterval(function() {
                        if (indexes["sing_out" + i]) {
                            indexes["workingTimeNow" + i] = new Date(indexes["sing_out" + i])
                        } else {
                            indexes["workingTimeNow" + i] = new Date().toLocaleString('en-US', {
                                timeZone: 'Australia/Sydney'
                            });
                            indexes["workingTimeNow" + i] = new Date(indexes["workingTimeNow" + i])
                        }

                        indexes["atNow" + i] = indexes["workingTimeNow" + i].getTime();
                        indexes["totalDistance" + i] = indexes["atNow" + i] - indexes["workingTime" + i];

                        indexes["h" + i] = Math.floor((indexes["totalDistance" + i] % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        indexes["m" + i] = Math.floor((indexes["totalDistance" + i] % (1000 * 60 * 60)) / (1000 * 60));

                        document.getElementById("working" + i).innerHTML = indexes["h" + i] + "h, " +
                            indexes["m" + i] + "min.";
                    }, 1000);
                }
            }
            startTime = function() {
                let timeNow = new Date().toLocaleString('en-US', {
                    timeZone: 'Australia/Sydney'
                });
                timeNow = new Date(timeNow)
                const today = timeNow;
                let h = today.getHours();
                let m = today.getMinutes();
                let s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
                setTimeout(startTime, 1000);
            }

            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i
                }; // add zero in front of numbers < 10
                return i;
            }

            $(document).on('show.bs.modal', '.modal', function() {
                const zIndex = 1040 + 10 * $('.modal:visible').length;
                $(this).css('z-index', zIndex);
                setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
            });

            document.getElementById('capture').onclick = () => {
                if (enhancer) {
                    let frame = enhancer.getFrame();
                    let imgUrl = frame.canvas.toDataURL("image/png")
                    document.getElementById('result').innerHTML = '<img src="' + imgUrl + '" width="100%" height="100%"/>'
                    $('.photoStore').val(imgUrl);

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
                retakePhoto()
            });
            function retakePhoto(){
                $('#my_camera').addClass('d-block');
                $('#my_camera').removeClass('d-none');

                $('#result').addClass('d-none');

                $('#capture').addClass('d-block');
                $('#capture').removeClass('d-none');

                $('#retakephoto').addClass('d-none');
                $('#retakephoto').removeClass('d-block');

                $('#uploadphoto').addClass('d-none');
                $('#uploadphoto').removeClass('d-block');
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>