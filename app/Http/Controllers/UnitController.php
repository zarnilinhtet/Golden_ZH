<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariation;
use App\Models\PricePercent;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->get();
        return view('unit.unit', compact('units'));
    }

    public function unit_store(Request $request)
    {
        Unit::create($request->all());
        return redirect(url('unit'))->with('success', 'Unit Create Successful');
    }

    public function edit($id)
    {
        $units = Unit::find($id);
        return view('unit.unitEdit', compact('units'));
    }

    public function update(Request $request, $id)
    {
        $units = Unit::find($id);
        $units->update($request->all());
        return redirect(url('unit'))->with('success', 'Unit Update Successful');
    }

    public function delete($id)
    {
        $unit = Unit::find($id);
        $unit->delete();
        return redirect()->back()->with('delete', 'Unit Delete Successful');
    }
    public function get_part_data_unit()
    {
        // Clear application cache
        Cache::flush();

        // Clear configuration cache
        Artisan::call('config:clear');

        // Clear route cache
        Artisan::call('route:clear');

        // Clear view cache
        Artisan::call('view:clear');
        $units = Unit::all();

        return response()->json($units);
    }



    public function unitSearch_withName(Request $request)
    {
        $price_percent = '';
        $item = Item::where('item_name', $request->item_name)->first();
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        $price = PricePercent::where('branch', $request->location)->first();
        if ($price) {
            $price_percent = $price->percent;
        } else {
            $price_percent = 0;
        }
        $unitName = $request->unit;
        $description = $request->description;
        // $productCode = $request->product_code;
        $id = $request->item_id;
        $expiredDate = $request->result_expired_date;

        $variation = ItemVariation::find($id);
        info($variation);

        if ($unitName === $variation->name1) {
            return response()->json([
                'retail' => $variation->retail1 + ($variation->retail1 * ($price_percent / 100)),
                'wholesale' => $variation->wholesale1,
                'buyprice' => $variation->price1,

            ]);
        } elseif ($unitName === $variation->name2) {
            return response()->json([
                'retail' => $variation->retail2 + ($variation->retail2 * ($price_percent / 100)),
                'wholesale' => $variation->wholesale2,
                'buyprice' => $variation->price2,

            ]);
        } elseif ($unitName === $variation->name3) {
            return response()->json([
                'retail' => $variation->retail3 + ($variation->retail3 * ($price_percent / 100)),
                'wholesale' => $variation->wholesale3,
                'buyprice' => $variation->price3,

            ]);
        } else {
            return response()->json('item not found');
        }
    }

    public function unitSearch_withID(Request $request)
    {
        $item = Item::where('item_name', $request->item_name)->first();
        info($item);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        $unitName = $request->unit;
        $variation_id = $request->result_id;
        info($variation_id);
        info($unitName);
        $variation = ItemVariation::where('item_id', $item->id)
            ->where('id', $variation_id)
            ->first();

        if ($unitName === $variation->name1) {
            return response()->json([
                'retail' => $variation->retail1,
                'wholesale' => $variation->wholesale1,
                'buyprice' => $variation->price1,
            ]);
        } elseif ($unitName === $variation->name2) {
            return response()->json([
                'retail' => $variation->retail2,
                'wholesale' => $variation->wholesale2,
                'buyprice' => $variation->price2,
            ]);
        } elseif ($unitName === $variation->name3) {
            return response()->json([
                'retail' => $variation->retail3,
                'wholesale' => $variation->wholesale3,
                'buyprice' => $variation->price3,
            ]);
        } else {
            return response()->json('item not found');
        }
    }
}
