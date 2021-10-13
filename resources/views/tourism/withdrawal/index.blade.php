@extends('adminlte::page')

@section('title', 'Withdrawal Pariwisata')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{__('Withdrawal') }} {{ __('Tourism') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('Withdrawal')}} {{ __('Tourism') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
@include('inc.modal-confirmation')

<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title mt-1">
                        <i class="fa fa-store-alt"></i>
                            &nbsp; {{ __('Withdrawal').' '.__('Tourism') }}
                    </h3>
                    @if(Laratrust::hasRole('administrator'))
                        <a href="#" data-href="{{ route('tourism-info-withdrawal.store') }}" class="btn btn-primary btn-flat btn-sm btn-tooltip" title="Pengajuan" data-toggle="modal" data-text="Apakah anda yakin untuk mengajukan pencairan dana {{ $authUser->tourism_name }} sebesar Rp.{{  number_format($authUser->balance) }}" data-target="#modal-confirmation" data-value="'.$tourismWithdrawal->id.'"><i class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('Withdrawal').' '.__('Tourism') }}</a>
                       
                    @endif
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
@push('js')
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
                url: "{{ route('tourism-info-withdrawal.data') }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            },
            columns: [
                { title: "{{ __('Name') }}", data: 'tourism.name', name: 'tourism.name', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Amount') }}", data: 'amount', name: 'amount', defaultContent: '-', class: 'text-center' },
                { title: "{{ __('Amount') }}", data: 'status', name: 'status', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },
                { title: "{{ __('Date') }}", data: 'tourism_info_balances.created_at', name: 'tourism_info_balances.created_at', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },

                { title: "{{ __('Action') }}", data: 'action', name: 'action', defaultContent: ' ', class: 'text-center', searchable:false, orderable: false },

            ]
        });


        });
    </script>
@endpush



