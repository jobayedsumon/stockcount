<?php

namespace App\Http\Controllers;

use App\Distributor;
use App\Product;
use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class StockUpdateController extends Controller
{
    public function index()
    {
        return view('stockupdate.index');
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


    public function get_db_area(Request $request)
    {
        $data = Distributor::where('tso_area', $request->tso_area)->select('dbareaname')->distinct()->get();

        return response()->json($data);
    }

    public function get_db_name(Request $request)
    {
        $data = Distributor::where('dbareaname', $request->db_area)->select('id', 'name')->distinct()->get();

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


}
