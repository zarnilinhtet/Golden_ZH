<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Warehouse;
use App\Models\WayAssign;
use App\Models\SalePerson;
use Illuminate\Http\Request;

class WayAssignController extends Controller
{
    public function index()
    {

        if (auth()->user()->is_admin == '1') {
            // $brand = Brand::latest()->get();
            $branches = Warehouse::latest()->get();
            $assigns = WayAssign::latest()->get();
        } else if (auth()->user()->type == '2') {
            $branches = Warehouse::where('id', auth()->user()->level)->latest()->get();
            $salePersonId = auth()->user()->sale_person_id;

            $assigns = WayAssign::where('sale_person', 'LIKE', "%{$salePersonId}%")->latest()->get();

            // $assigns = WayAssign::whereIn('sale_person', auth()->user()->sale_person_id)->latest()->get();
        } else {
            // $brand = Brand::where('branch', auth()->user()->level)->latest()->get();
            $branches = Warehouse::latest()->get(); // This is shared too
            $assigns = WayAssign::where('location', auth()->user()->level)->latest()->get();
        }
        $sale_persons = SalePerson::latest()->get();



        return view('sale_person_way_assign.sale_person_assign', compact('branches', 'sale_persons', 'assigns'));
    }
    public function store(Request $request)
    {

        $assign = new WayAssign();
        $assign->name = $request->name;
        $assign->phno = $request->phno;
        $assign->address = $request->address;
        $assign->location = $request->branch;
        $assign->sale_person = implode(',', $request->input('sale_person', []));

        $assign->assign = $request->assign;
        $assign->date = $request->date;
        $assign->call = $request->call;
        $assign->description = $request->description;

        $assign->save();
        return redirect()->back()->with('success', 'Way Assign Successfully');
    }
    public function edit($id)
    {
        if (auth()->user()->is_admin == '1') {
            // $brand = Brand::latest()->get();
            $branches = Warehouse::latest()->get();
        } else {
            // $brand = Brand::where('branch', auth()->user()->level)->latest()->get();
            $branches = Warehouse::latest()->get(); // This is shared too
        }
        $assign = WayAssign::find($id);
        $sale_persons = SalePerson::latest()->get();
        return view('sale_person_way_assign.sale_person_assign_edit', compact('assign', 'branches', 'sale_persons'));
    }
    public function update(Request $request, $id)
    {
        $assign = WayAssign::find($id);
        $assign->name = $request->name;
        $assign->phno = $request->phno;
        $assign->address = $request->address;
        $assign->location = $request->branch;
        $assign->sale_person = implode(',', $request->input('sale_person', []));
        $assign->assign = $request->assign;
        $assign->date = $request->date;
        $assign->call = $request->call;
        $assign->description = $request->description;

        $assign->save();
        return redirect(url('sale_person_assign'))->with('success', 'Way Assign Update Successfully');
    }
    public function delete($id)
    {
        $assign = WayAssign::find($id);
        $assign->delete();
        return redirect(url('sale_person_assign'))->with('success', 'Way Assign Delete Successfully');
    }

    public function way_call()
    {

        // $education_yrs = SalePerson::whereHas('invoices')
        //     ->with(['invoices' => function ($q) {
        //         $q->selectRaw('sale_by, YEAR(invoice_date) as year')
        //             ->distinct()
        //             ->orderBy('year', 'desc');
        //     }])
        //     ->get();
        if (auth()->user()->is_admin == '1') {
            $education_yrs = WayAssign::whereHas('SalePerson')
                ->selectRaw('YEAR(date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->get();
        } else if (auth()->user()->type == '2') {
            $education_yrs = WayAssign::whereHas('SalePerson')
                ->selectRaw('YEAR(date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->where('sale_person', 'LIKE', "%" . auth()->user()->sale_person_id . "%")
                ->get();
        }




        // dd($education_yrs);

        return view('sale_person_way_assign.select_years', compact('education_yrs'));
    }
    public function select_month($start)
    {
        // dd($start,$end);
        if (auth()->user()->is_admin == '1') {
            $assigns = WayAssign::whereHas('SalePerson')
                ->selectRaw('MONTH(date) as month')->whereYear('date', $start)
                ->distinct()
                ->orderBy('month', 'desc')
                ->get();
        } else if (auth()->user()->type == '2') {
            $assigns = WayAssign::whereHas('SalePerson')
                ->selectRaw('MONTH(date) as month')->whereYear('date', $start)
                ->distinct()
                ->orderBy('month', 'desc')
                ->where('sale_person', 'LIKE', "%" . auth()->user()->sale_person_id . "%")
                ->get();
        } else {
            $assigns = WayAssign::whereHas('SalePerson')
                ->selectRaw('MONTH(date) as month')->whereYear('date', $start)
                ->distinct()
                ->orderBy('month', 'desc')
                ->where('location', auth()->user()->level)
                ->get();
        }


        // dd($students);


        return view('sale_person_way_assign.select_months', compact('assigns', 'start'));
    }
    public function select_sale_person($year, $month)
    {

        $invoices = WayAssign::whereHas('SalePerson')
            ->selectRaw('sale_person')->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->distinct()
            ->orderBy('sale_person', 'desc')
            ->get();

        return view('sale_person_way_assign.select_sale_person', compact('invoices', 'year', 'month'));
    }
    public function show_way_call($year, $month, $id)
    {
        $sale_person = SalePerson::find($id);
        $ways = WayAssign::whereRaw('FIND_IN_SET(?, sale_person)', [$id])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();


        return view('sale_person_way_assign.show_way_call', compact('ways', 'year', 'month', 'id', 'sale_person'));
    }
}
