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
                                <h1>Item Kits</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Item Kits</li>
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


                @if (session('excelimport'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('excelimport') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span dangeraria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ session('message') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="container-fluid">
                    <div class="ml-2 row d-flex">


                    </div>
                    <!-- /.modal -->
                    {{-- <div class="row col-md-6">
                        <a href="{{ url('/items') }}" class="btn btn-primary col-md-2 mx-2">Items</a>
                        <a href="{{ url('/item_kits') }}" class="btn btn-primary col-md-2">Item Kits</a>
                    </div> --}}

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
                        <div class="table-responsive card">
                            <div>
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="card-title">Item Kit List</h3>
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
                                                @if (auth()->user()->is_admin == '1' || Auth::user()->type == 'Admin')
                                                    <a href="{{ url('item_kits') }}" class="dropdown-item">All
                                                        Item Kits</a>
                                                    @foreach ($branch_drop as $drop)
                                                        <a class="dropdown-item"
                                                            href="{{ route('item.kit.index', $drop->id) }}">{{ $drop->name }}</a>
                                                    @endforeach
                                                @else
                                                    @foreach ($branch_drop as $drop)
                                                        @if ($drop->id == auth()->user()->level)
                                                            <a class="dropdown-item"
                                                                href="{{ route('item.kit.index', $drop->id) }}">{{ $drop->name }}</a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>



                                    </div>
                                </div>


                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="d-flex justify-content-end mb-3">
                                        <input type="text" id="search" class="form-control col-2 ms-auto"
                                            placeholder="Search items">
                                    </div>
                                    <table id="example1" class="table table-bordered table-striped item-tables">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Item Kit Name</th>
                                                <th>Category</th>
                                                <th>Location</th>
                                                <th>Qty</th>



                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="items-data">
                                            @php
                                                $total_price = 0;
                                                $total_retail = 0;
                                            @endphp
                                            @foreach ($itemsGroupedByName as $itemName => $items)
                                                <tr>
                                                    <td>{{ $loop->iteration }}
                                                    </td>
                                                    <td>{{ $items->item_name }}</td>

                                                    <td>{{ $items->category }}</td>

                                                    <td>{{ $items->warehouse->name }}</td>

                                                    <td>
                                                        @if ($items->market == 'Service')
                                                            <span class="text-danger">0</span>
                                                        @else
                                                            <span>
                                                                {{ $items->variations->isNotEmpty() ? $items->variations->sum('quantity') : '0' }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    </td>




                                                    </td>

                                                    @php
                                                        $total_price += $items->price1;
                                                        $total_retail += $items->retail1;
                                                    @endphp
                                                    <td>


                                                        <div class="row">
                                                            @if (in_array('Item Kits Details', $userPermissions) || auth()->user()->is_admin == '1')
                                                                <form action="{{ url('item_details', $items->id) }}"
                                                                    method="GET" class="mr-2">
                                                                    <input type="hidden" name="item_id"
                                                                        value="{{ $items->id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="fa-solid fa-eye"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            @if (in_array('Item Kits Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                                <form action="{{ url('item_kit_edit', $items->id) }}"
                                                                    method="GET" class="mr-2">
                                                                    <input type="hidden" name="item_id"
                                                                        value="{{ $items->id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-sm">
                                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            @if (in_array('Item Kits Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                                <form action="{{ url('item_delete', $items->id) }}"
                                                                    method="GET" class="mr-2">
                                                                    <input type="hidden" name="item_id"
                                                                        value="{{ $items->id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                        {{-- <a href="{{ url('in_out', $firstItem['id']) }}"
                                                            class="mt-1 btn btn-info btn-sm">In/Out
                                                            History </a> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="pagination-info text-start">
                                            Showing {{ $item->firstItem() }} to {{ $item->lastItem() }} of
                                            {{ $item->total() }} results
                                        </div>

                                        <div>
                                            {{ $item->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
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


    <script>
        $(document).ready(function() {
            $('#example1').DataTable({
                lengthChange: false,
                searching: false,
                info: false,
                paging: false,
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
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                let searchQuery = $(this).val();
                $.ajax({
                    url: "{{ route('item.kit.index') }}",
                    method: "GET",
                    data: {
                        search: searchQuery
                    },
                    success: function(response) {
                        $('.items-data').html($(response).find('.items-data').html());
                        $('.pagination-info').html($(response).find('.pagination-info').html());
                        $('.pagination').html($(response).find('.pagination').html());

                        let totalRetail = $(response).find('.total-retail-cell').html();
                        $('.total-retail-cell').html(totalRetail);
                    }
                });
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                let url = $(this).attr('href');
                let searchQuery = $('#search').val();

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: searchQuery
                    },
                    success: function(response) {
                        $('.items-data').html($(response).find('.items-data').html());
                        $('.pagination-info').html($(response).find('.pagination-info').html());
                        $('.pagination').html($(response).find('.pagination').html());

                        let totalRetail = $(response).find('.total-retail-cell').html();
                        $('.total-retail-cell').html(totalRetail);
                    }
                });
            });
        });
    </script>
</body>

</html>
