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
}
