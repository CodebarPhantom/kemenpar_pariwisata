
@extends('adminlte::page')

@section('title', 'Add Pariwisata')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Add').' '.__('Tourism') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master').' '.__('Tourism') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Add').' '.__('Tourism') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" id="form_1" action="{{ route('tourism-info.store') }}" method="POST" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header"> 
                    <div class="d-flex">
                        <div class="mr-auto">
                            <h3 class="card-title mt-1">
                                <i class="fa fa-store-alt"></i>
                                    &nbsp; {{ __('Add').' '.__('Tourism') }}
                            </h3>
                        </div>
                        <div class="mr-1">
                            <a href="{{ route('tourism-info.index') }}" class="btn btn-secondary btn-flat btn-sm">
                                <i class="fa fa-arrow-left"></i>
                                &nbsp;&nbsp;{{ __('Back') }}
                            </a>  
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-info btn-flat btn-sm">
                                <i class="fa fa-check"></i>
                                &nbsp;&nbsp;{{ __('Save') }}
                            </button>
                        </div>
                    </div>               
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Name').' '.__('Tourism') }} </label>
                                <input type="text" name="tourismName" class="form-control" placeholder="{{ __('Name').' '.__('Tourism') }}...."  required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Price') }}</label>
                                <input id="price-separator" type="text" class="form-control" placeholder="Price....">
                                <input id="price" type="hidden" name="tourismPrice" class="form-control">

                            </div>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Code')}} </label>
                                <input type="text" name="tourismCode" class="form-control" minlength="5" maxlength="5" placeholder="{{ __('Code').' '.__('Tourism') }}...."  required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="logoFile">Logo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="tourismLogo" accept="image/*" id="logoFile" required>
                                        <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Address') }}</label>
                                <textarea class="form-control" name="tourismAddress" rows="3" placeholder="Enter ..."></textarea>
                              </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="searchAddress" class="control-label">Lokasi</label>
                            <div class="input-group">
                                <input id="searchAddress" type="text" class="form-control" placeholder="Masukkan koordinat (latitude, longitude) / alamat lengkap / nama tempat / nama jalan / kelurahan / kecamatan / kode pos / kota / kabupaten">
                                <span class="input-group-btn">
                                    <button id="geocode" class="btn btn-info btn-flat" type="button">Cari</button>
                                </span>
                            </div>    
                        </div>
    
                        <div class="form-group col-md-12">
                            <div id="map" style="width:100%;height:380px;"></div>
                        </div>
    
                        <div class="form-group col-md-12">
                            <label class="control-label">Koordinat Lokasi</label>
                            <input id="position" type="text" class="form-control" name="tourismPosition" value="{{ old('tourismPosition') }}" readonly>
                        </div>
                    </div>
                    
                </div>
            </div>  
        </div>
    </form>
</div>
    
@stop


@section('plugins.bsCustomFileInput', true)

@section('adminlte_js')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&language=id&region=ID"></script>
    <script type="text/javascript">       

        $(document).ready(function() {
            bsCustomFileInput.init();
            var $form = $( "#form_1" );
            var $input = $form.find( "#price-separator");
            var $input_hidden = $form.find("#price");

            $input.on( "keyup", function( event ) {
                if (event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return value
                    // Keep only digits and decimal points:
                    .replace(/[^\d.]/g, "")
                    // Remove duplicated decimal point, if one exists:
                    .replace(/^(\d*\.)(.*)\.(.*)$/, '$1$2$3')
                    // Keep only two digits past the decimal point:
                    .replace(/\.(\d{2})\d+/, '.$1')
                    // Add thousands separators:
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                });    
                parseFloat($input_hidden.val($(this).val().replace(/,/g, '')));

            });
        });

        var map, infoWindow, marker, geocoder;

        function initMap() {
            var defaultLatitude = -6.3146898;
            var defaultLongitude = 107.3025944;
            var defaultPosition = {lat:defaultLatitude, lng:defaultLongitude};

            document.getElementById('position').value = defaultLatitude + ',' + defaultLongitude;
            
            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultPosition,
                zoom: 16
            });
            geocoder = new google.maps.Geocoder();
            marker = new google.maps.Marker({position: defaultPosition, map: map, draggable:true});
            infoWindow = new google.maps.InfoWindow;

            document.getElementById('geocode').addEventListener('click', function() {
                if ($('#searchAddress').val() == '') {
                    swal('Error!', 'Kata kunci lokasi tidak boleh kosong.', 'error');
                    return;
                }

                geocodeAddress(geocoder, map, marker);
            });

            google.maps.event.addListener(marker, 'dragend', function(evt){
                document.getElementById('position').value = evt.latLng.lat() + ',' + evt.latLng.lng();
                map.setCenter(marker.position);
                marker.setMap(map);
            });
        
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    map.setCenter(pos);
                    marker.setPosition(pos);
                    document.getElementById('position').value = position.coords.latitude + ',' + position.coords.longitude;
                }, function() {
                   // handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                //handleLocationError(false, infoWindow, map.getCenter());
            }
        }

        function geocodeAddress(geocoder, resultsMap, marker) {
            var address = document.getElementById('searchAddress').value;
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
                    resultsMap.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);

                    var position = results[0].geometry.location.toString().split(', ').toString();
                    var stringLength = position.length;
                    document.getElementById('position').value = position.substring(1, stringLength - 1);
                } else {
                    swal('Error!', 'Geocode tidak berhasil karena alasan berikut: ' + status, 'error');
                }
            });
        }

       /* function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                                    'Error: Layanan geolokasi gagal.' :
                                    'Error: Web browser Anda tidak mendukung geolokasi.');
            infoWindow.open(map);
        }*/
    </script>
@endsection




