<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistributorProduct extends Model
{
    //
    protected $guarded = [];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
