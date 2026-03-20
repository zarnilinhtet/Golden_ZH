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
                                <h1>Products</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Products</li>
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

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                {{-- @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                        @if ($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif --}}
                {{-- <form method="POST" action="{{ route('migrate') }}">
                    @csrf
                    <button type="submit">Migrate</button>
                </form> --}}

                <div class="container-fluid">
                    <div class="row ml-3">
                        <div class="ml-2 col row d-flex">
                            <form action="{{ route('file-import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4 form-group" style="max-width: 500px; margin: 0 auto;">

                                    <div class="text-left custom-file">

                                        @if (Auth::user()->is_admin == '1')
                                            <label for="warehouse">Choose Location</label>
                                            <select name="warehouse_id" id="warehouse" class="form-control" required>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <label for="warehouse">Choose Location</label>
                                            <select name="warehouse_id" id="warehouse" class="form-control" required>

                                                @foreach ($warehouses as $warehouse)
                                                    @if ($warehouse->id == auth()->user()->level)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        @endif


                                        <div class="p-1 mt-2 text-left custom-file col"
                                            style="border:#d0d0db 1px solid;background-color: white">
                                            <input type="file" name="file" class="" id="customFile">
                                        </div>
                                        <button class="mt-3 btn btn-primary">Import </button>
                                        {{-- @endif --}}
                                        <a class="mt-3 btn btn-success" href="{{ route('file-export') }}">Export </a>
                                    </div>
                                </div>
                                {{-- @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin' || Auth::user()->type == 'Warehouse') --}}
                                <a class="" href="{{ route('file-import-template') }}">Download
                                    Import CSV Template</a>
                                {{-- @endif --}}

                            </form>
                        </div>
                    </div>

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
                                <div class="card-header d-flex justify-between">
                                    <h3 class="card-title">Product List</h3>
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
                                                    <a href="{{ url('items') }}" class="dropdown-item">All
                                                        Products</a>
                                                    @foreach ($branch_drop as $drop)
                                                        <a class="dropdown-item"
                                                            href="{{ route('item.index', $drop->id) }}">{{ $drop->name }}</a>
                                                    @endforeach
                                                @else
                                                    @foreach ($branch_drop as $drop)
                                                        @if ($drop->id == auth()->user()->level)
                                                            <a class="dropdown-item"
                                                                href="{{ route('item.index', $drop->id) }}">{{ $drop->name }}</a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>



                                    </div>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body">
                                    {{-- <div class="d-flex justify-content-end mb-3">
                                        <input type="text" id="search" class="form-control col-2 ms-auto"
                                            placeholder="Search items">
                                    </div> --}}
                                    {{-- <table id="example1" class="table table-bordered table-striped item-tables "> --}}
                                    <table id="example1" class=" table table-bordered table-striped items-tables">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Brand</th>
                                                <th>Product Type</th>
                                                <th>Product Category</th>
                                                <th>Retail Price</th>
                                                <th>WholeSale Price</th>
                                                <th>Purchase Price</th>
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
                                                        <td>@if($items->image)
                                                            <img src="{{ asset('upload/item/'.$items->image) }}" width="50px" height="50px">
                                                            @endif
                                                        </td>
                                                    <td>{{ $items->item_name }}</td>
                                                    <td>{{ $items->BrandName->name??'' }}</td>

                                                    <td>{{ $items->product_type }}</td>
                                                    <td>{{ $items->product_category }}</td>
                                                    <td>
                                                        @if ($items->getVariation)
                                                            @if ($items->getVariation->retail3)

                                                                    {{ $items->getVariation->retail3 }}

                                                            @elseif($items->getVariation->retail2)

                                                                    {{ $items->getVariation->retail2 }}

                                                            @elseif($items->getVariation->retail1)

                                                                    {{ $items->getVariation->retail1 }}

                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($items->getVariation)
                                                            @if ($items->getVariation->wholesale3)

                                                                    {{ $items->getVariation->wholesale3 }}

                                                            @elseif($items->getVariation->wholesale2)

                                                                    {{ $items->getVariation->wholesale2 }}

                                                            @elseif($items->getVariation->wholesale1)

                                                                    {{ $items->getVariation->wholesale1 }}

                                                            @endif
                                                        @endif


                                                    </td>
                                                    <td>
                                                        {{-- {{ $items->pricePercent }} --}}
                                                        @if ($items->getVariation)
                                                            @if ($items->getVariation->price3)
                                                                {{ number_format( $items->getVariation->price3) }}
                                                            @elseif($items->getVariation->price2)
                                                                {{ number_format($items->getVariation->price2) }}
                                                            @else
                                                                {{ number_format( $items->getVariation->price1) }}
                                                            @endif
                                                        @endif
                                                    </td>

                                                    <td>

                                                        <div class="row">
                                                            @if (in_array('Item Details', $userPermissions) || auth()->user()->is_admin == '1')
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
                                                            @if (in_array('Item Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                                <form action="{{ url('item_edit', $items->id) }}"
                                                                    method="GET" class="mr-2">
                                                                    <input type="hidden" name="item_id"
                                                                        value="{{ $items->id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-sm">
                                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            @if (in_array('Item Delete', $userPermissions) || auth()->user()->is_admin == '1')
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                    {{-- <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="pagination-info text-start">
                                            Showing {{ $paginatedItems->firstItem() }} to
                                            {{ $paginatedItems->lastItem() }} of
                                            {{ $paginatedItems->total() }} results
                                        </div>
                                        <div>
                                            {{ $paginatedItems->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div> --}}
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
    {{--
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                let searchQuery = $(this).val();
                $.ajax({
                    url: "{{ route('item.index') }}",
                    method: "GET",
                    data: {
                        search: searchQuery
                    },
                    success: function(response) {
                        $('.items-data').html($(response).find('.items-data').html());
                        $('.pagination-info').html($(response).find('.pagination-info').html());
                        $('.pagination').html($(response).find('.pagination').html());

                        let totalRetail = $(response).find('.total-retail-cell')
                            .html();
                        $('.total-retail-cell').html(
                            totalRetail);
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

                        let totalRetail = $(response).find('.total-retail-cell')
                            .html();
                        $('.total-retail-cell').html(
                            totalRetail);
                    }
                });
            });
        });
    </script> --}}
</body>

</html>
