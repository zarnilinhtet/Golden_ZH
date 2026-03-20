<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index($branch = null)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];
// dd(auth()->user()->is_admin);
        if (auth()->user()->is_admin == '1') {
            if ($branch) {
                $invoiceCount = Invoice::where('status', 'invoice')
                 ->where('location', $branch)
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();
                $newcustomerCount = Customer::where('branch', $branch)
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();
                $customerCount = Customer::where('branch', $branch)->count();

                $posCount = Invoice::where('status', 'pos')
                    ->where('location', $branch)
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();


                $quotationCount = Invoice::where('status', 'quotation')
                    ->where('location', $branch)
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();

                $purchaseOrderCount = PurchaseOrder::where('balance_due', 'PO')
                    ->where('status', 'invoice')->where('unit', $branch)
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();

                $monthNames = [
                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'May',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Aug',
                    9 => 'Sep',
                    10 => 'Oct',
                    11 => 'Nov',
                    12 => 'Dec'
                ];

                $chart = [];
                $posChart = [];
                $poChart = [];

                // Fetch invoices for the current year
                $invoices = Invoice::where('status', 'invoice')
                    ->where('location', $branch)
                    ->whereYear('created_at', now()->year) // Get all months of this year
                    ->get();

                // Loop through invoices and sum totals per month
                foreach ($invoices as $invoice) {
                    $monthIndex = $invoice->created_at->month; // Get numeric month (1-12)
                    $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                    // Sum total amounts per month
                    if (!isset($chart[$month])) {
                        $chart[$month] = 0; // Initialize if not set
                    }
                    $chart[$month] += $invoice->total;
                }

                // dd($chart);

                // Fetch invoices for the current year
                $point_of_sales = Invoice::where('status', 'pos')
                   ->where('location', $branch)
                    ->whereYear('created_at', now()->year) // Get all months of this year
                    ->get();

                // Loop through invoices and sum totals per month
                foreach ($point_of_sales as $pos) {
                    $monthIndex = $pos->created_at->month; // Get numeric month (1-12)
                    $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                    // Sum total amounts per month
                    if (!isset($posChart[$month])) {
                        $posChart[$month] = 0; // Initialize if not set
                    }
                    $posChart[$month] += $pos->total;
                }

                // Fetch invoices for the current year
                $purchase_orders = PurchaseOrder::where('balance_due', 'PO')
                    ->where('status', 'invoice')->where('unit', $branch)
                    ->whereYear('created_at', now()->year) // Get all months of this year
                    ->get();

                // Loop through invoices and sum totals per month
                foreach ($purchase_orders as $po) {
                    $monthIndex = $po->created_at->month; // Get numeric month (1-12)
                    $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                    // Sum total amounts per month
                    if (!isset($poChart[$month])) {
                        $poChart[$month] = 0; // Initialize if not set
                    }
                    $poChart[$month] += $po->total;
                }

                $warrantyClaimCounts = Sell::select('part_number', DB::raw('count(*) as count'))
                    ->whereMonth('created_at', now()->month)->where('warehouse', $branch)
                    ->groupBy('part_number')
                    ->orderBy('count', 'desc')
                    ->take(8)
                    ->get();
                $SalePersons = Invoice::select('sale_by', DB::raw('SUM(net_total) as total_net'))
                    ->whereMonth('created_at', now()->month)->where('location', $branch)
                    ->where('status', 'invoice')
                    ->groupBy('sale_by')
                    ->orderByDesc('total_net')
                    ->take(8)
                    ->get();

            } else {
                $invoiceCount = Invoice::where('status', 'invoice')

                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();
                $newcustomerCount = Customer::whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();
                $customerCount = Customer::count();

                $posCount = Invoice::where('status', 'pos')
                    // ->where('invoice_category', 'POS')
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();


                $quotationCount = Invoice::where('status', 'quotation')
                    // ->where('invoice_category', 'quotation')
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();

                $purchaseOrderCount = PurchaseOrder::where('balance_due', 'PO')
                    ->where('status', 'invoice')
                    ->whereMonth('created_at', now()->month) // Current month
                    ->whereYear('created_at', now()->year)   // Current year
                    ->count();

                $monthNames = [
                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'May',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Aug',
                    9 => 'Sep',
                    10 => 'Oct',
                    11 => 'Nov',
                    12 => 'Dec'
                ];

                $chart = [];
                $posChart = [];
                $poChart = [];

                // Fetch invoices for the current year
                $invoices = Invoice::where('status', 'invoice')
                    // ->where('invoice_category', 'Invoice')
                    ->whereYear('created_at', now()->year) // Get all months of this year
                    ->get();

                // Loop through invoices and sum totals per month
                foreach ($invoices as $invoice) {
                    $monthIndex = $invoice->created_at->month; // Get numeric month (1-12)
                    $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                    // Sum total amounts per month
                    if (!isset($chart[$month])) {
                        $chart[$month] = 0; // Initialize if not set
                    }
                    $chart[$month] += $invoice->total;
                }

                // dd($chart);

                // Fetch invoices for the current year
                $point_of_sales = Invoice::where('status', 'pos')
                    // ->where('invoice_category', 'POS')
                    ->whereYear('created_at', now()->year) // Get all months of this year
                    ->get();

                // Loop through invoices and sum totals per month
                foreach ($point_of_sales as $pos) {
                    $monthIndex = $pos->created_at->month; // Get numeric month (1-12)
                    $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                    // Sum total amounts per month
                    if (!isset($posChart[$month])) {
                        $posChart[$month] = 0; // Initialize if not set
                    }
                    $posChart[$month] += $pos->total;
                }

                // Fetch invoices for the current year
                $purchase_orders = PurchaseOrder::where('balance_due', 'PO')
                    ->where('status', 'invoice')
                    ->whereYear('created_at', now()->year) // Get all months of this year
                    ->get();

                // Loop through invoices and sum totals per month
                foreach ($purchase_orders as $po) {
                    $monthIndex = $po->created_at->month; // Get numeric month (1-12)
                    $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                    // Sum total amounts per month
                    if (!isset($poChart[$month])) {
                        $poChart[$month] = 0; // Initialize if not set
                    }
                    $poChart[$month] += $po->total;
                }

                $warrantyClaimCounts = Sell::select('part_number', DB::raw('count(*) as count'))
                    ->whereMonth('created_at', now()->month)
                    ->groupBy('part_number')
                    ->orderBy('count', 'desc')
                    ->take(8)
                    ->get();
                $SalePersons = Invoice::select('sale_by', DB::raw('SUM(net_total) as total_net'))
                    ->whereMonth('created_at', now()->month)->whereNotNull('sale_by')
                    ->where('status', 'invoice')
                    ->groupBy('sale_by')
                    ->orderByDesc('total_net')
                    ->take(8)
                    ->get();
            }
        } else {
            $invoiceCount = Invoice::where('status', 'invoice')
                // ->where('invoice_category', 'Invoice')
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->where('location', auth()->user()->level)
                ->count();
            $newcustomerCount = Customer::where('branch', auth()->user()->level)
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->count();
            $customerCount = Customer::where('branch', auth()->user()->level)->count();

            $posCount = Invoice::where('status', 'pos')
                // ->where('invoice_category', 'POS')
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->where('location', auth()->user()->level)
                ->count();

            $quotationCount = Invoice::where('status', 'quotation')
                ->where('invoice_category', 'quotation')
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->where('location', auth()->user()->level)
                ->count();

            $purchaseOrderCount = PurchaseOrder::where('balance_due', 'PO')
                ->where('status', 'invoice')
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->where('unit', auth()->user()->level)
                ->count();


            $monthNames = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'May',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Aug',
                9 => 'Sep',
                10 => 'Oct',
                11 => 'Nov',
                12 => 'Dec',
            ];

            $chart = [];
            $posChart = [];
            $poChart = [];

            // Fetch invoices for the current year
            $invoices = Invoice::where('status', 'invoice')
                // ->where('invoice_category', 'Invoice')
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->where('location', auth()->user()->level)
                ->get();

            // Loop through invoices and sum totals per month
            foreach ($invoices as $invoice) {
                $monthIndex = $invoice->created_at->month; // Get numeric month (1-12)
                $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                // Sum total amounts per month
                if (!isset($chart[$month])) {
                    $chart[$month] = 0; // Initialize if not set
                }
                $chart[$month] += $invoice->total;
            }

            // Fetch point of sales for the current year
            $point_of_sales = Invoice::where('status', 'pos')
                // ->where('invoice_category', 'POS')
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->where('location', auth()->user()->level)
                ->get();

            // Loop through point of sales and sum totals per month
            foreach ($point_of_sales as $pos) {
                $monthIndex = $pos->created_at->month; // Get numeric month (1-12)
                $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                // Sum total amounts per month
                if (!isset($posChart[$month])) {
                    $posChart[$month] = 0; // Initialize if not set
                }
                $posChart[$month] += $pos->total;
            }

            // Fetch purchase orders for the current year
            $purchase_orders = PurchaseOrder::where('balance_due', 'PO')
                ->where('status', 'invoice')
                ->whereMonth('created_at', now()->month) // Current month
                ->whereYear('created_at', now()->year)   // Current year
                ->where('unit', auth()->user()->level)
                ->get();

            // Loop through purchase orders and sum totals per month
            foreach ($purchase_orders as $po) {
                $monthIndex = $po->created_at->month; // Get numeric month (1-12)
                $month = $monthNames[$monthIndex] ?? $monthIndex; // Get month name

                // Sum total amounts per month
                if (!isset($poChart[$month])) {
                    $poChart[$month] = 0; // Initialize if not set
                }
                $poChart[$month] += $po->total;
            }
            $warrantyClaimCounts = Sell::select('part_number', DB::raw('count(*) as count'))->where('warehouse', auth()->user()->level)
                ->whereMonth('created_at', now()->month)
                ->groupBy('part_number')
                ->orderBy('count', 'desc')
                ->take(8)
                ->get();
            $SalePersons = Invoice::select('sale_by', DB::raw('SUM(net_total) as total_net'))
                ->whereMonth('created_at', now()->month)
                ->where('location', auth()->user()->level)
                ->where('status', 'invoice')->whereNotNull('sale_by')
                ->groupBy('sale_by')
                ->orderByDesc('total_net')
                ->take(8)
                ->get();
        }


        // dd($chart);

        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Locations';
        return view('dashboard', compact('invoiceCount', 'posCount', 'quotationCount', 'purchaseOrderCount', 'purchaseOrderCount', 'chart', 'posChart', 'poChart', 'warrantyClaimCounts', 'branchs', 'currentBranchName', 'branchNames', 'SalePersons', 'newcustomerCount', 'customerCount'));
    }

    public function home()
    {

        return view('home');
    }
}
