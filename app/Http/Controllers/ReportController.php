<?php

namespace App\Http\Controllers;

use App\Distributor;
use App\Exports\DistributorExport;
use App\Exports\StockExport;
use App\ProductStock;
use App\Stock;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function index()
    {
        $stock_ids = Stock::all()->pluck('distributor_id');
        $distributors = Distributor::whereIn('id', $stock_ids)->get();

        return view('report.index', compact('distributors'));
    }

    public function individual_report(Request $request)
    {
        $stock = \App\Stock::where('distributor_id', $request->distributor_id)->first();
        $products = $stock->products()->withPivot('pkg_date', 'opening_stock', 'already_received', 'stock_in_transit',
            'delivery_done', 'in_delivery_van', 'physical_stock', 'created_at', 'updated_at')->get();

        $export_type = $request->export_type;

        $filename = $stock->distributor->name .'_'. now();

        if ($export_type == 'excel') {

            return Excel::download(new DistributorExport($stock, $products),
                $filename.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        } else if ($export_type == 'csv') {
            return Excel::download(new DistributorExport($stock, $products),
                $filename.'.csv', \Maatwebsite\Excel\Excel::CSV, [
                    'Content-Type' => 'text/csv',
                ]);
        }

    }

    public function overall_report(Request $request)
    {

        $product_stock = ProductStock::all();

        $export_type = $request->export_type;

        $filename = 'Overall_Distributors_Stock_Report_'. now();

        if ($export_type == 'excel') {

            return Excel::download(new StockExport($product_stock),
                $filename.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        } else if ($export_type == 'csv') {

            return Excel::download(new StockExport($product_stock),
                $filename.'.csv', \Maatwebsite\Excel\Excel::CSV, [
                    'Content-Type' => 'text/csv',
                ]);
        }
    }
}
