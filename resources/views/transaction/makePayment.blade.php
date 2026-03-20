@include('layouts.header')


<style>
    /* Ensure the DataTable and its container have the same width */
    .dataTables_scroll {
        overflow: auto;
        width: 100%;
    }

    /* Adjust the header and body alignment */
    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
        text-align: center;
    }

    .dataTables_scrollHeadInner,
    .dataTables_scrollBody table {
        width: 100% !important;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle text-white" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu ">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-1 btn changelogout " style="width: 157px">
                                <i class="fa-solid fa-right-from-bracket "></i> Logout</button>

                        </form>


                    </div>
                </div>



            </ul>
        </nav>
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
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-default text-white" data-toggle="modal"
                                            data-target="#modal-lg" style="background-color: #007BFF">
                                            Payment Register
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <h5 class="my-5"> Transaction
                                Name -{{ $transaction->transaction_name }}</h5>
                            {{-- Modal Content --}}
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
                                            <form action="{{ url('/transaction_payment_register', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $transaction->id }}"
                                                    name="transaction_id">
                                                <input type="hidden" value="{{ $transaction->account->account_name }}"
                                                    name="account_id">

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
                                                    <label for="transaction_code">Amount <span
                                                            style="color: red;">*</span></label>
                                                    <input type="number" class="form-control" id="transaction_code"
                                                        name="amount" placeholder="Enter Amount" required>
                                                    @error('transaction_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction_code">Voucher No. <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="voucher_no"
                                                        name="voucher_no" placeholder="Enter Voucher Numer" required>
                                                    @error('voucher_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction_code">Receiver Name <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="receiver_name"
                                                        name="receiver_name" placeholder="Enter Receiver Name"
                                                        required>
                                                    @error('receiver_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction_code">Reference No. <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" class="form-control" id="reference_no"
                                                        name="reference_no" placeholder="Enter Reference Number"
                                                        required>
                                                    @error('receiver_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction_code">Date <span
                                                            style="color: red;">*</span></label>
                                                    <input type="date" class="form-control" id="date"
                                                        name="date" required value="{{ date('Y-m-d') }}">
                                                    @error('date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" id="description" name="note" rows="3" placeholder="Enter ..."></textarea>
                                                    @error('description')
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



                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Payment Table</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Name</th>


                                                <th>Status</th>



                                                <th>Amount</th>
                                                <th>Voucher No.</th>
                                                <th>Receiver Name</th>
                                                <th>Reference No.</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                                use Carbon\Carbon;

                                            @endphp
                                            @foreach ($payment as $payments)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $payments->account_id }}</td>
                                                    <td>{{ $payments->payment_status }}</td>
                                                    <td>{{ $payments->amount }}</td>
                                                    <td>{{ $payments->voucher_no }}</td>
                                                    <td>{{ $payments->receiver_name }}</td>
                                                    <td>{{ $payments->reference_no }}</td>
                                                    <td>{{ Carbon::parse($payments->date)->format('d-M-Y') }} </td>
                                                    <td>{{ $payments->note }}</td>
                                                    <td>
                                                        <a href="{{ url('transaction_payment_edit', $payments->id) }}"
                                                            class="btn btn-success"><i
                                                                class="fa-solid fa-pen-to-square"></i></a>
                                                        <a href="{{ url('transaction_delete_payment', $payments->id) }}"
                                                            class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this payment ?')"><i
                                                                class="fa-solid fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                        </tbody>




                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="col-md-4 my-2">
                                <label for="invoice_dropdown">Select One</label>
                                <select name="" id="invoice_dropdown" class="form-control">
                                    <option value="Invoice">Invoice</option>
                                    <option value="Invoice Refund"> Invoice Refund</option>
                                    <option value="Exchange Order"> Exchange Order</option>
                                    <option value="Exchange Order Return"> Exchange Order Return</option>
                                </select>
                            </div>
                            <div class="card" id="invoice">
                                <div class="card-header">
                                    <h3 class="card-title">InvoicePayment Table</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Name</th>

                                                <th>Invoice No.</th>
                                                <th>Status</th>






                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Amount(MMK)</th>
                                                <th>Amount(USD)</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                                $invoice_totalmmk = 0;
                                                $invoice_totalusd = 0;

                                            @endphp
                                            @foreach ($invoices as $invoices)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $invoices->transaction_name->account->account_name }}</td>
                                                    <td>{{ $invoices->invoice_number }}</td>
                                                    <td>IN</td>



                                                    <td>{{ Carbon::parse($invoices->invoice_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ $invoices->note }}</td>
                                                    <td>

                                                        @if ($invoices->payment_method == 'MMK')
                                                            {{ $invoices->net_total }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 MMK
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($invoices->payment_method == 'USD')
                                                            {{ number_format($invoices->net_total) }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 USD
                                                        @endif
                                                    </td>

                                                </tr>
                                                @php
                                                    if ($invoices->payment_method == 'MMK') {
                                                        $invoice_totalmmk += $invoices->net_total;
                                                    } else {
                                                        $invoice_totalusd += $invoices->net_total;
                                                    }
                                                    $no++;
                                                @endphp
                                            @endforeach
                                            @foreach ($invoices_deposit as $invoices)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $invoices->transaction_name->account->account_name }}</td>
                                                    <td>{{ $invoices->invoice_number }}</td>
                                                    <td>IN</td>



                                                    <td>{{ Carbon::parse($invoices->invoice_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ $invoices->note }}</td>
                                                    <td>
                                                        @if ($invoices->payment_method == 'MMK')
                                                            {{ $invoices->deposit }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 MMK
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($invoices->payment_method == 'USD')
                                                            {{ number_format($invoices->deposit) }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 USD
                                                        @endif
                                                    </td>

                                                </tr>
                                                @php
                                                    $no++;
                                                    if ($invoices->payment_method == 'MMK') {
                                                        $invoice_totalmmk += $invoices->deposit;
                                                    } else {
                                                        $invoice_totalusd += $invoices->deposit;
                                                    }
                                                @endphp
                                            @endforeach
                                        </tbody>


                                        <tfoot>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td>Total Amount</td>
                                                <td>{{ number_format($invoice_totalmmk) }} MMK</td>
                                                <td>{{ number_format($invoice_totalusd) }} USD</td>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card" id="invoice_refund" style="display: none">
                                <div class="card-header">
                                    <h3 class="card-title">Invoice Refund Payment Table</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Name</th>
                                                <th>Refund No.</th>


                                                <th>Status</th>






                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Amount(MMK)</th>
                                                <th>Amount(USD)</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                                $invoice_refund_totalmmk = 0;
                                                $invoice_refund_totalusd = 0;
                                            @endphp
                                            @foreach ($invoices_refund as $invoices)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $invoices->transaction_name->account->account_name }}</td>
                                                    <td>{{ $invoices->refund_number }}</td>
                                                    <td>OUT</td>



                                                    <td>{{ Carbon::parse($invoices->refund_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ $invoices->note }}</td>
                                                    <td>
                                                        @if ($invoices->payment_method == 'MMK')
                                                            {{ number_format($invoices->net_total) }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 MMK
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($invoices->payment_method == 'USD')
                                                            {{ number_format($invoices->net_total) }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 USD
                                                        @endif
                                                    </td>

                                                </tr>
                                                @php
                                                    $no++;
                                                    if ($invoices->payment_method == 'MMK') {
                                                        $invoice_refund_totalmmk += $invoices->net_total;
                                                    } else {
                                                        $invoice_refund_totalusd += $invoices->net_total;
                                                    }
                                                @endphp
                                            @endforeach
                                            @foreach ($invoices_refund_deposit as $invoices)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $invoices->transaction_name->account->account_name }}</td>
                                                    <td>{{ $invoices->refund_number }}</td>
                                                    <td>IN</td>



                                                    <td>{{ Carbon::parse($invoices->refund_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ $invoices->note }}</td>
                                                    <td>
                                                        @if ($invoices->payment_method == 'MMK')
                                                            {{ number_format($invoices->deposit) }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 MMK
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($invoices->payment_method == 'USD')
                                                            {{ number_format($invoices->deposit) }}{{ ' ' }}{{ $invoices->payment_method }}
                                                        @else
                                                            0 USD
                                                        @endif
                                                    </td>

                                                </tr>
                                                @php
                                                    $no++;
                                                    if ($invoices->payment_method == 'MMK') {
                                                        $invoice_refund_totalmmk += $invoices->deposit;
                                                    } else {
                                                        $invoice_refund_totalusd += $invoices->deposit;
                                                    }
                                                @endphp
                                            @endforeach
                                        </tbody>


                                        <tfoot>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td>Total Amount</td>
                                                <td>{{ number_format($invoice_refund_totalmmk) }} MMK</td>
                                                <td>{{ number_format($invoice_refund_totalusd) }} USD</td>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card" id="exchange_order" style="display: none">
                                <div class="card-header">
                                    <h3 class="card-title">EO Payment Table</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Name</th>

                                                <th>EO No.</th>
                                                <th>Status</th>






                                                <th>Date</th>
                                                <th>Amount(MMK)</th>
                                                <th>Amount(USD)</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                                $exchange_totalmmk = 0;
                                                $exchange_totalusd = 0;
                                            @endphp
                                            @foreach ($exchange_orders as $exchange_order)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $exchange_order->transaction_name->account->account_name }}
                                                    </td>
                                                    <td>{{ $exchange_order->eo_number }}</td>
                                                    <td>OUT</td>



                                                    <td>{{ Carbon::parse($exchange_order->eo_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($exchange_order->payment_method == 'MMK')
                                                            {{ number_format($exchange_order->net_total) }}{{ ' ' }}{{ $exchange_order->payment_method }}
                                                        @else
                                                            0 MMK
                                                        @endif


                                                    </td>
                                                    <td>
                                                        @if ($exchange_order->payment_method == 'USD')
                                                            {{ number_format($exchange_order->net_total) }}{{ ' ' }}{{ $exchange_order->payment_method }}
                                                        @else
                                                            0 USD
                                                        @endif
                                                    </td>


                                                </tr>
                                                @php
                                                    $no++;
                                                    if ($exchange_order->payment_method == 'MMK') {
                                                        $exchange_totalmmk += $exchange_order->net_total;
                                                    } else {
                                                        $exchange_totalusd += $exchange_order->net_total;
                                                    }
                                                @endphp
                                            @endforeach
                                            @foreach ($exchange_orders_deposit as $exchange_order)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $exchange_order->transaction_name->account->account_name }}
                                                    </td>
                                                    <td>{{ $exchange_order->e_number }}</td>

                                                    <td>OUT</td>



                                                    <td>{{ Carbon::parse($exchange_order->eo_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($exchange_order->payment_method == 'MMK')
                                                            {{ number_format($exchange_order->deposit) }}{{ ' ' }}{{ $exchange_order->payment_method }}
                                                        @else
                                                            0 MMK
                                                        @endif


                                                    </td>
                                                    <td>
                                                        @if ($exchange_order->payment_method == 'USD')
                                                            {{ number_format($exchange_order->deposit) }}{{ ' ' }}{{ $exchange_order->payment_method }}
                                                        @else
                                                            0 USD
                                                        @endif
                                                    </td>

                                                </tr>
                                                @php
                                                    $no++;
                                                    if ($exchange_order->payment_method == 'MMK') {
                                                        $exchange_totalmmk += $exchange_order->deposit;
                                                    } else {
                                                        $exchange_totalusd += $exchange_order->deposit;
                                                    }
                                                @endphp
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td>Total Amount</td>
                                                <td>{{ number_format($exchange_totalmmk) }} MMK</td>
                                                <td>{{ number_format($exchange_totalusd) }} USD</td>
                                            </tr>
                                        </tfoot>


                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card" id="exchange_order_return" style="display: none">
                                <div class="card-header">
                                    <h3 class="card-title">EO Return Payment Table</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Name</th>

                                                <th>EO Return No.</th>
                                                <th>Status</th>






                                                <th>Date</th>
                                                <th>Amount(MMk)</th>
                                                <th>Amount(USD)</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                                $eo_return_totalmmk = 0;
                                                $eo_return_totalusd = 0;

                                            @endphp
                                            @foreach ($exchange_order_return as $exchange_order)
                                                <tr>
                                                    <td>{{ $no }}</td>

                                                    <td>{{ $exchange_order->transaction_name->account->account_name }}
                                                    </td>
                                                    <td>{{ $exchange_order->eo_number }}</td>
                                                    <td>IN</td>



                                                    <td>{{ Carbon::parse($exchange_order->eo_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($exchange_order->payment_method == 'MMK')
                                                            {{ number_format($exchange_order->net_total) }}{{ ' ' }}{{ $exchange_order->payment_method }}
                                                        @else
                                                            0 MMK
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($exchange_order->payment_method == 'USD')
                                                            {{ number_format($exchange_order->net_total) }}{{ ' ' }}{{ $exchange_order->payment_method }}
                                                        @else
                                                            0 USD
                                                        @endif
                                                    </td>


                                                </tr>
                                                @php
                                                    $no++;
                                                    if ($exchange_order->payment_method == 'MMK') {
                                                        $eo_return_totalmmk += $exchange_order->net_total;
                                                    } else {
                                                        $eo_return_totalusd += $exchange_order->net_total;
                                                    }
                                                @endphp
                                            @endforeach
                                            @foreach ($exchange_orders_deposit as $exchange_order)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $exchange_order->transaction_name->account->account_name }}
                                                    </td>
                                                    <td>{{ $exchange_order->e_number }}</td>
                                                    <td>IN</td>



                                                    <td>{{ Carbon::parse($exchange_order->eo_date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ number_format($exchange_order->deposit) }}{{ ' ' }}{{ $exchange_order->payment_method }}
                                                    </td>

                                                </tr>
                                                @php
                                                    $no++;
                                                    if ($exchange_order->payment_method == 'MMK') {
                                                        $eo_return_totalmmk += $exchange_order->deposit;
                                                    } else {
                                                        $eo_return_totalusd += $exchange_order->deposit;
                                                    }
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="1" class="text-end">Total Amount</td>
                                                <td sclass="text-center">{{ number_format($eo_return_totalmmk) }} MMK
                                                </td>
                                                <td sclass="text-center">{{ number_format($eo_return_totalusd) }} USD
                                                </td>
                                            </tr>
                                        </tfoot>




                                    </table>
                                </div>
                                <!-- /.card-body -->
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
        $(document).ready(function() {
            // Call changeInvoice function when the dropdown value changes
            $('#invoice_dropdown').change(function() {
                changeInvoice();
            });
        });
    </script>

    <script>
        function changeInvoice() {
            var selectedValue = document.getElementById("invoice_dropdown").value;

            if (selectedValue === "Invoice") {
                $('#invoice').show();
                $('#invoice_refund, #exchange_order, #exchange_order_return').hide(); // Corrected syntax
            } else if (selectedValue === "Invoice Refund") {
                $('#invoice_refund').show();
                $('#invoice, #exchange_order, #exchange_order_return').hide(); // Corrected syntax
            } else if (selectedValue === "Exchange Order") {
                $('#exchange_order').show();
                $('#invoice, #invoice_refund, #exchange_order_return').hide(); // Corrected syntax
            } else if (selectedValue === "Exchange Order Return") {
                $('#exchange_order_return').show();
                $('#invoice, #invoice_refund, #exchange_order').hide(); // Corrected syntax
            } else {
                $('#invoice, #invoice_refund, #exchange_order, #exchange_order_return').hide(); // Corrected syntax
            }
        }

        // Call changeInvoice when the page loads and when dropdown value changes
        $('#invoice_dropdown').change(changeInvoice);
    </script>

    <script>
        $("input[type=date]").on("change", function() {
            if (this.value && moment(this.value, "YYYY-MM-DD").isValid()) {
                this.setAttribute(
                    "data-date",
                    moment(this.value, "YYYY-MM-DD").format("D-MMM-YYYY").replace(/^\w/, (c) => c.toUpperCase())
                )
            } else {
                this.setAttribute("data-date", "dd-mm-yyyy");
            }
        }).trigger("change");
    </script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "scrollX": true,
                "scrollCollapse": true,
                "paging": true,
                "buttons": ["excel", "pdf", "print"],
                "columnDefs": [{
                        "width": "100px",
                        "targets": 0
                    } // Adjust the width of columns as needed
                ],
                "fixedHeader": true, // Ensure header is fixed
                "initComplete": function() {
                    // Adjust the width of the DataTable columns after initialization
                    this.api().columns.adjust();
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
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
</body>

</html>
