@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.nav')
        @include('layouts.sidebar')
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1> Sale Person</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Sale Person
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

                        @php
                            $userPermissions = [];
                            if (auth()->user()->permission) {
                                $decodedPermissions = json_decode(auth()->user()->permission, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $userPermissions = $decodedPermissions;
                                }
                            }
                        @endphp
                <div class="ml-2 container-fluid">


@if (in_array('Sale Person Register', $userPermissions) || auth()->user()->is_admin == '1')
                    <div class="row">
                        <div class="mr-auto col"> <button type="button" class="mr-auto btn btn-primary "
                                data-toggle="modal" data-target="#modal-lg">
                                Create Sale Person
                        </div>
                    </div>
                    @endif
                    <div class="modal fade" id="modal-lg">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Create Sale Person</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('sale_person_store') }}" method="POST">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name<span class="text-danger">*</span></label>


                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Name" required autofocus name="name">
                                            </div>
                                            <div class="form-group">
                                                <label for="unit">Phone Number</label>
                                                <!-- <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Unit" required autofocus name="unit"> -->

                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Phone Number" autofocus name="phno">
                                            </div>
                                            <div class="form-group">
                                                <label for="unit">Principal</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Principal" autofocus name="principal">
                                            </div>
                                            <div class="form-group">
                                                <label for="unit">Position</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Position" autofocus name="position">
                                            </div>
                                            <div class="form-group">
                                                <label for="unit">Address</label>


                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Address" autofocus name="address">
                                            </div>
                                             @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')

                                            <div class="form-group ">
                                                <label for="branch">Location<span
                                                        class="text-danger">*</span></label>
                                                <select name="location" id="branch" class="form-control"
                                                    required>

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
                                                <input class="form-control" type="text" name="location"
                                                    id="branch" value="{{ auth()->user()->level }}"
                                                    required>
                                            </div>
                                        @endif

                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">Save </button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <div class="mt-3 col-md-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title">Sale Person Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Phone Number</th>
                                        <th>Principal</th>
                                        <th>Position</th>
                                        <th>Address</th>
                                        <th>Location</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale_persons as $key => $sale_person)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $sale_person->name }}</td>
                                            <td>{{ $sale_person->phno }}</td>
                                            <td>{{ $sale_person->principal }}</td>
                                            <td>{{ $sale_person->position }}</td>
                                            <td>{{ $sale_person->address }}</td>
                                            <td>{{ $sale_person->warehouse->name??'' }}</td>

                                            <td class="d-flex justify-content-center">
                                                @if (in_array('Sale Person Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                <a href="{{ url('sale_person_edit', $sale_person->id) }}"
                                                    class="btn btn-success btn-sm"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                    @endif
                                                    @if (in_array('Sale Person Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                <a href="{{ url('sale_person_delete', $sale_person->id) }}"
                                                    class="btn btn-danger btn-sm mx-2"
                                                    onclick="return confirm('Are you sure you want to delete this Sale Person ?')"><i
                                                        class="fa-solid fa-trash"></i></a>
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            </div>
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
        // JavaScript code
        document.addEventListener('DOMContentLoaded', function() {
            var singleRadio = document.getElementById('single');
            var base_id = document.getElementById('base_id');
            var multiRadio = document.getElementById('multi');
            var multiUnitInput = document.getElementById('multiUnitInput');
            if (singleRadio.checked) {
                base_id.value = 0;
            }
            singleRadio.addEventListener('change', function() {
                multiUnitInput.style.display = 'none';
                base_id.value = 0;

            });

            multiRadio.addEventListener('change', function() {
                multiUnitInput.style.display = 'block';
            });
        });
    </script>
    {{-- <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script> --}}

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 100,
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Excel Export',
                    filename: 'sale_person', // Set filename here
                    footer: true, // Ensure the footer is included in the export
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var lastRow = $('row:last', sheet); // Get last row in the sheet
                        // Clone last row (which is the total row) and append it as the Grand Total row
                        var grandTotalRow = lastRow.clone();
                        $('row:last', sheet).after(grandTotalRow);
                        // Modify the first cell to indicate "Grand Total"
                        $('c[r^="A"]', grandTotalRow).attr('t', 'inlineStr').find('is t').text(
                            'Grand Total');
                    }
                }, ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
