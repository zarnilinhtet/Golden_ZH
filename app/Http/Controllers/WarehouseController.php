<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariation;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\TransferHistory;
use App\Models\Transfer_Historyy;
use Exception;
use Illuminate\Support\Facades\Cache;

class WarehouseController extends Controller
{
    public function index()
    {
        if (auth()->user()->is_admin == '1') {
            $warehouses = Warehouse::all();
        } else {
            $warehouses = Warehouse::where('id', auth()->user()->level)->get();
        }
        return view('warehouse.warehouse', compact('warehouses'));
    }
    public function warehouse_register(Request $request, Warehouse $warehouse)
    {
        $warehouse->phone_number = $request->phone_number ?? "";
        $warehouse->name = $request->name ?? "";
        $warehouse->address = $request->address ?? "";
        $warehouse->save();
        return back()->with('success', 'Register Location Successful');
    }
    public function warehouse_delete($id)
    {
        Warehouse::destroy($id);
        return back()->with('delete', 'Location Deleted Successful');
    }
    public function warehouse_edit($id)
    {
        $warehouse = Warehouse::find($id);
        return view('warehouse.warehouse_edit', compact('warehouse'));
    }
    public function warehouse_Update($id, Request $request)
    {
        $warehouse = Warehouse::find($id);
        $warehouse->name = $request->name;
        $warehouse->address = $request->address ?? "";
        $warehouse->phone_number = $request->phone_number ?? "";
        $warehouse->save();
        return redirect(url('warehouse'))->with('success', 'Location Updated Successful');
    }
    public function transfer_item()
    {
        $transfer = TransferHistory::select('transfer_id')
            ->groupBy('transfer_id')
            ->get();

        $transfer_id = count($transfer) + 1;

        $warehouses = WareHouse::all();
        return view('warehouse.transfer_item', compact('warehouses', 'transfer_id'));
    }

