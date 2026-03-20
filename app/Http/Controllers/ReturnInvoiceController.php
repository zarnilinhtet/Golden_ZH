<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\InvoicePaymentMethod;
use App\Models\InvoiceReturnPaymentMethod;
use App\Models\Item;
use App\Models\ItemVariation;
use App\Models\ReturnInvoice;
use App\Models\ReturnItem;
use App\Models\SalePerson;
use App\Models\Sell;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ReturnInvoiceController extends Controller
{

    public function index($id)
    {

        $invoices = ReturnInvoice::where('invoice_id', $id)->latest()->get();
        return view('sale_return.sale_return_manage', compact('invoices', 'id'));
    }


    public function sale_return_get_transaction(Request $request)
    {
        $category = $request->status;


        $settings = Setting::where('branch_id', $request->locationId)
            ->where('category', 'Sale Return (Invoice)')
            ->pluck('transaction_id'); // Get only transaction IDs

        if ($settings->isEmpty()) {
            return response()->json(['error' => 'Settings not found'], 404);
        }

        $transactions = Transaction::where('location', $request->locationId)
            ->whereIn('id', $settings)
            ->get();

        return $transactions->isNotEmpty()
            ? response()->json($transactions)
            : response()->json(['error' => 'Transactions not found'], 404);
    }
    public function invoice_return($id)
    {


        $invoice = Invoice::whereNull('deleted_at')->where('status', 'invoice')
            ->where('id', $id)
            ->latest()->first();
        // dd($invoices);

        $invoices = $invoice ? $invoice->invoice_no : 'SR - 0';
        $mainString = $invoices;
        $parts = explode("-", $mainString);

        $part1 = $parts[0] . "-";
        $part2 = $parts[1];
        $invoice_no = $part2 + 1;



        // Fetch units and warehouses for the view
        $units = Unit::all();
        $warehouses = Warehouse::orderBy('created_at', 'asc')
            ->where('id', $invoice->location)
            ->first();
        $doctors = Doctor::latest()->get();
        $sale_persons = SalePerson::latest()->get();

        // Pass the necessary data to the view, including 'invoicea' if needed
        return view('sale_return.sale_return', compact('invoice_no', 'units', 'warehouses', 'invoices', 'doctors', 'sale_persons', 'invoice'));
    }


    public function invoice_return_register(Request $request, $id)
    {


        try {

            $count = count($request->part_number);
            $invoice = new ReturnInvoice();

            $invoice->customer_id = $request->customer_id;
            if ($request->customer_id) {
                $customer = Customer::find($request->customer_id);
                $customer->name = $request->customer_name;
                $customer->phno = $request->phno;
                $customer->address = $request->address;
                $customer->dob = $request->dob;
                $customer->branch = $request->location;
                $customer->save();
            } elseif ($request->customer_name) {
                $customer = new Customer();
                $customer->name = $request->customer_name;
                $customer->phno = $request->phno;
                $customer->address = $request->address;
                $customer->branch = $request->location;
                $customer->dob = $request->dob;
                $customer->save();
            }

            $invoice->invoice_id = $id;
            $invoice->customer_name = $request->customer_name;
            $invoice->phno  = $request->phno;
            $invoice->status  = $request->status;
            $invoice->type  = $request->type;
            $invoice->address  = $request->address;
            $invoice->nrc  = $request->nrc;
            $invoice->category = $request->category;
            $invoice->dob  = $request->dob;
            $invoice->invoice_no  = $request->invoice_no;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->overdue_date  = $request->overdue_date;
            $invoice->net_total  = $request->sub_total;
            $invoice->total  = $request->total;
            $invoice->discount_total  = $request->discount;
            $invoice->deposit  = $request->deposit;
            $invoice->remain_balance  = $request->balance;
            $invoice->remark = $request->remark;
            $invoice->location = $request->location;
            $invoice->sale_price_category = $request->sale_price_category;
            $invoice->sale_by = $request->sale_person_id;

            // $invoice->payment_method   = $request->payment_method;
            $invoice->save();

            $last_id = $invoice->id;
            for ($i = 0; $i < $count; $i++) {
                $result = new ReturnItem();
                $result->invoice_return_id = $last_id;
                $result->invoice_id = $id;
                $result->customer_id = $request->customer_id;
                $result->description = $request->part_description[$i];
                $result->discount = $request->buy_price[$i] ?? 0;
                $result->part_number = $request->part_number[$i];
                $result->product_qty = (float) $request->product_qty[$i];
                // dd(gettype($result->product_qty));
                $result->product_price = $request->product_price[$i] ?? 0;
                $result->retail_price = $request->retail_price[$i];
                $result->exp_date = $request->exp_date[$i];
                $result->unit = $request->item_unit[$i];
                $result->warehouse = $request->warehouse[$i];
                $result->variation_id = $request->result_id[$i];
                $result->item_id = $request->item_id[$i];
                $result->product_name = $request->result_item_name[$i];
                $result->item_discount = $request->item_discount[$i] ?? 0;
                $result->ks_percent = $request->valueIndicator[$i] ?? "ks";
                $result->save();
            }

            $count2 = count($request->payment_method);



            for ($i = 0; $i < $count2; $i++) {
                $payment_method = new InvoiceReturnPaymentMethod();
                $payment_method->invoice_return_id = $last_id;
                $payment_method->transaction_id = $request->payment_method[$i];
                $payment_method->payment_amount = $request->payment_amount[$i];
                $payment_method->invoice_status = 'sale_return_invoice';
                $payment_method->save();
            }
            if ($invoice->status === 'invoice' && $invoice->balance_due == 'Invoice') {
                $receivable_setting = Setting::where('category', 'Receivable (Invoice Return)')
                    ->where('branch_id', $request->location)
                    ->get();  // Returns a collection

                $saleaccount_setting = Setting::where('category', 'Sale Account (Invoice Return)')
                    ->where('branch_id', $request->location)
                    ->get();  // Returns a collection

                // Handling receivable settings
                if ($invoice->remain_balance > 0 && $receivable_setting->isNotEmpty()) {
                    foreach ($receivable_setting as $setting) {
                        $payment_method = new InvoiceReturnPaymentMethod();
                        $payment_method->invoice_return_id = $last_id;
                        $payment_method->status = 'receivable_return_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->remain_balance;
                        $payment_method->invoice_status = 'invoice';
                        $payment_method->save();
                    }
                }

                // Handling sale account settings
                if ($saleaccount_setting->isNotEmpty()) {
                    foreach ($saleaccount_setting as $setting) {
                        $payment_method = new InvoiceReturnPaymentMethod();
                        $payment_method->invoice_return_id = $last_id;
                        $payment_method->status = 'return_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->total;
                        $payment_method->invoice_status = 'invoice';
                        $payment_method->save();
                    }
                }
            }



            $count = count($request->part_number);

            for ($i = 0; $i < $count; $i++) {
                $item = Item::where('id', $request->item_id[$i])->where('warehouse_id', $request->warehouse[$i])->first();


                $item_variation = ItemVariation::where('item_id', $item->id)
                    ->where('id', $request->result_id[$i])
                    ->first();

                if ($item_variation->name1 == $request->item_unit[$i]) {
                    $product_qty = (float) $request->product_qty[$i];
                    $unit2 = (float) (!empty($item_variation->unit2) ? $item_variation->unit2 : 1);
                    $item_variation->quantity += $product_qty * $unit2;
                    $item_variation->save();
                }

                if ($item_variation->name2 == $request->item_unit[$i]) {
                    $product_qty = (float) $request->product_qty[$i];
                    $item_variation->quantity += $product_qty;
                    $item_variation->save();
                }

                if ($item_variation->name3 == $request->item_unit[$i]) {
                    $product_qty = (float) $request->product_qty[$i];
                    $conversionFactor3to2 = 1 / (float) $item_variation->unit3;
                    $item_variation->quantity += $product_qty * $conversionFactor3to2;
                    $item_variation->save();
                }
            }


            return redirect('/invoice')->with('success', 'Invoice Added Successfully!');
        } catch (\Exception $e) {
            return redirect('/invoice')->with('error', 'Something went wrong! Please try again' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $invoice = ReturnInvoice::findOrFail($id);

        foreach ($invoice->return_item as $sell) {
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
            } else {
                continue;
            }
        }
        $invoice->delete();

        // Delete related items
        ReturnItem::where('invoice_return_id', $id)->delete();

        // Delete related payment methods
        InvoiceReturnPaymentMethod::where('invoice_return_id', $id)->delete();

        return redirect()->back()->with('success', 'Return Invoice Deleted Successfully');
    }
}
