
@extends('adminlte::page')

@section('title', 'Add Pariwisata')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/css/fileinput.min.css" integrity="sha512-8KeRJXvPns3KF9uGWdZW18Azo4c1SG8dy2IqiMBq8Il1wdj7EWtR3EGLwj+DnvznrRjn0oyBU+OEwJk7A79n7w==" crossorigin="anonymous" />
@endpush

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Create').' '.__('Tourism') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master').' '.__('Tourism') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Create').' '.__('Tourism') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @php
        if(Auth::check()){
            $routePost ='tourism-info.store';
        }else{
            $routePost ='tourism-register.store';
        }
    @endphp
    <form role="form" id="form_1" action="{{ route($routePost) }}" method="POST" class="" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="mr-auto">
                                <h3 class="card-title mt-1">
                                    <i class="fa fa-store-alt"></i>
                                        &nbsp; {{ __('Create').' '.__('Tourism') }}
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
                        @include('inc.error')
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button id="add-category" class="btn btn-success btn-flat btn-sm ">
                                    <i class="fa fa-plus"></i>
                                    {{ __('Add').' '.__('Category') }}
                                </button>
                                {{-- <button id="remove-category" class="btn btn-danger btn-flat btn-sm ">
                                    <i class="fa fa-minus"></i>
                                    {{ __('Remove').' '.__('Category') }}
                                </button> --}}
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label> {{ __('Name').' '.__('Tourism') }} </label>
                                    <input type="text" name="tourismName" class="form-control @error('tourismName') is-invalid @enderror" value="{{ old('tourismName') }}" placeholder="{{ __('Name').' '.__('Tourism') }}....">
                                </div>
                            </div>
                            <div class="col-sm-3" id="category">
                                <div class="form-group" data-index="0">
                                    <label>{{ __('Category').' '.__('Ticket'). ' ' . '1' }}</label>
                                    <input id="category[0]" type="text" name="tourismCategories[0]" class="form-control @error('tourismCategories') is-invalid @enderror" value="{{ old('tourismCategories.0') }}" placeholder="{{ __('Name').' '.__('Category') }}...." required>
                                </div>
                            </div>
                            <div class="col-sm-3" id="price">
                                <div class="form-group">
                                    <label>{{ __('Price').' '.__('Ticket'). ' ' . '1' }}</label>
                                    <div class="input-group">
                                        <input id="price-separator[{{ 0 }}]" name="priceSeparator[{{ 0 }}]" type="text" class="form-control @error('tourismPrice.0') is-invalid @enderror" value="{{ old('priceSeparator.0') }}" placeholder="{{ __('Price') }}...." data-a-sign="Rp. " data-a-dec="," data-a-sep="." required>
                                        <input id="price[{{ 0 }}]" type="hidden" name="tourismPrice[{{ 0 }}]"  value="{{ old('tourismPrice.0') }}" class="form-control">
                                        <span class="input-group-append">
                                            <button type="button" onClick="removeCategory({{ 0 }})"  class="btn btn-danger btn-flat">
                                                {{ __('Remove') }}
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label> {{ __('Code').' Pariwsata' }} </label>
                                    <input type="text" name="tourismCode" class="form-control @error('tourismCode') is-invalid @enderror" minlength="5" maxlength="5" value="{{ old('tourismCode') }}" placeholder="{{ __('Code').' '.__('Tourism') }}...." required>
                                    <span class="form-text text-muted">Tentukan Sendiri Kode Pariwisatanya harus 5 huruf</span>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="logoFile">Gambar Cover</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('tourismCoverImage') is-invalid @enderror" name="tourismCoverImage" value="{{ old('tourismCoverImage') }}" accept="image/*" id="logoFile" required>
                                            <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Gambar Cover</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="logoFile">Logo Pariwisata</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('tourismLogo') is-invalid @enderror" name="tourismLogo" value="{{ old('tourismLogo') }}" accept="image/*" id="logoFile" required>
                                            <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo Pariwisata</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="logoFile">Logo Bumdes</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('tourismLogoBumdes') is-invalid @enderror" name="tourismLogoBumdes" value="{{ old('tourismLogoBumdes') }}" accept="image/*" id="logoFile">
                                            <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo Bumdes</label>
                                        </div>
                                    </div>
                                    <span class="form-text text-muted">Jika tidak ada maka dikosongkan saja.</span>
        
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> {{ __('Manage').' '.__('By') }} </label>
                                    <input type="text" name="tourismManageBy" value="{{ old('tourismManageBy') }}" class="form-control @error('tourismManageBy') is-invalid @enderror" placeholder="{{ __('Name').' '.__('Pengelola') }}...."  required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Insurance') }}</label>
                                    <input type="text" class="form-control @error('tourismInsurance') is-invalid @enderror" name="tourismInsurance" value="{{ old('tourismInsurance') }}" placeholder="{{ __('Name').' '.__('Insurance') }}....">
                                    <span class="form-text text-muted">Jika tidak ada Asuransi maka dikosongkan saja kolom ini.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Perda') }}</label>
                                    <input class="form-control @error('tourismNote1') is-invalid @enderror" name="tourismNote1" value="{{ old('tourismNote1') }}" placeholder="Perda ..." />
                                    <span class="form-text text-muted">Jika belum ada maka dikosongkan saja kolom ini.</span>
        
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="text" class="form-control @error('tourismPhone') is-invalid @enderror" name="tourismPhone" value="{{ old('tourismPhone') }}" placeholder="{{ __('Phone').' '.__('Pariwisata') }}....">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> {{ __('Facebook') }} </label>
                                    <input type="text" name="tourismFacebook" value="{{ old('tourismFacebook') }}" class="form-control @error('tourismFacebook') is-invalid @enderror" placeholder="{{ __('Facebook') }}...." >
                                    <span class="form-text text-muted">Jika tidak ada Asuransi maka dikosongkan saja kolom ini.</span>                                
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Instagram') }}</label>
                                    <input class="form-control @error('tourismInstagram') is-invalid @enderror" name="tourismInstagram" value="{{ old('tourismInstagram') }}" placeholder="Instagram...." />
                                    <span class="form-text text-muted">Jika belum ada maka dikosongkan saja kolom ini.</span>
        
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Alamat') }}</label>
                                    <textarea class="form-control @error('tourismAddress') is-invalid @enderror" name="tourismAddress" rows="3" value="{{ old('tourismAddress') }}" placeholder="Alamat ..."></textarea>
                                    </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Gambaran Singkat Pariwisata') }}</label>
                                    <textarea class="form-control @error('tourismOverview') is-invalid @enderror" name="tourismOverview" rows="3" value="{{ old('tourismOverview') }}" placeholder="Gambaran Singkat Pariwisata ..."></textarea>
                                    </div>
                            </div>                        
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fasilitas</label>
                                    <select id="amenities" class="form-control select2" style="width: 100%;" name="amenities[]" multiple="multiple">
                                        @foreach ($amenities as $amenity)                                            
                                            <option value="{{ $amenity->id }}"><i class="{{ $amenity->icon }}"></i>{{  $amenity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="form-text text-muted">Boleh dikosongkan terlebih dahulu</span>

                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
          
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Gallery</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-12" >
                                <input id="gallery" type="file" multiple name="gallery[]" data-overwrite-initial="false" accept="image/*" >
                            </div>
                        </div>
                    <span class="form-text text-danger">Jika ingin melampirkan lebih dari satu gambar simpan pada satu folder yang sama lalu block semua gambar</span>
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Jam Operasional</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                            <div class="col-lg-12" style="float:none;margin:auto;">
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Senin</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="day[0]" class="form-control" value="Senin">
                                        <input type="text" name="opening_hour[0]" class="form-control" value="10.00 - 23.50" required placeholder="10.00 - 23.50">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Selasa</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="day[1]" class="form-control" value="Selasa">
                                        <input type="text" name="opening_hour[1]" class="form-control" value="10.00 - 23.50" required placeholder="10.00 - 23.50">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Rabu</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="day[2]" class="form-control" value="Rabu">
                                        <input type="text" name="opening_hour[2]" class="form-control" value="10.00 - 23.50" required placeholder="10.00 - 23.50">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Kamis</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="day[3]" class="form-control" value="Kamis">
                                        <input type="text" name="opening_hour[3]" class="form-control" value="10.00 - 23.50" required placeholder="10.00 - 23.50">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Jumat</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="day[4]" class="form-control" value="Jumat">
                                        <input type="text" name="opening_hour[4]" class="form-control" value="10.00 - 23.50" required placeholder="10.00 - 23.50">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Sabtu</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="day[5]" class="form-control" value="Sabtu">
                                        <input type="text" name="opening_hour[5]" class="form-control" value="10.00 - 23.50" required placeholder="10.00 - 23.50">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Minggu</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="day[6]" class="form-control" value="Minggu">
                                        <input type="text" name="opening_hour[6]" class="form-control" value="10.00 - 23.50" required placeholder="10.00 - 23.50">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>


            <div class="col-md-8">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Lokasi</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
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
                                <input id="position" type="text" class="form-control @error('tourismPosition') is-invalid @enderror" name="tourismPosition" value="{{ old('tourismPosition') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>      
        </div>
           
    </form>

    
</div>


@stop

@section('plugins.bsCustomFileInput', true)

@section('plugins.Select2', true)

@section('adminlte_js')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&language=id&region=ID"></script>
    <script src="{{ asset(mix('js/autonumeric/autonumeric.js')) }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/js/fileinput.min.js" integrity="sha512-vDrq7v1F/VUDuBTB+eILVfb9ErriIMW7Dn3JC/HOQLI8ZzTBTRRKrKJO3vfMmZFQpEGVpi+EYJFatPgVFxOKGA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/themes/fas/theme.min.js" integrity="sha512-BeQMmfGMfVp5kEkEGxUtlT5R9+m7jDVr5LDFCG2EK9VR50cEhR0kKzD5bn3XtSit/qNoYQUtr405lf5aSCSF8A==" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var $form = $( "#form_1" );
        var $category = document.getElementById("category");
        var $price = document.getElementById("price");

        var $arrCategories = [0];

        $(document).ready(function() {
            bsCustomFileInput.init();
            $('#amenities').select2({ placeholder: 'Pilih Fasilitas yang Tersedia'});

            $form = $( "#form_1" );
            $form.find('[name^="priceSeparator"]').autoNumeric('init');

            $category = document.getElementById("category");
            $price = document.getElementById("price");

            var $add_category = $form.find("#add-category");
            var $remove_category = $form.find("#remove-category");
            var $submit_tourism = $form.find("#submit_tourism");

            $add_category.on("click", function(event) {
                event.preventDefault();

                $arrCategory = [];
                $arrPrice = [];

                // for (i = 0; i < $category.childElementCount; i++) {
                //     $catVal = $form.find('[name^="tourismCategories['+i+']"]').val();
                //     $arrCategory.push($catVal);

                //     $priceVal = $form.find('[name^="priceSeparator['+i+']"]').autoNumeric('get');
                //     $arrPrice.push($priceVal);
                // }

                $arrCategories.forEach(element => {
                    $catVal = $form.find('[name^="tourismCategories['+element+']"]').val();
                    $arrCategory.push($catVal);

                    $priceVal = $form.find('[name^="priceSeparator['+element+']"]').autoNumeric('get');
                    $arrPrice.push($priceVal);
                });

                lastIndex = parseInt($category.lastElementChild.getAttribute('data-index'));

                if ($category.childElementCount == $price.childElementCount) {
                    $category.innerHTML += '<div class="form-group" data-index="' + (lastIndex + 1) + '">' +
                                                '<label>{{ __("Category")." ".__("Ticket"). " " }}' + (lastIndex + 2) + '</label>' +
                                                '<input id="category[' + (lastIndex + 1) + ']" type="text" name="tourismCategories[' + (lastIndex + 1) + ']" class="form-control" placeholder="{{ __("Name").' '.__("Category") }}...." required>' +
                                                '<input type="hidden" name="tourismCategoriesId[' + (lastIndex + 1) + ']"  value="" class="form-control">'+
                                            '</div>';
                    $price.innerHTML += '<div class="form-group">' +
                                            '<label>{{ __("Price")." ".__("Ticket"). " " }}' + (lastIndex + 2) + '</label>' +
                                            '<div class="input-group">' +
                                                '<input id="price-separator[' + (lastIndex + 1) + ']" name="priceSeparator[' + (lastIndex + 1) + ']" type="text" class="form-control" placeholder="Harga...." data-a-sign="Rp. " data-a-dec="," data-a-sep="." required>' +
                                                '<input id="price[' + (lastIndex + 1) + ']" type="hidden" name="tourismPrice[' + (lastIndex + 1) + ']" class="form-control">' +
                                                '<span class="input-group-append">' +
                                                    '<button type="button" onClick="removeCategory(' + (lastIndex + 1) + ')" class="btn btn-danger btn-flat">' +
                                                        '{{ __("Remove") }}' +
                                                    '</button>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>';
                }

                $arrCategories.push(lastIndex + 1);

                $arrCategories.forEach(element => {
                    $form.find('[name^="tourismCategories['+element+']"]').val($arrCategory[element]);

                    $form.find('[name^="priceSeparator['+element+']"]').autoNumeric('init');
                    if ($arrPrice[element] > 0 ) {
                        $form.find('[name^="priceSeparator['+element+']"]').autoNumeric('set', $arrPrice[element]);
                    }
                });
            });

            $remove_category.on("click", function(event) {
                event.preventDefault();
                if (($category.childElementCount == $price.childElementCount) && $category.childElementCount > 1){
                    $category.removeChild($category.lastChild);
                    $price.removeChild($price.lastChild);
                }
            });

            $submit_tourism.on("click", function(event) {
                $arrCategories.forEach(element => {
                    $value = $form.find('[name^="priceSeparator['+element+']"]').autoNumeric('get');
                    $form.find('[name^="tourismPrice['+element+']"]').val($value);
                });

                return true;
           });
        });

        $("#gallery").fileinput({
            theme: 'fas',
            previewFileType: "image",
            browseClass: "btn btn-success",
            browseLabel: "Pick Image",
            browseIcon: "<i style='color:white;' class=\"fa fa-images\"></i> ",
            removeClass: "btn btn-danger",
            removeLabel: "Delete",
            removeIcon: "<i style='color:white;' class=\"fa fa-trash\"></i> ",
            uploadClass: "btn btn-info",
            uploadLabel: "Upload",
            uploadIcon: "<i style='color:white;' class=\"fa fa-upload\"></i> ",
            showRemove: false,
            showUpload: false,
            required: true,
            overwriteInitial: false,
            maxFileSize:1500,
            maxFilesNum: 5,
        }); 

        function removeCategory(index) {
            if (($category.childElementCount == $price.childElementCount) && $category.childElementCount > 1){
                $form.find('[name^="tourismCategories['+index+']').parents('.form-group').remove();
                $form.find('[name^="priceSeparator['+index+']').parents('.form-group').remove();

                $arrCategories.forEach((e, i) => {
                    if ($arrCategories[i] === index) {
                        $arrCategories.splice(i, 1);
                    }
                });
            }
        }

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




