@extends('adminlte::page')

@section('title', 'Master Promosi')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Master Promosi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Master Promosi</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">                
                <div class="d-flex justify-content-between">
                    <h3 class="card-title mt-1">
                        <i class="fa fa-users"></i>
                            &nbsp; {{ __('Promotion') }}
                    </h3>

                    <a href="{{ route('ticket-promotion.create') }}" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('Create').' '.__('Promotion') }}</a>                    
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
                url: "{{ route('ticket-promotion.data') }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            },
            columns: [
                { title: "{{ __('Name').' '.__('Promotion') }}", data: 'name', name: 'name', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Name').' '.__('Tourism') }}", data: 'tourism_name', name: 'ti.name as tourism_name', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Start Date') }}", data: 'start_date', name: 'start_date', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('End Date') }}", data: 'end_date', name: 'end_date', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Discount') }}", data: 'disc_percentage', name: 'disc_percentage', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Status') }}", data: 'status', name: 'status', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },
                { title: "{{ __('Action') }}", data: 'action', name: 'action', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },

            ]
        });
            
        
        });
    </script>
@endsection



