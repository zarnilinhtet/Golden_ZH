<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Sell;
use App\Models\Doctor;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SalePerson;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicePaymentMethod;


class ReportController extends Controller
{
    public function customer_report($branch = null)
    {
        if ($branch) {
            $customers = Customer::whereHas('invoices', function ($q) use ($branch) {
                $q->where('location', $branch)
                    ->whereBetween('invoice_date', [
                        now()->startOfMonth()->toDateString(),
                        now()->endOfMonth()->toDateString(),
                    ]);
            })
                ->with(['invoices' => function ($q) use ($branch) {
                    $q->where('location', $branch)
                        ->whereBetween('invoice_date', [
                            now()->startOfMonth()->toDateString(),
                            now()->endOfMonth()->toDateString(),
                        ]);
                }])
                ->latest()
                ->get();
        } else {
            $customers = Customer::whereHas('invoices', function ($q) {
                $q->whereBetween('invoice_date', [
                    now()->startOfMonth()->toDateString(),
                    now()->endOfMonth()->toDateString(),
                ]);
            })
                ->with(['invoices' => function ($q) {
                    $q->whereBetween('invoice_date', [
                        now()->startOfMonth()->toDateString(),
                        now()->endOfMonth()->toDateString(),
                    ]);
                }])
                ->latest()
                ->get();
        }

        // calculate totals per customer
        $customers->each(function ($customer) {
            $customer->invoice_count = $customer->invoices->count();
            $customer->total_amount  = $customer->invoices->sum('total');
            $customer->total_deposit = $customer->invoices->sum('deposit');
            $customer->total_remain  = $customer->invoices->sum('remain_balance');
        });


        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All';
        $start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d');


        return view('report.report_customer', compact('customers', 'branch', 'branch_drop', 'currentBranchName', 'start', 'end'));
    }
    public function customer_report_search(Request $request)
    {
        $start = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');
        if ($branch) {
            $customers = Customer::whereHas('invoices', function ($q) use ($branch, $start, $end) {
                $q->where('location', $branch)
                    ->whereBetween('invoice_date', [
                        $start,
                        $end,
                    ]);
            })
                ->with(['invoices' => function ($q) use ($branch, $start, $end) {
                    $q->where('location', $branch)
                        ->whereBetween('invoice_date', [
                            $start,
                            $end,
                        ]);
                }])
                ->latest()
                ->get();
        } else {
            $customers = Customer::whereHas('invoices', function ($q) use ($start, $end) {
                $q->whereBetween('invoice_date', [
                    $start,
                    $end,
                ]);
            })
                ->with(['invoices' => function ($q) use ($start, $end) {
                    $q->whereBetween('invoice_date', [
                        $start,
                        $end,
                    ]);
                }])
                ->latest()
                ->get();
        }

        // calculate totals per customer
        $customers->each(function ($customer) {
            $customer->invoice_count = $customer->invoices->count();
            $customer->total_amount  = $customer->invoices->sum('total');
            $customer->total_deposit = $customer->invoices->sum('deposit');
            $customer->total_remain  = $customer->invoices->sum('remain_balance');
        });


        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All';



        return view('report.report_customer', compact('customers', 'branch', 'branch_drop', 'currentBranchName', 'start', 'end'));
    }

