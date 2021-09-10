
@extends('adminlte::page')

@section('title', 'Tambah Fasilitas')

@push('css')
<!-- Bootstrap CDN -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"/>
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
<!-- Bootstrap-Iconpicker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/css/bootstrap-iconpicker.min.css" />


<style>
	.select-fontawesome{
		font-family: fontAwesome
	}
</style>
@endpush

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Create').' '.__('Fasilitas') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Setting') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Fasilitas') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form role="form" id="form_1" action="{{ route('setting.amenities.store') }}" method="POST" class="" >
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="mr-auto">
                                <h3 class="card-title mt-1">
                                    <i class="fa fa-store-alt"></i>
                                        &nbsp; {{ __('Create').' '.__('Fasilitas') }}
                                </h3>
                            </div>
                            <div class="mr-1">
                                <a href="{{ route('setting.amenities.index') }}" class="btn btn-secondary btn-flat btn-sm">
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
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> {{ __('Name')}} </label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name') }}" placeholder="{{ __('Name')}}...." required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ __('Category') }}</label>
                                    <select class="form-control select2 @error('category') is-invalid @enderror" style="width: 100%;" name="category">
                                        <option value="Fasilitas Umum">Fasilitas Umum</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Kesehatan">Kesehatan</option>
                                        <option value="Transportasi">Transportasi</option>
                                        <option value="Disabilitas">Disabilitas</option>
                                        <option value="Fasilitas Lainnya">Fasilitas Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> {{ __('Icon') }} </label>
                                    <!-- Button tag -->
                                    <button class="form-control btn btn-secondary" data-rows="3"
                                    data-cols="12" name="icon" role="iconpicker"></button>
                                </div>
                            </div>

                        </div>
                    </div>         
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('plugins.Select2', true)

@section('adminlte_js')

<!-- Bootstrap CDN -->
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap-Iconpicker Bundle -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/js/bootstrap-iconpicker.bundle.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize select2
            initailizeSelect2();



        });

        function initailizeSelect2(){
            $(".select2").select2({
                placeholder: 'Pilih Category',
                theme: 'bootstrap4',
                allowClear: true,
               
            });
        }
    </script>
@endsection




