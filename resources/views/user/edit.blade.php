
@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Edit').' '.__('User') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Master User') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Edit').' '.__('User') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" action="{{ route('user.update',$userData->idUser) }}" method="POST" class="col-md-12" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="mr-auto">
                            <h3 class="card-title mt-1">
                                <i class="fa fa-users"></i>
                                    &nbsp; {{ __('Edit').' '.__('User') }}
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
                        <!-- text input -->
                        <div class="form-group">
                            <label> {{ __('Name') }} </label>
                            <input type="text" name="pic_name" class="form-control @error('pic_name') is-invalid @enderror" placeholder="Nama ..." value="{{ old('pic_name', $userData->name) }}" required>
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email ..." value="{{ old('email', $userData->email) }}" readonly>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Place').' '.__('Tourism') }}</label>
                                @if (Laratrust::hasRole('superadmin'))
                                    <select class="form-control select2-eryan" style="width: 100%;" name="tourism_place">
                                        @if ($userData->tourism_info_id != NULL)
                                            <option value="{{ $tourismInfo->id }}" selected>{{$tourismInfo->name}}</option>
                                        @endif
                                    </select>
                                @else
                                    <input type="text" class="form-control" value="{{ auth()->user()->tourism_info()->first()->name }}" required disabled>
                                    <input type="hidden" name="tourism_place" class="form-control" value="{{ auth()->user()->tourism_info()->first()->id }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="photoFile">Photo</label><br/>
                                <a href="{{ old('photo', $userData->url_photo) }}" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="100px" height="100px" src="{{ old('photo', $userData->url_photo) }}"></a>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="photo" value="{{ old('photo', $userData->url_photo) }}" accept="image/*" id="photoFile">
                                        <label class="custom-file-label" for="photoFile">{{ __('Choose') }} Logo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>{{ __('Status') }}</label>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @if (old('is_active', $userData->is_active) == 1) checked @endif name="is_active" value="1">
                                    <label class="form-check-label">{{ __('Active') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @if (old('is_active', $userData->is_active) == 0)  checked @endif name="is_active" value="0">
                                    <label class="form-check-label">{{ __('Inactive') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>{{ __('Type').' '.__('User') }}</label>
                            <div class="form-group">
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input @error('type_user') is-invalid @enderror" type="radio" name="type_user" value="{{ $role->id }}" required @if (old('photo', $userData->user_type) == $role->id) checked @endif>
                                        <label class="form-check-label">{{ $role->display_name }}</label>
                                    </div>
                                @endforeach
                                {{-- <div class="form-check">
                                    <input class="form-check-input" type="radio" @if ($userData->user_type == 1 ) checked @endif name="type_user" value="1">
                                    <label class="form-check-label">Administrator</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @if ($userData->user_type == 2 ) checked @endif  name="type_user" value="2">
                                    <label class="form-check-label">Users</label>
                                </div> --}}
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
@section('plugins.bsCustomFileInput', true)

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
                allowClear: true,
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




