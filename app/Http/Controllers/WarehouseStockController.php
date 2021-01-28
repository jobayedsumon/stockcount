<?php

namespace App\Http\Controllers;

use App\Distributor;
use App\Imports\WarehouseImport;
use App\Product;
use App\ProductWarehouse;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Maatwebsite\Excel\Facades\Excel;


class WarehouseStockController extends Controller
{

    public function index()
    {
        $total_inward = 0;
        $total_outward = 0;
        //
        $warehouses = Warehouse::with('products')->get();
        foreach ($warehouses as $warehouse) {
            foreach ($warehouse->products as $product) {
                $total_inward += $product->pivot->from_factory_count;
                $total_inward += $product->pivot->from_transfer_count;
                $total_outward += $product->pivot->to_db_count;
                $total_outward += $product->pivot->to_transfer_count;
            }
            $warehouse->total_inward = $total_inward;
            $warehouse->total_outward = $total_outward;
        }
        return view('warehouse-stock.index', compact('warehouses'));
    }


    public function create()
    {
        //
        $warehouses = Warehouse::all();
        $products = Product::select('brandname')->distinct()->get();
        return view('warehouse-stock.create', compact('warehouses', 'products'));
    }

    public function adjust_inward_form($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $warehouses = Warehouse::where('id', '!=', $warehouseId)->get();
        $products = Product::all();
        return view('warehouse-stock.adjust-inward', compact('warehouse', 'products', 'warehouses'));
    }

