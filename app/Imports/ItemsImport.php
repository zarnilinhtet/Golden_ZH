<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Item;
use App\Models\ItemVariation;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Log; // Import the Log facade
use Maatwebsite\Excel\Concerns\ToModel;
use DateInterval;
use DateTime;

class ItemsImport implements ToModel
{
    private $firstRowSkipped = false;
    private $rowCount = 0;
    private $warehouseId;
    private $hasErrors = false; // Add this property to track errors

    public function __construct($warehouseId)
    {
        $this->warehouseId = $warehouseId;
    }
    private function convertExcelDate($value)
    {
        if (is_numeric($value)) {
            $baseDate = new DateTime('1899-12-30');
            return $baseDate->add(new DateInterval("P{$value}D"))->format('Y-m-d');
        }

        return $value;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        // Skip the first row (header)
        if (!$this->firstRowSkipped) {
            $this->firstRowSkipped = true;
            return null;
        }

        try {
            $warehouse = Warehouse::where('name', $row[0])->first();


            $brand = Brand::where('id', $row[2])->first();

            if (!$warehouse || $warehouse->id != $this->warehouseId) {
                $this->hasErrors = true;
                Log::error('Row ' . $this->rowCount . ': Warehouse does not match the selected warehouse ID. Expected: ' . $this->warehouseId . ', Found: ' . ($warehouse ? $warehouse->id : 'None'));
                throw new \Exception('Warehouse does not match the selected warehouse ID.');
            }

            // $quantity = !empty($row[12]) && !empty($row[9])
            //     ? (float)$row[9] * (float)$row[12]
            //     : (float)$row[9];
            $level1qty = is_numeric($row[14]) ? (float) $row[14] : 0;
            $level2qty = is_numeric($row[15]) ? (float) $row[15] : 0;
            $level3qty = is_numeric($row[16]) ? (float) $row[16] : 0;

            // Retrieve conversion values (if they are numeric)
            $unit2conversion = is_numeric($row[18]) ? (float) $row[18] : 1;
            $unit3conversion = is_numeric($row[19]) ? (float) $row[19] : 1;

            // Calculate the total quantity by factoring in the levels and conversions
            $total = ($level1qty * $unit2conversion) + $level2qty + ($level3qty / $unit3conversion);
            // info('Total:', ['total' => $total]);

            // Convert total back to the original unit structure
            $newLevel1 = intdiv($total, ($unit2conversion * $unit3conversion));
            $remaining1 = $total % ($unit2conversion * $unit3conversion);
            $newLevel2 = intdiv($remaining1, $unit3conversion);
            $newLevel3 = $remaining1 % $unit3conversion;


            $item = Item::updateOrCreate(
                [
                    'item_name' => $row[1],
                    'warehouse_id' => $warehouse->id,
                ],
                [
                    'brand' => $row[2],
                    'descriptions' => $row[3],
                    'product_type' => $row[4],
                    'product_category' => $row[5],
                ]
            );

            $variation = ItemVariation::where('item_id', $item->id)
                ->where('descriptions', $row[7])
                ->where('product_code', $row[8])
                ->where('expired_date', $row[4])
                ->first();

            if ($variation) {
                $variation->quantity += $total;
                $variation->reorder_level_stock = $row[13];
                $variation->unit1 = $row[17];
                $variation->unit2 = $row[18];
                $variation->unit3 = $row[19];
                $variation->name1 = $row[20];
                $variation->name2 = $row[21];
                $variation->name3 = $row[22];
                $variation->retail1 = $row[23];
                $variation->wholesale1 = $row[24];
                $variation->price1 = $row[25];
                $variation->retail2 = $row[26];
                $variation->wholesale2 = $row[27];
                $variation->price2 = $row[28];
                $variation->retail3 = $row[29];
                $variation->wholesale3 = $row[30];
                $variation->price3 = $row[31];
                $variation->save();
            } else {
                $variation = new ItemVariation([
                    'item_id' => $item->id,
                    'lot_no' => $row[6] ?? null,
                    'expired_date' => $this->convertExcelDate($row[7]),
                    'muf_date' => $this->convertExcelDate($row[8]),
                    'arrival_date' => $this->convertExcelDate($row[9]),
                    'estd_test' => $row[10],
                    'country_of_origin' => $row[11],
                    'distributor' => $row[12],
                    'quantity' => $total,
                    'reorder_level_stock' => $row[13],
                    'unit1' => $row[17],
                    'unit2' => $unit2conversion,
                    'unit3' => $unit3conversion,
                    'name1' => $row[20],
                    'name2' => $row[21],
                    'name3' => $row[22],
                    'retail1' => $row[23],
                    'wholesale1' => $row[24],
                    'price1' => $row[25],
                    'retail2' => $row[26],
                    'wholesale2' => $row[27],
                    'price2' => $row[28],
                    'retail3' => $row[29],
                    'wholesale3' => $row[30],
                    'price3' => $row[31],
                ]);
                $variation->save();
            }
        } catch (\Exception $e) {
            Log::error('Error processing row ' . $this->rowCount . ': ' . $e->getMessage());
            $this->hasErrors = true;
            throw $e;
        }
    }

    public function generateRandomBarcode()
    {
        $barcodeBase = str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
        $checksum = $this->calculateEAN13Checksum($barcodeBase);
        return $barcodeBase . $checksum;
    }

    public function calculateEAN13Checksum($barcode)
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ($i % 2 === 0 ? 1 : 3) * intval($barcode[$i]);
        }
        $mod = $sum % 10;
        return $mod === 0 ? 0 : 10 - $mod;
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function hasErrors()
    {
        return $this->hasErrors;
    }
}
