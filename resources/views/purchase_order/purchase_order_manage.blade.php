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

        @include('layouts.sidebar')
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Purchase Order Manage</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Purchase Order Manage</li>
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
                        @php
                            $userPermissions = [];
                            if (auth()->user()->permission) {
                                $decodedPermissions = json_decode(auth()->user()->permission, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $userPermissions = $decodedPermissions;
                                }
                            }
                        @endphp
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ session('error') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Purchase Order</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Purchase Order Number</th>
                                            <th>Supplier Name</th>
                                            <!-- <th>Phone Number</th> -->
                                            {{-- <th>Receiving Mode</th> --}}

                                            <th>Total</th>

                                            <th>Date</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @php
                                            $no = '1';
                                        @endphp
                                        @foreach ($po as $pos)
                                            <tr>

                                                <td>{{ $no }}</td>
                                                <td>{{ $pos->quote_no }}</td>

                                                <td>{{ $pos->supplier->name ?? 'N/A' }}</td>
                                                <td>{{ $pos->balance_due }}</td>


                                                <td>{{ number_format($pos->total) }}</td>
                                                <td>{{ $pos->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    @if (in_array('Purchase Order Details', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ route('purchase_order_details', $pos->id) }}"
                                                            class="btn btn-primary btn-sm"><i
                                                                class="fa-solid fa-eye"></i></a>
                                                    @endif

                                                    @if (in_array('Purchase Order Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ route('purchase_order_edit', $pos->id) }}"
                                                            class="btn btn-success btn-sm"><i
                                                                class="fa-solid fa-pen-to-square"></i></a>
                                                    @endif

                                                    @if (in_array('Purchase Order Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('purchase_order_delete', $pos->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this Purchase Order ?')"><i
                                                                class="fa-solid fa-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php
                                                $no++;
                                            @endphp
                                        @endforeach --}}
                                        @php
                                            $no = 1;
                                            $quoteCounts = [];
                                            foreach ($po as $pos) {
                                                if (!isset($quoteCounts[$pos->quote_no])) {
                                                    $quoteCounts[$pos->quote_no] = 0;
                                                }
                                                $quoteCounts[$pos->quote_no]++;
                                            }
                                            $previousQuoteNo = null;
                                        @endphp

                                        @foreach ($po as $pos)
                                            <tr>
                                                @if ($previousQuoteNo !== $pos->quote_no)
                                                    <td rowspan="{{ $quoteCounts[$pos->quote_no] }}">{{ $no }}
                                                    </td>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endif

                                                <td>{{ $pos->quote_no }}</td>
                                                <td>{{ $pos->supplier->name ?? 'N/A' }}</td>
                                                {{-- <td>{{ $pos->balance_due }}</td> --}}
                                                <td>{{ number_format($pos->total) }}</td>
                                                <td>{{ $pos->po_date ? $pos->po_date : $pos->created_at->format('Y-m-d') }}
                                                </td>
                                                <td>
                                                    @if (in_array('Purchase Order Details', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ route('purchase_order_details', ['id' => $pos->id, 'quote_no' => $pos->quote_no]) }}"
                                                            class="btn btn-primary btn-sm"><i
                                                                class="fa-solid fa-eye"></i></a>
                                                    @endif

                                                    @if (in_array('Purchase Order Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ route('purchase_order_edit', $pos->id) }}"
                                                            class="btn btn-success btn-sm"><i
                                                                class="fa-solid fa-pen-to-square"></i></a>
                                                    @endif

                                                    @if (in_array('Purchase Order Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('purchase_order_delete', $pos->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this Purchase Order ?')"><i
                                                                class="fa-solid fa-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>

                                            @php
                                                $previousQuoteNo = $pos->quote_no;
                                            @endphp
                                        @endforeach

                                    </tbody>
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
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A4', // Set page size
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 8; // Set font size
                            doc.styles.tableHeader.fontSize = 10; // Set header font size

                            // Set equal width for all columns
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');

                            // Center-align the headers
                            doc.styles.tableHeader.alignment = 'center';

                            // Center-align all table body cells
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.alignment =
                                        'center'; // Set cell alignment to center
                                });
                            });
                        },
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column) from export
                        }


                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column) from print
                        }
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
