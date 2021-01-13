<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];
    //
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
