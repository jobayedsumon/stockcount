@extends('voyager::master')

@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">

@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-chart"></i>
        Warehouse Stock Data
    </h1>

    <a class="btn btn-success" href="{{ route('warehouse-stock.create') }}">Create New Stock Record</a>

    @if(session()->has('msg'))
        <span class="text-success font-bold">{{ session()->get('msg') }}</span>
    @endif
@stop

@section('content')

    <div class="page-content container">

        <table id="Table_ID" class="table">
            <thead>
            <tr class="text-black">
                <th>S/L No</th>
                <th>Warehouse Name</th>
                <th>Warehouse Code</th>
                <th>Opening Stock</th>
                <th>Opening Stock Date</th>
                <th>Total Inward</th>
                <th>Total Outward</th>
                <th>Final Stock</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody class="text-black">
            @forelse($warehouses as $warehouse)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->code }}</td>
                <td>{{ $warehouse->opening_stock_count }}</td>
                <td>{{ $warehouse->opening_stock_date }}</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>{{ $warehouse->created_at ?? '' }}</td>
                <td>{{ $warehouse->updated_at ?? '' }}</td>
                <td>
                    <a class="btn btn-success" href="{{ route('warehouse-stock.show', $warehouse->id) }}">View</a>

                    <!-- Example single danger button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Adjust Stock <span class="voyager-double-down"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="btn btn-success ml-5" href="{{ route('adjust-inward', $warehouse->id) }}">Inward</a>
                            <div class="dropdown-divider"></div>
                            <a class="btn btn-primary ml-5" href="{{ route('adjust-outward', $warehouse->id) }}">Outward</a>
                        </div>
                    </div>





                </td>

            </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>



@stop

@section('javascript')

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

    <script>

            $('document').ready(function () {

                $('#Table_ID').DataTable({

                });

            });

    </script>
@stop
