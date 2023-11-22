@extends('layouts.Admin.master')


@section('admincontent')
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
                    <button class="btn btn-primary" id="add"><i data-feather='plus'></i></button>
                </div>
                @include('pages.Admin.project.modals.projectAddModal')


                <div class="container">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover-animation table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Venue Name</th>
                                    <th>Contact Name</th>
                                    <th>Contact Number</th>
                                    <th>Status</th>
                                    <th>Client</th>
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
    <script>
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
                            $('#example').DataTable().clear().destroy();
                            $('#projectBody').html(data.data)
                            $('#example').DataTable({
                                "drawCallback": function(settings) {
                                    feather.replace({
                                        width: 14,
                                        height: 14
                                    });
                                }
                            });
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
@endpush
