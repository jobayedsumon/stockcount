<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(
            'from_factory_name', 'from_factory_count', 'from_factory_date', 'from_factory_per_carton',
            'from_transfer_name', 'from_transfer_count', 'from_transfer_date', 'from_transfer_per_carton',
            'to_db_name', 'to_db_count', 'to_db_date', 'to_db_per_carton',
            'to_transfer_name', 'to_transfer_count', 'to_transfer_date', 'to_transfer_per_carton',
            'pkd'
        )->withTimestamps();
    }
}
