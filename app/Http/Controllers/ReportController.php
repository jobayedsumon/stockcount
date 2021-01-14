<?php

namespace App\Http\Controllers;

use App\Stock;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function download_pdf($stockId)
    {
        $stock = Stock::findOrFail($stockId);

        return response()->download(storage_path('app'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.$stock->pdf));
    }

    public function download_excel($stockId)
    {
        $stock = Stock::findOrFail($stockId);

        return response()->download(storage_path('app'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'excel'.DIRECTORY_SEPARATOR.$stock->excel));
    }
}
