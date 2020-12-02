@extends('adminlte::page')

@section('title', 'Master Tickets')

@section('adminlte_css_pre')
    <style>
        
        @import url(https://fonts.googleapis.com/css?family=Droid+Sans+Mono|Pacifico|Oxygen);

        
        .calculator {
            position: relative;
            margin: 1em auto;
            padding: 1em 0;
            display: block-inline;
            width: 100%;
            background-color: #444;
            border-radius: 25px;
            font-family: 'Oxygen';
        }

        .calc-row {
            text-align: center;
        }

        .calc-row div.screen {
            font-family: Droid Sans Mono;
            display: table;
            width: 97%;
            background-color: #aaa;
            text-align: center;
            font-size: 1.5em;
            min-height: 1.0em;
            margin-right: 0.5em;
            padding-right: 0.5em;
            border: 1px solid #888;
            color: #333;
        }

        .calc-row div {
            text-align: center;
            display: inline-block;
            font-weight: 900;
            border: 1px solid #555;
            background-color: #eee;
            padding: 10px 0;
            margin: 7px 5px;
            border-radius: 15px;
            width: 25%;
        }

        .calc-row div.zero {
            width: 50%;
        }

        .calc-row div.zero {
            margin-right: 2%;
        }

    </style>
@endsection

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Master Tiket</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Master Tiket</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <form role="form" id="form_1" action="{{ route('ticket.store') }}" onsubmit="return confirm('Apakah jumlah tiket sudah benar?');"  method="POST" class="col-md-12" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="card card-info card-outline">
                <div class="card-header">                
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title mt-1">
                            <i class="fa fa-ticket-alt"></i>
                                &nbsp; {{ __('Ticket') }}
                        </h3>                
                    </div>
                </div>
                <div class="card-body">                     
                    <div class="row">
                        <div class="col-12 text-center text-lg">
                            <label> {{ __('Quantity').' '.__('Ticket') }} </label>
                            <div class="calc-row">
                                <div class="screen">0123456789</div>
                            </div>
                            <input type="hidden" readonly min="1" value="1" name="qty" class="form-control screen"  required>
                        </div>
                        <div class="col-12">   
                            <div class="calculator">                             
                                <div class="calc-row">
                                  <div class="button">7</div><div class="button">8</div><div class="button">9</div>
                                </div>
                                
                                <div class="calc-row">
                                  <div class="button">4</div><div class="button">5</div><div class="button">6</div>
                                </div>
                                
                                <div class="calc-row">
                                  <div class="button">1</div><div class="button">2</div><div class="button">3</div>
                                </div>
                                
                                <div class="calc-row">
                                  <div class="button zero">0</div><div class="button">CE</div>
                                </div>
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
                            &nbsp; {{ __('Ticket').' '.__('Today') }}
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
                    { title: "{{ __('Code') }}", data: 'code', name: 'code', defaultContent: '-', class: 'text-center', orderable: false,sorting: false },
                    { title: "{{ __('Status') }}", data: 'status', name: 'status', defaultContent: '-', class: 'text-center',searchable:false, orderable: false },
                    { title: "{{ __('Action') }}", data: 'action', name: 'action', defaultContent: ' ', class: 'text-center',searchable:false, orderable: false },

                ]
            });        
            var result = 0;
            var prevEntry = 0;
            var operation = null;
            var currentEntry = '0';
            updateScreen(result);
            
            $('.button').on('click', function(evt) {
                var buttonPressed = $(this).html();
                console.log(buttonPressed);
                
                if (buttonPressed === "CE") {
                    currentEntry = '0';
                } else if (isNumber(buttonPressed)) {
                    if (currentEntry === '0') currentEntry = buttonPressed;
                    else currentEntry = currentEntry + buttonPressed;
                } 
                
                updateScreen(currentEntry);
            });
        });

        updateScreen = function(displayValue) {
            var displayValue = displayValue.toString();
            $('.screen').val(displayValue.substring(0, 10));
            $('.screen').text(displayValue.substring(0, 10));

        };

        isNumber = function(value) {
            return !isNaN(value);
        }

        function myFunction() {
            var txt;
            if (confirm("Press a button!")) {
                txt = "You pressed OK!";
            } else {
                txt = "You pressed Cancel!";
            }
            document.getElementById("demo").innerHTML = txt;
        }
    </script>
@endsection



