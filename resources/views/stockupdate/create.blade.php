@extends('voyager::master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

@stop


@section('content')

    <div class="container">

        <div class="page-content row">

            <div class="col-md-6">

                <div class="row">
                    <h4 class="text-green-600">Distributor Filtering</h4>
                    <div class="form-group col-md-3 pl-0">
                        <label>RSM Area</label>
                        <select class="form-control selectpicker" name="rsm_area" id="rsmArea" data-live-search="true">
                            <option selected value="">Nothing Selected</option>
                            @forelse($distributors as $distributor)
                                <option value="{{ $distributor->rsm_area }}">{{ $distributor->rsm_area }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group col-md-3" id="asm">
                        <label>ASM Area</label>
                        <select class="form-control selectpicker" name="asm_area" id="asmArea"
                                data-live-search="true"></select>
                    </div>
                    <div class="form-group col-md-3" id="tso">
                        <label>TSO Area</label>
                        <select class="form-control selectpicker" name="tso_area" id="tsoArea"
                                data-live-search="true"></select>
                    </div>

                    <div class="form-group col-md-3" id="db">
                        <label>Distributor Name</label><span class="text-danger">*</span>
                        <select class="form-control selectpicker" name="distributor_id" id="distributorId" required
                                data-live-search="true"></select>
                    </div>

                </div>
                <div class="row">
                    <h4 class="text-green-600">Product Filtering</h4>
                    <div class="form-group col-md-4 pl-0">
                        <label>Product Brand</label>
                        <select class="form-control selectpicker" name="product_brand" id="productBrand"
                                data-live-search="true">
                            <option selected>Nothing selected</option>
                            @forelse($products as $product)
                                <option value="{{ $product->brandname }}">{{ $product->brandname }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group col-md-4" id="category">
                        <label>Product Category</label>
                        <select class="form-control selectpicker" name="product_category" id="productCategory"
                                data-live-search="true"></select>
                    </div>
                    <div class="form-group col-md-4" id="name">
                        <label>Product Name</label><span class="text-danger">*</span>
                        <select class="form-control selectpicker" name="product_id" id="productId" required
                                data-live-search="true"></select>
                    </div>
                </div>

                <div class="form-group row w-1/4">
                    <label>Packaging Date</label><span class="text-danger">*</span>
                    <input class="form-control" type="month" name="pkg_date" id="pkgDate" required>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 pl-0">
                        <label class="text-green-600">Distributor file (PDF)</label>
                        <input type="file" name="pdf">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="text-green-600">Distributor file (Excel)</label>
                        <input type="file" name="excel">
                    </div>

                </div>



                <div class="row">

                    <form action="{{ route('update-stock.store') }}" method="POST">
                        @csrf
                        <button class="btn btn-success bg-green-600" id="save">Save</button>
                    </form>


                    @if(session()->has('msg'))
                        <span class="text-danger font-bold relative">{{ session()->get('msg') }}</span>
                    @endif

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


            <div class="col-md-6">
                <div class="row">
                    <h4 class="text-green-600">Product Stock Management</h4>

                    <div class="form-group col-md-3 pl-0">
                        <label>Opening Stock</label><span class="text-danger">*</span>
                        <input class="form-control " type="number" min="0" name="opening_stock" id="openingStock" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Opening Date</label><span class="text-danger">*</span>
                        <input class="form-control " type="date" name="opening_stock_date" id="openingStockDate" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Physical Stock</label><span class="text-danger">*</span>
                        <input class="form-control" type="number" min="0" name="physical_stock" id="physicalStock" required>
                    </div>

                </div>

                <div class="row">

                    <div class="purchase col-md-6 pl-0 mb-0">
                        <h4 class="text-green-600 text-center">Product Purchase (Optional)</h4>
                        <div class="form-group col-md-6 pl-0">
                            <label>Already Received</label>
                            <input class="form-control" type="number" min="0" id="alreadyReceived" name="already_received">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Stock in Transit</label>
                            <input class="form-control" type="number" min="0" id="stockInTransit" name="stock_in_transit">
                        </div>
                    </div>

                    <div class="ims col-md-6">
                        <h4 class="text-green-600 text-center">Product IMS (Optional)</h4>
                        <div class="form-group col-md-6">
                            <label>Delivery Done</label>
                            <input class="form-control" type="number" min="0" id="deliveryDone" name="delivery_done">
                        </div>
                        <div class="form-group col-md-6">
                            <label>In Delivery Van</label>
                            <input class="form-control" type="number" min="0" id="inDeliveryVan" name="in_delivery_van">
                        </div>
                    </div>


                </div>

                <button class="btn btn-warning mb-5" id="draft">Draft</button>

                <div class="flex justify-between">
                    <h4 class="text-green-600 mb-5">Available Drafts:</h4>
                    <span class="text-danger font-bold" id="msg"></span>
                </div>


                <table class="table table-striped text-sm" id="draftStockTable">
                    <thead>
                    <tr>
                        <th>Distributor</th>
                        <th>Product</th>
                        <th>PKD</th>
                        <th>Opening Stock</th>
                        <th>Opening Date</th>
                        <th>Physical Stock</th>
                        <th>Already Received</th>
                        <th>Transit Stock</th>
                        <th>Delivery Done</th>
                        <th>Delivery Van</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(session()->has('draftStock'))
                    @forelse(session()->get('draftStock') as $draftStock)
                    <tr>
                        <td>{{ getDistributorName($draftStock['distributorId']) }}</td>
                        <td>{{ getProductName($draftStock['productId']) }}</td>
                        <td>{{ $draftStock['pkgDate'] }}</td>
                        <td>{{ $draftStock['openingStock'] }}</td>
                        <td>{{ $draftStock['openingStockDate'] }}</td>
                        <td>{{ $draftStock['physicalStock'] }}</td>
                        <td>{{ $draftStock['alreadyReceived'] }}</td>
                        <td>{{ $draftStock['stockInTransit'] }}</td>
                        <td>{{ $draftStock['deliveryDone'] }}</td>
                        <td>{{ $draftStock['inDeliveryVan'] }}</td>
                    </tr>
                    @empty
                    @endforelse
                    @endif
                    </tbody>
                </table>

            </div>



        </div>


    </div>


@stop

@section('javascript')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

@stop

