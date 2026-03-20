@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link  text-gray" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
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
                    <button type="button" class="btn dropdown-toggle text-gray" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
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
        @include('layouts.sidebar') <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1> POS Reports</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">POS Reports
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>



                <div class="ml-2 container-fluid">


                    <div class="mb-4 container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ url('pos_search') }}" method="get">
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="start_date">Date From:</label>
                                            <input type="date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="end_date">Date To:</label>
                                            <input type="date" name="end_date" class="form-control" required>
                                        </div>
                                        @if (auth()->user()->is_admin == '1' || Auth::user()->type == 'Admin')
                                            <div class="col-md-4 form-group">
                                                <label for="branch">Branch:</label>
                                                <select name="branch" id="branch" class="form-control">
                                                    <option value="">All</option>
                                                    @foreach ($branch_drop as $drop)
                                                        <option value="{{ $drop->id }}">
                                                            {{ $drop->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="col-md-4 form-group" style="display: none;">
                                                <label for="branch">Branch:</label>
                                                <select name="branch" id="branch" class="form-control">
                                                    @foreach ($branch_drop as $drop)
                                                        @if ($drop->id == auth()->user()->level)
                                                            <option value="{{ $drop->id }}">
                                                                {{ $drop->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-md-3 form-group">
                                            <input type="submit" class="btn btn-primary form-control" value="Search"
                                                style="background-color: #218838">
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">POS Report</h3>
                                <div class="dropdown ml-auto ">
                                    <!-- Dropdown Menu HTML -->
                                    @if (auth()->user()->is_admin == '1' || Auth::user()->type == 'Admin')
                                        <div id="branchDropdown" class="dropdown ml-auto"
                                            style="display:inline-block; margin-left: 10px;">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                {{ $currentBranchName }}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="{{ url('report') }}" class="dropdown-item">All POS</a>
                                                @foreach ($branch_drop as $drop)
                                                    <a class="dropdown-item"
                                                        href="{{ route('report_pos', $drop->id) }}">{{ $drop->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif


                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>POS No.</th>
                                            <th>Date</th>

                                            <th>Branch</th>
                                            <th>Sale By</th>
                                            <th>Sub Total</th>
                                            <th>Discount</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;

                                            $subtotal = 0; // Initialize subtotal variable
                                            $discounttotal = 0; // Initialize subtotal variable
                                            $amounttotal = 0;
                                        @endphp
                                        @php
                                            use App\Models\Warehouse;
                                            $branches = Warehouse::all();
                                        @endphp

                                        @foreach ($invoices as $pos_datas)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td><a
                                                        href="{{ url('invoice_detail', $pos_datas->id) }}">{{ $pos_datas->invoice_no }}</a>
                                                </td>

                                                <td>{{ $pos_datas->invoice_date }}</td>

                                                <td>
                                                    @foreach ($branches as $branch)
                                                        @if ($branch->id == $pos_datas->location)
                                                            {{ $branch->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $pos_datas->quote_date }}</td>
                                                <td>{{ number_format($pos_datas->discount_total + $pos_datas->total) }}
                                                </td>

                                                <td>{{ $pos_datas->discount_total ?? 0 }}</td>
                                                <td>{{ number_format($pos_datas->total) }}</td>
                                            </tr>
                                            @php
                                                $no++;
                                                $subtotal += $pos_datas->discount_total + $pos_datas->total; // Add subtotal for each row
                                                $discounttotal += $pos_datas->discount_total;
                                                $amounttotal += $pos_datas->total;
                                            @endphp
                                        @endforeach



                                    <tfoot>
                                        <tr>
                                            <td colspan="4"> </td>
                                            <td style="text-align:right">Total</td>
                                            <td colspan="">{{ number_format($subtotal) }}</td>
                                            <td colspan="">{{ number_format($discounttotal) }}</td>
                                            <td colspan="">{{ number_format($amounttotal) }}</td>




                                            <!-- Display the subtotal here -->
                                        </tr>

                                    </tfoot>
                                    </tbody>



                                </table>

                                <table id="example1" class="table table-bordered table-striped my-3">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Sale By</th>
                                            <!-- <th>Number Of POS</th> -->
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($sale_totals as $sale_total)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td colspan="">{{ $sale_total->quote_date }}</td>
                                            <!-- <td colspan="">{{ $sale_total->total_invoices }}</td> -->

                                            <td>{{ number_format($sale_total->sale_total) }}</td>


                                            <!-- Display the subtotal here -->
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Payment Method</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Cash</td>
                                            <td>{{ number_format($totalCash) }}</td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>K Pay</td>
                                            <td>{{ number_format($totalKbz) }}</td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Wave Pay</td>
                                            <td>{{ number_format($totalWave) }}</td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Other</td>
                                            <td>{{ number_format($totalOther) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:right">Total</td>
                                            <td>{{ number_format($totalCash + $totalKbz + $totalWave + $totalOther) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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



    <script>
        $(function() {
            $('#example1').DataTable({
                lengthChange: false,
                paging: true,
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        footer: true, // Include the footer in the export
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

                            // Set equal width for all columns
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

                            // Custom footer row for "Total"
                            var totalRow = [{}, {}, {}, {}, {
                                    text: 'Total',
                                    colSpan: 1,
                                    alignment: 'center',
                                    bold: 'true',
                                    fontSize: 8,
                                    margin: [0, 6, 0, 0]
                                }, // Empty cells for spanning
                                {
                                    text: "{{ number_format($subtotal) }}",
                                    alignment: 'center',
                                    bold: 'true',
                                    fontSize: 8,
                                    margin: [0, 6, 0, 0]
                                }, {
                                    text: "{{ number_format($discounttotal) }}",
                                    alignment: 'center',
                                    bold: 'true',
                                    fontSize: 8,
                                    margin: [0, 6, 0, 0]
                                },
                                {
                                    text: "{{ number_format($amounttotal) }}",
                                    alignment: 'center',
                                    bold: 'true',
                                    fontSize: 8,
                                    margin: [0, 6, 0, 0]
                                }
                            ];

                            // Add the total row to the footer
                            tableBody.push(totalRow);

                        },
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        footer: true


                    }
                ]
            });
        });
    </script>


</body>

</html>
