<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Customer;
use App\Models\Treatment;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\TreatmentSell;
use Illuminate\Support\Facades\Cache;

class TreatmentController extends Controller
{

    public function index()
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $treatments = Treatment::latest()->get();
            $branchs = Warehouse::all();
        } else {
            $treatments = Treatment::where('branch', $warehousePermission)->latest()->get();
            $branchs = Warehouse::where('id', $warehousePermission)->get();
        }

        return view('treatment.treatment', compact('branchs', 'treatments'));
    }

    public function treatment_search(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $treatments = Treatment::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->get();
            $branchs = Warehouse::all();
        } else {
            $treatments = Treatment::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('branch', $warehousePermission)->latest()->get();
            $branchs = Warehouse::whereIn('id', $warehousePermission)->get();
        }

        return view('treatment.treatment', compact('treatments', 'branchs'));
    }

    public function show_register()
    {

        $branchs = Warehouse::all();
        $doctors = Doctor::all();
        return view('treatment.treatment_register', compact('branchs', 'doctors'));
    }

    public function store(Request $request)
    {
        $treatment = new Treatment();
        $treatment->customer_id = $request->customer_id;
        $treatment->doctor_id = $request->doctor_id;

        $treatment->name = $request->name;

        $treatment->phno = $request->phno;
        // $treatment->type = $request->type;
        $treatment->customer_age = $request->age;
        $treatment->customer_gender = $request->gender;
        $treatment->nrc = $request->nrc;
        $treatment->dob = $request->dob;
        $treatment->patient_status = json_encode($request->patient_status);
        $treatment->patient_type = json_encode($request->patient_type);
        $treatment->department = $request->department;
        $treatment->inout_patient = $request->inout_patient;
        $treatment->customer_deposit = $request->customer_deposit;
        $treatment->cdc_no = $request->cdc_no;
        $treatment->company = $request->company;
        $treatment->branch = $request->branch;
        $treatment->address = $request->address;

        $treatment->diagnosis = $request->diagnosis;
        $treatment->treatment = $request->treatment;
        $treatment->investigation = $request->investigations;
        $treatment->history = $request->history;
        $treatment->physical_examination = $request->physical_examination;

        $image1 = $request->file('file1');
        $old1 = $treatment->file1;
        if ($image1) {
            $file1name = time() . '.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('logos'), $file1name);
            $treatment->file1 = $file1name;
            if ($old1 && $old1 !== $file1name) {
                $oldImagePath = public_path('logos') . '/' . $old1;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old2 = $treatment->file2;
        $image2 = $request->file('file2');
        if ($image2) {
            $file2name = time() . '.' . $image2->getClientOriginalExtension();
            $image2->move(public_path('logos'), $file2name);
            $treatment->file2 = $file2name;
            if ($old2 && $old2 !== $file2name) {
                $oldImagePath = public_path('logos') . '/' . $old2;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old3 = $treatment->file3;
        $image3 = $request->file('file3');
        if ($image3) {
            $file3name = time() . '.' . $image3->getClientOriginalExtension();
            $image3->move(public_path('logos'), $file3name);
            $treatment->file3 = $file3name;
            if ($old3 && $old3 !== $file3name) {
                $oldImagePath = public_path('logos') . '/' . $old3;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old4 = $treatment->file4;
        $image4 = $request->file('file4');
        if ($image4) {
            $file4name = time() . '.' . $image4->getClientOriginalExtension();
            $image4->move(public_path('logos'), $file4name);
            $treatment->file4 = $file4name;
            if ($old4 && $old4 !== $file4name) {
                $oldImagePath = public_path('logos') . '/' . $old4;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old5 = $treatment->file5;
        $image5 = $request->file('file5');
        if ($image5) {
            $file5name = time() . '.' . $image5->getClientOriginalExtension();
            $image5->move(public_path('logos'), $file5name);
            $treatment->file5 = $file5name;
            if ($old5 && $old5 !== $file5name) {
                $oldImagePath = public_path('logos') . '/' . $old5;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }




        $treatment->save();


        // for ($i = 0; $i < $count; $i++) {
        //     $result = new TreatmentSell();
        //     $result->treatment_id = $id;
        //     $result->item_descriptions = $request->part_number[$i];
        //     $result->product_qty = $request->product_qty[$i];
        //     $result->product_price = $request->product_price[$i];
        //     $result->buy_price = $request->buy_price[$i];
        //     $result->variation_id = $request->result_id[$i];
        //     $result->item_id = $request->item_id[$i];
        //     $result->retail_price = $request->retail_price[$i];
        //     $result->unit = $request->item_unit[$i];
        //     $result->warehouse = $request->warehouse[$i];
        //     $result->radio_category = $request->radio_category[$i];
        //     $result->warehouse = $request->warehouse[$i];
        //     $result->product_name = $request->result_item_name[$i];
        //     $result->exp_date = $request->exp_date[$i];
        //     $result->stock_and_service = $request->stock_and_service[$i];
        //     $result->status = $request->sell_status[$i] ?? '0';
        //     $result->save();
        // }


        return redirect(url('treatment'))->with('success', 'Treatment Register successfully!');
    }

    public function edit($id)
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];
        $doctors = Doctor::all();

        $treatment = Treatment::find($id);
        // $treatment_sell = TreatmentSell::where('treatment_id', $treatment->id)->get();

        if (auth()->user()->is_admin == '1') {
            $branchs = Warehouse::all();
        } else {
            $branchs = Warehouse::whereIn('id', $warehousePermission)->get();
        }


        return view('treatment.treatment_edit', compact('treatment', 'branchs', 'doctors'));
    }

    public function details($id)
    {

        $treatment = Treatment::find($id);
        // $treatment_sell = TreatmentSell::where('treatment_id', $treatment->id)->get();
        $branchs = Warehouse::all();

        return view('treatment.treatment_details', compact('treatment', 'branchs',));
    }


    public function update(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->customer_id = $request->customer_id;
        $treatment->doctor_id = $request->doctor_id;

        $treatment->name = $request->name;

        $treatment->phno = $request->phno;
        // $treatment->type = $request->type;
        $treatment->customer_age = $request->age;
        $treatment->customer_gender = $request->gender;
        $treatment->nrc = $request->nrc;
        $treatment->dob = $request->dob;
        $treatment->patient_status = json_encode($request->patient_status);
        $treatment->patient_type = json_encode($request->patient_type);
        $treatment->department = $request->department;
        $treatment->inout_patient = $request->inout_patient;
        $treatment->customer_deposit = $request->customer_deposit;
        $treatment->cdc_no = $request->cdc_no;
        $treatment->company = $request->company;
        $treatment->branch = $request->branch;
        $treatment->address = $request->address;

        $treatment->diagnosis = $request->diagnosis;
        $treatment->treatment = $request->treatment;
        $treatment->investigation = $request->investigations;




        $treatment->save();





        // Redirect with success message
        return redirect(url('treatment'))->with('success', 'Treatment updated successfully!');
    }


    public function destory($id)
    {

        $treatment = Treatment::find($id);
        // $treatment->treatment_sells()->delete();
        $treatment->delete();

        return redirect()->back()->with('delete', 'Treatment Delete Successfully!');
    }


    public function customer_search(Request $request)
    {
        info($request);
        $data = Customer::select('name', 'phno')
            ->where('branch', $request->location)
            ->where('name', 'LIKE', '%' . $request->get('query') . '%')
            ->orWhere('phno', 'LIKE', '%' . $request->get('query') . '%')
            ->get();
        info($request->location);
        return response()->json($data);
    }


    public function customer_search_fill(Request $request)
    {
        $product = Customer::where(function ($query) use ($request) {
            $query->where('name', $request->model)
                ->orWhere('phno', $request->model);
        })
            ->where('branch', $request->location)
            ->latest('created_at')
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $responseData = [
            'customer' => $product,
        ];

        return response()->json($responseData);
    }


    public function treatment_service_search(Request $request)
    {
        $query = $request->get('query');
        $location = $request->location;

        $data = Treatment::select('name', 'phno')
            ->where('branch', $location)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('phno', 'LIKE', '%' . $query . '%');
            })
            ->get();

        info($request->location);

        return response()->json($data);
    }




    // public function treatment_service_search_fill(Request $request)
    // {
    //     $model = $request->model;
    //     $location = $request->location;

    //     $product = Treatment::with('treatment_sells')->where(function ($query) use ($model) {
    //         $query->where('name', $model)
    //             ->orWhere('phno', $model);
    //     })
    //         ->where('branch', $location)
    //         ->orderBy('created_at', 'desc')
    //         ->first();

    //     if (!$product) {
    //         return response()->json(['error' => 'Customer not found'], 404);
    //     }

    //     return response()->json([
    //         'customer' => $product,
    //     ]);
    // }

    public function treatment_service_search_fill(Request $request)
    {
        $model = $request->model;
        $location = $request->location;

        $product = Treatment::where(function ($query) use ($model) {
            $query->where('name', $model)
                ->orWhere('phno', $model);
        })
            ->where('branch', $location)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Customer not found'], 404);
        }



        return response()->json([
            'customer' => $product,

        ]);
    }
}
