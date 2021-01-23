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

        <h4 class="text-green-600">Distributor Filtering</h4>

        <div class="page-content row items-center">

            <div class="form-group col-md-2">
                <label>Region</label>
                <select class="form-control selectpicker" name="rsm_area" id="rsmArea" data-live-search="true">
                    <option selected value="">Nothing Selected</option>
                    @forelse($distributors as $distributor)
                        <option value="{{ $distributor->rsm_area }}">{{ $distributor->rsm_area }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="form-group col-md-2" id="asm">
                <label>Area</label>
                <select class="form-control selectpicker" name="asm_area" id="asmArea"
                        data-live-search="true"></select>
            </div>
            <div class="form-group col-md-2" id="tso">
                <label>Territory</label>
                <select class="form-control selectpicker" name="tso_area" id="tsoArea"
                        data-live-search="true"></select>
            </div>

            <div class="form-group col-md-4" id="db">
                <label>Distributor</label>
                <select class="form-control selectpicker" name="distributor_id" id="distributorId" required
                        data-live-search="true"></select>
            </div>

            <div class="form-group col-md-2">
                <label>Opening Date</label>
                <input class="form-control" type="date" name="opening_stock_date" id="openingStockDate" required>
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


        <div class="page-content row items-center">

            <div class="col-md-4">
                <h4 class="text-green-600">Data Entry</h4>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Opening Stock</label>
                        <input class="form-control " type="number" min="0" step="1" value="0" name="opening_stock" id="openingStock" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Physical Stock</label>
                        <input class="form-control" type="number" min="0" step="1" value="0" name="physical_stock" id="physicalStock" required>
                    </div>
                </div>
            </div>

            <div class="col-md-4 border border-green-600">
                <h4 class="text-green-600 text-center">Purchase</h4>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Already Received</label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="alreadyReceived" name="already_received">
                    </div>
                    <div class="form-group col-md-6">
                        <label>In Transit</label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="stockInTransit" name="stock_in_transit">
                    </div>
                </div>
            </div>
            <div class="col-md-4 border border-green-600">
                <h4 class="text-green-600 text-center">IMS</h4>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Delivery Done</label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="deliveryDone" name="delivery_done">
                    </div>
                    <div class="form-group col-md-6">
                        <label>In Delivery Van</label>
                        <input class="form-control" type="number" min="0" step="1" value="0" id="inDeliveryVan" name="in_delivery_van">
                    </div>
                </div>
            </div>


        </div>


            <div class="page-content row">

                <div class="col-md-7">
                    <div class="row">
                        <form action="{{ route('update-stock.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-md-6">
                                <label class="text-green-600">Distributor file (PDF)</label>
                                <input type="file" name="pdf">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="text-green-600">Distributor file (Excel)</label>
                                <input type="file" name="excel">
                            </div>
                            <button onclick="return confirm('Are you sure you want to save?')" type="submit" class="btn btn-danger ml-5 mt-10" id="save">Save</button>
                        </form>
                    </div>


                </div>

                <div class="col-md-5">
                    <button class="btn btn-success" id="draft">Draft</button>

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
                    <th>Distributor</th>
                    <th>Opening Date</th>
                    <th>Product</th>
                    <th>PKD</th>
                    <th>Opening Stock</th>
                    <th>Physical Stock</th>
                    <th>Already Received</th>
                    <th>In Transit</th>
                    <th>Delivery Done</th>
                    <th>Delivery Van</th>
                </tr>
                </thead>
                <tbody id="draftStockTable">
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

