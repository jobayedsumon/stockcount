<?php

namespace App\Imports;

use App\ProductWarehouse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarehouseImport implements ToModel, WithStartRow, WithValidation
{
    private $warehouse_id;

    public function __construct($id)
    {
        $this->warehouse_id = $id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (count($row) == 15) {

            $product_warehouse = ProductWarehouse::firstOrCreate([
                'warehouse_id' => $this->warehouse_id,
                'product_id' => getProductId($row[1]),
                'pkd' => $row[2],
            ]);

            $product_warehouse->update([


                'from_factory_name' => $row[3],
                'from_factory_count' => $row[4],
                'from_factory_date' => $row[5],
                'from_transfer_name' => getWarehouseId($row[6]),
                'from_transfer_count' => $row[7],
                'from_transfer_date' => $row[8],
                'to_db_name' => getDistributorId($row[9]),
                'to_db_count' => $row[10],
                'to_db_date' => $row[11],
                'to_transfer_name' => getWarehouseId($row[12]),
                'to_transfer_count' => $row[13],
                'to_transfer_date' => $row[14],

            ]);

            return $product_warehouse;
        }

    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
       return [
            '0' => 'required',
            '1' => 'required',
            '2' => 'required',
            '3'=> 'required',
            '4'=> 'required',
            '5'=> 'required',
            '6' => 'required',
            '7' => 'required',
            '8' => 'required',
            '9' => 'required',
            '10' => 'required',
            '11' => 'required',
            '12' => 'required',
            '13' => 'required',
            '14' => 'required',
        ];
    }
}
