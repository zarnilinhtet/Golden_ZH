<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Item;
use Illuminate\Support\Collection;

class ItemsImportTemplate implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    /**
     * @var Item $item
     */

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Warhouse',
            'Product Name',
            'Brand',
            'Product Description',
            'Product Type',
            'Product Category',
            'LOT NO.',
            'Expired Date',
            'MUF Date',
            'Warehouse In Date',
            'Estd Test',
            'Country Of Origin',
            'Distributor/Supplier',
            'Reorder Level Stock',
            'Quantity1',
            'Quantity2',
            'Quantity3',
            'Unit1',
            'Unit2',
            'Unit3',
            'Name1',
            'Name2',
            'Name3',
            'လက်လီစျေး(Unit1)',
            'လက်ကားစျေး(Unit1)',
            'ဝယ်စျေး(Unit1)',
            'လက်လီစျေး(Unit2)',
            'လက်ကားစျေး(Unit2)',
            'ဝယ်စျေး(Unit2)',
            'လက်လီစျေး(Unit3)',
            'လက်ကားစျေး(Unit3)',
            'ဝယ်စျေး(Unit3)',
        ];
    }
}
