@extends('voyager::master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

@stop

<style>
    #asm, #tso, #db, #category, #name {
        display: block !important;
    }
</style>


@section('content')

    <div class="container">

        <div class="page-content row ml-5 flex items-center">

            <div class="col-md-6">

                <form method="POST" action="{{ route('update-stock.update', $stock->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <h4 class="text-green-600">Distributor Filtering</h4>
                        <div class="form-group col-md-3 pl-0">
                            <label>RSM Area</label>
                            <select class="form-control selectpicker" name="rsm_area" id="rsmArea" data-live-search="true">
                                <option selected value="">Nothing Selected</option>
                                @forelse($distributors as $distributor)
                                    <option {!! $distributor->rsm_area == $stock->distributor->rsm_area ? 'selected' : '' !!} value="{{ $distributor->rsm_area }}">{{ $distributor->rsm_area }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group col-md-3" id="asm">
                            <label>ASM Area</label>
                            <select class="form-control selectpicker" name="asm_area" id="asmArea" data-live-search="true">
                                <option selected value="{{ $stock->distributor->asm_area }}">{{ $stock->distributor->asm_area }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3" id="tso">
                            <label>TSO Area</label>
                            <select class="form-control selectpicker" name="tso_area" id="tsoArea" data-live-search="true">
                                <option selected value="{{ $stock->distributor->tso_area }}">{{ $stock->distributor->tso_area }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3" id="db">
                            <label>Distributor Name</label><span class="text-danger">*</span>
                            <select class="form-control selectpicker" name="distributor_id" id="dbName" required data-live-search="true">
                                <option selected value="{{ $stock->distributor->id }}">{{ $stock->distributor->name }}</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <h4 class="text-green-600">Product Filtering</h4>
                        <div class="form-group col-md-4 pl-0">
                            <label>Product Brand</label>
                            <select class="form-control selectpicker" name="product_brand" id="productBrand" data-live-search="true">
                                <option selected>Nothing selected</option>
                                @forelse($products as $product)
                                    <option {!! $product->brandname == $stock->product->brandname ? 'selected' : '' !!} value="{{ $product->brandname }}">{{ $product->brandname }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group col-md-4" id="category">
                            <label>Product Category</label>
                            <select class="form-control selectpicker" name="product_category" id="productCategory" data-live-search="true">
                                <option selected value="{{ $stock->product->categoryname }}">{{ $stock->product->categoryname }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4" id="name">
                            <label>Product Name</label><span class="text-danger">*</span>
                            <select class="form-control selectpicker" name="product_id" id="productName" required data-live-search="true">
                                <option selected value="{{ $stock->product->id }}">{{ $stock->product->name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <h4 class="text-green-600">Product Stock Management</h4>

                        <div class="form-group col-md-4 pl-0">
                            <label>Opening Stock</label><span class="text-danger">*</span>
                            <input class="form-control " type="number" min="0" name="opening_stock" value="{{ $stock->opening_stock }}" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Physical Stock</label><span class="text-danger">*</span>
                            <input class="form-control" type="number" min="0" name="physical_stock" value="{{ $stock->physical_stock }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Package Date</label><span class="text-danger">*</span>
                            <input class="form-control" type="date" name="pkg_date" value="{{ $stock->pkg_date }}" required>
                        </div>

                    </div>

                    <div class="row">

                        <div class="purchase col-md-6 pl-0 mb-0">
                            <h4 class="text-green-600 text-center">Product Purchase (Optional)</h4>
                            <div class="form-group col-md-6 pl-0">
                                <label>Already Received</label>
                                <input class="form-control" type="number" min="0" name="already_received" value="{{ $stock->already_received }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Stock in Transit</label>
                                <input class="form-control" type="number" min="0" name="stock_in_transit" value="{{ $stock->stock_in_transit }}">
                            </div>
                        </div>

                        <div class="ims col-md-6">
                            <h4 class="text-green-600 text-center">Product IMS (Optional)</h4>
                            <div class="form-group col-md-6">
                                <label>Delivery Done</label>
                                <input class="form-control" type="number" min="0" name="delivery_done" value="{{ $stock->delivery_done }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>In Delivery Van</label>
                                <input class="form-control" type="number" min="0" name="in_delivery_van" value="{{ $stock->in_delivery_van }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <button class="btn btn-success bg-green-600 text-sm" type="submit">Update</button>

                            @if(session()->has('msg'))
                                <p class="text-danger font-bold">{{ session()->get('msg') }}</p>
                            @endif

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

                </form>


            </div>

            <div class="col-md-3 ml-10">
                <form method="POST" action="{{ route('update-stock.declare') }}">
                    @csrf
                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                    <button onclick="return alertBeforeDeclare()" class="btn btn-danger text-sm" type="submit">DECLARE</button>
                    <p class="text-danger">(Proceed with caution!)</p>
                    <p class="text-danger">(You can not update once declared)</p>
                </form>
            </div>

        </div>



    </div>


@stop

@section('javascript')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>

        function alertBeforeDeclare() {
            value = confirm('Declare? (Can\'t edit anymore!)');
            if(value) {
                value = confirm('Are you sure, you want to declare??');
                if(value) {
                    input = prompt('Type \'yes\' to declare');
                    if(input === 'yes') {
                        value = true;
                    } else {
                        value = false;
                    }
                }
            }
            return value;
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $('#rsmArea').on('change',function(e) {
                var rsm_area = e.target.value;
                $.ajax({
                    url:"/get-asm-area",
                    type:"POST",
                    data: {
                        rsm_area: rsm_area
                    },
                    success:function (data) {
                        var html = '<option value="">Nothing Selected</option>';
                        $('#asmArea').empty();
                        $.each(data,function(index){
                            html += '<option value="'+data[index].asm_area+'">'+data[index].asm_area+'</option>';
                        });

                        $('#asmArea').html(html);
                        $('#asmArea').selectpicker('refresh');
                    }
                });
                $('#asm').show('slow');
            });

            $('#asmArea').on('change',function(e) {
                var asm_area = e.target.value;
                $.ajax({
                    url:"/get-tso-area",
                    type:"POST",
                    data: {
                        asm_area: asm_area
                    },
                    success:function (data) {
                        var html = '<option value="">Nothing Selected</option>';
                        $('#tsoArea').empty();
                        $.each(data,function(index){
                            html += '<option value="'+data[index].tso_area+'">'+data[index].tso_area+'</option>';
                        });

                        $('#tsoArea').html(html);
                        $('#tsoArea').selectpicker('refresh');
                    }
                });
                $('#tso').show('slow');
            });

            $('#tsoArea').on('change',function(e) {
                var tso_area = e.target.value;
                $.ajax({
                    url:"/get-db-name",
                    type:"POST",
                    data: {
                        tso_area: tso_area
                    },
                    success:function (data) {
                        var html = '<option value="">Nothing Selected</option>';
                        $('#dbName').empty();
                        $.each(data,function(index){
                            html += '<option value="'+data[index].id+'">'+data[index].name+'</option>';
                        });

                        $('#dbName').html(html);
                        $('#dbName').selectpicker('refresh');
                    }
                });
                $('#db').show('slow');
            });

            $('#productBrand').on('change',function(e) {
                var product_brand = e.target.value;
                $.ajax({
                    url:"/get-product-category",
                    type:"POST",
                    data: {
                        product_brand: product_brand
                    },
                    success:function (data) {
                        var html = '<option value="">Nothing Selected</option>';
                        $('#productCategory').empty();
                        $.each(data,function(index){
                            html += '<option value="'+data[index].categoryname+'">'+data[index].categoryname+'</option>';
                        });

                        $('#productCategory').html(html);
                        $('#productCategory').selectpicker('refresh');
                    }
                });
                $('#category').show('slow');
            });

            $('#productCategory').on('change',function(e) {
                var product_category = e.target.value;
                var product_brand = $('#productBrand').find('option:selected').text();
                $.ajax({
                    url:"/get-product-name",
                    type:"POST",
                    data: {
                        product_category: product_category,
                        product_brand: product_brand
                    },
                    success:function (data) {
                        var html = '<option value="">Nothing Selected</option>';
                        $('#productName').empty();
                        $.each(data,function(index){
                            html += '<option value="'+data[index].id+'">'+data[index].name+'</option>';
                        });

                        $('#productName').html(html);
                        $('#productName').selectpicker('refresh');
                    }
                });
                $('#name').show('slow');
            });

        });




    </script>
@stop

