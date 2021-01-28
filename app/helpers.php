<?php

use App\Distributor;
use App\Product;
use App\Warehouse;

function getDistributorName($id)
{
    $distributor = Distributor::findOrFail($id);

    return $distributor->name;
}

function getDistributorId($name)
{
    $distributor = Distributor::where('name', '=', $name)->first();

    return $distributor->id;
}

function getProductName($id)
{
    $product = Product::findOrFail($id);

    return $product->name;
}

function getProductId($name)
{
    $product = Product::where('name', '=', $name)->first();

    return $product->id;
}

function getWarehouseName($id)
{
    $warehouse= Warehouse::findOrFail($id);

    return $warehouse->name;
}

function getWarehouseId($name)
{
    $warehouse = Warehouse::where('name', '=', $name)->first();

    return $warehouse->id;
}


?>
