@extends('voyager::master')

@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">

@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-chart"></i>
        Stock Data
    </h1>

    <a class="btn btn-success" href="{{ route('update-stock.create') }}">Create New Stock Record</a>

    @if(session()->has('msg'))
        <span class="text-success font-bold">{{ session()->get('msg') }}</span>
    @endif
@stop

@section('content')

    <div class="page-content container">

        <table id="Table_ID" class="table">
            <thead>
            <tr>
                <th>S/L No</th>
                <th>Distributor</th>
                <th>Total Products</th>
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
                <td>{{ $stock->products()->count() }}</td>
                <td>{{ $stock->declared ? 'Yes' : 'No' }}</td>
                <td>{{ $stock->declare_time ?? '' }}</td>
                <td>{{ $stock->created_at ?? '' }}</td>
                <td>{{ $stock->updated_at ?? '' }}</td>
                <td>
                    <a class="btn btn-success" href="{{ route('update-stock.show', $stock->id) }}">View</a>
                    @if(!$stock->declared)
                        <a class="btn btn-primary" href="{{ route('update-stock.edit', $stock->id) }}">Edit</a>
                    @else
                        <span class="text-danger font-bold">Already Declared</span>
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
