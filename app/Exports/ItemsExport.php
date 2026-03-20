<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use App\Models\Item;
use Illuminate\Support\Collection;

class ItemsExport implements FromCollection, FromQuery, WithMapping, WithHeadings
{
    public function collection()
    {
        // This method is not used when FromQuery is implemented.
        // If you need to use collection(), implement only FromCollection instead.
        return new Collection();
    }

    public function query()
    {
        $user = auth()->user();

        if ($user->is_admin == '1' || $user->type == 'Admin') {
            return Item::whereNull('status');
        } else {
            $userLevel = $user->level;

            return Item::whereNull('status')
                ->whereHas('warehouse', function ($query) use ($userLevel) {
                    $query->where('id', $userLevel);
                });
        }
    }


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

    public function map($item): array
    {
        $warehouse = $item->warehouse->name ?? null;
        $mappedData = [];

        // Loop through item variations
        foreach ($item->variations as $variation) {
            // Ensure units are valid non-zero values
            $unit2 = (!empty($variation->unit2) && floatval($variation->unit2) > 0) ? floatval($variation->unit2) : 1;
            $unit3 = (!empty($variation->unit3) && floatval($variation->unit3) > 0) ? floatval($variation->unit3) : 1;

            // Get variation quantity (ensure it's a float)
            $quantity = floatval($variation->quantity);

            // Calculate level 1 quantity (floor the division result to get an integer)
            $level1qty = floor($quantity / $unit2);

            // Calculate remaining quantity for level 2
            $first_lvl2 = fmod($quantity, $unit2);
            $level2qty = floor($first_lvl2);

            // Calculate fractional part for level 3
            $level3_fractional = ($first_lvl2 - $level2qty) * $unit3;
            $level3qty = round($level3_fractional, 2); // Round to 2 decimal places for precision
            $mappedData[] = [
                'Warehouse' => $warehouse,
                'Product Name' => $item->item_name,
                'Brand' => $item->BrandName ? $item->BrandName->name : '',
                'Product Description' => $item->descriptions,
                'Product Type' => $item->product_type,
                'Product Category' => $item->product_category,
                'LOT NO.' => $variation->lot_no,
                'Expired Date' => $variation->expired_date,
                'MUF Date' => $variation->muf_date,
                'Warehouse In Date' => $variation->arrival_date,
                'Estd Test' => $variation->estd_test,
                'Country Of Origin' => $variation->country_of_origin,
                'Distributor/Supplier' => $variation->distributor,
                'Reorder Level Stock' => $variation->reorder_level_stock,
                'Quantity1' => $level1qty,
                'Quantity2' => $level2qty,
                'Quantity3' => $level3qty,
                'Unit1' => $variation->unit1,
                'Unit2' => $variation->unit2,
                'Unit3' => $variation->unit3,
                'Name1' => $variation->name1,
                'Name2' => $variation->name2,
                'Name3' => $variation->name3,
                'လက်လီစျေး(Unit1)' => $variation->retail1,
                'လက်ကားစျေး(Unit1)' => $variation->wholesale1,
                'ဝယ်စျေး(Unit1)' => $variation->price1,
                'လက်လီစျေး(Unit2)' => $variation->retail2,
                'လက်ကားစျေး(Unit2)' => $variation->wholesale2,
                'ဝယ်စျေး(Unit2)' => $variation->price2,
                'လက်လီစျေး(Unit3)' => $variation->retail3,
                'လက်ကားစျေး(Unit3)' => $variation->wholesale3,
                'ဝယ်စျေး(Unit3)' => $variation->price3,
            ];
        }

        return $mappedData;
    }
}
