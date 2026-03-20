<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InOut;
use App\Models\Invoice;
use App\Models\ItemVariation;
use App\Models\Warehouse;
use App\Models\PO_sells;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class InOutController extends Controller
{

    public function in(Request $request, $id)
    {
        $item = ItemVariation::findOrFail($request->item_variation_id);
        // dd($item);

        // $part->retail_price_in_mmk = $request->retail_price_mmk;
        // $part->save();

        $result = new InOut();
        $result->item_variation_id = $request->item_variation_id;
        $result->items_id = $request->items_id;
        if ($item->name1 === $request->unit) {
            $result->quantity = $request->quantity;

            $item->quantity = (float)($item->quantity) +
                ((float)($request->quantity) * intval($item->unit2 ?: 1));
            $result->total_quantity = $item->quantity;
        }
        if ($item->name2 === $request->unit) {
            $result->quantity = $request->quantity;
            // return (intval($item->quantity) + intval($request->quantity));
            $item->quantity = (float)($item->quantity) +
                (float)($request->quantity);
            $result->total_quantity = $item->quantity;
        }

        if ($item->name3 == $request->unit) {
            $result->quantity = $request->quantity;

            $item->quantity = (float)($item->quantity) + ((float)($request->quantity) / ($item->unit3));
            $result->total_quantity = $item->quantity;
        }
        $result->date = $request->date;


        $result->company_price = $request->company_price;
        $result->mingalar_market = $request->unit;
        $result->remark = $request->remark;
        $result->in_out = 'in';
        $result->warehouse_id = $request->warehouse_id;
        $result->save();



        $item->save();

        return redirect()->back()->with('success', 'Stock adjusted Successfully');
    }


    public function out(Request $request, $id)
    {
        //


        $item = ItemVariation::findorfail($id);
        $result = new InOut();
        $result->items_id = $request->items_id;
        $result->item_variation_id = $request->item_variation_id;

        $result->date = $request->date;
        if ($item->name1 === $request->unit) {
            $result->quantity = $request->quantity;

            $item->quantity = (float)($item->quantity) -
                ((float)($request->quantity) * intval($item->unit2 ?: 1));
            $result->total_quantity = $item->quantity;
        }
        if ($item->name2 === $request->unit) {
            $result->quantity = $request->quantity;
            // return (intval($item->quantity) + intval($request->quantity));
            $item->quantity = (float)($item->quantity) -
                (float)($request->quantity);
            $result->total_quantity = $item->quantity;
        }

        if ($item->name3 == $request->unit) {
            $result->quantity = $request->quantity;
            // dd((float)($item->quantity) + (float)($request->quantity) / ($item->unit3));
            $item->quantity = ((float)($item->quantity) - (float)(($request->quantity) / ($item->unit3)));
            $result->total_quantity = $item->quantity;
        }
        $result->remark = $request->remark;
        $result->mingalar_market = $request->unit;
        $result->in_out = 'out';
        $result->warehouse_id = $request->warehouse_id;
        $result->save();


        $item->save();


        // $result->create($request->all());

        return redirect()->back()->with('out-success', 'Damage Item Added Successfully');
    }

    public function display_print($variation_id, $invoiceid)
    {

        $item = ItemVariation::where('id', $variation_id)->first();
        $inout = PO_sells::where('invoiceid', $invoiceid)->where('variation_id', $item->id)->first();
        $profile = UserProfile::latest()->first();
        return view('inout.print-display', compact('inout', 'item', 'profile'));
    }
    public function damage_item_print($id, $item_variation_id)
    {

        $item = ItemVariation::find($item_variation_id);
        // dd($item);
        $inout = InOut::find($id);
        $profile = UserProfile::latest()->first();
        return view('inout.damage_item_print', compact('inout', 'item', 'profile'));
    }

    public function invoice_record($id)
    {
        $item_variation = ItemVariation::find($id);
        $items = Item::find($item_variation->item_id);
        // dd($items);
        $invoices = Invoice::latest()->get();
        return view('inout.invoice_record', compact('item_variation', 'invoices', 'items'));
    }

    public function pos_record($id)
    {
        $items = Item::find($id);
        $invoices = Invoice::latest()->get();
        return view('inout.pos_record', compact('invoices', 'items'));
    }

    public function purchase_order_reord($id)
    {
        $items = Item::find($id);
        $invoices = PurchaseOrder::latest()->get();
        return view('inout.purchase_order_record', compact('invoices', 'items'));
    }
    public function stock_adjust($id)
    {
        $inouts = Inout::where('item_variation_id', $id)->where('in_out', 'in')->get();
        $item_variation = ItemVariation::find($id);
        // dd($item_variation);

        $branchs = Warehouse::all();
        return view('inout.stock_adjust', compact('inouts', 'id', 'branchs', 'item_variation'));
    }
    public function damage_item($id)
    {
        $inouts = Inout::where('item_variation_id', $id)->where('in_out', 'out')->get();
        $item_variation = ItemVariation::find($id);
        $branchs = Warehouse::all();
        return view('inout.damage_item', compact('inouts', 'id', 'branchs', 'item_variation'));
    }
}
