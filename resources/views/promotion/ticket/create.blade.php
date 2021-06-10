
@extends('adminlte::page')

@section('title', 'Buat Promosi')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Create').' '.__('Promotion') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master User') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Create').' '.__('Promotion') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" action="{{ route('ticket-promotion.store') }}" method="POST" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="mr-auto">
                            <h3 class="card-title mt-1">
                                <i class="fa fa-users"></i>
                                    &nbsp; {{ __('Create').' '.__('Promotion') }}
                            </h3>
                        </div>
                        <div class="mr-1">
                            <a href="{{ route('ticket-promotion.index') }}" class="btn btn-secondary btn-flat btn-sm">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Name').' '.__('Promotion') }} </label>
                                <input type="text" name="promotion_name" class="form-control @error('promotion_name') is-invalid @enderror" placeholder="Nama Promosi ..." value="{{ old('promotion_name') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Place').' '.__('Tourism') }}</label>
                                @if (Laratrust::hasRole('superadmin'))
                                    <select class="form-control select2-eryan @error('tourism_place') is-invalid @enderror" style="width: 100%;" name="tourism_place" required>
                                    </select>
                                @else
                                    <input type="text" class="form-control @error('tourism_place') is-invalid @enderror" value="{{ auth()->user()->tourism_info()->first()->code.' - '.auth()->user()->tourism_info()->first()->name }}" required disabled>
                                    <input type="hidden" name="tourism_place" class="form-control" value="{{ auth()->user()->tourism_info()->first()->id }}" required>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Date').' '.__('Start') }} </label>
                                <input type="text"  name="start_date" class="form-control daterange-single @error('start_date') is-invalid @enderror" value="{{ old('start_date', date("d-m-Y 00:00")) }}" readonly required/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Date').' '.__('End') }} </label>
                                <input type="text" name="end_date" class="form-control daterange-single @error('end_date') is-invalid @enderror" value="{{ old('end_date', date("d-m-Y 23:59")) }}" readonly required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Percentage').' %' }}</label>
                                <input type="number" min="0" max="100" name="percentage" class="form-control @error('percentage') is-invalid @enderror" placeholder="Persentase...." value="{{ old('percentage') }}" required>
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
@section('plugins.daterangepicker', true)


@section('adminlte_js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Initialize select2
            initailizeSelect2();
            bsCustomFileInput.init();



        });

        $('.daterange-single').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                timePicker:true,
                timePicker24Hour:true,
                locale: {
                    format: 'DD-MM-YYYY HH:mm'
                }
            });

        function initailizeSelect2(){
            $(".select2-eryan").select2({
                placeholder: 'Pilih Tempat Wisata',
                minimumInputLength: 2,
                theme: 'bootstrap4',
                allowClear: true,
                ajax: {
                    url : "{{ route('ticket-promotion.tourism') }}",
                    method : "POST",
                    dataType : 'json',
                    delay: 1000,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    },

                    processResults: function (response) {
                        return {
                            results: response
                        };
                    }
                }
            });
        }
    </script>
@endsection




