@include('layouts.header')
<!-- <style>
    .changelogout:hover {
        background-color: whitesmoke;
        color: red;
    }

    input[type="date"]::-webkit-datetime-edit-text {
        color: transparent;
    }

    input[type="date"]::-webkit-inner-spin-button,
    input[type="date"]::-webkit-clear-button {
        color: #fff;
        position: relative;
    }

    input[type="date"]::-webkit-datetime-edit-year-field {
        position: absolute !important;
        border-left: 1px solid #8c8c8c;
        padding: 2px;
        padding-left: 10px;
        color: #000;
        left: 130px;
    }

    input[type="date"]::-webkit-datetime-edit-month-field {
        position: absolute !important;
        border-left: 1px solid #8c8c8c;
        padding: 2px;
        padding-left: 10px;
        color: #000;
        left: 78px;
    }


    input[type="date"]::-webkit-datetime-edit-day-field {
        position: absolute !important;
        color: #000;
        padding: 2px;
        padding-left: 10px;
        left: 30px;

    }
</style> -->

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
                                <h1>Item Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Item Edit
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
                        <!-- <div class="card-header">
                            <h3 class="card-title">Item Edit</h3>
                        </div> -->
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ url('item_update', $items->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="frmSearch col-sm-6">
                                        <input type="checkbox" name="item_kit" id="item_kit_show"
                                            {{ $items->item_kit ? 'checked' : 'on' }}> Items Kit (ကုန်ချောထုတ်လုပ်မှု)
                                    </div>

                                </div>

                                <div class="row">


                                    <div class="form-group col-md-6">
                                        <label for="item_name">Item Name</label>
                                        <input type="text" class="form-control" id="item_name" name="item_name"
                                            placeholder="Enter Item Name" value="{{ $items->item_name }}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" class="form-control" id="barcode" name="barcode"
                                            placeholder="Enter BarCode" value="{{ $items->barcode }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="descriptions">Item Desctriptions</label>
                                        <input type="text" class="form-control" id="descriptions"
                                            placeholder="Enter Item Descriptions" name="descriptions"
                                            value="{{ $items->descriptions }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="expired_date">Expired Date</label>
                                        <input type="date" class="form-control" id="expired_date" name="expired_date"
                                            value="{{ $items->expired_date }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="category">Item Category</label>
                                        <input type="text" class="form-control" id="category" name="category"
                                            value="{{ $items->category }}" placeholder="Enter Item Category">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="warehouse_id">Location<span class="text-danger">*</span></label>
                                        <select name="warehouse_id" id="warehouse_id" class="form-control">
                                            <option value="{{ $items->warehouse_id }}" selected>
                                                {{ $items->warehouse->name }}
                                            </option>
                                            @foreach ($branchs as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-5" style="font-size: 17.6px;">Purchase Price</div>
                                <hr>


                                <div class="row mt-3">


                                    <div class="form-group col-md-6">
                                        <label for="quantity">
                                            Quantity
                                        </label>
                                        <input type="hidden" class="form-control" name="quantity" id="quantity"
                                            value="{{ $items->quantity }}" placeholder="Enter Quantity" readonly>
                                        @php
                                        $quantity = floor($items->quantity / ($items->unit2 ?? 1));
                                        @endphp
                                        <input type="number" class="form-control" name="quantity1" id="quantity"
                                            value="{{ $quantity }}" placeholder="Enter Quantity" readonly>
                                    </div>

                                    {{-- <div class="form-group col-md-6">
                                        <label for="item_unit">
                                            Unit
                                        </label>

                                        <select class="form-control unit" name="item_unit[]" id="unit-0" required>
                                            <option selected disabled>Choose Unit</option>
                                            <option value="Capsule"  @if ($items->item_unit === 'Capsule') selected @endif>တစ်လုံး</option>
                                            <option value="Stick" @if ($items->item_unit === 'Stick') selected @endif>တစ်ချောင်း</option>
                                            <option value="Pack" @if ($items->item_unit === 'Pack') selected @endif>တစ်ထုပ်</option>
                                            <option value="Bottle" @if ($items->item_unit === 'Bottle') selected @endif>တစ်ဘူး</option>
                                            <option value="Card" @if ($items->item_unit === 'Card') selected @endif>တစ်ကဒ်</option>
                                            <option value="Dozen" @if ($items->item_unit === 'Dozen') selected @endif>တစ်ဒါဇင်</option>
                                            <option value="Packing" @if ($items->item_unit === 'Packing') selected @endif>တစ်ပါကင်</option>
                                        </select>
                                    </div> --}}
                                    {{-- <div class="form-group col-md-6">
                                        <label for="price">
                                            Price
                                        </label>
                                        <input type="number" class="form-control" name="price" id="price"
                                            value="{{ $items->price }}" placeholder="Enter Price" step="0.1">
                                </div> --}}



                                <div class="form-group col-md-6">
                                    <label for="reorder_level_stock">Reorder Level Stock</label>
                                    <input type="number" class="form-control" id="reorder_level_stock"
                                        name="reorder_level_stock" value="{{ $items->reorder_level_stock }}"
                                        placeholder="Enter Reorder Level Stock">
                                </div>

                                <div class="form-group col-md-2 mt-3">
                                    <label for="company_price">Company Price</label>
                                    <input type="checkbox" id="company_price" name="company_price"
                                        value="Company Price" {{ $items->company_price ? 'checked' : '' }}
                                        class="option-checkbox">
                                </div>

                                <div class="form-group col-md-2 mt-3">
                                    <label for="mingalar_market">Mingalar Market</label>
                                    <input type="checkbox" id="mingalar_market" name="mingalar_market"
                                        value="Mingalar Market" {{ $items->mingalar_market ? 'checked' : '' }}
                                        class="option-checkbox">
                                </div>

                                <div class="form-group col-md-2 mt-3">
                                    <label for="other">Other</label>
                                    <input type="checkbox" id="other" name="other" value="Other"
                                        {{ $items->other ? 'checked' : '' }} class="option-checkbox">
                                </div>

                            </div>

                            <hr>
                            <div class="row">

                                <div id="item_kit_add" style="display: none;">
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
                                                    <option value="{{ $warehouse->id }}">
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
                                            <div id="customer-box-result"></div>
                                        </div>
                                        {{-- <div class="mt-2 frmSearch col-md-3">
                                                    <button type="button" class="btn btn-success" id="addproduct"
                                                        style="margin-top:20px;margin-bottom:20px;">
                                                        <i class="fa fa-plus-square"></i> {{ trans('Add Item') }}
                                        </button>
                                    </div> --}}
                                </div>
                                <table class=" table-bordered">
                                    <thead style="background-color:#0047AA;color:white;">
                                        <tr class="item_header bg-gradient-directional-blue white"
                                            style="margin-bottom:10px;">
                                            <th width="5%" class="text-center">{{ trans('No') }}
                                            </th>
                                            <th width="18%" class="text-center">
                                                {{ trans('Item Name') }}
                                            </th>
                                            <th width="23%" class="text-center">
                                                {{ trans('Descriptions') }}
                                            </th>
                                            <th width="8%" class="text-center">{{ trans('Qty') }}
                                            </th>
                                            <th width="10%" class="text-center">{{ trans('Unit') }}
                                            </th>
                                            <th width="10%" class="text-center">{{ trans('Price') }}
                                            </th>
                                            <th width="14%" class="text-center">{{ trans('Amount') }}
                                            </th>
                                            <th class="text-center">{{ trans('Action') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="showitem123">
                                        @foreach ($items_kit as $key => $kit)
                                        <tr>
                                            <td class="text-center" id="count">
                                                {{ $key + 1 }}
                                            </td>
                                            <td><input type="text" class="form-control item_kit_name"
                                                    name="item_kit_name[]" id="item_kit_name-0"
                                                    autocomplete="off" value="{{ $kit->item_kit_name }}">
                                            </td>
                                            <td><input type="text"
                                                    class="form-control item_kit_description"
                                                    name="item_kit_description[]"
                                                    id="item_kit_description-0" autocomplete="off"
                                                    value="{{ $kit->item_kit_description }}">
                                            </td>
                                            <td><input type="text"
                                                    class="form-control item_kit_quantity"
                                                    name="item_kit_quantity[]" id="item_kit_quantity-0"
                                                    autocomplete="off"
                                                    value="{{ $kit->item_kit_quantity }}"></td>
                                            <td><input type="text" class="form-control item_kit_unit"
                                                    name="item_kit_unit[]" id="item_kit_unit-0"
                                                    value="{{ $kit->item_kit_unit }}">
                                            </td>
                                            <td><input type="text"
                                                    class="form-control item_kit_retail_price"
                                                    name="item_kit_retail_price[]"
                                                    id="item_kit_retail_price-0" autocomplete="off"
                                                    value="{{ $kit->item_kit_retail_price }}">
                                            </td>
                                            <td style="display: none;"><input type="text"
                                                    class="form-control item_kit_wholesale_price"
                                                    name="item_kit_wholesale_price[]"
                                                    id="item_kit_wholesale_price-0" autocomplete="off"
                                                    value="{{ $kit->item_kit_wholesale_price }}">
                                            </td>
                                            <td style="display: none;"><input type="text"
                                                    class="form-control warehouse" name="warehouse[]"
                                                    id="warehouse-0" autocomplete="off"
                                                    value="{{ $kit->warehouse }}"></td>
                                            <td style="text-align:center"><input type="text"
                                                    class="form-control item_kit_amount"
                                                    name="item_kit_amount[]" id="item_kit_amount-0"
                                                    value="{{ $kit->item_kit_amount }}" readonly></td>
                                            <td style="width: 10%;" class="text-center"><button
                                                    type="button"
                                                    class="btn btn-danger remove_item_btn">Remove</button>
                                            </td>
                                        </tr>
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

                                                @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin')
                                                <a href="{{ URL('items') }}" target="_blank"
                                                    id="item_search">
                                                    <button type="button" class="btn btn-success">
                                                        <i class="fa fa-plus-square"></i> Item Search
                                                    </button></a>
                                                @endif
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
                                                    style="background-color: #E9ECEF"
                                                    value="{{ $items->buy_price }}">

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
                                                    id="total_discount" value="{{ $items->service_charge }}">
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
                                                    style="background-color: #E9ECEF"
                                                    value="{{ $items->retail_price }}">

                                            </td>

                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="form-group col-md-6" id="retail_price_div">
                                        <label for="retail_price">လက်လီ‌စျေး</label>
                                        <input type="number" class="form-control" id="retail_price"
                                            name="retail_price" value="{{ $items->retail_price }}">
                    </div> --}}

                    {{-- <div class="form-group col-md-6">
                                        <label for="wholesale_price">လက်ကားစျေး</label>
                                        <input type="number" class="form-control" id="wholesale_price"
                                            name="wholesale_price" placeholder="Enter Wholesale_Price"
                                            value="{{ $items->wholesale_price }}">
                </div> --}}

                {{-- <div class="form-group col-md-6" id="buy_price_div">
                                        <label for="buy_price">ဝယ်စျေး</label>
                                        <input type="number" class="form-control" id="buy_price" name="buy_price"
                                            placeholder="Enter Buy Price" value="{{ $items->buy_price }}">
            </div> --}}
        </div>

        <div class="mt-5" style="font-size: 17.6px;">Sale Price</div>
        <hr>

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
                            <input type="number" class="form-control" name="unit1"
                                value="1" readonly>
                        </td>
                        <td>
                            <select name="name1" id="name1" class="form-control">


                                <option selected disabled>Choose Unit</option>
                                <option value="လုံး"
                                    @if ($items->name1 === 'လုံး') selected @endif>တစ်လုံး
                                </option>
                                <option value="ခု"
                                    @if ($items->name3 == 'ခု') selected @endif>တစ်ခု
                                </option>
                                <option value="ချောင်း"
                                    @if ($items->name1 === 'ချောင်း') selected @endif>တစ်ချောင်း
                                </option>
                                <option value="ထုပ်"
                                    @if ($items->name1 === 'ထုပ်') selected @endif>တစ်ထုပ်
                                </option>
                                <option value="ဘူး"
                                    @if ($items->name1 === 'ဘူး') selected @endif>တစ်ဘူး
                                </option>
                                <option value="ကဒ်"
                                    @if ($items->name1 === 'ကဒ်') selected @endif>တစ်ကဒ်
                                </option>
                                <option value="ဒါဇင်"
                                    @if ($items->name1 === 'ဒါဇင်') selected @endif>တစ်ဒါဇင်
                                </option>
                                <option value="ပါကင်"
                                    @if ($items->name1 === 'ပါကင်') selected @endif>တစ်ပါကင်
                                </option>
                            </select>
                        </td>
                        <td><input type="number" class="form-control" name="price1"
                                id="price1" value="{{ $items->price1 }}"></td>
                        <td><input type="number" class="form-control" name="wholesale1"
                                id="wholesale1" value="{{ $items->wholesale1 }}"></td>
                        <td><input type="number" class="form-control" name="retail1"
                                id="retail1" value="{{ $items->retail1 }}"></td>

                        <!-- <td><input type="number" class="form-control" name="quantity1"></td> -->
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><input type="number" class="form-control" name="unit2"
                                id="unit2" value="{{ $items->unit2 }}"></td>
                        <td> <select name="name2" id="name2" class="form-control">

                                <option selected disabled>Choose Unit</option>

                                <option value="လုံး"
                                    @if ($items->name2 == 'လုံး') selected @endif>တစ်လုံး
                                </option>
                                <option value="ခု"
                                    @if ($items->name3 == 'ခု') selected @endif>တစ်ခု
                                </option>
                                <option value="ချောင်း"
                                    @if ($items->name2 == 'ချောင်း') selected @endif>
                                    တစ်ချောင်း
                                </option>
                                <option value="ထုပ်"
                                    @if ($items->name2 == 'ထုပ်') selected @endif>တစ်ထုပ်
                                </option>
                                <option value="ဘူး"
                                    @if ($items->name2 == 'ဘူး') selected @endif>တစ်ဘူး
                                </option>
                                <option value="ကဒ်"
                                    @if ($items->name2 == 'ကဒ်') selected @endif>တစ်ကဒ်
                                </option>
                                <option value="ဒါဇင်"
                                    @if ($items->name2 == 'ဒါဇင်') selected @endif>တစ်ဒါဇင်
                                </option>
                                <option value="ပါကင်"
                                    @if ($items->name2 == 'ပါကင်') selected @endif>တစ်ပါကင်
                                </option>
                            </select></td>
                        <td><input type="number" class="form-control" name="price2"
                                id="price2" value="{{ $items->price2 }}"></td>
                        <td><input type="number" class="form-control" name="wholesale2"
                                id="wholesale2" value="{{ $items->wholesale2 }}"></td>
                        <td><input type="number" class="form-control" name="retail2"
                                id="retail2" value="{{ $items->retail2 }}"></td>

                        <!-- <td><input type="number" class="form-control" name="quantity2"></td> -->
                    </tr>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><input type="number" class="form-control" name="unit3"
                                id="unit3" value="{{ $items->unit3 }}"></td>
                        <td> <select name="name3" id="name3" class="form-control">

                                <option selected disabled>Choose Unit</option>
                                <option value="လုံး"
                                    @if ($items->name3 == 'လုံး') selected @endif>တစ်လုံး
                                </option>


                                <option value="ခု"
                                    @if ($items->name3 == 'ခု') selected @endif>တစ်ခု
                                </option>
                                <option value="ချောင်း"
                                    @if ($items->name3 == 'ချောင်း') selected @endif>
                                    တစ်ချောင်း</option>
                                <option value="ထုပ်"
                                    @if ($items->name3 == 'ထုပ်') selected @endif>တစ်ထုပ်
                                </option>
                                <option value="ဘူး"
                                    @if ($items->name3 == 'ဘူး') selected @endif>တစ်ဘူး
                                </option>
                                <option value="ကဒ်"
                                    @if ($items->name3 == 'ကဒ်') selected @endif>တစ်ကဒ်
                                </option>
                                <option value="ဒါဇင်"
                                    @if ($items->name3 == 'ဒါဇင်') selected @endif>တစ်ဒါဇင်
                                </option>
                                <option value="ပါကင်"
                                    @if ($items->name3 == 'ပါကင်') selected @endif>တစ်ပါကင်
                                </option>
                            </select></td>
                        <td><input type="number" class="form-control" name="price3"
                                id="price3" value="{{ $items->price3 }}"></td>
                        <td><input type="number" class="form-control" name="wholesale3"
                                id="wholesale3" value="{{ $items->wholesale3 }}"></td>
                        <td><input type="number" class="form-control" name="retail3"
                                id="retail3" value="{{ $items->retail3 }}"></td>

                        <!-- <td><input type="number" class="form-control" name="quantity3"></td> -->
                    </tr>
                    </tr>
                </tbody>
            </table>

        </div>



    </div>
    <!-- /.card-body -->

    <div class="mb-3 ml-3 ">
        <button type="submit" class="btn btn-danger">Update</button>
    </div>
    </form>
    </div>
    </div>

    </div>

    </div>



    </div>


    @include('layouts.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.option-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.option-checkbox').not(this).prop('checked', false);
                }
            });
        });


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
                                process(data);
                            },
                            error: function(error) {
                                console.error(error);
                            }
                        });
                    },
                });


            }

            function updateItemName(item) {
                var Selectedlocation = $('#location').val();
                if ($("#item_kit_name-0").val() === "") {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('get.part.data-invoice') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            itemname: item,
                            location: Selectedlocation,
                        },
                        success: function(data) {
                            $("#item_kit_name-0").val(data['item']['item_name']);
                            $("#item_kit_description-0").val(data['item']['descriptions']);
                            $("#item_kit_wholesale_price-0").val(data['item']['wholesale_price']);
                            $("#item_kit_retail_price-0").val(data['item']['retail_price']);
                            $("#warehouse-0").val(data['item']['warehouse_id']);
                            console.log($("#warehouse-0").val(data['item']['warehouse_id']));
                            $("#item_kit_unit-0").val(data['item']['item_unit']);
                            $("#productname").val('');

                            if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                                alert(data.quantity + " quantity!");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });

                } else {
                    if ($("#item_kit_name-0").val() === $("#productname").val()) {
                        var existingRow = $("#item_kit_quantity-0");
                        var currentQuantity = parseInt(existingRow.val());
                        existingRow.val(currentQuantity + 1);
                        $("#productname").val('');
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('get.part.data-invoice') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                itemname: item,
                                location: Selectedlocation,
                            },
                            success: function(data) {
                                if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                                    alert(data.quantity + " quantity!");
                                }
                                addNewRow(data['item']);
                                $("#productname").val('');
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                }
            }

            initializeTypeahead();


            function addNewRow(item) {
                let cuz_name = $("#type").val();
                let existingRow = $("#showitem123 input.item_kit_name[value='" + item['item_name'] + "']")
                    .closest(
                        'tr');

                console.log(existingRow.length);
                if (existingRow.length > 0) {
                    console.log('error');
                    // Item already exists, update quantity
                    let qtyInput = existingRow.find('.item_kit_quantity');
                    let currentQty = parseInt(qtyInput.val()) || 0;
                    qtyInput.val(currentQty + 1);
                } else {

                    count++;
                    let rowCount = $("#showitem123 tr").length;
                    let newRow = '<tr>' +

                        '<td class="text-center">' + (rowCount + 1) + '</td>' +

                        '<td><input type="text" class="form-control item_kit_name typeahead" name="item_kit_name[]" id="item_kit_name-' +
                        count + '" autocomplete="off" value="' + item['item_name'] + '"></td>' +
                        '<td><input type="text" class="form-control item_kit_description typeahead" name="item_kit_description[]"  id="item_kit_description-' +
                        count + '" autocomplete="off" value="' + (item['descriptions'] ? item['descriptions'] :
                            '') + '"></td>' +
                        '<td><input type="text" class="form-control item_kit_quantity" name="item_kit_quantity[]" id="item_kit_quantity-' +
                        count +
                        '" autocomplete="off" value="1"></td>' +
                        '<td><input type="text" class="form-control item_kit_unit " name="item_kit_unit[]" id="item_kit_unit-' +
                        count +
                        '" autocomplete ="off"  value="' + item['item_unit'] +
                        '" required> </td>' +


                        '<td><input type="text" class="form-control item_kit_retail_price" name="item_kit_retail_price[]" id="item_kit_retail_price-' +
                        count + '" autocomplete="off" value="' + (item['retail_price'] ?? 0) +
                        '"></td>' +
                        '<td style="display: none;"><input type="text" class="form-control item_kit_wholesale_price" name="item_kit_wholesale_price[]" id="item_kit_wholesale_price-' +
                        count + '" autocomplete="off" value="' + (item['wholesale_price'] ?? 0) +
                        '"></td>' +

                        '<td style="display: none;"><input  type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                        count + '" autocomplete="off" value="' + item['warehouse_id'] + '"></td>' +

                        '<td style="text-align:center"><input type="text" class="form-control item_kit_amount" name="item_kit_amount[]" id="item_kit_amount-' +
                        count + '" readonly></td>' +
                        '<td class="text-center"><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
                        '</tr>';
                    $("#showitem123").append(newRow);

                }
            }


            $(document).on('click', '.remove_item_btn', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                $('#showitem123 tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
                initializeTypeahead();
            });

            $(document).on('change', '.productname', function() {
                let itemCode = $(this).val();
                updateItemName(itemCode);
            });



            initializeTypeahead(count);
            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                let totalTax = 0;
                for (let i = 0; i < (count + 1); i++) {
                    var qty = parseInt($('#item_kit_quantity-' + i).val() || 0);
                    var item_name = $('#item_kit_name-' + i).val() || 0;
                    var sel = $('#focsel-' + i).val() || 0;
                    let price = parseInt($('#item_kit_retail_price-' + i).val() || 0);

                    $("#item_kit_amount-" + i).text(price * qty);
                    console.log($("#item_kit_amount-" + i).val(price * qty));


                    total += price * qty;

                }
                let taxt = total * 0.05;
                taxt = Math.ceil(taxt);
                let total_total = total - totalTax;
                $("#invoiceyoghtml").val(total);

                $('#total_total').val('');
            });


            function calculateTotal() {
                let invoiceyoghtml = parseFloat($('#invoiceyoghtml').val()) || 0;
                let total_discount = parseFloat($('#total_discount').val()) || 0;
                let total_total = invoiceyoghtml + total_discount;
                $('#total_total').val(total_total);
            }

            $(document).ready(function() {
                $('#total_discount').on('input', calculateTotal);
                calculateTotal(); // Initial calculation if needed
            });


            $(document).ready(function() {
                $('#item_kit_show').change(function() {
                    if ($(this).is(':checked')) {
                        $('#item_kit_add').show();
                        $('#retail_price_div').hide();
                        $('#buy_price_div').hide();
                        $('#retail_price_div input, #buy_price_div input').val('');
                    } else {
                        $('#item_kit_add').hide();
                        $('#retail_price_div').show();
                        $('#buy_price_div').show();
                        $('#item_kit_add input').val('');
                    }
                });

                // Trigger the change event on page load to set the initial state
                $('#item_kit_show').trigger('change');
            });

        });
    </script>
