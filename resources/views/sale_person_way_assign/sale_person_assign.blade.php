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
                                <h1>Sale Person Assign Management</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Sale Person Assign Management
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



                    <div class="row">
                        <div class="mr-auto col"> <button type="button" class="mr-auto btn btn-primary "
                                data-toggle="modal" data-target="#modal-lg">
                                Register Sale Person Assign
                        </div>
                    </div>
                    <div class="modal fade" id="modal-lg">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"> Register Sale Person Assign</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('sale_person_assign_store') }}" method="POST">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="name">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Enter Name" required autofocus name="name">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="name">Phone Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Enter Phone Number" required autofocus
                                                        name="phno">
                                                </div>
                                                 <div class="form-group col-md-12 ">
                                                <label for="name">Address<span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="address" id="" cols="30" rows="5"></textarea>
                                                </div>
                                             @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')
                                                <div class="form-group col-md-6">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>
                                                    <select name="branch" id="branch" class="form-control"
                                                        required>
                                                        <option selected disabled>Select Location</option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}">{{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                     <div class="form-group col-md-6">
                                                    <label for="name">Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}">
                                                </div>
                                            @else
                                                <div class="form-group" style="display: none;">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="branch"
                                                        id="branch" value="{{ auth()->user()->level }}" required>
                                                </div>
                                                     <div class="form-group col-md-12">
                                                    <label for="name">Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}">
                                                </div>
                                            @endif



                                                <div class="form-group col-md-6" id="formContainer">
                                                <div class="form-group">
                                                    <label for="name">Sale Person</label><span
                                                    class="text-danger">*</span></label>
                                                <select name="sale_person[]" id="sale_person" class="form-control" required>
                                                    <option selected disabled>Select Sale Person</option>

                                                    @foreach ($sale_persons as $sale_person)
                                                        <option value="{{ $sale_person->id }}">{{ $sale_person->name }}
                                                        </option>
                                                    @endforeach

                                                </select></div>
                                                </div>
                                                <div class="col-md-6 col-sm-6" style="margin-top: 30px;">
                                                <button id="addRowBtn" type="button" class="btn btn-primary "
                                                    title="Add Location Row"><i class="fa-solid fa-plus"></i></button>

                                                <button id="removeRowBtn" type="button" title="Remove Location Row"
                                                    class="btn btn-danger "><i class="fa-solid fa-xmark"></i></button>
                                                </div>
                                                <div class="form-group col-md-6">
                                                <label for="name">Assign</label><span
                                                    class="text-danger">*</span></label>
                                                <select name="assign" id="assign" class="form-control" required>
                                                    <option selected disabled>Select Assign</option>

                                                    <option value="Way">Way
                                                    </option>
                                                    <option value="Phone Call">Phone
                                                    </option>

                                                </select>
                                                </div>
                                                <div class="form-group col-md-6 d-none" id="call">
                                                <label for="name">Number of Calls</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Number of Calls"  name="call">
                                                </div>
                                                 <div class="form-group col-md-12">
                                                <label for="phno">Description</label>
                                                <textarea name="description" id="description" cols="10" rows="3" class="form-control"></textarea>
                                            </div>
                                        </div>





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
                    <!-- /.modal -->
                     @php
                            $userPermissions = [];
                            if (auth()->user()->permission) {
                                $decodedPermissions = json_decode(auth()->user()->permission, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $userPermissions = $decodedPermissions;
                                }
                            }
                        @endphp
                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Sale Person Assign Table</h3>
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
                                            <th>Address</th>
                                            <th>Date</th>
                                            <th>Sale Person</th>
                                            <th>Assign</th>

                                            <th>Report</th>
                                            <th>Tools</th>


                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = '1';
                                        @endphp
                                        @foreach($assigns as $assign)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $assign->name }}</td>
                                            <td>{{ $assign->phno }}</td>
                                            <td>{{ $assign->address }}</td>
                                            <td>{{ $assign->date }}</td>
                                            {{-- <td>{{ $assign->SalePerson->name }}</td> --}}
                                            <td>

                                                @php
                                                    $levelIds = explode(',', $assign->sale_person);
                                                    $salePersonMap = $sale_persons->pluck('name', 'id')->toArray();
                                                @endphp

                                                @if (!empty($levelIds))
                                                    {{ implode(', ', array_filter(array_map(function($id) use ($salePersonMap) {
                                                        return $salePersonMap[trim($id)] ?? null;
                                                    }, $levelIds))) }}
                                                @else
                                                    {{ $assign->sale_person }}
                                                @endif

                                            </td>
                                            <td>{{ $assign->assign }}</td>
                                            <td>@if (in_array('Sale Person Report', $userPermissions) || auth()->user()->is_admin == '1')<a href="{{ url('sale_person_report', $assign->id) }}" class="btn btn-primary btn-sm"> Report</a>
                                                @endif
                                            </td>
                                                <td>@if (in_array('Way Call Task', $userPermissions) || auth()->user()->is_admin == '1')<a href="{{ url('way_call_task', $assign->id) }}" class="btn btn-success btn-sm"> Task</a>
                                                @endif
                                            </td>
                                            <td class="d-flex justify-content-center">


                                                    @if (in_array('Sale Person Assign Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('sale_person_assign_edit', $assign->id) }}"
                                                            class="btn btn-success btn-sm"><i
                                                                class="fa-solid fa-pen-to-square"></i></a>
                                                    @endif

                                                    @if (in_array('Sale Person Assign Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('sale_person_assign_delete', $assign->id) }}"
                                                            class="btn btn-danger btn-sm mx-2"
                                                            onclick="return confirm('Are you sure you want to delete this ?')"><i
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
        $(document).ready(function() {
            var rowCount = 1; // Initial row count

            $('#addRowBtn').on('click', function() {
                var $lastFormGroup = $('#formContainer .form-group:last');
                var $newFormGroup = $lastFormGroup.clone();

                rowCount++;
                $newFormGroup.find('select').val('');
                // $newFormGroup.find('label').hide();
                $('#formContainer').append($newFormGroup);
            });

            $('#removeRowBtn').on('click', function() {
                if ($('#formContainer .form-group').length > 1) {
                    $('#formContainer .form-group:last').remove();
                }
            });
        });
    </script>
<script>
        $(document).ready(function() {
            $('#branch').on('change', function() {


                const selectedBranch = $(this).val();
                const doctorSelect = document.getElementById('sale_person');

                doctorSelect.innerHTML = '<option value="">Select Sale Person</option>';

                // Filter and add the doctors based on the selected branch
                @foreach ($sale_persons as $sale)
                    if (selectedBranch === '{{ $sale->location}}') {
                        const option = document.createElement('option');
                        option.value = '{{ $sale->id }}';
                        option.textContent = '{{ $sale->name }}';
                        doctorSelect.appendChild(option);
                    }
                @endforeach
            });
            $("#location").trigger("change");



        });
    </script>
    <script>
        $(document).ready(function() {
            $('#assign').on('change', function() {


                const selectedBranch = $(this).val();
                if(selectedBranch == 'Way'){
                    $("#call").addClass('d-none');


                }else{
                    $("#call").removeClass('d-none');
                }
            });




        });
    </script>
    <script>
        $(document).ready(function() {
            $('#sale_commission').on('input', function() {
                if ($(this).val()) {
                    $('#sale_commission_percentage').prop('readonly', true).val(
                        ''); // Disable and clear percent field
                } else {
                    $('#sale_commission_percentage').prop('readonly', false); // Enable percent field
                }
            });

            $('#sale_commission_percentage').on('input', function() {
                if ($(this).val()) {
                    $('#sale_commission').prop('readonly', true).val(
                        ''); // Disable and clear amount field
                } else {
                    $('#sale_commission').prop('readonly', false); // Enable amount field
                }
            });
        });
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
