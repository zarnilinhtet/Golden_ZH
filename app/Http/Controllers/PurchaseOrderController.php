<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sell;
use App\Models\Unit;
use App\Models\Invoice;
use App\Models\ItemVariation;
use App\Models\Setting;
use App\Models\PO_sells;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPaymentMethod;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $po = PurchaseOrder::latest()->get();
        return view('purchase_order.purchase_order_manage', compact('po'));
    }


    // public function purchase_order_register()
    // {
    //     $suppliers = Supplier::all();
    //     $po_number = PurchaseOrder::latest()->first();
    //     $units = Unit::all();
    //     // $po_no = $po_number ? ($po_number->id) + 1 : 1;
    //     $po_no = $po_number ? $po_number->id + 1 : 1;
    //     $warehouses = Warehouse::all();
    //     return view('purchase_order.purchase_order', compact('po_no', 'suppliers', 'units', 'warehouses'));
    // }

    public function purchase_order_register()
    {
        $suppliers = Supplier::all();
        $po_number = PurchaseOrder::where('quote_no', 'like', 'PO%')->latest()->first();
        // dd($po_number);
        $units = Unit::all();
        $po_number = $po_number ? $po_number->quote_no : 'PO-0';
        $mainString = $po_number;
        $parts = explode("-", $mainString);

        $part1 = $parts[0] . "-"; // "PO-"
        $part2 = $parts[1];
        $po_no = $part2 + 1;

        $warehouses = Warehouse::all();
        return view('purchase_order.purchase_order', compact('po_no', 'suppliers', 'units', 'warehouses', 'po_number'));
    }


    //Customer Fill
    public function po_search(Request $request)
    {
        $query = $request->get('query');
        $data = Supplier::select('name', 'phno')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('phno', 'LIKE', '%' . $query . '%')
            ->get();
        return response()->json($data);
    }
    public function po_search_fill(Request $request)
    {
        // $userBranchId = auth()->user()->branch_id;
        $supplier = Supplier::where('name', $request->model)
            ->orWhere('phno', $request->model)
            ->orderBy('created_at', 'desc')
            ->first();
        if (!$supplier) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $responseData = [
            'product' => $supplier,
        ];
        return response()->json($responseData);
    }
    public function purchase_order_store(Request $request)
    {

        // try {

        $item = Item::where('id', 'like', $request->item_id)->where('warehouse_id', $request->location)->first();
        // $item = Item::where('item_name', $request->part_number[$i])->where('warehouse_id', $request->location)->first();

        // dd($item->id);
        $count = count($request->part_number);
        // dd($count);
        $invoice = new PurchaseOrder();



        $invoice->supplier_id = $request->supplier_id;
        $invoice->supplier_name = $request->supplier_name;
        $invoice->invoice_category = $request->discount_total_percent;
        $invoice->phno = $request->phno;
        $invoice->status = $request->status;
        $invoice->type = $request->type;
        $invoice->unit = $request->location;
        $invoice->address = $request->address;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->item_no = $item->id;
        $invoice->overdue_date = $request->overdue_date;
        $invoice->po_date = $request->po_date;
        $invoice->quote_no = $request->po_number;
        $invoice->net_total = $request->sub_total;
        $invoice->total = $request->total;
        $invoice->balance_due = $request->balance_due;
        $invoice->discount_total = $request->discount_total;
        // $invoice->remain_balance = $request->remain_balance;
        // $invoice->deposit = $request->paid;
        $invoice->remark = $request->remark;
        $invoice->save();
        $last_id = $invoice->id;
        for ($i = 0; $i < $count; $i++) {
            $result = new PO_sells();
            $result->invoiceid = $last_id;
            $result->supplier_id = $request->supplier_id;
            $result->part_number = $request->part_number[$i];
            $result->unit = $request->item_unit[$i];
            $result->product_qty = (float) $request->product_qty[$i];
            $result->discount = $request->discount[$i];
            $result->company_price = $request->company_price[$i];
            $result->totalQty = (float) $request->totalQty[$i];
            $result->foc = $request->foc[$i];
            $result->amount = $request->amount[$i];
            $result->commercial_tax = $request->commercialtax[$i];
            $result->warehouse = $request->warehouse[$i];
            $result->name1 = $request->name1[$i];
            $result->price1 = $request->price1[$i];
            $result->retail1 = $request->retail1[$i];
            $result->wholesale1 = $request->wholesale1[$i];
            $result->name2 = $request->name2[$i];
            $result->price2 = $request->price2[$i];
            $result->retail2 = $request->retail2[$i];
            $result->wholesale2 = $request->wholesale2[$i];
            $result->name3 = $request->name3[$i];
            $result->price3 = $request->price3[$i];
            $result->retail3 = $request->retail3[$i];
            $result->wholesale3 = $request->wholesale3[$i];
            $result->variation_id = $request->result_id[$i];
            $result->item_id = $request->item_id[$i];
            $result->product_name = $request->result_item_name[$i];
            $result->exp_date = $request->result_expired_date[$i];
            $item = ItemVariation::find($request->result_id[$i]);
            if ($request->item_unit[$i] == $item->name1) {
                $result->item_total_qty = (float) $item->quantity + (float) $request->totalQty[$i] * ((float) (!empty($item->unit2) ? $item->unit2 : 1));
            }

            if ($request->item_unit[$i] == $item->name2) {
                $result->item_total_qty = (float) $item->quantity + (float) $request->totalQty[$i];
            }
            if ($request->item_unit[$i] == $item->name3) {
                $result->item_total_qty = (float) $item->quantity + ((float) $request->totalQty[$i] / (float) $item->unit3);
            }
            $result->save();
        }


        $count2 = count($request->payment_method);


        if ($invoice->status === 'invoice') {


            if ($invoice->status === 'invoice' && $invoice->balance_due == 'Purchase Order') {
                for ($i = 0; $i < $count2; $i++) {
                    $payment_method = new PurchaseOrderPaymentMethod();
                    $payment_method->po_id = $last_id;
                    $payment_method->transaction_id = $request->payment_method[$i];
                    $payment_method->payment_amount = $request->payment_amount[$i];
                    $payment_method->po_status = 'po';
                    $payment_method->save();
                }

                $payable_setting = Setting::where('category', 'Payable (Purchase Order)')
                    ->where('branch_id', $request->location)
                    ->get();  // Returns a collection

                $purchaseaccount_setting = Setting::where('category', 'Buy Account (Purchase Order)')
                    ->where('branch_id', $request->location)
                    ->get();  // Returns a collection

                // Handling receivable settings
                if ($invoice->remain_balance > 0 && $payable_setting->isNotEmpty()) {
                    foreach ($payable_setting as $setting) {
                        $payment_method = new PurchaseOrderPaymentMethod();
                        $payment_method->po_id = $last_id;
                        $payment_method->status = 'payable_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->remain_balance;
                        $payment_method->po_status = 'po';
                        $payment_method->save();
                    }
                }

                // Handling sale account settings
                if ($purchaseaccount_setting->isNotEmpty()) {
                    foreach ($purchaseaccount_setting as $setting) {
                        $payment_method = new PurchaseOrderPaymentMethod();
                        $payment_method->po_id = $last_id;
                        $payment_method->status = 'purchase_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->total;
                        $payment_method->po_status = 'po';
                        $payment_method->save();
                    }
                }
            } else {
                for ($i = 0; $i < $count2; $i++) {
                    $payment_method = new PurchaseOrderPaymentMethod();
                    $payment_method->po_id = $last_id;
                    $payment_method->transaction_id = $request->payment_method[$i];
                    $payment_method->payment_amount = $request->payment_amount[$i];
                    $payment_method->po_status = 'sale return';
                    $payment_method->save();
                }
            }
        }


        $count1 = count($request->item_id);
        for ($i = 0; $i < $count1; $i++) {
            $item = Item::where('id', $request->item_id[$i])->where('warehouse_id', $request->warehouse[$i])->first();


            $item_variation = ItemVariation::where('item_id', $item->id)->where('id', $request->result_id[$i])->first();

            $po = Supplier::find($request->supplier_id);

            $quotations = Invoice::with('sells')->where('status', 'quotation')->get();
            if ($item_variation) {
                $item_variation->price1 = $request->price1[$i];
                $item_variation->retail1 = $request->retail1[$i];
                $item_variation->wholesale1 = $request->wholesale1[$i];
                $item_variation->price2 = $request->price2[$i];
                $item_variation->retail2 = $request->retail2[$i];
                $item_variation->wholesale2 = $request->wholesale2[$i];
                $item_variation->price3 = $request->price3[$i];
                $item_variation->retail3 = $request->retail3[$i];
                $item_variation->wholesale3 = $request->wholesale3[$i];
                if ($request->item_unit[$i] == $item_variation->name1) {
                    $item_variation->quantity += (float) $request->totalQty[$i] * ((float)(!empty($item_variation->unit2) ? $item_variation->unit2 : 1) ?? 1);
                }
                if ($request->item_unit[$i] == $item_variation->name2) {
                    $item_variation->quantity += (float) $request->totalQty[$i];
                }
                if ($request->item_unit[$i] == $item_variation->name3) {
                    $item_variation->quantity += ((float) $request->totalQty[$i] / (float)$item_variation->unit3);
                }
            }

            $item_variation->update();

            foreach ($quotations as $quotation) {
                foreach ($quotation->sells as $sell) {
                    if ($sell->item_id === $request->item_id[$i]) {
                        if ($sell->unit == $request->name1[$i]) {
                            $sell->discount =  $request->price1;
                            $sell->product_price =   $request->wholesale1;
                            $sell->retail_price = $request->retail1;
                        }
                        if ($sell->unit == $request->name2[$i]) {
                            $sell->discount =  $request->price2[$i];
                            $sell->product_price =   $request->wholesale2[$i];
                            $sell->retail_price = $request->retail2[$i];
                        }
                        if ($sell->unit == $request->name1[$i]) {
                            $sell->discount =  $request->price3[$i];
                            $sell->product_price =   $request->wholesale3[$i];
                            $sell->retail_price = $request->retail3[$i];
                        } else {
                            continue;
                        }
                        $sell->update();
                    } else
                        continue;
                }
            }
        }
        return redirect('/purchase_order_register')->with('success', 'Purchase Order Added Successful!');
        // } catch (\Exception $e) {
        //     return redirect('/purchase_order_manage')->with('error', 'Something went wrong, please try again.');
        // }
    }


    public function edit($id)
    {
        $suppliers = Supplier::all();
        $purchase_orders = PurchaseOrder::find($id);
        $purchase_sells = PO_sells::where('invoiceid', $id)->get();
        $items = Item::all();
        $warehouses = Warehouse::all();
        // dd($purchase_sells[0]->warehouse);
        $units = Unit::all();
        $payment_method = PurchaseOrderPaymentMethod::where('po_id', $id)
            ->whereNull('status')
            ->get();

        if ($purchase_orders->balance_due == 'Purchase Order') {
            $setting = Setting::where('category', 'Purchase Order')->where('branch_id', $purchase_orders->unit)->get();
        } else {
            $setting = Setting::where('category', 'Sale Return (Invoice)')->where('branch_id', $purchase_orders->unit)->get();
        }

        // dd($setting);


        $transactions = [];
        if ($setting) {
            foreach ($setting as $singleSetting) {
                $transactions[] = Transaction::where('id', $singleSetting->transaction_id)
                    ->where('location', $purchase_orders->unit)
                    ->get();
            }
        }

        return view('purchase_order.purchase_order_edit', compact('purchase_orders', 'purchase_sells', 'warehouses', 'units', 'suppliers', 'items', 'transactions', 'payment_method'));
    }
    public function purchase_order_update(Request $request, $id)
    {
        // dd($request->all());

        try {
            $count = count($request->part_number);

            $invoice = PurchaseOrder::find($id);
            $invoice->invoice_category = $request->discount_total_percent;
            $invoice->phno  = $request->phno;
            $invoice->status  = $request->status;
            $invoice->type  = $request->type;
            $invoice->address  = $request->address;
            $invoice->invoice_no  = $request->invoice_no;
            $invoice->overdue_date = $request->overdue_date;
            $invoice->po_date = $request->po_date;
            $invoice->quote_no  = $request->po_number;
            $invoice->net_total  = $request->sub_total;
            $invoice->total  = $request->total;
            $invoice->unit  = $request->location;
            $invoice->balance_due  = $request->balance_due;
            $invoice->discount_total  = $request->discount_total;
            // $invoice->remain_balance  = $request->remain_balance;
            // $invoice->deposit  = $request->paid;
            $invoice->remark  = $request->remark;
            $invoice->save();
            $last_id = $invoice->id;

            $oldQuantities = [];
            // dd($invoice->po_sells);
            foreach ($invoice->po_sells as $key => $sell) {
                if ($sell->unit == $sell->item_variation->name1) {

                    if ($sell->item_variation->unit2 == null) {
                        $oldQuantities[$key] = (int) $sell->totalQty;
                    } else {
                        $oldQuantities[$key] = (int) $sell->totalQty * (int)($sell->item_variation->unit2);
                    }
                } elseif ($sell->unit == $sell->item_variation->name2) {
                    $oldQuantities[$key] = $sell->totalQty;
                } else {
                    $oldQuantities[$key] = $sell->totalQty / ($sell->item_variation->unit3);
                }
            }





            foreach ($request->input('item_id') as $key => $itemID) {
                $item = Item::find($itemID);


                if (!$item) {
                    continue;
                }

                $item_variation = ItemVariation::where('item_id', $item->id)
                    ->where('id', $request->result_id[$key])
                    ->first();



                if ($item_variation) {

                    $currentQuantity = (float)$item_variation->quantity;

                    if ($item_variation->name1 == $request->input('item_unit')[$key]) {
                        $Quantity = $currentQuantity - ($oldQuantities[$key] ?? 0);
                        // info('Qty' . $Quantity);
                        $newQuantity = $Quantity + ((float)$request->totalQty[$key] * (float) (!empty($item_variation->unit2) ? $item_variation->unit2 : 1));
                    }



                    if ($item_variation->name2 == $request->input('item_unit')[$key]) {
                        $Quantity = $currentQuantity -  ($oldQuantities[$key] ?? 0);
                        $newQuantity = $Quantity + ((float)$request->totalQty[$key]);
                    }
                    if ($item_variation->name3 == $request->input('item_unit')[$key]) {
                        $Quantity = $currentQuantity -  ($oldQuantities[$key] ?? 0);

                        $newQuantity = $Quantity + ((float)$request->totalQty[$key] / (float)$item_variation->unit2);
                    }

                    $item_variation->quantity = $newQuantity;
                    $item_variation->update();
                }
            }

            PO_sells::where('invoiceid', $id)->delete();

            for ($i = 0; $i < $count; $i++) {
                $result = new PO_sells();
                $result->invoiceid = $last_id;
                $result->supplier_id = $request->supplier_id;
                $result->part_number = $request->part_number[$i];

                $result->unit = $request->item_unit[$i];
                $result->product_qty = (float) $request->product_qty[$i];
                $result->discount = $request->discount[$i];
                $result->company_price = $request->company_price[$i];
                $result->totalQty = (float) $request->totalQty[$i];
                $result->foc = $request->foc[$i];
                $result->amount = $request->amount[$i];
                $result->name1 = $request->name1[$i];
                $result->name2 = $request->name2[$i];
                $result->name3 = $request->name3[$i];
                $result->price1 = $request->price1[$i];
                $result->price2 = $request->price2[$i];
                $result->price3 = $request->price3[$i];
                $result->retail1 = $request->retail1[$i];
                $result->retail2 = $request->retail2[$i];
                $result->retail3 = $request->retail3[$i];
                $result->wholesale1 = $request->wholesale1[$i];
                $result->wholesale2 = $request->wholesale2[$i];
                $result->wholesale3 = $request->wholesale3[$i];

                $result->variation_id = $request->result_id[$i];
                $result->product_name = $request->result_item_name[$i];
                $result->item_id = $request->item_id[$i];
                $result->product_name = $request->result_item_name[$i];
                // $result->exp_date = $request->result_expired_date[$i];



                $result->commercial_tax = $request->commercialtax[$i];

                $result->warehouse = $request->warehouse[$i];
                $result->save();
                $item = Item::where('id', $request->item_id[$i])->where('warehouse_id', $request->warehouse[$i])->first();

                $item_variation = ItemVariation::where('item_id', $item->id)->where('id', $request->result_id[$key])->first();
                if ($item_variation) {
                    $item_variation->price1 = $request->price1[$i];
                    $item_variation->retail1 = $request->retail1[$i];
                    $item_variation->wholesale1 = $request->wholesale1[$i];


                    $item_variation->price2 = $request->price2[$i];
                    $item_variation->retail2 = $request->retail2[$i];
                    $item_variation->wholesale2 = $request->wholesale2[$i];


                    $item_variation->price3 = $request->price3[$i];
                    $item_variation->retail3 = $request->retail3[$i];
                    $item_variation->wholesale3 = $request->wholesale3[$i];
                    $item_variation->save();
                }
            }


            if ($request->input('payment_method')) {
                $submittedPaymentIds = [];

                foreach ($request->input('payment_method') as $key => $paymentMethod) {
                    $payment_id = $request->input('payment_id')[$key] ?? null;
                    $payment_amount = $request->input('payment_amount')[$key] ?? 0;
                    $payment_method = PurchaseOrderPaymentMethod::find($payment_id) ?? new PurchaseOrderPaymentMethod();
                    $payment_method->po_id = $id;
                    $payment_method->transaction_id = $paymentMethod;
                    $payment_method->payment_amount = $payment_amount;
                    if (!$payment_method->exists) {
                        $payment_method->created_at = now();
                        $payment_method->payment_date = now()->format('Y-m-d');
                    }
                    $payment_method->po_status = $invoice->balance_due == 'Purchase Order' ? 'po' : 'sale return';
                    $payment_method->updated_at = now();
                    $payment_method->save();

                    if ($payment_method->id) {
                        $submittedPaymentIds[] = $payment_method->id;
                    }
                }

                PurchaseOrderPaymentMethod::where('po_id', $id)
                    ->whereNotIn('id', $submittedPaymentIds)
                    ->delete();
            }

            $payable_setting = Setting::where('category', 'Payable (Purchase Order)')
                ->where('branch_id', $request->location)
                ->get();  // Returns a collection

            $purchaseaccount_setting = Setting::where('category', 'Buy Account (Purchase Order)')
                ->where('branch_id', $request->location)
                ->get();  // Returns a collection



            if ($invoice->status === 'invoice' && $invoice->balance_due == 'Purchase Order') {
                // Update or insert receivable account records
                if ($invoice->remain_balance >= 0 && $payable_setting->isNotEmpty()) {
                    foreach ($payable_setting as $setting) {
                        $payment_method = PurchaseOrderPaymentMethod::where('po_id', $id)
                            ->where('transaction_id', $setting->transaction_id)
                            ->where('status', 'payable_account')
                            ->first();

                        if ($payment_method) {
                            // Update existing record
                            $payment_method->payment_amount = $invoice->remain_balance;
                            $payment_method->updated_at = now();
                            $payment_method->po_status = 'po';
                        } else {
                            // Insert new record
                            $payment_method = new PurchaseOrderPaymentMethod();
                            $payment_method->po_id = $id;
                            $payment_method->status = 'payable_account';
                            $payment_method->transaction_id = $setting->transaction_id;
                            $payment_method->payment_amount = $invoice->remain_balance;
                            $payment_method->created_at = now();
                            $payment_method->updated_at = now();
                            $payment_method->po_status = 'po';
                        }

                        $payment_method->save();
                    }
                }

                // Update or insert sale account records
                if ($purchaseaccount_setting->isNotEmpty()) {
                    foreach ($purchaseaccount_setting as $setting) {
                        $payment_method = PurchaseOrderPaymentMethod::where('po_id', $id)
                            ->where('transaction_id', $setting->transaction_id)
                            ->where('status', 'purchase_account')
                            ->first();

                        if ($payment_method) {
                            // Update existing record
                            $payment_method->payment_amount = $invoice->total;
                            $payment_method->updated_at = now();
                            $payment_method->po_status = 'po';
                        } else {
                            // Insert new record
                            $payment_method = new PurchaseOrderPaymentMethod();
                            $payment_method->po_id = $id;
                            $payment_method->status = 'purchase_account';
                            $payment_method->transaction_id = $setting->transaction_id;
                            $payment_method->payment_amount = $invoice->total;
                            $payment_method->created_at = now();
                            $payment_method->updated_at = now();
                            $payment_method->po_status = 'po';
                        }

                        $payment_method->save();
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error or take action
            Log::error('Error updating purchase order: ' . $e->getMessage());
            return redirect('/purchase_order_manage')->with('error', 'Something Wrong,Fail to update Purchase Order!' . $e->getMessage());
        }
        return redirect('/purchase_order_manage')->with('success', 'Purchase Order update Successful!');
    }
    public function po_delete($id)
    {
        DB::beginTransaction();
        try {

            $po = PurchaseOrder::findOrFail($id);
            foreach ($po->po_sells as $sell) {
                $item = Item::where('id', $sell->item_id)
                    ->where('warehouse_id', $sell->warehouse)
                    ->first();


                if ($item && $item->status === NULL) {
                    $item_variation = ItemVariation::where('item_id', $item->id)
                        ->where('id', $sell->variation_id)
                        ->first();

                    if ($item_variation) {
                        if ($item_variation->name1 == $sell->unit) {
                            $item_variation->quantity -= (float)$sell->product_qty * (float) (!empty(!empty($item_variation->unit2) ? $item_variation->unit2 : 1) ? (!empty($item_variation->unit2) ? $item_variation->unit2 : 1) : 1);
                            $item_variation->save();
                        }
                        if ($item_variation->name2 == $sell->unit) {
                            $item_variation->quantity -= (float)$sell->product_qty;
                            $item_variation->save();
                        }
                        if ($item_variation->name3 == $sell->unit) {
                            $conversionFactor3to2 = 1 / (float)$item_variation->unit3;
                            $item_variation->quantity -= (float)$sell->product_qty * $conversionFactor3to2;
                            $item_variation->save();
                        }
                    }
                } else if ($item && $item->status === "item_kit") {
                    $items = Item::where('id', $item->id)
                        ->where('warehouse_id', $sell->warehouse)
                        ->with('variations')
                        ->first();

                    if ($items && $items->variations) {
                        foreach ($items->variations as $variation) {
                            $variation->quantity -= (float)$sell->product_qty;
                            $variation->save();
                        }
                    }

                    $counts = count($items->itemKits);
                    $itemkits = $items->itemKits;
                    foreach ($items->itemKits as $kit) {
                        $itemKit = Item::where('id', $kit->item_id)
                            ->where('warehouse_id', $sell->warehouse)
                            ->first();

                        if ($itemKit) {
                            foreach ($kit->variations as $variation) {
                                if ($variation->name1 == $kit->item_unit) {
                                    $kitQuantityToSubtract = (float)$kit->qty * (float)$sell->product_qty * (float)(!empty($variation->unit2) ? $variation->unit2 : 1);
                                    $variation->quantity -= $kitQuantityToSubtract;
                                    $variation->save();
                                } elseif ($variation->name2 == $kit->item_unit) {
                                    $kitQuantityToSubtract = (float)$kit->qty * (float)$sell->product_qty;
                                    $variation->quantity -= $kitQuantityToSubtract;
                                    $variation->save();
                                } elseif ($variation->name3 == $kit->item_unit) {
                                    $conversionFactor3to2 = 1 / (float)$variation->unit3;
                                    $kitQuantityToSubtract = (float)$kit->qty * (float)$sell->product_qty * $conversionFactor3to2;
                                    $variation->quantity -= $kitQuantityToSubtract;
                                    $variation->save();
                                }
                            }
                        }
                    }
                } else {
                    continue;
                }
            }
            $po->delete();
            PO_sells::where('invoiceid', $id)->delete();
            DB::commit();
            return redirect('/purchase_order_manage')->with('success', 'Purchase Order Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/purchase_order_manage')->with('error', 'Failed to delete purchase order.');
        }
        return redirect('/quotation')->with('success', 'Quotation Deleted Successful!');
    }
    public function details($id, Request $request)
    {
        $purchase_orders = PurchaseOrder::where('quote_no', $request->quote_no)->get();
        $purchase_order = PurchaseOrder::find($id);
        // dd($purchase_orders);
        $purchaseOrdersWithSells = [];
        foreach ($purchase_orders as $purchase_order) {
            $sells = PO_sells::where('invoiceid', $purchase_order->id)->get();
            $purchaseOrdersWithSells[] = [
                'sells' => $sells,
            ];
        }
        // dd($purchaseOrdersWithSells);
        $profile = UserProfile::latest()->first();
        // $item = Item::where('id', $purchase_orders->item_no)->get()->first();
        return view('purchase_order.purchase_order_details', compact('purchase_order', 'purchase_orders', 'purchaseOrdersWithSells', 'profile'));
    }


    // public function item_search_for_po(Request $request)
    // {
    //     info($request);
    //     $query = $request->get('query');
    //     $location = $request->get('location');
    //     $items = Item::where('item_name', 'like', '%' . $query . '%')
    //         ->where('warehouse_id', $location)
    //         ->whereNull('status')
    //         ->where('market', 'Stock')
    //         ->withoutTrashed()
    //         ->pluck('item_name');

    //     return response()->json($items);
    // }
    public function item_search_for_po(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');

        $items = Item::where('item_name', 'like', '%' . $query . '%')
            ->where('warehouse_id', $location)
            ->whereNull('status')
            // ->where('market', 'Stock')
            ->withoutTrashed()
            ->pluck('id');

        $variations = ItemVariation::whereIn('item_id', $items)
            ->get(['item_id', 'descriptions', 'lot_no', 'id', 'expired_date'])
            ->map(function ($variation) {
                $item = Item::find($variation->item_id);

                return [
                    'item_name' => $item ? $item->item_name : 'Unknown Item',
                    'description' => $variation->descriptions,
                    'product_code' => $variation->lot_no,
                    'id' => $variation->id,
                    'item_id' => $variation->item_id,
                    'expired_date' => $variation->expired_date,
                ];
            });

        return response()->json($variations);
    }

    public function po_get_transaction(Request $request)
    {
        $mode = $request->balance_due;
        $location = $request->locationId;

        if ($mode == "Purchase Order") {
            $settings = Setting::where('branch_id', $location)
                ->where('category', 'Purchase Order')
                ->get();
        } else {
            $settings = Setting::where('branch_id', $location)
                ->where('category', 'Sale Return (Invoice)')
                ->get();
        }

        if ($settings->isNotEmpty()) {
            $transaction = collect();

            foreach ($settings as $setting) {
                $transactionsForSetting = Transaction::where('location', $location)
                    ->where('id', $setting->transaction_id)
                    ->get();

                $transaction = $transaction->merge($transactionsForSetting);
            }
            info($transaction);


            if ($transaction->isNotEmpty()) {
                return response()->json($transaction);
            } else {
                return response()->json(['error' => 'Transactions not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Settings not found'], 404);
        }
    }
}
