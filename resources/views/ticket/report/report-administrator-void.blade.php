@extends('adminlte::page')

@section('title', 'Report Tickets')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Report') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{ __('Report') }}</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" id="form_1" action="{{ route('report-ticket.administrator-void') }}" method="GET" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title mt-1">
                            <i class="fa  fa-calendar-alt"></i>
                                &nbsp; {{ __('Option').' '.__('Search') }}
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Month') }} </label>
                                <select class="form-control select2-eryan" style="width: 100%;" name="month">
                                    <option value="01" @if ($monthReport == "01") {{ 'selected' }} @endif >Januari</option>
                                    <option value="02" @if ($monthReport == "02") {{ 'selected' }} @endif>Februari</option>
                                    <option value="03" @if ($monthReport == "03") {{ 'selected' }} @endif>Maret</option>
                                    <option value="04" @if ($monthReport == "04") {{ 'selected' }} @endif>April</option>
                                    <option value="05" @if ($monthReport == "05") {{ 'selected' }} @endif>Mei</option>
                                    <option value="06" @if ($monthReport == "06") {{ 'selected' }} @endif>Juni</option>
                                    <option value="07" @if ($monthReport == "07") {{ 'selected' }} @endif>Juli</option>
                                    <option value="08" @if ($monthReport == "08") {{ 'selected' }} @endif>Agustus</option>
                                    <option value="09" @if ($monthReport == "09") {{ 'selected' }} @endif>September</option>
                                    <option value="10" @if ($monthReport == "10") {{ 'selected' }} @endif>Oktober</option>
                                    <option value="11" @if ($monthReport == "11") {{ 'selected' }} @endif>November</option>
                                    <option value="12" @if ($monthReport == "12") {{ 'selected' }} @endif>Desember</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> {{ __('Year') }} </label>
                                <select class="form-control select2-eryan" style="width: 100%;" name="year">
                                    @php
                                    for($i=date('Y'); $i>=2018; $i--) {
                                        $selected = '';
                                        if ($yearReport == $i){$selected = ' selected="selected"';}
                                        print('<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n");
                                        //print('<option value="'.$i.'">'.$i.'</option>'."\n");
                                    }
                                    @endphp
                                </select>
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
                            &nbsp; {{ __('Report').' '.__('Bulanan').' '.__('Void') }}
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
                                @foreach ($visitorRevenueTourisms as $visitorRevenueTourism)
                                <tr>
                                    <td>{{ $visitorRevenueTourism->tourism_name }}</td>
                                    <td>{{ number_format($visitorRevenueTourism->count_visitor) }}</td>
                                    <td>{{ number_format($visitorRevenueTourism->sum_price) }}</td>

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

@section('adminlte_js')
    <script>
        $(document).ready(function(){

            $(".select2-eryan").select2({
                placeholder: 'Pilih Bulan',
                theme: 'bootstrap4',
                dropdownPosition: 'below'
            });

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
                    { title: "{{ __('Pariwisata') }}", defaultContent: '-', class: 'text-center'},
                    { title: "{{ __('Visitor') }}", defaultContent: ' ', class: 'text-center' },
                    { title: "{{ __('Loss') }}",defaultContent: '-', class: 'text-center' },

                ]
            });


        });
    </script>
@endsection



