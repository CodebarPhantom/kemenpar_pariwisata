@extends('adminlte::page')

@section('title', 'Master Tickets')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Master Tickets</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Master Tickets</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" id="form_1" action="{{ route('ticket.store') }}" method="POST" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">                
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title mt-1">
                            <i class="fa fa-ticket-alt"></i>
                                &nbsp; {{ __('Tickets') }}
                        </h3>                
                    </div>
                </div>
                <div class="card-body">                     
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label> {{ __('Quantity').' '.__('Ticket') }} </label>
                                <input type="number" min="1" value="1" name="qty" class="form-control"  required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-flat btn-sm form-control">
                                    <i class="fa fa-print"></i>
                                    &nbsp;&nbsp;{{ __('Print') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">                
                <div class="d-flex justify-content-between">
                    <h3 class="card-title mt-1">
                        <i class="fa fa-ticket-alt"></i>
                            &nbsp; {{ __('Tickets').' '.__('Today') }}
                    </h3>                
                </div>
            </div>
            <div class="card-body">                     
                <div class="row">
                    <div class="table-responsive">  
                        <table class="table table-striped table-bordered dt-responsive nowrap table-sm" width="100%" id="datatable_1"></table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
    
@stop

@section('plugins.Datatables', true)
@section('adminlte_js')
    <script>
        $(document).ready(function(){

            $(document).on("wheel", "input[type=number]", function (e) {
                $(this).blur();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#datatable_1').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                emptyTable: "{{ __('No data available in table') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                infoFiltered: "({{ __('filtered from _MAX_ total entries') }})",
                lengthMenu: "{{ __('Show _MENU_ entries') }}",
                loadingRecords: "{{ __('Loading') }}...",
                processing: "{{ __('Processing') }}...",
                search: "{{ __('Search') }}",
                zeroRecords: "{{ __('No matching records found') }}"
            },
            ajax: {
                method: 'POST',
                url: "{{ route('ticket.data') }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            },
            columns: [
                { title: "{{ __('Code') }}", data: 'code', name: 'code', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Status') }}", data: 'status', name: 'status', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },
                { title: "{{ __('Action') }}", data: 'action', name: 'action', defaultContent: ' ', class: 'text-center',searchable:false, orderable: false },

            ]
        });
            
        
        });
    </script>
@endsection



