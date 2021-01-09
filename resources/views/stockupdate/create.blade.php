@extends('voyager::master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')



@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-chart"></i>
        Create Stock
    </h1>

@stop

@section('content')

    <div class="page-content container">

        <form method="POST" action="{{ route('update-stock.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <h3>Distributor and Product Filtering</h3>
                    <div class="form-group">
                        <label>RSM Area</label>
                        <select class="form-control" name="rsm_area" id="rsmArea">
                            <option selected value="">None Selected</option>
                            @forelse($distributors as $distributor)
                                <option value="{{ $distributor->rsm_area }}">{{ $distributor->rsm_area }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ASM Area</label>
                        <select class="form-control" name="asm_area" id="asmArea">
                            <option selected>None selected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>TSO Area</label>
                        <select class="form-control" name="tso_area" id="tsoArea">
                            <option selected>None selected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Distribution Area</label>
                        <select class="form-control" name="db_area" id="dbArea">
                            <option selected>None selected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Distributor Name</label><span class="text-danger">*</span>
                        <select class="form-control" name="db_name" id="dbName" required>
                            <option selected>None selected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Brand</label>
                        <select class="form-control" name="product_brand" id="productBrand">
                            <option selected>None selected</option>
                            @forelse($products as $product)
                                <option value="{{ $product->brandname }}">{{ $product->brandname }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Category</label>
                        <select class="form-control" name="product_category" id="productCategory">
                            <option selected>None selected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Name</label><span class="text-danger">*</span>
                        <select class="form-control" name="product_name" id="productName" required>
                            <option selected>None selected</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Product Stock Management</h3>

                    <div class="form-group">
                        <label>Opening Stock</label><span class="text-danger">*</span>
                        <input class="form-control" type="number" name="opening_stock" required>
                    </div>

                    <div class="form-group">
                        <input id="purchase" type="checkbox" value="purchase">
                        <label for="purchase">Purchase</label>
                    </div>

                    <div class="purchase">
                        <div class="form-group">
                            <label>Already Received</label>
                            <input class="form-control" type="number" name="already_received">
                        </div>
                        <div class="form-group">
                            <label>Stock in Transit</label>
                            <input class="form-control" type="number" name="stock_in_transit">
                        </div>
                    </div>

                    <div class="form-group">
                        <input id="ims" type="checkbox" value="ims">
                        <label for="ims">IMS</label>
                    </div>

                    <div class="ims">
                        <div class="form-group">
                            <label>Delivery Done</label>
                            <input class="form-control" type="number" name="delivery_done">
                        </div>
                        <div class="form-group">
                            <label>In Delivery Van</label>
                            <input class="form-control" type="number" name="in_delivery_van">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Physical Stock</label><span class="text-danger">*</span>
                        <input class="form-control" type="number" name="physical_stock" required>
                    </div>
                    <div class="form-group">
                        <label>Package Date</label>
                        <input class="form-control" type="date" name="pkg_date">
                    </div>

                    <div class="form-group">
                        <button class="form-control btn btn-success" type="submit">Save</button>
                    </div>
                </div>
            </div>





        </form>

    </div>






@stop

@section('javascript')
    <script>
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
                        $('#asmArea').empty();
                        $('#asmArea').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#asmArea').append('<option value="'+data[index].asm_area+'">'+data[index].asm_area+'</option>');
                        })
                    }
                })
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
                        $('#tsoArea').empty();
                        $('#tsoArea').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#tsoArea').append('<option value="'+data[index].tso_area+'">'+data[index].tso_area+'</option>');
                        })
                    }
                })
            });

            $('#tsoArea').on('change',function(e) {
                var tso_area = e.target.value;
                $.ajax({
                    url:"/get-db-area",
                    type:"POST",
                    data: {
                        tso_area: tso_area
                    },
                    success:function (data) {
                        $('#dbArea').empty();
                        $('#dbArea').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#dbArea').append('<option value="'+data[index].dbareaname+'">'+data[index].dbareaname+'</option>');
                        })
                    }
                })
            });

            $('#dbArea').on('change',function(e) {
                var db_area = e.target.value;
                $.ajax({
                    url:"/get-db-name",
                    type:"POST",
                    data: {
                        db_area: db_area
                    },
                    success:function (data) {
                        $('#dbName').empty();
                        $('#dbName').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#dbName').append('<option value="'+data[index].id+'">'+data[index].name+'</option>');
                        })
                    }
                })
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
                        $('#productCategory').empty();
                        $('#productCategory').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#productCategory').append('<option value="'+data[index].categoryname+'">'+data[index].categoryname+'</option>');
                        })
                    }
                })
            });

            $('#productCategory').on('change',function(e) {
                var product_category = e.target.value;
                $.ajax({
                    url:"/get-product-name",
                    type:"POST",
                    data: {
                        product_category: product_category
                    },
                    success:function (data) {
                        $('#productName').empty();
                        $('#productName').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#productName').append('<option value="'+data[index].id+'">'+data[index].name+'</option>');
                        })
                    }
                })
            });

            $('input[type="checkbox"]').click(function(){
                var inputValue = $(this).attr("value");
                $("." + inputValue).toggle('slow');
            });


        });




    </script>
@stop

