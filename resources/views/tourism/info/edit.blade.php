
@extends('adminlte::page')

@section('title', 'Edit Pariwisata')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/css/fileinput.min.css" integrity="sha512-8KeRJXvPns3KF9uGWdZW18Azo4c1SG8dy2IqiMBq8Il1wdj7EWtR3EGLwj+DnvznrRjn0oyBU+OEwJk7A79n7w==" crossorigin="anonymous" />
@endpush

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
<div class="container-fluid">
    <form role="form" id="form_1" action="{{ route('tourism-info.update',$tourismInfo->id) }}" method="POST" class="" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-md-12">
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
                        @if ($errors->all())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                        @endif
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
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label> {{ __('Code')}} </label>
                                    <input type="text" name="tourismCode" class="form-control @error('tourismCode') is-invalid @enderror" minlength="5" maxlength="5" value="{{ old('tourismCode', $tourismInfo->code) }}" placeholder="{{ __('Code').' '.__('Tourism') }}...."  required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label> {{ __('Name').' '.__('Tourism') }} </label>
                                    <input type="text" name="tourismName" class="form-control @error('tourismName') is-invalid @enderror" placeholder="{{ __('Name').' '.__('Tourism') }}...." value="{{ old('tourismName', $tourismInfo->name) }}"  required>
                                </div>
                            </div>
                            {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Price') }}</label>
                                    <input id="price-separator" type="text" class="form-control" value="{{ old('tourismPrice', number_format($tourismInfo->price) ) }}" placeholder="Price....">
                                    <input id="price" type="hidden" name="tourismPrice" value="{{ old('tourismPrice', $tourismInfo->price ) }}" class="form-control">
    
                                </div>
                            </div> --}}
                            <div class="col-sm-3" id="category">
                                @if (count($tourismInfoCategories))
                                    @foreach ($tourismInfoCategories as $i => $tourismInfoCategory)
                                    <div class="form-group" data-index="{{ $i }}">
                                        <label>{{ __('Category').' '.__('Ticket'). ' ' . ($i+1) }}</label>
                                        <input id="category[{{ $i }}]" type="text" name="tourismCategories[{{ $i }}]" class="form-control @error('tourismCategories.'.$i) is-invalid @enderror" value="{{ old('tourismCategories.'.$i, $tourismInfoCategory->name) }}" placeholder="{{ __('Name').' '.__('Category') }}...." required>
                                        <input type="hidden" name="tourismCategoriesId[{{ $i }}]"  value="{{ $tourismInfoCategory->id }}" class="form-control">
                                    </div>
                                    @endforeach
                                @else
                                    <div class="form-group" data-index="{{ 0 }}">
                                        <label>{{ __('Category').' '.__('Ticket'). ' ' . (1) }}</label>
                                        <input id="category[{{ 0 }}]" type="text" name="tourismCategories[{{ 0 }}]" class="form-control @error('tourismCategories.0') is-invalid @enderror" value="{{ old('tourismCategories.0', 'Umum' ) }}" placeholder="{{ __('Name').' '.__('Category') }}...." required>
                                        <input type="hidden" name="tourismCategoriesId[{{ 0 }}]"  value="" class="form-control">
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-3" id="price">
                                @if (count($tourismInfoCategories))
                                    @foreach ($tourismInfoCategories as $i => $tourismInfoCategory)
                                        <div class="form-group">
                                            <label>{{ __('Price').' '.__('Ticket'). ' ' . ($i+1) }}</label>
                                            <div class="input-group">
                                                <input id="price-separator[{{ $i }}]" name="priceSeparator[{{ $i }}]" type="text" class="form-control @error('tourismPrice.'.$i) is-invalid @enderror" value="{{ old('priceSeparator.'.$i, $tourismInfoCategory->price) }}" placeholder="{{ __('Price') }}...." data-a-sign="Rp. " data-a-dec="," data-a-sep="." required>
                                                <input id="price[{{ $i }}]" type="hidden" name="tourismPrice[{{ $i }}]"  value="{{ old('tourismPrice.'.$i, $tourismInfoCategory->price) }}" class="form-control">
                                                <span class="input-group-append">
                                                    <button type="button" onClick="removeCategory({{ $i }})" class="btn btn-danger btn-flat">
                                                        {{ __('Remove') }}
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group">
                                        <label>{{ __('Price').' '.__('Ticket'). ' ' . (1) }}</label>
                                        <div class="input-group">
                                            <input id="price-separator[{{ 0 }}]" name="priceSeparator[{{ 0 }}]" type="text" class="form-control @error('tourismPrice.0') is-invalid @enderror" value="{{ old('priceSeparator.0', $tourismInfo->price) }}" placeholder="{{ __('Price') }}...." data-a-sign="Rp. " data-a-dec="," data-a-sep="." required>
                                            <input id="price[{{ 0 }}]" type="hidden" name="tourismPrice[{{ 0 }}]"  value="{{ old('tourismPrice.0', $tourismInfo->price) }}" class="form-control">
                                            <span class="input-group-append">
                                                <button type="button" onClick="removeCategory({{ 0 }})"  class="btn btn-danger btn-flat">
                                                    {{ __('Remove') }}
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">                        
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> {{ __('Manage').' '.__('By') }} </label>
                                    <input type="text" name="tourismManageBy" class="form-control @error('tourismManageBy') is-invalid @enderror" value="{{ old('tourismManageBy', $tourismInfo->manage_by) }}" placeholder="{{ __('Name').' Pengelola' }}...."  required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Insurance') }}</label>
                                    <input type="text" class="form-control @error('tourismInsurance') is-invalid @enderror" name="tourismInsurance" value="{{ old('tourismInsurance', $tourismInfo->insurance) }}" placeholder="{{ __('Name').' '.__('Insurance') }}....">
                                    <span class="form-text text-muted">Jika tidak ada Asuransi maka dikosongkan saja kolom ini.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Perda') }}</label>
                                    <input class="form-control @error('tourismNote1') is-invalid @enderror" name="tourismNote1" value="{{ old('tourismNote1', $tourismInfo->note1) }}" placeholder="Perda ...">
                                    <span class="form-text text-muted">Jika belum ada maka dikosongkan saja kolom ini.</span>
    
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="text" class="form-control @error('tourismPhone') is-invalid @enderror" value="{{ old('tourismPhone', $tourismInfo->phone) }}" name="tourismPhone"  placeholder="{{ __('Phone').' '.__('Pariwisata') }}....">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> {{ __('Facebook') }} </label>
                                    <input type="text" name="tourismFacebook" class="form-control @error('tourismFacebook') is-invalid @enderror" value="{{ old('tourismFacebook', $tourismInfo->facebook) }}" placeholder="{{ __('Facebook') }}...." >
                                    <span class="form-text text-muted">Jika tidak ada Asuransi maka dikosongkan saja kolom ini.</span>                                
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Instagram') }}</label>
                                    <input class="form-control @error('tourismInstagram') is-invalid @enderror" name="tourismInstagram" value="{{ old('tourismInstagram', $tourismInfo->instagram) }}" placeholder="Instagram...." />
                                    <span class="form-text text-muted">Jika belum ada maka dikosongkan saja kolom ini.</span>
    
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Alamat') }}</label>
                                    <textarea class="form-control @error('tourismAddress') is-invalid @enderror" name="tourismAddress" rows="3" placeholder="Address ...">{{ old('tourismAddress', $tourismInfo->address) }}</textarea>
                                  </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Gambaran Singkat Pariwisata') }}</label>
                                    <textarea class="form-control @error('tourismOverview') is-invalid @enderror" name="tourismOverview" rows="3" placeholder="Gambaran Singkat Pariwisata ..."> {{ old('tourismOverview',$tourismInfo->overview) }}</textarea>
                                  </div>
                            </div>                             
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="logoFile">Gambar Cover</label><br/>
                                    @if (old('tourismCoverImage', $tourismInfo->url_cover_image) != NULL)
                                        <a href="{{ old('tourismCoverImage', $tourismInfo->url_cover_image ) }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ old('tourismCoverImage', $tourismInfo->url_cover_image) }}"></a>
                                    @endif
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('tourismCoverImage') is-invalid @enderror" name="tourismCoverImage" accept="image/*" id="logoFile">
                                            <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">                            
                                <div class="form-group">
                                    <label for="logoFile">Logo Pariwisata</label><br/>
                                    <a href="{{ old('tourismLogo', $tourismInfo->url_logo ) }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ old('tourismLogo', $tourismInfo->url_logo) }}"></a>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('tourismLogo') is-invalid @enderror" name="tourismLogo" accept="image/*" id="logoFile">
                                            <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="logoFile">Logo Bumdes</label><br/>
                                    @if (old('tourismLogoBumdes', $tourismInfo->logo_bumdes) != NULL)
                                        <a href="{{ old('tourismLogoBumdes', $tourismInfo->logo_bumdes) }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ old('tourismLogoBumdes', $tourismInfo->logo_bumdes) }}"></a>
                                    @endif
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('tourismLogoBumdes') is-invalid @enderror" name="tourismLogoBumdes" accept="image/*" id="logoFile">
                                            <label class="custom-file-label" for="logoFile">{{ __('Choose') }} Logo Bumdes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label>{{ __('Status') }}</label>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="radio" @if (old('is_active', $tourismInfo->is_active) == 1) checked @endif name="is_active" value="1">
                                        <label class="form-check-label">{{ __('Active') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="radio" @if (old('is_active', $tourismInfo->is_active) == 0) checked @endif name="is_active" value="0">
                                        <label class="form-check-label">{{ __('Inactive') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fasilitas</label>
                                    <select id="amenities" class="form-control select2" style="width: 100%;" name="amenities[]" multiple="multiple">
                                        @foreach ($amenities as $amenity)                                            
                                            <option value="{{ $amenity->id }}"  {{ old('amenities', in_array($amenity->id, $tourismInfoAmenities)) == $amenity->id ? 'selected' : '' }}>{{  $amenity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>

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
                    @php
                        $operationalTourism = json_decode($tourismInfo->opening_hour);
                    @endphp
                    <div class="card-body">
                        <div class="col-lg-12" style="float:none;margin:auto;">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Senin</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="day[0]" class="form-control" value="{{ old('day[0]',$operationalTourism[0]->day) }}">
                                    <input type="text" name="opening_hour[0]" class="form-control" value="{{ old('opening_hour[0]',$operationalTourism[0]->opening_hour) }}" required placeholder="10.00 - 23.50">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Selasa</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="day[1]" class="form-control" value="{{ old('day[1]',$operationalTourism[1]->day) }}">
                                    <input type="text" name="opening_hour[1]" class="form-control" value="{{ old('opening_hour[1]',$operationalTourism[1]->opening_hour) }}" required placeholder="10.00 - 23.50">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Rabu</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="day[2]" class="form-control" value="{{ old('day[2]',$operationalTourism[2]->day) }}">
                                    <input type="text" name="opening_hour[2]" class="form-control" value="{{ old('opening_hour[2]',$operationalTourism[2]->opening_hour) }}" required placeholder="10.00 - 23.50">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Kamis</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="day[3]" class="form-control" value="{{ old('day[3]',$operationalTourism[3]->day) }}">
                                    <input type="text" name="opening_hour[3]" class="form-control" value="{{ old('opening_hour[3]',$operationalTourism[3]->opening_hour) }}" required placeholder="10.00 - 23.50">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Jumat</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="day[4]" class="form-control" value="{{ old('day[4]',$operationalTourism[4]->day) }}">
                                    <input type="text" name="opening_hour[4]" class="form-control" value="{{ old('opening_hour[4]',$operationalTourism[4]->opening_hour) }}" required placeholder="10.00 - 23.50">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Sabtu</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="day[5]" class="form-control" value="{{ old('day[5]',$operationalTourism[5]->day) }}">
                                    <input type="text" name="opening_hour[5]" class="form-control" value="{{ old('opening_hour[5]',$operationalTourism[5]->opening_hour) }}" required placeholder="10.00 - 23.50">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Minggu</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="day[6]" class="form-control" value="{{ old('day[6]',$operationalTourism[6]->day) }}">
                                    <input type="text" name="opening_hour[6]" class="form-control" value="{{ old('opening_hour[6]',$operationalTourism[6]->opening_hour) }}" required placeholder="10.00 - 23.50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"> Lokasi</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
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
                                <input id="position" type="text" class="form-control @error('tourismPosition') is-invalid @enderror" name="tourismPosition" value="{{ old('tourismPosition',$tourismInfo->latitude.','.$tourismInfo->longitude) }}" readonly>
                            </div>
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

        var $arrCategories = [];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
            overwriteInitial: true,
            maxFileSize:1500,
            maxFilesNum: 5,

            initialPreview: [
                @php
                    use Illuminate\Support\Facades\Storage;
                @endphp     
                @foreach ($tourismInfo->galleries as $file)
                    '{{ $file->url_image }}',
                @endforeach
            ],
            initialPreviewAsData: true,
            initialPreviewConfig: [
                @foreach ($tourismInfo->galleries as $file)
                    @php
                        $mimeType = Storage::mimeType(str_replace('storage', 'public', parse_url($file->url_image, PHP_URL_PATH)));
                        $size = Storage::size(str_replace('storage', 'public', parse_url($file->url_image, PHP_URL_PATH)));
                        if (strpos($mimeType, 'postscript') || strpos($mimeType, 'tiff')) { 
                            $type = 'gdocs';
                        } elseif (strpos($mimeType, 'office')) {
                            $type = 'office';
                        } elseif (strpos($mimeType, 'pdf')) {
                            $type = 'pdf';
                        } elseif (strpos($mimeType, 'text')) {
                            $type = 'text';
                        } elseif (strpos($mimeType, 'html')) {
                            $type = 'html';
                        } else {
                            $type = 'image';
                        }
                    @endphp
                    { type: "{{ $type }}", caption: "{{ basename($file->url_image) }}", size: {{ $size }}, url: "{{ route('tourism-info.destroy-file') }}", key: {{ $file->id }}},
                @endforeach
            ],
            previewFileExtSettings: { // configure the logic for determining icon file extensions
                'doc': function(ext) {
                    return ext.match(/(doc|docx)$/i);
                },
                'xls': function(ext) {
                    return ext.match(/(xls|xlsx)$/i);
                },
                'ppt': function(ext) {
                    return ext.match(/(ppt|pptx)$/i);
                },
                'zip': function(ext) {
                    return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                },
                'htm': function(ext) {
                    return ext.match(/(htm|html)$/i);
                },
                'txt': function(ext) {
                    return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                },
                'mov': function(ext) {
                    return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                },
                'mp3': function(ext) {
                    return ext.match(/(mp3|wav)$/i);
                }
            }
        }); 

        @foreach ($tourismInfoCategories as $i => $tourismInfoCategory)
            $arrCategories.push({{ $i }});
        @endforeach

        $(document).ready(function() {
            bsCustomFileInput.init();
            $form = $( "#form_1" );
            $form.find('[name^="priceSeparator"]').autoNumeric('init');
            $('#amenities').select2({ placeholder: 'Pilih Fasilitas yang Tersedia'});


            $category = document.getElementById("category");
            $price = document.getElementById("price");

            var $add_category = $form.find("#add-category");
            var $remove_category = $form.find("#remove-category");
            var $submit_tourism = $form.find("#submit_tourism");

            $add_category.on("click", function(event) {
                event.preventDefault();

                $arrCategory = [];
                $arrPrice = [];

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




