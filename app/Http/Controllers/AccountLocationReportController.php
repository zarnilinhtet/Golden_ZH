<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class AccountLocationReportController extends Controller
{
    public function index()
    {

        if (auth()->user()->is_admin == '1') {
            $warehouses = Warehouse::latest()->get();
        } else {
            $warehouses = Warehouse::where('id', auth()->user()->level)->get();
        }
        return view('report.account_location.account_location_report', compact('warehouses'));
    }

    public function account_location_all_report($id)
    {

        $warehouse = Warehouse::find($id);
        return view('report.account_location.account_location_all_report', compact('id', 'warehouse'));
    }

    public function general_ledger($id)
    {

        $invoiceAmountsByAccount = [];
        $refundAmountsByAccount = [];
        $pre_invoiceAmountsByAccount = [];
        $pre_refundAmountsByAccount = [];
        $accounts = Account::with('payment')->get();
        $warehouse = Warehouse::find($id);

        foreach ($accounts as $account) {
            $total_invoice = 0;
            $total_refund = 0;
            $pre_total_invoice = 0;
            $pre_total_refund = 0;


            $transactions = Transaction::with('account')->whereMonth('created_at', now()->month)->where('account_id', $account->id)->get();


            $currentSumByIn = Payment::where('payment_status', 'in')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');



            $total_invoice += $currentSumByIn;
            $currentSumByOut = Payment::where('payment_status', 'out')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');

            $total_refund += $currentSumByOut;


            $preSumByIn = Payment::where('payment_status', 'in')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->sum('amount');
            $pre_total_invoice += $preSumByIn;





            $preSumByOut = Payment::where('payment_status', 'out')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->subMonth()->month)->sum('amount');

            $pre_total_refund += $preSumByOut;


            foreach ($account->transaction as $transaction) {
                $invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pos_amount  = Invoice::where('status', 'pos')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $refund_amount = Invoice::where('status', 'invoice')
                    ->where('location', $id)
                    ->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->month)
                    ->sum('total');

                $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)->where('unit', $id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $total_refund += $refund_amount + $exchange_order;
                $total_invoice += $invoice_amount + $pos_amount +  $exchange_return;
                $invoiceAmountsByAccount[$account->account_name] = $total_invoice;
                $refundAmountsByAccount[$account->account_name] = $total_refund;
                $pre_invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_pos_amount  = Invoice::where('status', 'pos')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_return = PurchaseOrder::where('status', 'return')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');
                $pre_total_refund += $pre_refund_amount + $pre_exchange_order;
                $pre_total_invoice += $pre_invoice_amount + $pre_pos_amount +  $pre_exchange_return;
                $pre_invoiceAmountsByAccount[$account->account_name] = $pre_total_invoice;
                $pre_refundAmountsByAccount[$account->account_name] = $pre_total_refund;
            }
        }

        return view('report.account_location.account_location_general_ledger', compact('accounts',  'invoiceAmountsByAccount', 'refundAmountsByAccount',  'pre_invoiceAmountsByAccount', 'pre_refundAmountsByAccount', 'id', 'warehouse'));
    }

    public function profit_loss($id)
    {
        $invoiceAmountsByAccount = [];
        $refundAmountsByAccount = [];
        $pre_invoiceAmountsByAccount = [];
        $pre_refundAmountsByAccount = [];
        $accounts = Account::with('payment')->where('account_type', 'PL')->get();
        $warehouse = Warehouse::find($id);

        foreach ($accounts as $account) {
            $total_invoice = 0;
            $total_refund = 0;
            $pre_total_invoice = 0;
            $pre_total_refund = 0;


            $transactions = Transaction::with('account')->whereMonth('created_at', now()->month)->where('account_id', $account->id)->get();


            $currentSumByIn = Payment::where('payment_status', 'in')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');



            $total_invoice += $currentSumByIn;
            $currentSumByOut = Payment::where('payment_status', 'out')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');

            $total_refund += $currentSumByOut;


            $preSumByIn = Payment::where('payment_status', 'in')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->sum('amount');
            $pre_total_invoice += $preSumByIn;





            $preSumByOut = Payment::where('payment_status', 'out')
                ->where('account_id', $account->account_name)
                ->where('branch', $id)
                ->whereMonth('created_at', now()->subMonth()->month)->sum('amount');

            $pre_total_refund += $preSumByOut;


            foreach ($account->transaction as $transaction) {
                $invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('location', $id)
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pos_amount  = Invoice::where('status', 'pos')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->month)
                    ->sum('total');

                $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)
                    ->where('unit', $id)
                    ->whereMonth('created_at', now()->month)
                    ->sum('total');

                $total_refund += $refund_amount + $exchange_order;
                $total_invoice += $invoice_amount + $pos_amount +  $exchange_return;
                $invoiceAmountsByAccount[$account->account_name] = $total_invoice;
                $refundAmountsByAccount[$account->account_name] = $total_refund;
                $pre_invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('location', $id)
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_pos_amount  = Invoice::where('status', 'pos')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_return = PurchaseOrder::where('status', 'return')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');
                $pre_total_refund += $pre_refund_amount + $pre_exchange_order;
                $pre_total_invoice += $pre_invoice_amount + $pre_pos_amount +  $pre_exchange_return;
                $pre_invoiceAmountsByAccount[$account->account_name] = $pre_total_invoice;
                $pre_refundAmountsByAccount[$account->account_name] = $pre_total_refund;
            }
        }

        return view('report.account_location.account_location_profit_and_loss', compact('accounts',  'invoiceAmountsByAccount', 'refundAmountsByAccount',  'pre_invoiceAmountsByAccount', 'pre_refundAmountsByAccount', 'id', 'warehouse'));
    }

    public function balance_sheet($id)
    {

        $invoiceAmountsByAccount = [];
        $refundAmountsByAccount = [];
        $pre_invoiceAmountsByAccount = [];
        $pre_refundAmountsByAccount = [];
        $accounts = Account::with('payment')->where('account_type', 'BL')->get();
        $warehouse = Warehouse::find($id);


        foreach ($accounts as $account) {
            $total_invoice = 0;
            $total_refund = 0;
            $pre_total_invoice = 0;
            $pre_total_refund = 0;


            $transactions = Transaction::with('account')->whereMonth('created_at', now()->month)->where('account_id', $account->id)->get();


            $currentSumByIn = Payment::where('payment_status', 'in')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->month)->sum('amount');



            $total_invoice += $currentSumByIn;
            $currentSumByOut = Payment::where('payment_status', 'out')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->month)->sum('amount');

            $total_refund += $currentSumByOut;


            $preSumByIn = Payment::where('payment_status', 'in')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->subMonth()->month)
                ->sum('amount');
            $pre_total_invoice += $preSumByIn;





            $preSumByOut = Payment::where('payment_status', 'out')
                ->where('branch', $id)
                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->subMonth()->month)->sum('amount');

            $pre_total_refund += $preSumByOut;


            foreach ($account->transaction as $transaction) {
                $invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('lccation', $id)
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pos_amount  = Invoice::where('status', 'pos')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $refund_amount = Invoice::where('status', 'invoice')
                    ->where('location', $id)
                    ->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->month)
                    ->sum('total');

                $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)
                    ->where('unit', $id)
                    ->whereMonth('created_at', now()->month)
                    ->sum('total');

                $total_refund += $refund_amount + $exchange_order;
                $total_invoice += $invoice_amount + $pos_amount +  $exchange_return;
                $invoiceAmountsByAccount[$account->account_name] = $total_invoice;
                $refundAmountsByAccount[$account->account_name] = $total_refund;
                $pre_invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('location', $id)
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_pos_amount  = Invoice::where('status', 'pos')
                    ->where('location', $id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')
                    ->where('location', $id)
                    ->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('unit', $id)
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)
                    ->where('unit', $id)
                    ->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');
                $pre_total_refund += $pre_refund_amount + $pre_exchange_order;
                $pre_total_invoice += $pre_invoice_amount + $pre_pos_amount +  $pre_exchange_return;
                $pre_invoiceAmountsByAccount[$account->account_name] = $pre_total_invoice;
                $pre_refundAmountsByAccount[$account->account_name] = $pre_total_refund;
            }
        }

        return view('report.account_location.account_location_balance_sheet', compact('accounts',  'invoiceAmountsByAccount', 'refundAmountsByAccount',  'pre_invoiceAmountsByAccount', 'pre_refundAmountsByAccount', 'id', 'warehouse'));
    }


    public function AccountTransactions($branch, $id)
    {
        $account = Account::with('transaction')->find($id);
        $warehouse = Warehouse::find($branch);


        $transaction = Transaction::with('account')->where('account_id', $id)->whereMonth('created_at', now()->month)->get();


        $sumByIn = Payment::where('payment_status', 'in')
            ->where('branch', $branch)
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')->whereMonth('created_at', now()->month)
            ->pluck('total', 'transaction_id');
        $sumByOut = Payment::where('payment_status', 'out')
            ->where('branch', $branch)
            ->groupBy('transaction_id')
            ->whereMonth('created_at', now()->month)
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');
        //start for mmk
        $invoice_amount = Invoice::groupBy('transaction_id')->where('status', 'invoice')
            ->where('location', $branch)
            ->whereMonth('invoice_date', now()->month)->where('balance_due', 'Invoice')
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');
        $pos_amount = Invoice::groupBy('transaction_id')->where('status', 'pos')
            ->where('location', $branch)
            ->whereMonth('invoice_date', now()->month)
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');
        $refund_amount = Invoice::groupBy('transaction_id')->where('status', 'invoice')->where('balance_due', 'PO Return')
            ->where('location', $branch)
            ->whereMonth('invoice_date', now()->month)
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');

        // Collect all unique transaction IDs from $sumByIn, $sumByOut, and $invoice_amount
        $transactionIds = collect($sumByIn->keys())
            ->merge($sumByOut->keys())
            ->merge($invoice_amount->keys())
            ->merge($refund_amount->keys())
            ->merge($pos_amount->keys())

            ->unique();


        $diff = collect($transactionIds)->mapWithKeys(function ($transactionId) use ($sumByIn, $sumByOut, $invoice_amount, $refund_amount, $pos_amount) {
            $totalIn = $sumByIn->get($transactionId, 0);
            $totalOut = $sumByOut->get($transactionId, 0);
            $invoiceTotal = $invoice_amount->get($transactionId, 0);
            $invoiceRefundTotal = $refund_amount->get($transactionId, 0);
            $posTotal = $pos_amount->get($transactionId, 0);


            $totalIn += $invoiceTotal;
            $totalIn += $posTotal;
            $totalOut += $invoiceRefundTotal;



            // Return an associative array with transactionId as key and calculated difference as value
            return [$transactionId => $totalIn - $totalOut];
        });
        //end for mmk
        //start for USD



        return view('report.account_location.account_location_account_transaction', compact('account', 'transaction', 'diff', 'warehouse', 'branch'));
    }

    public function TransactionsPayment($branch, $id)
    {
        $accounts = Account::all();
        $warehouse = Warehouse::find($branch);
        $transaction = Transaction::find($id);

        $payment = Payment::where('transaction_id', $id)->whereMonth('created_at', now()->month)
            ->where('branch', $branch)
            ->get();
        $invoices = Invoice::where('transaction_id', $id)->where('status', 'invoice')->where('balance_due', 'Invoice')->whereMonth('invoice_date', now()->month)
            ->where('location', $branch)
            ->get();
        $pos = Invoice::where('transaction_id', $id)->where('status', 'pos')
            ->where('location', $branch)
            ->whereMonth('invoice_date', now()->month)->get();
        return view('report.account_location.account_location_account_transaction_payment', compact('accounts', 'transaction', 'payment', 'invoices', 'pos', 'warehouse', 'branch'));
    }
}
