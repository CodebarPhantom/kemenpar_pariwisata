
@extends('adminlte::page')

@section('title', 'Show User')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Show').' '.__('User') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master User') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Show').' '.__('User') }}</a></li>
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
                                &nbsp; {{ __('Show').' '.__('User') }}
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
                            <label>{{ __('User Type') }}</label>
                            <input class="form-control" value="{{ $userData->roles->first()->display_name }}" disabled>
                        </div>
                    </div>
                    @if ($userData->roles->first()->name == 'user')
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Password') }}</label>
                            <input class="form-control" value="{{  $userData->raw_password }}" disabled>
                        </div>
                    </div>
                    @endif

                    <div class="col-sm-6">
                        <div class="form-group">
                            @php
                                if($userData->is_active == 0){
                                    $status = 'Tidak Aktif';
                                }elseif($userData->is_active == 1){
                                    $status = 'Aktif';
                                }
                            @endphp
                            <label>{{ __('Status') }}</label>
                            <input class="form-control" value="{{ $status }}" disabled>
                        </div>
                    </div>
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



