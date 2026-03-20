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
                                <h1>Item Register</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Item Register
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
                        <div class="card-header">
                            <h3 class="card-title">Item Register</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ url('item_store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="frmSearch col-sm-6">
                                        <input type="checkbox" id="show" class=""> <span>Already Registered?</span>
                                        <div id="additional-elements" style="display: none;" class="mt-2">
                                            <span style="font-weight: bolder;">
                                                <label for="cst"
                                                    class="caption">{{ trans('Search With Item Name') }}</label>
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

                                    {{-- @if (in_array('Item Kits Register', $userPermissions) || auth()->user()->is_admin == '1')
                                        <input type="hidden" name="status" id="status">
                                        <div class="frmSearch col-sm-6" id="item_kit_div">
                                            <input type="checkbox"  name="item_kit" id="item_kit_show" class="">
                                            <span>Items
                                                Kit</span>

                                        </div>
                                    @endif --}}
                                </div>

                                <div class="row mt-5">


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
                                        <input type="text" class="form-control" id="category" name="category"
                                            value="{{ old('category') }}" placeholder="Enter Item Category">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="category">Product Category</label>
                                        <input type="text" class="form-control" id="category" name="category">
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




                                <div class="mt-5" style="font-size: 17.6px;"><span>Sale Price</span></div>
                                <hr>

                                <div id="rowsContainer">



                                    <div class="mt-3 row mb-5">


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
                        $("#barcode").val(data['item']['barcode']);
                        $("#descriptions").val(data['item']['descriptions']);
                        $("#expired_date").val(data['variation']['expired_date']);
                        $("#variations_descriptions").val(data['variation']['descriptions']);
                        $("#product_code").val(data['variation']['product_code']);
                        $("#category").val(data['item']['category']);
                        $("#market").val(data['item']['market']);
                        $("#item_unit").val(data['item']['item_unit']);

                        $("#price").val(data['item']['price']);
                        $("#reorder_level_stock").val(data['variation']['reorder_level_stock']);
                        $("#mingalar_market").val(data['item']['mingalar_market']);
                        $("#company_price").val(data['item']['company_price']);
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


        $(document).ready(function() {
            let count = 0;



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
            <hr>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="expired_date">Expired Date</label>
                    <input type="date" class="form-control" name="expired_date[]" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="variations_descriptions">Descriptions<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="variations_descriptions[]" placeholder="Enter Descriptions" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="product_code">Product Code  </label>
                    <input type="text" class="form-control"  name="product_code[]" placeholder="Enter Product Code">
                </div>
                <div class="form-group col-md-4">
                    <label for="barcode">Barcode</label>
                    <input type="text" class="form-control" name="variations_barcode[]" placeholder="Enter BarCode">
                </div>
                <div class="form-group col-md-4">
                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="quantity[]" value="0" placeholder="Enter Quantity" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="reorder_level_stock">Reorder Level Stock</label>
                    <input type="number" class="form-control" name="reorder_level_stock[]" value="0" placeholder="Enter Reorder Level Stock">
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
