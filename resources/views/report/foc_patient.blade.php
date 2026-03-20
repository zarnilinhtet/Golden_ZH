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
                                <h1>FOC Patient</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">FOC Patient
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
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

                @if ($errors->has('phno'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong> {{ $errors->first('phno') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif


                <div class="ml-2 container-fluid">

                    <!-- left column -->

                    <!-- general form elements -->

                    @php
                        $userPermissions = [];
                        if (auth()->user()->permission) {
                            $decodedPermissions = json_decode(auth()->user()->permission, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $userPermissions = $decodedPermissions;
                            }
                        }
                    @endphp






                    <!-- /.modal -->
                    <div class="mt-3 col-md-12">

                        <div class="col-md-6">
                            <form action="{{ url('foc_patient_search') }}" method="get">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="start_date">Date From:</label>
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="end_date">Date To:</label>
                                        <input type="date" name="end_date" class="form-control" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="branch">Type:</label>
                                        <select name="branch" id="branch" class="form-control">
                                            <option value="All">All</option>
                                            @foreach ($branch_drop as $drop)
                                                <option value="{{ $drop }}">
                                                    {{ $drop }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <input type="submit" class="btn btn-primary form-control" value="Search"
                                            style="background-color: #218838">
                                    </div>
                                </div>
                            </form>

                        </div>
                        <h5 class="my-3 " style="font-weight: bold">Total Patients : {{ $customers->count() }}</h5>
                        <div class="card ">
                            {{-- <div class="card-header">
                                 <h3 class="card-title">Patient Table</h3>
                             </div> --}}
                            <!-- /.card-header -->
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">FOC Patient Table</h3>
                                <div class="dropdown ml-auto mr-5">
                                    <!-- Dropdown Menu HTML -->

                                    <div id="branchDropdown" class="dropdown ml-auto"
                                        style="display:inline-block; margin-left: 10px;">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ $currentBranchName }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{ url('foc_patient') }}" class="dropdown-item">All
                                                Type</a>
                                            @foreach ($branch_drop as $drop)
                                                <a class="dropdown-item"
                                                    href="{{ route('foc_patient', $drop) }}">{{ $drop }}</a>
                                            @endforeach
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>

                                            <th>Name</th>


                                            <th>Phone Number</th>
                                            <th>Department</th>
                                            <th> IN/OUT Patient</th>
                                            <th>Age</th>

                                            <th>Address</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = '1';
                                        @endphp
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td>{{ $no }}</td>


                                                <td><a
                                                        href="{{ url('foc_patient_invoice/' . $customer->id) }}">{{ $customer->name }}</a>
                                                </td>


                                                <td>{{ $customer->phno }}</td>
                                                <td>
                                                    {{ $customer->department }}
                                                </td>
                                                <td>{{ $customer->inout_patient }}</td>

                                                <td>{{ $customer->age }}</td>



                                                <td>{{ $customer->address }}</td>

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
        });
    </script>


</body>

</html>
