<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\PricePercent;
use Illuminate\Http\Request;

class PricePercentController extends Controller
{
    public function item_price_percent()
    {
        $branches = Warehouse::all();
        $all_prices = PricePercent::all();
        return view('price_percent.item_price_percent', compact('branches', 'all_prices'));
    }
    public function store(Request $request)
    {
        // dd($request->branch);
        $price = PricePercent::where('branch', $request->branch)->get();
        // dd($price->count());
        if ($price->count() == 1) {
            return redirect()->back()->with('warning', 'Price Percent already have for that location.');
        }
        $price = new PricePercent();
        $price->percent = $request->percent;
        $price->branch = $request->branch;
        $price->save();
        return redirect()->back()->with('success', 'Percent Add Successful!');
    }
    public function edit($id)
    {
        $branches = Warehouse::all();
        $price = PricePercent::find($id);
        return view('price_percent.item_price_percent_edit', compact('price', 'branches'));
    }
    public function update(Request $request, $id)
    {
        $price = PricePercent::find($id);
        $price->percent = $request->percent;
        $price->branch = $request->branch;
        $price->save();
        return redirect(url('item_price_percent'))->with('success', 'Percent update Successful!');
    }
    public function delete($id)
    {
        $price = PricePercent::find($id);
        $price->delete();
        return redirect(url('item_price_percent'))->with('delete', 'Percent delete Successful!');
    }
}