    public function customer_invoice_report($id, $start, $end)
    {
        $customer = Customer::find($id);
        $invoices = Invoice::where('customer_id', $id)->whereBetween('invoice_date', [$start, $end])->get();
        return view('customer.customer_invoice', compact('invoices', 'customer'));
    }
    public function report_sale_person($branch = null)
    {
        if ($branch) {
            $sale_persons = SalePerson::where('location', $branch)->latest()->get();
        } else {
            $sale_persons = SalePerson::all();
        }
        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All';
        return view('report.report_sale_person', compact('sale_persons', 'branch', 'branch_drop', 'currentBranchName'));
    }
    public function patient_invoice($id)
    {
        $invoices = Invoice::where('customer_id', $id)->get();
        $customer = Customer::find($id);
        return view('customer.customer_invoice', compact('invoices', 'customer'));
    }
    public function foc_patient_invoice($id)
    {
        $invoices = Invoice::where('customer_id', $id)->where('foc_patient', 'Yes')->get();
        $customer = Customer::find($id);
        return view('customer.customer_invoice', compact('invoices', 'customer'));
    }
    public function doctorDetail($id)
    {
        $doctors = Doctor::findOrFail($id);
        $invoices = Invoice::where('doctor_id', $id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->get();
        $sells = Sell::all();
        $totalInvoices = Invoice::where('doctor_id', $id)->count();
        $invoice_qty = $invoices->count();
        $total = $invoices->sum('doctor_commission');

        return view('report.doctor_detail_report', compact('doctors', 'invoices', 'invoice_qty', 'total', 'totalInvoices', 'sells'));
    }
    public function foc_patient($branch = null)
    {

        if ($branch == 'FOC') {
            $customers = Customer::with(['invoices' => function ($query) {
                $query->where('foc_patient', 'Yes');
            }])
                ->whereHas('invoices', function ($query) {
                    $query->where('foc_patient', 'Yes');
                })
                ->get();
        } else if ($branch == 'Non-FOC') {
            $customers = Customer::with(['invoices' => function ($query) {
                $query->where('foc_patient', 'No');
            }])
                ->whereHas('invoices', function ($query) {
                    $query->where('foc_patient', 'No');
                })
                ->get();
        } else {
            $customers = Customer::with(['invoices' => function ($query) {
                $query->whereIn('foc_patient', ['No', 'Yes']);
            }])
                ->whereHas('invoices', function ($query) {
                    $query->whereIn('foc_patient', ['No', 'Yes']);
                })
                ->get();
        }


        $branch_drop = ['FOC', 'Non-FOC'];
        if ($branch) {
            $currentBranchName = $branch;
        } else {
            $currentBranchName = 'All';
        }
        return view('report.foc_patient', compact('customers', 'branch_drop', 'currentBranchName'));
    }
    public function doctorDetailSearch(Request $request, $id)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');

        $doctors = Doctor::findOrFail($id);
        $invoices = Invoice::where('doctor_id', $id)
            ->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->get();
        $sells = Sell::all();
        $totalInvoices = Invoice::where('doctor_id', $id)->count();
        $invoice_qty = $invoices->count();
        $total = $invoices->sum('doctor_commission');

        return view('report.doctor_detail_report', compact('doctors', 'invoices', 'invoice_qty', 'total', 'totalInvoices', 'sells'));
    }

    public function foc_patient_search(Request $request, $branch = null)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');

        if ($request->branch == 'FOC') {
            $customers = Customer::with(['invoices' => function ($query)                use ($start_date, $end_date) {
                $query->where('foc_patient', 'Yes')
                    ->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            }])
                ->whereHas('invoices', function ($query) use ($start_date, $end_date) {
                    $query->where('foc_patient', 'Yes')
                        ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })
                ->get();
        } else if ($request->branch == 'Non-FOC') {
            $customers = Customer::with(['invoices' => function ($query) use ($start_date, $end_date) {
                $query->where('foc_patient', 'No')
                    ->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            }])
                ->whereHas('invoices', function ($query) use ($start_date, $end_date) {
                    $query->where('foc_patient', 'No')
                        ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })
                ->get();
        } else {
            $customers = Customer::with(['invoices' => function ($query) use ($start_date, $end_date) {
                $query->whereIn('foc_patient', ['No', 'Yes'])
                    ->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            }])
                ->whereHas('invoices', function ($query) use ($start_date, $end_date) {
                    $query->whereIn('foc_patient', ['No', 'Yes'])
                        ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })
                ->get();
        }


