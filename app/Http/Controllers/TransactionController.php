<?php

namespace App\Http\Controllers;


use App\Models\Account;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePaymentMethod;
use App\Models\InvoiceReturnPaymentMethod;
use App\Models\OtherIncome;
use App\Models\Payment;
use App\Models\Warehouse;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function getTransactionsByLocation(Request $request)
    {

        $locationId = $request->locationId;
        $transactions = Transaction::where('location', $locationId)->get(['id', 'transaction_name']);
        return response()->json($transactions);
    }
    // public function transaction($branch = null)
    // {
    //     $branches = Warehouse::latest()->get();
    //     $account = Account::all();
    //     $transaction = Transaction::with('account')->latest()->get();

    //     $invoiceAmountsByAccount = [];
    //     $refundAmountsByAccount = [];
    //     $usd_invoiceAmountsByAccount = [];
    //     $usd_refundAmountsByAccount = [];

    //     $account = Account::all();

    //     if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
    //         if ($branch) {
    //             $transactions = Transaction::with('account')->where('location', $branch)->get();
    //         } else {
    //             $transactions = Transaction::with('account')->latest()->get();
    //         }
    //     } else {
    //         $transactions = Transaction::with('account')->where('location', auth()->user()->level)->get();
    //     }
    //     if (Auth::user()->is_admin == '1' || auth::user()->level == 'Admin') {
    //         foreach ($transactions as $transaction) {
    //             $total_invoice = 0;
    //             $total_refund = 0;
    //             // $usd_total_invoice = 0;
    //             // $usd_total_refund = 0;
    //             $currentSumByIn = Payment::where('payment_status', 'in')

    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('amount');



    //             $total_invoice += $currentSumByIn;
    //             $currentSumByOut = Payment::where('payment_status', 'out')

    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('amount');

    //             $total_refund += $currentSumByOut;
    //             $invoice_amount  = Invoice::where('status', 'invoice')
    //                 ->where('balance_due', 'Invoice')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->where('ar_transaction_id', null)
    //                 ->sum('total');
    //             $invoice_amount_ar = Invoice::where('status', 'invoice')
    //                 ->where('balance_due', 'Invoice')

    //                 ->whereNotNull('transaction_id')
    //                 ->where('ar_transaction_id', $transaction->id)
    //                 ->sum('remain_balance');
    //             $invoice_amount_deposit = Invoice::where('status', 'invoice')
    //                 ->where('balance_due', 'Invoice')

    //                 ->whereNotNull('ar_transaction_id')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('deposit');

    //             // Directly sum the net_total

    //             $pos_amount  = Invoice::where('status', 'pos')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('total');


    //             $refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
    //                 ->sum('total');

    //             $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('total');


    //             $exchange_return = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Sale Return Invoice')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('total');
    //             $total_refund +=  $exchange_order + $exchange_return;
    //             $total_invoice += $invoice_amount + $pos_amount  + $refund_amount + $invoice_amount_ar + $invoice_amount_deposit;
    //             $invoiceAmountsByAccount[$transaction->transaction_name] = $total_invoice;
    //             $refundAmountsByAccount[$transaction->transaction_name] = $total_refund;
    //         }
    //     } else {
    //         foreach ($transactions as $transaction) {
    //             $total_invoice = 0;
    //             $total_refund = 0;
    //             // $usd_total_invoice = 0;
    //             // $usd_total_refund = 0;
    //             $currentSumByIn = Payment::where('payment_status', 'in')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('amount');



    //             $total_invoice += $currentSumByIn;
    //             $currentSumByOut = Payment::where('payment_status', 'out')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('amount');

    //             $total_refund += $currentSumByOut;
    //             $invoice_amount  = Invoice::where('status', 'invoice')
    //                 ->where('balance_due', 'Invoice')
    //                 ->where('location', Auth::user()->level)
    //                 ->where('transaction_id', $transaction->id)
    //                 ->where('ar_transaction_id', null)
    //                 ->sum('total');
    //             $invoice_amount_ar = Invoice::where('status', 'invoice')
    //                 ->where('balance_due', 'Invoice')
    //                 ->where('location', Auth::user()->level)
    //                 ->whereNotNull('transaction_id')
    //                 ->where('ar_transaction_id', $transaction->id)
    //                 ->sum('remain_balance');
    //             $invoice_amount_deposit = Invoice::where('status', 'invoice')
    //                 ->where('balance_due', 'Invoice')
    //                 ->where('location', Auth::user()->level)
    //                 ->whereNotNull('ar_transaction_id')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->sum('deposit');

    //             // Directly sum the net_total

    //             $pos_amount  = Invoice::where('status', 'pos')
    //                 ->where('transaction_id', $transaction->id)
    //                 ->where('location', Auth::user()->level)
    //                 ->sum('total');


    //             $refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)->where('location', Auth::user()->level)
    //                 ->sum('total');

    //             $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
    //                 ->where('transaction_id', $transaction->id)->where('unit', Auth::user()->level)
    //                 ->sum('total');

    //             $exchange_return = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Sale Return Invoice')->where('transaction_id', $transaction->id)
    //                 ->where('unit', Auth::user()->level)
    //                 ->sum('total');
    //             $total_refund +=
    //                 $exchange_order + $exchange_return;
    //             $total_invoice += $invoice_amount + $pos_amount + $refund_amount;
    //             $invoiceAmountsByAccount[$transaction->transaction_name] = $total_invoice;
    //             $refundAmountsByAccount[$transaction->transaction_name] = $total_refund;
    //         }
    //     }
    //     $branch_drop = Warehouse::all();
    //     $branchNames = $branch_drop->pluck('name', 'id');

    //     $currentBranchName = $branch ? $branchNames[$branch] : 'All Accounts';
    //     return view('transaction.transaction', compact('branches', 'account', 'transactions', 'invoiceAmountsByAccount', 'refundAmountsByAccount', 'currentBranchName', 'branch_drop'));
    // }

    public function transaction($branch = null)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        $account = Account::all();
        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            if ($branch) {
                $transactions = Transaction::with('account')->where('location', $branch)->get();
            } else {
                $transactions = Transaction::with('account')->latest()->get();
            }
            $branches = Warehouse::all();
        } else {
            $branches = Warehouse::whereIn('id', $warehousePermission)->get();
            $transactions = Transaction::with('account')->whereIn('location', $warehousePermission)->get();
        }
        // $transaction = Transaction::with('account')->latest()->get();
        $sumByIn = Payment::where('payment_status', 'IN')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');

        $sumByOut = Payment::where('payment_status', 'OUT')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');

        $invoice_payment_method = InvoicePaymentMethod::groupBy('transaction_id')
            ->where('invoice_status', 'invoice')
            ->selectRaw('transaction_id, SUM(payment_amount) as amount')
            ->pluck('amount', 'transaction_id');

        $return_payment_method = InvoiceReturnPaymentMethod::groupBy('transaction_id')
            ->where('invoice_status', 'sale_return_invoice')
            ->selectRaw('transaction_id, SUM(payment_amount) as amount')
            ->pluck('amount', 'transaction_id');

        $po_payment_method = PurchaseOrderPaymentMethod::groupBy('transaction_id')
            ->where('po_status', 'po')
            ->selectRaw('transaction_id, SUM(payment_amount) as amount')
            ->pluck('amount', 'transaction_id');

        $expense = Expense::groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');



        // $purchase_orders_payment_methods = PurchaseOrderPaymentMethod::groupBy('payment_method')
        //     ->selectRaw('payment_method, SUM(payment_amount) as payment_amount')
        //     ->pluck('payment_amount', 'payment_method');

        // $sale_return_payment_methods = PurchaseOrderPaymentMethod::groupBy('payment_method')
        //     ->selectRaw('payment_method, SUM(payment_amount) as payment_amount')
        //     ->pluck('payment_amount', 'payment_method');

        // $expense = Expense::groupBy('transaction_id')
        //     ->selectRaw('transaction_id, SUM(amount) as total')
        //     ->pluck('total', 'transaction_id');


        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Accounts';
        return view('transaction.transaction', compact('account', 'transactions',  'branches', 'branch_drop', 'currentBranchName', 'invoice_payment_method', 'sumByIn', 'sumByOut', 'expense', 'po_payment_method', 'return_payment_method'));
    }

    public function getAccountsByLocation(Request $request)
    {

        $locationId = $request->locationId;
        $accounts = Account::where('location', $locationId)->get(['id', 'account_name']);
        return response()->json($accounts);
    }

    public function register(Request $request)
    {
        // $existingTransaction = Transaction::where('account_id', $request->input('account_id'))
        //     ->where('location', $request->input('location'))
        //     ->first();

        // if ($existingTransaction) {
        //     return redirect()->back()->with('error', 'Transaction already exists for this location and account.');
        // }

        $request->validate([

            "transaction_name" => "required",

            "account_id" => "required",


        ], ["account_id" => "The account name is required."]);


        $trasaction = new Transaction();
        $trasaction->transaction_code = $request->transaction_code;
        $trasaction->transaction_name = $request->transaction_name;
        $trasaction->location = $request->location;
        $trasaction->description = $request->description;
        $account = Account::find($request->input('account_id'));

        $account->transaction()->save($trasaction);
        return redirect()->back()->with("success", "Transaction Register is Successfull");
    }

    public function delete($id)
    {
        $hasInvoices = InvoicePaymentMethod::where('transaction_id', $id)
            ->whereNull('deleted_at')
            ->exists();
        $hasPurchaseOrders = PurchaseOrderPaymentMethod::where('transaction_id', $id)
            ->whereNull('deleted_at')
            ->exists();

        $hasExpenses = Expense::where('transaction_id', $id)
            ->whereNull('deleted_at')
            ->exists();

        if ($hasInvoices  || $hasExpenses || $hasPurchaseOrders) {
            return redirect('transaction')->with('error', 'Transaction cannot be deleted because there are existing related records.');
        }
        Transaction::find($id)->delete();
        return redirect()->back()->with("deleteStatus", "Transaction Delete is Successfull");
    }

    public function show($id)
    {
        $accounts = Account::all();
        $transaction = Transaction::find($id);
        $branches = Warehouse::latest()->get();
        return view('transaction.transactionEdit', compact('accounts', 'transaction', 'branches'));
    }

    public function update(Request $request, $id)
    {

        // $existingTransaction = Transaction::where('account_id', $request->input('account_id'))
        //     ->where('location', $request->input('location'))
        //     ->where('id', '!=', $id)
        //     ->first();

        // if ($existingTransaction) {
        //     return redirect('/transactionManagement')->with('error', 'Duplicate transaction found for the same account and location.');
        // }

        $transaction = Transaction::find($id);


        if ($transaction->account_id != $request->input('account_id')) {
            $transaction->account()->associate(Account::find($request->input('account_id')));
        }
        $transaction->update([

            'transaction_name' => $request->input('transaction_name'),
            'location' => $request->input('location'),


        ]);
        return redirect('transaction')->with('updateStatus', 'Transaction Update is Successfull');
    }
    // public function payment($id)
    // {

    //     $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

    //     if (auth()->user()->is_admin == '1') {
    //         $accounts = Account::all();
    //     } else {
    //         $accounts = Account::whereIn('location', $warehousePermission)->get();
    //     }
    //     $transaction = Transaction::find($id);
    //     $transactions = Transaction::with('account')->latest()->get();


    //     $invoice_make_payments = InvoicePaymentMethod::where('transaction_id', $transaction->id)
    //         ->where('invoice_status', 'invoice')
    //         ->get();


    //     $make_payments = InvoicePaymentMethod::where('transaction_id', $transaction->id)
    //         ->where('invoice_status', 'invoice')
    //         ->get();

    //     $purchase_order_payment_method = PurchaseOrderPaymentMethod::where('transaction_id', $transaction->id)
    //         ->get();

    //     $expenses = Expense::where('transaction_id', $transaction->id)->get();

    //     $sumByIn = Payment::where('payment_status', 'IN')
    //         ->groupBy('transaction_id')
    //         ->selectRaw('transaction_id, SUM(amount) as total')
    //         ->pluck('total', 'transaction_id');
    //     $sumByOut = Payment::where('payment_status', 'OUT')
    //         ->groupBy('transaction_id')
    //         ->selectRaw('transaction_id, SUM(amount) as total')
    //         ->pluck('total', 'transaction_id');
    //     $diff = collect($sumByIn)->map(function ($totalIn, $transactionId) use ($sumByOut) {
    //         $totalOut = $sumByOut->get($transactionId, 0);

    //         return $totalIn - $totalOut;
    //     });

    //     $total_invoice = $make_payments->sum('payment_amount');
    //     $total_po = $purchase_order_payment_method->sum('payment_amount');
    //     $total_expense = $expenses->sum('amount');


    //     $warehouses = Warehouse::all();
    //     $payment = Payment::where('transaction_id', $id)->get();
    //     return view('transaction.transaction_payment', compact('accounts', 'transaction', 'payment', 'warehouses', 'transactions', 'diff', 'total_invoice',  'invoice_make_payments', 'make_payments', 'total_po', 'purchase_order_payment_method', 'total_expense', 'expenses'));
    // }

    public function payment($id)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $accounts = Account::all();
        } else {
            $accounts = Account::whereIn('location', $warehousePermission)->get();
        }

        $transaction = Transaction::find($id);
        $transactions = Transaction::with('account')->latest()->get();

        $invoice_make_payments = InvoicePaymentMethod::where('transaction_id', $transaction->id)
            ->where('invoice_status', 'invoice')
            ->where('payment_amount', '>', 0) // filter 0 amount
            ->get();

        $return_make_payments = InvoiceReturnPaymentMethod::where('transaction_id', $transaction->id)
            ->where('invoice_status', 'sale_return_invoice')
            ->where('payment_amount', '>', 0) // filter 0 amount
            ->get();

        $make_payments = InvoicePaymentMethod::where('transaction_id', $transaction->id)
            ->where('invoice_status', 'invoice')
            ->where('payment_amount', '>', 0) // filter 0 amount
            ->get();

        $purchase_order_payment_method = PurchaseOrderPaymentMethod::where('transaction_id', $transaction->id)
            ->where('payment_amount', '>', 0) // filter 0 amount
            ->get();

        $expenses = Expense::where('transaction_id', $transaction->id)
            ->where('amount', '>', 0) // filter 0 amount
            ->get();

        $sumByIn = Payment::where('payment_status', 'IN')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');

        $sumByOut = Payment::where('payment_status', 'OUT')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');

        $diff = collect($sumByIn)->map(function ($totalIn, $transactionId) use ($sumByOut) {
            $totalOut = $sumByOut->get($transactionId, 0);
            return $totalIn - $totalOut;
        });

        $total_invoice = $make_payments->sum('payment_amount');
        $total_po = $purchase_order_payment_method->sum('payment_amount');
        $total_expense = $expenses->sum('amount');
        $total_return = $return_make_payments->sum('payment_amount');

        $warehouses = Warehouse::all();
        $payment = Payment::where('transaction_id', $id)
            ->where('amount', '>', 0) // filter 0 amount
            ->get();

        return view('transaction.transaction_payment', compact(
            'accounts',
            'transaction',
            'payment',
            'warehouses',
            'transactions',
            'diff',
            'total_invoice',
            'invoice_make_payments',
            'make_payments',
            'total_po',
            'purchase_order_payment_method',
            'total_expense',
            'expenses',
            'return_make_payments',
            'total_return'
        ));
    }

    public function payment_register(Request $request, $id)
    {
        $data = new Payment();
        $data->fill($request->all());
        $data->save();

        return redirect()->back()->with('success', 'Payment created successfully');
    }
    // public function payment_delete($id)
    // {


    //     $delete = payment::find($id);
    //     $delete->delete();
    //     return back()->with('deleteStatus', 'Payment Delete Successful');
    // }
    public function payment_edit($id)
    {
        $show = Payment::find($id);

        return view('transaction.transaction_payment_edit', compact('show'));
    }
    public function payment_update(Request $request, $id)
    {
        $update = Payment::find($id);
        $update->payment_status = $request->input('payment_status');
        $update->amount = $request->input('amount');
        $update->note = $request->input('note');
        $update->save();
        return redirect(url('payment', $update->transaction_id))->with('updateStatus', 'Payment Update Successful');
    }
}
