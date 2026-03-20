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
                                <h1>Item Report</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Item Report
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>



                <div class="ml-2 container-fluid">

                    <!-- left column -->

                    <!-- general form elements -->

                    <!-- /.modal -->

                    <div class="container-fluid mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ url('item_search') }}" method="get">
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

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Item Report</h3>
                                <div class="dropdown ml-auto mr-5">
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
                                                <a href="{{ url('report_item') }}" class="dropdown-item">All
                                                    Items</a>
                                                @foreach ($branch_drop as $drop)
                                                    <a class="dropdown-item"
                                                        href="{{ route('report_item', $drop->id) }}">{{ $drop->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif


                                </div>
                            </div>
                            @php
                                use App\Models\Warehouse;
                                $branches = Warehouse::all();
                            @endphp
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>

                                            <th>Item Name</th>
                                            <th>Branch</th>
                                            <th>Total Stock</th>
                                            <th>Item Type</th>





                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = '1';
                                            $nos = '1';
                                        @endphp

                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $nos }}</td>

                                                <td>
                                                    <a
                                                        href="{{ url('report_item_details', [$item->id, $item->warehouse_id]) }}?item_name={{ $item->item_name }}?branch={{ $item->warehouse_id }}">{{ $item->item_name }}
                                                    </a>
                                                </td>

                                                <td>
                                                    @foreach ($branches as $branch)
                                                        @if ($branch->id == $item->warehouse_id)
                                                            {{ $branch->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @php
                                                        $totalLevel1Qty = 0;
                                                        $totalLevel2Qty = 0;
                                                        $totalLevel3Qty = 0;
                                                        $showDefault = true; // Initialize the default display flag

                                                        if ($item->variations->isNotEmpty()) {
                                                            foreach ($item->variations as $variation) {
                                                                $quantity = intval($variation->quantity ?? 0);
                                                                $unit2 = intval($variation->unit2 ?? 1);
                                                                $unit3 = floatval($variation->unit3 ?? 1);

                                                                $lvl1 = floor($quantity / ($unit2 !== 0 ? $unit2 : 1));
                                                                $totalLevel1Qty += $lvl1;

                                                                $first_lvl2 = fmod($quantity, $unit2);
                                                                $level2qty = floor($first_lvl2);
                                                                $totalLevel2Qty += $level2qty;

                                                                $level3_fractional =
                                                                    ($first_lvl2 - $level2qty) * $unit3;
                                                                $level3qty = ceil($level3_fractional - 0.5);
                                                                $totalLevel3Qty += $level3qty;
                                                            }

                                                            // If any total is greater than 0, disable the default display
                                                            if (
                                                                $totalLevel1Qty > 0 ||
                                                                $totalLevel2Qty > 0 ||
                                                                $totalLevel3Qty > 0
                                                            ) {
                                                                $showDefault = false;
                                                            }
                                                        }
                                                    @endphp

                                                    {{-- Display calculated quantities if available --}}
                                                    @if ($totalLevel1Qty > 0)
                                                        {{ $totalLevel1Qty }}
                                                        {{ $item->variations->first()->name1 ?? '' }}
                                                    @endif
                                                    @if ($totalLevel2Qty > 0)
                                                        {{ $totalLevel2Qty }}
                                                        {{ $item->variations->first()->name2 ?? '' }}
                                                    @endif
                                                    @if ($totalLevel3Qty > 0)
                                                        {{ $totalLevel3Qty }}
                                                        {{ $item->variations->first()->name3 ?? '' }}
                                                    @endif

                                                    {{-- Display default if no quantities are set --}}
                                                    @if ($showDefault)
                                                        0 {{ $item->variations->first()->name1 ?? '' }}
                                                    @endif
                                                </td>





                                                <td>{{ $item->market }}</td>
                                            </tr>
                                            @php
                                                $nos++;
                                            @endphp
                                        @endforeach





                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                <h3 class="card-title">Sale Item Table</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>

                                            <th>Item Name</th>
                                            <th>Branch</th>
                                            <th>Quantity</th>





                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = '1';
                                            $nos = '1';
                                        @endphp


                                        @foreach ($invoice_items as $item)
                                            <tr>
                                                <td>{{ $nos }}</td>
                                                <td>{{ $item['item_name'] }}</td>
                                                <td>
                                                    @foreach ($branches as $branch)
                                                        @if ($branch->id == $item['warehouse_id'])
                                                            {{ $branch->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $item['quantity'] }}{{ '' }} {{ $item['unit'] }}
                                                </td>
                                            </tr>
                                        @endforeach





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


                            // Add the total row to the footer


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
