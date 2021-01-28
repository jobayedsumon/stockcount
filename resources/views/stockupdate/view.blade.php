@extends('voyager::master')

@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">

@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-chart"></i>
        <span id="distributorName">Stock Report for {{ $stock->distributor->name }} </span>
    </h1>


    @if(session()->has('msg'))
        <span class="text-success font-bold">{{ session()->get('msg') }}</span>
    @endif
@stop

@section('content')

    <div class="page-content container">

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <div class="panel-heading">
                        <h3 class="panel-title">Distributor</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $stock->distributor->name }}</p>
                    </div>
                    <hr>
                    <div class="panel-heading">
                        <h3 class="panel-title">Total Products</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $stock->products()->count() }}</p>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Product List</h3>
                    </div>

                    <div class="panel-body">
                        <table id="Table_ID" class="table-bordered cell-border hover order-column row-border stripe mdl-data-table" style="color: #333">
                            <thead>
                            <tr>
                                <th>S/L</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>PKD</th>
                                <th>Opening Stock</th>
                                <th>Already Received</th>
                                <th>In Transit</th>
                                <th>Delivery Done</th>
                                <th>In Delivery Van</th>
                                <th>Physical Stock</th>
                                <th>Total Stock</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->code }}</td>
                                    <td>{{ $product->pivot->pkg_date }}</td>
                                    <td>{{ $product->pivot->opening_stock }}</td>
                                    <td>{{ $product->pivot->already_received }}</td>
                                    <td>{{ $product->pivot->stock_in_transit }}</td>
                                    <td>{{ $product->pivot->delivery_done }}</td>
                                    <td>{{ $product->pivot->in_delivery_van }}</td>
                                    <td>{{ $product->pivot->physical_stock }}</td>
                                    @php

                                        $total = 0;

                                        $total += $product->pivot->opening_stock;
                                        $total += $product->pivot->already_received;
                                        $total += $product->pivot->stock_in_transit;
                                        $total -= $product->pivot->delivery_done;
                                        $total -= $product->pivot->in_delivery_van;
                                        $total += $product->pivot->physical_stock;

                                        @endphp
                                    <td>{{ $total }}</td>
                                    <td>{{ $product->pivot->created_at }}</td>
                                    <td>{{ $product->pivot->updated_at }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Associated Files</h3>
                    </div>

                    <div class="panel-body">

                        @if($stock->pdf)
                            <p class="font-bold">PDF: <a href="{{ route('download-pdf', $stock->id) }}">{{ $stock->pdf }}</a></p>
                        @endif
                        @if($stock->excel)
                            <p class="font-bold">Excel: <a href="{{ route('download-excel', $stock->id) }}">{{ $stock->excel }}</a></p>
                        @endif
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Declared</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $stock->declared ? 'YES' : 'NO' }}</p>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Declare Time</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $stock->declare_time }}</p>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Created At</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $stock->created_at }}</p>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Updated At</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $stock->updated_at }}</p>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Declare Stock</h3>
                    </div>

                    <div class="panel-body">
                        @if(!$stock->declared)
                        <form method="POST" action="{{ route('update-stock.declare') }}">
                            @csrf
                            <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                            <button onclick="return alertBeforeDeclare()" class="btn btn-danger text-sm" type="submit">DECLARE</button>
                            <p style="color: red">(Proceed with caution!)</p>
                            <p style="color: red">(You can not update once declared)</p>
                        </form>
                        @else
                        <p style="color: red; font-weight: bold">Stcok already declared!</p>
                        @endif
                    </div>
                    <hr>




                </div>
            </div>
        </div>

    </div>

@stop

@section('javascript')

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>



    <script>

        $('document').ready(function () {

            distributorName = $('#distributorName').text();

        function alertBeforeDeclare() {
            value = confirm('Declare? (Can\'t edit anymore!)');
            if(value) {
                value = confirm('Are you sure, you want to declare??');
                if(value) {
                    input = prompt('Type \'yes\' to declare');
                    if(input === 'yes') {
                        value = true;
                    } else {
                        value = false;
                    }
                }
            }
            return value;
        }


            $('#Table_ID').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: distributorName + new Date().toDateString() + ' ' + gettime(),
                        exportOptions: {
                            columns: ':not(:last-child)',
                        },
                        text:      '<img src="/icons/excel.png">',
                        titleAttr: 'Excel'

                    },
                    {
                        extend: 'copyHtml5',
                        title: distributorName + new Date().toDateString() + ' ' + gettime(),
                        exportOptions: {
                            columns: ':not(:last-child)',
                        },
                        text:      '<img src="/icons/copy.png">',
                        titleAttr: 'Copy'

                    },
                    {
                        extend: 'csvHtml5',
                        title: distributorName + new Date().toDateString() + ' ' + gettime(),
                        exportOptions: {
                            columns: ':not(:last-child)',
                        },
                        text:      '<img src="/icons/csv.png">',
                        titleAttr: 'Csv'

                    },
                ],
                "scrollX": true,
                "createdRow": function(row, data, dataIndex) {
                    $('#Table_ID thead tr th').css({
                        "border-bottom": "1px solid #fff",
                        "font-weight": "600",
                        "background-color": "#3faba4",
                        "color": "white"
                    });
                },

                autoWidth: false,
                columnDefs: [
                    {
                        targets: ['_all'],
                        className: 'mdc-data-table__cell'
                    }
                ]

            });


            function gettime() {
                var date = new Date();
                var newdate = (date.getHours() % 12 || 12) + "_" + date.getMinutes() + "_" + date.getSeconds();
                setInterval(gettime, 1000);
                return newdate;
            }

        });

    </script>



@stop






