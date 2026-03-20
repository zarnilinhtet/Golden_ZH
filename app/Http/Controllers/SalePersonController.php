<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\SalePerson;
use Illuminate\Http\Request;
use App\Models\SalePersonReport;
use App\Models\WayAssign;

class SalePersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sale_person()
    {
        if (auth()->user()->is_admin == '1') {
            $sale_persons = SalePerson::all();
        } else {
            $sale_persons = SalePerson::where('location', auth()->user()->level)->get();
        }
        // dd($sale_persons);
        $branches = Warehouse::all();
        return view('sale_person.sale_person', compact('sale_persons', 'branches'));
    }


    public function sale_person_store(Request $request)
    {
        $sale_person = new SalePerson();
        $sale_person->name = $request->name;
        $sale_person->phno = $request->phno;
        $sale_person->address = $request->address;
        $sale_person->location = $request->location;
        $sale_person->principal = $request->principal;
        $sale_person->position = $request->position;
        $sale_person->save();
        return redirect(url('sale_person'))->with('success', 'Sale Person Create Successful');
    }



    public function sale_person_edit(string $id)
    {
        $sale_person = SalePerson::find($id);
        $branches = Warehouse::all();

        return view('sale_person.sale_person_edit', compact('sale_person', 'branches'));
    }


    public function sale_person_update(Request $request, string $id)
    {
        $sale_person = SalePerson::find($id);
        $sale_person->name = $request->name;
        $sale_person->phno = $request->phno;
        $sale_person->address = $request->address;
        $sale_person->location = $request->location;
        $sale_person->principal = $request->principal;
        $sale_person->position = $request->position;
        $sale_person->save();
        return redirect(url('sale_person'))->with('success', 'Sale Person Update Successful');
    }


    public function sale_person_delete(string $id)
    {
        $sale_person = SalePerson::find($id);
        $sale_person->delete();
        return redirect(url('sale_person'))->with('delete', 'Sale Person Delete Successful');
    }
    public function sale_person_report($id)
    {
        $sale_person_assign = WayAssign::find($id);
        $sale_persons = $sale_person_assign->sale_person;
        $sale_person = [];
        $levelIds = explode(',', $sale_persons);
        if (auth()->user()->is_admin == '1') {
            foreach ($levelIds as $levelId) {
                $sale_person_reports[] = SalePersonReport::where('sale_person_id', $levelId)->get();
                $sale_person[] = SalePerson::find($levelId);
            }
        } else if (auth()->user()->type == '2') {
            $sale_person_reports = SalePersonReport::where('sale_person_id', auth()->user()->sale_person_id)->get();
            $sale_person[] = SalePerson::find(auth()->user()->sale_person_id);
        } else {
            foreach ($levelIds as $levelId) {
                $sale_person_reports[] = SalePersonReport::where('sale_person_id', $levelId)->get();
                $sale_person[] = SalePerson::find($levelId);
            }
        }



        return view('sale_person_report.sale_person_report', compact('sale_person_reports', 'sale_person'));
    }
    public function sale_person_report_store(Request $request)
    {
        $sale_person_report = new SalePersonReport();
        $sale_person_report->sale_person_id = $request->sale_person_id;
        $sale_person = SalePerson::find($request->sale_person_id);
        $sale_person_report->sale_person_name = $sale_person->name;
        $sale_person_report->report_date = $request->report_date;
        $sale_person_report->description = $request->description;
        $sale_person_report->save();
        return redirect(url('sale_person_report', $request->sale_person_id))->with('success', 'Sale Person Report Create Successful');
    }
    public function sale_person_report_edit($id)
    {
        $report = SalePersonReport::find($id);
        $sale_person = SalePerson::find($report->sale_person_id);
        return view('sale_person_report.sale_person_report_edit', compact('report', 'sale_person'));
    }
    public function sale_person_report_update(Request $request, $id)
    {
        $report = SalePersonReport::find($id);
        $report->sale_person_name = $request->name;
        $report->sale_person_id = $request->sale_person_id;
        $report->report_date = $request->report_date;
        $report->description = $request->description;
        $report->save();
        return redirect(url('sale_person_report', $report->sale_person_id))->with('success', 'Sale Person Report Update Successful');
    }
    public function sale_person_report_delete($id, $sale_person_id)
    {
        $report = SalePersonReport::find($id);
        $report->delete();
        return redirect(url('sale_person_report', $sale_person_id))->with('delete', 'Sale Person Report Delete Successful');
    }
}
