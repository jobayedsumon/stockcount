@extends('voyager::master')

@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">

@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-chart"></i>
        Stock Data
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
                        <h3 class="panel-title">Distributor Name</h3>
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
                        <table class="table table-striped" style="color: #333">
                            <thead>
                            <tr>
                                <th>S/L</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>PKD</th>
                                <th>Opening Stock</th>
                                <th>Already Received</th>
                                <th>Stock In Transit</th>
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



        });



    </script>
@stop
