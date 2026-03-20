@include('layouts.header')
<link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
<script src="{{ asset('backend/js/jquery191.js') }}"></script>
<script src="{{ asset('backend/js/typehead401.js') }}"></script>

<script src="{{ asset('backend/js/moment2103.js') }}"></script>


<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                {{-- <li>
                    <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Logout</button>
                </form>
                </li> --}}
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
                                <h1>Product Register</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Product Register
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

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
            <div class="content-body">
                <div class="container-fluid justify-content-center d-flex">
                    <div class="card card-default col-md-12" >
                        {{-- <div class="card-header">
                            <h3 class="card-title">Item Register</h3>
                        </div> --}}
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ url('item_store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row d-none">

                                    <div class="frmSearch col-sm-6 d-none">
                                        <input type="checkbox" id="show" class=""> <span>Already Registered?</span>
                                        <div id="additional-elements" style="display: none;" class="mt-2">
                                            <span style="font-weight: bolder;">
                                                <label for="cst"
                                                    class="caption">{{ trans('Search With Product Name') }}</label>
                                            </span>
                                            <input type="text" id="customer" name="customer"
                                                class="form-control round" autocomplete="off">
                                            <button type="submit" class="my-3 btn btn-primary"
                                                id="customer_search">Add</button>
                                            <div id="customer-box-result"></div>
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

                                    @if (in_array('Item Kits Register', $userPermissions) || auth()->user()->is_admin == '1')
                                        <input type="hidden" name="status" id="status">
                                        <div class="frmSearch col-sm-6 d-none" id="item_kit_div" >
                                            <input type="checkbox"  name="item_kit" id="item_kit_show" class="">
                                            <span>Items
                                                Kit</span>

                                        </div>
                                    @endif
                                </div>

                                <div class="row mt-2">


                                    <div class="form-group col-md-4">
                                        <label for="item_name">Product Name <span class="text-danger">
                                                *</span></label>
                                        <input type="text" class="form-control" id="item_name" name="item_name"
                                            placeholder="Enter Item Name" value="{{ old('item_name') }}" required>
                                    </div>
                                    <input type="hidden" name="parent_id" id="parent_id" value="0">

                                    <div class="form-group col-md-4">
                                        <label for="barcode">Brand Name</label>
                                        <select class="form-control" id="brand" name="brand"
                                          >
                                          <option value=""selected disabled>Choose Brand</option>
                                          @foreach ($brands as $brand)
                                              <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                    @error('barcode')
                                        <p>{{ $message }}</p>
                                    @enderror

                                    <div class="form-group col-md-4">
                                        <label for="descriptions">Product Descriptions</label>
                                        <input type="text" class="form-control" id="descriptions"
                                            placeholder="Enter Item Descriptions" name="descriptions" value="0">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="expired_date">Image</label>
                                        <input type="file" class="form-control" id="image"
                                            name="image" >
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="category">Product Type</label>
                                        <input type="text" class="form-control" id="product_type" name="product_type"
                                            value="{{ old('product_type') }}" placeholder="Enter Product Type">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="category">Product Category</label>
                                        <input type="text" class="form-control" id="pruduct_category" name="product_category" value="{{ old('pruduct_category') }}" placeholder="Enter Product category">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="warehouse_id">Location<span class="text-danger">*</span></label>
                                        <input type="hidden" id="warehouse_id_from" name="warehouse_id_from">
                                        <select name="warehouse_id" id="warehouse_id" class="form-control" required>
                                            <option value="" selected disabled>Select Warehouse</option>
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
                                </div>


                                {{-- <div id="item_kit_add" style="display: none;">
                                    <div class="row">
                                        <div class="frmSearch col-md-3">
                                            <div class="frmSearch col-sm-12">
                                                <span style="font-weight:bolder">
                                                    <label for="location"
                                                        class="caption">{{ trans('Location') }}&nbsp;</label>
                                                </span>
                                                <select name="location" id="location"
                                                    class="mb-4 form-control location" required>
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
                                                <th width="18%" class="text-center">{{ trans('Item Name') }}</th>
                                                <th width="23%" class="text-center">{{ trans('Descriptions') }}
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
                                            <tr>
                                                <td class="text-center" id="count">1</td>
                                                <td><input type="text" class="form-control item_kit_name"
                                                        name="item_kit_name[]" id="item_kit_name-0"
                                                        autocomplete="off" readonly>
                                                    <input type="hidden" class="form-control item_kit_id"
                                                        name="item_kit_id[]" id="item_kit_id-0" autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_item_name typeahead"
                                                        name="result_item_name[]"
                                                        value="{{ old('result_item_name') }}" id='result_item_name-0'
                                                        autocomplete="off">

                                                    <input type="hidden" class="form-control result_id typeahead"
                                                        name="result_id[]" value="{{ old('result_id') }}"
                                                        id='result_id-0' autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_variation_id typeahead"
                                                        name="result_variation_id[]"
                                                        value="{{ old('result_variation_id') }}"
                                                        id='result_variation_id-0' autocomplete="off">
                                                </td>
                                                <td><input type="text" class="form-control item_kit_description"
                                                        name="item_kit_description[]" id="item_kit_description-0"
                                                        autocomplete="off" readonly></td>
                                                <td><input type="text" class="form-control item_kit_quantity"
                                                        name="item_kit_quantity[]" id="item_kit_quantity-0"
                                                        value="1" autocomplete="off"></td>
                                                <td><select class="form-control item_kit_unit" name="item_kit_unit[]"
                                                        id="item_kit_unit-0">
                                                </td>
                                                <td><input type="text" class="form-control item_kit_retail_price"
                                                        name="item_kit_retail_price[]" id="item_kit_retail_price-0"
                                                        autocomplete="off">
                                                </td>
                                                <td style="display: none;"><input type="text"
                                                        class="form-control item_kit_wholesale_price"
                                                        name="item_kit_wholesale_price[]"
                                                        id="item_kit_wholesale_price-0" autocomplete="off">
                                                </td>
                                                <td style="display: none;"><input type="text"
                                                        class="form-control warehouse" name="warehouse[]"
                                                        id="warehouse-0" autocomplete="off"></td>
                                                <td style="text-align:center"><input type="text"
                                                        class="form-control item_kit_amount" name="item_kit_amount[]"
                                                        id="item_kit_amount-0" readonly></td>
                                                <td style="width: 10%;" class="text-center"><button type="button"
                                                        class="btn btn-danger remove_item_btn">Remove</button></td>
                                            </tr>
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
                                                    <button type="button" class="btn btn-success" id="addproduct"
                                                        style="margin-top:20px;margin-bottom:20px;display:none;">
                                                        <i class="fa fa-plus-square"></i>
                                                        {{ trans('Add row') }}
                                                    </button>
                                                    <button type="button" class="btn btn-primary" id="calculate">
                                                        Calculate
                                                    </button>


                                                    <a href="{{ URL('items') }}" target="_blank" id="item_search">
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
                                                        id="total_discount">
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
                                </div> --}}
                                <hr>
                                <div class="mt-2" style="font-size: 17.6px;"><span>Variations</span></div>


                                <div id="rowsContainer">



                                    <div class="mt-3 row mb-2">
                                        <div class="form-group col-md-4">
                                            <label for="variations_descriptions">LOT NO.<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="lot_no"
                                                name="lot_no[]" placeholder="Enter LOT NO."
                                                required>
                                        </div><div class="form-group col-md-4">
                                            <label for="expired_date">Warehouse In Date</label>
                                            <input type="date" class="form-control" id="warehousein_date"
                                                name="warehousein_date[]" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="expired_date">MUF Date</label>
                                            <input type="date" class="form-control" id="muf_date"
                                                name="muf_date[]" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="expired_date">Expired Date</label>
                                            <input type="date" class="form-control" id="expired_date"
                                                name="expired_date[]" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="product_code">Estd. Test</label>
                                            <input type="text" class="form-control" id="estd_test"
                                                name="estd_test[]" placeholder="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="product_code">Country Of Origin</label>
                                            <input type="text" class="form-control" id="country_of_origin"
                                                name="country_of_origin[]" placeholder="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="product_code">Distributor/Supplier</label>
                                            <input type="text" class="form-control" id="distributor"
                                                name="distributor[]" placeholder="">
                                        </div>




                                        <div class="form-group col-md-4">
                                            <label for="quantity">
                                                Quantity <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" required class="form-control" name="quantity[]"
                                                id="quantity" value="0" placeholder="Enter Quantity" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="reorder_level_stock">Reorder Level Stock</label>
                                            <input type="number" class="form-control" id="reorder_level_stock"
                                                name="reorder_level_stock[]" value="0"
                                                placeholder="Enter Reorder Level Stock">
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
                                                <!-- <th>Quantity</th> -->
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <input type="number" class="form-control" name="unit1[]"
                                                            value="1" readonly>
                                                    </td>
                                                    <td>
                                                        <select name="name1[]" id="name1" class="form-control"
                                                            required>

                                                            <option value="btl">btl</option>
                                                            <option value="amp">amp</option>
                                                            <option value="tube">tube</option>
                                                            <option value="strip">strip</option>
                                                            <option value="cap">cap</option>
                                                            <option value="pcs">pcs</option>
                                                            <option value="sac">sac</option>
                                                            <option value="box">box</option>
                                                            <option value="pkg">pkg</option>
                                                            <option value="tab">tab</option>

                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control" name="price1[]"
                                                            id="price1" value="0" step="any" required>
                                                    </td>

                                                    <td><input type="number" class="form-control"
                                                            name="wholesale1[]" id="wholesale1" value="0"
                                                            step="any" required></td>
                                                    <td><input type="number" class="form-control" name="retail1[]"
                                                            id="retail1" value="0" step="any"></td>
                                                    <!-- <td><input type="number" class="form-control" name="quantity1"></td> -->
                                                </tr>
                                                <tr id="row2">
                                                    <td>2</td>
                                                    <td><input type="number" class="form-control" name="unit2[]"
                                                            id="unit2"></td>
                                                    <td> <select name="name2[]" id="name2" class="form-control">
                                                            <option selected disabled>Choose Unit</option>
                                                            <option value="btl">btl</option>
                                                            <option value="amp">amp</option>
                                                            <option value="tube">tube</option>
                                                            <option value="strip">strip</option>
                                                            <option value="cap">cap</option>
                                                            <option value="pcs">pcs</option>
                                                            <option value="sac">sac</option>
                                                            <option value="box">box</option>
                                                            <option value="pkg">pkg</option>
                                                            <option value="tab">tab</option>

                                                        </select></td>
                                                    <td><input type="number" class="form-control" name="price2[]"
                                                            id="price2" step="any"></td>
                                                    <td><input type="number" class="form-control"
                                                            name="wholesale2[]" id="wholesale2" step="any"></td>
                                                    <td><input type="number" class="form-control" name="retail2[]"
                                                            id="retail2" step="any"></td>

                                                    <!-- <td><input type="number" class="form-control" name="quantity2"></td> -->
                                                </tr>
                                                </tr>
                                                <tr id="row3">
                                                    <td>3</td>
                                                    <td><input type="number" class="form-control" name="unit3[]"
                                                            id="unit3"></td>
                                                    <td> <select name="name3[]" id="name3" class="form-control">

                                                            <option selected disabled>Choose Unit</option>
                                                            <option value="btl">btl</option>
                                                            <option value="amp">amp</option>
                                                            <option value="tube">tube</option>
                                                            <option value="strip">strip</option>
                                                            <option value="cap">cap</option>
                                                            <option value="pcs">pcs</option>
                                                            <option value="sac">sac</option>
                                                            <option value="box">box</option>
                                                            <option value="pkg">pkg</option>
                                                            <option value="tab">tab</option>

                                                        </select></td>
                                                    <td><input type="number" class="form-control" name="price3[]"
                                                            id="price3" step="any"></td>
                                                    <td><input type="number" class="form-control"
                                                            name="wholesale3[]" id="wholesale3" step="any"></td>
                                                    <td><input type="number" class="form-control" name="retail3[]"
                                                            id="retail3" step="any"></td>

                                                    <!-- <td><input type="number" class="form-control" name="quantity3"></td> -->
                                                </tr>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>

                                    <button type="button" class="btn btn-success mt-3 addRow main-add-row"><i
                                            class="fa-solid fa-plus"></i></button>

                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="mb-3 ml-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>



    </div>

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


    <script>
        $(document).ready(function() {
            $('.option-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.option-checkbox').not(this).prop('checked', false);
                }
            });


            var path = "{{ url('item_search_for_add') }}";
            $('#customer').typeahead({
                source: function(query, process) {
                    return $.get(path, {
                        query: query
                    }, function(data) {
                        // console.log("Data received:", data); // Log the received data
                        return process(data);
                    })
                }
            });
            $(document).on('click', '#customer_search', function(e) {
                e.preventDefault();
                let serialNumber = $("#customer").val();


                $.ajax({
                    type: 'POST',
                    url: "{{ route('item_data_search_fill') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        model: serialNumber // Adjusted to match server-side parameter name
                    },
                    success: function(data) {
                        // console.log(data);
                        $("#item_name").val(data['item']['item_name']);
                        $("#brand").val(data['item']['brand']);

                        $("#descriptions").val(data['item']['descriptions']);
                        $("#lot_no").val(data['variation']['lot_no']);
                        $("#warehousein_date").val(data['variation']['arrival_date']);
                        $("#muf_date").val(data['variation']['muf_date']);
                        $("#expired_date").val(data['variation']['expired_date']);
                        $("#estd_test").val(data['variation']['estd_test']);
                        $("#country_of_origin").val(data['variation']['country_of_origin']);
                        $("#distributor").val(data['variation']['distributor']);


                        $("#product_category").val(data['item']['product_category']);
                        $("#product_type").val(data['item']['product_type']);

                        $("#item_unit").val(data['item']['item_unit']);

                        $("#price").val(data['item']['price']);
                        $("#reorder_level_stock").val(data['variation']['reorder_level_stock']);

                        $("#other").val(data['item']['other']);
                        // $("#retail_price").val(data['item']['retail_price']);
                        // $("#wholesale_price").val(data['item']['wholesale_price']);
                        $("#price1").val(data['variation']['price1']);
                        $("#price2").val(data['variation']['price2']);
                        $("#price3").val(data['variation']['price3']);
                        $("#name1").val(data['variation']['name1']);
                        // $("#name2").val(data['variation']['name2']);

                        if (!(data['variation']['name2'])) {


                            $("#name2").val('Choose Unit').prop('disabled', false);
                        } else {
                            // Enable the dropdown and set the value
                            $("#name2").prop('disabled', false);
                            $("#name2").val(data['variation']['name2']);
                        }


                        if (!(data['variation']['name3'])) {


                            $("#name3").val('Choose Unit').prop('disabled', false);
                        } else {
                            // Enable the dropdown and set the value
                            $("#name3").prop('disabled', false);
                            $("#name3").val(data['variation']['name3']);
                        }
                        $("#unit1").val(data['variation']['unit1']);
                        $("#unit2").val(data['variation']['unit2']);
                        $("#unit3").val(data['variation']['unit3']);
                        $("#retail1").val(data['variation']['retail1']);
                        $("#retail2").val(data['variation']['retail2']);
                        $("#retail3").val(data['variation']['retail3']);
                        $("#wholesale1").val(data['variation']['wholesale1']);
                        $("#wholesale2").val(data['variation']['wholesale2']);
                        $("#wholesale3").val(data['variation']['wholesale3']);
                        $("#parent_id").val(data['parent_id']);
                        console.log(document.getElementById('parent_id').value);
                        // $("#warehouse_id").val(data['warehouse']['id']);

                        // Find the option with the selected value and hide it
                        data['all_items'].forEach(function(item) {
                            // Hide the corresponding warehouse option for each item location
                            $("#warehouse_id option[value='" + item.warehouse_id + "']")
                                .hide();
                        });
                        $('#customer').val('');





                        // Adjusted to match server-side data
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Listen for changes in the market dropdown
            $('#market').on('change', function() {
                var marketValue = $(this).val(); // Get the selected value
                if (marketValue === 'Service') {
                    // Set the value of quantity to 0 and make it read-only
                    $('#quantity').val(0).prop('readonly', true);
                    $('#row2, #row3').hide();
                } else {
                    // Enable the quantity input for 'Stock' and remove readonly
                    $('#quantity').val('').prop('readonly', false);
                    $('#row2, #row3').show();
                }
            });
        });
    </script>
    <script>
        document.getElementById("show").addEventListener("change", function() {
            var additionalElements = document.getElementById("additional-elements");
            if (this.checked) {
                additionalElements.style.display = "block";
            } else {
                additionalElements.style.display = "none";
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#item_kit_show').change(function() {
                if ($(this).is(':checked')) {
                    $('#item_kit_add').show();
                    $('#row2, #row3').hide();
                    $('#name1').val('ခု');
                    $('#status').val('item_kit');
                    $('#retail_price_div').hide();
                    $('#buy_price_div').hide();
                    $('#retail_price_div input, #buy_price_div input').val('');
                } else {
                    $('#item_kit_add').hide();
                    $('#row2, #row3').show();
                    $('#status').val('');
                    $('#retail_price_div').show();
                    $('#buy_price_div').show();
                    $('#item_kit_add input').val('');
                }
            });
            $('#item_kit_show').trigger('change');
        });


        // $(document).ready(function() {
        //     let count = 0;

        //     function initializeTypeahead() {

        //         $('#productname').typeahead({
        //             source: function(query, process) {
        //                 var Selectedlocation = $('#location').val();
        //                 return $.ajax({
        //                     url: "{{ route('autocomplete.part-code-invoice') }}",
        //                     method: 'POST',
        //                     data: {
        //                         _token: "{{ csrf_token() }}",
        //                         query: query,
        //                         location: Selectedlocation,
        //                     },
        //                     dataType: 'json',
        //                     success: function(data) {
        //                         const formattedData = data.map(function(item) {
        //                             const itemName = item.item_name || '';
        //                             const description = item.description || '';
        //                             const productCode = item.product_code || '';
        //                             const expiredDate = item.expired_date || '';

        //                             const displayText = itemName +
        //                                 (description || productCode ? ' (' +
        //                                     description : '') +
        //                                 (description && productCode ? ' - ' : '') +
        //                                 (productCode ? productCode : '') +
        //                                 (description || productCode ? ')' : '') +
        //                                 (expiredDate ? ' (' + expiredDate + ' )' :
        //                                     '');

        //                             return {
        //                                 item_name: itemName,
        //                                 description: description,
        //                                 product_code: productCode,
        //                                 id: item.id,
        //                                 item_id: item.item_id,
        //                                 expired_date: expiredDate,
        //                                 display: displayText
        //                             };
        //                         });

        //                         process(formattedData);
        //                     }
        //                 });
        //             },
        //             displayText: function(item) {
        //                 return item.display;
        //             },
        //             afterSelect: function(item) {
        //                 $('#result_descriptions-0').val(item.description);
        //                 $('#result_product_code-0').val(item.product_code);
        //                 $('#result_product_name-0').val(item.item_name);
        //                 $('#result_expired_date-0').val(item.expired_date);
        //                 $('#result_id-0').val(item.id);
        //             },
        //             autoSelect: true
        //         });
        //     }



        //     function updateItemName(item, row, description, item_id, cuz_name, productname, expired_date) {
        //         var Selectedlocation = $('#location').val();

        //         var unit = $('#item_kit_unit-0');
        //         var retail_price = $('#item_kit_retail_price-0');

        //         if ($("#item_kit_name-0").val() === "") {

        //             $.ajax({
        //                 type: 'POST',
        //                 url: "{{ route('get.part.data-invoice') }}",
        //                 data: {
        //                     _token: "{{ csrf_token() }}",
        //                     itemname: item,
        //                     location: Selectedlocation,
        //                     description: description,
        //                     // product_code: product_code,
        //                     item_id: item_id,
        //                     expired_date: expired_date,

        //                 },
        //                 success: function(data) {
        //                     var item = data['item'];
        //                     var variation = data['variations'][0];

        //                     $("#item_kit_name-0").val(productname);
        //                     $("#result_item_name-0").val(item['item_name']);
        //                     $("#result_variation_id-0").val(variation['id']);
        //                     $("#item_kit_id-0").val(item['id']);
        //                     $("#item_kit_description-0").val(variation['descriptions']);
        //                     $("#warehouse-0").val(item['warehouse_id']);
        //                     $(unit).on('change', function() {
        //                         let selectedUnit = unit.val();

        //                         $.ajax({
        //                             type: 'POST',
        //                             url: "{{ route('unit_search_withName') }}",
        //                             data: {
        //                                 _token: "{{ csrf_token() }}",
        //                                 unit: selectedUnit,
        //                                 item_name: item,
        //                                 description: description,
        //                                 // product_code: product_code,
        //                                 item_id: item_id,
        //                                 expired_date: expired_date,

        //                                 // Adjusted to match server-side parameter name
        //                             },
        //                             success: function(data) {

        //                                 retail_price.val(data.retail);

        //                             },
        //                             error: function(xhr, status, error) {
        //                                 console.error(xhr.responseText);
        //                             }
        //                         });

        //                     });

        //                     $("#item_kit_quantity-0").val('1');
        //                     $("#productname").val('');

        //                     console.log(variation['name1'], variation['name2'], variation[
        //                         'name3'
        //                     ]);
        //                     if (variation['name2'] != null && variation[
        //                             'name3'
        //                         ] != null) {
        //                         unitdata = [variation['name1'], variation['name2'], variation[
        //                             'name3'
        //                         ]];
        //                     } else if (variation['name2'] != null && variation[
        //                             'name3'
        //                         ] == null) {
        //                         unitdata = [variation['name1'], variation['name2']];
        //                     } else if (variation['name2'] == null && variation[
        //                             'name3'
        //                         ] != null) {
        //                         unitdata = [variation['name1'], variation[
        //                             'name3'
        //                         ]];


        //                     } else {
        //                         unitdata = [variation['name1']];
        //                     }

        //                     $.each(unitdata, function(index, variation) {
        //                         let option = $('<option></option>').val(variation).text(
        //                             variation);
        //                         unit.append(option);
        //                     });
        //                     unit.trigger('change');

        //                     if (parseFloat(variation['reorder_level_stock']) >= parseFloat(variation[
        //                             'quantity'])) {
        //                         alert(variation['quantity'] + " quantity!");
        //                     }

        //                 },
        //                 error: function(xhr, status, error) {
        //                     console.error(xhr.responseText);
        //                 }
        //             });

        //         } else {
        //             console.log("testing1");
        //             if ($("#item_kit_name-0").val() === $("#productname").val()) {
        //                 var existingRow = $("#item_kit_quantity-0");
        //                 var currentQuantity = parseInt(existingRow.val());
        //                 existingRow.val(currentQuantity + 1);
        //                 $("#productname").val('');
        //             } else {

        //                 $.ajax({
        //                     type: 'POST',
        //                     url: "{{ route('get.part.data-invoice') }}",
        //                     data: {
        //                         _token: "{{ csrf_token() }}",
        //                         itemname: item,
        //                         location: Selectedlocation,
        //                         description: description,
        //                         // product_code: product_code,
        //                         item_id: item_id,
        //                         expired_date: expired_date,

        //                     },
        //                     success: function(data) {
        //                         var item = data['item'];
        //                         var variation = data['variations'][0];
        //                         if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
        //                             alert(data.quantity + " quantity!");
        //                         }
        //                         console.log("testing2");
        //                         console.log(data);
        //                         // addNewRow(data['item']);
        //                         // $("#productname").val('');
        //                         data.variations.forEach(function(variation) {
        //                             addNewRow(data.item, variation, productname);
        //                         });

        //                         $("#productname").val('');
        //                     },
        //                     error: function(xhr, status, error) {
        //                         console.error(xhr.responseText);
        //                     }
        //                 });
        //             }
        //         }
        //     }

        //     // function updateItemName(item_name, row, description, product_code, cuz_name, productname) {
        //     //     var $location = $('#location');
        //     //     var $itemName0 = $("#item_name-0");
        //     //     var $item_id = $("#item_id-0");
        //     //     var $variation_id = $("#result_id-0");
        //     //     var $result_item_name = $("#result_item_name-0");
        //     //     var $description0 = $("#description-0");
        //     //     var $expDate0 = $("#exp_date-0");
        //     //     var $barcode0 = $("#barcode-0");
        //     //     var $price0 = $("#price-0");
        //     //     var $itemUnit0 = $("#item_unit-0");
        //     //     var $retailPrice0 = $("#retail_price-0");
        //     //     var $buyPrice0 = $("#buy_price-0");
        //     //     var $warehouse0 = $("#warehouse-0");
        //     //     var $productname = $("#productname");
        //     //     var $productName0 = $("#result_product_name-0");
        //     //     var $barcode = $("#barcode");
        //     //     var $amount0 = $("#amount-0");
        //     //     var selectedLocation = $location.val();
        //     //     var cuzName = $("#type").val();

        //     //     function handleSuccess(data) {
        //     //         var item = data['item'];
        //     //         var variation = data['variations'][0];

        //     //         $result_item_name.val(item['item_name']);
        //     //         $itemName0.val(productname ? productname : item.item_name + ' (' + (variation.descriptions ||
        //     //                 'No description') + ' - ' +
        //     //             (variation.product_code || 'No code') + ')');
        //     //         // $itemName0.val(productname);
        //     //         $item_id.val(item['id']);
        //     //         $variation_id.val(variation['id']);
        //     //         $warehouse0.val(item['warehouse_id']);
        //     //         $itemUnit0.val(item['item_unit']);
        //     //         $description0.val(variation['descriptions']);
        //     //         $expDate0.val(variation['expired_date']);
        //     //         $barcode0.val(variation['variations_barcode']);
        //     //         $price0.val(variation['wholesale_price']);
        //     //         $retailPrice0.val(variation['retail_price']);
        //     //         $buyPrice0.val(variation['buy_price']);
        //     //         $productname.val('');
        //     //         $barcode.val('');
        //     //         if (parseFloat(variation.reorder_level_stock) >= parseFloat(variation.quantity)) {
        //     //             alert(variation.quantity + " quantity!");
        //     //         }
        //     //     }

        //     //     function handleError(xhr, status, error) {
        //     //         console.error(xhr.responseText);
        //     //     }

        //     //     if ($itemName0.val() === "") {


        //     //         var $barcodeInput = $('.barcode-input');
        //     //         var item_barcode = $barcodeInput.val();

        //     //         if (item_barcode.length > 1) {
        //     //             $.ajax({
        //     //                 type: 'POST',
        //     //                 url: "{{ route('get.barcode.data-invoice') }}",
        //     //                 data: {
        //     //                     _token: "{{ csrf_token() }}",
        //     //                     barcode: item_name,
        //     //                     location: selectedLocation,

        //     //                 },
        //     //                 success: handleSuccess,
        //     //                 error: handleError
        //     //             });
        //     //             // console.log(item_name);

        //     //         } else {
        //     //             $.ajax({
        //     //                 type: 'POST',
        //     //                 url: "{{ route('get.part.data-invoice') }}",
        //     //                 data: {
        //     //                     _token: "{{ csrf_token() }}",
        //     //                     itemname: item_name,
        //     //                     location: selectedLocation,
        //     //                     description: description,
        //     //                     product_code: product_code
        //     //                 },
        //     //                 success: handleSuccess,
        //     //                 error: handleError
        //     //             });

        //     //         }


        //     //     } else {
        //     //         if ($itemName0.val() === $productname.val() || $barcode0.val() === $barcode.val()) {
        //     //             console.log($productname.val());
        //     //             console.log($itemName0.val());
        //     //             var currentQuantity = parseInt($amount0.val());
        //     //             $amount0.val(currentQuantity + 1);
        //     //             $barcode.val('');
        //     //             $productname.val('');

        //     //         } else {


        //     //             $.ajax({
        //     //                 type: 'POST',
        //     //                 url: "{{ route('get.part.data-invoice') }}",
        //     //                 data: {
        //     //                     _token: "{{ csrf_token() }}",
        //     //                     itemname: item_name,
        //     //                     location: selectedLocation,
        //     //                     description: description,
        //     //                     product_code: product_code
        //     //                 },
        //     //                 success: function(data) {
        //     //                     if (parseFloat(data.reorder_level_stock) >= parseFloat(data
        //     //                             .quantity)) {
        //     //                         alert(data.quantity + " quantity!");
        //     //                     }
        //     //                     data.variations.forEach(function(variation) {
        //     //                         addNewRow(data.item, variation, productname);
        //     //                     });

        //     //                     $barcode.val('');
        //     //                     $productname.val('');
        //     //                 },
        //     //                 error: handleError
        //     //             });


        //     //         }
        //     //     }
        //     // }


        //     initializeTypeahead();


        //     function addNewRow(item, variation, productname) {
        //         let cuz_name = $("#type").val();
        //         let existingRow = $("#showitem123 input.item_kit_name[value='" + productname + "']")
        //             .closest(
        //                 'tr');


        //         if (existingRow.length > 0) {
        //             // Item already exists, update quantity
        //             let qtyInput = existingRow.find('.item_kit_quantity');
        //             let currentQty = parseInt(qtyInput.val()) || 0;
        //             qtyInput.val(currentQty + 1);
        //         } else {

        //             count++;
        //             let rowCount = $("#showitem123 tr").length;

        //             var retail_price = $("#item_kit_retail_price-" + count);

        //             let newRow = '<tr>' +
        //                 '<td class="text-center">' + (rowCount + 1) + '</td>' +
        //                 '<td><input type="text" class="form-control item_kit_name typeahead" name="item_kit_name[]" id="item_kit_name-' +
        //                 count + '" autocomplete="off" value="' + productname +
        //                 '" readonly><input type="hidden" class="form-control item_kit_id " name="item_kit_id[]" id="item_kit_id-' +
        //                 count + '" autocomplete="off" value="' + item['id'] +
        //                 '"><input type="hidden" class="form-control result_item_name " name="result_item_name[]" id="result_item_name-' +
        //                 count + '" autocomplete="off" value="' + item['item_name'] +
        //                 '"><input type="hidden" class="form-control result_id " name="result_id[]" id="result_id-' +
        //                 count + '" autocomplete="off" value="' + variation['id'] +
        //                 '"> <input type="hidden" class="form-control result_variation_id " name="result_variation_id[]" id="result_variation_id-' +
        //                 count + '" autocomplete="off" value="' + variation['id'] + '"></td>' +
        //                 '<td><input type="text" readonly class="form-control item_kit_description typeahead" name="item_kit_description[]"  id="item_kit_description-' +
        //                 count + '" autocomplete="off" value="' + (variation['descriptions'] ? variation[
        //                         'descriptions'] :
        //                     '') + '"></td>' +
        //                 '<td><input type="text" class="form-control item_kit_quantity" name="item_kit_quantity[]" id="item_kit_quantity-' +
        //                 count +
        //                 '" autocomplete="off" value="1"></td>' +
        //                 '<td><select class="form-control item_kit_unit "  name="item_kit_unit[]" id="item_kit_unit-' +
        //                 count + '" autocomplete ="off" required><option selected value="' + variation["name1"] +
        //                 '">' + (
        //                     variation[
        //                         "name1"]) +
        //                 '</option>' + (variation["name2"] ? '<option value="' + variation["name2"] + '">' +
        //                     variation["name2"] +
        //                     '</option>' : '') + // Show only if name2 exists
        //                 (variation["name3"] ? '<option value="' + variation["name3"] + '">' + variation["name3"] +
        //                     '</option>' :
        //                     '') + // Show only if name3 exists+
        //                 '</option></select ></td>' +
        //                 '<td><input type="text" class="form-control item_kit_retail_price" name="item_kit_retail_price[]" id="item_kit_retail_price-' +
        //                 count + '" autocomplete="off" ></td>' +
        //                 '<td style="display: none;"><input type="text" class="form-control item_kit_wholesale_price" name="item_kit_wholesale_price[]" id="item_kit_wholesale_price-' +
        //                 count + '" autocomplete="off" value="' + (variation['wholesale_price'] ?? 0) +
        //                 '"></td>' +

        //                 '<td style="display: none;"><input  type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
        //                 count + '" autocomplete="off" value="' + item['warehouse_id'] + '"></td>' +

        //                 '<td style="text-align:center"><input type="text" class="form-control item_kit_amount" name="item_kit_amount[]" id="item_kit_amount-' +
        //                 count + '" readonly></td>' +
        //                 '<td class="text-center"><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
        //                 '</tr>';

        //             $(document).on('change', '#item_kit_unit-' + count, function() {
        //                 var selectedUnit = $(this).val();
        //                 // $.ajax({
        //                 //     type: 'POST',
        //                 //     url: "{{ route('unit_search_withName') }}",
        //                 //     data: {
        //                 //         _token: "{{ csrf_token() }}",
        //                 //         unit: selectedUnit,
        //                 //         item_name: item['item_name'],
        //                 //         // Adjusted to match server-side parameter name
        //                 //     },
        //                 //     success: function(data) {

        //                 //         $("#item_kit_retail_price-" + count).val(data.retail);


        //                 //     },
        //                 //     error: function(xhr, status, error) {
        //                 //         console.error(xhr.responseText);
        //                 //     }
        //                 // });

        //                 $.ajax({
        //                     type: 'POST',
        //                     url: "{{ route('unit_search_withName') }}",
        //                     data: {
        //                         _token: "{{ csrf_token() }}",
        //                         unit: selectedUnit,
        //                         item_name: item['item_name'],
        //                         item_id: variation['id'],

        //                         description: variation['descriptions'],
        //                         result_expired_date: variation['expired_date'],
        //                         // Adjusted to match server-side parameter name
        //                     },
        //                     success: function(data) {

        //                         $("#item_kit_retail_price-" + count).val(data.retail);
        //                     },
        //                     error: function(xhr, status, error) {
        //                         console.error(xhr.responseText);
        //                     }
        //                 });

        //             });



        //             $("#showitem123").append(newRow);

        //         }
        //         $("#item_kit_unit-" + count).trigger('change');
        //     }


        //     $(document).on('click', '.remove_item_btn', function(e) {
        //         e.preventDefault();
        //         $(this).closest('tr').remove();
        //         $('#showitem123 tr').each(function(index) {
        //             $(this).find('td:first').text(index + 1);
        //         });
        //         initializeTypeahead();
        //     });

        //     // $(document).on('click', '.typeahead .dropdown-item', function(e) {
        //     //     e.preventDefault();

        //     //     if ($("#customer").val()) {} else {
        //     //         const row = $(this).closest('tr');

        //     //         const item_name = $('#result_product_name-0').val();
        //     //         const productname = $('#productname').val();
        //     //         const description = $('#result_descriptions-0').val();
        //     //         const product_code = $('#result_product_code-0').val();
        //     //         let cuz_name = $("#type").val();
        //     //         updateItemName(item_name, row, description, product_code, cuz_name, productname);
        //     //         $('#productname').val('');
        //     //     }
        //     // });

        //     $(document).on('click', '.typeahead .dropdown-item', function(e) {
        //         e.preventDefault();

        //         if ($("#customer").val()) {} else {
        //             const row = $(this).closest('tr');

        //             const itemname = $('#result_product_name-0').val();
        //             const productname = $('#productname').val();
        //             const description = $('#result_descriptions-0').val();
        //             // const product_code = $('#result_product_code-0').val();
        //             const item_id = $('#result_id-0').val();
        //             const expired_date = $('#result_expired_date-0').val();
        //             let cuz_name = $("#type").val();
        //             updateItemName(itemname, row, description, item_id, cuz_name, productname,
        //                 expired_date);

        //             $('#productname').val('');
        //         }
        //     });


        //     initializeTypeahead(count);
        //     $(document).on("click", '#calculate', function(e) {
        //         e.preventDefault();
        //         let total = 0;
        //         let totalTax = 0;
        //         for (let i = 0; i < (count + 1); i++) {
        //             var qty = parseInt($('#item_kit_quantity-' + i).val() || 0);
        //             var item_name = $('#item_kit_name-' + i).val() || 0;
        //             var sel = $('#focsel-' + i).val() || 0;
        //             let price = parseInt($('#item_kit_retail_price-' + i).val() || 0);

        //             $("#item_kit_amount-" + i).text(price * qty);
        //             console.log($("#item_kit_amount-" + i).val(price * qty));


        //             total += price * qty;

        //         }
        //         let taxt = total * 0.05;
        //         taxt = Math.ceil(taxt);
        //         let total_total = total - totalTax;
        //         $("#invoiceyoghtml").val(total);

        //         $('#total_total').val('');
        //     });


        //     function calculateTotal() {
        //         let invoiceyoghtml = parseFloat($('#invoiceyoghtml').val()) || 0;
        //         let total_discount = parseFloat($('#total_discount').val()) || 0;
        //         let total_total = invoiceyoghtml + total_discount;
        //         $('#total_total').val(total_total);
        //         $('#retail1').val(total_total);
        //         $('#wholesale1').val(total_total);
        //         $('#price1').val(invoiceyoghtml);
        //     }

        //     $(document).ready(function() {
        //         $('#total_discount').on('input', calculateTotal);
        //         calculateTotal();
        //     });




        // });

        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 0;

            function toggleMainAddRowButton() {
                if (document.querySelectorAll('.row-wrapper').length > 0) {
                    document.querySelector('.main-add-row').style.display = 'none';
                } else {
                    document.querySelector('.main-add-row').style.display = 'block';
                }
            }



            function toggleCheckboxVisibility() {
                const itemKitCheckbox = document.getElementById('item_kit_div');
                if (rowCount > 0) {
                    itemKitCheckbox.style.display = 'none';
                } else {
                    itemKitCheckbox.style.display = '';
                }
            }

            toggleMainAddRowButton();
            toggleCheckboxVisibility();

            document.querySelector('.addRow').addEventListener('click', function() {
                addNewRow();
            });

            function addNewRow() {
                rowCount++;

                const newRow = `
        <div class="row-wrapper">
            <hr class="" style="border: 1px solid #000000;">
            <div class="mt-2 mb-3" style="font-size: 17.6px;"><span>Variations</span></div>
            <div class="row">
            <div class="form-group col-md-4">
            <label for="variations_descriptions">LOT NO.<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="lot_no"
                name="lot_no[]" placeholder="Enter LOT NO."
                equired>
            </div>
            <div class="form-group col-md-4">
                <label for="expired_date">Warehouse In Date</label>
                <input type="date" class="form-control" id="warehousein_date"
                name="warehousein_date[]" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="expired_date">MUF Date</label>
                <input type="date" class="form-control" id="muf_date" name="muf_date[]"value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="expired_date">Expired Date</label>
                <input type="date" class="form-control" id="expired_date" name="expired_date[]" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="product_code">Estd. Test</label>
                <input type="text" class="form-control" id="estd_test" name="estd_test[]" placeholder="">
            </div>
            <div class="form-group col-md-4">
                <label for="product_code">Country Of Origin</label>
                <input type="text" class="form-control" id="country_of_origin" name="country_of_origin[]" placeholder="">
            </div>
            <div class="form-group col-md-4">
                <label for="product_code">Distributor/Supplier</label>
                <input type="text" class="form-control" id="distributor" name="distributor[]" placeholder="">
            </div>
            <div class="form-group col-md-4">
                <label for="quantity">Quantity <span class="text-danger">*</span></label>
                <input type="number" required class="form-control" name="quantity[]" id="quantity" value="0" placeholder="Enter Quantity" required>
            </div>

            <div class="form-group col-md-4">
                <label for="reorder_level_stock">Reorder Level Stock</label>
                <input type="number" class="form-control" id="reorder_level_stock" name="reorder_level_stock[]" value="0" placeholder="Enter Reorder Level Stock">
            </div>
            </div>
            <div class="row mt-2">
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
                            <td><input type="number" class="form-control" name="unit1[]" value="1" readonly></td>
                            <td><select name="name1[]" class="form-control" required>
                                    <option value="btl">btl</option>
                                    <option value="amp">amp</option>
                                    <option value="tube">tube</option>
                                    <option value="strip">strip</option>
                                    <option value="cap">cap</option>
                                    <option value="pcs">pcs</option>
                                    <option value="sac">sac</option>
                                    <option value="box">box</option>
                                    <option value="pkg">pkg</option>
                                    <option value="tab">tab</option>

                                </select></td>
                            <td><input type="number" class="form-control" name="price1[]" step="any" value="0" required></td>
                            <td><input type="number" class="form-control" name="wholesale1[]" step="any" value="0" required></td>
                            <td><input type="number" class="form-control" name="retail1[]" step="any" value="0"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><input type="number" class="form-control" name="unit2[]"></td>
                            <td><select name="name2[]" class="form-control">
                                      <option selected disabled>Choose Unit</option>
                                      <option value="btl">btl</option>
                                    <option value="amp">amp</option>
                                    <option value="tube">tube</option>
                                    <option value="strip">strip</option>
                                    <option value="cap">cap</option>
                                    <option value="pcs">pcs</option>
                                    <option value="sac">sac</option>
                                    <option value="box">box</option>
                                    <option value="pkg">pkg</option>
                                    <option value="tab">tab</option>
                                </select></td>
                            <td><input type="number" class="form-control" step="any" name="price2[]"></td>
                            <td><input type="number" class="form-control" step="any" name="wholesale2[]"></td>
                            <td><input type="number" class="form-control" step="any" name="retail2[]"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><input type="number" class="form-control" name="unit3[]"></td>
                            <td><select name="name3[]" class="form-control">
                                    <option selected disabled>Choose Unit</option>
                                      <option value="btl">btl</option>
                                    <option value="amp">amp</option>
                                    <option value="tube">tube</option>
                                    <option value="strip">strip</option>
                                    <option value="cap">cap</option>
                                    <option value="pcs">pcs</option>
                                    <option value="sac">sac</option>
                                    <option value="box">box</option>
                                    <option value="pkg">pkg</option>
                                    <option value="tab">tab</option>
                                </select></td>
                            <td><input type="number" class="form-control" step="any" name="price3[]"></td>
                            <td><input type="number" class="form-control" step="any" name="wholesale3[]"></td>
                            <td><input type="number" class="form-control" step="any" name="retail3[]"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-success mx-1 mt-3 addRow ${rowCount}"><i class="fa-solid fa-plus"></i></button>
            <button type="button" class="btn btn-danger removeRow mt-3"><i class="fa-solid fa-minus"></i></button>
        </div>`;

                document.getElementById('rowsContainer').insertAdjacentHTML('beforeend', newRow);

                if (rowCount > 1) {
                    const prevAddRowButton = document.querySelector(
                        `#rowsContainer .row-wrapper:nth-last-child(2) .addRow`);
                    if (prevAddRowButton) {
                        prevAddRowButton.style.display = 'none';
                    }
                }

                toggleMainAddRowButton();
                toggleCheckboxVisibility();

                const newAddRowButton = document.querySelector('#rowsContainer .row-wrapper:last-child .addRow');
                newAddRowButton.addEventListener('click', function() {
                    addNewRow();
                });

                const newRemoveRowButton = document.querySelector(
                    '#rowsContainer .row-wrapper:last-child .removeRow');
                newRemoveRowButton.addEventListener('click', function() {
                    rowCount--;
                    this.closest('.row-wrapper').remove();
                    toggleMainAddRowButton();
                    toggleCheckboxVisibility();

                    if (rowCount > 0) {
                        const prevAddRowButton = document.querySelector(
                            `#rowsContainer .row-wrapper:nth-last-child(1) .addRow`);
                        if (prevAddRowButton) {
                            prevAddRowButton.style.display = '';
                        }
                    }
                });
            }
        });


        $(document).ready(function() {
            $('#item_kit_show').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.main-add-row').hide();
                } else {
                    $('.main-add-row').show();
                }
            });
        });
    </script>
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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                // "buttons": ["excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
