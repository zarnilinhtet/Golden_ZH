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
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1>General Ladger</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">General Ladger
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="ml-2 container-fluid">

                    <!-- left column -->

                    <!-- general form elements -->

                    {{-- <div class="row mb-3">
                        <label class="col-1">Account Name : </label>
                        <select class="form-control col-2" name="Supplier_type" id="Supplier_type">
                            < <option value="B2C">select account</option>
                                <option value="B2B">B2B</option>
                                <option value="Coporate">Coporate</option>
                        </select>
                    </div> --}}

                    {{-- <form action="{{ url('general_ledger_fitter') }}" method="GET">
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

                <div class="mt-3 col-md-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title">General Ladger Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Account Number</th>
                                        <th>Account Name</th>
                                        <th>Type</th>

                                        <th>IN</th>
                                        <th>OUT</th>

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
                                                    href="{{ url('account_transactions', $account->id) }}">{{ $account->account_code }}</a>
                                            </td>
                                            <td>{{ $account->account_name }}</td>
                                            <td>{{ $account->account_type }}</td>

                                            <td>
                                                <span class="text-info"
                                                    style="font-weight: bold">{{ number_format($invoiceAmountsByAccount[$account->account_name] ?? 0) }}</span>


                                            </td>
                                            <td><span class="text-info"
                                                    style="font-weight: bold">{{ number_format($refundAmountsByAccount[$account->account_name] ?? 0) }}</span>

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
    <script src="{{ asset('backend/js/jquery-3.6.0.js') }}"></script>

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

                            // Optional: Adding a title or additional text
                            doc.content.unshift({
                                text: 'Centered Table Title',
                                alignment: 'center',
                                fontSize: 18,
                                bold: true,
                                margin: [0, 0, 0, 20]
                            });

                            // Optional: Adjust the margins for the table
                            // doc.content[1].margin = [0, 0, 0, 20]; // Adjust margins if needed
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
</body>

</html>