    public function store_transfer_item(Request $request)
    {
        $from = Warehouse::find($request->from_location);
        $to = Warehouse::find($request->to_location);
        $lastItem = Item::latest()->first();
        // dd($request->to_location);
        $count = count($request->part_number);
        info($count);
        for ($i = 0; $i < $count; $i++) {

            // Find 'to' and 'from' items based on warehouse location
            $item_to = Item::where('item_name', $request->result_item_name[$i])->where('warehouse_id', $request->to_location)->first();

            // dd($item_to);
            $item_from = Item::where('item_name', $request->result_item_name[$i])->where('warehouse_id', $request->from_location)->first();

            $item_from_variation = ItemVariation::where('item_id', $item_from->id)
                ->where('id', $request->result_id[$i])
                ->first();

            if ($item_to) {
                info('Item To' . $item_to);
                $item_to_variation = ItemVariation::where('item_id', $item_to->id)
                    ->where('descriptions', $request->result_descriptions[$i])->where('product_code', $request->result_product_code[$i])->where('expired_date', $request->result_expired_date[$i])->first();
                // info('Item To Variation' . $item_to_variation);

                if ($item_to_variation) {

                    if ($request->unit[$i] == $item_from_variation->name1) {
                        $product_qty = (float) $request->product_qty[$i];
                        $unit2 = (float) (!empty($item_from_variation->unit2) ? $item_from_variation->unit2 : 1);
                        $item_to_variation->quantity += $product_qty * $unit2;
                        $item_from_variation->quantity -= $product_qty * $unit2;
                        // info($unit2);
                    }
                    if ($request->unit[$i] == $item_from_variation->name2) {
                        $product_qty = (float) $request->product_qty[$i];
                        $item_to_variation->quantity += $product_qty;
                        $item_from_variation->quantity -= $product_qty;
                    }

                    if ($request->unit[$i] == $item_from_variation->name3) {
                        $product_qty = (float) $request->product_qty[$i];
                        $unit2 = (float) ($item_from_variation->unit2 ?? 1);
                        $item_to_variation->quantity += $product_qty / $unit2;
                        $item_from_variation->quantity -= $product_qty / $unit2;
                    }
                    $item_to_variation->save();
                    $item_from_variation->save();
                } else {
                    $variation = new ItemVariation();
                    $variation->item_id = $item_to->id;
                    $variation->expired_date = $item_from_variation->expired_date;
                    $variation->descriptions = $item_from_variation->descriptions;
                    $variation->product_code = $item_from_variation->product_code;
                    $variation->reorder_level_stock = $item_from_variation->reorder_level_stock;
                    $variation->variations_barcode = $item_from_variation->variations_barcode;
                    $variation->name1 = $item_from_variation->name1;
                    $variation->name2 = $item_from_variation->name2;
                    $variation->name3 = $item_from_variation->name3;
                    $variation->unit1 = $item_from_variation->unit1;
                    $variation->unit2 = $item_from_variation->unit2;
                    $variation->unit3 = $item_from_variation->unit3;
                    $variation->price1 = $item_from_variation->price1;
                    $variation->price2 = $item_from_variation->price2;
                    $variation->price3 = $item_from_variation->price3;
                    $variation->retail1 = $item_from_variation->retail1;
                    $variation->retail2 = $item_from_variation->retail2;
                    $variation->retail3 = $item_from_variation->retail3;
                    $variation->wholesale1 = $item_from_variation->wholesale1;
                    $variation->wholesale2 = $item_from_variation->wholesale2;
                    $variation->wholesale3 = $item_from_variation->wholesale3;
                    if ($request->unit[$i] == $variation->name1) {
                        $product_qty = (float) $request->product_qty[$i];
                        $unit2 = (float) (!empty($variation->unit2) ? $variation->unit2 : 1);
                        $variation->quantity = $product_qty * $unit2;
                        $item_from_variation->quantity -= $product_qty * (float) (!empty($item_from_variation->unit2) ? $item_from_variation->unit2 : 1);
                    }

                    if ($request->unit[$i] == $variation->name2) {
                        $product_qty = (float) $request->product_qty[$i];
                        $variation->quantity = $product_qty;
                        $item_from_variation->quantity -= $product_qty;
                    }

                    if ($request->unit[$i] == $variation->name3) {
                        $product_qty = (float) $request->product_qty[$i];
                        $unit2 = (float) ($variation->unit2 ?? 1);

                        $variation->quantity = $product_qty / $unit2;
                        $item_from_variation->quantity -= $product_qty / (float) ($item_from_variation->unit2 ?? 1);
                    }


                    $variation->save();
                    $item_from_variation->save();
                }



                $transfer_history = new TransferHistory();
                $transfer_history->transfer_id = $request->transfer_id;
                $transfer_history->from_location = $request->from_location;
                $transfer_history->to_location = $request->to_location;
                $transfer_history->item_name = $request->part_number[$i];
                $transfer_history->product_name = $request->result_item_name[$i];
                $transfer_history->item_id = $request->item_id[$i];
                $transfer_history->variation_id = $request->result_id[$i];
                $transfer_history->quantity = $request->product_qty[$i] . ' ' . $request->unit[$i];
                $transfer_history->date = $request->date;
                $transfer_history->save();
            } else {
                // Insert new item and variation if 'item_to' does not exist
                $item = new Item();
                $item->item_name = $request->result_item_name[$i];
                $item->descriptions = $item_from->descriptions;
                $item->category = $item_from->category;
                $item->market = $item_from->market;
                $item->status = $item_from->status;
                $item->parent_id = $item_from->id;
                $item->warehouse_id = $request->to_location;
                $item->save();

                // Insert a new variation for the 'to' location
                if ($request->result_id[$i] == $item_from_variation->id) {
                    $variation = new ItemVariation();
                    $variation->item_id = $item->id;
                    $variation->expired_date = $item_from_variation->expired_date;
                    $variation->descriptions = $item_from_variation->descriptions;
                    $variation->product_code = $item_from_variation->product_code;
                    $variation->reorder_level_stock = $item_from_variation->reorder_level_stock;
                    $variation->variations_barcode = $item_from_variation->variations_barcode;
                    $variation->name1 = $item_from_variation->name1;
                    $variation->name2 = $item_from_variation->name2;
                    $variation->name3 = $item_from_variation->name3;
                    $variation->unit1 = $item_from_variation->unit1;
                    $variation->unit2 = $item_from_variation->unit2;
                    $variation->unit3 = $item_from_variation->unit3;
                    $variation->price1 = $item_from_variation->price1;
                    $variation->price2 = $item_from_variation->price2;
                    $variation->price3 = $item_from_variation->price3;
                    $variation->retail1 = $item_from_variation->retail1;
                    $variation->retail2 = $item_from_variation->retail2;
                    $variation->retail3 = $item_from_variation->retail3;
                    $variation->wholesale1 = $item_from_variation->wholesale1;
                    $variation->wholesale2 = $item_from_variation->wholesale2;
                    $variation->wholesale3 = $item_from_variation->wholesale3;
                    if ($request->unit[$i] == $variation->name1) {
                        $product_qty = (float) $request->product_qty[$i];
                        $unit2 = (float) (!empty($variation->unit2) ? $variation->unit2 : 1);
                        $variation->quantity = $product_qty * $unit2;
                        $item_from_variation->quantity -= $product_qty * $unit2;
                    }
                    if ($request->unit[$i] == $variation->name2) {
                        $product_qty = (float) $request->product_qty[$i];

                        $variation->quantity = $product_qty;
                        $item_from_variation->quantity -= $product_qty;
                    }
                    if ($request->unit[$i] == $variation->name3) {
                        $product_qty = (float) $request->product_qty[$i];
                        $unit2 = (float) ($variation->unit2 ?? 1);
                        $variation->quantity = $product_qty / $unit2;
                        $item_from_variation->quantity -= $product_qty / $unit2;
                    }


                    $variation->save();
                    $item_from_variation->save();
                }


                // Store the transfer history
                $transfer_history = new TransferHistory();
                $transfer_history->transfer_id = $request->transfer_id;
                $transfer_history->from_location = $request->from_location;
                $transfer_history->to_location = $request->to_location;
                $transfer_history->item_name = $request->part_number[$i];
                $transfer_history->product_name = $request->result_item_name[$i];
                $transfer_history->item_id = $request->item_id[$i];
                $transfer_history->variation_id = $variation->id;
                $transfer_history->quantity = $request->product_qty[$i] . ' ' . $request->unit[$i];
                $transfer_history->date = $request->date;
                $transfer_history->save();
            }
        }

        return redirect('show_transfer_history')->with('success', 'Successfully Transfer Item from ' . $from->name . ' to ' . $to->name . ' ');
    }
    // public function autocompletePartCode(Request $request)
    // {
    //     $query = $request->get('query');
    //     $location = $request->get('location');


