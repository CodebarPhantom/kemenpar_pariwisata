@extends('adminlte::page')

@section('title', 'Report Tickets')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Report</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Report</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" id="form_1" action="{{ route('report-ticket.administrator-daily') }}" method="GET" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">                
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title mt-1">
                            <i class="fa fa-calendar-alt"></i>
                                &nbsp; {{ __('Option').' '.__('Search') }}
                        </h3>                
                    </div>
                </div>
                <div class="card-body">                     
                    <div class="row">
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Start').' '.__('Date') }} </label>
                                <input type="text"  name="start_date" class="form-control daterange-single" value="{{  date("d-m-Y",strtotime($startDate)) }}" readonly />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('End').' '.__('Date') }} </label>
                                <input type="text" name="end_date" class="form-control daterange-single" value="{{ date("d-m-Y",strtotime($endDate)) }}" readonly />
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-flat btn-sm form-control">
                                    <i class="fa fa-search"></i>
                                    &nbsp;&nbsp;{{ __('Search') }}
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
                        <i class="fa fa-calendar-alt"></i>
                            &nbsp; {{ __('Report').' '.__('Harian').' - '.date("d F Y",strtotime($startDate)).' s.d. '.date("d F Y",strtotime($endDate)) }} 
                    </h3>                
                </div>
            </div>
            <div class="card-body">                     
                <div class="row">
                    <div class="table-responsive">  
                        <table class="table table-striped table-bordered dt-responsive nowrap table-sm" width="100%" id="datatable_1">
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($visitorRevenueDailys as $visitorRevenueDaily)
                                <tr>
                                    <td>{{ $visitorRevenueDaily->tourism_name }}</td>                                 
                                    <td>{{ number_format($visitorRevenueDaily->count_visitor) }}</td>
                                    <td>{{ number_format($visitorRevenueDaily->sum_price) }}</td>

                                </tr> 
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
    
@stop

@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('plugins.daterangepicker', true)


@section('adminlte_js')
    <script>
        $(document).ready(function(){

            $('.daterange-single').daterangepicker({ 
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });

            
            initailizeSelect2();

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
                serverSide: false,
                responsive: true,
                paging: false,

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
                columns: [
                    { title: "{{ __('Date') }}", defaultContent: '-', class: 'text-center',searchable:false, orderable: false },
                    { title: "{{ __('Visitor') }}", defaultContent: ' ', class: 'text-center' },
                    { title: "{{ __('Revenue') }}",defaultContent: '-', class: 'text-center' },

                ]
            });
            
        
        });

        function initailizeSelect2(){
                $(".select2-eryan").select2({
                    placeholder: 'Pilih Tempat Wisata',     
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



