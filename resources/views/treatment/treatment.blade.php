@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item text-white">
                    <a class="nav-link text-white" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle text-white" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
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
                                <h1>Treatment Management</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Treatment Management
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
                @if (session('delete'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

                @if ($errors->has('phno'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong> {{ $errors->first('phno') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif


                <div class="ml-2 container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <form action="{{ url('treatment_search') }}" method="get">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="start_date">Date From:</label>
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="end_date">Date To:</label>
                                        <input type="date" name="end_date" class="form-control" required>
                                    </div>


                                    <div class="col-md-2 form-group">
                                        <label for="">&nbsp;</label>
                                        <input type="submit" class="btn btn-primary form-control" value="Search"
                                            style="background-color: #218838">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- left column -->

                    <!-- general form elements -->

                    {{-- Permission --}}
                    @php
                        $choosePermission = [];
                        if (auth()->user()->permission) {
                            $decodedPermissions = json_decode(auth()->user()->permission, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $choosePermission = $decodedPermissions;
                            }
                        }
                    @endphp
                    {{-- End Permission --}}


                    @if (in_array('Treatment Register', $choosePermission) || auth()->user()->is_admin == '1')
                        <div class="row">
                            <div class="mr-auto col"> <a href="{{ url('treatment_register') }}"
                                    class="mr-auto btn btn-primary">
                                    Register New Treatment </a>

                            </div>
                        </div>
                    @endif


                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Treatment Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Phone Number</th>
                                            <th>Doctor Name</th>
                                            <th>Remark</th>
                                            <th>Location</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($treatments as $key => $treatment)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $treatment->name }}</td>
                                                <td>{{ $treatment->customer_age }}</td>
                                                <td>{{ $treatment->phno }}</td>
                                                <td>{{ $treatment->doctor->name ?? 'N/A' }}</td>
                                                <td>{{ $treatment->remark }}</td>
                                                <td>{{ $treatment->warehouse->name ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="row">
                                                        @if (in_array('Treatment Detail', $choosePermission) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('treatment_details', $treatment->id) }}"
                                                                title="Treatment Detail" class="btn btn-primary"><i
                                                                    class="fa-solid fa-eye"></i></a>
                                                        @endif


                                                        @if (in_array('Treatment Edit', $choosePermission) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('treatment_edit', $treatment->id) }}"
                                                                title="Customer Edit" class="mx-2 btn btn-success"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                        @endif

                                                        @if (in_array('Treatment Delete', $choosePermission) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('treatment_destory', $treatment->id) }}"
                                                                title="Customer Delete" class=" btn btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this Treatment ?')"><i
                                                                    class="fa-solid fa-trash"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
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
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
