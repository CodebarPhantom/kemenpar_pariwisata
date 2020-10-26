
@extends('adminlte::page')

@section('title', 'Show User')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Show User') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master User') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Show User') }}</a></li>
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
                                &nbsp; {{ __('Show Users') }}
                        </h3>
                    </div>
                    <div class="mr-1">
                        <a href="{{ route('user.index') }}" class="btn btn-secondary btn-flat btn-sm">
                            <i class="fa fa-arrow-left"></i>
                            &nbsp;&nbsp;{{ __('Back') }}
                        </a>  
                    </div>
                </div>               
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label> {{ __('PIC Name') }} </label>
                        <input type="text" name="pic_name" class="form-control" placeholder="Name ..." value="{{ $userData->name }}" disabled>
                    </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email ..." value="{{  $userData->email }}"  disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            @php
                                if($userData->user_type == 1){
                                    $user_type = 'Administrator';
                                }elseif($userData->user_type == 2){
                                    $user_type = 'User';
                                }
                            @endphp
                            <label>{{ __('User Type') }}</label>
                            <input class="form-control" value="{{ $user_type }}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            @php
                                if($userData->is_status == 0){
                                    $status = 'Inactive';
                                }elseif($userData->is_status == 1){
                                    $status = 'Active';
                                }
                            @endphp
                            <label>{{ __('Status') }}</label>
                            <input class="form-control" value="{{ $status }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> {{ __('Place').' '.__('Tourism') }} </label>
                            <input class="form-control" value="{{ $userData->tourism_name }}" disabled>
                            
                        </div>
                    </div>
                    <div class="col-sm-6">                        
                        <div class="form-group">
                            <label for="photoFile">Photo</label><br/>
                            <a href="{{ $userData->url_photo }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ $userData->url_photo  }}"></a>                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>  
    </div>
</div>
    
@stop



