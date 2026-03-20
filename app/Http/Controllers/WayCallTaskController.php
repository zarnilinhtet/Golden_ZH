<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WayAssign;
use App\Models\SalePerson;
use App\Models\WayCallTask;
use Illuminate\Http\Request;

class WayCallTaskController extends Controller
{
    public function index($id)
    {
        $sale_person_assign = WayAssign::find($id);
        $sale_persons = $sale_person_assign->sale_person;
        $sale_person = [];
        $levelIds = explode(',', $sale_persons);
        if (auth()->user()->is_admin == '1') {
            foreach ($levelIds as $levelId) {
                $way_call_tasks[] = WayCallTask::where('sale_person_id', $levelId)->where('assign_id', $id)->get();
                $sale_person[] = SalePerson::find($levelId);
            }
        } else if (auth()->user()->type == '2') {
            $way_call_tasks[] = WayCallTask::where('sale_person_id', 'LIKE', "%" . auth()->user()->sale_person_id . "%")->where('assign_id', $id)->get();
            $sale_person[] = SalePerson::find(auth()->user()->sale_person_id);
        } else {
            foreach ($levelIds as $levelId) {
                $way_call_tasks[] = WayCallTask::where('sale_person_id', $levelId)->where('assign_id', $id)->get();
                $sale_person[] = SalePerson::find($levelId);
            }
        }
        // Logic to retrieve and display way call tasks

        $branches = Warehouse::latest()->get(); // Assuming you need branches as well

        return view('way_call_task.way_call_task', compact('way_call_tasks', 'branches', 'sale_person', 'sale_person_assign', 'id'));
    }

    //
    public function store(Request $request)
    {
        $way_call_task = new WayCallTask();
        $way_call_task->sale_person_id = $request->sale_person_id;
        $way_call_task->assign_id = $request->assign_id;
        $way_call_task->doctor_name = $request->doctor_name;
        $way_call_task->designation = $request->designation;
        $way_call_task->speciality = $request->speciality;
        $way_call_task->hp_clinic = $request->hp_clinic;
        $way_call_task->address = $request->address;
        $way_call_task->date = $request->date;
        $way_call_task->location = $request->location;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = time() . $file->getClientOriginalName();
            $file->move(public_path('upload/task'), $filename);
            $way_call_task->image = $filename;
        }
        $way_call_task->type = $request->type;
        $way_call_task->date = $request->date;
        $way_call_task->save();

        return redirect()->back()->with('success', 'Way Call Task Created Successfully');
    }
    public function edit($id)
    {
        $way_call_task = WayCallTask::find($id);
        $branches = Warehouse::latest()->get(); // Assuming you need branches as well

        return view('way_call_task.way_call_task_edit', compact('way_call_task', 'branches'));
    }
    public function update(Request $request, $id)
    {

        $way_call_task = WayCallTask::find($id);
        $way_call_task->doctor_name = $request->doctor_name;
        $way_call_task->designation = $request->designation;
        $way_call_task->speciality = $request->speciality;
        $way_call_task->hp_clinic = $request->hp_clinic;
        $way_call_task->address = $request->address;
        $way_call_task->date = $request->date;
        $way_call_task->location = $request->location;
        if ($request->file('image')) {

            $file = $request->file('image');
            $filename = time() . $file->getClientOriginalName();
            $file->move(public_path('upload/task'), $filename);
            $way_call_task->image = $filename;
        }else if($request->old_image != null){

            $way_call_task->image = $request->old_image; // Keep the old image if no new image is uploaded
        } else {
            $way_call_task->image = null; // Clear the image if no image is uploaded and no old image is provided
        }
        $way_call_task->type = $request->type;
        $way_call_task->date = $request->date;
        $way_call_task->save();

        return redirect(url('way_call_task', $way_call_task->assign_id))->with('success', 'Way Call Task Updated Successfully');
    }
    public function delete($id){
        $way_call_task = WayCallTask::find($id);
        if ($way_call_task) {
            $way_call_task->delete();
            return redirect()->back()->with('delete', 'Way Call Task Deleted Successfully');
        }
        return redirect()->back()->with('error', 'Way Call Task Not Found');
    }
}
