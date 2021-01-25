
$(document).ready(function () {

    function is_int(value){
        if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
            return true;
        } else {
            return false;
        }
    }


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

       if (!is_int(openingStock) || !is_int(physicalStock)
       || !is_int(alreadyReceived) || !is_int(stockInTransit)
       || !is_int(deliveryDone) || !is_int(inDeliveryVan)) {

           $('#msg').text('Float/empty values are not allowed!');

       } else if (!distributorId || !productId) {
           $('#msg').text('Distributor and product both are required');
       }

       else if (!openingStockDate || !pkgDate) {
           $('#msg').text('Opening date and PKD both are required');
       }

       else {

           $.ajax({
               url: "/admin/update-stock/draft",
               type: "POST",
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
               success: function (data) {

                   if (data.validationError) {

                       $('#validation-errors').empty();
                       $.each(data.validationError, function (key, value) {
                           $('#validation-errors').append('<div class="alert alert-danger p-0">' + value + '</div');
                       });

                   } else if (data.msg) {
                       $('#validation-errors').empty();
                       $('#msg').text(data.msg);
                   } else {
                       distributorName = data['distributorName'];
                       productName = data['productName'];
                       confirmMsg = "Are you sure you want to remove the draft?";

                       html = '<tr>';
                       html += '<td>' + distributorName + '</td>';
                       html += '<td>' + productName + '</td>';
                       html += '<td>' + data["pkgDate"] + '</td>';
                       html += '<td>' + data["openingStock"] + '</td>';
                       html += '<td>' + data["openingStockDate"] + '</td>';
                       html += '<td>' + data["physicalStock"] + '</td>';
                       html += '<td>' + data["alreadyReceived"] + '</td>';
                       html += '<td>' + data["stockInTransit"] + '</td>';
                       html += '<td>' + data["deliveryDone"] + '</td>';
                       html += '<td>' + data["inDeliveryVan"] + '</td>';
                       html += '<td><a class="text-danger draftRemove" href="javascript:void(0);" data-draftid="' + data['draftId'] + '">Remove</a></td>';
                       html += '</tr>';

                       $('#draftStockTable tr:last').after(html);
                       $('#validation-errors').empty();
                       $('#msg').empty();

                       swal('Draft added successfully');
                   }

               },

           });
       }

    });

    $('#warehouseDraft').on('click', function (e) {
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

        if (!is_int(openingStock) || !is_int(physicalStock)
            || !is_int(alreadyReceived) || !is_int(stockInTransit)
            || !is_int(deliveryDone) || !is_int(inDeliveryVan)) {

            $('#msg').text('Float/empty values are not allowed!');

        } else if (!distributorId || !productId) {
            $('#msg').text('Distributor and product both are required');
        }

        else if (!openingStockDate || !pkgDate) {
            $('#msg').text('Opening date and PKD both are required');
        }

        else {

            $.ajax({
                url: "/admin/update-stock/draft",
                type: "POST",
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
                success: function (data) {

                    if (data.validationError) {

                        $('#validation-errors').empty();
                        $.each(data.validationError, function (key, value) {
                            $('#validation-errors').append('<div class="alert alert-danger p-0">' + value + '</div');
                        });

                    } else if (data.msg) {
                        $('#validation-errors').empty();
                        $('#msg').text(data.msg);
                    } else {
                        distributorName = data['distributorName'];
                        productName = data['productName'];
                        confirmMsg = "Are you sure you want to remove the draft?";

                        html = '<tr>';
                        html += '<td>' + distributorName + '</td>';
                        html += '<td>' + productName + '</td>';
                        html += '<td>' + data["pkgDate"] + '</td>';
                        html += '<td>' + data["openingStock"] + '</td>';
                        html += '<td>' + data["openingStockDate"] + '</td>';
                        html += '<td>' + data["physicalStock"] + '</td>';
                        html += '<td>' + data["alreadyReceived"] + '</td>';
                        html += '<td>' + data["stockInTransit"] + '</td>';
                        html += '<td>' + data["deliveryDone"] + '</td>';
                        html += '<td>' + data["inDeliveryVan"] + '</td>';
                        html += '<td><a class="text-danger draftRemove" href="javascript:void(0);" data-draftid="' + data['draftId'] + '">Remove</a></td>';
                        html += '</tr>';

                        $('#draftStockTable tr:last').after(html);
                        $('#validation-errors').empty();
                        $('#msg').empty();

                        swal('Draft added successfully');
                    }

                },

            });
        }

    });


    $(document.body).on('click', '.draftRemove', function (e) {

        if (confirm('Are you sure you want to remove draft?')) {

            draftId = $(this).data('draftid');
            draftRow = $(this).closest('tr');

            $.ajax({
                type: 'GET',
                url: '/admin/draft/remove/'+draftId,

                success: function (msg) {
                    draftRow.remove();
                    swal(msg);
                }
            });
        }

    });


    function gettime() {
        var date = new Date();
        var newdate = (date.getHours() % 12 || 12) + "_" + date.getMinutes() + "_" + date.getSeconds();
        setInterval(gettime, 1000);
        return newdate;
    }

    // $('#Table_ID').DataTable({
    //     dom: 'Bfrtip',
    //     buttons: [
    //         {
    //             extend: 'excelHtml5',
    //             title: 'Stock Report ' + new Date().toDateString() + ' ' + gettime(),
    //             exportOptions: {
    //                 columns: ':not(:last-child)',
    //             }
    //
    //         },
    //         {
    //             extend: 'pdfHtml5',
    //             title: 'Stock Report ' + new Date().toDateString() + ' ' + gettime(),
    //
    //         },
    //         {
    //             extend: 'csvHtml5',
    //             title: 'Stock Report ' + new Date().toDateString() + ' ' + gettime(),
    //             exportOptions: {
    //                 columns: ':not(:last-child)',
    //             }
    //
    //         }
    //     ]
    //
    //
    // });





});
