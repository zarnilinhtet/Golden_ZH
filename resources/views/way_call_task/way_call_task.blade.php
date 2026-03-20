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
                                <h1> Way Call Task</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Way Call Task
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
                                Create Task
                        </div>
                    </div>
                    @endif
                    <div class="modal fade" id="modal-lg">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Create Way Call Task</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('way_call_task_store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                <label for="name">Sale Person<span class="text-danger">*</span></label>



 <select name="sale_person_id" class="form-control" id="">
                                            @foreach($sale_person as $sale)

                                                    <option value="{{ $sale->id }}">{{ $sale->name }}</option>

                                            @endforeach
                                            <input type="hidden" name="assign_id" value="{{ $id }}">
                                        </select>
                                            </div>
                                                <div class="form-group col-md-6">
                                                <label for="name">Doctor Name<span class="text-danger">*</span></label>


                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Doctor Name" required autofocus name="doctor_name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit">Designation</label>
                                                <!-- <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Unit" required autofocus name="unit"> -->

                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Designation" autofocus name="designation">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit">Speciality</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Speciality" autofocus name="speciality">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit">Hp/Clinic</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Hp/Clinic" autofocus name="hp_clinic">
                                            </div>

                                             @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')

                                            <div class="form-group d-none">
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
                                            <div class="form-group col-md-6">
                                                <label for="base_id">Type</label>
                                                <select name="type" id="" class="form-control">
                                                    <option value="Operation Service">Operation Service</option>
                                                    <option value="Control Service">Control Service</option>
                                                    <option value="Delivery Call">Delivery Call</option>
                                                    <option value="KOL Relation/Promotion">KOL Relation/Promotion</option>
                                                    <option value="Holiday">Holiday</option>
                                                    <option value="Leave">Leave</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="image">Date</label>
                                                <input type="date" class="form-control" id="date" name="date"
                                                    required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="image">Image</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                    accept="image/*" >
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="unit">Address</label>


                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Address" autofocus name="address">
                                            </div>
                                        </div>

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
                            <h3 class="card-title">Way Call Task</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Doctor Name</th>

                                        <th>Designation</th>
                                        <th>Speciality</th>
                                        <th>Hp Clinic</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Address</th>
                                        {{-- <th>Location</th> --}}
                                         <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (collect($way_call_tasks)->flatten() as $key => $way_call_task)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $way_call_task->doctor_name }}</td>

                                            <td>{{ $way_call_task->designation }}</td>
                                            <td>{{ $way_call_task->speciality }}</td>
                                            <td>{{ $way_call_task->hp_clinic }}</td>
                                            <td>{{ $way_call_task->date }}</td>
                                           @php
    switch ($way_call_task->type) {
        case 'Operation Service':
            $bgColor = 'background-color: #FFC107;'; // Yellow
            break;
        case 'Control Service':
            $bgColor = 'background-color: #6A1B9A;'; // Purple
            break;
        case 'Delivery Call':
            $bgColor = 'background-color: #03A9F4;'; // Light Blue
            break;
        case 'KOL Relation/Promotion':
            $bgColor = 'background-color: #8BC34A;'; // Green
            break;
        case 'Holiday':
            $bgColor = 'background-color: #F44336;'; // Red
            break;
        case 'Leave':
            $bgColor = 'background-color: #FFDAB9;'; // Peach
            break;
        default:
            $bgColor = '';
            break;
    }
@endphp

<td style="{{ $bgColor }}" class="text-white">{{ $way_call_task->type }}</td>

                                            <td>{{ $way_call_task->address }}</td> <td>
                                                @if($way_call_task->image)
                                                    <img src="{{ asset('upload/task/'.$way_call_task->image) }}" width="50px" height="50px">
                                                @endif
                                            </td>
                                            {{-- <td>{{ $way_call_task->Warehouse->name??'' }}</td> --}}

                                            <td>
                                                @if (in_array('Way Call Task Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                <a href="{{ url('way_call_task_edit', $way_call_task->id) }}"
                                                    class="btn btn-success btn-sm"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                    @endif
                                                    @if (in_array('Way Call Task Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                <a href="{{ url('way_call_task_delete', $way_call_task->id) }}"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this Way Call Task?')"><i
                                                        class="fa-solid fa-trash"></i></a>
                                                    @endif
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
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 100,
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Excel Export',
                    exportOptions: {
                            columns: ':not(:last-child)' // Exclude the last column (Action column) from export
                        },
                    filename: 'Way Call Task', // Set filename here
                    footer: true, // Ensure the footer is included in the export
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        // Remove the last column (Actions)


                    }
                }, ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
