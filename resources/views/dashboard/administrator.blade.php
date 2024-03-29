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
    <div class="col-4" >
        <div class="small-box bg-danger" >
          <div class="inner">
            <h3>{{ $emergencyReports->count() }}</h3>
            <p>Keadaan Darurat Belum di Tanggapi</p>
          </div>
          <div class="icon">
            <i class="fa fa-bullhorn"></i>
          </div>
          <a href="{{ route('report-emergency.index') }}" class="small-box-footer">{{ __('Respond').' ' }}<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @if($emergencyReports->count() != 0)
        <div class="col-8">
            <div class="card card-danger card-outline">
                <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle table-sm">
                    <thead>
                    <tr>
                    <th>{{ __('Reporter') }}</th>
                    <th>{{ __('Name').' '.__('Tourism') }}</th>
                    <th>{{ __('Summary') }}</th>
                    <th>{{ __('Date').' '.__('Reporting') }}</th>
                    <th>{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                            @foreach ($emergencyReports->get() as $emergencyReport)
                            <tr>
                                <td>{{ $emergencyReport->user_name }}</td>
                                <td>{{ $emergencyReport->tourism_name }}</td>
                                <td>{{ $emergencyReport->title }}</td>
                                <td>{{ $emergencyReport->created_at->translatedFormat('D, d-m-Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('report-emergency.show',$emergencyReport->id) }}" class="btn btn-info btn-flat btn-xs align-middle" title="{{ __('Show') }}"><i class="fa fa-eye fa-sm"></i></a>
                                    <a href="{{ route('report-emergency.edit',$emergencyReport->id) }}" class="btn btn-danger btn-flat btn-xs align-middle" title="{{ __('Respond') }}"><i class="fa fa-exclamation-triangle fa-sm"></i></a>
                                </td>
                            </tr>
                            @endforeach


                    </tbody>
                </table>
                </div>
            </div>
        </div>
    @endif

</div>
@if (Laratrust::hasRole('superadmin'))
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
                <h3 class="card-title">Grafik Penjualan Tiket @php echo Carbon::createFromDate($yearReport, $monthReport)->translatedFormat('F Y'); @endphp</h3>

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
                <h3 class="card-title">Grafik Pembatalan Tiket @php echo Carbon::createFromDate($yearReport, $monthReport)->translatedFormat('F Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                <canvas id="barChartVoid" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Grafik Pendapatan Loss @php echo Carbon::createFromDate($yearReport, $monthReport)->translatedFormat('F Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                <canvas id="barChart2Void" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-6">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Grafik Penjualan Tiket @php echo Carbon::today()->translatedFormat('l, j M Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="donutChartVisitorDaily" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
    </div>
    <div class="col-6">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Grafik Penjualan Tiket @php echo Carbon::today()->translatedFormat('F Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="donutChartVisitorMonthly" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Grafik Pendapatan @php echo Carbon::today()->translatedFormat('l, j M Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="donutChartRevenueDaily" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
    </div>
    <div class="col-6">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Grafik Pendapatan @php echo Carbon::today()->translatedFormat('F Y'); @endphp</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="donutChartRevenueMonthly" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
    </div>
</div>
@endif

@stop

@section('plugins.Chartjs', true)
@section('plugins.Select2', true)
@section('adminlte_js')
@php
    $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$monthReport, $yearReport);
@endphp
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".select2-eryan").select2({
        placeholder: 'Pilih Bulan',
        theme: 'bootstrap4',
        dropdownPosition: 'below'
    });

    @if (Laratrust::hasRole('superadmin'))
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

    var ctxVoid = document.getElementById("barChartVoid");
    var myChartVoid = new Chart(ctxVoid, {
        type: 'bar',
        data: {
            labels  : [
                @foreach ($visitorVoidRevenueTourisms as $visitorVoidRevenueTourism)
                    '{{$visitorVoidRevenueTourism->tourism_name }}',
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
                                        @foreach ($visitorVoidRevenueTourisms as $visitorVoidRevenueTourism)
                                            '{{$visitorVoidRevenueTourism->count_visitor }}',
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

    var ctx2Void = document.getElementById("barChart2Void");
    var myChart2Void = new Chart(ctx2Void, {
        type: 'bar',
        data: {
            labels  : [
                @foreach ($visitorVoidRevenueTourisms as $visitorVoidRevenueTourism)
                    '{{$visitorVoidRevenueTourism->tourism_name }}',
                @endforeach
            ],

            datasets: [
            {
            label               : 'Loss',
            backgroundColor     : 'rgba(210, 214, 222, 1)',
            borderColor         : 'rgba(210, 214, 222, 1)',
            pointRadius         : false,
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                :   [
                                        @foreach ($visitorVoidRevenueTourisms as $visitorVoidRevenueTourism)
                                            '{{$visitorVoidRevenueTourism->sum_price }}',
                                        @endforeach
                                    ]
            },
        ],
        },
        options: {
            title: {
            display: false,
            text: 'Loss Graph'
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
    @else

    var chartVisitorDaily;
    var chartVisitorMonthly;
    var chartRevenueDaily;
    var chartRevenueMonthly;

    var chartDataColor = [];

    function respondCanvasChartRevenueMonthly() {
        var cm = $('#donutChartRevenueMonthly');
        var ctxm = cm.get(0).getContext("2d");

        var donutOptions = {
            maintainAspectRatio : false,
            responsive : true,
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    title: function(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function(tooltipItem, data) {
                        return data['datasets'][0]['data'][tooltipItem['index']].toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                    },
                }
            },
        }

        new Chart(ctxm, {
            type: 'doughnut',
            data: chartDataRevenueMonthly,
            options: donutOptions
        })
    }


    var GetChartDataVisitorDaily = function () {
        $.ajax({
            url: "{{ route('report.ticket.daily') }}",
            method: 'POST',
            dataType: 'json',
            success: function (d) {
                if (d.data.length != chartDataColor.length) {
                    d.data.forEach(element => {
                        chartDataColor.push(getRandomColor());
                    });
                }

                chartDataVisitorDaily = {
                    labels: d.data.map(function(a) {return a.category_name}),
                    datasets: [
                        {
                            data: d.data.map(function(a) {return a.count_visitor}),
                            backgroundColor : chartDataColor,
                        }
                    ]
                };

                if(chartVisitorDaily.data.datasets[0]?.data != ''+chartDataVisitorDaily.datasets[0].data) {
                    chartVisitorDaily.data = chartDataVisitorDaily;
                    chartVisitorDaily.update();
                }

                chartDataRevenueDaily = {
                    labels: d.data.map(function(a) {return a.category_name}),
                    datasets: [
                        {
                            data: d.data.map(function(a) {return a.sum_price}),
                            backgroundColor : chartDataColor,
                        }
                    ]
                };

                if(chartRevenueDaily.data.datasets[0]?.data != ''+chartDataRevenueDaily.datasets[0].data) {
                    chartRevenueDaily.data = chartDataRevenueDaily;
                    chartRevenueDaily.update();
                }
            }
        });
    };

    var GetChartDataVisitorMonthly = function () {
        $.ajax({
            url: "{{ route('report.ticket.monthly') }}",
            method: 'POST',
            dataType: 'json',
            success: function (d) {
                if (d.data.length != chartDataColor.length) {
                    d.data.forEach(element => {
                        chartDataColor.push(getRandomColor());
                    });
                }

                chartDataVisitorMonthly = {
                    labels: d.data.map(function(a) {return a.category_name}),
                    datasets: [
                        {
                            data: d.data.map(function(a) {return a.count_visitor}),
                            backgroundColor : chartDataColor,
                        }
                    ]
                };

                if(chartVisitorMonthly.data.datasets[0]?.data != ''+chartDataVisitorMonthly.datasets[0].data) {
                    chartVisitorMonthly.data = chartDataVisitorMonthly;
                    chartVisitorMonthly.update();
                }

                chartDataRevenueMonthly = {
                    labels: d.data.map(function(a) {return a.category_name}),
                    datasets: [
                        {
                            data: d.data.map(function(a) {return a.sum_price}),
                            backgroundColor : chartDataColor,
                        }
                    ]
                };


                if(chartRevenueMonthly.data.datasets[0]?.data != ''+chartDataRevenueMonthly.datasets[0].data) {
                    chartRevenueMonthly.data = chartDataRevenueMonthly;
                    chartRevenueMonthly.update();
                }
            }
        });
    };

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    $(document).ready(function() {
        var cvd = $('#donutChartVisitorDaily');
        var ctxvd = cvd.get(0).getContext("2d");

        var cvm = $('#donutChartVisitorMonthly');
        var ctxvm = cvm.get(0).getContext("2d");

        var donutOptions = {
            maintainAspectRatio : false,
            responsive : true,
            animation: {
                animateRotate : false,
            }
        }

        chartVisitorDaily = new Chart(ctxvd, {
            type: 'doughnut',
            options: donutOptions
        });

        chartVisitorMonthly = new Chart(ctxvm, {
            type: 'doughnut',
            options: donutOptions
        });


        var crd = $('#donutChartRevenueDaily');
        var ctxrd = crd.get(0).getContext("2d");

        var crm = $('#donutChartRevenueMonthly');
        var ctxrm = crm.get(0).getContext("2d");

        var donutOptionsRevenue = {
            maintainAspectRatio : false,
            responsive : true,
            animation: {
                animateRotate : false,
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    title: function(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function(tooltipItem, data) {
                        return data['datasets'][0]['data'][tooltipItem['index']].toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                    },
                }
            },
        }

        chartRevenueDaily = new Chart(ctxrd, {
            type: 'doughnut',
            options: donutOptionsRevenue
        })

        chartRevenueMonthly = new Chart(ctxrm, {
            type: 'doughnut',
            options: donutOptionsRevenue
        })

        setTimeout(function() {
            GetChartDataVisitorDaily();
            GetChartDataVisitorMonthly();

            setInterval(function() {
                GetChartDataVisitorDaily();
                GetChartDataVisitorMonthly();
            }, 3000);

        }, 1000)
    });

    @endif
</script>
@endsection