    public function adjust_outward_form($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $warehouses = Warehouse::where('id', '!=', $warehouseId)->get();
        $distributors = Distributor::all();
        $products = Product::all();
        return view('warehouse-stock.adjust-outward',
            compact('warehouse', 'products', 'distributors', 'warehouses'));
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
        $warehouse = Warehouse::findOrFail($id);
        $products = $warehouse->products;

        return view('warehouse-stock.view', compact('warehouse', 'products'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function adjust_inward_store(Request $request, $id)
    {
        if ($request->adjustment_type == 'from_factory') {

            $request->validate([
               'from_factory_product' => 'required',
               'from_factory_pkd' => 'required',
               'from_factory_count' => 'required',
               'from_factory_date' => 'required',
               'from_factory_per_carton' => 'required',
            ]);

            $from_factory_name = $request->from_factory_name ?? 'Main Factory';
            $from_factory_product = $request->from_factory_product;
            $from_factory_pkd = $request->from_factory_pkd;
            $from_factory_count = $request->from_factory_count;
            $from_factory_date = $request->from_factory_date;
            $from_factory_per_carton = $request->from_factory_per_carton;


            $warehouse_stock = ProductWarehouse::firstOrCreate([
                    'warehouse_id' => $id,
                    'product_id' => $request->from_factory_product,
                    'pkd' => $request->from_factory_pkd,
                ]);

            $warehouse_stock->update([
               'from_factory_name' => $from_factory_name,
               'from_factory_count' => $from_factory_count,
               'from_factory_date' => $from_factory_date,
               'from_factory_per_carton' => $from_factory_per_carton,
            ]);

            if ($warehouse_stock) {
                return redirect(route('adjust-inward', $id))
                    ->with(['message' => 'Inward stock adjusted successfully from factory!', 'alert-type' => 'success']);
            } else {
                return redirect(route('adjust-inward', $id))
                    ->with(['message' => 'Inward stock adjusting failed from factory!', 'alert-type' => 'error']);
            }
        }


        if ($request->adjustment_type == 'from_transfer') {

            $request->validate([
                'from_transfer_name' => 'required',
                'from_transfer_product' => 'required',
                'from_transfer_pkd' => 'required',
                'from_transfer_count' => 'required',
                'from_transfer_date' => 'required',
                'from_transfer_per_carton' => 'required',
            ]);

            $from_transfer_name = $request->from_transfer_name;
            $from_transfer_product = $request->from_transfer_product;
            $from_transfer_pkd = $request->from_transfer_pkd;
            $from_transfer_count = $request->from_transfer_count;
            $from_transfer_date = $request->from_transfer_date;
            $from_transfer_per_carton = $request->from_transfer_per_carton;


            $warehouse_stock = ProductWarehouse::firstOrCreate([
                'warehouse_id' => $id,
                'product_id' => $request->from_transfer_product,
                'pkd' => $request->from_transfer_pkd,
            ]);

            $warehouse_stock->update([
                'from_transfer_name' => $from_transfer_name,
                'from_transfer_count' => $from_transfer_count,
                'from_transfer_date' => $from_transfer_date,
                'from_transfer_per_carton' => $from_transfer_per_carton,
            ]);

            if ($warehouse_stock) {
                return redirect(route('adjust-inward', $id))
                    ->with(['message' => 'Inward stock adjusted successfully from warehouse!', 'alert-type' => 'success']);
            } else {
                return redirect(route('adjust-inward', $id))
                    ->with(['message' => 'Inward stock adjusting failed from warehouse!', 'alert-type' => 'error']);
            }
        }


    }

    public function adjust_outward_store(Request $request, $id)
    {
        if ($request->adjustment_type == 'to_db') {

            $request->validate([
                'to_db_product' => 'required',
                'to_db_pkd' => 'required',
                'to_db_count' => 'required',
                'to_db_date' => 'required',
                'to_db_per_carton' => 'required',
            ]);

            $to_db_name = $request->to_db_name;
            $to_db_product = $request->to_db_product;
            $to_db_pkd = $request->to_db_pkd;
            $to_db_count = $request->to_db_count;
            $to_db_date = $request->to_db_date;
            $to_db_per_carton = $request->to_db_per_carton;


            $warehouse_stock = ProductWarehouse::firstOrCreate([
                'warehouse_id' => $id,
                'product_id' => $request->to_db_product,
                'pkd' => $request->to_db_pkd,
            ]);

            $warehouse_stock->update([
                'to_db_name' => $to_db_name,
                'to_db_count' => $to_db_count,
                'to_db_date' => $to_db_date,
                'to_db_per_carton' => $to_db_per_carton,
            ]);

            if ($warehouse_stock) {
                return redirect(route('adjust-outward', $id))
                    ->with(['message' => 'Outward stock adjusted successfully to DB!', 'alert-type' => 'success']);
            } else {
                return redirect(route('adjust-outward', $id))
                    ->with(['message' => 'Outward stock adjusting failed to DB!', 'alert-type' => 'error']);
            }
        }


        if ($request->adjustment_type == 'to_transfer') {

            $request->validate([
                'to_transfer_name' => 'required',
                'to_transfer_product' => 'required',
                'to_transfer_pkd' => 'required',
                'to_transfer_count' => 'required',
                'to_transfer_date' => 'required',
                'to_transfer_per_carton' => 'required',
            ]);

            $to_transfer_name = $request->to_transfer_name;
            $to_transfer_product = $request->to_transfer_product;
            $to_transfer_pkd = $request->to_transfer_pkd;
            $to_transfer_count = $request->to_transfer_count;
            $to_transfer_date = $request->to_transfer_date;
            $to_transfer_per_carton = $request->to_transfer_per_carton;


            $warehouse_stock = ProductWarehouse::firstOrCreate([
                'warehouse_id' => $id,
                'product_id' => $request->to_transfer_product,
                'pkd' => $request->to_transfer_pkd,
            ]);

            $warehouse_stock->update([
                'to_transfer_name' => $to_transfer_name,
                'to_transfer_count' => $to_transfer_count,
                'to_transfer_date' => $to_transfer_date,
                'to_transfer_per_carton' => $to_transfer_per_carton,
            ]);

            if ($warehouse_stock) {
                return redirect(route('adjust-outward', $id))
                    ->with(['message' => 'Outward stock adjusted successfully to warehouse!', 'alert-type' => 'success']);
            } else {
                return redirect(route('adjust-outward', $id))
                    ->with(['message' => 'Outward stock adjusting failed to warehouse!', 'alert-type' => 'error']);
            }
        }


    }

    public function import(Request $request, $id)
    {

        if ($request->hasFile('import_file')) {

            $request->validate([
                'import_file' => 'required|mimes:csv,txt'
            ]);


                Excel::import(new WarehouseImport($id), $request->file('import_file')->getRealPath());
            return back()->with(['message' => 'Data imported successfully!', 'alert-type' => 'success']);
        } else {
            return back()->with(['message' => 'No file chosen!', 'alert-type' => 'error']);
        }



    }

}
