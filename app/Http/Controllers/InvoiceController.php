<?php

namespace App\Http\Controllers;


use Exception;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Sell;
use App\Models\Unit;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\ItemKit;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SalePerson;
use App\Models\Transaction;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\ItemVariation;
use App\Models\ReturnInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicePaymentMethod;
use App\Models\InvoiceRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\SuperInvoicePaymentMethod;
use App\Models\MakePayment;


class InvoiceController extends Controller
{
    public function payment($id)
    {
        $make_payments = Invoice::find($id);

        $settings = Setting::where('branch_id', $make_payments->location)->where('category', 'Invoice')->first();

        $transactions = Transaction::where('location', $make_payments->location)->where('account_id', $settings->transaction_id)->get();

        $payments = MakePayment::where('invoice_id', $id)
            // ->where('invoice_no', '!=', null)
            ->get();
        $payments_number = MakePayment::latest()->first();
        return view('invoice.make_payment', compact('make_payments', 'payments', 'payments_number', 'transactions'));
    }
    public function payment_store(Request $request, $id)
    {

        if ($request->remain_balance == '0') {
            return redirect()->back()->with('error', 'Remaining Balance is 0 , Nothing To Pay!');
        }

        $make_payments = new MakePayment();

        $invoice = Invoice::where('status', 'invoice')->where('id', $id)->first();
        $make_payments->payment_method = $request->payment_method;
        $make_payments->amount = $request->amount;
        $make_payments->note = $request->note;
        $make_payments->invoice_no = $request->invoice_no;
        $make_payments->invoice_id = $invoice->id;
        $make_payments->location = $request->branch;
        $make_payments->payment_date = $request->payment_date;
        $make_payments->save();

        //substract receivable deposit when make makepayment
        $invoice_payment_method = InvoicePaymentMethod::where('invoice_id', $invoice->id)->where('status', 'receivable')->first();


        // $invoice_deposit_payment = InvoicePaymentMethod::where('invoice_id', $invoice->id)->whereNull('status')->first();
        // if ($invoice_deposit_payment) {
        //     $invoice_deposit_payment->payment_amount = $invoice_deposit_payment->payment_amount + $request->amount;
        //     $invoice_deposit_payment->save();
        // }

        if ($invoice_payment_method) {
            $invoice_payment_method->payment_amount = $invoice_payment_method->payment_amount - $request->amount;
            $invoice_payment_method->save();
        }


        //end substract receivable deposit when make makepayment


        $invoice->deposit = $request->amount + $invoice->deposit;
        $invoice->remain_balance = $invoice->remain_balance - $request->amount;
        $invoice->update();

        $payment_method = new InvoicePaymentMethod();
        $payment_method->invoice_id = $invoice->id;
        $payment_method->transaction_id = $request->payment_method;
        $payment_method->payment_amount = $request->amount;
        $payment_method->created_at = Carbon::now();
        $payment_method->updated_at = Carbon::now();
        $payment_method->invoice_status = 'invoice';
        $payment_method->save();

        return redirect(url('invoice'))->with('success', 'Payment Added Successfull!');
    }
    //
    public function index()
    {
        if (Auth::user()->type == '0' || Auth::user()->is_admin == 1) {
            $invoices = Invoice::where('status', 'invoice')->latest()->get();
        } else {
            $invoices = Invoice::where('status', 'invoice')->where('location', Auth::user()->level)->latest()->get();
        }
        return view('invoice.invoice_manage', compact(
            'invoices'
        ));
    }

    public function record()
    {
        $invoice_records = InvoiceRecord::latest()->get();
        return view('invoice.invoice_record', compact('invoice_records'));
    }
    public function quotation()
    {
        if (Auth::user()->type == '0' || Auth::user()->is_admin == 1) {
            $quotations = Invoice::where('status', 'quotation')->latest()->get();
        } else {
            $quotations = Invoice::where('status', 'quotation')->where('location', Auth::user()->level)->latest()->get();
        }

        return view('quotation.quotation_manage', compact(
            'quotations'
        ));
    }

    public function pos_register()
    {
        $invoices = Invoice::whereIn('status', ["pos", "suspend"])->latest()->whereNull('deleted_at')->first();
        $suspends = Invoice::where('status', 'suspend')->latest()->get();
        // $invoice_no = $invoices ? count($invoices) + 1 : 1;
        $invoices = $invoices ? $invoices->invoice_no : 'POS-0';
        $mainString = $invoices;
        $parts = explode("-", $mainString);

        $part1 = $parts[0] . "-";
        $part2 = $parts[1];
        $invoice_no = $part2 + 1;
        $units = Unit::all();
        $doctors = Doctor::latest()->get();
        $warehouses = Warehouse::orderBy('created_at', 'asc')->get();
        return view('pos.pos', compact('invoice_no', 'units', 'warehouses', 'suspends', 'invoices', 'doctors'));
    }


    public function pos()
    {
        if (Auth::user()->type == '0' || Auth::user()->is_admin == 1) {
            $invoices = Invoice::where('status', 'pos')->latest()->get();
        } else {
            $invoices = Invoice::where('status', 'pos')->where('location', Auth::user()->level)->latest()->get();
        }

        return view('invoice.pos_manage', compact(
            'invoices'
        ));
    }

    public function quotation_register()
    {
        $quotations = Invoice::where('status', 'quotation')->latest()->withoutTrashed()->get();
        $quotation_no = $quotations ? count($quotations) + 1 : 1;
        $units = Unit::all();
        $warehouses = Warehouse::all();
        $doctors = Doctor::latest()->get();
        return view('quotation.quotation', compact('quotation_no', 'units', 'warehouses', 'doctors'));
    }
    //     public function invoice()
    //     {
    //         // $invoices = Invoice::where('status', ['invoice'])->latest()->first();
    //         $invoicea = DB::select("
    //     SELECT COUNT(*) AS counter, invoice_category
    //     FROM invoices
    //     WHERE invoice_category = 'invoice'
    //     AND deleted_at IS NULL
    //     GROUP BY invoice_category
    // ");


    //         $quotations = Invoice::where('status', 'quotation')->latest()->get();
    //         $total_quotation = count($quotations);
    //         $invoice_no = $invoices ? $invoices->id + 1 : 1;
    //         $units = Unit::all();
    //         $warehouses = Warehouse::orderBy('created_at', 'asc')->get();
    //         return view('invoice.invoice', compact('invoice_no', 'units', 'warehouses', 'total_quotation'));
    //     }
    // public function invoice()
    // {
    //     // Get the latest invoice with status 'invoice' for generating the next invoice number
    //     // $invoices = Invoice::where('status', 'invoice')->latest()->first();

    //     // Get the count of invoices by category 'invoice'
    //     $invoices = DB::table('invoices')
    //         //  ->select(DB::raw('COUNT(*) as counter'), 'invoice_category')
    //         ->select(DB::raw('COUNT(*) as zarni'))
    //         ->where('invoice_category', 'invoice')
    //         ->whereNull('deleted_at')
    //         ->groupBy('invoice_category')
    //         ->first();
    //     //  dd($invoices);
    //     // Get all quotations and count them
    //     $quotations = Invoice::where('status', 'quotation')->latest()->get();
    //     $total_quotation = count($quotations);

    //     // Generate the next invoice number, starting at 1 if no invoices exist
    //     // $invoice_no = $invoices ? $invoices->counter + 1 : 1;
    //     //10/24/2024 zarni code with sayr (invoice counter)
    //     $invoice_no = $invoices ? $invoices->zarni + 1 : 1;


    //     // Fetch units and warehouses for the view
    //     $units = Unit::all();
    //     $warehouses = Warehouse::orderBy('created_at', 'asc')->get();

    //     // Pass the necessary data to the view, including 'invoicea' if needed
    //     return view('invoice.invoice', compact('invoice_no', 'units', 'warehouses', 'total_quotation', 'invoices'));
    // }

