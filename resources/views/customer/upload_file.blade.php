@include('layouts.header')



<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link  text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  text-white" href="#">Date -
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
                    <button type="button" class="btn dropdown-toggle  text-white" data-toggle="dropdown" aria-haspopup="true"
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
                                <h1>Patient's FileUpload</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Patient's FileUpload
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
                @if ($errors->any())

                    @foreach ($errors->all() as $error)
                        {{-- <li>{{ $error }}</li> --}}
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>{{ $error }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endforeach

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
                    <h4 class="my-2 font-weight-bold">Patient Name : {{ $customer->name }}</h4>
                    <div class="card mx-2">
                        <div class="card-body">

                            <form action="{{ url('customer_file_upload_store', $customer->id) }}" method="POST"
                                enctype="multipart/form-data" id="myForm">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="file1">File 1</label>
                                        <input type="file" name="file1" class="form-control" id="file1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="file2">File 2</label>
                                        <input type="file" name="file2" class="form-control" id="file2">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="file3">File 3</label>
                                        <input type="file" name="file3" class="form-control" id="file3">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="file4">File 4</label>
                                        <input type="file" name="file4" class="form-control" id="file4">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="file5">File 5</label>
                                        <input type="file" name="file5" class="form-control" id="file5">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date">Date</label>
                                        <input type="date" name="date" class="form-control" id="date"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Patient's File Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">


                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>File 1</th>
                                            <th>File 2</th>
                                            <th>File 3</th>
                                            <th>File 4</th>
                                            <th>File 5</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = '1';
                                        @endphp
                                        @foreach ($customer_files as $file)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $file->date }}</td>
                                                <td>
                                                    @if ($file->file1)
                                                        {{ $file->file1 }}
                                                        <br> <a
                                                            href="{{ asset('logos/' . ($file->file1 ?? 'null')) }}"
                                                            class="btn btn-info btn-sm btn-round ms-2 "
                                                            download>Download</a>
                                                    @else
                                                        No File
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($file->file2)
                                                        {{ $file->file2 }}
                                                        <br><a href="{{ asset('logos/' . ($file->file2 ?? 'null')) }}"
                                                            class="btn btn-info btn-sm btn-round ms-2 "
                                                            download>Download</a>
                                                    @else
                                                        No File
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($file->file3)
                                                        {{ $file->file3 }}
                                                        <br><a href="{{ asset('logos/' . ($file->file3 ?? 'null')) }}"
                                                            class="btn btn-info btn-sm btn-round ms-2 "
                                                            download>Download</a>
                                                    @else
                                                        No File
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($file->file4)
                                                        {{ $file->file4 }}
                                                        <br> <a
                                                            href="{{ asset('logos/' . ($file->file4 ?? 'null')) }}"
                                                            class="btn btn-info btn-sm btn-round ms-2 "
                                                            download>Download</a>
                                                    @else
                                                        No File
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($file->file5)
                                                        {{ $file->file5 }}
                                                        <br><a href="{{ asset('logos/' . ($file->file5 ?? 'null')) }}"
                                                            class="btn btn-info btn-sm btn-round ms-2 "
                                                            download>Download</a>
                                                    @else
                                                        No File
                                                    @endif
                                                </td>


                                                <td>
                                                    <div class="row">

                                                        <a href="{{ url('file_edit', $file->id) }}" title="File Edit"
                                                            class="mx-1 btn btn-success"><i
                                                                class="fa-solid fa-pen-to-square"></i></a>


                                                        @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin' || auth()->user()->type == 'Branch Manager')
                                                            <a href="{{ url('file_delete', $file->id) }}"
                                                                title="File Delete" class=" btn btn-danger"><i
                                                                    class="fa-solid fa-trash"></i></a>
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
        document.addEventListener("DOMContentLoaded", function() {
            let customerFilesCount = {{ $file_count }};
            const submitButton = document.getElementById("submitButton");
            const form = document.getElementById("myForm"); // Replace with your form's ID

            submitButton.addEventListener("click", function(event) {
                if (customerFilesCount == 3) {
                    event.preventDefault(); // Prevent the form from submitting
                    alert("You cann't upload more than 3 times.");
                    submitButton.disabled = true;
                    form.reset();
                }
            });
        });
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "scrollX": true,
                "scrollCollapse": true,
                "pageLength": 30,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
