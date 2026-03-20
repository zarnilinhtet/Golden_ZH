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
                                <h1>Reorder Items </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Reorder Item</li>
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
                                                <th>Quatity</th>
                                            </tr>
                                        </thead>
                                        <tbody class="items-data">
                                            @foreach ($variations as $key => $variation)
                                                @php
                                                    $check_unit2 = !empty($variation->unit2) ? $variation->unit2 : 1;
                                                    $unit2 = floor($variation->quantity / $check_unit2);
                                                @endphp


                                                @if ($unit2 <= $variation->reorder_level_stock)
                                                    <tr>

                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $variation->item ? $variation->item->item_name : 'N/A' }}({{ $variation->descriptions ? $variation->descriptions : 'N/A' }})
                                                            @if ($variation->product_code)
                                                                ({{ $variation->product_code ? $variation->product_code : 'N/A' }})
                                                            @endif
                                                            @if ($variation->expired_date)
                                                                ({{ $variation->expired_date ? $variation->expired_date : 'N/A' }})
                                                            @endif
                                                        </td>
                                                        <td>{{ $variation->item->warehouse ? $variation->item->warehouse->name : 'N/A' }}
                                                        </td>
                                                        <td> @php

                                                            $level1qty = 0;
                                                            $level2qty = 0;
                                                            $level3qty = 0;

                                                            // Calculate level 1 quantity
                                                            $lvl1 = floor(
                                                                intval($variation->quantity) /
                                                                    (intval($variation->unit2) !== 0
                                                                        ? intval($variation->unit2)
                                                                        : 1),
                                                            );

                                                            $level1qty = $lvl1;

                                                            // Calculate remaining quantity for level 2
                                                            $first_lvl2 = fmod(
                                                                $variation->quantity,
                                                                (float) ($variation->unit2 ?? 1),
                                                            );
                                                            $level2qty = floor($first_lvl2);

                                                            // Calculate fractional part for level 3
                                                            $level3_fractional =
                                                                ($first_lvl2 - $level2qty) * (float) $variation->unit3;
                                                            $level3qty = ceil($level3_fractional - 0.5);
                                                        @endphp
                                                            @if ($level1qty != 0)
                                                                {{ $level1qty }}{{ ' ' }}
                                                                {{ $variation->name1 }}
                                                            @endif
                                                            @if ($level2qty != 0 && $level2qty > 0)
                                                                {{ $level2qty }}{{ ' ' }}
                                                                {{ $variation->name2 }}
                                                            @endif
                                                            @if ($level3qty != 0 && $level3qty > 0)
                                                                {{ $level3qty }}{{ ' ' }}
                                                                {{ $variation->name3 }}
                                                            @endif
                                                            @if ($level1qty == 0 && $level2qty == 0 && $level3qty == 0)
                                                                0 {{ $variation->name1 }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        {{-- <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $variation->item ? $variation->item->item_name : 'N/A' }}
                                                    </td>
                                                    <td>{{ $variation->item->warehouse ? $variation->item->warehouse->name : 'N/A' }}
                                                    </td>
                                                    <td>{{ $variation->quantity }}</td>
                                                </tr> --}}
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
