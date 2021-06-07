
@extends('adminlte::page')

@section('title', 'Show Pariwisata')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Show').' '.__('Tourism') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master').' '.__('Tourism') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Show').' '.__('Tourism') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <div class="d-flex">
                    <div class="mr-auto">
                        <h3 class="card-title mt-1">
                            <i class="fa fa-store-alt"></i>
                                &nbsp; {{ __('Show').' '.__('Tourism') }}
                        </h3>
                    </div>
                    <div class="mr-1">
                        <a href="{{ route('tourism-info.index') }}" class="btn btn-secondary btn-flat btn-sm">
                            <i class="fa fa-arrow-left"></i>
                            &nbsp;&nbsp;{{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> {{ __('Name').' '.__('Tourism') }} </label>
                            <input type="text" name="tourismName" class="form-control" value="{{  $tourismInfo->name }}"  disabled>
                        </div>
                    </div>
                    {{-- <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Price') }}</label>
                            <input id="price-separator" type="text" class="form-control" value="{{ number_format($tourismInfo->price) }}" disabled>
                        </div>
                    </div> --}}
                    <div class="col-sm-3" id="category">
                        @if (count($tourismInfoCategories))
                            @foreach ($tourismInfoCategories as $i => $tourismInfoCategory)
                            <div class="form-group">
                                <label>{{ __('Category'). ' ' . ($i+1) }}</label>
                                <input id="category[{{ $i }}]" type="text" name="tourismCategories[{{ $i }}]" class="form-control" value="{{ $tourismInfoCategory->name }}" placeholder="{{ __('Name').' '.__('Category') }}...." disabled>
                            </div>
                            @endforeach
                        @else
                            <div class="form-group">
                                <label>{{ __('Category'). ' ' . (1) }}</label>
                                <input id="category[{{ 0 }}]" type="text" name="tourismCategories[{{ 0 }}]" class="form-control" value="Umum" placeholder="{{ __('Name').' '.__('Category') }}...." disabled>
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-3" id="price">
                        @if (count($tourismInfoCategories))
                            @foreach ($tourismInfoCategories as $i => $tourismInfoCategory)
                                <div class="form-group">
                                    <label>{{ __('Price') }}</label>
                                    <input id="price-separator[{{ $i }}]" name="priceSeparator[{{ $i }}]" type="text" class="form-control" value="{{ $tourismInfoCategory->price }}" placeholder="{{ __('Price') }}...." data-a-sign="Rp. " data-a-dec="," data-a-sep="." disabled>
                                </div>
                            @endforeach
                        @else
                            <div class="form-group">
                                <label>{{ __('Price') }}</label>
                                <input id="price-separator[{{ 0 }}]" name="priceSeparator[{{ 0 }}]" type="text" class="form-control" value="{{ $tourismInfo->price }}" placeholder="{{ __('Price') }}...." data-a-sign="Rp. " data-a-dec="," data-a-sep="." disabled>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Address') }}</label>
                            <textarea class="form-control" name="tourismShowress" rows="3" disabled>{{ $tourismInfo->address  }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            @php
                                if($tourismInfo->is_active == 0){
                                    $status = 'Inactive';
                                }elseif($tourismInfo->is_active == 1){
                                    $status = 'Active';
                                }
                            @endphp
                            <label>{{ __('Status') }}</label>
                            <input id="price-separator" type="text" class="form-control" value="{{ __($status) }}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="logoFile">Logo Pariwisata</label><br/>
                            <a href="{{ $tourismInfo->url_logo }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ $tourismInfo->url_logo  }}"></a>
                        </div>
                    </div>
                    @if ($tourismInfo->logo_bumdes != NULL)
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="logoFile">Logo Bumdes</label><br/>
                            <a href="{{ $tourismInfo->logo_bumdes }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ $tourismInfo->logo_bumdes  }}"></a>
                        </div>
                    </div>
                    @endif



                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="searchAddress" class="control-label">Lokasi</label>
                        <div class="input-group">
                            <input id="searchAddress" type="text" required class="form-control" placeholder="Masukkan koordinat (latitude, longitude) / alamat lengkap / nama tempat / nama jalan / kelurahan / kecamatan / kode pos / kota / kabupaten">
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

</div>

@stop

@section('plugins.Datatables', true)
@section('plugins.bsCustomFileInput', true)

@section('adminlte_js')
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&language=id&region=ID"></script>
<script src="{{ asset(mix('js/autonumeric/autonumeric.js')) }}" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            bsCustomFileInput.init();
            $('[name^="priceSeparator"]').autoNumeric('init');
        });

        var map, infoWindow, marker, geocoder;

        function initMap() {
            var defaultLatitude = {{$tourismInfo->latitude}};
            var defaultLongitude = {{$tourismInfo->longitude}};
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
                    //handleLocationError(true, infoWindow, map.getCenter());
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

        /*function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                                    'Error: Layanan geolokasi gagal.' :
                                    'Error: Web browser Anda tidak mendukung geolokasi.');
            infoWindow.open(map);
        }*/
    </script>
@endsection




