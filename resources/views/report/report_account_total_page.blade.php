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

    .card {
        background-color: #186CD5;
        color: white;
        transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
        background-color: #155a8a;
    }

    .text-truncate {
        font-family: Arial, Helvetica, sans-serif;
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
                                            <h1>Account Location All Report</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Account Location All Report</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div><!-- /.container-fluid -->
                            </section>


                            @php
                                $userPermissions = [];
                                if (auth()->user()->permission) {
                                    $decodedPermissions = json_decode(auth()->user()->permission, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        $userPermissions = $decodedPermissions;
                                    }
                                }
                            @endphp
                            {{-- <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Account Location Report Table</h3>
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
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                        </tbody>




                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div> --}}

                            <h4 class="ml-3 p-2" style="margin-top: 10vh;">
                                Location : All Report
                            </h4>

                            <div class="d-flex justify-content-center align-items-center flex-wrap overflow-auto">
                                <div class="row w-100">
                                    @if (in_array('General Ledger', $userPermissions) || auth()->user()->is_admin == '1')
                                        <div class="col-12 col-md-4 col-lg-4">
                                            <a href="{{ url('general_ledger') }}">
                                                <div class="card mx-2 my-3 shadow rounded">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-center" style="width: 100%;">General
                                                            Ledger</h5>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if (in_array('Balance Sheet', $userPermissions) || auth()->user()->is_admin == '1')
                                        <div class="col-12 col-md-4 col-lg-4">
                                            <a href="{{ url('balance_sheet') }}">
                                                <div class="card mx-2 my-3 shadow rounded">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-center" style="width: 100%;">Balance
                                                            Sheet</h5>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    @if (in_array('Profit&Loss', $userPermissions) || auth()->user()->is_admin == '1')
                                        <div class="col-12 col-md-4 col-lg-4">
                                            <a href="{{ url('profit_loss') }}">
                                                <div class="card mx-2 my-3 shadow rounded">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-center" style="width: 100%;">Profit &
                                                            Loss</h5>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    <!-- Add more cards as needed with the same structure -->
                                </div>
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
