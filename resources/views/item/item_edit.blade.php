@include('layouts.header')
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
                    <a class="nav-link  text-gray" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
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
                    <button type="button" class="btn dropdown-toggle text-gray" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
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

                                    Product Edit

                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Product Edit
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
                            <h3 class="col-md-12 mt-1">

                                <div class="frmSearch col-sm-4">
                                    <label for="" style="font-size: 20px;">Location</label>
                                    <select name="item_location" id="item_location" class="form-control">

                                        @foreach ($branchs as $branch)
                                            @if ($branch->id == $items->warehouse_id)
                                                <option value="{{ $branch->id }}" selected>{{ $branch->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>


                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->



                        <form action="{{ url('item_update', $items->id) }}" method="POST" enctype="multipart/form-data"
                            class="item-form">
                            @csrf
                            <div class="card-body">


                                <div class="row">


                                    <div class="form-group col-md-4">
                                        <label for="item_name">Product Name <span class="text-danger">
                                                *</span></label>
                                        <input type="text" class="form-control" id="item_name" name="item_name"
                                            placeholder="Enter Item Name" value="{{ $items->item_name }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="barcode">Brand Name</label>
                                        <select class="form-control" id="brand" name="brand">
                                            <option value=""selected disabled>Choose Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    @if ($brand->id == $items->brand) selected @endif>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="form-group col-md-6">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode"
                                                placeholder="Enter BarCode" value="{{ $items->barcode }}">
                                        </div> --}}

                                    <div class="form-group col-md-4">
                                        <label for="descriptions">Product Desctriptions</label>
                                        <input type="text" class="form-control" id="descriptions"
                                            placeholder="Enter Item Descriptions" name="descriptions"
                                            value="{{ $items->descriptions }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="expired_date">Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        <span class="text-danger">{{ $items->image }}</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="category">Product Type</label>
                                        <input type="text" class="form-control" id="product_type" name="product_type"
                                            value="{{ $items->product_type }}" placeholder="Enter Product Type">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="category">Product Category</label>
                                        <input type="text" class="form-control" id="product_category"
                                            name="product_category" value="{{ $items->product_category }}"
                                            placeholder="Enter ProductCategory">
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

                                </div>


                                <div class="mt-2" style="font-size: 17.6px;"><span>Variations</span></div>
                                <hr>

                                <div id="rowsContainer">

                                    @foreach ($items->variations as $key => $variation)
                                        <div class="group" id="group-{{ $key }}">
                                            <div class="mt-3 row mb-5">
                                                <div class="form-group col-md-4">
                                                    <label for="variations_descriptions">LOT NO.<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="lot_no"
                                                        name="lot_no[]" placeholder="Enter LOT NO." required
                                                        value="{{ $variation->lot_no }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="expired_date">Warehouse In Date</label>
                                                    <input type="date" class="form-control" id="warehousein_date"
                                                        name="warehousein_date[]"
                                                        value="{{ $variation->arrival_date }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="expired_date">MUF Date</label>
                                                    <input type="date" class="form-control" id="muf_date"
                                                        name="muf_date[]" value="{{ $variation->muf_date }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="expired_date">Expired Date</label>
                                                    <input type="date" class="form-control" id="expired_date"
                                                        name="expired_date[]" value="{{ $variation->expired_date }}">

                                                    <input type="hidden" class="form-control" id="variation_id"
                                                        name="variation_id[]" value="{{ $variation->id }}">
                                                </div>



                                                <div class="form-group col-md-4">
                                                    <label for="product_code">Estd. Test</label>
                                                    <input type="text" class="form-control" id="estd_test"
                                                        name="estd_test[]" placeholder=""
                                                        value="{{ $variation->estd_test }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="product_code">Country Of Origin</label>
                                                    <input type="text" class="form-control" id="country_of_origin"
                                                        name="country_of_origin[]" placeholder=""
                                                        value="{{ $variation->country_of_origin }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="product_code">Distributor/Supplier</label>
                                                    <input type="text" class="form-control" id="distributor"
                                                        name="distributor[]" placeholder=""
                                                        value="{{ $variation->distributor }}">
                                                </div>


                                                <div class="form-group col-md-4">
                                                    <label for="quantity">
                                                        Quantity
                                                    </label>
                                                    <input type="hidden" class="form-control" name="quantity[]"
                                                        id="quantity" value="{{ $variation->quantity }}"
                                                        placeholder="Enter Quantity" readonly>

                                                    @php
                                                        $level1qty = 0;
                                                        $level2qty = 0;
                                                        $level3qty = 0;

                                                        // Calculate level 1 quantity
                                                        $lvl1 = floor(
                                                            intval($variation->quantity) /
                                                                (intval($variation->unit2) ?: 1),
                                                        );
                                                        $level1qty = $lvl1;

                                                        // Calculate remaining quantity for level 2
                                                        $first_lvl2 = fmod(
                                                            floatval($variation->quantity),
                                                            floatval($variation->unit2 ?? 1),
                                                        ); // Ensure unit2 is float
                                                        $level2qty = floor($first_lvl2);

                                                        // Calculate fractional part for level 3
                                                        $level3_fractional =
                                                            ($first_lvl2 - $level2qty) * (float) $variation->unit3;
                                                        $level3qty = ceil($level3_fractional - 0.5);
                                                    @endphp



                                                    <input type="text" class="form-control" name="quantity1"
                                                        id="quantity"
                                                        value="@if ($level1qty != 0) {{ $level1qty . ' ' . $variation->name1 }} @endif
                                                        @if ($level2qty != 0 && $level2qty > 0) {{ $level2qty . ' ' . $variation->name2 }} @endif
                                                        @if ($level3qty != 0 && $level3qty > 0) {{ $level3qty . ' ' . $variation->name3 }} @endif
                                                        @if ($level1qty == 0 && $level2qty == 0 && $level3qty == 0) 0 {{ $variation->name1 }} @endif"
                                                        placeholder="Enter Quantity" readonly step="any">
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="reorder_level_stock">Reorder Level Stock</label>
                                                    <input type="number" class="form-control"
                                                        id="reorder_level_stock" name="reorder_level_stock[]"
                                                        value="{{ $variation->reorder_level_stock }}"
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
                                                                <input type="number" class="form-control"
                                                                    name="unit1[]" value="{{ $variation->unit1 }}"
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <select name="name1[]" id="name1"
                                                                    class="form-control" required>
                                                                    <option selected disabled>Choose Unit</option>
                                                                    <option value="btl"
                                                                        @if ($variation->name1 == 'btl') selected @endif>
                                                                        btl</option>
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
                                                            <td><input type="number" class="form-control"
                                                                    name="price1[]" id="price1"
                                                                    value="{{ $variation->price1 }}" step="any"
                                                                    required>
                                                            </td>

                                                            <td><input type="number" class="form-control"
                                                                    name="wholesale1[]" id="wholesale1"
                                                                    value="{{ $variation->wholesale1 }}"
                                                                    step="any" required>
                                                            </td>
                                                            <td><input type="number" class="form-control"
                                                                    name="retail1[]" id="retail1" step="any"
                                                                    value="{{ $variation->retail1 }}">
                                                            </td>
                                                            <!-- <td><input type="number" class="form-control" name="quantity1"></td> -->
                                                        </tr>
                                                        <tr id="row2">
                                                            <td>2</td>
                                                            <td><input type="number" class="form-control"
                                                                    name="unit2[]" id="unit2"
                                                                    value="{{ $variation->unit2 }}"></td>
                                                            <td>
                                                                <select name="name2[]" id="name2"
                                                                    class="form-control">
                                                                    <option selected disabled>Choose Unit</option>
                                                                    <option value="btl"
                                                                        @if ($variation->name2 == 'btl') selected @endif>
                                                                        btl</option>
                                                                    <option value="amp"
                                                                        @if ($variation->name2 == 'amp') selected @endif>
                                                                        amp</option>
                                                                    <option
                                                                        value="tube"@if ($variation->name2 == 'tube') selected @endif>
                                                                        tube</option>
                                                                    <option value="strip"
                                                                        @if ($variation->name2 == 'strip') selected @endif>
                                                                        strip</option>
                                                                    <option value="cap"
                                                                        @if ($variation->name2 == 'cap') selected @endif>
                                                                        cap</option>
                                                                    <option value="pcs"
                                                                        @if ($variation->name2 == 'pcs') selected @endif>
                                                                        pcs</option>
                                                                    <option value="sac"
                                                                        @if ($variation->name2 == 'sac') selected @endif>
                                                                        sac</option>
                                                                    <option value="box"
                                                                        @if ($variation->name2 == 'box') selected @endif>
                                                                        box</option>
                                                                    <option value="pkg"
                                                                        @if ($variation->name2 == 'pkg') selected @endif>
                                                                        pkg</option>
                                                                    <option value="tab"
                                                                        @if ($variation->name2 == 'tab') selected @endif>
                                                                        tab</option>
                                                                </select>

                                                            </td>
                                                            <td><input type="number" class="form-control"
                                                                    name="price2[]" id="price2" step="any"
                                                                    value="{{ $variation->price2 }}"></td>
                                                            <td><input type="number" class="form-control"
                                                                    name="wholesale2[]" id="wholesale2"
                                                                    step="any"
                                                                    value="{{ $variation->wholesale2 }}"></td>
                                                            <td><input type="number" class="form-control"
                                                                    step="any" name="retail2[]" id="retail2"
                                                                    value="{{ $variation->retail2 }}"></td>

                                                            <!-- <td><input type="number" class="form-control" name="quantity2"></td> -->
                                                        </tr>
                                                        </tr>
                                                        <tr id="row3">
                                                            <td>3</td>
                                                            <td><input type="number" class="form-control"
                                                                    name="unit3[]" id="unit3"
                                                                    value="{{ $variation->unit3 }}"></td>
                                                            <td><select name="name3[]" id="name3"
                                                                    class="form-control">
                                                                    <option selected disabled>Choose Unit</option>
                                                                    <option value="btl"
                                                                        @if ($variation->name3 == 'btl') selected @endif>
                                                                        btl</option>
                                                                    <option value="amp"
                                                                        @if ($variation->name3 == 'amp') selected @endif>
                                                                        amp</option>
                                                                    <option
                                                                        value="tube"@if ($variation->name3 == 'tube') selected @endif>
                                                                        tube</option>
                                                                    <option
                                                                        value="strip"@if ($variation->name3 == 'strip') selected @endif>
                                                                        strip</option>
                                                                    <option value="cap"
                                                                        @if ($variation->name3 == 'cap') selected @endif>
                                                                        cap</option>
                                                                    <option
                                                                        value="pcs"@if ($variation->name3 == 'pcs') selected @endif>
                                                                        pcs</option>
                                                                    <option value="sac"
                                                                        @if ($variation->name3 == 'sac') selected @endif>
                                                                        sac</option>
                                                                    <option value="box"
                                                                        @if ($variation->name3 == 'box') selected @endif>
                                                                        box</option>
                                                                    <option value="pkg"
                                                                        @if ($variation->name3 == 'pkg') selected @endif>
                                                                        pkg</option>
                                                                    <option value="tab"
                                                                        @if ($variation->name3 == 'tab') selected @endif>
                                                                        tab</option>
                                                                </select>
                                                            </td>
                                                            <td><input type="number" class="form-control"
                                                                    name="price3[]" id="price3" step="any"
                                                                    value="{{ $variation->price3 }}"></td>
                                                            <td><input type="number" class="form-control"
                                                                    step="any" name="wholesale3[]"
                                                                    id="wholesale3"
                                                                    value="{{ $variation->wholesale3 }}"></td>
                                                            <td><input type="number" class="form-control"
                                                                    step="any" name="retail3[]" id="retail3"
                                                                    value="{{ $variation->retail3 }}"></td>

                                                            <!-- <td><input type="number" class="form-control" name="quantity3"></td> -->
                                                        </tr>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>

                                            <button type="button" class="btn btn-danger mainremoveRow mt-3"
                                                id="removebutton-{{ $key }}"><i
                                                    class="fa-solid fa-minus"></i></button>
                                        </div>
                                    @endforeach


                                    <button type="button" class="btn btn-success mt-3 addRow main-add-row"><i
                                            class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="mb-3 ml-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger">Update</button>
                            </div>
                        </form>


                    </div>
                </div>

            </div>

        </div>



    </div>


    @include('layouts.footer')

    <script src="{{ asset('backend/js/moment2103.js') }}"></script>
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
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






        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 0;

            function toggleMainAddRowButton() {
                if (document.querySelectorAll('.row-wrapper').length > 0) {
                    document.querySelector('.main-add-row').style.display = 'none';
                } else {
                    document.querySelector('.main-add-row').style.display = '';
                }
            }

            toggleMainAddRowButton();

            document.querySelector('.addRow').addEventListener('click', function() {
                addNewRow();
            });

            document.querySelector('.mainremoveRow').addEventListener('click', function() {
                removeLastRow();
            });

            function addNewRow() {
                rowCount++;

                const newRow = `
        <div class="row-wrapper">
            <hr>
             <div class="row">
            <div class="form-group col-md-4">
            <label for="variations_descriptions">LOT NO.<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="lot_no"
                name="lot_no[]" placeholder="Enter LOT NO."
                required>
                 <input type="hidden" class="form-control" id="variation_id"
                name="variation_id[]"
                >
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
                            <td><input type="number" class="form-control" name="unit1[]" value="1" readonly></td>
                            <td><select name="name1[]" class="form-control">
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
                            <td><input type="number" class="form-control" step="any" name="price1[]" value="0" required></td>
                            <td><input type="number" class="form-control" step="any" name="wholesale1[]" value="0" required></td>
                            <td><input type="number" class="form-control" step="any" name="retail1[]" value="0"></td>
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

                    if (rowCount > 0) {
                        const prevAddRowButton = document.querySelector(
                            `#rowsContainer .row-wrapper:nth-last-child(1) .addRow`);
                        if (prevAddRowButton) {
                            prevAddRowButton.style.display = '';
                        }
                    }
                });
            }

            // document.addEventListener('DOMContentLoaded', function() {
            //     const removeRowButtons = document.querySelectorAll('.mainremoveRow');
            //     removeRowButtons.forEach(button => {
            //         button.addEventListener('click', removeLastRow);
            //     });
            // });

            // function removeLastRow() {
            //     const rows = document.querySelectorAll(
            //         '#rowsContainer .group');
            //     const removeButton = document.querySelector('.mainremoveRow');

            //     if (rows.length > 1) {
            //         rows[rows.length - 1].remove();
            //         removeButton.style.display = '';
            //     } else {
            //         removeButton.style.display = 'none';
            //     }
            // }

        });
        document.querySelectorAll('[id^="removebutton-"]').forEach(button => {
            button.addEventListener('click', function() {
                removeClickedRow(this);
            });
        });

        function removeClickedRow(button) {
            const row = button.closest('.group'); // Finds the closest parent row
            if (row) {
                row.remove();
            }
        }
    </script>