    public function invoice()
    {
        $invoices = Invoice::whereNull('deleted_at')
            ->where('status', 'invoice')
            ->latest()
            ->first();

        // ရက် လ နှစ်
        $day   = date('d'); // 12
        $month = date('m'); // 08
        $year  = date('y'); // 25

        // နောက်ဆုံး invoice ရှာပြီး running number တွက်
        if ($invoices) {
            // နောက်ဆုံး invoice_no ရဲ့ နောက်ဆုံး 3 digit ကိုယူ
            $lastNumber = (int)substr($invoices->invoice_no, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        // အသစ် format လုပ်
        $invoice_no = 'GZH' . '00' . $day . $month . $year . $nextNumber;

        // Delivery_no ကို မပြောင်းဘူး (original)
        $donumber = $invoices ? $invoices->delivery_no : 'DO - 0';
        $mainString2 = $donumber;
        $do_parts = explode("-", $mainString2);
        $do_part1 = $do_parts[0] . "-";
        $do_part2 = $do_parts[1];
        $delivery_no = $do_part2 + 1;

        // Other data
        $quotations = Invoice::where('status', 'quotation')->latest()->get();
        $total_quotation = count($quotations);
        $units = Unit::all();
        $warehouses = Warehouse::orderBy('created_at', 'asc')->get();
        $doctors = Doctor::latest()->get();
        $sale_persons = SalePerson::latest()->get();

        return view('invoice.invoice', compact(
            'invoice_no',
            'units',
            'warehouses',
            'total_quotation',
            'invoices',
            'doctors',
            'sale_persons',
            'delivery_no'
        ));
    }

    //Try Cache
    public function invoice_register(Request $request)
    {
        // dd($request->all());

        try {



            $count = count($request->part_number);
            $invoice = new Invoice();

            $invoice->customer_id = $request->customer_id;
            if ($request->customer_id) {
                $customer = Customer::find($request->customer_id);
                $customer->name = $request->customer_name;
                $customer->phno = $request->phno;
                $customer->address = $request->address;
                $customer->dob = $request->dob;
                $customer->branch = $request->location;
                $customer->save();
            } elseif ($request->customer_name != null) {
                $customer = new Customer();
                $customer->name = $request->customer_name;
                $customer->phno = $request->phno;
                $customer->address = $request->address;
                $customer->branch = $request->location;
                $customer->dob = $request->dob;
                $customer->save();
            }
            $invoice->customer_id = $customer->id ?? null;

            $invoice->customer_name = $request->customer_name;
            $invoice->invoice_category = $request->quote_category;
            $invoice->phno  = $request->phno;
            $invoice->status  = $request->status;
            $invoice->type  = $request->type;
            $invoice->address  = $request->address;
            $invoice->nrc  = $request->nrc;
            $invoice->category = $request->category;
            $invoice->dob  = $request->dob;
            $invoice->invoice_no  = $request->invoice_no;
            $invoice->delivery_no  = $request->delivery_no;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->quote_date = $request->quote_date;
            $invoice->quote_no  = $request->quote_no;
            $invoice->overdue_date  = $request->overdue_date;
            $invoice->net_total  = $request->sub_total;
            $invoice->total  = $request->total;
            $invoice->balance_due  = $request->balance_due;
            $invoice->discount_total  = $request->discount;
            $invoice->deposit  = $request->deposit;
            $invoice->remain_balance  = $request->balance;
            $invoice->super_net_total  = $request->super_sub_total;
            $invoice->super_total  = $request->super_total;

            $invoice->super_discount  = $request->super_discount;
            $invoice->super_deposit  = $request->super_deposit;
            $invoice->super_remain_balance  = $request->super_remain_balance;
            $invoice->remark = $request->remark;
            $invoice->location = $request->location;
            $invoice->sale_price_category = $request->sale_price_category;
            $invoice->sale_by = $request->sale_person_id;

            // $invoice->payment_method   = $request->payment_method;
            $invoice->save();

            $last_id = $invoice->id;
            for ($i = 0; $i < $count; $i++) {
                $result = new Sell();
                $result->invoiceid = $last_id;
                $result->customer_id = $request->customer_id;
                $result->description = $request->part_description[$i];
                $result->discount = $request->buy_price[$i] ?? 0;
                $result->part_number = $request->part_number[$i];
                $result->product_qty = (float) $request->product_qty[$i];
                // dd(gettype($result->product_qty));
                $result->product_price = $request->product_price[$i] ?? 0;
                $result->retail_price = $request->retail_price[$i];
                $result->special_price = $request->special_price[$i] ?? '';
                $result->exp_date = $request->exp_date[$i] ?? '';
                $result->unit = $request->item_unit[$i];
                $result->warehouse = $request->warehouse[$i];
                $result->variation_id = $request->result_id[$i];
                $result->item_id = $request->item_id[$i];
                $result->product_name = $request->result_item_name[$i];
                $result->item_discount = $request->item_discount[$i] ?? 0;
                $result->ks_percent = $request->valueIndicator[$i] ?? "ks";
                $result->super_item_discount = $request->super_item_discount[$i] ?? 0;
                $result->super_ks_percent = $request->super_valueIndicator[$i] ?? "ks";
                $result->super_discount = $request->is_special_discount[$i] ?? 0;
                $result->super = $request->is_special_price[$i] ?? 0;
                $result->save();
            }
            // if ($invoice->status === 'quotation' || $invoice->status === 'invoice') {
            //     $count2 = count($request->payment_method);
            //     for ($i = 0; $i < $count2; $i++) {
            //         $payment_method = new InvoicePaymentMethod();
            //         $payment_method->invoice_id = $last_id;
            //         $payment_method->transaction_id = null;
            //         $payment_method->payment_method = $request->payment_method[$i];
            //         $payment_method->payment_amount = $request->payment_amount[$i];
            //         $payment_method->save();
            //     }
            // }
            $count2 = count($request->payment_method);



            if ($invoice->status === 'invoice') {
                $count3 = count($request->super_payment_method);
                for ($i = 0; $i < $count2; $i++) {
                    $payment_method = new InvoicePaymentMethod();
                    $payment_method->invoice_id = $last_id;
                    $payment_method->transaction_id = $request->payment_method[$i];
                    $payment_method->payment_amount = $request->payment_amount[$i];
                    $payment_method->invoice_status = $invoice->balance_due == 'Invoice' ? 'invoice' : 'po return';
                    $payment_method->save();
                }
                for ($i = 0; $i < $count3; $i++) {
                    $payment_method = new SuperInvoicePaymentMethod();
                    $payment_method->invoice_id = $last_id;
                    $payment_method->transaction_id = $request->super_payment_method[$i];
                    $payment_method->payment_amount = $request->super_payment_amount[$i];
                    $payment_method->invoice_status = $invoice->balance_due == 'Invoice' ? 'invoice' : 'po return';
                    $payment_method->save();
                }
                if ($invoice->status === 'invoice' && $invoice->balance_due == 'Invoice') {
                    $receivable_setting = Setting::where('category', 'Receivable (Invoice)')
                        ->where('branch_id', $request->location)
                        ->get();  // Returns a collection

                    $saleaccount_setting = Setting::where('category', 'Sale Account (Invoice)')
                        ->where('branch_id', $request->location)
                        ->get();  // Returns a collection

                    // Handling receivable settings
                    if ($invoice->remain_balance > 0 && $receivable_setting->isNotEmpty()) {
                        foreach ($receivable_setting as $setting) {
                            $payment_method = new InvoicePaymentMethod();
                            $payment_method->invoice_id = $last_id;
                            $payment_method->status = 'receivable_account';
                            $payment_method->transaction_id = $setting->transaction_id;
                            $payment_method->payment_amount = $invoice->remain_balance;
                            $payment_method->invoice_status = 'invoice';
                            $payment_method->save();
                        }
                    }

                    // Handling sale account settings
                    if ($saleaccount_setting->isNotEmpty()) {
                        foreach ($saleaccount_setting as $setting) {
                            $payment_method = new InvoicePaymentMethod();
                            $payment_method->invoice_id = $last_id;
                            $payment_method->status = 'sale_account';
                            $payment_method->transaction_id = $setting->transaction_id;
                            $payment_method->payment_amount = $invoice->total;
                            $payment_method->invoice_status = 'invoice';
                            $payment_method->save();
                        }
                    }
                }
            } elseif ($invoice->status == 'quotation') {
                for ($i = 0; $i < $count2; $i++) {
                    $payment_method = new InvoicePaymentMethod();
                    $payment_method->invoice_id = $last_id;
                    $payment_method->transaction_id = $request->payment_method[$i];
                    $payment_method->payment_amount = $request->payment_amount[$i];
                    $payment_method->invoice_status = 'quotation';
                    $payment_method->save();
                }

                $receivable_setting = Setting::where('category', 'Receivable (Invoice)')
                    ->where('branch_id', $request->location)
                    ->get();  // Returns a collection

                $saleaccount_setting = Setting::where('category', 'Sale Account (Invoice)')
                    ->where('branch_id', $request->location)
                    ->get();  // Returns a collection

                // Handling receivable settings
                if ($invoice->remain_balance > 0 && $receivable_setting->isNotEmpty()) {
                    foreach ($receivable_setting as $setting) {
                        $payment_method = new InvoicePaymentMethod();
                        $payment_method->invoice_id = $last_id;
                        $payment_method->status = 'receivable_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->remain_balance;
                        $payment_method->invoice_status = 'quotation';
                        $payment_method->save();
                    }
                }

                // Handling sale account settings
                if ($saleaccount_setting->isNotEmpty()) {
                    foreach ($saleaccount_setting as $setting) {
                        $payment_method = new InvoicePaymentMethod();
                        $payment_method->invoice_id = $last_id;
                        $payment_method->status = 'sale_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->total;
                        $payment_method->invoice_status = 'quotation';
                        $payment_method->save();
                    }
                }
            }


            if ($request->status == 'quotation') {
                return redirect('/quotation')->with('success', 'Quotation Added Successfully!');
            } elseif ($invoice->status === 'pos') {
                $count = count($request->part_description);

                for ($i = 0; $i < $count; $i++) {
                    $item = Item::where('id', $request->item_id[$i])->where('warehouse_id', $request->warehouse[$i])->first();
                    if ($item && $item->status === NULL) {

                        $item_variation = ItemVariation::where('item_id', $item->id)
                            ->where('id', $request->result_id[$i])
                            ->first();

                        if ($item_variation->name1 == $request->item_unit[$i]) {
                            $unit2 = (float) (!empty($item_variation->unit2) ? $item_variation->unit2 : 1);
                            $product_qty = (float) $request->product_qty[$i];
                            $item_variation->quantity -= $product_qty * $unit2;
                            $item_variation->save();
                        }

                        if ($item_variation->name2 == $request->item_unit[$i]) {
                            $product_qty = (float) $request->product_qty[$i];
                            $item_variation->quantity -= $product_qty;
                            $item_variation->save();
                        }

                        if ($item_variation->name3 == $request->item_unit[$i]) {
                            $conversionFactor3to2 = 1 / (float) $item_variation->unit3;
                            $product_qty = (float) $request->product_qty[$i];
                            $item_variation->quantity -= $product_qty * $conversionFactor3to2;
                            $item_variation->save();
                        }
                    } else if ($item && $item->status === "item_kit") {

                        $items = Item::where('id', $item->id)
                            ->where('warehouse_id', $request->warehouse[$i])
                            ->with('variations')
                            ->first();

                        if ($items && $items->variations) {
                            foreach ($items->variations as $variation) {
                                $product_qty = (float) $request->product_qty[$i];
                                $variation->quantity -= $product_qty;
                                $variation->save();
                            }
                        }

                        // $counts = count($items->itemKits);
                        // // dd($counts);
                        // foreach ($items->itemKits as $kit) {
                        //     $itemKit = Item::where('id', $kit->item_id)
                        //         ->where('warehouse_id', $request->warehouse[$i])
                        //         ->first();

                        //     if ($itemKit) {
                        //         foreach ($kit->variations as $variation) {

                        //             $product_qty = (float) $request->product_qty[$i];
                        //             $kit_qty = (float) $kit->qty;
                        //             if ($variation->name1 == $kit->item_unit) {
                        //                 $unit2 = (float) (!empty($variation->unit2) ? $variation->unit2 : 1);
                        //                 $kitQuantityToSubtract = $kit_qty * $product_qty * $unit2;
                        //                 $variation->quantity -= $kitQuantityToSubtract;
                        //                 $variation->save();
                        //             } elseif ($variation->name2 == $kit->item_unit) {
                        //                 $kitQuantityToSubtract = $kit_qty * $product_qty;
                        //                 $variation->quantity -= $kitQuantityToSubtract;
                        //                 $variation->save();
                        //             } elseif ($variation->name3 == $kit->item_unit) {
                        //                 $conversionFactor3to2 = 1 / (float) $variation->unit3;
                        //                 $kitQuantityToSubtract = $kit_qty * $product_qty * $conversionFactor3to2;
                        //                 $variation->quantity -= $kitQuantityToSubtract;
                        //                 $variation->save();
                        //             }
                        //         }
                        //     }
                        // }
                    } else {
                        continue;
                    }
                }

                return redirect()->route('invoice_detail', ['invoice' => $invoice->id])->with('success', 'POS Register Successfully');
            } elseif ($invoice->status === 'invoice') {
                $count = count($request->part_number);

                for ($i = 0; $i < $count; $i++) {
                    $item = Item::where('id', $request->item_id[$i])->where('warehouse_id', $request->warehouse[$i])->first();

                    if ($item && $item->status === NULL) {
                        $item_variation = ItemVariation::where('item_id', $item->id)
                            ->where('id', $request->result_id[$i])
                            ->first();

                        if ($item_variation->name1 == $request->item_unit[$i]) {
                            $product_qty = (float) $request->product_qty[$i];
                            $unit2 = (float) (!empty($item_variation->unit2) ? $item_variation->unit2 : 1);
                            $item_variation->quantity -= $product_qty * $unit2;
                            $item_variation->save();
                        }

                        if ($item_variation->name2 == $request->item_unit[$i]) {
                            $product_qty = (float) $request->product_qty[$i];
                            $item_variation->quantity -= $product_qty;
                            $item_variation->save();
                        }

                        if ($item_variation->name3 == $request->item_unit[$i]) {
                            $product_qty = (float) $request->product_qty[$i];
                            $conversionFactor3to2 = 1 / (float) $item_variation->unit3;
                            $item_variation->quantity -= $product_qty * $conversionFactor3to2;
                            $item_variation->save();
                        }
                    } else if ($item && $item->status === "item_kit") {

                        $items = Item::where('id', $item->id)
                            ->where('warehouse_id', $request->warehouse[$i])
                            ->with('variations')
                            ->first();

                        if ($items && $items->variations) {
                            foreach ($items->variations as $variation) {
                                $product_qty = (float) $request->product_qty[$i];
                                $variation->quantity -= $product_qty;
                                $variation->save();
                            }
                        }

                        $counts = count($items->itemKits);
                        $itemkits = $items->itemKits;



                        // foreach ($items->itemKits as $kit) {
                        //     $itemKit = ItemVariation::where('item_id', $kit->item_id)

                        //         ->first();


                        //     if ($itemKit) {
                        //         foreach ($kit->variations as $variation) {

                        //             $product_qty = (float) $request->product_qty[$i];
                        //             $kit_qty = (float) $kit->qty;
                        //             if ($variation->name1 == $kit->item_unit) {
                        //                 $unit2 = (float) (!empty($variation->unit2) ? $variation->unit2 : 1);
                        //                 $kitQuantityToSubtract = $kit_qty * $product_qty * $unit2;
                        //                 $variation->quantity -= $kitQuantityToSubtract;
                        //                 $variation->save();
                        //             } elseif ($variation->name2 == $kit->item_unit) {
                        //                 $kitQuantityToSubtract = $kit_qty * $product_qty;
                        //                 $variation->quantity -= $kitQuantityToSubtract;
                        //                 $variation->save();
                        //             } elseif ($variation->name3 == $kit->item_unit) {
                        //                 $conversionFactor3to2 = 1 / (float) $variation->unit3;
                        //                 $kitQuantityToSubtract = $kit_qty * $product_qty * $conversionFactor3to2;
                        //                 $variation->quantity -= $kitQuantityToSubtract;
                        //                 $variation->save();
                        //             }
                        //         }
                        //     }
                        // }
                    } else {
                        continue;
                    }
                }


                return redirect('/invoice')->with('success', 'Invoice Added Successfully!');
            } else {
                return redirect()->back()->with('success', 'POS Suspended Added Successfully!');
            }
        } catch (\Exception $e) {
            return redirect('/invoice')->with('error', 'Something went wrong! Please try again' . $e->getMessage());
        }
    }

    //Try Cache
    public function customer_service_search(Request $request)
    {
        $data = Customer::select('name', 'phno', 'id')
            ->where('name', 'LIKE', '%' . $request->get('query') . '%')
            ->orWhere('phno', 'LIKE', '%' . $request->get('query') . '%')
            ->get(); // Retrieve all matching records

        return response()->json($data);
    }


    public function item_search(Request $request)
    {
        $data = Item::select('item_name')
            ->where('item_name', 'LIKE', '%' . $request->get('query') . '%')->where('parent_id', 0)->withoutTrashed()

            ->pluck('item_name'); // Retrieve all matching records

        return response()->json($data);
    }


    public function item_data_search_fill(Request $request)
    {
        $lastItem = Item::latest()->first();
        $product = Item::where('item_name', $request->model)->where('parent_id', 0)
            ->orderBy('created_at', 'desc')->withoutTrashed()
            ->first();
        $variation = ItemVariation::where('item_id', $product->id)->first();

        $all_items = Item::where('item_name', $request->model)->get();


        if (!$product) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        $warehouse = Warehouse::find($product->warehouse_id);
        $responseData = [
            'item' => $product,
            'warehouse' => $warehouse,
            'parent_id' => $lastItem->id + 1,
            'variation' => $variation,
            'all_items' => $all_items
        ];

        return response()->json($responseData);
    }


    public function customer_service_search_fill(Request $request)
    {

        $customerId = $request->input('result');

        $product = Customer::where('id', $customerId)->first();
        if (!$product) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
        $responseData = [
            'customer' => $product,

        ];

        return response()->json($responseData);
    }


    public function quotation_delete($id)
    {
        $quotation = Invoice::find($id);
        Sell::where('invoiceid', $id)->delete();
        $quotation->delete();
        return redirect('/quotation')->with('success', 'Quotation Deleted Successful!');
    }
    public function invoice_delete($id)
    {
        $invoice = Invoice::find($id);

        $invoice_record = new InvoiceRecord();
        $invoice_record->invoice_id = $invoice->id;
        $invoice_record->user_id = Auth::user()->id;
        $invoice_record->warehouse_id = $invoice->location;
        $invoice_record->invoice_no = $invoice->invoice_no;
        $invoice_record->date = now();
        $invoice_record->save();

        if ($invoice->status == 'pos') {
            foreach ($invoice->sells as $sell) {
                $item = Item::where('id', $sell->item_id)
                    ->where('warehouse_id', $sell->warehouse)
                    ->first();


                if ($item && $item->status === NULL) {
                    $item_variation = ItemVariation::where('item_id', $item->id)
                        ->where('id', $sell->variation_id)
                        ->first();

                    if ($item_variation->name1 == $sell->unit) {
                        $item_variation->quantity += (float)$sell->product_qty * (float) (!empty(!empty($item_variation->unit2) ? $item_variation->unit2 : 1) ? (!empty($item_variation->unit2) ? $item_variation->unit2 : 1) : 1);
                        $item_variation->save();
                    }
                    if ($item_variation->name2 == $sell->unit) {
                        $item_variation->quantity += (float)$sell->product_qty;
                        $item_variation->save();
                    }
                    if ($item_variation->name3 == $sell->unit) {
                        $conversionFactor3to2 = 1 / (!empty($item_variation->unit3) ? $item_variation->unit3 : 1);
                        $item_variation->quantity += (float)$sell->product_qty * $conversionFactor3to2;
                        $item_variation->save();
                    }
                } else {
                    continue;
                }
            }
        } elseif ($invoice->status == 'suspend') {
        } else {
            foreach ($invoice->sells as $sell) {
                $item = Item::where('id', $sell->item_id)
                    ->where('warehouse_id', $sell->warehouse)
                    ->first();


                if ($item && $item->status === NULL) {
                    $item_variation = ItemVariation::where('item_id', $item->id)
                        ->where('id', $sell->variation_id)
                        ->first();

                    if ($item_variation) {
                        if ($item_variation->name1 == $sell->unit) {
                            $item_variation->quantity += (float)$sell->product_qty * (float) (!empty(!empty($item_variation->unit2) ? $item_variation->unit2 : 1) ? (!empty($item_variation->unit2) ? $item_variation->unit2 : 1) : 1);
                            $item_variation->save();
                        }
                        if ($item_variation->name2 == $sell->unit) {
                            $item_variation->quantity += (float)$sell->product_qty;
                            $item_variation->save();
                        }
                        if ($item_variation->name3 == $sell->unit) {
                            $conversionFactor3to2 = 1 / (float)$item_variation->unit3;
                            $item_variation->quantity += (float)$sell->product_qty * $conversionFactor3to2;
                            $item_variation->save();
                        }
                    }
                } else {
                    continue;
                }
            }
        }
        $return = ReturnInvoice::where('invoice_id', $id)->exists();
        $payments = InvoicePaymentMethod::where('invoice_id', $id)->get();
        if ($payments) {
            foreach ($payments as $payment) {
                $payment->delete();
            }
        }
        $super_make_payment = SuperInvoicePaymentMethod::where('invoice_id', $id)->get();
        if ($super_make_payment) {
            foreach ($super_make_payment as $payment) {
                $payment->delete();
            }
        } 
        $make_payment = MakePayment::where('invoice_id', $id)->get();
        if ($make_payment) {
            foreach ($make_payment as $payment) {
                $payment->delete();
            }
        }

        if ($return) {
            return redirect()->back()->with('delete', 'This invoice has a return invoice. Please delete the return invoice first.');
        }
        Sell::where('invoiceid', $id)->delete();
        $invoice->delete();
        if ($invoice->status == 'pos') {
            return redirect()->back()->with('success', 'POS Deleted Successful!');
        } elseif ($invoice->status == 'suspend') {
            return redirect()->back()->with('delete', 'Suspend Deleted Successful!');
        } else {

            return redirect('/invoice')->with('success', 'Invoice Deleted Successful!');
        }
    }
    public function quotation_edit($id)
    {
        $quotation = Invoice::find($id);
        $sell = Sell::where('invoiceid', $id)->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $sell_no = $sell->count();
        $payment_method = InvoicePaymentMethod::where('invoice_id', $id)
            ->whereNull('status')
            ->get();

        $setting = Setting::where('category', 'Invoice')->where('branch_id', $quotation->location)
            ->get();

        $transactions = [];
        if ($setting) {
            foreach ($setting as $singleSetting) {
                $transactions[] = Transaction::where('id', $singleSetting->transaction_id)
                    ->where('location', $quotation->location)
                    ->get();
            }
        }
        $doctors = Doctor::latest()->get();
        return view('quotation.quotation_edit', compact('quotation', 'sell', 'warehouses', 'units', 'payment_method', 'transactions', 'doctors', 'sell_no'));
    }
    public function invoice_edit($id)
    {
        $invoice = Invoice::find($id);
        $doctors = Doctor::latest()->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $sale_persons = SalePerson::all();
        $payment_method = InvoicePaymentMethod::where('invoice_id', $id)
            ->whereNull('status')
            ->get();
        $super_payment_method = SuperInvoicePaymentMethod::where('invoice_id', $id)
            ->whereNull('status')
            ->get();

        if ($invoice->balance_due == 'Invoice') {
            $setting = Setting::where('category', 'Invoice')->where('branch_id', $invoice->location)
                ->get();
        } else {
            $setting = Setting::where('category', 'Po Return')->where('branch_id', $invoice->location)
                ->get();
        }

        $transactions = [];
        if ($setting) {
            foreach ($setting as $singleSetting) {
                $transactions[] = Transaction::where('id', $singleSetting->transaction_id)
                    ->where('location', $invoice->location)
                    ->get();
            }
        }

        if ($invoice->status == 'suspend') {
            $sells = Sell::where('invoiceid', $id)->get();
            $sell_no = $sells->count();
            return view('pos.pos_edit', compact('invoice', 'sells', 'warehouses', 'units', 'sell_no', 'doctors'));
        } else {
            $sell = Sell::where('invoiceid', $id)->get();
            $sell_no = $sell->count();
            return view('invoice.invoice_edit', compact('invoice', 'sell', 'warehouses', 'units', 'sell_no', 'doctors', 'payment_method', 'sale_persons', 'transactions', 'super_payment_method'));
        }
    }

    public function invoice_update(Request $request, $id)
    {

        $invoice = Invoice::find($id);
        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found');
        }
        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            $customer->name = $request->customer_name;
            $customer->phno = $request->phno;
            $customer->address = $request->address;
            $customer->dob = $request->dob;
            $customer->branch = $request->location;
            $customer->save();
        } elseif ($request->customer_name != null && $request->customer_id == null) {
            $customer = new Customer();
            $customer->name = $request->customer_name;
            $customer->phno = $request->phno;
            $customer->address = $request->address;
            $customer->dob = $request->dob;
            $customer->branch = $request->location;
            $customer->save();
        }

        $invoice->customer_id = $request->customer_id;
        $invoice->customer_name = $request->customer_name;
        $invoice->age = $request->age;
        $invoice->invoice_category = $request->quote_category;
        $invoice->phno = $request->phno;
        if ($request->status == 'suspend') {

            $invoice->status = 'pos';
        } else {
            $invoice->status = $request->status;
        }
        $invoice->type = $request->type;
        $invoice->address = $request->address;
        $invoice->category = $request->category;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->delivery_no = $request->delivery_no;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->overdue_date = $request->overdue_date;
        $invoice->quote_date = $request->quote_date;
        $invoice->quote_no = $request->quote_no;
        $invoice->location = $request->location;
        $invoice->net_total = $request->sub_total;
        $invoice->total = $request->total;
        $invoice->balance_due = $request->balance_due;
        $invoice->discount_total = $request->discount;
        $invoice->remark = $request->remark;
        $invoice->deposit = $request->deposit;
        $invoice->remain_balance = $request->balance;

        //for super
        $invoice->super_net_total = $request->super_sub_total;
        $invoice->super_total = $request->super_total;
        $invoice->super_discount = $request->super_discount;
        $invoice->super_deposit = $request->super_deposit;
        $invoice->super_remain_balance = $request->super_balance;
        $invoice->foc_patient = $request->foc_patient;
        $invoice->sale_price_category = $request->sale_price_category;
        $invoice->sale_by = $request->sale_person_id;

        $invoice->save();

        $sellsData = [];
        if ($request->input('item_id')) {
            foreach ($request->input('item_id') as $key => $partDescription) {
                $sellsData[] = [

                    // 'description' => $partDescription,
                    'part_number' => $request->input('part_number')[$key],
                    'product_qty' => (float) $request->input('product_qty')[$key],
                    'product_price' => $request->input('product_price')[$key] ?? 0,
                    'retail_price' => $request->input('retail_price')[$key],
                    'special_price' => $request->input('special_price')[$key] ?? '',
                    'unit' => $request->input('item_unit')[$key],
                    'discount' => $request->input('buy_price')[$key],
                    // 'exp_date' => $request->input('exp_date')[$key],
                    'warehouse' => $request->input('warehouse')[$key],
                    'variation_id' => $request->input('result_id')[$key],
                    'item_id' => $request->input('item_id')[$key],
                    'item_discount' => $request->input('item_discount')[$key] ?? 0,
                    'ks_percent' => $request->input('valueIndicator')[$key] ?? "Ks",
                    'super_item_discount' => $request->input('super_item_discount')[$key] ?? 0,
                    'super_ks_percent' => $request->input('super_valueIndicator')[$key] ?? "Ks",
                    'product_name' => $request->input('result_item_name')[$key],
                    'super' => $request->input('is_special_price')[$key] ?? 0,
                    'super_discount' => $request->input('is_special_discount')[$key] ?? 0,
                    'invoiceid' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        $now = Carbon::now();
        if ($invoice->status === 'invoice') {

            if ($request->input('payment_method')) {
                $submittedPaymentIds = [];

                foreach ($request->input('payment_method') as $key => $paymentMethod) {
                    $payment_id = $request->input('payment_id')[$key] ?? null;
                    $payment_amount = $request->input('payment_amount')[$key] ?? 0;

                    $payment_method = InvoicePaymentMethod::find($payment_id) ?? new InvoicePaymentMethod();

                    $payment_method->invoice_id = $id;
                    $payment_method->transaction_id = $paymentMethod;
                    $payment_method->payment_amount = $payment_amount;

                    if (!$payment_method->exists) {
                        $payment_method->created_at = now();
                    }

                    $payment_method->invoice_status = $invoice->balance_due == 'Invoice' ? 'invoice' : 'po return';
                    $payment_method->updated_at = now();
                    $payment_method->save();

                    if ($payment_method->id) {
                        $submittedPaymentIds[] = $payment_method->id;
                    }
                }

                // Delete removed payment methods for this invoice
                InvoicePaymentMethod::where('invoice_id', $id)
                    ->whereNotIn('id', $submittedPaymentIds)
                    ->delete();
            }
            if ($request->input('super_payment_method')) {
                $super_submittedPaymentIds = [];

                foreach ($request->input('super_payment_method') as $key => $paymentMethod) {
                    $payment_id = $request->input('super_payment_id')[$key] ?? null;
                    $payment_amount = $request->input('super_payment_amount')[$key] ?? 0;

                    $payment_method = SuperInvoicePaymentMethod::find($payment_id) ?? new SuperInvoicePaymentMethod();

                    $payment_method->invoice_id = $id;
                    $payment_method->transaction_id = $paymentMethod;
                    $payment_method->payment_amount = $payment_amount;

                    if (!$payment_method->exists) {
                        $payment_method->created_at = now();
                    }

                    $payment_method->invoice_status = $invoice->balance_due == 'Invoice' ? 'invoice' : 'po return';
                    $payment_method->updated_at = now();
                    $payment_method->save();

                    if ($payment_method->id) {
                        $super_submittedPaymentIds[] = $payment_method->id;
                    }
                }

                // Delete removed payment methods for this invoice
                SuperInvoicePaymentMethod::where('invoice_id', $id)
                    ->whereNotIn('id', $super_submittedPaymentIds)
                    ->delete();
            }


            $receivable_setting = Setting::where('category', 'Receivable (Invoice)')
                ->where('branch_id', $request->location)
                ->get();

            $saleaccount_setting = Setting::where('category', 'Sale Account (Invoice)')
                ->where('branch_id', $request->location)
                ->get();

            if ($invoice->status === 'invoice' && $invoice->balance_due == 'Invoice') {
                // Update or insert receivable account records
                if ($invoice->remain_balance >= 0 && $receivable_setting->isNotEmpty()) {
                    foreach ($receivable_setting as $setting) {
                        $payment_method = InvoicePaymentMethod::where('invoice_id', $id)
                            ->where('transaction_id', $setting->transaction_id)
                            ->where('status', 'receivable_account')
                            ->first();

                        if ($payment_method) {
                            // Update existing record
                            $payment_method->payment_amount = $invoice->remain_balance;
                            $payment_method->updated_at = now();
                            $payment_method->invoice_status = 'invoice';
                        } else {
                            // Insert new record
                            $payment_method = new InvoicePaymentMethod();
                            $payment_method->invoice_id = $id;
                            $payment_method->status = 'receivable_account';
                            $payment_method->transaction_id = $setting->transaction_id;
                            $payment_method->payment_amount = $invoice->remain_balance;
                            $payment_method->created_at = now();
                            $payment_method->updated_at = now();
                            $payment_method->invoice_status = 'invoice';
                        }

                        $payment_method->save();
                    }
                }

                // Update or insert sale account records
                if ($saleaccount_setting->isNotEmpty()) {
                    foreach ($saleaccount_setting as $setting) {
                        $payment_method = InvoicePaymentMethod::where('invoice_id', $id)
                            ->where('transaction_id', $setting->transaction_id)
                            ->where('status', 'sale_account')
                            ->first();

                        if ($payment_method) {
                            // Update existing record
                            $payment_method->payment_amount = $invoice->total;
                            $payment_method->updated_at = now();
                            $payment_method->invoice_status = 'invoice';
                        } else {
                            // Insert new record
                            $payment_method = new InvoicePaymentMethod();
                            $payment_method->invoice_id = $id;
                            $payment_method->status = 'sale_account';
                            $payment_method->transaction_id = $setting->transaction_id;
                            $payment_method->payment_amount = $invoice->total;
                            $payment_method->created_at = now();
                            $payment_method->updated_at = now();
                            $payment_method->invoice_status = 'invoice';
                        }

                        $payment_method->save();
                    }
                }
            }
        } elseif ($invoice->status === 'quotation') {

            $submittedPaymentIds = [];

            foreach ($request->input('payment_method') as $key => $paymentMethod) {
                $payment_id = $request->input('payment_id')[$key] ?? null;
                $payment_amount = $request->input('payment_amount')[$key] ?? 0;

                $payment_method = InvoicePaymentMethod::find($payment_id) ?? new InvoicePaymentMethod();

                $payment_method->invoice_id = $id;
                $payment_method->transaction_id = $paymentMethod;
                $payment_method->payment_amount = $payment_amount;

                if (!$payment_method->exists) {
                    $payment_method->created_at = now();
                }

                $payment_method->invoice_status = 'quotation';
                $payment_method->updated_at = now();
                $payment_method->save();

                if ($payment_method->id) {
                    $submittedPaymentIds[] = $payment_method->id;
                }
            }

            // if ($request->input('payment_method')) {
            //     foreach ($request->input('payment_method') as $key => $paymentMethod) {
            //         $payment_id = $request->input('payment_id')[$key] ?? null;
            //         $payment_amount = $request->input('payment_amount')[$key] ?? 0;
            //         $payment_method = InvoicePaymentMethod::find($payment_id) ?? new InvoicePaymentMethod();
            //         $payment_method->invoice_id = $id;
            //         $payment_method->transaction_id = $paymentMethod;
            //         $payment_method->payment_amount = $payment_amount;
            //         if (!$payment_method->exists) {
            //             $payment_method->created_at = now();
            //             $payment_method->payment_date = now()->format('Y-m-d');
            //         }
            //         $payment_method->invoice_status = 'quotation';
            //         $payment_method->updated_at = now();
            //         $payment_method->save();
            //     }
            // }



            $receivable_setting = Setting::where('category', 'Receivable (Invoice)')
                ->where('branch_id', $request->location)
                ->get();

            $saleaccount_setting = Setting::where('category', 'Sale Account (Invoice)')
                ->where('branch_id', $request->location)
                ->get();
            // Update or insert receivable account records
            if ($invoice->remain_balance >= 0 && $receivable_setting->isNotEmpty()) {
                foreach ($receivable_setting as $setting) {
                    $payment_method = InvoicePaymentMethod::where('invoice_id', $id)
                        ->where('transaction_id', $setting->transaction_id)
                        ->where('status', 'receivable_account')
                        ->first();

                    if ($payment_method) {
                        // Update existing record
                        $payment_method->payment_amount = $invoice->remain_balance;
                        $payment_method->updated_at = now();
                        $payment_method->invoice_status = 'quotation';
                    } else {
                        // Insert new record
                        $payment_method = new InvoicePaymentMethod();
                        $payment_method->invoice_id = $id;
                        $payment_method->status = 'receivable_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->remain_balance;
                        $payment_method->created_at = now();
                        $payment_method->updated_at = now();
                        $payment_method->invoice_status = 'quotation';
                    }

                    $payment_method->save();
                }
            }

            // Update or insert sale account records
            if ($saleaccount_setting->isNotEmpty()) {
                foreach ($saleaccount_setting as $setting) {
                    $payment_method = InvoicePaymentMethod::where('invoice_id', $id)
                        ->where('transaction_id', $setting->transaction_id)
                        ->where('status', 'sale_account')
                        ->first();

                    if ($payment_method) {
                        // Update existing record
                        $payment_method->payment_amount = $invoice->total;
                        $payment_method->updated_at = now();
                        $payment_method->invoice_status = 'quotation';
                    } else {
                        // Insert new record
                        $payment_method = new InvoicePaymentMethod();
                        $payment_method->invoice_id = $id;
                        $payment_method->status = 'sale_account';
                        $payment_method->transaction_id = $setting->transaction_id;
                        $payment_method->payment_amount = $invoice->total;
                        $payment_method->created_at = now();
                        $payment_method->updated_at = now();
                        $payment_method->invoice_status = 'quotation';
                    }

                    $payment_method->save();
                }
            }
        }
        // dd($invoice->status);

        if ($invoice->status === 'invoice') {

            $oldQuantities = [];
            $newItems = [];
            foreach ($invoice->sells as $key => $sell) {
                $unit2 = '';
                if ($sell->GetVar) {
                    if ($sell->GetVar->unit2 == '') {
                        $unit2 = 1;
                    } elseif ($sell->GetVar->unit2 == '0') {
                        $unit2 = 1;
                    } else {
                        $unit2 = $sell->GetVar->unit2;
                    }
                } else {
                    $unit2 = 1;
                }

                if ($sell->unit == $sell->GetVar->name1) {
                    $oldQuantities[$sell->item_id] = $sell->product_qty * $unit2 ?? 1;
                } elseif ($sell->unit == $sell->GetVar->name2) {
                    $oldQuantities[$sell->item_id] = $sell->product_qty;
                } else {
                    $oldQuantities[$sell->item_id] = $sell->product_qty / $sell->GetVar->unit3;
                }
            }



            foreach ($request->input('item_id') as $key => $ItemID) {
                $item = Item::where('id', $ItemID)->where('warehouse_id', $request->warehouse[$key])->first();
                $newItems[$item->id] = $item->id;
                $item_variation = ItemVariation::where('item_id', $item->id)
                    ->where('id', $request->result_id[$key])
                    ->first();

                if (!$item || !$item_variation) {
                    continue;
                }


                if ($item_variation) {
                    $currentQuantity = (float) $item_variation->quantity;
                    if ($item_variation->name1 == $request->input('item_unit')[$key]) {
                        $newQuantity = $currentQuantity + ($oldQuantities[$ItemID] ?? 0) - (float) $request->input('product_qty')[$key] * (float) (!empty($item_variation->unit2) ? $item_variation->unit2 : 1);
                    }

                    if ($item_variation->name2 == $request->input('item_unit')[$key]) {
                        $newQuantity = $currentQuantity + ($oldQuantities[$$ItemID] ?? 0) - (float) $request->input('product_qty')[$key];
                    }

                    if ($item_variation->name3 == $request->input('item_unit')[$key]) {
                        $newQuantity = $currentQuantity + ($oldQuantities[$$ItemID] ?? 0) - (float) $request->input('product_qty')[$key] / (float) $item_variation->unit3;
                    }

                    $item_variation->quantity = $newQuantity;
                    $item_variation->save();
                }
            }

            foreach ($invoice->sells as $sell) {
                if (!in_array($sell->item_id, $newItems)) {
                    $item = Item::find($sell->item_id);
                    if ($item && $item->status === NULL) {
                        $item_variation = ItemVariation::where('item_id', $item->id)
                            ->where('id', $sell->variation_id)
                            ->first();

                        if ($item_variation->name1 == $sell->unit) {
                            $item_variation->quantity += (float)$sell->product_qty * (float) (!empty(!empty($item_variation->unit2) ? $item_variation->unit2 : 1) ? (!empty($item_variation->unit2) ? $item_variation->unit2 : 1) : 1);
                            $item_variation->save();
                        }
                        if ($item_variation->name2 == $sell->unit) {
                            $item_variation->quantity += (float)$sell->product_qty;
                            $item_variation->save();
                        }
                        if ($item_variation->name3 == $sell->unit) {
                            $conversionFactor3to2 = 1 / (!empty($item_variation->unit3) ? $item_variation->unit3 : 1);
                            $item_variation->quantity += (float)$sell->product_qty * $conversionFactor3to2;
                            $item_variation->save();
                        }
                    }
                }
            }
        }
        if ($invoice->status === 'pos') {
            $count = count($request->part_description);

            for ($i = 0; $i < $count; $i++) {
                $item = Item::where('id', $request->item_id[$i])->where('warehouse_id', $request->warehouse[$i])->first();
                if ($item && $item->status === NULL) {

                    $item_variation = ItemVariation::where('item_id', $item->id)
                        ->where('id', $request->result_id[$i])
                        ->first();

                    if ($item_variation->name1 == $request->item_unit[$i]) {
                        $unit2 = (float) (!empty($item_variation->unit2) ? $item_variation->unit2 : 1);
                        $product_qty = (float) $request->product_qty[$i];
                        $item_variation->quantity -= $product_qty * $unit2;
                        $item_variation->save();
                    }

                    if ($item_variation->name2 == $request->item_unit[$i]) {
                        $product_qty = (float) $request->product_qty[$i];
                        $item_variation->quantity -= $product_qty;
                        $item_variation->save();
                    }

                    if ($item_variation->name3 == $request->item_unit[$i]) {
                        $conversionFactor3to2 = 1 / (float) $item_variation->unit3;
                        $product_qty = (float) $request->product_qty[$i];
                        $item_variation->quantity -= $product_qty * $conversionFactor3to2;
                        $item_variation->save();
                    }
                } else if ($item && $item->status === "item_kit") {

                    $items = Item::where('id', $item->id)
                        ->where('warehouse_id', $request->warehouse[$i])
                        ->with('variations')
                        ->first();

                    if ($items && $items->variations) {
                        foreach ($items->variations as $variation) {
                            $product_qty = (float) $request->product_qty[$i];
                            $variation->quantity -= $product_qty;
                            $variation->save();
                        }
                    }

                    $counts = count($items->itemKits);
                    foreach ($items->itemKits as $kit) {
                        $itemKit = Item::where('id', $kit->item_id)
                            ->where('warehouse_id', $request->warehouse[$i])
                            ->first();

                        if ($itemKit) {
                            foreach ($kit->variations as $variation) {

                                $product_qty = (float) $request->product_qty[$i];
                                $kit_qty = (float) $kit->qty;
                                if ($variation->name1 == $kit->item_unit) {
                                    $unit2 = (float) (!empty($variation->unit2) ? $variation->unit2 : 1);
                                    $kitQuantityToSubtract = $kit_qty * $product_qty * $unit2;
                                    $variation->quantity -= $kitQuantityToSubtract;
                                    $variation->save();
                                } elseif ($variation->name2 == $kit->item_unit) {
                                    $kitQuantityToSubtract = $kit_qty * $product_qty;
                                    $variation->quantity -= $kitQuantityToSubtract;
                                    $variation->save();
                                } elseif ($variation->name3 == $kit->item_unit) {
                                    $conversionFactor3to2 = 1 / (float) $variation->unit3;
                                    $kitQuantityToSubtract = $kit_qty * $product_qty * $conversionFactor3to2;
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
        }

        $invoice_record = new InvoiceRecord();
        $invoice_record->invoice_id = $invoice->id;
        $invoice_record->invoice_no = $invoice->invoice_no;
        $invoice_record->user_id = Auth::user()->id;
        $invoice_record->warehouse_id = $request->location;
        $invoice_record->date = now();
        $invoice_record->save();

        Sell::where('invoiceid', $id)->delete();
        Sell::insert($sellsData);

        if ($invoice->status === 'quotation') {
            return redirect('quotation')->with('success', 'Quotation Update Successful');
        } elseif ($invoice->status == 'invoice') {
            return redirect('invoice')->with('success', 'Invoice Update Successful');
        } elseif ($invoice->status === 'pos') {
            return redirect('pos_manage')->with('success', 'POS Update Successful');
        }

        return redirect()->back()->with('error', 'Failed to update invoice');
    }

    public function invoice_receipt_print(Invoice $invoice)
    {
        $sells = Sell::where('invoiceid', $invoice->id)->get();
        $invoices = Invoice::where('id', $invoice->id)->get();
        $profile = UserProfile::where('branch', $invoice->location)->latest()->first();
        $items = Item::all();

        $invoice_no = Invoice::where('invoice_no', $invoice->invoice_no)->get();
        // dd($invoice_no);
        $invoiceWithSells = [];
        foreach ($invoice_no as $invoice) {
            $invoice_sells = Sell::where('invoiceid', $invoice->id)->get();
            $invoiceWithSells[] = [
                'invoice_sells' => $invoice_sells,
            ];
        }
        return view('invoice.invoice_receipt', [
            'invoice' => $invoice,
            'invoices' => $invoices,
            'items' => $items,
            'profile'  => $profile,
            'invoice_sells' => $invoice_sells,
            'invoiceWithSells' => $invoiceWithSells,
            'invoice_no' => $invoice_no
        ]);
    }

    public function change_invoice($id)
    {
        $invoice_number = '';
        $invoice = Invoice::where('status', 'invoice')
            ->orderBy('invoice_no', 'desc')
            ->first();

        $invoices = Invoice::find($id);
        foreach ($invoices->sells as $sell) {
            $item = Item::where('id', $sell->item_id)
                ->where('warehouse_id', $sell->warehouse)
                ->first();

            if ($item && $item->status === NULL) {
                $item_variation = ItemVariation::where('item_id', $item->id)
                    ->where('id', $sell->variation_id)
                    ->first();

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
            } else {
                continue;
            }
        }

        if ($invoice) {
            $invoice_number = $invoice->invoice_no;
            $number_part = (int)substr($invoice_number, 9) + 1;
        } else {
            $invoice_number = 'Invoice-';
            $number_part = (int)substr($invoice_number, 8) + 1;
        }




        $inv_number = 'Invoice - ' . $number_part;

        $quotation = Invoice::find($id);
        $setting = Setting::find('1');

        $quotation->transaction_id = $setting->transaction_id ?? null;
        $quotation->status = 'invoice';
        $quotation->invoice_no = $inv_number;
        $quotation->balance_due = "Invoice";
        $quotation->invoice_date = Carbon::today()->format('Y-m-d');
        $quotation->update();

        $receivable_setting = Setting::where('category', 'Receivable (Invoice)')
            ->where('branch_id', $quotation->location)
            ->get();

        $saleaccount_setting = Setting::where('category', 'Sale Account (Invoice)')
            ->where('branch_id', $quotation->location)
            ->get();

        $invoice_setting = Setting::where('category', 'Invoice')
            ->where('branch_id', $quotation->location)
            ->get();


        if ($invoice_setting->isNotEmpty()) {
            foreach ($invoice_setting as $setting) {
                $payment_methods = InvoicePaymentMethod::where('invoice_id', $id)
                    ->where('transaction_id', $setting->transaction_id)
                    ->whereNull('status')
                    ->get();

                foreach ($payment_methods as $payment_method) {
                    $payment_method->invoice_status = 'invoice';
                    $payment_method->save();
                }
            }
        }

        if ($saleaccount_setting->isNotEmpty()) {
            foreach ($saleaccount_setting as $setting) {
                $payment_methods = InvoicePaymentMethod::where('invoice_id', $id)
                    ->where('transaction_id', $setting->transaction_id)
                    ->where('status', 'sale_account')
                    ->get();

                foreach ($payment_methods as $payment_method) {
                    $payment_method->invoice_status = 'invoice';
                    $payment_method->save();
                }
            }
        }

        if ($receivable_setting->isNotEmpty()) {
            foreach ($receivable_setting as $setting) {
                $payment_methods = InvoicePaymentMethod::where('invoice_id', $id)
                    ->where('transaction_id', $setting->transaction_id)
                    ->where('status', 'receivable_account')
                    ->get();

                foreach ($payment_methods as $payment_method) {
                    $payment_method->invoice_status = 'invoice';
                    $payment_method->save();
                }
            }
        }



        return redirect('/invoice')->with('status', 'Change Invoice Successful!');
    }


    //Edit By Farrooq
    public function autocompletePartCodeInvoice(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');

        $cacheKey = "autocomplete_{$query}_{$location}";
        $items = Cache::remember($cacheKey, 60, function () use ($query, $location) {
            $itemIds = Item::where('item_name', 'like', '%' . $query . '%')
                ->where('warehouse_id', $location)
                ->pluck('id');

            return ItemVariation::whereIn('item_id', $itemIds)
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
        });

        return response()->json($items);
    }



    public function getPartDataInvoice(Request $request)
    {
        try {
            $itemName = $request->result_item_name;
            $description = $request->result_descriptions;
            // $productCode = $request->result_product_code;
            $expiredDate = $request->result_expired_date;
            $location = $request->location;
            $id = $request->item_id;
            $cacheKey = "part_data_{$itemName}_{$description}_{$expiredDate}_{$location}_{$id}";
            $result = Cache::remember($cacheKey, 60, function () use ($itemName, $description, $location, $expiredDate, $id) {
                $item = Item::where('item_name', $itemName)
                    ->where('warehouse_id', $location)
                    ->first();

                if ($item) {
                    $variation = ItemVariation::where('item_id', $item->id)
                        ->where('descriptions', $description)
                        // ->where('product_code', $productCode)
                        ->where('expired_date', $expiredDate)
                        ->where('id', $id)
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



    //POS
    public function autocompletePartCode(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');

        $cacheKey = "autocomplete_items_{$location}_{$query}";

        $items = Cache::remember($cacheKey, 60, function () use ($query, $location) {
            $itemIds = Item::where('item_name', 'like', '%' . $query . '%')
                ->where('warehouse_id', $location)
                ->pluck('id');

            return ItemVariation::whereIn('item_id', $itemIds)
                ->get(['item_id', 'descriptions', 'id', 'expired_date'])
                ->map(function ($variation) {
                    $item = Item::find($variation->item_id);
                    return [
                        'item_name' => $item ? $item->item_name : 'Unknown Item',
                        'description' => $variation->descriptions,
                        // 'product_code' => $variation->product_code,
                        'id' => $variation->id,
                        'item_id' => $variation->item_id,
                        'expired_date' => $variation->expired_date,
                    ];
                });
        });

        return response()->json($items);
    }

    public function autocompleteBarCode(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');

        $barcodeResults = Cache::remember("autocomplete_barcode_{$query}_{$location}", 60, function () use ($query, $location) {
            return ItemVariation::where('variations_barcode', 'like', '%' . $query . '%')
                ->whereHas('item', function ($query) use ($location) {
                    $query->where('warehouse_id', $location);
                })
                ->with('item')
                ->get()
                ->map(function ($variation) {
                    $item = $variation->item;
                    return [
                        'item_name' => $item ? $item->item_name : 'Unknown Item',
                        'variations_barcode' => $variation->variations_barcode,
                        'description' => $variation->descriptions,
                        'product_code' => $variation->product_code,
                        'id' => $variation->id,
                        'item_id' => $variation->item_id,
                        'expired_date' => $variation->expired_date,
                    ];
                });
        });

        return response()->json($barcodeResults);
    }


    public function getPartData(Request $request)
    {
        info($request);
        $itemName = $request->itemname;
        $location = $request->location;
        $description = $request->description;
        // $productCode = $request->product_code;
        $expiredDate = $request->expired_date;
        $item_id = $request->item_id;
        $cacheKey = "part_data_{$itemName}_{$location}_{$description}_{$expiredDate}_{$item_id}";

        $itemData = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($itemName, $location, $description,  $expiredDate, $item_id) {
            $item = Item::where('item_name', $itemName)
                ->where('warehouse_id', $location)
                ->first();

            if ($item) {
                $variations = ItemVariation::where('item_id', $item->id)
                    ->where('descriptions', $description)
                    // ->where('product_code', $productCode)
                    ->where('expired_date', $expiredDate)
                    ->where('id', $item_id)
                    ->get();

                return [
                    'item' => $item,
                    'variations' => $variations,
                ];
            }

            return null;
        });

        if (!$itemData) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $resdata = [
            'item' => [
                'item_name' => $itemData['item']->item_name,
                'id' => $itemData['item']->id,
                'item_unit' => $itemData['item']->item_unit,
                'warehouse_id' => $itemData['item']->warehouse_id,
            ],
            'variations' => $itemData['variations']->map(function ($variation) {
                return [
                    'descriptions' => $variation->descriptions,
                    'variations_barcode' => $variation->variations_barcode,
                    'id' => $variation->id,
                    'product_code' => $variation->product_code,
                    'expired_date' => $variation->expired_date,
                    'buy_price' => $variation->buy_price,
                    'quantity' => $variation->quantity,
                    'reorder_level_stock' => $variation->reorder_level_stock,
                    'name1' => $variation->name1,
                    'name2' => $variation->name2,
                    'name3' => $variation->name3,
                    'unit1' => $variation->unit1,
                    'unit2' => $variation->unit2,
                    'unit3' => $variation->unit3,
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
            }),
        ];

        return response()->json($resdata);
    }


    // public function getBarcodeData(Request $request)
    // {
    //     $cacheKey = 'barcode_data_' . $request->barcode . '_' . $request->location;

    //     $item = Cache::remember($cacheKey, 60, function () use ($request) {
    //         return Item::where('barcode', $request->barcode)
    //             ->where('warehouse_id', $request->location)
    //             ->orderBy('created_at', 'desc')
    //             ->withoutTrashed()
    //             ->first();
    //     });

    //     if (!$item) {
    //         return response()->json(['error' => 'Product not found'], 404);
    //     }

    //     $resdata = [
    //         'item' => $item,
    //     ];

    //     return response()->json($resdata);
    // }
    public function getBarcodeData(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'location' => 'required|integer',
        ]);

        $barcode = $request->barcode;
        $location = $request->location;

        $cacheKey = "variations_data_{$barcode}_{$location}";

        $variationsData = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($barcode, $location) {
            return ItemVariation::where('variations_barcode', $barcode)
                ->join('items', 'item_variations.item_id', '=', 'items.id')
                ->where('items.warehouse_id', $location)
                ->select('item_variations.*', 'items.item_name', 'items.item_unit', 'items.warehouse_id')
                ->get();
        });

        if ($variationsData->isEmpty()) {
            return response()->json(['error' => 'No variations found for this barcode'], 404);
        }

        $resdata = [
            'item' => [
                'item_name' => $variationsData->first()->item_name,
                'id' => $variationsData->first()->item_id,
                'item_unit' => $variationsData->first()->item_unit,
                'warehouse_id' => $variationsData->first()->warehouse_id,
            ],
            'variations' => $variationsData->map(function ($variation) {
                return [
                    'descriptions' => $variation->descriptions,
                    'variations_barcode' => $variation->variations_barcode,
                    'id' => $variation->id,
                    'product_code' => $variation->product_code,
                    'expired_date' => $variation->expired_date,
                    'buy_price' => $variation->buy_price,
                    'quantity' => $variation->quantity,
                    'reorder_level_stock' => $variation->reorder_level_stock,
                    'name1' => $variation->name1,
                    'name2' => $variation->name2,
                    'name3' => $variation->name3,
                    'unit1' => $variation->unit1,
                    'unit2' => $variation->unit2,
                    'unit3' => $variation->unit3,
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
            }),
        ];

        // Return the JSON response
        return response()->json($resdata);
    }
    // End Edit By Farrooq

    public function invoice_detail(Invoice $invoice)
    {
        $sells = Sell::where('invoiceid', $invoice->id)->get();
        $invoices = Invoice::where('id', $invoice->id)->get();
        $profile = UserProfile::where('branch', $invoice->location)->latest()->first();
        $items = Item::all();


        if ($invoice->status === 'pos') {
            $invoice_no = Invoice::where('invoice_no', $invoice->invoice_no)->get();
            // dd($invoice_no);
            $invoiceWithSells = [];
            foreach ($invoice_no as $invoice) {
                $invoice_sells = Sell::where('invoiceid', $invoice->id)->get();
                $invoiceWithSells[] = [
                    'invoice_sells' => $invoice_sells,
                ];
            }
            return view('pos.pos_detail', [
                'invoice' => $invoice,
                'invoices' => $invoices,
                'items' => $items,
                'profile'  => $profile,
                'invoice_sells' => $invoice_sells,
                'invoiceWithSells' => $invoiceWithSells,
                'invoice_no' => $invoice_no
            ]);
        } elseif ($invoice->status === 'invoice') {
            $invoice_no = Invoice::where('invoice_no', $invoice->invoice_no)->get();
            $invoiceWithSells = [];
            foreach ($invoice_no as $invoice) {
                $invoice_sells = Sell::where('invoiceid', $invoice->id)->get();
                $invoiceWithSells[] = [
                    'invoice_sells' => $invoice_sells,
                ];
            }
            // dd($invoice_no->sum('net_total'));


            return view('invoice.invoice_detail', [
                'invoice' => $invoice,
                'invoices' => $invoices,
                'items' => $items,
                'profile'  => $profile,
                'invoice_sells' => $invoice_sells,
                'invoiceWithSells' => $invoiceWithSells,
                'invoice_no' => $invoice_no
            ]);
        } elseif ($invoice->status === 'quotation') {
            return view('quotation.quotation_detail', [
                'invoice' => $invoice,
                'invoices' => $invoices,
                'sells' => $sells,
                'items' => $items,
                'profile' => $profile,
            ]);
        }
    }
    public function do_detail(Invoice $invoice)
    {
        $sells = Sell::where('invoiceid', $invoice->id)->get();
        $invoices = Invoice::where('id', $invoice->id)->get();
        $profile = UserProfile::where('branch', $invoice->location)->latest()->first();
        $items = Item::all();



        $invoice_no = Invoice::where('invoice_no', $invoice->invoice_no)->get();
        $invoiceWithSells = [];
        foreach ($invoice_no as $invoice) {
            $invoice_sells = Sell::where('invoiceid', $invoice->id)->get();
            $invoiceWithSells[] = [
                'invoice_sells' => $invoice_sells,
            ];
        }
        // dd($invoice_no->sum('net_total'));


        return view('invoice.do_form', [
            'invoice' => $invoice,
            'invoices' => $invoices,
            'items' => $items,
            'profile'  => $profile,
            'invoice_sells' => $invoice_sells,
            'invoiceWithSells' => $invoiceWithSells,
            'invoice_no' => $invoice_no
        ]);
    }


    public function invoice_daily_sales()
    {

        $daily_invoices = Invoice::whereDate('invoice_date', Carbon::today())->where('status', 'invoice')->orderBy('invoice_no', 'desc')->get();

        $total = $daily_invoices->sum('total');

        return view('invoice.daily_sales', compact('daily_invoices', 'total',));
    }
    public function invoice_daily_sale_search(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $daily_invoices = Invoice::whereDate('invoice_date', '>=', $start_date)
            ->whereDate('invoice_date', '<=', $end_date)->where('status', 'invoice')->orderBy('invoice_no', 'desc')->get();

        $total = $daily_invoices->sum('total');

        return view('invoice.daily_sales', compact('daily_invoices', 'total',));
    }
    public function pos_daily_sales()
    {

        $daily_pos = Invoice::whereDate('invoice_date', Carbon::today())->where('status', 'pos')->get();

        $total = $daily_pos->sum('total');

        return view('pos.daily_sales', compact('daily_pos', 'total'));
    }
    public function pos_daily_sale_search(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $daily_pos = Invoice::whereDate('invoice_date', '>=', $start_date)
            ->whereDate('invoice_date', '<=', $end_date)->where('status', 'pos')->get();

        $total = $daily_pos->sum('total');

        return view('pos.daily_sales', compact('daily_pos', 'total'));
    }

    public function sale_return()
    {

        return view('invoice.sale_return');
    }


    public function invoice_get_transaction(Request $request)
    {
        $category = ($request->balance_due === "Invoice" || $request->balance_due === null)
            ? "Invoice"
            : "Po Return";


        $settings = Setting::where('branch_id', $request->locationId)
            ->where('category', $category)
            ->pluck('transaction_id'); // Get only transaction IDs
        info($settings);

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
}
