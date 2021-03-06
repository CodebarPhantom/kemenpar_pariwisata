
@extends('adminlte::page')

@section('title', 'Menampilkan Laporan Keadaan Darurat')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Respond').' '.__('Report').' '.__('Emergency') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{  __('Respond').' '.__('Report') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Respond').' '.__('Report').' '.__('Emergency') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
<form role="form" action="{{ route('report-emergency.update',$emergencyReport->id) }}" method="POST" class="col-md-12" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="">
        <div class="card card-info card-outline">
            <div class="card-header"> 
                <div class="d-flex">
                    <div class="mr-auto">
                        <h3 class="card-title mt-1">
                            <i class="fa fa-users"></i>
                                &nbsp; {{ __('Respond').' '.__('Report').' '.__('Emergency') }}
                        </h3>
                    </div>
                    <div class="mr-1">
                        <a href="{{ route('report-emergency.index') }}" class="btn btn-secondary btn-flat btn-sm">
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
                            <label> {{ __('Reporter') }} </label>
                            <input type="text" name="reporter" class="form-control" disabled value="{{ $emergencyReport->user_name }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> {{ __('Name').' '.__('Tourism') }} </label>
                            <input type="text" name="name_tourism"  class="form-control" disabled value="{{ $emergencyReport->tourism_name }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> {{ __('Date').' '.__('Report') }} </label>
                            <input type="text" name="date_report"  class="form-control" disabled value="{{ $emergencyReport->created_at->translatedFormat('l, d-m-Y H:i') }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> {{ __('Status') }} </label>
                            @php
                                if($emergencyReport->status == 1){
                                    $status = 'New';
                                }elseif($emergencyReport->status == 2){
                                    $status = 'In Handling';
                                }elseif($emergencyReport->status == 3){
                                    $status = 'Done';
                                }
                            @endphp
                            <input type="text" name="date_report"  class="form-control" disabled value="{{ __($status) }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label> {{ __('Title') }} </label>
                            <input type="text" name="title" maxlength="100" class="form-control" disabled value="{{ $emergencyReport->title }}" required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Description').' '.__('Emergency') }}</label>
                            <textarea name="description" class="form-control" rows="3" disabled>{{ $emergencyReport->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="logoFile">{{ __('Photo') }}</label><br/>
                            <a href="{{ $emergencyReport->url_photo }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="500px" src="{{ $emergencyReport->url_photo  }}"></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label> {{ __('Respond') }} </label>

                        <select class="form-control" name="respond" required>
                            <option value="">-- {{ __('Choose').' '.__('Response') }} --</option>
                            <option value="2" @if($emergencyReport->status == 2) selected @endif>{{ __('In Handling') }}</option>
                            <option value="3" @if($emergencyReport->status == 3) selected @endif>{{ __('Done') }}</option>
                        </select>
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




