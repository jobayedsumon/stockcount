<?php

function getDistributorName($id)
{
    $distributor = \App\Distributor::findOrFail($id);

    return $distributor->name;
}

function getProductName($id)
{
    $product = \App\Product::findOrFail($id);

    return $product->name;
}

function getWarehouseName($id)
{
    $warehouse = \App\Warehouse::findOrFail($id);

    return $warehouse->name;
}


?>