    //     $items = Item::where('item_name', 'like', '%' . $query . '%')->where('warehouse_id', $location)->where('market', 'Stock')->withoutTrashed()
    //         ->pluck('item_name');

    //     return response()->json($items);
    // }
    public function autocompletePartCode(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');

        $cacheKey = "autocomplete_{$query}_{$location}";
        $items = Cache::remember($cacheKey, 60, function () use ($query, $location) {
            $itemIds = Item::where('item_name', 'like', '%' . $query . '%')
                ->where('warehouse_id', $location)
                ->where('market', 'Stock')
                ->pluck('id');

            return ItemVariation::whereIn('item_id', $itemIds)
                ->get(['item_id', 'descriptions', 'product_code', 'id', 'expired_date'])
                ->map(function ($variation) {
                    $item = Item::find($variation->item_id);
                    return [
                        'item_name' => $item ? $item->item_name : 'Unknown Item',
                        'description' => $variation->descriptions,
                        'product_code' => $variation->product_code,
                        'id' => $variation->id,
                        'item_id' => $variation->item_id,
                        'expired_date' => $variation->expired_date,
                    ];
                });
        });

        return response()->json($items);
    }
    // public function getPartData(Request $request)
    // {
    //     $result = Item::where('item_name', $request->item_name)->where('warehouse_id', $request->location)
    //         ->first();

    //     if (!$result) {
    //         return response()->json(['error' => 'Product not found'], 404);
    //     }
    //     return response()->json($result);
    // }

    public function getPartData(Request $request)
    {
        try {
            $itemName = $request->result_item_name;
            $description = $request->result_descriptions;
            // $productCode = $request->result_product_code;
            $id = $request->item_id;
            $expiredDate = $request->result_expired_date;
            $location = $request->location;
            $cacheKey = "part_data_{$itemName}_{$description}_{$id}_{$expiredDate}_{$location}";
            $result = Cache::remember($cacheKey, 60, function () use ($itemName, $description, $id, $location, $expiredDate) {
                $item = Item::where('item_name', $itemName)
                    ->where('warehouse_id', $location)
                    ->first();

                if ($item) {
                    $variation = ItemVariation::where('item_id', $item->id)
                        ->where('descriptions', $description)
                        // ->where('product_code', $productCode)
                        ->where('id', $id)
                        ->where('expired_date', $expiredDate)
                        ->first();

                    if ($variation) {
                        return [
                            'warehouse_id' => $item->warehouse_id,
                            'descriptions' => $variation->descriptions,
                            'expired_date' => $variation->expired_date,
                            'quantity' => $variation->quantity,
                            'reorder_level_stock' => $variation->reorder_level_stock,
                            'unit1' => $variation->unit1,
                            'unit2' => $variation->unit2,
                            'unit3' => $variation->unit3,
                            'name1' => $variation->name1,
                            'name2' => $variation->name2,
                            'name3' => $variation->name3,
                            'price1' => $variation->price1,
                            'price2' => $variation->price2,
                            'price3' => $variation->price3,
                            'retail1' => $variation->retail1,
                            'retail2' => $variation->retail2,
                            'retail3' => $variation->retail3,
                            'wholesale1' => $variation->wholesale1,
                            'wholesale2' => $variation->wholesale2,
                            'wholesale3' => $variation->wholesale3,
                        ];
                    }
                }

                return null;
            });

            if (!$result) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function show_history()
    {
        // $location_from_name = "";
        // $location_to_name = "";
        // $histories = TransferHistory::all();
        // foreach ($histories as $history)
        //     $from = Warehouse::find($history->from_location);


        // $to = Warehouse::find($history->to_location);
        $location_names = [];
        $histories = TransferHistory::latest()->get();

        foreach ($histories as $history) {
            $from = Warehouse::find($history->from_location);
            $to = Warehouse::find($history->to_location);

            // Check if $from and $to are not null before accessing their properties
            if ($from && $to) {
                // Store location names along with history ID in the associative array
                $location_names[$history->id] = [
                    'from' => $from->name,
                    'to' => $to->name
                ];
            }
        }



        return view('warehouse.transfer_history', compact('histories', 'location_names'));
    }
    public function transfer_delete($id)
    {

        TransferHistory::destroy($id);
        return back()->with('delete', 'Transfer History Deleted Successful');
    }
}
