@include('layouts.header')
<style>
    #tableSelect {
        background-color: #6c757d;
        color: white;
    }

    #tableSelect option {
        background-color: white;
        color: black;
    }


    #tableSelect:focus {
        background-color: #6c757d;
        color: white;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.nav')
        @include('layouts.sidebar')
        <div class="content-wrapper">


            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">


                            <!-- Content Header (Page header) -->
                            <section class="content-header">
                                <div class="container-fluid">
                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <h1>Transaction Add Payment</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Transaction Add Payment</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div><!-- /.container-fluid -->
                            </section>
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('deleteStatus'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('deleteStatus') }}
                                </div>
                            @endif
                            @if (session('updateStatus'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('updateStatus') }}
                                </div>
                            @endif
                            <div class="container-fluid mb-4 mr-auto">
                                <div class="row">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-default text-white" data-toggle="modal"
                                            data-target="#modal-lg" style="background-color: #007BFF">
                                            Payment Register
                                        </button>
                                    </div>

                                    <div class="col-auto">
                                        <button type="button" class="btn btn-success text-white" data-toggle="modal"
                                            data-target="#modal-opening">
                                            Opening Balance
                                        </button>
                                    </div>

                                    <div class="col-auto">
                                        <a href="{{ url('delete_record_payment', $transaction->id) }}"
                                            class="btn btn-danger text-white">
                                            Payment Delete Record
                                        </a>
                                    </div>

                                </div>
                            </div>
                            {{-- @dd($transaction); --}}
                            <h5 class="my-3"> Transaction
                                Name -{{ $transaction->transaction_name }}</h5>
                            @php
                                $groupedPayments = $payment->groupBy('transaction_id');
                                $sumInAmount = 0;
                                $sumOutAmount = 0;
                            @endphp

                            @foreach ($groupedPayments as $transactionId => $payments)
                                @php

                                    $sumInAmount = $payments->where('payment_status', 'IN')->sum('amount');
                                    $sumOutAmount = $payments->where('payment_status', 'OUT')->sum('amount');
                                @endphp
                            @endforeach

                            <h5 class="mb-4">Amount:
                                {{ number_format($total_invoice + $sumInAmount - $sumOutAmount + $total_po + $total_expense + $total_return) }}
                            </h5>


                            <div class="modal fade" id="modal-opening">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Opening Balance Register</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('/transaction_payment_register', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="text" value="Opening Balance " name="opening_balance"
                                                    id="opening_balance" class="form-control" hidden>
                                                <input type="hidden" value="{{ $transaction->id }}"
                                                    name="transaction_id">
                                                <input type="hidden" value="{{ $transaction->location }}"
                                                    id="location">
                                                <input type="hidden" value="{{ $transaction->account->id }}"
                                                    name="account_id">
                                                <div class="form-group">
                                                    <label for="voucher_no">Voucher No. <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" name="voucher_no"
                                                        placeholder="Enter Voucher No" required>
                                                    @error('voucher_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="invoice_no">Invoice No.</label>
                                                    <input type="text" class="form-control" id="invoice_no"
                                                        name="invoice_no" placeholder="Enter Invoice No">
                                                    @error('invoice_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status <span
                                                            style="color: red;">*</span></label>
                                                    <select name="payment_status" class="form-control" id="status"
                                                        required>
                                                        <option value="">Choose One</option>
                                                        <option value="IN">IN</option>
                                                        <option value="OUT">OUT</option>
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>


                                                <div class="form-group">
                                                    <label for="receiver_name">Receiver Name <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="receiver_name"
                                                        name="receiver_name" placeholder="Enter Receiver Name"
                                                        required>
                                                    @error('receiver_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="reference_no">Reference No. <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="reference_no"
                                                        name="reference_no" placeholder="Enter reference no" required>
                                                    @error('reference_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount">Amount <span
                                                            style="color: red;">*</span></label>
                                                    <input type="number" class="form-control" id="amount"
                                                        name="amount" placeholder="Enter Amount" required>
                                                    @error('transaction_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date <span
                                                            style="color: red;">*</span></label>
                                                    <input type="date" class="form-control" id="payment_date"
                                                        name="payment_date" required>
                                                    @error('payment_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="note">Description</label>
                                                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Enter ..."></textarea>
                                                    @error('note')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        style="background-color: #007BFF">Register</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>


                            <div class="modal fade" id="modal-lg">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Payment Register</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form
                                                action="{{ url('/transaction_payment_register', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $transaction->id }}"
                                                    name="transaction_id">
                                                <input type="hidden" value="{{ $transaction->location }}"
                                                    id="location">
                                                <input type="hidden" value="{{ $transaction->account->id }}"
                                                    name="account_id">

                                                <div class="form-group">
                                                    <label for="voucher_no">Voucher No. <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="voucher_no"
                                                        name="voucher_no" placeholder="Enter Voucher No" required
                                                        readonly>
                                                    @error('voucher_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="invoice_no">Invoice No.</label>
                                                    <input type="text" class="form-control" id="invoice_no"
                                                        name="invoice_no" placeholder="Enter Invoice No">
                                                    @error('invoice_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status <span
                                                            style="color: red;">*</span></label>
                                                    <select name="payment_status" class="form-control" id="status"
                                                        required>
                                                        <option value="">Choose One</option>
                                                        <option value="IN">IN</option>
                                                        <option value="OUT">OUT</option>
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>



                                                @if (auth()->user()->is_admin == '1')
                                                    <!-- Internal Transfer Accounts -->
                                                    <div class="form-group" id="internal_transfer_group">
                                                        <label for="internal_transfer_account">Account</label>
                                                        <select name="transfer_account_id" class="form-control"
                                                            id="internal_transfer_account" required>
                                                            <option value="">Choose Select Account</option>
                                                            @foreach ($accounts as $account)
                                                                <option value="{{ $account->id }}">
                                                                    {{ $account->account_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- HO Transfer Accounts -->
                                                @else
                                                    <div class="form-group" id="internal_transfer_group">
                                                        <label for="status">Account</label>
                                                        <select name="transfer_account_id" class="form-control"
                                                            id="transfer_account_id" required>
                                                            <option value="">Choose Select Account</option>
                                                            @foreach ($accounts as $account)
                                                                <option value="{{ $account->id }}">
                                                                    {{ $account->account_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>



                                                @endif





                                                <div class="form-group">
                                                    <label for="receiver_name">Receiver Name <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="receiver_name"
                                                        name="receiver_name" placeholder="Enter Receiver Name"
                                                        required>
                                                    @error('receiver_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="reference_no">Reference No. <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="reference_no"
                                                        name="reference_no" placeholder="Enter reference no" required>
                                                    @error('reference_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount">Amount <span
                                                            style="color: red;">*</span></label>
                                                    <input type="number" class="form-control" id="amount"
                                                        name="amount" placeholder="Enter Amount" required>
                                                    @error('transaction_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date <span
                                                            style="color: red;">*</span></label>
                                                    <input type="date" class="form-control" id="payment_date"
                                                        name="payment_date" required>
                                                    @error('payment_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="note">Description</label>
                                                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Enter ..."></textarea>
                                                    @error('note')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        style="background-color: #007BFF">Register</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>


                            <!-- Nav tabs -->

                            <div class="col-md-3 col-6 col-lg-2 mb-5">
                                <select
                                    class="form-control shadow-sm p-2 bg-secondary text-white border-0 rounded-pill"
                                    id="tableSelect">
                                    <option value="payment" selected>Payment Table</option>
                                    <option value="invoice">Invoice Table</option>
                                    {{-- <option value="pos">POS Table</option> --}}
                                    <option value="po">PO Table</option>
                                    {{-- <option value="pr">PO Return Table</option> --}}
                                    <option value="sr-inv">Sale Return (Invoice) Table</option>
                                    {{-- <option value="sr-pos">Sale Return (POS) Table</option> --}}
                                    <option value="expense">Expense Table</option>

                                </select>
                            </div>



                            <!-- Tab content -->
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="payment" role="tabpanel"
                                    aria-labelledby="payment-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>INV/PO Number</th>
                                                            <th>Account Name</th>
                                                            <th>Customer/Supplier Name</th>
                                                            <th>Description</th>
                                                            <th>Date</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($invoice_make_payments as $index => $invoice_make_payment)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $invoice_make_payment->invoice ? $invoice_make_payment->invoice->invoice_no : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $invoice_make_payment->transaction ? ($invoice_make_payment->transaction->account ? $invoice_make_payment->transaction->account->account_name : 'N/A') : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $invoice_make_payment->invoice ? $invoice_make_payment->invoice->customer_name : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $invoice_make_payment->note }}
                                                                </td>
                                                                <td>{{ $invoice_make_payment->payment_date ?? \Carbon\Carbon::parse($invoice_make_payment->created_at)->format('Y-m-d') }}
                                                                </td>
                                                                <td>
                                                                    {{ number_format($invoice_make_payment->payment_amount) }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($return_make_payments as $index => $return_make_payment)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $return_make_payment->invoice_return ? $return_make_payment->invoice_return->invoice_no : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $return_make_payment->transaction ? ($return_make_payment->transaction->account ? $return_make_payment->transaction->account->account_name : 'N/A') : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $return_make_payment->invoice_return ? $return_make_payment->invoice_return->customer_name : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $return_make_payment->note }}
                                                                </td>
                                                                <td>{{ $return_make_payment->payment_date ?? \Carbon\Carbon::parse($return_make_payment->created_at)->format('Y-m-d') }}
                                                                </td>
                                                                <td>
                                                                    {{ number_format($return_make_payment->payment_amount) }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($purchase_order_payment_method as $purchase_order_payment)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $purchase_order_payment->purchase_order ? $purchase_order_payment->purchase_order->quote_no : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $purchase_order_payment->transaction ? ($purchase_order_payment->transaction->account ? $purchase_order_payment->transaction->account->account_name : 'N/A') : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $purchase_order_payment->purchase_order ? ($purchase_order_payment->purchase_order->supplier ? $purchase_order_payment->purchase_order->supplier->name : 'N/A') : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    N/A
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($purchase_order_payment->created_at)->format('Y-m-d') }}
                                                                </td>
                                                                <td>
                                                                    {{ number_format($purchase_order_payment->payment_amount) }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach

                                                        @foreach ($expenses as $expense)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td></td>
                                                                <td>
                                                                    {{ $expense->transaction ? $expense->transaction->transaction_name : '' }}
                                                                </td>
                                                                <td>
                                                                    N/A
                                                                </td>
                                                                <td>
                                                                    {{ $expense->description }}
                                                                </td>
                                                                <td>
                                                                    {{ $expense->date }}
                                                                </td>
                                                                <td>
                                                                    {{ number_format($expense->amount) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Payment</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example9" class="table table-bordered table-striped mt-4">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Transfer Account</th>
                                                            <th>Account Name</th>
                                                            <th>Status</th>
                                                            <th>Opening Balance</th>
                                                            <th>Voucher No.</th>
                                                            <th>Invoice No.</th>
                                                            <th>Receiver Name</th>
                                                            <th>Reference No</th>
                                                            <th>Payment Date</th>
                                                            <th>Description</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $total_amt = 0; @endphp
                                                        @foreach ($payment as $index => $pay)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td> {{ $pay->transferAccount ? $pay->transferAccount->account_name : 'N/A' }}

                                                                </td>
                                                                <td>
                                                                    {{ $pay->account->account_name }}
                                                                </td>

                                                                <td>
                                                                    {{ $pay->payment_status }}
                                                                </td>
                                                                <td>
                                                                    {{ $pay->opening_balance ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $pay->voucher_no }}</td>
                                                                <td>{{ $pay->invoice_number }}</td>
                                                                <td>{{ $pay->receiver_name ?? 'N/A' }}</td>
                                                                <td>{{ $pay->reference_no ?? 'N/A' }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d-m-Y') ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $pay->note ?? 'N/A' }}</td>
                                                                <td>{{ $pay->amount }}</td>
                                                                <td>
                                                                    <a href="{{ url('transaction_payment_edit', $pay->id) }}"
                                                                        class="btn btn-success"><i
                                                                            class="fa-solid fa-pen-to-square"></i></a>

                                                                    @if ($pay->opening_balance == null)
                                                                        <a href="{{ url('transaction_delete_payment', $pay->voucher_no) }}"
                                                                            class="btn btn-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this payment ?')"><i
                                                                                class="fa-solid fa-trash"></i></a>
                                                                    @else
                                                                        <a href="{{ url('transaction_delete_payment_id', $pay->id) }}"
                                                                            class="btn btn-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this payment ?')"><i
                                                                                class="fa-solid fa-trash"></i></a>
                                                                    @endif


                                                                </td>
                                                            </tr>
                                                            @php $total_amt += (float) $pay->amount; @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="11" class="text-right">Total</td>
                                                            <td>{{ $total_amt }}</td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="invoice" role="tabpanel"
                                    aria-labelledby="invoice-tab">

                                    {{-- <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="invoiceSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-6 col-md-4 col-lg-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date" id="start_date"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-6 col-md-4 col-lg-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="end_date"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-4 col-md-4 col-lg-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="button" id="invoicesearchButton"
                                                                class="btn btn-primary form-control" value="Search"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example2" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Invoice No.</th>
                                                            <th>Location</th>
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>Payment Status</th>
                                                            <th>Invoice Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp

                                                        @php
                                                            $uniqueInvoices = $make_payments
                                                                ->pluck('invoice')
                                                                ->unique('id'); // Get unique invoices

                                                        @endphp

                                                        @foreach ($uniqueInvoices as $invoice)
                                                            @if ($invoice && $invoice->balance_due == 'Invoice')
                                                                <tr>
                                                                    <td>{{ $no }}</td>
                                                                    <td>
                                                                        <a
                                                                            href="{{ url('invoice_detail', $invoice->id) }}">{{ $invoice->invoice_no ?? 'N/A' }}</a>
                                                                    </td>
                                                                    <td>
                                                                        {{ $invoice->warehouse->name ?? 'N/A' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($invoice->deposit ?? 0) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($invoice->remain_balance ?? 0) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($invoice->total ?? 0) }}
                                                                    </td>
                                                                    @if ($invoice->total == ($invoice->deposit ?? 0))
                                                                        <td>Paid</td>
                                                                    @elseif (($invoice->deposit ?? 0) > 0)
                                                                        <td>Partial Paid</td>
                                                                    @else
                                                                        <td>Unpaid</td>
                                                                    @endif
                                                                    <td>
                                                                        {{ $invoice->invoice_date ?? '' }}
                                                                    </td>
                                                                </tr>
                                                                @php $no++; @endphp
                                                            @endif
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="po" role="tabpanel" aria-labelledby="po-tab">

                                    {{-- <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="poSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                class="form-control" id="po_start_date" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="po_end_date"
                                                                class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="poSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example4" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>PO No.</th>
                                                            <th>Location</th>
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>PO Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @php
                                                            $uniquePurchaseOrders = $purchase_order_payment_method->unique(
                                                                'po_id',
                                                            );
                                                            $no = 1;
                                                        @endphp

                                                        @foreach ($uniquePurchaseOrders as $po)
                                                            @if ($po->purchase_order ? $po->purchase_order->balance_due : '' == 'PO')
                                                                <tr>
                                                                    <td>{{ $no }}</td>
                                                                    <td>
                                                                        <a
                                                                            href="{{ url('purchase_order_details', $po->purchase_order->id ?? '') }}">
                                                                            {{ $po->purchase_order->quote_no ?? '' }}
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        {{ $po->purchase_order->warehouse->name ?? '' }}
                                                                    </td>
                                                                    <td>{{ number_format($po->purchase_order->remain_balance ?? 0) }}
                                                                    </td>
                                                                    <td>{{ number_format($po->purchase_order->deposit ?? 0) }}
                                                                    </td>
                                                                    <td>{{ number_format($po->purchase_order->total ?? 0) }}
                                                                    </td>
                                                                    <td>{{ $po->purchase_order->po_date ?? '' }}</td>
                                                                </tr>
                                                            @endif
                                                            @php $no++; @endphp
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pr" role="tabpanel" aria-labelledby="pr-tab">

                                    {{-- <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="prSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                class="form-control" id="pr_start_date" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="pr_end_date"
                                                                class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="prSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example5" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Invoice No.</th>
                                                            <th>Location</th>
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>Payment Status</th>
                                                            <th>Invoice Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @php
                                                            $uniqueInvoices = $make_payments
                                                                ->pluck('invoice')
                                                                ->unique('id'); // Get unique invoices

                                                        @endphp

                                                        @foreach ($uniqueInvoices as $invoice)
                                                            @if ($invoice && $invoice->balance_due == 'Po Return')
                                                                <tr>
                                                                    <td>{{ $no }}</td>
                                                                    <td>
                                                                        <a
                                                                            href="{{ url('invoice_detail', $invoice->id) }}">{{ $invoice->invoice_no ?? 'N/A' }}</a>
                                                                    </td>
                                                                    <td>
                                                                        {{ $invoice->warehouse->name ?? 'N/A' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($invoice->deposit ?? 0) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($invoice->remain_balance ?? 0) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($invoice->total ?? 0) }}
                                                                    </td>
                                                                    @if ($invoice->total == ($invoice->deposit ?? 0))
                                                                        <td>Paid</td>
                                                                    @elseif (($invoice->deposit ?? 0) > 0)
                                                                        <td>Partial Paid</td>
                                                                    @else
                                                                        <td>Unpaid</td>
                                                                    @endif
                                                                    <td>
                                                                        {{ $invoice->invoice_date ?? '' }}
                                                                    </td>
                                                                </tr>
                                                                @php $no++; @endphp
                                                            @endif
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="sr-inv" role="tabpanel"
                                    aria-labelledby="sr-inv-tab">

                                    {{-- <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="saleReturnInvoiceForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                id="sr_inv_start_date" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date"
                                                                id="sr_inv_end_date" class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="saleReturnInvoiceSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example6" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Sale Return No.</th>
                                                            <th>Location</th>
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp

                                                        @foreach ($return_make_payments as $key => $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>

                                                                    {{ $po->invoice_return->invoice_no ?? '' }}

                                                                </td>
                                                                <td>
                                                                    {{ $po->invoice_return->warehouse->name ?? '' }}
                                                                </td>
                                                                <td>{{ number_format($po->purchase_order->remain_balance ?? 0) }}
                                                                </td>
                                                                <td>{{ number_format($po->invoice_return->deposit ?? 0) }}
                                                                </td>
                                                                <td>{{ number_format($po->invoice_return->total ?? 0) }}
                                                                </td>
                                                                <td>{{ $po->invoice_return->invoice_date ?? '' }}</td>
                                                            </tr>


                                                            @php $no++; @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="expense" role="tabpanel" aria-labelledby="expense">

                                    {{-- <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="saleReturnPosSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                id="sr_pos_start_date" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date"
                                                                id="sr_pos_end_date" class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="saleReturnPosSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example8" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Expense.</th>
                                                            <th>Expense Category</th>
                                                            <th>Location</th>
                                                            <th>Amount</th>
                                                            <th>Description</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($expenses as $expense)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $expense->name }}</td>
                                                                <td>

                                                                    {{ $expense->expense_category ? $expense->expense_category->name : '' }}

                                                                </td>

                                                                <td>{{ $expense->warehouse ? $expense->warehouse->name : '' }}
                                                                </td>
                                                                <td>{{ $expense->amount }}</td>
                                                                <td>{{ $expense->description }}</td>

                                                                <td>
                                                                    {{ $expense->date }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="other-income" role="tabpanel"
                                    aria-labelledby="invoice-tab">

                                    {{-- <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="invoiceSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-6 col-md-4 col-lg-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date" id="start_date"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-6 col-md-4 col-lg-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="end_date"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-4 col-md-4 col-lg-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="button" id="invoicesearchButton"
                                                                class="btn btn-primary form-control" value="Search"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div> --}}


                                </div>
                            </div>

                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>

            </section>



        </div>
    </div>

    </section>

    </div>



    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js ') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example2").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example3").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example4").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example5").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example6").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example7").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });
        $(function() {
            $("#example8").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        })
        $(function() {
            $("#example9").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        })
        $(function() {
            $("#example10").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        })
    </script>


    <script>
        // JavaScript
        function updateModels() {
            var selectedAccountId = document.getElementById('account_id').value;
            var transactionDropdown = document.getElementById('transaction_id');
            var transactionOptions = transactionDropdown.getElementsByTagName('option');

            // Reset the dropdown
            transactionDropdown.selectedIndex = 0;

            // Hide all options initially
            for (var i = 0; i < transactionOptions.length; i++) {
                transactionOptions[i].hidden = true;
            }

            // Show options that belong to the selected account
            for (var i = 0; i < transactionOptions.length; i++) {
                if (selectedAccountId === "" || transactionOptions[i].getAttribute('data-account') === selectedAccountId) {
                    transactionOptions[i].hidden = false;
                }
            }
        }

        // Call updateModels() when the page is loaded
        window.addEventListener('load', updateModels);
    </script>

    <script>
        $(document).ready(function() {
            $('#tableSelect').on('change', function() {
                const selectedValue = $(this).val();

                $('.tab-pane').removeClass('show active');

                $('#' + selectedValue).addClass('show active');
            });

            $('#tableSelect').trigger('change');
        });

        $(document).ready(function() {
            function fetchVoucherNo() {
                $.ajax({
                    url: "{{ route('get-voucher-no') }}", // Adjust the route to match your Laravel setup
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        $("#voucher_no").val(response.voucher_no); // Update the element
                    },
                    error: function() {
                        console.error("Error fetching voucher number.");
                    }
                });
            }

            // Poll every 5 seconds
            setInterval(fetchVoucherNo, 5000);

            // Fetch immediately on page load
            fetchVoucherNo();
        });
    </script>
</body>

</html>
