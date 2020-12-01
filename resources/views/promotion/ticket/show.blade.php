
@extends('adminlte::page')

@section('title', 'Lihat Promosi')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Show').' '.__('Promotion') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master User') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Show').' '.__('Promotion') }}</a></li>
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
                            <i class="fa fa-users"></i>
                                &nbsp; {{ __('Show').' '.__('Promotion') }}
                        </h3>
                    </div>
                    <div class="mr-1">
                        <a href="{{ route('ticket-promotion.index') }}" class="btn btn-secondary btn-flat btn-sm">
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
                            <label> {{ __('Name').' '.__('Promotion') }} </label>
                            <input type="text" name="promotion_name" disabled class="form-control"  value="{{ $ticketPromotion->name }}" >
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Place').' '.__('Tourism') }}</label>
                            <input type="text" name="name_place" class="form-control" value="{{ $ticketPromotion->tourism_name }}" disabled>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> {{ __('Start').' '.__('Date') }} </label>
                            <input type="text"  name="start_date" class="form-control daterange-single" value="{{  $ticketPromotion->start_date->translatedFormat('D, d-m-Y H:i') }}" disabled />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> {{ __('End').' '.__('Date') }} </label>
                            <input type="text" name="end_date" class="form-control daterange-single" value="{{ $ticketPromotion->end_date->translatedFormat('D, d-m-Y H:i') }}" disabled />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Percentage').' %' }}</label>
                            <input type="text" min="0" max="100" name="percentage" class="form-control" disabled value="{{ $ticketPromotion->disc_percentage.' %' }}">

                        </div>
                    </div>
                </div>


                
            </div>
        </div>  
    </div>
</div>
    
@stop

@section('plugins.bsCustomFileInput', true)
@section('plugins.Select2', true)
@section('plugins.daterangepicker', true)


@section('adminlte_js')
    <script>
        
    </script>
@endsection




