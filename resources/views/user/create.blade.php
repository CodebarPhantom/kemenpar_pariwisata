
@extends('adminlte::page')

@section('title', 'Add User')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Add User') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master User') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Add User') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" action="{{ route('user.store') }}" method="POST" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header"> 
                    <div class="d-flex">
                        <div class="mr-auto">
                            <h3 class="card-title mt-1">
                                <i class="fa fa-users"></i>
                                    &nbsp; {{ __('Add Users') }}
                            </h3>
                        </div>
                        <div class="mr-1">
                            <a href="{{ route('user.index') }}" class="btn btn-secondary btn-flat btn-sm">
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
                        <!-- text input -->
                        <div class="form-group">
                            <label> {{ __('PIC Name') }} </label>
                            <input type="text" name="pic_name" class="form-control" placeholder="Name ..." required>
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email ..." required>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password ..." required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Place').' '.__('Tourism') }}</label>
                                <select class="form-control select2-eryan" style="width: 100%;" name="tourism_place">                                
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="photoFile">Photo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="photo" accept="image/*" id="photoFile" required>
                                        <label class="custom-file-label" for="photoFile">{{ __('Choose') }} Photo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>{{ __('Type User') }}</label>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type_user" value="1">
                                    <label class="form-check-label">Administrator</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type_user" value="2">
                                    <label class="form-check-label">Users</label>
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

@section('plugins.bsCustomFileInput', true)
@section('plugins.Select2', true)

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

        function initailizeSelect2(){
            $(".select2-eryan").select2({
                placeholder: 'Pilih Tempat Wisata',
                minimumInputLength: 2,                
                theme: 'bootstrap4',
                ajax: {
                    url : "{{ route('user.tourism') }}",
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




