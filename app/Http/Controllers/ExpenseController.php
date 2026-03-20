<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Warehouse;

class ExpenseController extends Controller
{
    public function index()
    {
        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {

        // } else {
        //     $expenses = Expense::where('branch', auth()->user()->level)->latest()->get();
        //     $categories = ExpenseCategory::latest()->get();
        //     $branches = Warehouse::latest()->get();
        // }


        if (auth()->user()->is_admin == '1') {
            $expenses = Expense::latest()->get();
            $categories = ExpenseCategory::all();
            $branches = Warehouse::latest()->get();
        } else {
            $expenses = Expense::where('branch', auth()->user()->level)->latest()->get();
            $categories = ExpenseCategory::all();

            // dd($categories);
            $branches = Warehouse::latest()->get();
        }

        return view('expense.expense', [
            "expenses" => $expenses,
            "categories" => $categories,
            "branches" => $branches
        ]);
    }

    public function expenseStore(Request $request)
    {

        Expense::create($request->all());
        return redirect(url('expense'))->with('success', 'Expense Created Successfully!');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::latest()->get();
        $branches = Warehouse::latest()->get();
        return view('expense.expenseEdit', compact('expense', 'categories', 'branches'));
    }

    public function update(Request $request, Expense $expense)
    {

        $expense->update($request->all());
        return redirect(url('expense'))->with('success', 'Expense Updated Successfully!');
    }

    public function delete(Expense $expense)
    {
        // $unit = Expense::find($id);
        $expense->delete();
        return redirect()->back()->with('delete', 'Expense Deleted Successfully!');
    }

    public function get_part_data_unit()
    {
        $units = Expense::all();
        return response()->json($units);
    }

    public function expense_get_transaction(Request $request)
    {
        $location = $request->locationId;


        $settings = Setting::where('branch_id', $location)
            ->where('category', 'Expense')
            ->get();

        if ($settings->isNotEmpty()) {
            $transaction = collect();
            foreach ($settings as $setting) {
                $relatedTransactions = Transaction::where('location', $location)
                    ->where('id', $setting->transaction_id)
                    ->get();

                $transaction = $transaction->merge($relatedTransactions);
            }

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
