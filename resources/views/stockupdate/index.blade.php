@extends('voyager::master')

@section('css')

    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">

@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-chart"></i>
        Update Stock
    </h1>

        <a class="btn btn-success" href="{{ route('update-stock.create') }}">Create New Stock Record</a>
@stop

@section('content')

    <div class="page-content container">

        <table id="Table_ID">
            <thead>
            <tr>
                <th>S/L No</th>
                <th>Distributor Name</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Opening Stock</th>
                <th>Already Received</th>
                <th>Stock in Transit</th>
                <th>Delivery Done</th>
                <th>In Delivery Van</th>
                <th>Physical Stock</th>
                <th>Total Stock</th>
                <th>PKG Date</th>
                <th>Declared</th>
                <th>Declare Time</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $stock)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $stock->distributor->name }}</td>
                <td>{{ $stock->product->name }}</td>
                <td>{{ $stock->product->code }}</td>
                <td>{{ $stock->opening_stock }}</td>
                <td>{{ $stock->already_received }}</td>
                <td>{{ $stock->stock_in_transit }}</td>
                <td>{{ $stock->delivery_done }}</td>
                <td>{{ $stock->in_delivery_van }}</td>
                <td>{{ $stock->physical_stock }}</td>
                @php

                    $total = 0;

                    $total += $stock->opening_stock;
                    $total += $stock->already_received;
                    $total += $stock->stock_in_transit;
                    $total -= $stock->delivery_done;
                    $total -= $stock->in_delivery_van;
                    $total += $stock->physical_stock;

                    @endphp
                <td>{{ $total }}</td>
                <td>{{ $stock->pkg_date ?? '' }}</td>
                <td>{{ $stock->declared ? 'Yes' : 'No' }}</td>
                <td>{{ $stock->declare_time ?? '' }}</td>
                <td>{{ $stock->created_at ?? '' }}</td>
                <td>{{ $stock->updated_at ?? '' }}</td>
                <td>
                    @if(!$stock->declared)
                        <a class="btn btn-primary" href="{{ route('update-stock.edit', $stock->id) }}">Edit</a>
                    @else
                        <span class="text-danger">Already Declared</span>
                    @endif
                </td>
            </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>



@stop

@section('javascript')

    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    // <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    // <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    // <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    // <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    // <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    // <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    // <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

    <script>

            $('document').ready(function () {

                function gettime() {
                    var date = new Date();
                    var newdate = (date.getHours() % 12 || 12) + "_" + date.getMinutes() + "_" + date.getSeconds();
                    //setInterval(gettime, 1000);
                    return newdate;
                }

            $('#Table_ID').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Stock Report ' + new Date().toDateString() + ' ' + gettime()
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Stock Report ' + new Date().toDateString() + ' ' + gettime()
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Stock Report ' + new Date().toDateString() + ' ' + gettime()
                    }
                ]
            });
        });
    </script>
@stop
