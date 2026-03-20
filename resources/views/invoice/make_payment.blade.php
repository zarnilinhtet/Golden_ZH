@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link  text-gray" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  text-gray" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


                {{-- <li class="nav-item ml-auto">
                    <a class="nav-link text-white" href="#">
                       Goldenzh

                    </a>
                </li> --}}

            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle text-gray" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu">
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

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Payment List</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Invoice</li>
                                    <li class="breadcrumb-item active">Payment List</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="container-fluid">
                    <div class="row  justify-content-center d-flex">


                    </div>



                    <!-- /.modal -->
                    <div class="col-md-12 mt-3">
                           <div class="row mt-5">


                    <div class="col-lg-12">
                        @if (session('success'))
                            <div class="alert text-white bg-success" role="alert">
                                <div class="iq-alert-text"><i
                                        class="fa-solid fa-check mx-2"></i>{{ session('success') }}
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert text-white bg-warning" role="alert">
                                <div class="iq-alert-text"><i class="fa-solid fa-check mx-2"></i>{{ session('error') }}
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        @endif
                        <div class="table-responsive rounded mb-3">
                            <div class="row justify-content-center d-flex">
                                <div class="card col-8">

                                    <form action="{{ url('make_payment_store', $make_payments->id) }}" method="POST"
                                        class="p-5">
                                        @csrf
                                        <div class="row mb-4">

                                            <div class="col-md-4 form-group">
                                                <button type="button" class="btn btn-primary" style="width: 100%">Total
                                                    -
                                                    {{ number_format($make_payments->total) }}
                                                </button>

                                            </div>


                                            <div class="col-md-4 form-group">
                                                <button type="button" class="btn btn-primary"
                                                    style="width: 100%">Deposit
                                                    -
                                                    {{ number_format($make_payments->deposit) }}
                                                </button>

                                            </div>

                                            <div class="col-md-4 form-group">
                                                <input type="hidden" name="remain_balance"
                                                    value="{{ $make_payments->remain_balance }}">
                                                <button type="button" class="btn btn-primary" style="width: 100%">
                                                    Balance -
                                                    {{ number_format($make_payments->remain_balance) }}
                                                </button>
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label for="">Invoice No</label>
                                                <input type="text" class="form-control" id=""
                                                    value="{{ $make_payments->invoice_no }}" name="" readonly>
                                                {{-- <input type="text" class="form-control" id="payment_id"
                                            value="{{ $make_payments->branch }}" name="" hidden> --}}
                                                <input type="hidden" class="form-control" id="location" name="branch"
                                                    value="{{ $make_payments->location }}">
                                            </div>

                                            <div class="form-group col-md-4" hidden>
                                                <label for="invoice_no">Cash Voucher No<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="invoice_no"
                                                    id="invoice_no" value="" readonly>

                                            </div>


                                            <div class="form-group col-md-6">
                                                <label for="payment_date">Payment Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="payment_date"
                                                    placeholder="Enter payment date" name="payment_date" required
                                                    value="{{ date('Y-m-d') }}">

                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="payment_method">Payment Method <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="payment_method" id="payment_method-0"
                                                    required>
                                                    <option disabled>Select payment method</option>
                                                    @foreach ($transactions as $transaction)
                                                        <option value="{{ $transaction->id }}">
                                                            {{ $transaction->transaction_name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            <div class="form-group col-md-6" id="total_amount">
                                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="amount"
                                                    name="amount" placeholder="Enter amount" required>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="note">Note</label>
                                                <textarea rows="3" class="form-control" id="note" name="note" placeholder="Enter note"></textarea>
                                            </div>


                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary px-2"
                                                    onclick="return confirm('Are you sure payment?');">
                                                    Make Payment
                                                </button>
                                            </div>

                                    </form>
                                </div>
                            </div>

                            <div class="card col-md-12 ">
                                <div class="card-header">
                                    <h5>Payment List</h5>
                                </div>
                                <div class="card-body">
                                    <table class="data-tables table mb-0 tbl-server-info">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                {{-- <th>Cash Voucher No</th> --}}
                                                <th>Payment Method</th>
                                                <th>Amount</th>
                                                <th>Note</th>
                                                <th>Payment Date</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payments as $key => $payment)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    {{-- <td>

                                                        {{ $payment->invoice_no }}

                                                    </td> --}}
                                                    <td>{{ $payment->transaction->transaction_name }}</td>
                                                    <td>{{ number_format($payment->amount) }}</td>
                                                    <td>{{ $payment->note }}</td>
                                                    <td>{{ $payment->payment_date }}</td>
                                                    {{-- <td>
                                                        <a href="{{ url('cash_voucher', $payment->id) }}"
                                                            class="btn btn-primary">Print</a>

                                                        <a href="{{ url('cash_voucher_edit', $payment->id) }}"
                                                            class="btn btn-primary">Edit</a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                    </div>
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
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="../../dist/js/demo.js"></script> --}}
    <!-- Page specific script -->
  <script>
        document.addEventListener("DOMContentLoaded", function() {
            getAccount(0); // Automatically run on page load

        });

        function getAccount(payment_count) {
            var locationId = $("#location").val();
            console.log(locationId);
            var $paymentMethod = $('#payment_method-' + payment_count);
            $paymentMethod.html('<option value="">Loading...</option>');

            if (locationId) {
                $.ajax({
                    url: "{{ route('get_accounts_transaction') }}",
                    method: 'GET',
                    data: {
                        locationId: locationId
                    },
                    success: function(data) {
                        console.log(data);

                        $paymentMethod.empty().append(
                            '<option value="">Select Transaction</option>'
                        );

                        if (data && data.length > 0) {
                            $.each(data, function(index, transaction) {
                                $paymentMethod.append(
                                    '<option value="' + transaction.id + '">' + transaction.transaction_name +
                                    '</option>'
                                );
                            });
                        } else {
                            $paymentMethod.append(
                                '<option value="">No Transaction available</option>'
                            );
                        }
                    },
                    error: function() {
                        $paymentMethod.empty().append(
                            '<option value="">Error loading transactions</option>'
                        );
                    }
                });
            } else {
                $paymentMethod.empty().append(
                    '<option value="">Select Transaction</option>'
                );
            }
        }




        // $(document).ready(function() {
        //     function fetchInvoiceUpdates() {
        //         const paymentId = $("#payment_id").val();

        //         $.ajax({
        //             url: "{{ url('payment_no_updates') }}",
        //             method: "GET",
        //             dataType: "json",
        //             data: {
        //                 location: $('#location').val(),
        //             },
        //             success: function(data) {
        //                 // console.log(data);
        //                 const invoiceNoInput = $("#invoice_no");




        //                 if (data.invoice_no && invoiceNoInput.val() !== data
        //                     .invoice_no) {
        //                     invoiceNoInput.val(data.invoice_no);
        //                 }


        //             },
        //             error: function(xhr, status, error) {
        //                 console.error("Failed to fetch invoice updates:", error);
        //             }
        //         });
        //     }
        //     // Fetch updates every 3 seconds

        //     fetchInvoiceUpdates();
        //     setInterval(fetchInvoiceUpdates, 3000);
        // });
    </script>
</body>

</html>
