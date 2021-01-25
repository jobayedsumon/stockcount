<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    //
    public function stocks()
    {
        return $this->belongsToMany(Stock::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }
}
