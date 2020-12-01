@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
@php
    use Carbon\Carbon;
@endphp
<div class="row">
    <form role="form" id="form_1" action="{{ route('dashboard.administrator') }}" method="GET" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">                
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title mt-1">
                            <i class="fa fa-chart-bar"></i>
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
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Grafik Pengunjung @php echo Carbon::createFromDate($yearReport, $monthReport)->translatedFormat('F Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Grafik Pendapatan  @php echo Carbon::createFromDate($yearReport, $monthReport)->translatedFormat('F Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                <canvas id="barChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
    </div>
</div>
@stop

@section('plugins.Chartjs', true)
@section('plugins.Select2', true)
@section('adminlte_js')
@php
    $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$monthReport, $yearReport);
@endphp
<script>
    $(".select2-eryan").select2({
        placeholder: 'Pilih Bulan',           
        theme: 'bootstrap4',
        dropdownPosition: 'below'
    });

    var ctx = document.getElementById("barChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels  : [  
                @foreach ($visitorRevenueTourisms as $visitorRevenueTourism)
                    '{{$visitorRevenueTourism->tourism_name }}',
                @endforeach
            ],
            
            datasets: [
            {
            label               : 'Visitor',
            backgroundColor     : 'rgba(60,141,188,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : [
                                        @foreach ($visitorRevenueTourisms as $visitorRevenueTourism)
                                            '{{$visitorRevenueTourism->count_visitor }}',
                                        @endforeach
                                    ]
            },
            /*{
            label               : 'Electronics',
            backgroundColor     : 'rgba(210, 214, 222, 1)',
            borderColor         : 'rgba(210, 214, 222, 1)',
            pointRadius         : false,
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                : [65, 59, 80, 81, 56, 55, 40]
            },*/
        ],
        },
        options: {
            title: {
            display: false,
            text: 'Visitor Graph'
            },
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines : {
                        display : false,
                    }
                }],
                yAxes: [{
                    gridLines : {
                        display : false,
                    },
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

    var ctx2 = document.getElementById("barChart2");
    var myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels  : [  
                @foreach ($visitorRevenueTourisms as $visitorRevenueTourism)
                    '{{$visitorRevenueTourism->tourism_name }}',
                @endforeach
            ],
            
            datasets: [            
            {
            label               : 'Revenue',
            backgroundColor     : 'rgba(210, 214, 222, 1)',
            borderColor         : 'rgba(210, 214, 222, 1)',
            pointRadius         : false,
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                :   [
                                        @foreach ($visitorRevenueTourisms as $visitorRevenueTourism)
                                            '{{$visitorRevenueTourism->sum_price }}',
                                        @endforeach
                                    ]
            },
        ],
        },
        options: {
            title: {
            display: false,
            text: 'Revenue Graph'
            },
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines : {
                        display : false,
                    }
                }],
                yAxes: [{
                    gridLines : {
                        display : false,
                    },
                    ticks: {
                        beginAtZero:true
                    },
                }]
            }
        }
    });
</script>
@endsection
