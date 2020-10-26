
@extends('adminlte::page')

@section('title', 'Show Role')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Show').' '.__('Role')  }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master').' '.__('Role') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Show').' '.__('Role') }}</a></li>
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
                            <i class="fa fa-user-lock"></i>
                                &nbsp; {{ __('Show').' '.__('Role')  }}
                        </h3>
                    </div>
                    <div class="mr-1">
                        <a href="{{ route('role.index') }}" class="btn btn-secondary btn-flat btn-sm">
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
                            <label> {{ __('Name').' '.__('Role') }} </label>
                            <input type="text"  class="form-control" value="{{ $role->display_name }}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- text input -->
                        <div class="form-group">
                            <label> {{ __('Description') }} </label>
                            <input type="text" class="form-control" name="description" value="{{ $role->description }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        @php
                            $no = 0;
                        @endphp
                        @foreach ($permissions as $permission)
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" disabled value="{{ $permission->id }}" {{ (in_array($permission->id, $rolePermissions) ? 'checked' : '') }} name="hak_akses[]" class="custom-control-input" id="customSwitch{{ $no }}">
                                <label class="custom-control-label" for="customSwitch{{ $no }}">{{ $permission->display_name }}</label>
                            </div>
                        </div>
                        @php
                            $no++;
                        @endphp
                        @endforeach
                    </div>
                </div>
                
            </div>
        </div>  
    </div>
</div>
    
@stop



