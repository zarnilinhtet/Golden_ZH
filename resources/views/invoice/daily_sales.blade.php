@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">


                <div class="btn-group">
                    <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu ">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn  p-1 changelogout " style="width: 157px">
                                <i class="fa-solid fa-right-from-bracket "></i> Logout</button>

                        </form>


                    </div>
                </div>

                {{-- <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Logout</button>
                </form> --}}

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
                                <h1>Invoice Daily Sales</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Invoice Daily Sales</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="container-fluid">
                    <div class="row  justify-content-center d-flex">
                        <div class="my-2 container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ url('invoice_daily_sale_search') }}" method="get">
                                        <div class="row">
                                            <div class="col-md-5 form-group">
                                                <label for="">Date From :</label>
                                                <input type="date" name="start_date" class="form-control" required>
                                            </div>
                                            <div class="col-md-5 form-group">
                                                <label for="">Date To :</label>
                                                <input type="date" name="end_date" class="form-control" required>
                                            </div>
                                            <div class="mt-3 col-md-3 form-group">
                                                <input type="submit" class="btn btn-primary form-control"
                                                    value="Search" style="background-color: #218838">
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal -->
                        <div class="col-md-12 mt-3">
                            <div class="card ">
                                <div class="card-header">
                                    <h3 class="card-title">Daily Sales</h3>
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Invoice No.</th>
                                                <th>Customer Name</th>
                                                <th>Phone Number</th>
                                                <th>Discount</th>
                                                <th>Date</th>
                                                <th>Payment Status</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @php
                                            $no = '1';
                                        @endphp
                                        @foreach ($daily_sales as $sale)
                                            @foreach ($daily_invoices as $invoice)
                                                @if ($sale->invoiceid == $invoice->id) --}}
                                            {{-- <tr>
                                                        <td>{{ $no }}</td>
                                                        @if ($invoice->status === 'pos')
                                                            <td>POS - {{ $invoice->invoice_no }}</td>
                                                        @else
                                                            <td>Invoice - {{ $invoice->invoice_no }}</td>
                                                        @endif

                                                        <td>{{ $invoice->customer_name }}</td>
                                                        <td>{{ $invoice->phno }}</td>
                                                        <td>{{ $invoice->type }}</td>

                                                        <td>{{ $sale->description }}</td>
                                                        <td>{{ $sale->product_price }}</td>
                                                        <td>{{ $sale->product_qty }}</td> --}}
                                            {{-- <td>{{ $invoice->discount_total ?? 0 }}</td>
                                                        <td>{{ $sale->unit }}</td>
                                                        <td>{{ $sale->created_at }}</td>

                                                        <td>{{ $invoice->status }}</td>
                                                        <td>{{ $invoice->deposit ?? 0 }}</td>
                                                        <td>{{ $invoice->sub_total ?? 0 }}</td>
                                                        <td>{{ $sale->product_price * $sale->product_qty }}</td>
                                                        <td>{{ $invoice->total }}</td>
                                                        <td>
                                                            <a href="{{ url('/invoice_detail', $invoice->id) }}"
                                                                class="btn btn-primary btn-sm"><i
                                                                    class="fa-solid fa-eye"></i></a>
                                                        </td>


                                                    </tr> --}}
                                            {{-- @endif
                                            @endforeach
                                            @php
                                                $no++;
                                            @endphp
                                        @endforeach --}}


                                            @php
                                                $no = '1';
                                            @endphp
                                            @foreach ($daily_invoices as $invoice)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $invoice->invoice_no }}</td>
                                                    <td>{{ $invoice->customer_name }}</td>
                                                    <td>{{ $invoice->phno }}</td>
                                                    <td>{{ number_format($invoice->discount_total) ?? 0 }}</td>
                                                    <td>{{ $invoice->created_at->format('d-m-Y') }}</td>

                                                    @if ($invoice->remain_balance = 0 || $invoice->deposit == $invoice->total || $invoice->deposit > $invoice->total)
                                                        <td><span class="badge bg-success"
                                                                style="font-size: 15px;">Paid</span>
                                                        </td>
                                                    @elseif($invoice->deposit != null)
                                                        <td><span class="badge bg-warning "
                                                                style="font-size: 15px;">Partial
                                                                Paid</span></td>
                                                    @elseif($invoice->deposit == null)
                                                        <td><span class="badge bg-danger"
                                                                style="font-size: 15px;">Unpaid</span>
                                                        </td>
                                                    @endif

                                                    <td class="text-right me-1">{{ number_format($invoice->total) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('/invoice_detail', $invoice->id) }}"
                                                            class="btn btn-primary btn-sm"><i
                                                                class="fa-solid fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                                @php $no++; @endphp
                                            @endforeach
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" style="text-align:right">Total</td>
                                                <td colspan="" class="text-right me-1">
                                                    {{ number_format($total) }}</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->
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
        $(function() {
            $("#example1").DataTable({
                "scrollX": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
                // "buttons": ["excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 30,
                "buttons": ["excel", "pdf", "print"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>
