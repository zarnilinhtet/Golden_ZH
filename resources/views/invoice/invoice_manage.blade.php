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
                                <h1>Invoice</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Invoice</li>
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
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('delete'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>{{ session('delete') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>{{ session('error') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif


                        <div class="row"> <a href="{{ route('invoice_daily_sales') }}" class="mb-3 btn btn-primary"
                                style="border-radius:10px;"><i class="fa-regular fa-calendar-days"></i> Daily Sales</a>
                            {{-- <a href="{{ route('sale_return') }}" class="mb-3 mx-2 btn btn-primary"
                     style="border-radius:10px;"><i class="fa-regular "></i> Sale Return</a> --}}
                        </div>
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Invoice List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Invoice No.</th>
                                            <th>Customer Name</th>
                                            {{-- <th>Invoice Category</th> --}}
                                            {{-- <th>Register Mode</th> --}}
                                            <th>Invoice Date</th>
                                            <th>Sale Person</th>
                                            <th>Payment Status</th>
                                            <th>Total</th>
                                            <th>Return</th>
                                            <th>Tools</th>
                                            <th>Next Payment</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                            $quoteCounts = [];
                                            foreach ($invoices as $invoice) {
                                                if (!isset($quoteCounts[$invoice->invoice_no])) {
                                                    $quoteCounts[$invoice->invoice_no] = 0;
                                                }
                                                $quoteCounts[$invoice->invoice_no]++;
                                            }
                                            $previousQuoteNo = null;
                                        @endphp
                                        @foreach ($invoices as $invoice)
                                            <tr>

                                                @if ($previousQuoteNo !== $invoice->invoice_no)
                                                    <td rowspan="{{ $quoteCounts[$invoice->invoice_no] }}">
                                                        {{ $no }}
                                                    </td>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endif
                                                <td> {{ $invoice->invoice_no }}</td>

                                                <td>{{ $invoice->customer_name }}</td>
                                                {{-- <td>{{ $invoice->balance_due }}</td> --}}
                                                {{-- <td>{{ $invoice->invoice_category }}</td> --}}
                                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('j-M-Y') }}
                                                </td>
                                                <td>{{ $invoice->SaleBy->name??'' }}</td>

                                                @if ($invoice->remain_balance = 0 || $invoice->deposit == $invoice->total || $invoice->deposit > $invoice->total)
                                                    <td><span class="badge bg-success"
                                                            style="font-size: 16px;">Paid</span>
                                                    </td>
                                                @elseif($invoice->deposit != null && $invoice->deposit > 0)
                                                    <td><span class="badge bg-warning " style="font-size: 16px;">Partial
                                                            Paid</span></td>
                                                @else
                                                    <td><span class="badge bg-danger"
                                                            style="font-size: 16px;">Unpaid</span>
                                                    </td>
                                                @endif

                                                </td>
                                                 
                                                <td>{{ number_format($invoice->total, 2) }}</td>
                                                <td>{{ $invoice->invoice_return->count() }}</td>
                                                <td> <a href="{{ url('do_detail', $invoice->id) }}"
                                                        class="btn btn-primary btn-sm">Do Form</a></td>

                                                        <td class="text-center">
                                                            @php
                                                        $userPermissions = [];
                                                        if (auth()->user()->permission) {
                                                            $decodedPermissions = json_decode(
                                                                auth()->user()->permission,
                                                                true,
                                                            );
                                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                                $userPermissions = $decodedPermissions;
                                                            }
                                                        }
                                                    @endphp
                                                @if (in_array('Invoice Payment', $userPermissions) || auth()->user()->is_admin == '1')
                                                    <a class="badge bg-warning mr-2" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="Payment"
                                                        href="{{ url('make_payment', $invoice->id) }}"
                                                        style="font-size: 1rem; padding: 8px 12px; display: inline-block;">
                                                        <i class="fa fa-money-check-dollar text-white"></i>
                                                    </a>
                                                @endif
                                            </td>
                                                <td>
                                                    @php
                                                        $userPermissions = [];
                                                        if (auth()->user()->permission) {
                                                            $decodedPermissions = json_decode(
                                                                auth()->user()->permission,
                                                                true,
                                                            );
                                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                                $userPermissions = $decodedPermissions;
                                                            }
                                                        }
                                                    @endphp

                                                    <a href="{{ url('sale_return', $invoice->id) }}"
                                                        class="btn btn-danger btn-sm">Invoice Return</a>
                                                    @if (in_array('Invoice Details', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('/invoice_receipt_print', $invoice->id) }}"
                                                            class="btn btn-info btn-sm"><i
                                                                class="fa-solid fa-print"></i></a>

                                                        <a href="{{ url('/invoice_detail', $invoice->id) }}"
                                                            class="btn btn-primary btn-sm"><i
                                                                class="fa-solid fa-eye"></i></a>
                                                    @endif

                                                    @if (in_array('Invoice Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                        @if ($invoice->status === 'invoice')
                                                            <a href="{{ url('invoice_edit', $invoice->id) }}"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                        @else
                                                        @endif
                                                    @endif


                                                    @if (in_array('Invoice Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('invoice_delete', $invoice->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this Invoice ?')"><i
                                                                class="fa-solid fa-trash"></i></a>
                                                    @endif

                                                </td>


                                            </tr>
                                            @php
                                                $previousQuoteNo = $invoice->invoice_no;
                                            @endphp
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td colspan="">Total</td>
                                            <td colspan="">{{ number_format($invoices->sum('total'), 2) }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                            <!-- /.card-body -->
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
            $('#example1').DataTable({
                lengthChange: false,
                paging: true,
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column) from export
                        },
                        footer: true,
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A4', // Set page size

                        customize: function(doc) {
                            var tableBody = doc.content[1].table.body;

                            // Set font size for all content
                            doc.defaultStyle.fontSize = 8; // Set body font size
                            doc.styles.tableHeader.fontSize = 8; // Set header font size

                            // Remove last column from each row
                            tableBody.forEach(function(row, rowIndex) {
                                row.pop(); // Remove the last column
                            });

                            // Set equal width for all columns (excluding the removed last column)
                            doc.content[1].table.widths = Array(tableBody[0].length).fill('*');

                            // Center-align the headers
                            doc.styles.tableHeader.alignment = 'center';

                            // Center-align all table body cells
                            tableBody.forEach(function(row, rowIndex) {
                                row.forEach(function(cell, cellIndex) {
                                    cell.alignment =
                                        'center'; // Set cell alignment to center
                                });
                            });

                            // Custom footer row for "Total" (adjusting to match the new number of columns)
                            var totalRow = [{}, {}, {}, {}, {}, {
                                    text: 'Total',
                                    colSpan: 1,
                                    alignment: 'center',
                                    bold: 'true',
                                    fontSize: 8,
                                    margin: [0, 6, 0, 0]
                                }, // Adjust number of cells for spanning
                                {
                                    text: "{{ number_format($invoices->sum('total')) }}",
                                    alignment: 'center',
                                    bold: 'true',
                                    fontSize: 8,
                                    margin: [0, 6, 0, 0]
                                }
                            ];

                            // Add the total row to the footer
                            tableBody.push(totalRow);
                        }

                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column) from print
                        },
                        footer: true,
                    }
                ]
            });
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
