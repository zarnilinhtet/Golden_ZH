<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\PatientFile;
use App\Models\ReturnInvoice;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //index

    public function service($branch = null)
    {
        if ($branch) {
            $customers = Customer::where('type', $branch)->latest()->get();
            // dd($customers);
        } else {
            $customers = Customer::latest()->get();
        }
        $branches = Warehouse::latest()->get();

        // $branchNames = $branch_drop->pluck('name', 'id');

        $branch_drop = ['OG', 'OTO', 'Surgery', 'General'];
        if ($branch) {
            $currentBranchName = $branch;
        } else {
            $currentBranchName = 'All Type';
        }

        return view('customer.service', compact('customers', 'branches', 'currentBranchName', 'branch_drop'));
    }
    public function service_search(Request $request, $branch = null)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        if ($request->branch == 'All Type') {
            $customers = Customer::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->latest()->get();
            // dd($customers);
        } else {
            $customers = Customer::where('type', $request->branch)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->latest()->get();
        }
        $branches = Warehouse::latest()->get();

        // $branchNames = $branch_drop->pluck('name', 'id');

        $branch_drop = ['OG', 'OTO', 'Surgery', 'General'];
        if ($request->branch) {
            $currentBranchName = $request->branch;
        } else {
            $currentBranchName = 'All Type';
        }

        return view('customer.service', compact('customers', 'branches', 'currentBranchName', 'branch_drop'));
    }
    public function index()
    {
        if (auth()->user()->is_admin == '1') {
            $customers = Customer::latest()->get();
        } else {
            $customers = Customer::where('branch', auth()->user()->level)->latest()->get();
        }
        $branches = Warehouse::latest()->get();
        return view('customer.customer', compact('customers', 'branches'));
    }

    public function credit($id)
    {
        $customer = Customer::find($id);

        // Get all invoices for the customer
        $invoices = Invoice::where('customer_id', $id)->get();

        // Get returns related to those invoices
        $invoiceIds = $invoices->pluck('id');
        $returns = ReturnInvoice::where('customer_id', $id)
            ->whereIn('invoice_id', $invoiceIds)
            ->get()
            ->groupBy('invoice_id');

        // Adjust invoices
        $adjustedInvoices = $invoices->map(function ($invoice) use ($returns) {
            $return = $returns->get($invoice->id)?->first();

            $invoice->adjusted_total = max(0, $invoice->total - ($return->total ?? 0));
            $invoice->adjusted_deposit = max(0, $invoice->deposit);
            $invoice->adjusted_remain = max(0, $invoice->remain_balance - ($return->total ?? 0)); // HERE

            return $invoice;
        })->filter(function ($invoice) {
            // Return only if remain balance still exists
            return $invoice->adjusted_remain > 0;
        })->values();

        $total_amount = $adjustedInvoices->sum('adjusted_total');
        $balance = $adjustedInvoices->sum('adjusted_remain');
        $deposit = $adjustedInvoices->sum('adjusted_deposit');

        return view('customer.credit', [
            'invoices' => $adjustedInvoices,
            'customer' => $customer,
            'total_amount' => $total_amount,
            'balance' => $balance,
            'deposit' => $deposit,
        ]);
    }

    public function customer_invoice($id)
    {
        $customer = Customer::find($id);
        $filter = request('filter');

        $query = Invoice::where('customer_id', $id);

        if ($filter) {
            $query->where('status', $filter);
        } else {
            $query->whereIn('status', ['Invoice', 'pos']);
        }

        $invoices = $query->get();

        $total_amount = $invoices->sum('total');
        $balance = $invoices->sum('remain_balance');
        $deposit = $invoices->sum('deposit');

        return view('customer.customer_invoice_remark', compact('invoices', 'customer', 'total_amount', 'balance', 'deposit'));
    }

    // public function all_customer_credit()
    // {
    //     $customers = Customer::whereHas('invoices', function ($query) {
    //         $query->whereNotNull('remain_balance')->where('remain_balance', '<>', 0);
    //     })->with(['invoices' => function ($query) {
    //         $query->whereNotNull('remain_balance')->where('remain_balance', '<>', 0);
    //     }])->get();
    //     $invoices = [];
    //     // dd($customers);
    //     // Assuming $customers is a collection of customer objects
    //     $customers->each(function ($customer) use (&$invoices) {
    //         $customerInvoices = $customer->invoices->filter(function ($invoice) {
    //             return $invoice->remain_balance != 0;
    //         })->map(function ($invoice) {
    //             return [
    //                 'invoice_no' => $invoice->invoice_no,
    //                 'remain_balance' => $invoice->remain_balance,
    //                 'sale_person' => $invoice->SaleBy->name ?? '',
    //                 'customer_id' => $invoice->customer_id, // Assuming customer_id is a property of the invoice
    //             ];
    //         })->toArray();

    //         $invoices = array_merge($invoices, $customerInvoices);
    //     });

    //     return view('customer.all_customer_credit', compact('customers', 'invoices'));
    // }
    public function all_customer_credit()
    {
        $customers = Customer::whereHas('invoices')
            ->with(['invoices.SaleBy']) // eager load SaleBy
            ->get();

        // Get all invoice IDs
        $allInvoices = $customers->flatMap->invoices;
        $invoiceIds = $allInvoices->pluck('id');

        // Get ReturnInvoices grouped by invoice_id
        $returns = ReturnInvoice::whereIn('invoice_id', $invoiceIds)
            ->get()
            ->groupBy('invoice_id');

        $invoices = [];

        $customers = $customers->map(function ($customer) use (&$invoices, $returns) {
            $filteredInvoices = [];

            foreach ($customer->invoices as $invoice) {
                $return = $returns->get($invoice->id)?->first();

                $adjusted_remain = max(0, $invoice->remain_balance - ($return->total ?? 0));

                if ($adjusted_remain > 0) {
                    $filteredInvoices[] = $invoice;

                    $invoices[] = [
                        'invoice_no' => $invoice->invoice_no,
                        'remain_balance' => $adjusted_remain,
                        'sale_person' => $invoice->SaleBy->name ?? '',
                        'customer_id' => $invoice->customer_id,
                    ];
                }
            }

            $customer->filtered_invoices = collect($filteredInvoices);
            return $customer;
        });

        return view('customer.all_customer_credit', compact('customers', 'invoices'));
    }

    public function store(Request $request)
    {

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phno = $request->phno;
        $customer->branch = $request->branch;
        $customer->inout_patient = $request->inout_patient;
        $customer->age = $request->age;
        $customer->gender = $request->gender;
        $customer->address = $request->address;
        $customer->nrc = $request->nrc;
        $customer->dob = $request->dob;
        $customer->company = $request->company;
        $customer->department = $request->department;
        $customer->deposit = $request->deposit;
        $customer->cdc_no = $request->cdc_no;
        $customer->patient_status = json_encode($request->patient_status);
        $customer->patient_type = json_encode($request->patient_type);
        $customer->save();
        return redirect()->back()->with('success', 'New Patient Added Successful!');
    }
    public function edit(Request $request, $id)
    {
        $showCustomer = Customer::find($id);
        $branches = Warehouse::latest()->get();
        return view('customer.customer_edit', compact('showCustomer', 'branches'));
    }
    public function update($id, Request $request)
    {
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->phno = $request->phno;
        $customer->branch = $request->branch;
        $customer->inout_patient = $request->inout_patient;
        $customer->age = $request->age;
        $customer->gender = $request->gender;
        $customer->address = $request->address;
        $customer->nrc = $request->nrc;
        $customer->dob = $request->dob;
        $customer->company = $request->company;
        $customer->department = $request->department;
        $customer->deposit = $request->deposit;
        $customer->cdc_no = $request->cdc_no;
        $customer->patient_status = json_encode($request->patient_status);
        $customer->patient_type = json_encode($request->patient_type);
        $customer->save();
        $customers = Customer::latest()->get();
        return redirect('customer')->with('success', 'Patient Updated Successful!');
    }
    public function delete($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return redirect('customer')->with('error', 'Patient Deleted Successful!');
    }
    public function view($id)
    {

        $customer = Customer::find($id);
        $branches = Warehouse::latest()->get();

        return view('customer.customer_view', compact('customer', 'branches'));
    }
    public function upload_file($id)
    {

        $customer = Customer::find($id);
        $customer_files = PatientFile::where('customer_id', $id)->get();
        $file_count = $customer_files->count();


        return view('customer.upload_file', compact('customer', 'customer_files', 'file_count'));
    }


    public function upload_file_store(Request $request, $id)
    {
        if ($request->hasFile('file1')) {
            $rules['file1'] = 'file|mimes:jpeg,png,pdf,doc,docx|max:1024';
        }

        if ($request->hasFile('file2')) {
            $rules['file2'] = 'file|mimes:jpeg,png,pdf,doc,docx|max:1024';
        }

        if ($request->hasFile('file3')) {
            $rules['file3'] = 'file|mimes:jpeg,png,pdf,doc,docx|max:1024';
        }

        if ($request->hasFile('file4')) {
            $rules['file4'] = 'file|mimes:jpeg,png,pdf,doc,docx|max:1024';
        }

        if ($request->hasFile('file5')) {
            $rules['file5'] = 'file|mimes:jpeg,png,pdf,doc,docx|max:1024';
        }

        $validatedData = $request->validate($rules, [
            'file1.max' => 'File 1 size must be less than 1MB.',
            'file2.max' => 'File 2 size must be less than 1MB.',
            'file3.max' => 'File 3 size must be less than 1MB.',
            'file4.max' => 'File 4 size must be less than 1MB.',
            'file5.max' => 'File 5 size must be less than 1MB.',
            'file1.mimes' => 'File 1 must be a file of type: jpeg, png, pdf.',
            'file2.mimes' => 'File 2 must be a file of type: jpeg, png, pdf.',
            'file3.mimes' => 'File 3 must be a file of type: jpeg, png, pdf.',
            'file4.mimes' => 'File 4 must be a file of type: jpeg, png, pdf.',
            'file5.mimes' => 'File 5 must be a file of type: jpeg, png, pdf.',
        ]);

        $customer = new PatientFile();
        $image1 = $request->file('file1');
        $old1 = $customer->file1;
        if ($image1) {
            $file1name = time() . '.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('logos'), $file1name);
            $customer->file1 = $file1name;
            if ($old1 && $old1 !== $file1name) {
                $oldImagePath = public_path('logos') . '/' . $old1;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old2 = $customer->file2;
        $image2 = $request->file('file2');
        if ($image2) {
            $file2name = time() . '.' . $image2->getClientOriginalExtension();
            $image2->move(public_path('logos'), $file2name);
            $customer->file2 = $file2name;
            if ($old2 && $old2 !== $file2name) {
                $oldImagePath = public_path('logos') . '/' . $old2;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old3 = $customer->file3;
        $image3 = $request->file('file3');
        if ($image3) {
            $file3name = time() . '.' . $image3->getClientOriginalExtension();
            $image3->move(public_path('logos'), $file3name);
            $customer->file3 = $file3name;
            if ($old3 && $old3 !== $file3name) {
                $oldImagePath = public_path('logos') . '/' . $old3;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old4 = $customer->file4;
        $image4 = $request->file('file4');
        if ($image4) {
            $file4name = time() . '.' . $image4->getClientOriginalExtension();
            $image4->move(public_path('logos'), $file4name);
            $customer->file4 = $file4name;
            if ($old4 && $old4 !== $file4name) {
                $oldImagePath = public_path('logos') . '/' . $old4;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old5 = $customer->file5;
        $image5 = $request->file('file5');
        if ($image5) {
            $file5name = time() . '.' . $image5->getClientOriginalExtension();
            $image5->move(public_path('logos'), $file5name);
            $customer->file5 = $file5name;
            if ($old5 && $old5 !== $file5name) {
                $oldImagePath = public_path('logos') . '/' . $old5;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $customer->customer_id = $id;
        $customer->date = $request->date;
        $customer->save();
        return redirect()->back()->with('success', 'File Uploaded Successful!');
    }
    public function file_edit($id)
    {

        $customer = PatientFile::find($id);
        return view('customer.upload_file_edit', compact('customer'));
    }
    public function file_update(Request $request, $id)
    {

        $customer = PatientFile::find($id);
        $customer->date = $request->date;
        $image1 = $request->file('file1');
        $old1 = $customer->file1;
        if ($image1) {
            $file1name = time() . '.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('logos'), $file1name);
            $customer->file1 = $file1name;
            if ($old1 && $old1 !== $file1name) {
                $oldImagePath = public_path('logos') . '/' . $old1;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old2 = $customer->file2;
        $image2 = $request->file('file2');
        if ($image2) {
            $file2name = time() . '.' . $image2->getClientOriginalExtension();
            $image2->move(public_path('logos'), $file2name);
            $customer->file2 = $file2name;
            if ($old2 && $old2 !== $file2name) {
                $oldImagePath = public_path('logos') . '/' . $old2;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old3 = $customer->file3;
        $image3 = $request->file('file3');
        if ($image3) {
            $file3name = time() . '.' . $image3->getClientOriginalExtension();
            $image3->move(public_path('logos'), $file3name);
            $customer->file3 = $file3name;
            if ($old3 && $old3 !== $file3name) {
                $oldImagePath = public_path('logos') . '/' . $old3;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old4 = $customer->file4;
        $image4 = $request->file('file4');
        if ($image4) {
            $file4name = time() . '.' . $image4->getClientOriginalExtension();
            $image4->move(public_path('logos'), $file4name);
            $customer->file4 = $file4name;
            if ($old4 && $old4 !== $file4name) {
                $oldImagePath = public_path('logos') . '/' . $old4;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $old5 = $customer->file5;
        $image5 = $request->file('file5');
        if ($image5) {
            $file5name = time() . '.' . $image5->getClientOriginalExtension();
            $image5->move(public_path('logos'), $file5name);
            $customer->file5 = $file5name;
            if ($old5 && $old5 !== $file5name) {
                $oldImagePath = public_path('logos') . '/' . $old5;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $customer->save();
        return redirect(url('upload_file', $customer->customer_id))->with('success', 'File Updated Successful!');
    }
    public function file_delete($id)
    {
        $customer = PatientFile::find($id);
        $customer->delete();
        return redirect()->back()->with('success', 'File Deleted Successful!');
    }

    public function birthday_reminder()
    {
        $branches = Warehouse::latest()->get();
        $today = Carbon::now();
        $nextWeek = $today->copy()->addWeek();
        $customers = Customer::whereBetween('dob', [$today, $nextWeek])
            ->get();

        return view('customer.birthday_reminder', compact('customers', 'branches'));
    }
}
