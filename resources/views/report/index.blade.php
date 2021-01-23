@extends('voyager::master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

@stop

<style>
    .row>[class*=col-] {
        margin-bottom: 10px !important;
    }
</style>


@section('content')

    @include('voyager::alerts')

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form action="{{ route('individual-report') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="text-xl mb-2" for="">Individual Distributor Report</label>
                        <select class="form-control selectpicker" name="distributor_id" id="" data-live-search="true">
                            <option value="-1">Nothing Selected</option>
                            @forelse($distributors as $distributor)
                                <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="form-radio" type="radio" name="export_type" value="csv">
                        <span class="ml-2 font-bold">CSV</span>
                    </div>
                    <div class="form-group">
                        <input class="form-radio" type="radio" name="export_type" value="excel">
                        <span class="ml-2 font-bold">Excel</span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-success" type="submit">Export</button>
                    </div>

                </form>
            </div>
            <div class="col-md-3">
                <form  action="{{ route('overall-report') }}" method="POST">
                    @csrf
                    <label class="text-xl mb-2" for="">Overall Distributor Report</label>

                    <div class="form-group">
                        <input class="form-radio" type="radio" name="export_type" value="csv">
                        <span class="ml-2 font-bold">CSV</span>
                    </div>
                    <div class="form-group">
                        <input class="form-radio" type="radio" name="export_type" value="excel">
                        <span class="ml-2 font-bold">Excel</span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-success" type="submit">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@stop



@section('javascript')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

@stop

