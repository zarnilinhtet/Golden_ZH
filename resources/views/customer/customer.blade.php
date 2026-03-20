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
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1>Customer Management</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Customer Management
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
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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


                    <div class="row">
                        @if (in_array('Patient Register', $userPermissions) || auth()->user()->is_admin == '1')
                            <div class="mr-auto col"> <button type="button" class="mr-auto btn btn-primary "
                                    data-toggle="modal" data-target="#modal-xl">
                                    Register New Customer </button>


                            </div>
                        @endif

                        <div class="text-right mx-2">
                            @if (in_array('Birthday Reminder', $userPermissions) || auth()->user()->is_admin == '1')
                            <a href="{{ url('birthday_reminder') }}" class="btn text-white btn-info">
                                Birthday Reminder
                            </a>
                            @endif
                            @if (in_array('All Credit', $userPermissions) || auth()->user()->is_admin == '1')
                            <a href="{{ url('all_customer_credit') }}" class="btn btn-warning mx-4">All Credit</a>
                            @endif
                        </div>

                    </div>

                    <div class="modal fade" id="modal-xl">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" >
                                    <h4 class="modal-title"> Register New Customer</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" >
                                    <form action="{{ url('customer_register') }}" method="POST">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="form-group col-md-6">
                                                    <label for="name">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Enter Name" required autofocus name="name">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="phno">Phone Number </label>
                                                    <input type="text" class="form-control" id="phone number"
                                                        placeholder="Enter Phone Number" name="phno" required>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="address">Date Of Birth </label>
                                                    <input type="date" class="form-control" id="dob"
                                                        placeholder="Enter Date of Birth" name="dob">
                                                </div>
                                                <div class="form-group col-md-6">

                                                    @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')


                                                            <label for="branch">Location<span
                                                                    class="text-danger">*</span></label>
                                                            <select name="branch" id="branch"
                                                                class="form-control" required>

                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="form-group col-md-6" style="display: none;">
                                                            <label for="branch">Location<span
                                                                    class="text-danger">*</span></label>
                                                            <input class="form-control" type="text" name="branch"
                                                                id="branch" value="{{ auth()->user()->level }}"
                                                                required>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="row">








                                            </div>

                                            <hr class="my-2" style="color: black">





                                            <div class=" form-group">
                                                <label for="address">Address </label>
                                                <textarea name="address" id="" cols="30" rows="5" class="form-control"></textarea>
                                            </div>

                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save </button>
                                </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
                    <!-- /.modal -->
                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Customer Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Date of Birth</th>
                                            <th>Location</th>
                                            <th>Address</th>
                                            <th>Tools</th>
                                            <th>Action</th>
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
                                                        href="{{ url('customer_invoice', $customer->id) }}">{{ $customer->name }}</a>
                                                </td>
                                                <td>{{ $customer->phno }}</td>
                                                <td>{{ $customer->dob }}</td>
                                                <td>
                                                    @foreach ($branches as $branch)
                                                        @if ($customer->branch == $branch->id)
                                                            {{ $branch->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $customer->address }}</td>
                                                {{-- <td><a href="{{ url('upload_file', $customer->id) }}"
                                                        class="btn btn-warning btn-sm">File Upload</a>
                                                </td> --}}
                                                <td> @if (in_array('Credit', $userPermissions) || auth()->user()->is_admin == '1') <a href="{{ url('customer_credit', $customer->id) }}"
                                                    title="Customer Credit" class="btn btn-warning mx-2">Credit</a>
                                                @endif</td>
                                                <td>
                                                    <div class="row">

                                                        @if (in_array('Patient Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('customer_edit', $customer->id) }}"
                                                                title="Customer Edit" class="btn btn-success mx-2"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                        @endif

                                                        @if (in_array('Patient Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('customer_delete', $customer->id) }}"
                                                                title="Customer Delete" class="mx-2 btn btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this patient?');">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </a>
                                                        @endif

                                                    </div>



                                                </td>
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
