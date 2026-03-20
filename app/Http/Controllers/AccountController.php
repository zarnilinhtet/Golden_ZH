<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // public function account()
    // {
    //     $branches = Warehouse::latest()->get();

    //     return view('account.account', compact('branches'));
    // }

    public function accountRegister(Request $request)
    {

        $request->validate([
            'account_code' => 'required|unique:accounts',
        ], [
            'account_code.unique' => 'Account Number Already Exists!',
        ]);

        $accountData = new Account();
        $accountData->account_code = $request->account_code;
        $accountData->account_name = $request->account_name;
        $accountData->account_type = $request->account_type;
        $accountData->location = $request->location;
        $accountData->account_bl_pl = $request->account_bl_pl;
        // $accountData->description = $request->description;

        $accountData->save();
        return redirect()->back()->with('success', 'Account Register is Successfull');
    }

    public function accountStore($branch = null)
    {
        // $accountList = Account::latest()->get();
        $branches = Warehouse::latest()->get();
        $branch_drop = Warehouse::all();




        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            if ($branch) {
                $accountList = Account::where('location', $branch)->get();
            } else {
                $accountList = Account::latest()->get();
            }
        } else {
            $accountList = Account::where('location', auth()->user()->level)->get();
        }
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Accounts';
        return view('account.account', compact('accountList', 'branches', 'currentBranchName', 'branch_drop'));
    }

    public function delete($id)
    {
        $account = Account::find($id);
        $account->delete();
        return redirect()->back()->with('deleteStatus', 'Account Delete is Successfull');
    }

    public function show($id)
    {
        $accounts = Account::find($id);
        $branches = Warehouse::latest()->get();
        return view('account.accountsEdit', compact('accounts', 'branches'));
    }

    public function update(Request $request, $id)
    {

        $account = Account::find($id);
        $account->update($request->all());
        return redirect('account')->with('updateStatus', 'Account Update is Successfull');
    }
}
