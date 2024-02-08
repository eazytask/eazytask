@extends('layouts.Admin.master')

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Client
        @endslot
        @slot('title')
            Site / Venue
        @endslot
    @endcomponent
    <div class="content-header row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#addProject" id="add">
                                <i class="ri-add-fill me-1 align-bottom"></i>Add Client Site/Venue
                            </button>
                            @include('pages.Admin.project.modals.projectAddModal')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card" id="client_list">
                <div class="card-body">
                    <div>
                        <div class="table-responsive mb-3">
                            <table id="example" class="table table-bordered table-hover-animation">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Venue Name</th>
                                        <th>Contact Person</th>
                                        <th>Contact No</th>
                                        <th>Client Name</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="projectBody">
    
                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ companies
                                        We did not find any
                                        companies for you search.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
                        aria-labelledby="deleteRecordLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="btn-close deleteRecord-close"></button>
                                </div>
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                                    </lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4 class="fs-semibold">You are about to delete a company ?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your company will
                                            remove all of your information from our database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i>
                                                Close</button>
                                            <button class="btn btn-danger" id="delete-record">Yes,
                                                Delete It!!</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end delete modal -->

                </div>
            </div>
            <!--end card-->
        </div>
    </div>

@endsection

@section('')
    @include('sweetalert::alert')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Venues</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="/admin/home/{{ Auth::user()->company_roles->first()->company->id }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Venue Lists
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- <input id="pac-input" class="controls" type="text" placeholder="Search Box" />
                                                                                                                                                                                                                                                                                                                    <div id="googleMap" style="width:100%;height:400px;"></div> -->
    <!-- Basic Tables start -->
    <!-- Table Hover Animation start -->
    <div class="row" id="table-hover-animation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                </div>

                <div class="card-body">
                    <div class="container row row-xs">
                        <div class="col mt-md-0">
                            <button class="btn btn-default float-left" id="download" title="Download Report"><img
                                    src="{{ url('backend/img/download_icon.png') }}" class="img-responsive"
                                    style="width: 35px;"></button>
                            <button class="btn btn-default" id="add" title="Add Venue"><img
                                    src="{{ url('backend/img/office_building.png') }}" class="img-responsive"
                                    style="width: 35px;"></button>
                            @include('pages.Admin.project.modals.projectAddModal')
                        </div>

                        <div class="col-lg-2">
                            <select class="form-control select2" name="status_id" id="status_id">
                                <option>Select Status</option>
                                <option value="0">All</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-1 col-lg-1">
                            <button type="button" onclick="handleStatusChange(this)"
                                class="btn btn btn-outline-primary btn-block" id="btn_search"><i
                                    data-feather='search'></i></button>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover-animation table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Venue Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact No</th>
                                    <th>Client Name</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="projectBody">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Table head options end -->
    <!-- Basic Tables end -->
@endsection

