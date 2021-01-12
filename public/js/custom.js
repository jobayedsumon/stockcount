
$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
                $('#distributorId').empty();
                $.each(data,function(index){
                    html += '<option value="'+data[index].id+'">'+data[index].name+'</option>';
                });

                $('#distributorId').html(html);
                $('#distributorId').selectpicker('refresh');
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
                $('#productId').empty();
                $.each(data,function(index){
                    html += '<option value="'+data[index].id+'">'+data[index].name+'</option>';
                });

                $('#productId').html(html);
                $('#productId').selectpicker('refresh');
            }
        });
        $('#name').show('slow');
    });

    $('#draft').on('click', function (e) {
       e.preventDefault();

       distributorId = $('#distributorId').val();
       productId = $('#productId').val();
       pkgDate = $('#pkgDate').val();
       openingStock = $('#openingStock').val();
       openingStockDate = $('#openingStockDate').val();
       physicalStock = $('#physicalStock').val();
       alreadyReceived = $('#alreadyReceived').val();
       stockInTransit = $('#stockInTransit').val();
       deliveryDone = $('#deliveryDone').val();
       inDeliveryVan = $('#inDeliveryVan').val();

        $.ajax({
            url:"/admin/update-stock/draft",
            type:"POST",
            data: {
                distributorId: distributorId,
                productId: productId,
                pkgDate: pkgDate,
                openingStock: openingStock,
                openingStockDate: openingStockDate,
                physicalStock: physicalStock,
                alreadyReceived: alreadyReceived,
                stockInTransit: stockInTransit,
                deliveryDone: deliveryDone,
                inDeliveryVan: inDeliveryVan,
            },
            success:function (data) {

                if (data.msg) {

                    $('#msg').text(data.msg);

                } else {
                    distributorName = data['distributorName'];
                    productName = data['productName'];

                    html = '<tr>';
                    html += '<td>'+distributorName+'</td>';
                    html += '<td>'+productName+'</td>';
                    html += '<td>'+data["pkgDate"]+'</td>';
                    html += '<td>'+data["openingStock"]+'</td>';
                    html += '<td>'+data["openingStockDate"]+'</td>';
                    html += '<td>'+data["physicalStock"]+'</td>';
                    html += '<td>'+data["alreadyReceived"]+'</td>';
                    html += '<td>'+data["stockInTransit"]+'</td>';
                    html += '<td>'+data["deliveryDone"]+'</td>';
                    html += '<td>'+data["inDeliveryVan"]+'</td>';
                    html += '</tr>';

                    $('#draftStockTable tr:last').after(html);
                }

            },

            error: function (xhr) {
                console.log(xhr);
                $('#validation-errors').html('');
                $.each(xhr.responseJSON.errors, function(key,value) {
                    $('#validation-errors').append('<div class="alert alert-danger">'+value+'</div');
                });
            },
        });

    });


});
