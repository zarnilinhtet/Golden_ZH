<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\InOut;
use App\Models\Invoice;
use App\Models\ItemKit;
use App\Models\PO_sells;
use App\Models\Warehouse;
use App\Exports\ItemsExport;

use App\Imports\ItemsImport;
use App\Models\PricePercent;
use Illuminate\Http\Request;
use App\Models\ItemVariation;
use App\Exports\ItemsImportTemplate;
use Maatwebsite\Excel\Facades\Excel;



class ItemController extends Controller
{

    public function items_expire()
    {

        $oneMonthFromNow = Carbon::now()->addMonths(6);


        if (auth()->user()->is_admin == '1') {
            $variations = ItemVariation::where('expired_date', '<=', $oneMonthFromNow)->get();
            $warehouses = Warehouse::all();
        } else {
            $userWarehouseId = auth()->user()->level;
            $variations = ItemVariation::whereHas('item', function ($query) use ($userWarehouseId) {
                $query->where('warehouse_id', $userWarehouseId);
            })->where('expired_date', '<=', $oneMonthFromNow)->get();

            $warehouses = Warehouse::where('id', $userWarehouseId)->get();
        }

        return view('item.expire_date', compact('variations', 'warehouses'));
    }
    public function  reorder_item()
    {
        if (auth()->user()->is_admin == '1') {
            // $variations = ItemVariation::where('reorder_level_stock', '', 'quantity')->get();
            $variations = ItemVariation::whereHas('item', function ($query) {
                $query->where('market', 'stock');
            })->get();


            $warehouses = Warehouse::all();
        } else {
            $userWarehouseId = auth()->user()->level;
            $variations = ItemVariation::whereHas('item', function ($query) use ($userWarehouseId) {
                $query->where('warehouse_id', $userWarehouseId);
            })->get();

            $warehouses = Warehouse::where('id', $userWarehouseId)->get();
        }

        return view('item.reorder_item', compact('variations', 'warehouses'));
    }
    //Edit By Farrooq
    public function index(Request $request, $branch = null)
    {
        $warehouse_name = [];
        $warehouses = Warehouse::all();
        foreach ($warehouses as $key => $ware) {
            $warehouse_name[$key] = $ware->name;
        }

        $query = Item::query();

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%")
                    ->orWhere('market', 'LIKE', "%{$search}%")
                    ->orWhere('name1', 'LIKE', "%{$search}%")
                    ->orWhere('expired_date', 'LIKE', "%{$search}%")
                    ->orWhere('retail1', 'LIKE', "%{$search}%");
            });
        }
        if (auth()->user()->is_admin == '1') {
            $paginatedItems = $query->where('status', NULL)->latest()->get();
        } else {
            $paginatedItems = $query->where('status', NULL)
                ->where('warehouse_id', auth()->user()->level)
                ->latest()->get();
            $branch = auth()->user()->level;
        }

        if ($branch) {
            $itemsGroupedByName = $paginatedItems->where('warehouse_id', $branch);
        } else {
            $itemsGroupedByName = $paginatedItems;
        }

        // $total_retail = $paginatedItems->sum('retail1');

        $item_warehouses = Item::select('warehouses.id', 'warehouses.name')
            ->leftJoin('warehouses', 'items.warehouse_id', '=', 'warehouses.id')
            ->distinct()
            ->get();
        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Products';
        // 'itemsGroupedByName', 'total_retail'
        return view('item.item', compact('warehouse_name', 'warehouses', 'item_warehouses', 'paginatedItems', 'itemsGroupedByName', 'branchNames', 'currentBranchName', 'branch_drop'));
    }
    // End Edit By Farrooq



    public function register()
    {
        $units = Unit::all();
        $branchs = Warehouse::all();
        $brands = Brand::all();

        return view('item.itemRegister', compact('branchs', 'units', 'brands'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $item = new Item();
        $count1 = count($request->lot_no);
        $item->item_name = $request->item_name;
        $item->brand = $request->brand;
        $item->descriptions = $request->descriptions;
        $item->warehouse_id = $request->warehouse_id;
        $item->product_type = $request->product_type;
        $item->product_category = $request->product_category;
        $item->parent_id = $request->parent_id;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = time() . $file->getClientOriginalName();
            $file->move(public_path('upload/item'), $filename);
            $item->image = $filename;
        }

        $item->save();

        // if ($request->status == 'item_kit') {
        //     try {
        //         $count = count($request->item_kit_name);

        //         $item->item_unit = $request->service_charge;
        //         $item->save();

        //         for ($i = 0; $i < $count; $i++) {
        //             $result = new ItemKit();
        //             $result->product_id = $item->id;
        //             $result->item_id = $request->item_kit_id[$i];
        //             $result->variation_id = $request->result_variation_id[$i];
        //             $result->product_name = $request->result_item_name[$i];
        //             $result->item_kit_name = $request->item_name;
        //             $result->item_name = $request->item_kit_name[$i];
        //             $result->qty = $request->item_kit_quantity[$i] ?? 0;
        //             $result->item_unit = $request->item_kit_unit[$i];
        //             $result->retail_price = $request->item_kit_retail_price[$i];
        //             $result->warehouse = $request->warehouse[$i];
        //             $result->save();
        //         }
        //     } catch (\Exception $e) {
        //         return redirect(url('items_register'))->with('error', "Something went wrong. Please try again.");
        //     }
        // }


        // $item->fill($request->except('quantity'));

        for ($i = 0; $i < $count1; $i++) {
            $variations = new ItemVariation();
            $variations->item_id = $item->id;
            $variations->quantity = $request->quantity[$i];
            $variations->expired_date = $request->expired_date[$i];
            $variations->arrival_date = $request->warehousein_date[$i];
            $variations->muf_date = $request->muf_date[$i];
            $variations->lot_no = $request->lot_no[$i];
            $variations->estd_test = $request->estd_test[$i];
            $variations->country_of_origin = $request->country_of_origin[$i];
            $variations->distributor = $request->distributor[$i];
            $variations->reorder_level_stock = $request->reorder_level_stock[$i] ?? 0;
            $variations->unit1 = $request->unit1[$i] ?? '';
            $variations->name1 = $request->name1[$i] ?? '';
            $variations->price1 = $request->price1[$i] ?? 0;
            $variations->wholesale1 = $request->wholesale1[$i] ?? 0;
            $variations->retail1 = $request->retail1[$i] ?? 0;
            $variations->unit2 = $request->unit2[$i] ?? '';
            $variations->name2 = $request->name2[$i] ?? '';
            $variations->price2 = $request->price2[$i] ?? 0;
            $variations->wholesale2 = $request->wholesale2[$i] ?? 0;
            $variations->retail2 = $request->retail2[$i] ?? 0;
            $variations->unit3 = $request->unit3[$i] ?? '';
            $variations->name3 = $request->name3[$i] ?? '';
            $variations->price3 = $request->price3[$i] ?? 0;
            $variations->wholesale3 = $request->wholesale3[$i] ?? 0;
            $variations->retail3 = $request->retail3[$i] ?? 0;



            $variations->quantity = $request->quantity[$i] * ($request->unit2[$i] ?? 1);


            $variations->save();
        }
        if ($item->status == 'item_kit') {
            return redirect(url('items_register'))->with('success', 'Item Kit Registered Successfully');
        }
        // Redirect with success message
        return redirect(url('items_register'))->with('success', 'Product Registered Successfully');
    }

    public function details(Request $request, $id)
    {
        $all_items = Item::where('id', $id)->get();
        $item_kits = ItemKit::where('product_id', $request->item_id)->where('item_id', $id)->get();

        $variations = [];
        if ($all_items->isNotEmpty()) {
            $variations = ItemVariation::whereIn('item_id', $all_items->pluck('id'))->get();
        }

        $item_status = Item::where('id', $request->item_id)->first();
        $branchs = Warehouse::all();
        return view('item.item_details', compact('all_items', 'branchs', 'item_kits', 'item_status', 'variations'));
    }


    public function edit($id, Request $request)
    {


        $items = Item::where('id', $request->item_id)->whereNull('status')->withoutTrashed()->where('id', $id)->first();
        // dd($item);
        $units = Unit::all();
        $branchs = Warehouse::all();
        $brands = Brand::all();
        $items_kit = ItemKit::where('product_id', $id)->withoutTrashed()->get();
        $variations = ItemVariation::where('item_id', $id)->withoutTrashed()->get();

        return view('item.item_edit', compact('items', 'branchs', 'units', 'items_kit', 'variations', 'brands'));
    }
    public function item_kit_edit($id, Request $request)
    {


        $item = Item::where('id', $request->item_id)->where('status', 'item_kit')->withoutTrashed()->get();

        $units = Unit::all();
        $branchs = Warehouse::all();
        $items_kits = ItemKit::where('product_id', $request->item_id)->withoutTrashed()->get();
        $variations = ItemVariation::where('item_id', $id)->withoutTrashed()->get();
        // dd($variations);
        return view('item.item_kit_edit', compact('item', 'branchs', 'units', 'items_kits', 'variations'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->variation_id);
        $item = Item::findOrFail($id);

        $item->item_name = $request->item_name;

        $item->brand = $request->brand;
        $item->descriptions = $request->descriptions;
        $item->warehouse_id = $request->warehouse_id;
        $item->product_type = $request->product_type;
        $item->product_category = $request->product_category;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = time() . $file->getClientOriginalName();
            $file->move(public_path('upload/item'), $filename);
            $item->image = $filename;
        }

        $item->update();

        // if ($item->status == 'item_kit') {
        //     $delete_items = ItemKit::where('product_id', $item->id)->get();
        //     // dd($delete_items);
        //     foreach ($delete_items as $deleteitem) {
        //         $deleteitem->delete();
        //     }
        //     $item->item_unit = $request->service_charge;
        //     $item->update();
        //     $count = count($request->item_kit_unit);
        //     for ($i = 0; $i < $count; $i++) {
        //         $result = new ItemKit();
        //         $result->product_id = $item->id;
        //         $result->item_id = $request->item_kit_id[$i];
        //         $result->variation_id = $request->result_variation_id[$i];
        //         $result->product_name = $request->result_item_name[$i];
        //         $result->item_kit_name = $request->item_name;
        //         $result->item_name = $request->item_kit_name[$i];
        //         $result->qty = $request->item_kit_quantity[$i] ?? 0;
        //         $result->item_unit = $request->item_kit_unit[$i];
        //         $result->retail_price = $request->item_kit_retail_price[$i];
        //         $result->warehouse = $request->warehouse[$i];
        //         $result->save();
        //     }
        // }

        $variationCount = count($request->lot_no);

        $variations = ItemVariation::where('item_id', $id)->delete();

        for ($i = 0; $i < $variationCount; $i++) {

            $variation = new ItemVariation();
            $variation->item_id = $id;
            if ($request->variation_id[$i] != null) {
                $get_var = ItemVariation::where('id', $request->variation_id[$i])->withTrashed()->latest()->first();
                // dd($get_var);

                if ($get_var && $request->variation_id[$i] != null) {
                    $variation->quantity =  $get_var->quantity;
                }
            } else {

                $variation->quantity =  $request->quantity[$i] * ($request->unit2[$i] ?? 1);
            }
            $variation->expired_date = $request->expired_date[$i] ?? null;
            // $variation->descriptions = $request->variations_descriptions[$i] ?? '';
            $variation->arrival_date = $request->warehousein_date[$i] ?? '';
            $variation->muf_date = $request->muf_date[$i] ?? '';
            $variation->lot_no = $request->lot_no[$i];
            $variation->estd_test = $request->estd_test[$i];
            $variation->country_of_origin = $request->country_of_origin[$i];
            $variation->distributor = $request->distributor[$i];
            $variation->reorder_level_stock = $request->reorder_level_stock[$i] ?? 0;
            $variation->unit1 = $request->unit1[$i] ?? '';
            $variation->name1 = $request->name1[$i] ?? '';
            $variation->price1 = $request->price1[$i] ?? 0;
            $variation->wholesale1 = $request->wholesale1[$i] ?? 0;
            $variation->retail1 = $request->retail1[$i] ?? 0;
            $variation->unit2 = $request->unit2[$i] ?? '';
            $variation->name2 = $request->name2[$i] ?? '';
            $variation->price2 = $request->price2[$i] ?? 0;
            $variation->wholesale2 = $request->wholesale2[$i] ?? 0;
            $variation->retail2 = $request->retail2[$i] ?? 0;
            $variation->unit3 = $request->unit3[$i] ?? '';
            $variation->name3 = $request->name3[$i] ?? '';
            $variation->price3 = $request->price3[$i] ?? 0;
            $variation->wholesale3 = $request->wholesale3[$i] ?? 0;
            $variation->retail3 = $request->retail3[$i] ?? 0;


            // if ($request->market == 'Stock') {
            //     $variation->quantity = $request->quantity[$i] * ($request->unit2[$i] ?? 1);
            // } else {
            //     $variation->quantity = 0;
            // }

            $variation->save();
        }

        if ($item && $item->status == 'item_kit') {
            return redirect(url('item_kits'))->with('success', 'Item Kit Update Is Successfully');
        } else {
            return redirect(url('items'))->with('success', 'Product Update Is Successfully');
        }
    }


    public function delete($id, Request $request)
    {
        $item = Item::find($id);
        // dd($item);
        $items = Item::where('id', $request->item_id)->get();
        $item_variation = ItemVariation::where('item_id', $request->item_id)->get();
        $itemkits = ItemKit::where('product_id', $id)->get();

        // dd($itemkits);
        $item->delete();
        foreach ($itemkits as $itemkit) {
            $itemkit->delete();
        }

        foreach ($item_variation as $variation) {
            $variation->delete();
        }

        if ($item && $item->status == 'item_kit') {
            return redirect(url('item_kits'))->with('delete', 'Item Kit Delete Is Successfully');
        }

        return redirect(url('items'))->with('delete', 'Product Delete Is Successfully');
    }

    public function inout($id)
    {
        $item_variations = ItemVariation::find($id);
        $items = Item::find($item_variations->item_id);
        $inout = Inout::where('item_variation_id', $id)->where('in_out', 'first_in')->first();

        $po = PO_sells::where('variation_id', $item_variations->id)->latest()->get();

        $branchs = Warehouse::all();
        $invoices = Invoice::latest()->get();

        return view('inout.inout', compact('id', 'items', 'inout', 'branchs', 'invoices', 'item_variations', 'po'));
    }


    public function item_search(Request $request)
    {
        $items = Item::all();

        return response()->json($items);
    }


    //Edit By Farrooq
    public function item_kits_index(Request $request, $branch = null)
    {
        $warehouse_name = [];
        $warehouses = Warehouse::all();
        foreach ($warehouses as $key => $ware) {
            $warehouse_name[$key] = $ware->name;
        }

        $search = $request->input('search');
        if (auth()->user()->is_admin == '1') {
            $itemQuery = Item::where('status', 'item_kit')->withoutTrashed();
        } else {
            $itemQuery = Item::where('status', 'item_kit')
                ->where('warehouse_id', auth()->user()->level);
        }

        if ($search) {
            $itemQuery->where(function ($query) use ($search) {
                $query->where('item_name', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('name1', 'like', '%' . $search . '%')
                    ->orWhere('expired_date', 'like', '%' . $search . '%')
                    ->orWhere('retail1', 'like', '%' . $search . '%')
                ;
            });
        }



        if ($branch) {
            $item = $itemQuery->where('warehouse_id', $branch)->paginate(100);
            $itemsGroupedByName = $item;
        } else {
            $item = $itemQuery->paginate(100);
            $itemsGroupedByName = $item;
        }

        $warehouses = Item::select('warehouses.id', 'warehouses.name')
            ->leftJoin('warehouses', 'items.warehouse_id', '=', 'warehouses.id')
            ->distinct()
            ->get();
        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Item Kits';

        return view('item.item_kits', compact('itemsGroupedByName', 'warehouse_name', 'warehouses', 'item', 'branchNames', 'currentBranchName', 'branch_drop'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        // Query to filter items based on search input
        $item = Item::where('status', 'item_kit')
            ->where(function ($query) use ($search) {
                $query->where('item_name', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%")
                    ->orWhere('name1', 'LIKE', "%{$search}%")
                    ->orWhere('expired_date', 'LIKE', "%{$search}%")
                    ->orWhereHas('warehouse', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            })
            ->paginate(100);

        $itemsGroupedByName = $item->groupBy('item_name');
        $warehouses = Warehouse::select('id', 'name')->distinct()->get();

        // Return the updated view as an HTML string
        $html = view('item.item_kits', compact('itemsGroupedByName', 'warehouses', 'item'))->render();

        return response()->json(['html' => $html]);
    }
    // End Edit By Farrooq

    public function fileExport()
    {
        return Excel::download(new ItemsExport, 'items.xlsx');
    }
    public function fileImportTemplate()
    {
        return Excel::download(new ItemsImportTemplate, 'items.xlsx');
    }
    public function fileImport(Request $request)
    {
        try {
            $request->validate([
                'warehouse_id' => 'required|exists:warehouses,id',
                'file' => 'required|file|mimes:xlsx,xls,csv',
            ], [
                'file.required' => 'Please upload a file.',
                'file.file' => 'The uploaded file is invalid.',
                'file.mimes' => 'The file must be a valid Excel or CSV file.',
            ]);

            $warehouseId = $request->warehouse_id;
            $file = $request->file('file');
            $import = new ItemsImport($warehouseId);

            Excel::import($import, $file->store('temp'));

            // Check if there were any errors
            if ($import->hasErrors()) {
                return back()->with('error', 'Errors occurred during import. Please check the logs for details.');
            }

            return back()->with('success', 'File Import Is Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while importing the file: ' . $e->getMessage());
        }
    }
    public function barcode($id)
    {
        try {
            $productCode = ItemVariation::with('item')->findOrFail($id);
            return view('item.barcode', ['productCode' => $productCode]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Item variation not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function report_item_details($id, Request $request)
    {

        $items = Item::find($id);
        // dd($items);
        // $item_kits = ItemKit::where('item_name', $request->item_name)->get();


        $variations = ItemVariation::whereIn('item_id', $items->pluck('id'))->get();

        $item_status = $items->status;
        $branchs = Warehouse::all();
        return view('item.report_item_details', compact('items', 'item_status', 'branchs', 'variations'));
    }
}
