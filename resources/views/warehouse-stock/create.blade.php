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

    <div class="container-fluid">

        <h4 class="text-green-600">Select Warehouse</h4>

        <div class="page-content row items-center">

            <div class="form-group col-md-2">
                <label>Warehouse</label>
                <select class="form-control selectpicker" name="warehouse" id="warehouse" data-live-search="true">
                    <option selected value="">Nothing Selected</option>
                    @forelse($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>


        </div>

        <h4 class="text-green-600">Product Filtering</h4>

                <div class="page-content row items-center">

                    <div class="form-group col-md-2">
                        <label>Brand</label>
                        <select class="form-control selectpicker" name="product_brand" id="productBrand"
                                data-live-search="true">
                            <option selected>Nothing selected</option>
                            @forelse($products as $product)
                                <option value="{{ $product->brandname }}">{{ $product->brandname }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group col-md-2" id="category">
                        <label>Category</label>
                        <select class="form-control selectpicker" name="product_category" id="productCategory"
                                data-live-search="true"></select>
                    </div>
                    <div class="form-group col-md-6" id="name">
                        <label>Product Name</label>
                        <select class="form-control selectpicker" name="product_id" id="productId" required
                                data-live-search="true"></select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>PKD</label>
                        <input class="form-control" type="month" name="pkg_date" id="pkgDate" required>
                    </div>
                </div>


        <div class="page-content row items-center ml-0.5">

            <h4 class="text-green-600 mb-1">Data Entry</h4>

            <div class="col-md-4 border border-green-600 mr-5">

                <h4 class="text-green-600 text-center">Inward</h4>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>From Factory (In Carton) </label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="fromFactory">
                    </div>
                    <div class="form-group col-md-6">
                        <label>From Transfer (In Carton) </label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="fromTransfer">
                    </div>
                </div>
            </div>

            <div class="col-md-4 border border-green-600">
                <h4 class="text-green-600 text-center">Outward</h4>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>To DB (In Carton) </label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="toDb">
                    </div>
                    <div class="form-group col-md-6">
                        <label>To Transfer (In Carton) </label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="toTransfer">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>PC/Carton</label>
                    <input class="form-control" type="number" min="0" step="1" value="0" id="perCarton">
                </div>
            </div>


        </div>


            <div class="page-content row">

                <div class="col-md-7">
                    <div class="row">
                        <form action="{{ route('update-stock.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <button onclick="return confirm('Are you sure you want to save?')" type="submit" class="btn btn-danger ml-5 mt-10" id="save">Save</button>
                        </form>
                    </div>


                </div>

                <div class="col-md-5">
                    <button class="btn btn-success" id="warehouseDraft">Draft</button>

                    <span class="text-danger font-bold" id="msg"></span>


                    <div id="validation-errors">

                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                </div>


            </div>


    <div class="container-fluid">
        <div class="row">
            <div class="justify-between">
                <h4 class="text-green-600 mb-5">Available Drafts:</h4>

            </div>


            <table class="table table-striped text-sm">
                <thead>
                <tr>
                    <th>Warehouse</th>
                    <th>Product</th>
                    <th>PKD</th>
                    <th>From Factory</th>
                    <th>From Transfer</th>
                    <th>To DB</th>
                    <th>To Transfer</th>
                    <th>PC/Carton</th>
                </tr>
                </thead>
                <tbody id="draftWarehouseStockTable">
                    <tr></tr>
                </tbody>
            </table>
        </div>
    </div>






@stop



@section('javascript')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

@stop

