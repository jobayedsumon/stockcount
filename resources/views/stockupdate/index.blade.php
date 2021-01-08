@extends('voyager::master')

@section('css')

    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

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
                <th>Table_Head_1</th>
                <th>Table_Head_2</th>
                <th>Table_Head_3</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Table_Data_1</td>
                <td>Table_Data_2</td>
                <td>Table_Data_3</td>
            </tr>
            </tbody>
        </table>
    </div>



@stop

@section('javascript')

    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        $('document').ready(function () {
            $('#Table_ID').DataTable();
        });
    </script>
@stop
