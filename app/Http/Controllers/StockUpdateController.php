<?php

namespace App\Http\Controllers;

use App\Distributor;
use App\DistributorProduct;
use App\Product;
use App\ProductStock;
use App\Stock;
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
        $stocks = Stock::latest()->get();
        return view('stockupdate.index', compact('stocks'));
    }

    public function show($id)
    {
        $stock = Stock::findOrFail($id);

        $products = $stock->products()->withPivot('pkg_date', 'opening_stock', 'already_received', 'stock_in_transit',
            'delivery_done', 'in_delivery_van', 'physical_stock', 'created_at', 'updated_at')->get();

        return view('stockupdate.view', compact('stock', 'products'));
    }

    public function create()
    {
        \session()->forget(['draftStock']);
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

        if (!$draftStock) {
            $msg = 'No data in drafts.';
            return redirect(route('update-stock.create'))->with('msg', $msg);
        }

        foreach ($draftStock as $draft) {

            $validator = Validator::make($draft, [
                'distributorId' => 'required|numeric',
                'productId' => 'required|numeric',
                'openingStock' => 'required|numeric',
                'physicalStock' => 'required|numeric',
                'pkgDate' => 'required|date',
                'openingStockDate' => 'required|date',
                'alreadyReceived' => 'nullable|numeric',
                'stockInTransit' => 'nullable|numeric',
                'deliveryDone' => 'nullable|numeric',
                'inDeliveryVan' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                $msg = $validator->errors();
                return response()->json(['msg' => $msg]);
            }
        }

            foreach ($draftStock as $draft) {

                $stock = Stock::where('distributor_id', $draft['distributorId'])->first();

                if ($stock) {
                    $stock->update([
                        'stock_opening_date' => $draft['openingStockDate'],
                        'updated_at' => now()
                    ]);
                } else {
                    $stock = Stock::create([
                        'distributor_id' => $draft['distributorId'],
                        'stock_opening_date' => $draft['openingStockDate'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $product_stock = ProductStock::where('stock_id', $stock->id)
                    ->where('product_id', $draft['productId'])->where('pkg_date', $draft['pkgDate'])->first();

                if ($product_stock) {

                    $product_stock->update([
                        'opening_stock' => $draft['openingStock'],
                        'already_received' => $draft['alreadyReceived'],
                        'stock_in_transit' => $draft['stockInTransit'],
                        'delivery_done' => $draft['deliveryDone'],
                        'in_delivery_van' => $draft['inDeliveryVan'],
                        'physical_stock' => $draft['physicalStock'],
                        'updated_at' => now()
                    ]);

                } else {

                    DB::table('product_stock')->insert([
                        'stock_id' => $stock->id,
                        'product_id' => $draft['productId'],
                        'pkg_date' => $draft['pkgDate'],
                        'opening_stock' => $draft['openingStock'],
                        'already_received' => $draft['alreadyReceived'],
                        'stock_in_transit' => $draft['stockInTransit'],
                        'delivery_done' => $draft['deliveryDone'],
                        'in_delivery_van' => $draft['inDeliveryVan'],
                        'physical_stock' => $draft['physicalStock'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }


            }

            $excel = $request->file('excel');
            $pdf = $request->file('pdf');

            if ($excel) {
                $excel->storeAs('files/excel', $excel->getClientOriginalName());
                $stock->update([
                   'excel' => $excel->getClientOriginalName()
                ]);
            }
            if ($pdf) {
                $pdf->storeAs('files/pdf', $pdf->getClientOriginalName());
                $stock->update([
                    'pdf' => $pdf->getClientOriginalName()
                ]);
            }

        $msg = 'Stock created successfully!';
        return redirect(route('update-stock.create'))->with('msg', $msg);
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        if ($stock->declared) {
            return redirect(route('update-stock.index'));
        }
        $distributors = Distributor::select('rsm_area')->distinct()->get();
        $products = Product::select('brandname')->distinct()->get();

        $stock_products = $stock->products()->withPivot('pkg_date', 'opening_stock', 'already_received', 'stock_in_transit',
            'delivery_done', 'in_delivery_van', 'physical_stock', 'created_at', 'updated_at')->get();

        $draftStock =  [];

        foreach ($stock_products as $stock_data) {

            $draft = [];

            $draft['distributorId'] = $stock->distributor->id;
            $draft['distributorName'] = $stock->distributor->name;
            $draft['openingStockDate'] = $stock->stock_opening_date;
            $draft['productId'] = $stock_data->pivot->product_id;
            $draft['productName'] = $stock_data->name;
            $draft['pkgDate'] = $stock_data->pivot->pkg_date;
            $draft['openingStock'] = $stock_data->pivot->opening_stock;
            $draft['physicalStock'] = $stock_data->pivot->physical_stock;
            $draft['alreadyReceived'] = $stock_data->pivot->already_received;
            $draft['stockInTransit'] = $stock_data->pivot->stock_in_transit;
            $draft['deliveryDone'] = $stock_data->pivot->delivery_done;
            $draft['inDeliveryVan'] = $stock_data->pivot->in_delivery_van;

            array_push($draftStock, $draft);
        }

        session()->put('draftStock', $draftStock);

        return view('stockupdate.edit', compact('stock', 'distributors', 'products'));
    }

    public function update(Request $request, $id)
    {
        $draftStock = session()->pull('draftStock');
        $stock = Stock::findOrFail($id);

        if (!$draftStock) {
            $msg = 'No data in drafts.';
            return redirect(route('update-stock.edit', $id))->with('msg', $msg);
        }

        foreach ($draftStock as $draft) {

            $validator = Validator::make($draft, [
                'distributorId' => 'required|numeric',
                'productId' => 'required|numeric',
                'openingStock' => 'required|numeric',
                'physicalStock' => 'required|numeric',
                'pkgDate' => 'required|date',
                'openingStockDate' => 'required|date',
                'alreadyReceived' => 'nullable|numeric',
                'stockInTransit' => 'nullable|numeric',
                'deliveryDone' => 'nullable|numeric',
                'inDeliveryVan' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                $msg = $validator->errors();
                return response()->json(['msg' => $msg]);
            }
        }

        foreach ($draftStock as $draft) {

            $stock = Stock::where('distributor_id', $draft['distributorId'])->first();

            if ($stock) {
                $stock->update([
                    'stock_opening_date' => $draft['openingStockDate'],
                    'updated_at' => now()
                ]);
            } else {
                $stock = Stock::create([
                    'distributor_id' => $draft['distributorId'],
                    'stock_opening_date' => $draft['openingStockDate'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $product_stock = ProductStock::where('stock_id', $stock->id)
                ->where('product_id', $draft['productId'])->where('pkg_date', $draft['pkgDate'])->first();

            if ($product_stock) {

                $product_stock->update([
                    'opening_stock' => $draft['openingStock'],
                    'already_received' => $draft['alreadyReceived'],
                    'stock_in_transit' => $draft['stockInTransit'],
                    'delivery_done' => $draft['deliveryDone'],
                    'in_delivery_van' => $draft['inDeliveryVan'],
                    'physical_stock' => $draft['physicalStock'],
                    'updated_at' => now()
                ]);

            } else {

                DB::table('product_stock')->insert([
                    'stock_id' => $stock->id,
                    'product_id' => $draft['productId'],
                    'pkg_date' => $draft['pkgDate'],
                    'opening_stock' => $draft['openingStock'],
                    'already_received' => $draft['alreadyReceived'],
                    'stock_in_transit' => $draft['stockInTransit'],
                    'delivery_done' => $draft['deliveryDone'],
                    'in_delivery_van' => $draft['inDeliveryVan'],
                    'physical_stock' => $draft['physicalStock'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }


        }

        $excel = $request->file('excel');
        $pdf = $request->file('pdf');

        if ($excel) {
            $excel->storeAs('files/excel', $excel->getClientOriginalName());
            $stock->update([
                'excel' => $excel->getClientOriginalName()
            ]);
        }
        if ($pdf) {
            $pdf->storeAs('files/pdf', $pdf->getClientOriginalName());
            $stock->update([
                'pdf' => $pdf->getClientOriginalName()
            ]);
        }

        $msg = 'Stock updated successfully!';
        return redirect(route('update-stock.edit', $id))->with('msg', $msg);
    }

    public function draft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distributorId' => 'required|numeric',
            'productId' => 'required|numeric',
            'openingStock' => 'required|numeric',
            'physicalStock' => 'required|numeric',
            'pkgDate' => 'required|date',
            'openingStockDate' => 'required|date',
            'alreadyReceived' => 'nullable|numeric',
            'stockInTransit' => 'nullable|numeric',
            'deliveryDone' => 'nullable|numeric',
            'inDeliveryVan' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $msg = $validator->errors();
            return response()->json(['validationError' => $msg]);
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

    public function declare(Request $request)
    {
        $stock = Stock::findOrFail($request->stock_id);
        $stock->update([
            'declared' => true,
            'declare_time' => now(),
        ]);

        return redirect(route('update-stock.show', $stock->id))->with('msg', 'Stock declared successfully');
    }


}
