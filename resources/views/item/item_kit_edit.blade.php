@include('layouts.header')
<link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
<script src="{{ asset('backend/js/jquery191.js') }}"></script>
<script src="{{ asset('backend/js/typehead401.js') }}"></script>

<script src="{{ asset('backend/js/moment2103.js') }}"></script>
<style>
    .changelogout:hover {
        background-color: whitesmoke;
        color: red;
    }
</style>

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
                                <h1>

                                    Item Kit Edit

                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Item Kit Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

            </section>
            <div class="content-body">
                <div class="container-fluid justify-content-center d-flex">
                    <div class="card card-default col-md-12">
                        <div class="">
                            <h3 class="col-md-12 mt-5">
                                @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')
                                    <div class="frmSearch col-sm-4">
                                        <label for="" style="font-size: 20px;">Item Location</label>
                                        <select name="item_location" id="item_location" class="form-control" disabled>
                                            @foreach ($item as $items)
                                                @if ($items->parent_id == '0')
                                                    <option value="{{ $items->warehouse_id }}" selected>
                                                        {{ $items->warehouse->name ?? 'N/A' }}
                                                    </option>
                                                @endif
                                            @endforeach
                                            @foreach ($branchs as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="frmSearch col-sm-4" style="display: none;">
                                        <label for="" style="font-size: 20px;">Item Location</label>
                                        <select name="item_location" id="item_location" class="form-control">
                                            @foreach ($item as $items)
                                                @if ($items->warehouse_id == auth()->user()->level)
                                                    <option value="{{ $items->warehouse_id }}" selected>
                                                        {{ $items->warehouse->name ?? 'N/A' }}
                                                    </option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </div>

                                @endif

                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->


                        @foreach ($item as $items)
                            <form action="{{ url('item_update', $items->id) }}" method="POST"
                                enctype="multipart/form-data" class="item-form">
                                @csrf
                                <div class="card-body">
                                    <div class="row">


                                        <div class="form-group col-md-6">
                                            <label for="item_name">Item Name</label>
                                            <input type="text" class="form-control" id="item_name" name="item_name"
                                                placeholder="Enter Item Name" value="{{ $items->item_name }}" required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="descriptions">Item Desctriptions</label>
                                            <input type="text" class="form-control" id="descriptions"
                                                placeholder="Enter Item Descriptions" name="descriptions"
                                                value="{{ $items->descriptions }}">
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label for="category">Item Category</label>
                                            <input type="text" class="form-control" id="category" name="category"
                                                value="{{ $items->category }}" placeholder="Enter Item Category">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="warehouse_id">Location<span class="text-danger">*</span></label>
                                            <select name="warehouse_id" id="warehouse_id" class="form-control">
                                                <option value="{{ $items->warehouse_id }}" selected>
                                                    {{ $items->warehouse->name ?? 'N/A' }}
                                                </option>
                                                @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')
                                                    @foreach ($branchs as $warehouse)
                                                        <option value="{{ $warehouse->id }}">
                                                            {{ $warehouse->name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @foreach ($branchs as $warehouse)
                                                        @if ($warehouse->id == auth()->user()->level)
                                                            <option value="{{ $warehouse->id }}">
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif


                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="market">Item Type</label>
                                            <select name="market" id="market" class="form-control">
                                                <option value="Stock"
                                                    @if ($items->market == 'Stock') selected @endif>Stock</option>
                                                <option value="Service"
                                                    @if ($items->market == 'Service') selected @endif>Service</option>

                                            </select>
                                        </div>
                                    </div>


                                    <hr>




                                    <div id="item_kit_add">
                                        <div class="row">
                                            <div class="frmSearch col-md-3">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="location"
                                                            class="caption">{{ trans('Location') }}&nbsp;</label>
                                                    </span>
                                                    <select name="location" id="location"
                                                        class="mb-4 form-control location" required>
                                                        @foreach ($branchs as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                @if ($warehouse->id == $items->warehouse_id) selected @endif>
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="frmSearch col-md-3">
                                                <span style="font-weight:bolder">
                                                    <label for="productname"
                                                        class="caption">{{ trans('Search Item Name ') }}&nbsp;</label>
                                                </span>
                                                <input type="text" class="form-control productname typeahead"
                                                    name="itemname" id="productname" autocomplete="off"
                                                    placeholder="Search Item Name">
                                                <input type="hidden"
                                                    class="form-control result_product_name typeahead result_product_name"
                                                    name="result_product_name[]" id="result_product_name-0"
                                                    autocomplete="off">
                                                <input type="hidden"
                                                    class="form-control result_descriptions typeahead descriptions"
                                                    name="result_descriptions[]" id="result_descriptions-0"
                                                    autocomplete="off">
                                                <input type="hidden"
                                                    class="form-control result_product_code typeahead result_product_code"
                                                    name="result_product_code[]" id="result_product_code-0"
                                                    autocomplete="off">
                                                <input type="hidden"
                                                    class="form-control result_expired_date typeahead result_expired_date"
                                                    name="result_expired_date[]" id="result_expired_date-0"
                                                    autocomplete="off">
                                                <div id="customer-box-result"></div>
                                            </div>

                                        </div>
                                        <table class=" table-bordered">
                                            <thead style="background-color:#0047AA;color:white;">
                                                <tr class="item_header bg-gradient-directional-blue white"
                                                    style="margin-bottom:10px;">
                                                    <th width="5%" class="text-center">{{ trans('No') }}</th>
                                                    <th width="18%" class="text-center">{{ trans('Item Name') }}
                                                    </th>
                                                    <th width="18%" class="text-center">
                                                        {{ trans('Item Descriptions') }}
                                                    </th>

                                                    <th width="8%" class="text-center">{{ trans('Qty') }}</th>
                                                    <th width="10%" class="text-center">{{ trans('Unit') }}</th>
                                                    <th width="10%" class="text-center">{{ trans('Price') }}</th>
                                                    <th width="14%" class="text-center">{{ trans('Amount') }}
                                                    </th>
                                                    <th class="text-center">{{ trans('Action') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="showitem123">
                                                @php
                                                    $no = 0;
                                                @endphp
                                                @foreach ($items_kits as $key => $item_kit)
                                                    @foreach ($item_kit->variations as $variation)
                                                        <tr>
                                                            <td class="text-center" id="count">
                                                                {{ $no + 1 }}
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control item_kit_name"
                                                                    name="item_kit_name[]"
                                                                    id="item_kit_name-{{ $no }}"
                                                                    autocomplete="off"
                                                                    value="{{ $item_kit->item_name }}" readonly>
                                                                <input type="hidden" class="form-control item_kit_id"
                                                                    name="item_kit_id[]"
                                                                    id="item_kit_id-{{ $no }}"
                                                                    autocomplete="off"
                                                                    value="{{ $item_kit->item_id }}">
                                                                <input type="hidden"
                                                                    class="form-control result_item_name typeahead"
                                                                    name="result_item_name[]"
                                                                    id='result_item_name-{{ $no }}'
                                                                    autocomplete="off"
                                                                    value="{{ $item_kit->product_name }}">

                                                                <input type="hidden"
                                                                    class="form-control result_id typeahead"
                                                                    name="result_id[]"
                                                                    id='result_id-{{ $no }}'
                                                                    autocomplete="off"
                                                                    value="{{ $item_kit->variation_id }}">
                                                                <input type="hidden"
                                                                    class="form-control result_variation_id typeahead"
                                                                    name="result_variation_id[]"
                                                                    value="{{ $item_kit->variation_id }}"
                                                                    id='result_variation_id-{{ $no }}'
                                                                    autocomplete="off">
                                                            </td>
                                                            <td><input type="text"
                                                                    class="form-control descriptions"
                                                                    name="item_kit_descriptions[]"
                                                                    id="item_kit_descriptions-{{ $no }}"
                                                                    autocomplete="off"
                                                                    value="{{ $variation->descriptions ?? '' }}"
                                                                    readonly>
                                                            </td>

                                                            <td><input type="text"
                                                                    class="form-control item_kit_quantity"
                                                                    name="item_kit_quantity[]"
                                                                    id="item_kit_quantity-{{ $no }}"
                                                                    value="{{ $item_kit->qty }}" autocomplete="off">
                                                            </td>

                                                            <td><select class="form-control item_kit_unit"
                                                                    name="item_kit_unit[]"
                                                                    id="item_kit_unit-{{ $no }}">



                                                                    {{-- <option value="{{ $item_kit->item->name1 }}"
                                                                    @if ($item_kit->item->name1 == $item_kit->item_unit) selected @endif>
                                                                    {{ $item_kit->item->name1 }}</option>


                                                                <option value="{{ $item_kit->item->name2 }}"
                                                                    @if ($item_kit->item->name2 == $item_kit->item_unit) selected @endif>
                                                                    {{ $item_kit->item->name2 }}</option>

                                                                <option value="{{ $item_kit->item->name3 }}"
                                                                    @if ($item_kit->item->name3 == $item_kit->item_unit) selected @endif>
                                                                    {{ $item_kit->item->name3 }}</option> --}}



                                                                    <option value="{{ $variation->name1 }}"
                                                                        @if ($variation->name1 == $item_kit->item_unit) selected @endif>
                                                                        {{ $variation->name1 }}
                                                                    </option>

                                                                    <option value="{{ $variation->name2 }}"
                                                                        @if ($variation->name2 == $item_kit->item_unit) selected @endif>
                                                                        {{ $variation->name2 }}
                                                                    </option>

                                                                    <option value="{{ $variation->name3 }}"
                                                                        @if ($variation->name3 == $item_kit->item_unit) selected @endif>
                                                                        {{ $variation->name3 }}
                                                                    </option>



                                                            </td>
                                                            <td><input type="text"
                                                                    class="form-control item_kit_retail_price"
                                                                    name="item_kit_retail_price[]"
                                                                    id="item_kit_retail_price-{{ $no }}"
                                                                    autocomplete="off"
                                                                    value="{{ $item_kit->retail_price }}">
                                                            </td>
                                                            <td style="display: none;"><input type="text"
                                                                    class="form-control item_kit_wholesale_price"
                                                                    name="item_kit_wholesale_price[]"
                                                                    id="item_kit_wholesale_price-{{ $no }}"
                                                                    autocomplete="off">
                                                            </td>
                                                            <td style="display: none;"><input type="text"
                                                                    class="form-control warehouse" name="warehouse[]"
                                                                    id="warehouse-{{ $no }}"
                                                                    autocomplete="off"
                                                                    value="{{ $item_kit->warehouse }}"></td>
                                                            <td style="text-align:center"><input type="text"
                                                                    class="form-control item_kit_amount"
                                                                    name="item_kit_amount[]"
                                                                    id="item_kit_amount-{{ $no }}" readonly
                                                                    value="{{ $item_kit->qty * $item_kit->retail_price }}">
                                                            </td>
                                                            <td style="width: 10%;" class="text-center"><button
                                                                    type="button"
                                                                    class="btn btn-danger remove_item_btn">Remove</button>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $no++;
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <table class="mt-3" style="width: 100%">
                                            <tbody id="showitem">
                                                <tr style="display: table-row;">
                                                    <td></td>
                                                    <td colspan="">

                                                    </td>
                                                </tr>
                                                <tr class="last-item-row sub_c">
                                                    <td></td>
                                                    <td class="add-row">
                                                        <button type="button" class="btn btn-success"
                                                            id="addproduct"
                                                            style="margin-top:20px;margin-bottom:20px;display:none;">
                                                            <i class="fa fa-plus-square"></i>
                                                            {{ trans('Add row') }}
                                                        </button>
                                                        <button type="button" class="btn btn-primary"
                                                            id="calculate">
                                                            Calculate
                                                        </button>


                                                        <a href="{{ URL('items') }}" target="_blank"
                                                            id="item_search">
                                                            <button type="button" class="btn btn-success">
                                                                <i class="fa fa-plus-square"></i> Item Search
                                                            </button></a>

                                                    </td>
                                                    <td colspan="6"></td>
                                                    <br><br>

                                                </tr>

                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="5">

                                                    </td>
                                                    <td colspan="3" align="right"><strong> Total
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2" class="col-md-4"><input
                                                            type="text" name="item_kit_total_price"
                                                            class="form-control" id="invoiceyoghtml" readonly
                                                            style="background-color: #E9ECEF">

                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="5">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Service Charge
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2" class="col-md-4"><input
                                                            type="text" name="service_charge" class="form-control"
                                                            id="total_discount" onchange="calculateTotal()"
                                                            value={{ $items->item_unit }}>
                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="5">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Sale Price
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2" class="col-md-4"><input
                                                            type="text" name="item_kit_sale_price"
                                                            class="form-control" id="total_total" readonly
                                                            style="background-color: #E9ECEF">

                                                    </td>

                                                </tr>


                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-5" style="font-size: 17.6px;">Sale Price</div>
                                    <hr>

                                    @foreach ($variations as $key => $variation)
                                        <div class="mt-3 row mb-5">
                                            <div class="form-group col-md-4">
                                                <label for="expired_date">Expired Date</label>
                                                <input type="date" class="form-control" id="expired_date"
                                                    name="expired_date[]" value="{{ $variation->expired_date }}">
                                            </div>
                                            <input type="hidden" name="variation_id[]"
                                                value="{{ $variation->id }}">

                                            <div class="form-group col-md-4">
                                                <label for="variations_descriptions">Descriptions</label>
                                                <input type="text" class="form-control"
                                                    id="variations_descriptions" name="variations_descriptions[]"
                                                    placeholder="Enter Descriptions"
                                                    value="{{ $variation->descriptions }}">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="product_code">Product Code</label>
                                                <input type="text" class="form-control" id="product_code"
                                                    name="product_code[]" placeholder="Enter Product Code"
                                                    value="{{ $variation->product_code }}">
                                            </div>


                                            <div class="form-group col-md-4">
                                                <label for="barcode">Barcode</label>
                                                <input type="text" class="form-control" id="variations_barcode"
                                                    name="variations_barcode[]" placeholder="Enter BarCode"
                                                    value="{{ $variation->variations_barcode }}">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="quantity">
                                                    Quantity
                                                </label>
                                                <input type="number" required class="form-control" name="quantity[]"
                                                    id="quantity" value="{{ $variation->quantity }}"
                                                    placeholder="Enter Quantity">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="reorder_level_stock">Reorder Level Stock</label>
                                                <input type="number" class="form-control" id="reorder_level_stock"
                                                    name="reorder_level_stock[]"
                                                    placeholder="Enter Reorder Level Stock"
                                                    value="{{ $variation->reorder_level_stock }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <table class="table table-bordered">
                                                <thead class="text-center">
                                                    <th>Level</th>
                                                    <th>Unit</th>
                                                    <th>Name</th>
                                                    <th>Purchase Price</th>
                                                    <th>WholeSale Price</th>
                                                    <th>Retail Price</th>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <input type="number" class="form-control" name="unit1"
                                                                value="1" readonly>
                                                        </td>
                                                        <td>
                                                            <select name="name1[]" id="name1"
                                                                class="form-control" required>
                                                                <option value="amp"
                                                                    @if ($variation->name1 == 'amp') selected @endif>
                                                                    amp</option>
                                                                <option value="tube"
                                                                    @if ($variation->name1 == 'tube') selected @endif>
                                                                    tube</option>
                                                                <option value="strip"
                                                                    @if ($variation->name1 == 'strip') selected @endif>
                                                                    strip</option>
                                                                <option value="cap"
                                                                    @if ($variation->name1 == 'cap') selected @endif>
                                                                    cap</option>
                                                                <option value="pcs"
                                                                    @if ($variation->name1 == 'pcs') selected @endif>
                                                                    pcs</option>
                                                                <option value="sac"
                                                                    @if ($variation->name1 == 'sac') selected @endif>
                                                                    sac</option>
                                                                <option value="box"
                                                                    @if ($variation->name1 == 'box') selected @endif>
                                                                    box</option>
                                                                <option value="pkg"
                                                                    @if ($variation->name1 == 'pkg') selected @endif>
                                                                    pkg</option>
                                                                <option value="tab"
                                                                    @if ($variation->name1 == 'tab') selected @endif>
                                                                    tab</option>

                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control"
                                                                name="price1[]" id="price1" required></td>

                                                        <td><input type="text" class="form-control"
                                                                name="wholesale1[]" id="wholesale1" required></td>
                                                        <td><input type="text" class="form-control"
                                                                name="retail1[]" id="retail1"></td>
                                                        <!-- <td><input type="number" class="form-control" name="quantity1"></td> -->
                                                    </tr>

                                                </tbody>
                                            </table>

                                        </div>
                                    @endforeach








                                </div>
                                <!-- /.card-body -->

                                <div class="mb-3 ml-3 ">
                                    <button type="submit" class="btn btn-danger">Update</button>
                                </div>
                            </form>
                        @endforeach

                    </div>
                </div>

            </div>

        </div>



    </div>

    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.option-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.option-checkbox').not(this).prop('checked', false);
                }
            });
        });
    </script>
    <script>
        function formatQuantity() {
            var quantityInput = document.getElementById('quantity');
            var value = parseFloat(quantityInput.value).toFixed(1);
            quantityInput.value = value;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selectedLocation = document.getElementById('item_location').value;
            var forms = document.querySelectorAll('.item-form');

            forms.forEach(function(form) {
                var warehouseId = form.querySelector('#warehouse_id').value;

                if (selectedLocation === "" || warehouseId == selectedLocation) {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            });

            document.getElementById('item_location').addEventListener('change', function() {
                var selectedLocation = this.value;
                forms.forEach(function(form) {
                    var warehouseId = form.querySelector('#warehouse_id').value;
                    if (selectedLocation === "" || warehouseId == selectedLocation) {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                });
            });
            var event = new Event('change');
            document.getElementById('item_location').dispatchEvent(event);


        });
    </script>

    <script>
        $('[id^="item_kit_unit-"]').on('change', function() {
            let selectedUnit = $(this).val(); // Get the value of the changed element
            let item_name = $(this).closest('tr').find('.result_item_name')
                .val(); // Adjust selector to get item_name
            let result_id = $(this).closest('tr').find('.result_id')
                .val(); // Adjust selector to get item_name
            // console.log(result_id);
            let retail_price = $(this).closest('tr').find(
                '.item_kit_retail_price'); // Get retail price input in the same row



            $.ajax({
                type: 'POST',
                url: "{{ route('unit_search_withID') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    unit: selectedUnit,
                    item_name: item_name,
                    result_id: result_id,

                },
                success: function(data) {
                    console.log(data);
                    retail_price.val(data.retail); // Update the retail price input
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            calculate();
        })
    </script>
    <script>
        $(document).on("click", '#calculate', function(e) {
            e.preventDefault();
            calculate();

        });

        function calculate() {
            let total = 0;
            let totalTax = 0;
            let count = document.querySelectorAll('.item_kit_name').length;
            console.log(count);


            $('#showitem123 tr').each(function(index) {
                let row = $(this);
                let qty = parseInt(row.find('.item_kit_quantity').val()) || 0;
                let price = parseInt(row.find('.item_kit_retail_price').val()) || 0;
                let amount = row.find('.item_kit_amount');

                total += qty * price;
                amount.val(qty * price);

            });
            $("#invoiceyoghtml").val(total);


            calculateTotal();
        }


        function calculateTotal() {
            let invoiceyoghtml = parseFloat($('#invoiceyoghtml').val()) || 0;
            let total_discount = parseFloat($('#total_discount').val()) || 0;
            let total_total = invoiceyoghtml + total_discount;
            $('#total_total').val(total_total);
            $('#retail1').val(total_total);
            $('#wholesale1').val(total_total);
            $('#price1').val(invoiceyoghtml);
        }

        $(document).ready(function() {
            $('#total_discount').on('input', calculateTotal);
            calculateTotal(); // Initial calculation if needed

        });
    </script>
    <script>
        $(document).ready(function() {
            let count = 0;

            function initializeTypeahead() {

                $('#productname').typeahead({
                    source: function(query, process) {
                        var Selectedlocation = $('#location').val();
                        return $.ajax({
                            url: "{{ route('autocomplete.part-code-invoice') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                query: query,
                                location: Selectedlocation,
                            },
                            dataType: 'json',
                            success: function(data) {
                                const formattedData = data.map(function(item) {
                                    const itemName = item.item_name || '';
                                    const description = item.description || '';
                                    const productCode = item.product_code || '';
                                    const expiredDate = item.expired_date || '';

                                    const displayText = itemName +
                                        (description || productCode ? ' (' +
                                            description : '') +
                                        (description && productCode ? ' - ' : '') +
                                        (productCode ? productCode : '') +
                                        (description || productCode ? ')' : '') +
                                        (expiredDate ? ' (' + expiredDate + ' )' :
                                            '');

                                    return {
                                        item_name: itemName,
                                        description: description,
                                        product_code: productCode,
                                        id: item.id,
                                        item_id: item.item_id,
                                        expired_date: expiredDate,
                                        display: displayText
                                    };
                                });

                                process(formattedData);
                            }
                        });
                    },
                    displayText: function(item) {
                        return item.display;
                    },
                    afterSelect: function(item) {
                        $('#result_descriptions-0').val(item.description);
                        $('#result_product_code-0').val(item.product_code);
                        $('#result_product_name-0').val(item.item_name);
                        $('#result_expired_date-0').val(item.expired_date);
                        $('#result_id-0').val(item.id);
                    },
                    autoSelect: true
                });
            }



            function updateItemName(item, row, description, item_id, cuz_name, productname, expired_date) {
                // console.log(item_id);
                var Selectedlocation = $('#location').val();

                var unit = $('#item_kit_unit-0');
                var retail_price = $('#item_kit_retail_price-0');

                if ($("#item_kit_name-0").val() === "") {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('get.part.data-invoice') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            itemname: item,
                            location: Selectedlocation,
                            description: description,
                            // product_code: product_code,
                            item_id: item_id,
                            expired_date: expired_date,

                        },
                        success: function(data) {
                            var item = data['item'];
                            var variation = data['variations'][0];

                            $("#item_kit_name-0").val(productname);
                            $("#result_item_name-0").val(item['item_name']);
                            $("#result_variation_id-0").val(variation['id']);
                            $("#result_id-0").val(variation['id']);
                            $("#item_kit_id-0").val(item['id']);
                            $("#item_kit_description-0").val(variation['descriptions']);
                            $("#warehouse-0").val(item['warehouse_id']);
                            $(unit).on('change', function() {
                                let selectedUnit = unit.val();

                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('unit_search_withName') }}",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        unit: selectedUnit,
                                        item_name: item,
                                        description: description,
                                        // product_code: product_code,
                                        item_id: item_id,
                                        expired_date: expired_date,

                                        // Adjusted to match server-side parameter name
                                    },
                                    success: function(data) {

                                        retail_price.val(data.retail);

                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                    }
                                });

                            });

                            $("#item_kit_quantity-0").val('1');
                            $("#productname").val('');

                            console.log(variation['name1'], variation['name2'], variation[
                                'name3'
                            ]);
                            if (variation['name2'] != null && variation[
                                    'name3'
                                ] != null) {
                                unitdata = [variation['name1'], variation['name2'], variation[
                                    'name3'
                                ]];
                            } else if (variation['name2'] != null && variation[
                                    'name3'
                                ] == null) {
                                unitdata = [variation['name1'], variation['name2']];
                            } else if (variation['name2'] == null && variation[
                                    'name3'
                                ] != null) {
                                unitdata = [variation['name1'], variation[
                                    'name3'
                                ]];


                            } else {
                                unitdata = [variation['name1']];
                            }

                            $.each(unitdata, function(index, variation) {
                                let option = $('<option></option>').val(variation).text(
                                    variation);
                                unit.append(option);
                            });
                            unit.trigger('change');

                            if (parseFloat(variation['reorder_level_stock']) >= parseFloat(variation[
                                    'quantity'])) {
                                alert(variation['quantity'] + " quantity!");
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });

                } else {
                    // if ($("#item_kit_name-0").val() === $("#productname").val()) {
                    //     var existingRow = $("#item_kit_quantity-0");
                    //     var currentQuantity = parseInt(existingRow.val());
                    //     existingRow.val(currentQuantity + 1);
                    //     $("#productname").val('');
                    // } else {
                    // console.log("testing");
                    // console.log(item);
                    // console.log(description);
                    // console.log(item_id);
                    // console.log(Selectedlocation);
                    // console.log(expired_date);

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('get.part.data-invoice') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            itemname: item,
                            location: Selectedlocation,
                            description: description,
                            // product_code: product_code,
                            item_id: item_id,
                            expired_date: expired_date,


                        },
                        success: function(data) {



                            var item = data['item'];
                            var variation = data['variations'][0];
                            if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                                alert(data.quantity + " quantity!");
                            }
                            // addNewRow(data['item']);
                            // $("#productname").val('');

                            data.variations.forEach(function(variation) {
                                addNewRow(data.item, variation, productname);

                            });

                            $("#productname").val('');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                    // }
                }
            }



            initializeTypeahead();



            function addNewRow(item, variation, productname) {
                let cuz_name = $("#type").val();
                let existingRow = $("#showitem123 input.item_kit_name[value='" + productname + "']")
                    .closest(
                        'tr');


                if (existingRow.length > 0) {
                    // Item already exists, update quantity
                    let qtyInput = existingRow.find('.item_kit_quantity');
                    let currentQty = parseInt(qtyInput.val()) || 0;
                    qtyInput.val(currentQty + 1);
                } else {

                    count = $("#showitem123 tr").length;
                    console.log(count);

                    let rowCount = $("#showitem123 tr").length;

                    var retail_price = $("#item_kit_retail_price-" + count);

                    let newRow = '<tr>' +
                        '<td class="text-center">' + (rowCount + 1) + '</td>' +
                        '<td><input type="text" class="form-control item_kit_name typeahead" name="item_kit_name[]" id="item_kit_name-' +
                        count + '" autocomplete="off" value="' + productname +
                        '" readonly><input type="hidden" class="form-control item_kit_id " name="item_kit_id[]" id="item_kit_id-' +
                        count + '" autocomplete="off" value="' + item['id'] +
                        '"><input type="hidden" class="form-control result_item_name " name="result_item_name[]" id="result_item_name-' +
                        count + '" autocomplete="off" value="' + item['item_name'] +
                        '"><input type="hidden" class="form-control result_id " name="result_id[]" id="result_id-' +
                        count + '" autocomplete="off" value="' + variation['id'] +
                        '"><input type="hidden" class="form-control result_variation_id " name="result_variation_id[]" id="result_variation_id-' +
                        count + '" autocomplete="off" value="' + variation['id'] + '"></td>' +
                        '<td><input type="text" readonly class="form-control item_kit_description typeahead" name="item_kit_description[]"  id="item_kit_description-' +
                        count + '" autocomplete="off" value="' + (variation['descriptions'] ? variation[
                                'descriptions'] :
                            '') + '"></td>' +
                        '<td><input type="text" class="form-control item_kit_quantity" name="item_kit_quantity[]" id="item_kit_quantity-' +
                        count +
                        '" autocomplete="off" value="1"></td>' +
                        '<td><select class="form-control item_kit_unit "  name="item_kit_unit[]" id="item_kit_unit-' +
                        count + '" autocomplete ="off" required><option selected value="' + variation["name1"] +
                        '">' + (
                            variation[
                                "name1"]) +
                        '</option>' + (variation["name2"] ? '<option value="' + variation["name2"] + '">' +
                            variation["name2"] +
                            '</option>' : '') + // Show only if name2 exists
                        (variation["name3"] ? '<option value="' + variation["name3"] + '">' + variation["name3"] +
                            '</option>' :
                            '') + // Show only if name3 exists+
                        '</option></select ></td>' +
                        '<td><input type="text" class="form-control item_kit_retail_price" name="item_kit_retail_price[]" id="item_kit_retail_price-' +
                        count + '" autocomplete="off" ></td>' +
                        '<td style="display: none;"><input type="text" class="form-control item_kit_wholesale_price" name="item_kit_wholesale_price[]" id="item_kit_wholesale_price-' +
                        count + '" autocomplete="off" value="' + (variation['wholesale_price'] ?? 0) +
                        '"></td>' +

                        '<td style="display: none;"><input  type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                        count + '" autocomplete="off" value="' + item['warehouse_id'] + '"></td>' +

                        '<td style="text-align:center"><input type="text" class="form-control item_kit_amount" name="item_kit_amount[]" id="item_kit_amount-' +
                        count + '" readonly></td>' +
                        '<td class="text-center"><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
                        '</tr>';

                    $(document).on('change', '#item_kit_unit-' + count, function() {
                        var selectedUnit = $(this).val();
                        // $.ajax({
                        //     type: 'POST',
                        //     url: "{{ route('unit_search_withName') }}",
                        //     data: {
                        //         _token: "{{ csrf_token() }}",
                        //         unit: selectedUnit,
                        //         item_name: item['item_name'],
                        //         // Adjusted to match server-side parameter name
                        //     },
                        //     success: function(data) {

                        //         $("#item_kit_retail_price-" + count).val(data.retail);


                        //     },
                        //     error: function(xhr, status, error) {
                        //         console.error(xhr.responseText);
                        //     }
                        // });

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('unit_search_withName') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                unit: selectedUnit,
                                item_name: item['item_name'],
                                description: variation['descriptions'],
                                item_id: variation['id'],

                                // product_code: variation['product_code'],
                                // Adjusted to match server-side parameter name
                            },
                            success: function(data) {

                                $("#item_kit_retail_price-" + count).val(data.retail);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });

                    });



                    $("#showitem123").append(newRow);

                }
                $("#item_kit_unit-" + count).trigger('change');
            }


            $(document).on('click', '.remove_item_btn', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                $('#showitem123 tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
                initializeTypeahead();
            });

            $(document).on('click', '.typeahead .dropdown-item', function(e) {
                e.preventDefault();

                if ($("#customer").val()) {} else {
                    const row = $(this).closest('tr');

                    const itemname = $('#result_product_name-0').val();
                    const productname = $('#productname').val();
                    const description = $('#result_descriptions-0').val();
                    // const product_code = $('#result_product_code-0').val();
                    const item_id = $('#result_id-0').val();
                    const expired_date = $('#result_expired_date-0').val();
                    let cuz_name = $("#type").val();
                    updateItemName(itemname, row, description, item_id, cuz_name, productname,
                        expired_date);
                    $('#productname').val('');
                }
            });


            initializeTypeahead(count);
            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                calculate();

                calculateTotal();
            });


            function calculateTotal() {
                let invoiceyoghtml = parseFloat($('#invoiceyoghtml').val()) || 0;
                let total_discount = parseFloat($('#total_discount').val()) || 0;
                let total_total = invoiceyoghtml + total_discount;
                $('#total_total').val(total_total);
                $('#retail1').val(total_total);
                $('#wholesale1').val(total_total);
                $('#price1').val(invoiceyoghtml);
            }

            $(document).ready(function() {
                $('#total_discount').on('input', calculateTotal);
                calculateTotal(); // Initial calculation if needed
            });




        });
    </script>
    <script>
        $(document).ready(function() {
            $('#warehouse_id').change(function() {
                var selectedWarehouseId = $(this).val(); // Get the selected value from warehouse_id

                if (selectedWarehouseId) {
                    // Enable the location dropdown
                    $('#location').prop('disabled', false);

                    // Loop through location options and only enable the selected warehouse option
                    $('#location option').each(function() {
                        if ($(this).val() == selectedWarehouseId) {
                            $(this).prop('disabled', false); // Enable the matched option
                            $(this).prop('selected', true); // Set it as selected
                        } else {
                            $(this).prop('disabled', true); // Disable other options
                        }
                    });
                } else {
                    // If no warehouse selected, disable the location dropdown again
                    $('#location').prop('disabled', true).val('');
                }
            });
        });
    </script>
