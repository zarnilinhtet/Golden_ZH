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
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
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

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1>Profit&Loss </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Profit&Loss
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="ml-2 container-fluid">



                    {{-- <form action="{{ route('report#profitLossFitter') }}" method="GET">
                        @csrf
                        <input type="hidden" name="slug" value="">
                        <div class="row mb-5">
                            <div class="col-md-2">
                                <label>Date From</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label>Date From</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="col-md-2 pt-4 mt-2">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                    </form> --}}


                </div>
                <!-- /.modal -->
                <h4 class="ml-3 p-2 mt-5">
                    Location : {{ $warehouse->name }}
                </h4>
                <div class="mt-3 col-md-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title">Profit&Loss Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            {{-- <table id="example1" class="table table-bordered table-striped"> --}}
                            <table id="example1" class="table table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No.</th>
                                        <th rowspan="
                                        2">Account Number</th>
                                        <th rowspan="2">Account Name</th>
                                        <th colspan="2" class="text-center">Opening</th>
                                        <th colspan="2" class="text-center">Current</th>
                                        <th colspan="2" class="text-center">Total</th>
                                    </tr>
                                    <tr>
                                        <th>Credit</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Debit</th>
                                    </tr>

                                </thead>



                                <tbody>


                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($accounts as $account)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td><a
                                                    href="{{ url('account_location_transaction/' . $id . '/' . $account->id) }}">{{ $account->account_code }}</a>
                                            </td>
                                            <td>{{ $account->account_name }}</td>
                                            <td> <span
                                                    style="font-weight: bold">{{ number_format($pre_invoiceAmountsByAccount[$account->account_name] ?? 0) }}</span>

                                            </td>
                                            <td><span
                                                    style="font-weight: bold">{{ number_format($pre_refundAmountsByAccount[$account->account_name] ?? 0) }}</span>


                                            </td>
                                            <td><span
                                                    style="font-weight: bold">{{ number_format($invoiceAmountsByAccount[$account->account_name] ?? 0) }}</span>

                                            </td>
                                            <td><span
                                                    style="font-weight: bold">{{ number_format($refundAmountsByAccount[$account->account_name] ?? 0) }}</span>

                                            </td>
                                            <td><span
                                                    style="font-weight: bold">{{ number_format(($pre_invoiceAmountsByAccount[$account->account_name] ?? 0) + ($invoiceAmountsByAccount[$account->account_name] ?? 0)) }}</span>

                                            </td>
                                            <td><span
                                                    style="font-weight: bold">{{ number_format(($pre_refundAmountsByAccount[$account->account_name] ?? 0) + ($refundAmountsByAccount[$account->account_name] ?? 0)) }}</span>

                                            </td>





                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach

                                    </tr>
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
        $(document).ready(function() {
            $("#example1").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                pageLength: 30,

                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',



                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        customize: function(doc) {
                            // Access the table content in the PDF
                            var table = doc.content.find(function(item) {
                                return item.table;
                            });

                            if (table) {
                                // Center align text in all cells
                                table.table.body.forEach(function(row) {
                                    row.forEach(function(cell) {
                                        cell.alignment = 'center';
                                    });
                                });

                                // Optional: Set column widths if needed
                                // table.table.widths = ['*', '*', '*', '*']; // Adjust based on the number of columns
                            }

                            var title = doc.content.find(function(item) {
                                return item.text && typeof item.text === 'string';
                            });

                            if (title) {
                                title.text = 'Profit Loss Report ';
                                title.fontSize = 16;
                                title.bold = true;
                                title.alignment = 'center';
                                title.margin = [0, 0, 0, 20];
                            }
                        }
                    }, {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
