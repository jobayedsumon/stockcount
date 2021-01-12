<?php

namespace App\Http\Controllers;

use App\Distributor;
use App\DistributorProduct;
use App\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Exception;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class StockUpdateController extends Controller
{

    public function index()
    {
        $stocks = DistributorProduct::latest()->get();
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
        $data = Product::where('brandname', $request->product_brand)->where('categoryname', $request->product_category)->select('id', 'name')->distinct()->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $draftStock = session()->pull('draftStock');

        foreach ($draftStock as $draft) {

            $draft->validate([
                'distributorId' => 'required|numeric',
                'productId' => 'required|numeric',
                'openingStock' => 'required|numeric',
                'physicalStock' => 'required|numeric',
                'pkgDate' => 'required|date',
                'alreadyReceived' => 'nullable|numeric',
                'stockInTransit' => 'nullable|numeric',
                'deliveryDone' => 'nullable|numeric',
                'inDeliveryVan' => 'nullable|numeric',
            ]);
        }

        try {
            foreach ($draftStock as $draft) {
                DB::table('distributor_products')->insertOrIgnore([
                    'distributor_id' => $draft['distributorId'],
                    'product_id' => $draft['productId'],
                    'opening_stock' => $draft['openingStock'],
                    'already_received' => $draft['alreadyReceived'],
                    'stock_in_transit' => $draft['stockInTransit'],
                    'delivery_done' => $draft['deliveryDone'],
                    'in_delivery_van' => $draft['inDeliveryVan'],
                    'physical_stock' => $draft['physicalStock'],
                    'pkg_date' => $draft['pkgDate'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[1] == 1062) {
                $msg = 'Stock for this Product and Distributor already exists!';
            } else {
                $msg = 'Can not create stock';
            }

            return redirect(route('update-stock.create'))->with('msg', $msg);
        }

        $msg = 'Stock created successfully!';
        return redirect(route('update-stock.create'))->with('msg', $msg);
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
        $request->validate([
            'distributor_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'opening_stock' => 'required|numeric',
            'physical_stock' => 'required|numeric',
            'pkg_date' => 'required|date',
            'already_received' => 'nullable|numeric',
            'stock_in_transit' => 'nullable|numeric',
            'delivery_done' => 'nullable|numeric',
            'in_delivery_van' => 'nullable|numeric',
        ]);

        try {

            $stock = DistributorProduct::findOrFail($id);

            $stock->update([
                'distributor_id' => $request->distributor_id,
                'product_id' => $request->product_id,
                'opening_stock' => $request->opening_stock,
                'already_received' => $request->already_received,
                'stock_in_transit' => $request->stock_in_transit,
                'delivery_done' => $request->delivery_done,
                'in_delivery_van' => $request->in_delivery_van,
                'physical_stock' => $request->physical_stock,
                'pkg_date' => $request->pkg_date,
                'updated_at' => now()
            ]);

        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[1] == 1062) {
                $msg = 'Stock for this Product and Distributor already exists!';
            } else {
                $msg = 'Can not update stock';
            }

            return redirect(route('update-stock.edit', $id))->with('msg', $msg);
        }

        $msg = 'Stock updated successfully!';
        return redirect(route('update-stock.index'))->with('msg', $msg);

    }

    public function declare(Request $request)
    {
        $stock = DistributorProduct::findOrFail($request->stock_id);
        $stock->update([
           'declared' => true,
           'declare_time' => now(),
        ]);

        return redirect(route('update-stock.index'))->with('msg', 'Stock declared successfully');
    }

    public function draft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distributorId' => 'required|numeric',
            'productId' => 'required|numeric',
            'openingStock' => 'required|numeric',
            'physicalStock' => 'required|numeric',
            'pkgDate' => 'required|date',
            'alreadyReceived' => 'nullable|numeric',
            'stockInTransit' => 'nullable|numeric',
            'deliveryDone' => 'nullable|numeric',
            'inDeliveryVan' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $msg = $validator->errors();
            return response()->json(['msg' => $msg]);
        }

        $draftStock = session()->get('draftStock') ?? [];

        foreach ($draftStock as $draft) {
            if ($draft['productId'] == $request->productId &&
                $draft['distributorId'] == $request->distributorId &&
                $draft['pkgDate'] == $request->pkgDate) {

                $msg = 'Duplicate data can\'t be added';

                return response()->json(['msg' => $msg]);
            }
        }

        $data = $request->all();
        $data['distributorName'] = getDistributorName($request->distributorId);
        $data['productName'] = getProductName($request->productId);

        array_push($draftStock, $data);

        session()->put('draftStock', $draftStock);

        return $data;

    }


}
