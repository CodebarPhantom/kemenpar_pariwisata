@extends('adminlte::page')

@section('title', 'Laporan Keadaan Darurat')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Report').' '.__('Emergency') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Report').' '.__('Emergency') }}</a></li>
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
                            &nbsp; {{ __('Report').' '.__('Emergency') }}
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
                url: "{{ route('report-emergency.data') }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            },
            columns: [
                { title: "{{ __('Reporter') }}", data: 'user_name', name: 'us.name as user_name', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Name').' '.__('Tourism') }}", data: 'tourism_name', name: 'ti.name as tourism_name', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Summary') }}", data: 'summary', name: 'emergency_reports.title', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Date').' '.__('Reporting') }}", data: 'date_report', name: 'date_report', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Status') }}", data: 'status', name: 'status', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },
                { title: "{{ __('Action') }}", data: 'action', name: 'action', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },

            ]
        });
            
        
        });
    </script>
@endsection



