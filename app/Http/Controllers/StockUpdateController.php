<?php

namespace App\Http\Controllers;

use App\Distributor;
use App\DistributorProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class StockUpdateController extends Controller
{

    public function index()
    {
        $stocks = DistributorProduct::all();
        return view('stockupdate.index', compact('stocks'));
    }

    public function create()
    {
        $distributors = Distributor::select('rsm_area')->distinct()->get();
        $products = Product::select('brandname')->distinct()->get();
        return view('stockupdate.create', compact('distributors', 'products'));
    }

    public function get_asm_area(Request $request)
    {
        $data = Distributor::where('rsm_area', $request->rsm_area)->select('asm_area')->distinct()->get();

        return response()->json($data);
    }

    public function get_tso_area(Request $request)
    {
        $data = Distributor::where('asm_area', $request->asm_area)->select('tso_area')->distinct()->get();

        return response()->json($data);
    }

    public function get_db_name(Request $request)
    {
        $data = Distributor::where('tso_area', $request->tso_area)->select('id', 'name')->distinct()->get();

        return response()->json($data);
    }

    public function get_product_category(Request $request)
    {
        $data = Product::where('brandname', $request->product_brand)->select('categoryname')->distinct()->get();

        return response()->json($data);
    }

    public function get_product_name(Request $request)
    {
        $data = Product::where('categoryname', $request->product_category)->select('id', 'name')->distinct()->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        DB::table('distributor_products')->insert([
            'distributor_id' => $request->db_name,
            'product_id' => $request->product_name,
            'opening_stock' => $request->opening_stock,
            'already_received' => $request->already_received,
            'stock_in_transit' => $request->stock_in_transit,
            'delivery_done' => $request->delivery_done,
            'in_delivery_van' => $request->in_delivery_van,
            'physical_stock' => $request->physical_stock,
            'pkg_date' => $request->pkg_date,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect(route('update-stock.index'));
    }

    public function edit($id)
    {
        $stock = DistributorProduct::findOrFail($id);
        if ($stock->declared) {
            return redirect(route('update-stock.index'));
        }
        $distributors = Distributor::select('rsm_area')->distinct()->get();
        $products = Product::select('brandname')->distinct()->get();
        return view('stockupdate.edit', compact('stock', 'distributors', 'products'));
    }

    public function update(Request $request, $id)
    {
        $stock = DistributorProduct::findOrFail($id);

        $stock->update([
            'distributor_id' => $request->db_name,
            'product_id' => $request->product_name,
            'opening_stock' => $request->opening_stock,
            'already_received' => $request->already_received,
            'stock_in_transit' => $request->stock_in_transit,
            'delivery_done' => $request->delivery_done,
            'in_delivery_van' => $request->in_delivery_van,
            'physical_stock' => $request->physical_stock,
            'pkg_date' => $request->pkg_date,
            'updated_at' => now()
        ]);

        return redirect(route('update-stock.index'));
    }

    public function declare(Request $request)
    {
        $stock = DistributorProduct::findOrFail($request->stock_id);
        $stock->update([
           'declared' => true,
           'declare_time' => now(),
        ]);

        return redirect(route('update-stock.index'));
    }


}
