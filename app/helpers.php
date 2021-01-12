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


?>
