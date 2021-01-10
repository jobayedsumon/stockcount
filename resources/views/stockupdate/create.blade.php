@extends('voyager::master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')



@stop


@section('content')

    <div class="page-content container">

        <form method="POST" action="{{ route('update-stock.store') }}">
            @csrf
                <div class="row">
                    <h4 class="text-success text-center">Distributor Filtering</h4>
                    <div class="form-group col-md-3">
                        <label>RSM Area</label>
                        <select class="form-control" name="rsm_area" id="rsmArea">
                            <option selected value="">None Selected</option>
                            @forelse($distributors as $distributor)
                                <option value="{{ $distributor->rsm_area }}">{{ $distributor->rsm_area }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group col-md-3" id="asm">
                        <label>ASM Area</label>
                        <select class="form-control" name="asm_area" id="asmArea">
                            <option selected>None selected</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3" id="tso">
                        <label>TSO Area</label>
                        <select class="form-control" name="tso_area" id="tsoArea">
                            <option selected>None selected</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3" id="db">
                        <label>Distributor Name</label><span class="text-danger">*</span>
                        <select class="form-control" name="db_name" id="dbName" required>
                            <option selected>None selected</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <h4 class="text-success text-center">Product Filtering</h4>
                    <div class="form-group col-md-4">
                        <label>Product Brand</label>
                        <select class="form-control" name="product_brand" id="productBrand">
                            <option selected>None selected</option>
                            @forelse($products as $product)
                                <option value="{{ $product->brandname }}">{{ $product->brandname }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Product Category</label>
                        <select class="form-control" name="product_category" id="productCategory">
                            <option selected>None selected</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Product Name</label><span class="text-danger">*</span>
                        <select class="form-control" name="product_name" id="productName" required>
                            <option selected>None selected</option>
                        </select>
                    </div>
            </div>

                <div class="row">
                    <h4 class="text-success text-center">Product Stock Management</h4>

                    <div class="form-group col-md-4">
                        <label>Opening Stock</label><span class="text-danger">*</span>
                        <input class="form-control" type="number" name="opening_stock" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Physical Stock</label><span class="text-danger">*</span>
                        <input class="form-control" type="number" name="physical_stock" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Package Date</label>
                        <input class="form-control" type="date" name="pkg_date">
                    </div>

                </div>

            <div class="row">

                <h4 class="text-success text-center">Product Purchase / IMS Data (Optional)</h4>

                    <div class="purchase col-md-6">
                        <div class="form-group col-md-6">
                            <label>Already Received</label>
                            <input class="form-control" type="number" name="already_received">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Stock in Transit</label>
                            <input class="form-control" type="number" name="stock_in_transit">
                        </div>
                    </div>

                    <div class="ims col-md-6">
                        <div class="form-group col-md-6">
                            <label>Delivery Done</label>
                            <input class="form-control" type="number" name="delivery_done">
                        </div>
                        <div class="form-group col-md-6">
                            <label>In Delivery Van</label>
                            <input class="form-control" type="number" name="in_delivery_van">
                        </div>
                    </div>

            </div>

            <div class="form-group">
                <button style="width: 50%" class="form-control btn btn-success col-xs-offset-3" type="submit">Save</button>
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
                        $('#tsoArea').empty();
                        $('#tsoArea').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#tsoArea').append('<option value="'+data[index].tso_area+'">'+data[index].tso_area+'</option>');
                        })
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
                        $('#dbName').empty();
                        $('#dbName').append('<option>None selected</option>');
                        $.each(data,function(index){
                            $('#dbName').append('<option value="'+data[index].id+'">'+data[index].name+'</option>');
                        })
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

