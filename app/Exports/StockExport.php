<?php

namespace App\Exports;

use App\ProductStock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class StockExport implements FromView
{
    private $product_stock;

    public function __construct($product_stock)
    {
        $this->product_stock = $product_stock;
    }

    public function view(): View
    {
        return \view('report.overall', [
            'product_stock' => $this->product_stock
        ]);
    }
}
