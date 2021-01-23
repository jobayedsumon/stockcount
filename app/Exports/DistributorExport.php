<?php

namespace App\Exports;

use App\Distributor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;

class DistributorExport implements FromView, WithProperties
{
    private $stock;
    private $products;

    public function __construct($stock, $products)
    {
        $this->stock = $stock;
        $this->products = $products;
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Patrick Brouwers',
            'lastModifiedBy' => 'Patrick Brouwers',
            'title'          => 'Invoices Export',
            'description'    => 'Latest Invoices',
            'subject'        => 'Invoices',
            'keywords'       => 'invoices,export,spreadsheet',
            'category'       => 'Invoices',
            'manager'        => 'Patrick Brouwers',
            'company'        => 'Maatwebsite',
        ];
    }

    public function view(): View
    {
        return \view('report.individual', [
            'stock' => $this->stock,
            'products' => $this->products,
        ]);
    }
}
