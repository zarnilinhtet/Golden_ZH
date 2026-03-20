<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function payment_register(Request $request)
    {
        $data = new Payment();
        $data->fill($request->all());
        $data->save();

        if ($request->opening_balance == null) {
            $account = Account::find($request->transfer_account_id);

            if ($account) {
                $transaction = Transaction::where('account_id', $account->id)->first();


                if (!$transaction) {
                    return redirect()->back()->with('error', 'Transaction not found.');
                }
                $payment = new Payment();
                if ($payment) {
                    $payment->transaction_id = $transaction->id;
                    $payment->voucher_no = $request->voucher_no;
                    $payment->receiver_name = $request->receiver_name;
                    $payment->reference_no = $request->reference_no;
                    $payment->invoice_no = $request->invoice_no;
                    $payment->account_id = $account->id;
                    $payment->transfer_account_id = $data->account_id;
                    $payment->payment_date = $data->payment_date;
                    $payment->payment_status = ($request->payment_status === 'IN') ? 'OUT' : 'IN';
                    $payment->amount = $request->amount;
                    $payment->note = $request->note;
                    $payment->save();
                }
            }

            return redirect()->back()->with('success', 'Payment created successfully.');
        } else {
            return redirect()->back()->with('success', 'Opening balance created successfully.');
        }
    }
    // Direct finance Make payment Page
    public function makePaymentEdit($id)
    {
        $show = Payment::find($id);

        return view('transaction.makePaymentEdit', compact('show'));
    }
    public function paymentUpdate(Request $request, $id)
    {
        $update = Payment::find($id);
        $update->payment_status = $request->input('payment_status');
        $update->amount = $request->input('amount');
        $update->transfer_account_id = $request->input('transfer_account_id');
        $update->note = $request->input('note');
        $update->payment_date = $request->input('payment_date');
        $update->receiver_name = $request->input('receiver_name');
        $update->reference_no = $request->input('reference_no');
        $update->invoice_no = $request->invoice_no;


        if ($update->opening_balance == null) {
            $account_old = Account::find($update->transfer_account_id);
            if ($account_old) {
                $transaction_old = Transaction::where('account_id', $account_old->id)->first();
                if ($transaction_old) {

                    $payment_old = Payment::where('transaction_id', $transaction_old->id)
                        ->where('voucher_no', $update->voucher_no)
                        ->first();

                    if ($payment_old) {
                        $payment_old->delete_status = 'edit_delete';
                        $payment_old->save();
                        $payment_old->delete();
                    }
                } else {
                    return redirect(url('payment', $update->transaction_id))->with('error', 'Transaction not found.');
                }
            }

            $account = Account::find($request->transfer_account_id);

            if ($account) {
                $transaction = Transaction::where('account_id', $account->id)
                    ->first();
                // dd($account);

                if (!$transaction) {
                    return redirect()->back()->with('error', 'Transaction not found.');
                }

                $new_payment = new Payment();
                $new_payment->transaction_id = $transaction->id;
                $new_payment->account_id = $account->id;
                $new_payment->transfer_account_id = $update->account_id;
                $new_payment->voucher_no = $update->voucher_no;
                $new_payment->receiver_name = $update->receiver_name;
                $new_payment->reference_no = $update->reference_no;
                $new_payment->payment_date = $update->payment_date;
                $new_payment->payment_status = ($request->payment_status === 'IN') ? 'OUT' : 'IN';
                $new_payment->amount = $request->amount;
                $new_payment->note = $request->note;
                $new_payment->invoice_no = $request->invoice_no;
                $new_payment->save();
            }
            $update->save();
            return redirect(url('payment', $update->transaction_id))->with('updateStatus', 'Payment Update Successful');
        } else {
            $update->save();
            return redirect(url('payment', $update->transaction_id))->with('updateStatus', 'Opening Balance Update Successful');
        }
    }

    public function paymentDelete($voucher_no)
    {
        Payment::where('voucher_no', $voucher_no)->delete();
        return redirect()->back()->with('deleteStatus', 'Payment Deleted Successful!');
    }

    public function paymentDeleteId($id)
    {
        Payment::where('id', $id)->delete();
        return redirect()->back()->with('deleteStatus', 'Payment Deleted Successful!');
    }
    public function account_invoice_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transaction = Transaction::find($id);
        $invoices = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'invoices' => $invoices,
                'warehouses' => $warehouses
            ]);
        }

        return view('transaction.transaction_payment', compact('transaction', 'invoices', 'warehouses'));
    }

    public function account_po_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $transaction = Transaction::find($id);

        $purchase_orders = PurchaseOrder::where('transaction_id', $transaction->id)
            ->where('balance_due', 'Purchase Order')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'purchase_orders' => $purchase_orders,
                'warehouses' => $warehouses,
            ]);
        }

        return view('transaction.transaction_payment', compact('transaction', 'purchase_orders', 'warehouses', 'startDate', 'endDate'));
    }



    public function account_pos_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $transaction = Transaction::find($id);

        $point_of_sales = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'pos')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'point_of_sales' => $point_of_sales,
                'warehouses' => $warehouses,
            ]);
        }
        return view('transaction.transaction_payment', compact('transaction', 'point_of_sales', 'warehouses', 'startDate', 'endDate'));
    }



    public function purchase_return_invoice_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transaction = Transaction::find($id);

        $po_returns = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'invoice')
            ->where('balance_due', 'Po Return')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'po_returns' => $po_returns,
                'warehouses' => $warehouses,
            ]);
        }

        return view('transaction.transaction_payment', compact('transaction', 'po_returns', 'warehouses', 'startDate', 'endDate'));
    }


    public function sale_return_invoice_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transaction = Transaction::find($id);
        $sale_return_invoices = PurchaseOrder::where('transaction_id', $transaction->id)
            ->where('balance_due', 'Sale Return Invoice')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'sale_return_invoices' => $sale_return_invoices,
                'warehouses' => $warehouses
            ]);
        }

        return view('transaction.transaction_payment', compact('transaction', 'sale_return_invoices', 'warehouses', 'startDate', 'endDate'));
    }


    public function getVoucherNo()
    {
        // Retrieve the latest voucher number where voucher_no is not null and not deleted
        $latest_payment = Payment::whereNotNull('voucher_no')
            ->whereNull('deleted_at')
            ->where('voucher_no', 'like', 'Voucher-%') // Ensure it follows the correct format
            ->orderByRaw("CAST(SUBSTRING_INDEX(voucher_no, '-', -1) AS UNSIGNED) DESC") // Extract number and sort
            ->first();

        // Extract and increment the number
        if ($latest_payment && preg_match('/Voucher-(\d+)/', $latest_payment->voucher_no, $matches)) {
            $latest_number = intval($matches[1]);
            $new_voucher_no = 'Voucher-' . ($latest_number + 1);
        } else {
            $new_voucher_no = 'Voucher-1'; // Default if no records exist
        }

        return response()->json([
            'voucher_no' => $new_voucher_no
        ]);
    }

    public function delete_record_payment($id)
    {
        $payments = Payment::onlyTrashed()
            ->where('transaction_id', $id)
            ->whereNull('edit_delete')
            ->get();

        return view('transaction.makePayment_delete_record', compact('payments', 'id'));
    }

    public function restore_payment($id)
    {
        $payment = Payment::onlyTrashed()
            ->where('id', $id)
            ->first();

        if ($payment) {
            $payment->restore(); // Restore the record

            return redirect(url('payment', $payment->transaction_id))
                ->with('success', 'Payment Restored successfully.');
        } else {
            return redirect()->back()->with('error', 'No payments found to restore.');
        }
    }
}
