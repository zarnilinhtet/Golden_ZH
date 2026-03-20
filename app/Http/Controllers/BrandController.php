<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function brand()
    {
        if (auth()->user()->is_admin == '1') {
            $brand = Brand::latest()->get();
            $branches = Warehouse::latest()->get();
        } else {
            $brand = Brand::where('branch', auth()->user()->level)->latest()->get();
            $branches = Warehouse::latest()->get(); // This is shared too
        }

        return view('brand.brand', compact('branches', 'brand'));
    }


    public function brand_store(Request $request)
    {
        Brand::create($request->all());
        return redirect()->back()->with('success', 'Brand Created Successfully!');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        $branches = Warehouse::latest()->get();
        return view('brand.brand_edit', compact('brand', 'branches'));
    }

    public function brand_update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($request->all());
        return redirect('brand')->with('success', 'Brand Updated Successfully!');
    }
    public function brand_delete($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return redirect('brand')->with('error', 'Brand Deleted Successfully!');
    }
}