        $branch_drop = ['FOC', 'Non-FOC'];
        if ($branch) {
            $currentBranchName = $branch;
        } else {
            $currentBranchName = 'All';
        }
        return view('report.foc_patient', compact('customers', 'branch_drop', 'currentBranchName'));
    }
    public function inout_patient($branch = null)
    {
        if ($branch) {
            $customers = Customer::where('created_at', '>=', Carbon::now()->startOfMonth())->where('created_at', '<=', Carbon::now()->endOfMonth())->where('patient_type', $branch)->get();
        } else {
            $customers = Customer::where('created_at', '>=', Carbon::now()->startOfMonth())->where('created_at', '<=', Carbon::now()->endOfMonth())->get();
        }

        $branch_drop = ['IN Patient', 'OUT Patient'];
        if ($branch) {
            $currentBranchName = $branch;
        } else {
            $currentBranchName = 'All';
        }
        return view('report.inout_patient', compact('customers', 'branch_drop', 'currentBranchName'));
    }
    public function inout_patient_search(Request $request, $branch = null)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        if ($request->branch == 'All') {
            $customers = Customer::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->latest()->get();
            // dd($customers);
        } else {
            $customers = Customer::where('patient_type', $request->branch)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->latest()->get();
        }
        $branches = Warehouse::latest()->get();

        // $branchNames = $branch_drop->pluck('name', 'id');

        $branch_drop = ['IN Patient', 'OUT Patient'];
        if ($request->branch) {
            $currentBranchName = $request->branch;
        } else {
            $currentBranchName = 'All Type';
        }

        return view('report.inout_patient', compact('customers', 'branches', 'currentBranchName', 'branch_drop'));
    }
    public function doctor($branch = null)
    {
        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = Invoice::all();
            if ($branch) {
                $doctors = Doctor::where('branch', $branch)->get();
            } else {
                $doctors = Doctor::all();
            }
            $doctorInvoices = [];
            foreach ($doctors as $doctor) {
                $doctorInvoices[$doctor->id] = Invoice::where('doctor_id', $doctor->id)->count();
            }
            $total = $invoices->sum('total');
        } else {
            $invoices = Invoice::where('branch', auth()->user()->level)->get();
            $doctors = Doctor::where('branch', auth()->user()->level)->get();
            $doctorInvoices = [];
            foreach ($doctors as $doctor) {
                $doctorInvoices[$doctor->id] = Invoice::where('doctor_id', $doctor->id)
                    ->where('branch', auth()->user()->level)->count();
            }
            $total = $invoices->where('branch', auth()->user()->level)->sum('total');
        }
        $total = $invoices->sum('total');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Doctors';

        return view('report.doctor_report', compact('doctors', 'total', 'invoices',  'branchNames', 'currentBranchName', 'branch_drop'));
    }



    public function doctorSearch(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');

        $invoices_search = [];
        $doctor_search = [];
        $doctorInvoices = [];
        $branch = $request->input('branch');

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = Invoice::whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->when($branch, function ($query, $branch) {
                    return $query->where('location', $branch);
                })
                ->get();

            $doctors = Doctor::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->when($branch, function ($query, $branch) {
                    return $query->where('branch', $branch);
                })
                ->get();
        } else {
            $invoices = Invoice::whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->where('branch', auth()->user()->level)
                ->when($branch, function ($query, $branch) {
                    return $query->where('location', $branch);
                })
                ->get();

            $doctors = Doctor::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('branch', auth()->user()->level)
                ->when($branch, function ($query, $branch) {
                    return $query->where('branch', $branch);
                })
                ->get();
        }

        foreach ($doctor_search as $doctor) {
            $doctorInvoices[$doctor->id] = Invoice::where('doctor_id', $doctor->id)
                ->whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->count();
        }

        $total = $invoices->sum('total');
        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames[$branch] : 'All Doctors';
        return view('report.doctor_report', compact('doctors', 'total', 'invoices', 'doctorInvoices', 'branch_drop', 'branchNames', 'currentBranchName'));
    }


    public function account_total_report()
    {

        return view('report.report_account_total_page');
    }

    public function profit_loss()
    {
        $invoiceAmountsByAccount = [];
        $refundAmountsByAccount = [];
        $pre_invoiceAmountsByAccount = [];
        $pre_refundAmountsByAccount = [];
        $accounts = Account::with('payment')->where('account_type', 'PL')->get();

        foreach ($accounts as $account) {
            $total_invoice = 0;
            $total_refund = 0;
            $pre_total_invoice = 0;
            $pre_total_refund = 0;


            $transactions = Transaction::with('account')->whereMonth('created_at', now()->month)->where('account_id', $account->id)->get();


            $currentSumByIn = Payment::where('payment_status', 'in')

                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');



            $total_invoice += $currentSumByIn;
            $currentSumByOut = Payment::where('payment_status', 'out')

                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');

            $total_refund += $currentSumByOut;


            $preSumByIn = Payment::where('payment_status', 'in')


                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->sum('amount');
            $pre_total_invoice += $preSumByIn;





            $preSumByOut = Payment::where('payment_status', 'out')
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->subMonth()->month)->sum('amount');

            $pre_total_refund += $preSumByOut;


            foreach ($account->transaction as $transaction) {
                $invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pos_amount  = Invoice::where('status', 'pos')

                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->month)
                    ->sum('total');

                $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $total_refund += $refund_amount + $exchange_order;
                $total_invoice += $invoice_amount + $pos_amount +  $exchange_return;
                $invoiceAmountsByAccount[$account->account_name] = $total_invoice;
                $refundAmountsByAccount[$account->account_name] = $total_refund;
                $pre_invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_pos_amount  = Invoice::where('status', 'pos')

                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');
                $pre_total_refund += $pre_refund_amount + $pre_exchange_order;
                $pre_total_invoice += $pre_invoice_amount + $pre_pos_amount +  $pre_exchange_return;
                $pre_invoiceAmountsByAccount[$account->account_name] = $pre_total_invoice;
                $pre_refundAmountsByAccount[$account->account_name] = $pre_total_refund;
            }
        }

        return view('report.profit_loss', compact('accounts',  'invoiceAmountsByAccount', 'refundAmountsByAccount',  'pre_invoiceAmountsByAccount', 'pre_refundAmountsByAccount'));
    }

    public function balance_sheet()
    {

        $invoiceAmountsByAccount = [];
        $refundAmountsByAccount = [];
        $pre_invoiceAmountsByAccount = [];
        $pre_refundAmountsByAccount = [];
        $accounts = Account::with('payment')->where('account_type', 'BL')->get();

        foreach ($accounts as $account) {
            $total_invoice = 0;
            $total_refund = 0;
            $pre_total_invoice = 0;
            $pre_total_refund = 0;


            $transactions = Transaction::with('account')->whereMonth('created_at', now()->month)->where('account_id', $account->id)->get();


            $currentSumByIn = Payment::where('payment_status', 'in')

                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->month)->sum('amount');



            $total_invoice += $currentSumByIn;
            $currentSumByOut = Payment::where('payment_status', 'out')

                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->month)->sum('amount');

            $total_refund += $currentSumByOut;


            $preSumByIn = Payment::where('payment_status', 'in')


                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->subMonth()->month)
                ->sum('amount');
            $pre_total_invoice += $preSumByIn;





            $preSumByOut = Payment::where('payment_status', 'out')
                ->where('account_id', $account->account_name)
                ->whereMonth('date', now()->subMonth()->month)->sum('amount');

            $pre_total_refund += $preSumByOut;


            foreach ($account->transaction as $transaction) {
                $invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pos_amount  = Invoice::where('status', 'pos')

                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->month)
                    ->sum('total');

                $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $total_refund += $refund_amount + $exchange_order;
                $total_invoice += $invoice_amount + $pos_amount +  $exchange_return;
                $invoiceAmountsByAccount[$account->account_name] = $total_invoice;
                $refundAmountsByAccount[$account->account_name] = $total_refund;
                $pre_invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_pos_amount  = Invoice::where('status', 'pos')

                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');
                $pre_total_refund += $pre_refund_amount + $pre_exchange_order;
                $pre_total_invoice += $pre_invoice_amount + $pre_pos_amount +  $pre_exchange_return;
                $pre_invoiceAmountsByAccount[$account->account_name] = $pre_total_invoice;
                $pre_refundAmountsByAccount[$account->account_name] = $pre_total_refund;
            }
        }

        return view('report.balanceSheet', compact('accounts',  'invoiceAmountsByAccount', 'refundAmountsByAccount',  'pre_invoiceAmountsByAccount', 'pre_refundAmountsByAccount'));
    }
    public function general_ledger()
    {

        $invoiceAmountsByAccount = [];
        $refundAmountsByAccount = [];
        $pre_invoiceAmountsByAccount = [];
        $pre_refundAmountsByAccount = [];
        $accounts = Account::with('payment')->get();

        foreach ($accounts as $account) {
            $total_invoice = 0;
            $total_refund = 0;
            $pre_total_invoice = 0;
            $pre_total_refund = 0;


            $transactions = Transaction::with('account')->whereMonth('created_at', now()->month)->where('account_id', $account->id)->get();


            $currentSumByIn = Payment::where('payment_status', 'in')

                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');



            $total_invoice += $currentSumByIn;
            $currentSumByOut = Payment::where('payment_status', 'out')

                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->month)->sum('amount');

            $total_refund += $currentSumByOut;


            $preSumByIn = Payment::where('payment_status', 'in')


                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->sum('amount');
            $pre_total_invoice += $preSumByIn;





            $preSumByOut = Payment::where('payment_status', 'out')
                ->where('account_id', $account->account_name)
                ->whereMonth('created_at', now()->subMonth()->month)->sum('amount');

            $pre_total_refund += $preSumByOut;


            foreach ($account->transaction as $transaction) {
                $invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pos_amount  = Invoice::where('status', 'pos')

                    ->whereMonth('invoice_date', now()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->month)
                    ->sum('total');

                $exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->month)
                    ->sum('total');

                $total_refund += $refund_amount + $exchange_order;
                $total_invoice += $invoice_amount + $pos_amount +  $exchange_return;
                $invoiceAmountsByAccount[$account->account_name] = $total_invoice;
                $refundAmountsByAccount[$account->account_name] = $total_refund;
                $pre_invoice_amount  = Invoice::where('status', 'invoice')
                    ->where('balance_due', 'Invoice')
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_pos_amount  = Invoice::where('status', 'pos')

                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->where('transaction_id', $transaction->id)
                    ->sum('total');



                $pre_refund_amount = Invoice::where('status', 'invoice')->where('balance_due', 'PO Return')->where('transaction_id', $transaction->id)
                    ->whereMonth('invoice_date', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_order = PurchaseOrder::where('status', 'invoice')->where('balance_due', 'Purchase Order')
                    ->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');

                $pre_exchange_return = PurchaseOrder::where('status', 'return')->where('transaction_id', $transaction->id)->whereMonth('created_at', now()->subMonth()->month)
                    ->sum('total');
                $pre_total_refund += $pre_refund_amount + $pre_exchange_order;
                $pre_total_invoice += $pre_invoice_amount + $pre_pos_amount +  $pre_exchange_return;
                $pre_invoiceAmountsByAccount[$account->account_name] = $pre_total_invoice;
                $pre_refundAmountsByAccount[$account->account_name] = $pre_total_refund;
            }
        }

        return view('report.general_ledger', compact('accounts',  'invoiceAmountsByAccount', 'refundAmountsByAccount',  'pre_invoiceAmountsByAccount', 'pre_refundAmountsByAccount'));
    }
    public function AccountTransactions($id)
    {
        $account = Account::with('transaction')->find($id);


        $transaction = Transaction::with('account')->where('account_id', $id)->whereMonth('created_at', now()->month)->get();


        $sumByIn = Payment::where('payment_status', 'in')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')->whereMonth('created_at', now()->month)
            ->pluck('total', 'transaction_id');
        $sumByOut = Payment::where('payment_status', 'out')
            ->groupBy('transaction_id')
            ->whereMonth('created_at', now()->month)
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');
        //start for mmk
        $invoice_amount = Invoice::groupBy('transaction_id')->where('status', 'invoice')
            ->whereMonth('invoice_date', now()->month)->where('balance_due', 'Invoice')
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');
        $pos_amount = Invoice::groupBy('transaction_id')->where('status', 'pos')
            ->whereMonth('invoice_date', now()->month)
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');
        $refund_amount = Invoice::groupBy('transaction_id')->where('status', 'invoice')->where('balance_due', 'PO Return')
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



        return view('report.report_account_transactions', compact('account', 'transaction', 'diff'));
    }

    public function TransactionsPayment($id)
    {
        $accounts = Account::all();

        $transaction = Transaction::find($id);

        $payment = Payment::where('transaction_id', $id)->whereMonth('created_at', now()->month)->get();
        $invoices = Invoice::where('transaction_id', $id)->where('status', 'invoice')->where('balance_due', 'Invoice')->whereMonth('invoice_date', now()->month)->get();
        $pos = Invoice::where('transaction_id', $id)->where('status', 'pos')->whereMonth('invoice_date', now()->month)->get();
        return view('report.account_transactions_payment', compact('accounts', 'transaction', 'payment', 'invoices', 'pos'));
    }
    public function ProfitLossFitter(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // Fetch accounts with the date range including start and end dates
        $accounts = Account::where('account_type', 'PL')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        return view('report.profit_loss', compact('accounts'));
    }

    public function report_invoice($branch = null)
    {
        $invoicesQuery = Invoice::whereDate('invoice_date', today())
            ->where('status', 'invoice');

        if ($branch) {
            $invoicesQuery->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('location', auth()->user()->level);
            $invoices = $invoicesQuery->get();
        }

        $invoiceIds = $invoices->pluck('id');

        $invoicePaymentMethod = InvoicePaymentMethod::whereIn('invoice_id', $invoiceIds)->get();

        $total = $invoices->sum('total');
        $totalCash = $invoicePaymentMethod->where('payment_method', 'Cash')->sum('payment_amount');
        $totalKbz = $invoicePaymentMethod->where('payment_method', 'K Pay')->sum('payment_amount');
        $totalWave = $invoicePaymentMethod->where('payment_method', 'Wave')->sum('payment_amount');
        $totalOther = $invoicePaymentMethod->where('payment_method', 'Others')->sum('payment_amount');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Invoices';


        return view('report.report_invoice', compact('invoices', 'total', 'branchNames', 'branch', 'branch_drop', 'totalCash', 'totalKbz', 'totalWave', 'totalOther', 'currentBranchName'));
    }
    public function reportExpense($branch = null)
    {

        $expensesQuery = Expense::whereDate('created_at', today());

        if ($branch) {
            $expensesQuery->where('branch', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $expenses = $expensesQuery->get();
        } else {
            $expensesQuery->where('branch', auth()->user()->level);
            $expenses = $expensesQuery->get();
        }

        $total = $expenses->sum('amount');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames[$branch] : 'All Expenses';

        return view('report.report_expense', compact('expenses', 'total', 'branch', 'branch_drop', 'currentBranchName'));
    }

    public function expenseSearch(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');

        $expensesQuery = Expense::whereBetween('date', [$start_date, $end_date]);

        if ($branch) {
            $expensesQuery->where('branch', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
            $expenses = $expensesQuery->get();
        } else {
            $expensesQuery->where('branch', auth()->user()->level);
            $expenses = $expensesQuery->get();
        }

        $total = $expenses->sum('amount');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames[$branch] : 'All Expenses';

        return view('report.report_expense', compact('expenses', 'total', 'branch', 'branch_drop', 'currentBranchName', 'start_date', 'end_date'));
    }
    public function report_quotation($branch = null)
    {

        $invoicesQuery = Invoice::whereDate('quote_date', today())
            ->where('status', 'quotation');

        if ($branch) {
            $invoicesQuery->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('location', auth()->user()->level);
            $invoices = $invoicesQuery->get();
        }

        $totalCash = (clone $invoicesQuery)->where('payment_method', 'Cash')->sum('total');
        $totalKbz = (clone $invoicesQuery)->where('payment_method', 'KPay')->sum('total');
        $totalWave = (clone $invoicesQuery)->where('payment_method', 'Wave Pay')->sum('total');
        $totalOther = (clone $invoicesQuery)->where('payment_method', 'Others')->sum('total');

        $total = $invoices->sum('total');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Quotation';


        return view('report.report_quotation', compact('invoices', 'total', 'branchNames', 'branch', 'branch_drop', 'totalCash', 'totalKbz', 'totalWave', 'totalOther', 'currentBranchName'));
    }
    public function report_pos($branch = null)
    {


        $invoicesQuery = Invoice::whereDate('invoice_date', today())
            ->where('status', 'pos');

        if ($branch) {
            $invoicesQuery->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('location', auth()->user()->level);
            $invoices = $invoicesQuery->get();
        }

        $totalCash = (clone $invoicesQuery)->where('payment_method', 'Cash')->sum('total');
        $totalKbz = (clone $invoicesQuery)->where('payment_method', 'KPay')->sum('total');
        $totalWave = (clone $invoicesQuery)->where('payment_method', 'Wave Pay')->sum('total');
        $totalOther = (clone $invoicesQuery)->where('payment_method', 'Others')->sum('total');

        $sale_totals = (clone $invoicesQuery)
            ->select(DB::raw('quote_date, SUM(total) as sale_total')) // Specify what to select and aggregate
            ->groupBy('quote_date')
            ->get();


        $total = $invoices->sum('total');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All POS';



        return view('report.report_pos', compact('invoices', 'total', 'branchNames', 'branch', 'branch_drop', 'totalCash', 'totalKbz', 'totalWave', 'totalOther', 'currentBranchName', 'sale_totals'));
    }
    public function report_po()
    {



        $pos = PurchaseOrder::whereDate('created_at', today())
            ->where('balance_due', 'Purchase Order')
            ->latest()->get();

        $total = $pos->sum('total');

        return view('report.report_po', compact('pos', 'total'));
    }


    public function report_sale_return()
    {



        $pos = PurchaseOrder::whereDate('created_at', today())
            ->where('balance_due', 'Sale Return Invoice')
            ->latest()->get();

        $total = $pos->sum('total');

        return view('report.report_sale_return', compact('pos', 'total'));
    }

    public function report_item($branch = null)
    {


        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $invoices_items = Invoice::with('sells') // Assuming 'sell' is the relationship for the items
            ->whereDate('invoice_date', today())
            ->whereIn('status', ['invoice', 'pos']);

        if ($branch) {
            $invoices_items->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoices_items->get();
        } else {
            $invoices_items->where('location', auth()->user()->level);
            $invoices = $invoices_items->get();
        }

        $invoice_items = [];

        foreach ($invoices as $invoice) {
            foreach ($invoice->sells as $item) {
                // Create a composite key with part_number and warehouse_id (assuming 'warehouse_id' is the warehouse field)
                $key = $item->part_number . '-' . $item->warehouse;

                // If the combination of part_number and warehouse exists, sum the quantities
                if (isset($items[$key])) {
                    $invoice_items[$key]['quantity'] += $item->product_qty;
                } else {
                    // Otherwise, add it to the array with its initial quantity and unit
                    $invoice_items[$key] = [
                        'item_name' => $item->part_number,
                        'quantity' => $item->product_qty,
                        'unit' => $item->unit,
                        'warehouse_id' => $item->warehouse, // Assuming you have a warehouse identifier
                    ];
                }
            }
        }


        $invoicesQuery = Item::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', null);

        if ($branch) {
            $invoicesQuery->where('warehouse_id', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $items = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('warehouse_id', auth()->user()->level);
            $items = $invoicesQuery->get();
        }





        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Items';

        return view('report.report_item', compact('items', 'invoice_items', 'branchNames', 'branch', 'branch_drop', 'currentBranchName'));
    }

    public function invoiceSearch(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');



        $invoicesQuery = Invoice::whereBetween('invoice_date', [$start_date, $end_date])
            ->where('status', 'invoice');

        if ($branch) {
            $invoicesQuery->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('location', auth()->user()->level);
            $invoices = $invoicesQuery->get();
        }

        $invoiceIds = $invoices->pluck('id');

        $invoicePaymentMethod = InvoicePaymentMethod::whereIn('invoice_id', $invoiceIds)->get();

        $total = $invoices->sum('total');
        $totalCash = $invoicePaymentMethod->where('payment_method', 'Cash')->sum('payment_amount');
        $totalKbz = $invoicePaymentMethod->where('payment_method', 'K Pay')->sum('payment_amount');
        $totalWave = $invoicePaymentMethod->where('payment_method', 'Wave')->sum('payment_amount');
        $totalOther = $invoicePaymentMethod->where('payment_method', 'Others')->sum('payment_amount');

        $total = $invoices->sum('total');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Invoices';

        return view('report.report_invoice', compact('invoices', 'total', 'totalCash', 'totalKbz', 'totalWave', 'totalOther', 'branch', 'branch_drop', 'currentBranchName'));
    }


    public function monthly_pos_search(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');



        $invoicesQuery = Invoice::whereBetween('invoice_date', [$start_date, $end_date])
            ->where('status', 'pos');

        if ($branch) {
            $invoicesQuery->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('location', auth()->user()->level);
            $invoices = $invoicesQuery->get();
        }

        $totalCash = (clone $invoicesQuery)->where('payment_method', 'Cash')->sum('total');
        $totalKbz = (clone $invoicesQuery)->where('payment_method', 'KPay')->sum('total');
        $totalWave = (clone $invoicesQuery)->where('payment_method', 'Wave Pay')->sum('total');
        $totalOther = (clone $invoicesQuery)->where('payment_method', 'Others')->sum('total');

        $total = $invoices->sum('total');

        $sale_totals = (clone $invoicesQuery)
            ->select(DB::raw('quote_date, SUM(total) as sale_total')) // Specify what to select and aggregate
            ->groupBy('quote_date')
            ->get();

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All POS';

        return view('report.report_pos', compact('invoices', 'total', 'branchNames', 'branch', 'branch_drop', 'totalCash', 'totalKbz', 'totalWave', 'totalOther', 'currentBranchName', 'sale_totals'));
    }


    public function monthly_quotation_search(Request $request)
    {


        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');



        $invoicesQuery = Invoice::whereBetween('quote_date', [$start_date, $end_date])
            ->where('status', 'quotation');

        if ($branch) {
            $invoicesQuery->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('location', auth()->user()->level);
            $invoices = $invoicesQuery->get();
        }

        $totalCash = (clone $invoicesQuery)->where('payment_method', 'Cash')->sum('total');
        $totalKbz = (clone $invoicesQuery)->where('payment_method', 'KPay')->sum('total');
        $totalWave = (clone $invoicesQuery)->where('payment_method', 'Wave Pay')->sum('total');
        $totalOther = (clone $invoicesQuery)->where('payment_method', 'Others')->sum('total');

        $total = $invoices->sum('total');

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Quotations';

        return view('report.report_quotation', compact('invoices', 'total', 'totalCash', 'totalKbz', 'totalWave', 'totalOther', 'branch', 'branch_drop', 'currentBranchName'));
    }
    public function monthly_po_search(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');

        $pos = PurchaseOrder::where(function ($query) use ($start_date, $end_date) {
            $query->where(function ($query) use ($start_date, $end_date) {
                $query->whereNotNull('po_date')
                    ->whereDate('po_date', '>=', $start_date)
                    ->whereDate('po_date', '<=', $end_date);
            })
                ->orWhere(function ($query) use ($start_date, $end_date) {
                    $query->whereNull('po_date')
                        ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                });
        })
            ->where('balance_due', 'Purchase Order')
            ->latest()
            ->get();

        $total = $pos->sum('total');

        return view('report.report_po', compact('pos', 'total'));
    }


    public function monthly_sale_return_search(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $pos = PurchaseOrder::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('balance_due', 'Sale Return Invoice')
            ->latest()->get();
        $total = $pos->sum('total');

        return view('report.report_sale_return', compact('pos', 'total'));
    }
    public function monthly_item_search(Request $request)
    {


        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');
        $invoices_items = Invoice::with('sells')
            ->whereBetween('invoice_date', [$start_date, $end_date])
            ->whereIn('status', ['invoice', 'pos']);

        if ($branch) {
            $invoices_items->where('location', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoices_items->get();
        } else {
            $invoices_items->where('location', auth()->user()->level);
            $invoices = $invoices_items->get();
        }

        $invoice_items = [];

        foreach ($invoices as $invoice) {
            foreach ($invoice->sells as $item) {
                // Create a composite key with part_number and warehouse_id (assuming 'warehouse_id' is the warehouse field)
                $key = $item->part_number . '-' . $item->warehouse;

                // If the combination of part_number and warehouse exists, sum the quantities
                if (isset($items[$key])) {
                    $invoice_items[$key]['quantity'] += $item->product_qty;
                } else {
                    // Otherwise, add it to the array with its initial quantity and unit
                    $invoice_items[$key] = [
                        'item_name' => $item->part_number,
                        'quantity' => $item->product_qty,
                        'unit' => $item->unit,
                        'warehouse_id' => $item->warehouse, // Assuming you have a warehouse identifier
                    ];
                }
            }
        }


        $invoicesQuery = Item::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('status', null);


        if ($branch) {
            $invoicesQuery->where('warehouse_id', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $items = $invoicesQuery->get();
        } else {
            $invoicesQuery->where('warehouse_id', auth()->user()->level);
            $items = $invoicesQuery->get();
        }





        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Items';

        return view('report.report_item', compact('items', 'invoice_items', 'branchNames', 'branch', 'branch_drop', 'currentBranchName'));
    }
}
