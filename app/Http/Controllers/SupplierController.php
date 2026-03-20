<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    //
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('supplier.supplier', compact('suppliers'));
    }
    public function store(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->phno = $request->phno ?? "";
        $supplier->address = $request->address ?? "";
        $supplier->save();
        return redirect()->back()->with('success', 'Supplier Added Successful!');
    }
    public function edit(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        return view(
            'supplier.supplier_edit',
            compact('supplier')
        );
    }
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->name = $request->name ?? "";
        $supplier->phno = $request->phno ?? "";
        $supplier->address = $request->address ?? "";
        $supplier->save();
        return redirect('supplier')->with('success', 'Supplier Updated Successful!');
    }
    public function delete($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return redirect('supplier')->with('success', 'Supplier Delete Successful!');
    }
}
