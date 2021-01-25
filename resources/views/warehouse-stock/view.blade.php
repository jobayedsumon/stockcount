@extends('voyager::master')

@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">

@stop


@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-chart"></i>
        Stock Data for {{ $warehouse->name }}
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
                        <h3 class="panel-title">Warehouse</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $warehouse->name }}</p>
                    </div>
                    <hr>
                    <div class="panel-heading">
                        <h3 class="panel-title">Total Products</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $warehouse->products()->count() }}</p>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Detail Report</h3>
                    </div>

                    <div class="panel-body">
                        <table id="detailReport" class="table table-striped table-bordered" style="color: #333">
                            <thead class="text-center" >
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="6" class="text-center">Inward</th>
                                <th colspan="6" class="text-center">Outward</th>
                                <th colspan="2"></th>

                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="3" class="text-center">Factory</th>
                                <th colspan="3" class="text-center">Warehouse</th>
                                <th colspan="3" class="text-center">DB</th>
                                <th colspan="3" class="text-center">Warehouse</th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th>S/L</th>
                                <th>Product</th>
                                <th>PKD</th>
                                <th>From Factory</th>
                                <th>Carton</th>
                                <th>Date</th>
                                <th>Transferred From</th>
                                <th>Carton</th>
                                <th>Date</th>
                                <th>To DB</th>
                                <th>Carton</th>
                                <th>Date</th>
                                <th>Transferred To</th>
                                <th>Carton</th>
                                <th>Date</th>
                                <th>Created</th>
                                <th>Updated</th>
                            </tr>

                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->pivot->pkd }}</td>
                                    <td>{{ $product->pivot->from_factory_name }}</td>
                                    <td>{{ $product->pivot->from_factory_count }}</td>
                                    <td>{{ $product->pivot->from_factory_date }}</td>
                                    <td>{{ $product->pivot->from_transfer_name ? getWarehouseName($product->pivot->from_transfer_name) : '' }}</td>
                                    <td>{{ $product->pivot->from_transfer_count }}</td>
                                    <td>{{ $product->pivot->from_transfer_date }}</td>

                                    <td>{{ $product->pivot->to_db_name ? getDistributorName($product->pivot->to_db_name) : '' }}</td>
                                    <td>{{ $product->pivot->to_db_count }}</td>
                                    <td>{{ $product->pivot->to_db_date }}</td>
                                    <td>{{ $product->pivot->to_transfer_name ? getWarehouseName($product->pivot->to_transfer_name) : '' }}</td>
                                    <td>{{ $product->pivot->to_transfer_count }}</td>
                                    <td>{{ $product->pivot->to_transfer_date }}</td>
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
                        <h3 class="panel-title">Created At</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $warehouse->created_at }}</p>
                    </div>
                    <hr>

                    <div class="panel-heading">
                        <h3 class="panel-title">Updated At</h3>
                    </div>

                    <div class="panel-body">
                        <p>{{ $warehouse->updated_at }}</p>
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
        $('#detailReport').DataTable({
            "scrollX": true,
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                    $(aData).css({ "font-weight": "bold" });


                }
        });

    </script>

@stop
