<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
            $suppliers = Doctor::latest()->get();
            $branches = Warehouse::latest()->get();
        } else {
            $suppliers = Doctor::where('branch', auth()->user()->level)->latest()->get();
            $branches = Warehouse::latest()->get();
        }

        return view('doctor.doctor', compact('suppliers', 'branches'));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required',
                'phno' => 'required',
                'address' => 'required',
                'sale_commission' => 'nullable',
                'description' => 'nullable',
            ]);

            $existingDoctor = Doctor::where('name', $request->name)->where('branch', $request->branch)->first();

            if ($existingDoctor) {
                return redirect()->back()->with('error', 'Error: Doctor with the same name and branch already exists.');
            }
            if ($request->sale_commission_percentage == null && $request->sale_commission == null) {
                return redirect()->back()->with('error', 'Please enter sale commission or sale commission percentage.');
            }

            $doctor = new Doctor();
            $doctor->name = $request->name;
            $doctor->phno = $request->phno;
            $doctor->address = $request->address;

            if ($request->sale_commission_percentage != null) {
                $doctor->sale_commission_percentage = $request->sale_commission_percentage;
            } else {
                $doctor->sale_commission = $request->sale_commission;
            }
            $doctor->description = $request->description;
            $doctor->branch = $request->branch;
            $doctor->save();

            return redirect()->back()->with('success', 'Doctor Added Successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        $supplier = Doctor::find($id);
        $branches = Warehouse::latest()->get();

        return view(
            'doctor.doctor_edit',
            compact('supplier', 'branches')
        );
    }
    public function update(Request $request, $id)
    {
        $supplier = Doctor::find($id);
        $supplier->update($request->all());
        return redirect('doctors')->with('success', 'Doctors Updated Successful!');
    }
    public function delete($id)
    {
        $supplier = Doctor::find($id);
        $supplier->delete();
        return redirect('doctors')->with('success', 'Doctors Delete Successful!');
    }
}
