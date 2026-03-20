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
                                <h1>Items Expire</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Items Expire</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>






                <div class="container-fluid">


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
                        <div class=" card table-responsive">
                            <div class="">
                                <div class="card-header">
                                    <h3 class="card-title">Items List</h3>
                                </div>


                                <!-- /.card-header -->
                                <div class="card-body">

                                    <table id="example1" class=" table table-bordered table-striped items-tables">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Item Name</th>
                                                <th>Location</th>
                                                <th>Expired Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="items-data">
                                            @foreach ($variations as $key => $variation)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $variation->item ? $variation->item->item_name : 'N/A' }}</td>
                                                    <td>{{ $variation->item->warehouse ? $variation->item->warehouse->name : 'N/A' }}</td>
                                                    <td>{{ $variation->expired_date }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

                                </div>
                                <!-- /.card-body -->
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
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="../../dist/js/demo.js"></script> --}}
    <!-- Page specific script -->
    <script>
        $("#example1").DataTable({
            pageLength: 100,
            info: false,
            searching: true,
            lengthMenu: [
                [100, 250, 500, -1],
                [100, 250, 500, "All"]
            ]

        });

        document.querySelectorAll('.removeRowButton').forEach(function(button) {
            button.addEventListener('click', function() {
                $('#addRowModal').modal('hide');
            });
        });
    </script>

</body>

</html>
