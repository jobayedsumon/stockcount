<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Distributor;
use App\ProductStock;
use App\Stock;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class ProductChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $stocks = Stock::all();

        $distributors = [];
        $products = [];

        foreach ($stocks as $stock) {
            array_push($distributors, $stock->distributor->name);
            array_push($products, $stock->products()->count());
        }

        return Chartisan::build()
            ->labels($distributors)
            ->dataset('Total Products', $products);
    }
}
