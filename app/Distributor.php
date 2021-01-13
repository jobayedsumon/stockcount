<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $guarded = [];
    //
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