@push('scripts')
    @include('components.datatablescript')
    <script src="{{ asset('backend') }}/lib/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('backend') }}/lib/sweetalert/code.js"></script>
    @include('components.stepper')
    <script>
        const dataTableTitle = 'Client Site Report';
        const dataTableOptions = {
            "drawCallback": function(settings) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            },
            dom: 'Blfrtip', // Include 'l' for length menu
            lengthMenu: [30, 50,
                100, 200
            ], // Set the options for the number of records to display
            buttons: [
                {
                    extend: 'colvis',
                    fade: 0,
                },
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: dataTableTitle,
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: dataTableTitle,
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: dataTableTitle,
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible',
                    },
                    title: dataTableTitle,
                }
            ],
            initComplete: function() {
                let table = this.api();
            
                let search = `<div class="search-box">
                                <input type="text" class="form-control form-control-sm search" placeholder="Search for company...">
                                <i class="ri-search-line search-icon"></i>
                            </div>`;
                $('#example_filter').html(search);

                $('.search').on('keyup', function(){
                    table.search( this.value ).draw();
                });
            },
        }
        function handleStatusChange() {
            var status = $('#status_id').val();
            $.ajax({
                url: '/admin/home/fetch/project?status=' + status,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if (data.data) {
                        if (data.data.length > 100) {
                            $('#example').DataTable().clear().destroy();
                        }
                        $('#projectBody').html(data.data)
                        if (data.data.length > 100) {
                            $('#example').DataTable(dataTableOptions);
                            
                        }
                    }

                    $("#addClient").modal("hide")
                },
                error: function(err) {
                    console.log(err)
                }
            });
        }

        $(document).ready(function() {
            $('#clientName').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Client',
                dropdownParent: $('#clientName').parent(),
                allowClear: true
            });

            window.fetchData = fetchProjects = function() {
                $.ajax({
                    url: '/admin/home/fetch/project',
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if (data.data) {
                            console.log('before',$('#example'));
                            $('#example').DataTable().clear().destroy();
                            $('#projectBody').html(data.data)
                            let table = $('#example').DataTable(dataTableOptions);
                           
                        }

                        $("#addProject").modal("hide")
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
            fetchProjects()

            $(document).on("click", ".del", function() {
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '/admin/home/project/delete/' + $(this).data("id"),
                            type: 'get',
                            dataType: 'json',
                            success: function(data) {
                                toastr[data.alertType](data.message, {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                                fetchProjects()
                            },
                            error: function(err) {
                                console.log(err)
                            }
                        });
                    }
                });
            })
            // $("#newModalForm").validate()
            $(document).on("click", ".edit-btn", function() {
                resetValue()
                var rowData = $(this).data("row");

                $("#id").val(rowData.id);
                $("#pName").val(rowData.pName);
                $("#cName").val(rowData.cName)

                $("#cNumber").val(rowData.cNumber)
                $("#clientName").val(rowData.clientName).trigger('change')
                $("#project_address").val(rowData.project_address)
                $("#suburb").val(rowData.suburb)
                $("#project_state").val(rowData.project_state)
                $("#postal_code").val(rowData.postal_code)
                $("#Status").val(rowData.Status).trigger('change')
                $("#lat").val(rowData.lat)
                $("#lon").val(rowData.lon)
                // $('#newModalForm').attr('action', "{{ route('update-project') }}");
                window.formAction = "{{ route('update-project') }}"
                // $("#savebtn").hide()
                // $("#updatebtn").show()

                $("#buttom_bar").attr('style', 'display:flex !important')
                $("#addProject").modal("show")
            })

            $(document).on("click", "#add", function() {
                resetValue()
                $("#addProject").modal("show")
                $(".location").addClass('load_address_map')
            })

            function resetValue() {
                $("#id").val('')
                $("#pName").val('')
                $("#cName").val('')

                $("#cNumber").val('')
                $("#clientName").val('').trigger('change')
                $("#project_address").val('')
                $("#suburb").val('')
                $("#project_state").val('')
                $("#postal_code").val('')
                $("#Status").val('').trigger('change')
                $('#search_address').val('')
                // $("#lat").val('')
                // $("#lon").val('')
                $("#buttom_bar").attr('style', 'display:none !important')
                $(".location").removeClass('load_address_map')

                window.formAction = "{{ route('store-project') }}"
                // $('#newModalForm').attr('action', "{{ route('store-project') }}");
                // $("#savebtn").show()
                // $("#updatebtn").hide()
                window.StepperReset()
            }

        })
    </script>

    <script>
        function myMap(_lat = 23.87964951097153, _lon = 90.27272587563567) {
            initAutocomplete();

            let markers = []
            let searchBox = null
            const myLatlng = {
                lat: parseFloat(_lat),
                lng: parseFloat(_lon)
            };
            // console.log(myLatlng)
            const map = new google.maps.Map(document.getElementById("googleMap"), {
                zoom: 17,
                center: myLatlng,
            });
            let marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                animation: google.maps.Animation.DROP,
            });
            let cityCircle = new google.maps.Circle({
                strokeColor: "#FF0000",
                strokeColor: "#7367f0",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillColor: "#7367f0",
                fillOpacity: 0.35,
                map,
                center: myLatlng,
                radius: 80,
            });

            map.addListener("click", (mapsMouseEvent) => {
                loadNew(mapsMouseEvent.latLng)
            });

            $(document).on("click", ".edit-btn", function() {
                var rowData = $(this).data("row");
                let projectLatLng = {
                    lat: parseFloat(rowData.lat),
                    lng: parseFloat(rowData.lon)
                }
                loadNew(projectLatLng)
                map.setCenter(projectLatLng)
                map.setZoom(17);
                map.setZoom(17);

            })
            $(document).on("click", ".load_address_map", function() {
                var geocoder = new google.maps.Geocoder();
                var address = $("#project_address").val() + '%' + $("#suburb").val() + '%' + $("#project_state")
                    .val();
                geocoder.geocode({
                    'address': address
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        let currentLatLng = {
                            lat: parseFloat(results[0].geometry.location.lat()),
                            lng: parseFloat(results[0].geometry.location.lng())
                        }
                        loadNew(currentLatLng)
                        map.setCenter(currentLatLng)
                        map.setZoom(17);
                    }
                });
            })

            function loadNew(latLng) {
                $('#lat').val(latLng.lat)
                $('#lon').val(latLng.lng)
                marker.setMap(null);
                marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    draggable: true,
                });
                google.maps.event.addListener(marker, 'dragend', function() {
                    // loadNew(marker.getPosition());
                    latLng = marker.getPosition()
                    cityCircle.setMap(null);
                    cityCircle = new google.maps.Circle({
                        strokeColor: "#7367f0",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: "#7367f0",
                        fillOpacity: 0.35,
                        map,
                        center: latLng,
                        radius: 80,
                    });

                    $('#lat').val(latLng.lat)
                    $('#lon').val(latLng.lng)
                });

                cityCircle.setMap(null);
                cityCircle = new google.maps.Circle({
                    strokeColor: "#FF0000",
                    strokeColor: "#7367f0",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillColor: "#7367f0",
                    fillOpacity: 0.35,
                    map,
                    center: latLng,
                    radius: 80,
                });
                // alert(latLng)
                // map.setCenter(latLng)

                marker.addListener("click", toggleBounce);

                function toggleBounce() {
                    if (marker.getAnimation() !== null) {
                        marker.setAnimation(null);
                    } else {
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                    }
                }
                $('#lat').val(latLng.lat)
                $('#lon').val(latLng.lng)
            }

            searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));
            google.maps.event.addListener(searchBox, 'places_changed', function() {
                searchBox.set('map', null);
                var places = searchBox.getPlaces();

                var bounds = new google.maps.LatLngBounds();
                var i, place;
                for (i = 0; place = places[i]; i++) {
                    (function(place) {
                        // var marker = new google.maps.Marker({
                        //     position: place.geometry.location
                        // });
                        // marker.bindTo('map', searchBox, 'map');
                        google.maps.event.addListener(marker, 'map_changed', function() {
                            if (!this.getMap()) {
                                this.unbindAll();
                            }
                        });
                        bounds.extend(place.geometry.location);
                    }(place));

                }
                map.fitBounds(bounds);
                searchBox.set('map', map);
                map.setZoom(Math.min(map.getZoom(), 17));

            });
        }

        function initAutocomplete() {
            var AUTOCOMPLETE_OPTIONS = {
                componentRestrictions: {
                    country: 'AU',
                }
            };
            autocompleteAddress = new google.maps.places.Autocomplete(document.getElementById(
                    'search_address'),
                AUTOCOMPLETE_OPTIONS);

            autocompleteAddress.addListener('place_changed', function() {
                fillInAddress(autocompleteAddress)
            });
        }

        function fillInAddress(autoCompleteObject) {
            var place = autoCompleteObject.getPlace();
            console.log(place)
            // var name = place.name || "";
            // if (name) name += ": "
            // name += autoCompleteObject.getPlace().formatted_address;
            // setSelectedAddress(name);
            var address = place.formatted_address;
            // console.log(address);
            var value = address.split(",");
            var count = value.length;
            var country = value[count - 1] ?? '';
            var state = value[count - 2] ?? '';
            var city = value[count - 3] ?? '';
            var z = state.split(" ");
            // document.getElementById("selCountry").text = country;
            var i = z.length;
            document.getElementById("project_state").value = z[1];
            if (i > 2)
                document.getElementById("postal_code").value = z[2];
            document.getElementById("suburb").value = city;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var mesg = address;
            // document.getElementById("project_address").value = mesg;
            var lati = latitude;
            document.getElementById("plati").value = lati;
            var longi = longitude;
            document.getElementById("plongi").value = longi;

            address1Field = document.querySelector("#project_address");
            let address1 = "";

            for (const component of place.address_components) {
                // @ts-ignore remove once typings fixed
                const componentType = component.types[0];

                switch (componentType) {
                    case "street_number": {
                        address1 = `${component.long_name} ${address1}`;
                        break;
                    }

                    case "route": {
                        address1 += component.short_name;
                        break;
                    }

                    case "postal_code": {
                        document.querySelector("#postal_code").value = component.long_name;
                        // postcode = `${component.long_name}${postcode}`;
                        break;
                    }

                    case "postal_code_suffix": {
                        postcode = `${postcode}-${component.long_name}`;
                        break;
                    }
                    case "locality":
                        document.querySelector("#suburb").value = component.long_name;
                        break;
                    case "administrative_area_level_1": {
                        document.querySelector("#project_state").value = component.short_name;
                        break;
                    }
                    // case "country":
                    //     document.querySelector("#country").value = component.long_name;
                    //     break;
                }
            }

            address1Field.value = address1;
            // postalField.value = postcode;
        }

        // function setSelectedAddress(name) {
        //     var selected = $("#project_address");
        //     selected.val(name);
        // }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUiC49n0UCoKfiz4TrHQwK-BCsLzc_LY4&callback=myMap&libraries=places">
    </script>

    <style>
        /* Custom styles for DataTables search and length menu alignment */
        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-left: 10px;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-right: 20px;
        }
        div.dt-buttons {
            padding-right:1rem;
        }
        .dropdown-item.del{
            cursor: pointer;
        }
    </style>
@endpush
