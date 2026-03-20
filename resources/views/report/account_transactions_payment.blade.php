@include('layouts.header')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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

                                        </div>
                                        <div class="col-sm-6">
                                            {{-- <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Transaction Add Payment</li>
                                            </ol> --}}
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

                            <h4 class="my-5" style="font-weight: bold"> Transaction
                                Name -{{ $transaction->transaction_name }}</h4>





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

                                                <th>Date</th>
                                                <th>Description</th>

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
                                                    <td>{{ Str::ucfirst($payments->payment_status) }}</td>
                                                    <td>{{ number_format($payments->amount) }}</td>

                                                    <td>{{ Carbon::parse($payments->date)->format('d-M-Y') }} </td>
                                                    <td>{{ $payments->note }}</td>

                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                            @foreach ($invoices as $invoice)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $invoice->transaction->account->account_name }}</td>
                                                    <td>In</td>
                                                    <td>{{ number_format($invoice->net_total) }}</td>
                                                    <td>{{ Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}

                                                    </td>
                                                    <td>{{ $invoice->remark }}</td>
                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                            @foreach ($pos as $invoice)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $invoice->transaction->account->account_name }}</td>
                                                    <td>In</td>
                                                    <td>{{ number_format($invoice->net_total) }}</td>
                                                    <td>{{ Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}

                                                    </td>
                                                    <td>{{ $invoice->remark }}</td>
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



</body>

</html>
