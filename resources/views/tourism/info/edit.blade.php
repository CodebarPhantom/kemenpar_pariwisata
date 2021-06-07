
@extends('adminlte::page')

@section('title', 'Edit Pariwisata')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Edit').' '.__('Tourism') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master').' '.__('Tourism') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Edit').' '.__('Tourism') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" id="form_1" action="{{ route('tourism-info.update',$tourismInfo->id) }}" method="POST" class="col-md-12" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="mr-auto">
                            <h3 class="card-title mt-1">
                                <i class="fa fa-store-alt"></i>
                                    &nbsp; {{ __('Edit').' '.__('Tourism') }}
                            </h3>
                        </div>
                        <div class="mr-1">
                            <a href="{{ route('tourism-info.index') }}" class="btn btn-secondary btn-flat btn-sm">
                                <i class="fa fa-arrow-left"></i>
                                &nbsp;&nbsp;{{ __('Back') }}
                            </a>
                        </div>
                        <div class="">
                            <button id="submit_tourism" type="submit" class="btn btn-info btn-flat btn-sm">
                                <i class="fa fa-check"></i>
                                &nbsp;&nbsp;{{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button id="add-category" class="btn btn-success btn-flat btn-sm ">
                                <i class="fa fa-plus"></i>
                                {{ __('Add').' '.__('Category') }}
                            </button>
                            <button id="remove-category" class="btn btn-danger btn-flat btn-sm ">
                                <i class="fa fa-minus"></i>
                                {{ __('Remove').' '.__('Category') }}
                            </button>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label> {{ __('Code')}} </label>
                                <input type="text" name="tourismCode" class="form-control" minlength="5" maxlength="5" value="{{  $tourismInfo->code }}" placeholder="{{ __('Code').' '.__('Tourism') }}...."  required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Name').' '.__('Tourism') }} </label>
                                <input type="text" name="tourismName" class="form-control" placeholder="{{ __('Name').' '.__('Tourism') }}...." value="{{  $tourismInfo->name }}"  required>
                            </div>
                        </div>
                        {{-- <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Price') }}</label>
                                <input id="price-separator" type="text" class="form-control" value="{{ number_format($tourismInfo->price) }}" placeholder="Price....">
                                <input id="price" type="hidden" name="tourismPrice" value="{{ $tourismInfo->price}}" class="form-control">

                            </div>
                        </div> --}}
                        <div class="col-sm-3" id="category">
                            @if (count($tourismInfoCategories))
                                @foreach ($tourismInfoCategories as $i => $tourismInfoCategory)
                                <div class="form-group">
                                    <label>{{ __('Category'). ' ' . ($i+1) }}</label>
                                    <input id="category[{{ $i }}]" type="text" name="tourismCategories[{{ $i }}]" class="form-control" value="{{ $tourismInfoCategory->name }}" placeholder="{{ __('Name').' '.__('Category') }}....">
                                    <input type="hidden" name="tourismCategoriesId[{{ $i }}]"  value="{{ $tourismInfoCategory->id }}" class="form-control">
                                </div>
                                @endforeach
                            @else
                                <div class="form-group">
                                    <label>{{ __('Category'). ' ' . (1) }}</label>
                                    <input id="category[{{ 0 }}]" type="text" name="tourismCategories[{{ 0 }}]" class="form-control" value="Umum" placeholder="{{ __('Name').' '.__('Category') }}....">
                                    <input type="hidden" name="tourismCategoriesId[{{ 0 }}]"  value="" class="form-control">
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-3" id="price">
                            @if (count($tourismInfoCategories))
                                @foreach ($tourismInfoCategories as $i => $tourismInfoCategory)
                                    <div class="form-group">
                                        <label>{{ __('Price') }}</label>
                                        <input id="price-separator[{{ $i }}]" name="priceSeparator[{{ $i }}]" type="text" class="form-control" value="{{ $tourismInfoCategory->price }}" placeholder="{{ __('Price') }}...." data-a-sign="Rp. " data-a-dec="," data-a-sep=".">
                                        <input id="price[{{ $i }}]" type="hidden" name="tourismPrice[{{ $i }}]"  value="{{ $tourismInfoCategory->price }}" class="form-control">
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group">
                                    <label>{{ __('Price') }}</label>
                                    <input id="price-separator[{{ 0 }}]" name="priceSeparator[{{ 0 }}]" type="text" class="form-control" value="{{ $tourismInfo->price }}" placeholder="{{ __('Price') }}...." data-a-sign="Rp. " data-a-dec="," data-a-sep=".">
                                    <input id="price[{{ 0 }}]" type="hidden" name="tourismPrice[{{ 0 }}]"  value="{{ $tourismInfo->price }}" class="form-control">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Insurance') }}</label>
                                <input type="text" class="form-control" name="tourismInsurance" value="{{ $tourismInfo->insurance}}" placeholder="{{ __('Name').' '.__('Insurance') }}....">
                                <span class="form-text text-muted">Jika tidak ada Asuransi maka dikosongkan saja kolom ini.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Manage').' '.__('By') }} </label>
                                <input type="text" name="tourismManageBy" class="form-control" value="{{ $tourismInfo->manage_by}}" placeholder="{{ __('Name').' Pengelola' }}...."  required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Address') }}</label>
                                <textarea class="form-control" name="tourismAddress" rows="3" placeholder="Address ...">{{ $tourismInfo->address  }}</textarea>
                              </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Perda') }}</label>
                                <textarea class="form-control" name="tourismNote1" rows="3" placeholder="Perda ...">{{ $tourismInfo->note1  }}</textarea>
                                <span class="form-text text-muted">Jika belum ada maka dikosongkan saja kolom ini.</span>

                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="logoFile">Logo Pariwisata</label><br/>
                                <a href="{{ $tourismInfo->url_logo }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ $tourismInfo->url_logo  }}"></a>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="tourismLogo" accept="image/*" id="logoFile">
                                        <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="logoFile">Logo Bumdes</label><br/>
                                @if ($tourismInfo->logo_bumdes != NULL)
                                    <a href="{{ $tourismInfo->logo_bumdes }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ $tourismInfo->logo_bumdes  }}"></a>
                                @endif
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="tourismLogoBumdes" accept="image/*" id="logoFile">
                                        <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo Bumdes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>{{ __('Status') }}</label>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @if ($tourismInfo->is_active == 1 ) checked @endif name="is_active" value="1">
                                    <label class="form-check-label">{{ __('Active') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @if ($tourismInfo->is_active == 0 ) checked @endif  name="is_active" value="0">
                                    <label class="form-check-label">{{ __('Inactive') }}</label>
                                </div>
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
                            <input id="position" type="text" class="form-control" name="tourismPosition" value="{{ old('tourismPosition',$tourismInfo->latitude.','.$tourismInfo->longitude) }}" readonly>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </form>
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
            var $form = $( "#form_1" );
            $form.find('[name^="priceSeparator"]').autoNumeric('init');

            var $category = document.getElementById("category");
            var $price = document.getElementById("price");

            var $add_category = $form.find("#add-category");
            var $remove_category = $form.find("#remove-category");
            var $submit_tourism = $form.find("#submit_tourism");

            $add_category.on("click", function(event) {
                event.preventDefault();

                $arrCategory = [];
                $arrPrice = [];

                for (i = 0; i < $category.childElementCount; i++) {
                    $catVal = $form.find('[name^="tourismCategories['+i+']"]').val();
                    $arrCategory.push($catVal);

                    $priceVal = $form.find('[name^="priceSeparator['+i+']"]').autoNumeric('get');
                    $arrPrice.push($priceVal);
                }

                if ($category.childElementCount == $price.childElementCount) {
                    $category.innerHTML += '<div class="form-group">' +
                                                '<label>{{ __("Category") }} ' + ($category.childElementCount+1) + ' </label>' +
                                                '<input id="category['+($category.childElementCount)+']" type="text" name="tourismCategories['+($category.childElementCount)+']" class="form-control" placeholder="{{ __("Name").' '.__("Category") }}....">' +
                                                '<input type="hidden" name="tourismCategoriesId['+($category.childElementCount)+']"  value="" class="form-control">'+
                                            '</div>';
                    $price.innerHTML += '<div class="form-group">' +
                                            '<label>{{ __("Price") }}</label>' +
                                            '<input id="price-separator['+($price.childElementCount)+']" name="priceSeparator['+($price.childElementCount)+']" type="text" class="form-control" placeholder="Harga...." data-a-sign="Rp. " data-a-dec="," data-a-sep=".">' +
                                            '<input id="price['+($price.childElementCount)+']" type="hidden" name="tourismPrice['+($price.childElementCount)+']" class="form-control">' +
                                        '</div>';
                }

                for (i = 0; i < $category.childElementCount; i++) {
                    $form.find('[name^="tourismCategories['+i+']"]').val($arrCategory[i]);

                    $form.find('[name^="priceSeparator['+i+']"]').autoNumeric('init');
                    $form.find('[name^="priceSeparator['+i+']"]').autoNumeric('set', $arrPrice[i]);
                }
            });

            $remove_category.on("click", function(event) {
                event.preventDefault();
                if (($category.childElementCount == $price.childElementCount) && $category.childElementCount > 1){
                    $category.removeChild($category.lastChild);
                    $price.removeChild($price.lastChild);
                }
            });

            $submit_tourism.on("click", function(event) {
                for (i = 0; i < $category.childElementCount; i++) {
                    $value = $form.find('[name^="priceSeparator['+i+']"]').autoNumeric('get');
                    $form.find('[name^="tourismPrice['+i+']"]').val($value);
                }

                return true;
           });
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




