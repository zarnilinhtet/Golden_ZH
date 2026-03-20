<!DOCTYPE html>
<HTML>


<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap400.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap502.css') }}">
    <script src="{{ asset('backend/js/moment2103.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        /* Styling the flip-flop button */
        .flip-flop-btn {

            font-size: 16px;
            background-color: #4CAF50;
            /* Green background */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            /* Remove underline */
            display: inline-block;
        }

        .flip-flop-btn.Ks {
            background-color: #4CAF50;
            /* Color for 'ks' state */
        }

        .flip-flop-btn.percent {
            background-color: #2196F3;
            /* Color for '%' state */
        }
    </style>
    <style>
        input {
            position: relative;
            width: 150px;
            height: 40px;
            color: white;
        }

        input:before {
            position: absolute;
            top: 6px;
            left: 6px;
            content: attr(data-date);
            display: inline-block;
            color: black;
        }

        input::-webkit-datetime-edit,
        input::-webkit-inner-spin-button,
        input::-webkit-clear-button {
            display: none;
        }

        input::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 6px;
            right: 0;
            color: black;
            opacity: 1;
        }
    </style>

</head>

<body>

    <div class="container-fluid " id="content">

        <nav class="navbar navbar-light bg-light justify-content-between">
            <h1 class="mx-4">POS</h1>
            @if (session('success'))
                <h4 class="text-success">{{ session('success') }}</h4>
            @endif
            @if (session('delete'))
                <h4 class="text-danger">{{ session('delete') }}</h4>
            @endif

            @php
                $userPermissions = [];
                if (auth()->user()->permission) {
                    $decodedPermissions = json_decode(auth()->user()->permission, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $userPermissions = $decodedPermissions;
                    }
                }
            @endphp
            @if (in_array('Suspend', $userPermissions) || auth()->user()->is_admin == '1')
                <form class="form-inline">

                    {{-- <button class="my-2 btn btn-outline-success my-sm-0" type="submit">Search</button> --}}
                    <div class="row">
                        {{-- <div class="col">
                        <a href="{{ url('sale_return_register') }}" type="button" class="mr-auto btn btn-primary ">
                            Sale Return</a>
                    </div> --}}

                        <div class="col">
                            <button type="button" class="mx-2 btn btn-primary" data-toggle="modal"
                                data-target="#modal-xl">
                                Suspended
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </nav>
        <div class="modal fade" id="modal-xl">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Suspended List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Your HTML code -->
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>POS No.</th>
                                        <th>Patient Name</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = ' 1';
                                    @endphp
                                    @foreach ($suspends as $suspend)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $suspend->invoice_no }}</td>
                                            <td>{{ $suspend->customer_name ?? 'N/A' }}</td>
                                            <td>{{ $suspend->total }}</td>
                                            <td>{{ date('Y-m-d', strtotime($suspend->created_at)) }}
                                            </td>
                                            <td>
                                                <a href="{{ url('invoice_delete', $suspend->id) }}"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this Suspend ?')">
                                                    Delete</a>
                                                <a href="{{ url('invoice_edit', $suspend->id) }}"
                                                    class="btn btn-primary">Unsuspend</a>
                                            </td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" id="myForm" action="{{ url('invoice_register') }}" enctype="multipart/form-data">
            @csrf

            <div class="row mx-3 ">

                <div class="mt-4 row">
                    <div class="col-md-3">
                        <label for="invoice_no" style="font-weight:bolder">Invoice Number</label>
                        <input type="text" id="invoice_no" class="form-control" name="invoice_no"
                            value="POS-{{ $invoice_no }}">
                        <input type="hidden" id="invoice_no_latest" class="form-control" name=""
                            value="{{ $invoices }}">
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_date" style="font-weight:bolder">Date</label>
                        <input type="date" name="invoice_date" class="form-control" max="<?php echo date('Y-m-d'); ?>"
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3" style="display:none">
                        <label for="overdue_date" class=" caption"
                            style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>
                        <input type="date" name="overdue_date" id="overdue_date" class="form-control round"
                            autocomplete="off" min="<?= date('Y-m-d') ?>">
                    </div>
                    <input type="hidden" name="quote_date" value="{{ auth()->user()->name }}">
                    <div class="col-md-3 ">
                        <label for="payment_method" style="font-weight:bolder">{{ trans('Payment Methods') }}</label>
                        <select class="form-control round mb-4" aria-label="Default select example"
                            name="payment_method" required>
                            {{-- <option selected disabled>Select Payment Methods</option> --}}
                            <option value="Cash">Cash</option>
                            <option value="KPay">KPay</option>
                            <option value="Wave Pay">Wave Pay</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <input type="hidden" name="quote_category" id="quote_category" value="POS">
                </div>
                <div class="content-wrapper">
                    <div class="content-body">
                        <div class="">
                            <div class="card-content">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="row">
                                            <div class="frmSearch col-sm-3">
                                                <span style="font-weight:bolder">
                                                    <label for="cst"
                                                        class="caption">{{ trans('Search  Patient Name & Phone No.') }}</label>
                                                </span>
                                                <div class="form-group d-flex">
                                                    <input type="text" id="customer" name="customer"
                                                        class="mr-2 form-control round" autocomplete="off"
                                                        placeholder="Search.....">
                                                    &nbsp;&nbsp;&nbsp; <button type="submit" class="btn btn-primary"
                                                        id="customer_search">Add</button>
                                                </div>
                                                <div id="customer-box-result"></div>
                                            </div>

                                            <div class="col-sm-3">

                                                <div class="form-group"> <label for="" class="text-danger"
                                                        style="font-weight: bold">Haven't
                                                        Patient? Register
                                                        Here</label> <br>
                                                    <button type="button" data-toggle="modal"
                                                        data-target="#modal-lg" class="btn btn-secondary">Patient
                                                        Register</button>
                                                </div>
                                            </div>

                                            @if (Auth::user()->type == '0' || Auth::user()->is_admin == '1')


                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="location" style="font-weight:bolder">Choose
                                                            Location</label>
                                                        <select name="location" id="location"
                                                            class="form-control mb-4" required>

                                                            @foreach ($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->id }}">
                                                                    {{ $warehouse->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="frmSearch col-sm-6" style="display: none">
                                                    <label for="location" style="font-weight:bolder">Choose
                                                        Location</label>
                                                    <select name="location" id="location" class="form-control mb-4"
                                                        required>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                @if ($warehouse->id == auth::user()->level) selected @endif>
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="doctor" style="font-weight:bolder">Doctor
                                                        Name <span class="text-danger fw-bold">*</span></label>
                                                    <select name="doctor_id" id="doctor_id" class="form-control"
                                                        required>
                                                        <option value="">Select Doctor</option>
                                                        @foreach ($doctors as $doctor)
                                                            <option data-branch="{{ $doctor->branch }}"
                                                                value="{{ $doctor->id }}">
                                                                {{ $doctor->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if (session('error_doctor'))
                                                        <strong
                                                            class="text-danger">{{ session('error_doctor') }}</strong>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin') --}}



                                        </div>
                                        <div class="row table-responsive mb-4" style="margin-top:1vh;">
                                            <table class="table table-bordered">
                                                <thead style="background-color:#0047aa;color:white;">
                                                    <tr class="item_header bg-gradient-directional-blue white">
                                                        <th class="text-center" style="width: 13%;">
                                                            {{ trans('Patient ID') }}
                                                        </th>
                                                        <th class="text-center" style="width: 13%;">
                                                            {{ trans('Name') }}
                                                        </th>
                                                        <th class="text-center" style="width: 14%;">
                                                            {{ trans('Phone Number') }}
                                                        </th>
                                                        <th class="text-center" style="width: 18%;">
                                                            {{ trans('Patient Type') }}
                                                        </th>
                                                        <th class="text-center" style="width: 13%;">
                                                            {{ trans('Address') }}
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="item_header bg-gradient-directional-blue white">
                                                        <td class="text-center"><input type='text'
                                                                name='patient_id' id="patient_id"
                                                                class="form-control"></td>
                                                        <td class="text-center"><input type='text'
                                                                name='customer_name' id="name"
                                                                class="form-control"></td>
                                                        <input type='hidden' name='customer_id' id="customer_id"
                                                            class="form-control">
                                                        <input type='hidden' name='status' id="status"
                                                            class="form-control" value="pos">
                                                        <td class="text-center"><input type='text' name='phno'
                                                                id="phone_no" class="form-control"></td>
                                                        <td class="text-center"><input type='text' name='type'
                                                                id="type" class="form-control"></td>
                                                        <td class="text-center"><input type='text' name='address'
                                                                class="form-control" id="address"></td>
                                                    </tr>
                                                </tbody>
                                            </table>


                                        </div>
                                        <hr>
                                        {{-- @if (auth::user()->type == '0' || auth::user()->is_admin == '1')
                                            <div class="frmSearch col-md-3 mt-4">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="cst"
                                                            class="caption">{{ trans('ChooseLocation') }}&nbsp;</label>
                                                    </span> <select name="location" id="location"
                                                        class="form-control mb-4 location" required>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>


                                            </div>
                                        @else
                                            <div class="frmSearch col-md-3 mt-4" style="display: none;">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="cst"
                                                            class="caption">{{ trans('ChooseLocation') }}&nbsp;</label>
                                                    </span> <select name="location" id="location"
                                                        class="form-control mb-4 location" required>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                @if ($warehouse->id == auth::user()->level) selected @endif>


                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>


                                            </div>

                                        @endif --}}
                                        <div class="frmSearch col-md-3 mt-4">
                                            <div class="frmSearch col-sm-12">
                                                <span style="font-weight:bolder">
                                                    <label for="cst"
                                                        class="caption">{{ trans('Search Item Name ') }}&nbsp;</label>
                                                </span>
                                                <input type="text" class="form-control productname typeahead"
                                                    name="itemname" id='productname' autocomplete="off"
                                                    placeholder="Search Item Name ">
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

                                        <div class="frmSearch col-md-3 mt-4">
                                            <div class="frmSearch col-sm-12">
                                                <span style="font-weight:bolder">
                                                    <label for="cst"
                                                        class="caption">{{ trans('Search Item Barcode') }}&nbsp;</label>
                                                </span>
                                                <input type="text"
                                                    class="form-control productname typeahead barcode-input"
                                                    name="barcode" id='barcode' autocomplete="off"
                                                    placeholder="Search Item Barcode ">
                                                <div id="customer-box-result"></div>
                                            </div>



                                        </div>

                                        <div class="frmSearch col-md-3 mt-4">
                                            <label for="payment"
                                                style="font-weight:bolder">{{ trans('Sale Price Category') }}
                                            </label>
                                            <select class="form-control round mb-4 "
                                                aria-label="Default select example" name="sale_price_category"
                                                id="sale_price_category" required>

                                                {{-- <option value="Default" selected>Default</option>
                                                <option value="Whole Sale">Whole Sale</option> --}}
                                                <option value="Retail" selected>Retail</option>
                                            </select>
                                        </div>

                                        <div class="row table-responsive " style="margin-top:1vh;">
                                            <!-- <table class="table-responsive tfr my_stripe"> -->
                                            <table class="table table-bordered">
                                                <thead style="background-color:#0047aa;color:white;">
                                                    <tr class="item_header bg-gradient-directional-blue white"
                                                        style="margin-bottom:10px;">
                                                        <th width="5%" class="text-center">{{ trans('No.') }}
                                                        </th>
                                                        <th width="25%" class="text-center">
                                                            {{ trans('Item Name') }}
                                                        </th>
                                                        <!-- <th width="23%" class="text-center">
                                                            {{ trans('Descriptions') }}
                                                        </th> -->
                                                        <th width="8%" class="text-center">
                                                            {{ trans('Qty') }}
                                                        </th>
                                                        <th width="10%" class="text-center">{{ trans('Unit') }}
                                                        </th>


                                                        <th width="9%" class="text-center">
                                                            {{ trans('Retail Price') }}
                                                        </th>

                                                        <th width="10%" class="text-center">
                                                            {{ trans('Discounts') }}
                                                        </th>

                                                        <th width="9%" class="text-center">
                                                            {{ trans('Expiry') }}
                                                        </th>

                                                        <!-- <th width="10%" class="text-center">
                                                        {{ trans('Discounts (%)') }}
                                                    </th> -->

                                                        <th width="11%" class="text-center">{{ trans('Amount') }}
                                                            ({{ config('currency.symbol') }})
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody id="showitem123">
                                                    <tr>
                                                        <input type="hidden" class="form-control barcode typeahead"
                                                            name="barcode[]" value="{{ old('barcode') }}"
                                                            placeholder="{{ trans('Enter BarCode') }}" id='barcode-0'
                                                            autocomplete="off">

                                                        <td class="text-center" id="count">1</td>
                                                        <td><input type="text"
                                                                class="form-control productname item_name typeahead"
                                                                name="part_number[]" value="{{ old('part_number') }}"
                                                                placeholder="{{ trans('Enter Part Number') }}"
                                                                id='item_name-0' autocomplete="off">
                                                            <input type="hidden"
                                                                class="form-control result_item_name typeahead"
                                                                name="result_item_name[]"
                                                                value="{{ old('result_item_name') }}"
                                                                id='result_item_name-0' autocomplete="off">
                                                            <input type="hidden"
                                                                class="form-control item_id typeahead"
                                                                name="item_id[]" value="{{ old('item_id') }}"
                                                                id='item_id-0' autocomplete="off">
                                                            <input type="hidden"
                                                                class="form-control result_id typeahead"
                                                                name="result_id[]" value="{{ old('result_id') }}"
                                                                id='result_id-0' autocomplete="off">
                                                        </td>


                                                        <input type="hidden"
                                                            class="form-control description typeahead"
                                                            value="{{ old('part_description') }}"
                                                            name="part_description[]"
                                                            placeholder="{{ trans('') }}" id='description-0'
                                                            autocomplete="off">

                                                        <!-- <td><input type="text"
                                                                class="form-control description typeahead"
                                                                value="{{ old('part_description') }}"
                                                                name="part_description[]"
                                                                placeholder="{{ trans('') }}" id='description-0'
                                                                autocomplete="off"></td> -->

                                                        <td><input type="text" class="form-control req amnt"
                                                                name="product_qty[]" id="amount-0"
                                                                autocomplete="off" value="1"><input
                                                                type="hidden" id="alert-0" value=""
                                                                name="alert[]"></td>
                                                        <td>
                                                            <select class="form-control unit" name="item_unit[]"
                                                                id="item_unit-0">
                                                        </td>

                                                        <td><input type="text" class="form-control retail_price"
                                                                name="retail_price[]" id="retail_price-0"
                                                                autocomplete="off" value="0">
                                                        </td>

                                                        <td>
                                                            <div class="d-flex align-items-between ">
                                                                <input type="text"
                                                                    class="form-control item_discount col-md-8"
                                                                    name="item_discount[]" id="item_discount-0"
                                                                    value="0" autocomplete="off"
                                                                    value="{{ old('item_discount') }}">
                                                                <a href="javascript:void(0)" id="flipFlopButton-0"
                                                                    class="flip-flop-btn form-control Ks col-md-4"
                                                                    style="text-decoration: none; margin-left: 10px;">Ks</a><input
                                                                    type="hidden" id="valueIndicator-0"
                                                                    name="valueIndicator[]" value="Ks">


                                                            </div>
                                                        </td>




                                                        <td><input type="text" class="form-control exp_date "
                                                                name="exp_date[]" id="exp_date-0" autocomplete="off">
                                                        </td>
                                                        <td style="display: none;"><input type="text"
                                                                class="form-control warehouse " name="warehouse[]"
                                                                id="warehouse-0" autocomplete="off">
                                                        </td>
                                                        <!-- <td><input type="text" class="form-control vat " name="discount[]" id="vat-0" autocomplete="off" value="{{ old('discount') }}">
                                                    </td> -->

                                                        <td style="text-align:center">
                                                            <span class='ttlText' id="foc-0"></span>
                                                            <span
                                                                class="currenty">{{ config('currency.symbol') }}</span>
                                                            <strong>
                                                                <span class='ttlText' id="result-0"></span>
                                                            </strong>
                                                        </td>
                                                        <input type="hidden" class="form-control vat "
                                                            name="product_tax[]" id="vat-0" value="0">
                                                        <input type="hidden" name="total_tax[]" id="taxa-0"
                                                            value="0">
                                                        <input type="hidden" name="total_discount[]" id="disca-0"
                                                            value="0">
                                                        <input type="hidden" class="ttInput"
                                                            name="product_subtotal[]" id="total-0" value="0">
                                                        <input type="hidden" class="pdIn" name="product_id[]"
                                                            id="pid-0" value="0">
                                                        <input type="hidden" attr-org="" name="unit[]"
                                                            id="unit-0" value="">
                                                        <input type="hidden" name="unit_m[]" id="unit_m-0"
                                                            value="1">
                                                        <input type="hidden" name="code[]" id="hsn-0"
                                                            value="">
                                                        <input type="hidden" name="serial[]" id="serial-0"
                                                            value="">
                                                        {{-- <td></td> --}}
                                                    </tr>
                                                </tbody>

                                            </table>

                                            <table class="mt-3">
                                                <tbody id="showitem">




                                                    <tr style="display: table-row;">
                                                        <td></td>
                                                        <td colspan="">

                                                        </td>



                                                    </tr>


                                                    <tr class="last-item-row sub_c">
                                                        <td></td>
                                                        <td class="add-row">
                                                            {{-- <button type="button" class="btn btn-success"
                                                                id="addproduct" onclick=addproduct();
                                                                style="margin-top:20px;margin-bottom:20px;">
                                                                <i class="fa fa-plus-square"></i>
                                                                {{ trans('Add row') }}
                                                            </button> --}}
                                                            <button type="button" class="btn btn-primary"
                                                                id="calculate">
                                                                Calculate
                                                            </button>

                                                            @if (in_array('Item', $userPermissions) || auth()->user()->is_admin == '1')
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
                                                        <td colspan="2">
                                                            @if (isset($employees[0]))
                                                                {{ trans('general.employee') }}
                                                                <select name="user_id"
                                                                    class="selectpicker form-control">
                                                                    <option value="{{ $logged_in_user->id }}">
                                                                        {{ $logged_in_user->first_name }}
                                                                    </option>
                                                                    @foreach ($employees as $employee)
                                                                        <option value="{{ $employee->id }}">
                                                                            {{ $employee->first_name }}
                                                                            {{ $employee->last_name }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td>

                                                        </td>
                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Sub Total
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="sub_total" class="form-control"
                                                                id="invoiceyoghtml" readonly
                                                                style="background-color: #E9ECEF">

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Item Discount
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="commercial_text"
                                                                class="form-control" id="commercial_text" readonly>

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Overall Discount
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="discount" class="form-control"
                                                                id="total_discount">

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Total
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="total" class="form-control"
                                                                id="total_total" readonly
                                                                style="background-color: #E9ECEF">

                                                        </td>

                                                    </tr>



                                                    <tr class="sub_c" style="display: none;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Deposit
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2"><input type="text"
                                                                name="deposit" class="form-control" id="deposit"
                                                                onchange="paidFunction()">

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: none;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Change Due
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2"><input type="text"
                                                                name="balance" class="form-control" id="balance"
                                                                readonly="">

                                                        </td>
                                                    </tr>

                                                    <tr class="sub_c " style="display: table-row;">
                                                        <td colspan="12"> <label for="remark">Remark</label>
                                                            <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                                        </td>
                                                    </tr>
                                                    <tr class="sub_c " style="display: table-row;">


                                                        <td align="right" colspan="9">
                                                            @if (in_array('Suspend', $userPermissions) || auth()->user()->is_admin == '1')
                                                                <button id="suspend" class="mt-3 btn btn-primary"
                                                                    type="submit">Suspend</button>
                                                            @endif
                                                            <button id="submitButton" class="mt-3 btn btn-primary"
                                                                type="submit">Save</button>


                                                            <a href="{{ url('pos_manage') }}" type="submit"
                                                                class="mt-3 btn btn-danger">Cancel
                                                            </a>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>

        </form>
        <script>
            $(document).ready(function() {
                $('#location').on('change', function() {


                    const selectedBranch = $(this).val();
                    const doctorSelect = document.getElementById('doctor_id');
                    // Clear the doctor select options
                    doctorSelect.innerHTML = '<option value="">Select Doctor</option>';

                    // Filter and add the doctors based on the selected branch
                    @foreach ($doctors as $doctor)
                        if (selectedBranch === '{{ $doctor->branch }}') {
                            const option = document.createElement('option');
                            option.value = '{{ $doctor->id }}';
                            option.textContent = '{{ $doctor->name }}';
                            doctorSelect.appendChild(option);
                        }
                    @endforeach
                    if (doctorSelect.options.length < 1) { // More than the default "Select Doctor" option
                        doctorSelect.setAttribute('required', 'required');
                    }
                });
                $('#location').trigger('change');

            });
        </script>
        <script>
            document.getElementById("suspend").addEventListener("click", function() {
                setStatus("suspend");
            });

            document.getElementById("submitButton").addEventListener("click", function() {
                setStatus("pos");
            });

            function setStatus(status) {
                document.getElementById("status").value = status;
                document.getElementById("myForm").submit();
            }
        </script>
        <script>
            $(document).ready(function() {
                let count = 0;
                const flipFlopButton = document.getElementById('flipFlopButton-0');
                const valueIndicator = document.getElementById('valueIndicator-0');

                flipFlopButton.addEventListener('click', () => {
                    // Toggle button text and class based on its current state
                    if (flipFlopButton.classList.contains('Ks')) {
                        flipFlopButton.textContent = '%';
                        flipFlopButton.classList.remove('Ks');
                        flipFlopButton.classList.add('percent');
                        valueIndicator.value = '%'; // Assign value to hidden input
                    } else {
                        flipFlopButton.textContent = 'Ks';
                        flipFlopButton.classList.remove('percent');
                        flipFlopButton.classList.add('Ks');
                        valueIndicator.value = 'Ks'; // Assign value to hidden input
                    }
                });
                // Search item name suggestion (get item name from db)
                function initializeTypeahead() {
                    $('#productname').typeahead({
                        source: function(query, process) {

                            var Selectedlocation = $('#location').val();
                            return $.ajax({
                                url: "{{ route('autocomplete.part-code-invoice') }}",
                                method: 'POST',
                                data: {
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
                        },
                        autoSelect: true
                    });

                    // $('#barcode').typeahead({
                    //     source: function(query, process) {
                    //         var Selectedlocation = $('#location').val();
                    //         return $.ajax({
                    //             url: "{{ route('autocomplete.barcode-invoice') }}",
                    //             method: 'POST',
                    //             data: {
                    //                 query: query,
                    //                 location: Selectedlocation,
                    //             },
                    //             dataType: 'json',
                    //             success: function(data) {
                    //                 console.log(data);
                    //                 process(data);
                    //             },
                    //             error: function(error) {
                    //                 console.error(error);
                    //             }
                    //         });
                    //     },

                    // });
                    $('.barcode-input').typeahead({
                        source: function(query, process) {
                            var Selectedlocation = $('#location').val();
                            return $.ajax({
                                url: "{{ route('autocomplete.barcode-invoice') }}",
                                method: 'POST',
                                data: {
                                    query: query,
                                    location: Selectedlocation,
                                },
                                dataType: 'json',
                                success: function(data) {
                                    const formattedBarcodes = data.map(function(barcode) {
                                        return String(
                                            barcode);
                                    });
                                    process(formattedBarcodes);
                                },
                                error: function(error) {
                                    console.error(error);
                                }
                            });
                        },
                        displayText: function(item) {
                            return item;
                        },
                        afterSelect: function(item) {

                            $('.barcode_input').val(
                                item);
                        },
                        autoSelect: true
                    });
                }



                function updateItemName(itemname, row, description, product_code, cuz_name, productname, expired_date) {
                    // Check if the table body is empty
                    var Selectedlocation = $('#location').val();

                    if ($("#item_name-0").val() === "") {
                        let cuz_name = $("#type").val();
                        var $barcodeInput = $('.barcode-input');
                        var item_barcode = $barcodeInput.val();
                        if (item_barcode.length > 1) {
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('get.barcode.data-invoice') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    barcode: itemname,
                                    location: Selectedlocation,
                                },
                                success: function(data) {
                                    if (data['variations'].length > 0) {
                                        const variation = data['variations'][0];

                                        const itemName = productname || data['item']['item_name'] ||
                                            'No Item Name';
                                        const description = variation['descriptions'] || '';
                                        const productCode = variation['product_code'] || '';
                                        const expiredDate = variation['expired_date'] || '';

                                        const displayText = itemName +
                                            (description || productCode ? ' (' + description : '') +
                                            (description && productCode ? ' - ' : '') +
                                            (productCode ? productCode : '') +
                                            (description || productCode ? ')' : '') +
                                            (expiredDate ? ' (' + expiredDate + ' )' : '');

                                        $("#item_name-0").val(displayText);
                                        $("#description-0").val(variation['descriptions']);
                                        $("#exp_date-0").val(variation['expired_date']);
                                        $("#barcode-0").val(variation['variations_barcode']);
                                        $("#item_unit-0").val(variation['item_unit']);
                                        $("#retail_price-0").val(variation['retail1']);
                                        $("#warehouse-0").val(data['item']['warehouse_id']);
                                        $("#result_item_name-0").val(data['item']['item_name']);
                                        $("#item_id-0").val(data['item']['id']);
                                        $("#result_id-0").val(variation['id']);
                                        $("#warehouse-0").val(data['item']['warehouse_id']);
                                        $("#barcode").val('');
                                        $("#productname").val('');
                                        // Handle unit options
                                        let unitdata = [];
                                        if (variation['name2'] != null && variation['name3'] != null) {
                                            unitdata = [variation['name1'], variation['name2'], variation[
                                                'name3']];
                                        } else if (variation['name2'] != null) {
                                            unitdata = [variation['name1'], variation['name2']];
                                        } else if (variation['name3'] != null) {
                                            unitdata = [variation['name1'], variation['name3']];
                                        } else {
                                            unitdata = [variation['name1']];
                                        }

                                        $('#item_unit-0').empty();
                                        $.each(unitdata, function(index, item) {
                                            let option = $('<option></option>').val(item).text(
                                                item);
                                            $('#item_unit-0').append(option);
                                        });
                                        $('#item_unit-0').trigger('change');

                                        if (parseFloat(variation.reorder_level_stock) >= parseFloat(
                                                variation.quantity)) {
                                            alert(variation.quantity + " quantity!");
                                        }

                                        $('#item_unit-0').on('change', function() {
                                            let selectedUnit = $(this).val();
                                            $.ajax({
                                                type: 'POST',
                                                url: "{{ route('unit_search_withName') }}",
                                                data: {
                                                    _token: "{{ csrf_token() }}",
                                                    unit: selectedUnit,
                                                    item_name: data['item']['item_name'],
                                                    description: variation['descriptions'],
                                                    product_code: variation['product_code'],
                                                    result_expired_date: variation[
                                                        'expired_date'],
                                                },
                                                success: function(response) {

                                                    $("#retail_price-0").val(response
                                                        .retail);
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error(xhr.responseText);
                                                }
                                            });
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }

                            });
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('get.part.data-invoice') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    itemname: itemname,
                                    location: Selectedlocation,
                                    description: description,
                                    product_code: product_code,
                                    expired_date: expired_date,
                                },
                                success: function(data) {
                                    if (data['variations'].length > 0) {
                                        const variation = data['variations'][0];
                                        $("#item_name-0").val(productname);
                                        $("#description-0").val(variation['descriptions']);
                                        $("#exp_date-0").val(variation['expired_date']);
                                        $("#barcode-0").val(variation['variations_barcode']);
                                        $("#item_unit-0").val(variation['item_unit']);
                                        $("#retail_price-0").val(variation['retail1']);
                                        $("#warehouse-0").val(data['item']['warehouse_id']);
                                        $("#result_item_name-0").val(data['item']['item_name']);
                                        $("#item_id-0").val(data['item']['id']);
                                        $("#result_id-0").val(variation['id']);
                                        $("#warehouse-0").val(data['item']['warehouse_id']);
                                        // Handle unit options
                                        let unitdata = [];
                                        if (variation['name2'] != null && variation['name3'] != null) {
                                            unitdata = [variation['name1'], variation['name2'], variation[
                                                'name3']];
                                        } else if (variation['name2'] != null) {
                                            unitdata = [variation['name1'], variation['name2']];
                                        } else if (variation['name3'] != null) {
                                            unitdata = [variation['name1'], variation['name3']];
                                        } else {
                                            unitdata = [variation['name1']];
                                        }

                                        $('#item_unit-0').empty();
                                        $.each(unitdata, function(index, item) {
                                            let option = $('<option></option>').val(item).text(
                                                item);
                                            $('#item_unit-0').append(option);
                                        });
                                        $('#item_unit-0').trigger('change');

                                        if (parseFloat(variation.reorder_level_stock) >= parseFloat(
                                                variation.quantity)) {
                                            alert(variation.quantity + " quantity!");
                                        }

                                        $('#item_unit-0').on('change', function() {
                                            let selectedUnit = $(this).val();
                                            $.ajax({
                                                type: 'POST',
                                                url: "{{ route('unit_search_withName') }}",
                                                data: {
                                                    _token: "{{ csrf_token() }}",
                                                    unit: selectedUnit,
                                                    item_name: data['item']['item_name'],
                                                    description: variation['descriptions'],
                                                    product_code: variation['product_code'],
                                                    result_expired_date: variation[
                                                        'expired_date'],
                                                },
                                                success: function(response) {

                                                    $("#retail_price-0").val(response
                                                        .retail);
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error(xhr.responseText);
                                                }
                                            });
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        }




                    } else {

                        if ($("#item_name-0").val() === $("#productname").val() || $("#barcode-0").val() === $(
                                "#barcode").val()) {

                            var existingRow = $("#amount-0");

                            var currentQuantity = parseInt(existingRow.val());
                            existingRow.val(currentQuantity + 1);
                            $("#barcode").val('');
                            $("#productname").val('');
                        } else {

                            var $barcodeInput = $('.barcode-input');
                            var item_barcode = $barcodeInput.val();
                            if (item_barcode.length > 1) {
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('get.barcode.data-invoice') }}",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        barcode: itemname,
                                        location: Selectedlocation,
                                    },
                                    success: function(data) {
                                        var barcode = $("#barcode")
                                            .val();

                                        if (parseFloat(data.reorder_level_stock) >= parseFloat(data
                                                .quantity)) {
                                            alert(data.quantity + " quantity!");
                                        }

                                        console.log("Barcode:", barcode);

                                        data.variations.forEach(function(variation) {
                                            addNewRow(data.item, variation, "",
                                                barcode);
                                        });

                                        $("#barcode").val('');
                                        $("#productname").val('');
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                    }
                                });
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('get.part.data-invoice') }}",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        itemname: itemname,
                                        location: Selectedlocation,
                                        description: description,
                                        product_code: product_code,
                                        expired_date: expired_date,
                                    },
                                    success: function(data) {
                                        if (parseFloat(data.reorder_level_stock) >= parseFloat(data
                                                .quantity)) {
                                            alert(data.quantity + " quantity!");
                                        }
                                        data.variations.forEach(function(variation) {
                                            addNewRow(data.item, variation, productname);
                                        });

                                        $("#barcode").val();
                                        $("#productname").val();
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                    }
                                });

                            }


                        }


                    }

                    $("#barcode").val();
                    $("#productname").val();
                }
                initializeTypeahead();


                function addNewRow(item, variation, productname, barcode) {
                    let cuz_name = $("#type").val();
                    console.log("Barcode:", barcode);
                    if (barcode && barcode.length > 1) {
                        existingRow = $("#showitem123 input.productname[value='" + barcode + "']").closest('tr');
                    } else {
                        existingRow = $("#showitem123 input.productname[value='" + productname + "']").closest('tr');
                    }

                    if (existingRow.length > 0) {
                        let qtyInput = existingRow.find('.req.amnt');
                        let currentQty = parseInt(qtyInput.val()) || 0;
                        qtyInput.val(currentQty + 1);
                    } else {

                        var $barcodeInput = $('.barcode-input');
                        var item_barcode = $barcodeInput.val();

                        if (item_barcode.length > 1) {
                            var displayName = item.item_name +
                                ' (' +
                                (variation.descriptions || '') +
                                (variation.descriptions && variation.product_code ? ' - ' : '') +
                                (variation.product_code || '') +
                                ')' +
                                (variation.expired_date ? ' (' + variation.expired_date + ' )' : '');

                        } else {
                            var displayName = productname || "";
                        }
                        count++;
                        let rowCount = $("#showitem123 tr").length;

                        if ($(".item_name").length >= 40) {
                            alert("You can only add up to 40 rows.");
                            return;
                        }

                        let newRow = $('<tr>' +
                            '<td class="text-center">' + (rowCount + 1) + '</td>' +
                            '<td style="display:none"><input type="hidden" class="form-control barcode typeahead" name="barcode[]" id="barcode-' +
                            count + '" autocomplete="off" value="' + variation['variations_barcode'] + '"></td>' +
                            '<td><input type="text" class="form-control productname item_name typeahead" name="part_number[]" id="item_name-' +
                            count + '" autocomplete="off" value="' + displayName +
                            '"><input type="hidden" class="form-control result_item_name typeahead" name="result_item_name[]" id="result_item_name-' +
                            count + '" autocomplete="off" value="' + item['item_name'] +
                            '"><input type="hidden" class="form-control item_id typeahead" name="item_id[]" id="item_id-' +
                            count + '" autocomplete="off" value="' + item['id'] +
                            '"><input type="hidden" class="form-control result_id typeahead" name="result_id[]" id="result_id-' +
                            count + '" autocomplete="off" value="' + variation['id'] + '"></td>' +
                            '<input type="hidden" class="form-control description typeahead" name="part_description[]" required id="description-' +
                            count + '" autocomplete="off" value="' + variation['descriptions'] + '">' +
                            '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                            count +
                            '" autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                            '<td><select class="form-control unit" name="item_unit[]" id="item_unit-' +
                            count + '" autocomplete="off" ></select></td>' +
                            '<td><input type="text" class="form-control retail_price" name="retail_price[]" id="retail_price-' +
                            count + '" autocomplete="off" value=""></td>' +
                            '<td><div class="d-flex align-items-between"><input type="text" class="form-control col-md-8 item_discount" name="item_discount[]" value="0" id="item_discount-' +
                            count +
                            '"   autocomplete="off"> <a href="javascript:void(0)" id="flipFlopButton-' +
                            count +
                            '" class="flip-flop-btn form-control Ks col-md-4"style="text-decoration: none; margin-left: 10px;">Ks</a><input type="hidden" id="valueIndicator-' +
                            count + '" name="valueIndicator[]" value="Ks"></div></td>' +
                            '<td><input type="text" class="form-control exp_date" name="exp_date[]" id="exp_date-' +
                            count + '" autocomplete="off" value="' + (variation['expired_date'] || '') + '"></td>' +
                            '<td style="display: none;"><input type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                            count + '" autocomplete="off" value="' + item['warehouse_id'] + '"></td>' +
                            '<td style="text-align:center"><span class="currenty"></span><strong><span id="result-' +
                            count + '">0</span></strong></td>' +
                            '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                            '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                            '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' +
                            count + '" value="0">' +
                            '<input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">' +
                            '<input type="hidden" attr-org="" name="unit[]" id="unit-0" value="">' +
                            '<input type="hidden" name="unit_m[]" id="unit_m-0" value="1">' +
                            '<input type="hidden" name="code[]" id="hsn-0" value="">' +
                            '<input type="hidden" name="serial[]" id="serial-0" value="">' +
                            '<td style="width: 10%"><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
                            '</tr>');
                        $("#showitem123").append(newRow);
                        let flipFlopButton = document.getElementById('flipFlopButton-' + count);
                        let valueIndicator = document.getElementById('valueIndicator-' + count);

                        flipFlopButton.addEventListener('click', () => {
                            // Toggle button text and class based on its current state
                            if (flipFlopButton.classList.contains('Ks')) {
                                flipFlopButton.textContent = '%';
                                flipFlopButton.classList.remove('Ks');
                                flipFlopButton.classList.add('percent');
                                valueIndicator.value = '%'; // Assign value to hidden input
                            } else {
                                flipFlopButton.textContent = 'Ks';
                                flipFlopButton.classList.remove('percent');
                                flipFlopButton.classList.add('Ks');
                                valueIndicator.value = 'Ks'; // Assign value to hidden input
                            }
                        });

                        $('#item_unit-' + count).on('change', function() {
                            let selectedUnit = $(this).val();

                            $.ajax({
                                type: 'POST',
                                url: "{{ route('unit_search_withName') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    unit: selectedUnit,
                                    item_name: item['item_name'],
                                    description: variation['descriptions'],
                                    product_code: variation['product_code'],
                                    result_expired_date: variation[
                                        'expired_date'],
                                },
                                success: function(data) {
                                    $("#retail_price-" + count).val(data.retail);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });

                        });

                        $('#item_unit-' + count).empty();

                        // if(item['name2'] != null && item['name3'] != null){

                        let unitdata = [variation['name1'], variation['name2'], variation['name3']];



                        $.each(unitdata, function(index, unit) {
                            let option = unit !== null ? $('<option></option>').val(unit).text(unit) : null;
                            if (option) {
                                // $('#yourSelectElementId').append(option);
                                $('#item_unit-' + count).append(option);
                            }


                        });

                        $('#item_unit-' + count).trigger('change');



                    }
                }




                $(document).on('click', '.remove_item_btn', function(e) {
                    e.preventDefault();
                    let row_item = $(this).parent().parent();
                    $(row_item).remove();

                    // Update row numbers
                    $('#showitem123 tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });

                    initializeTypeaheads();
                });

                // $(document).on('click', '.typeahead .dropdown-item', function(e) {
                //     e.preventDefault();
                //     if ($("#customer").val()) {

                //     } else {
                //         const itemCode = $(this).text().trim();
                //         const row = $(this).closest('tr');
                //         updateItemName(itemCode, row);
                //         $('#productname').val('');
                //         $('#barcode').val('');
                //     }
                // });
                $(document).on('keydown', '#barcode', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();

                        let itemname = $(this).val();
                        let row = $(this).closest('tr');
                        let cuz_name = $("#type").val();
                        updateItemName(itemname, row, "", "", cuz_name, "");
                    }
                });


                $(document).on('click', '.typeahead .dropdown-item', function(e) {
                    e.preventDefault();

                    if ($("#customer").val()) {} else {
                        const row = $(this).closest('tr');

                        const itemname = $('#result_product_name-0').val();
                        const productname = $('#productname').val();
                        const description = $('#result_descriptions-0').val();
                        const product_code = $('#result_product_code-0').val();
                        const expired_date = $('#result_expired_date-0').val();
                        let cuz_name = $("#type").val();
                        updateItemName(itemname, row, description, product_code, cuz_name, productname,
                            expired_date);
                        $('#productname').val('');
                    }
                });

                // Initialize typeahead for the first row
                initializeTypeahead(count);
                $(document).on("click", '#calculate', function(e) {
                    e.preventDefault();
                    let total = 0;
                    let totalTax = 0;
                    let total_discount = 0;
                    let salePriceCategory = $('#sale_price_category').val();
                    for (let i = 0; i < (count + 1); i++) {
                        var qty = parseFloat($('#amount-' + i).val() || 0); // Changed to parseFloat
                        var item_name = $('#productname-' + i).val() || 0;
                        var sel = $('#focsel-' + i).val() || 0;
                        let discount = 0;
                        let price = 0;

                        // Check price category and assign price accordingly
                        if (salePriceCategory === 'Default') {
                            let cuz_name = $("#type").val();
                            price = cuz_name === "Whole Sale" ? parseFloat($('#price-' + i).val() || 0) :
                                parseFloat($('#retail_price-' + i).val() || 0);
                        } else if (salePriceCategory === 'Whole Sale') {
                            price = parseFloat($('#price-' + i).val() || 0);
                        } else if (salePriceCategory === 'Retail') {
                            price = parseFloat($('#retail_price-' + i).val() || 0);
                        } else if (salePriceCategory === 'Buy Price') {
                            price = parseFloat($('#buy_price-' + i).val() || 0);
                        }

                        let taxRate = parseFloat($('#item_discount-' + i).val() || 0);
                        let indicator = $('#valueIndicator-' + i).val();

                        console.log(indicator);

                        if (!isNaN(taxRate) && taxRate > 0) {
                            if (indicator == "Ks") {
                                discount = taxRate;
                                $("#result-" + i).text((price * qty) - discount);
                            } else {
                                discount = (price * qty * taxRate) / 100;
                                $("#result-" + i).text((price * qty) - discount);
                            }
                        } else {
                            $("#result-" + i).text(price * qty);
                        }

                        total += price * qty;
                        total_discount += discount;
                        totalTax += discount;
                    }

                    let taxt = total * 0.05; // Calculate tax based on the updated total
                    taxt = Math.ceil(taxt);
                    let total_total = total - totalTax;

                    $("#invoiceyoghtml").val(total);
                    $("#commercial_text").val(totalTax);
                    $("#total_amount").val(total);

                    calculateTotal();
                });


                function paidFunction() {
                    let paid = document.getElementById("paid").value;
                    let total_p = document.getElementById("total_total").value;
                    let balance = total_p - paid;
                    $("#balance").val(balance);


                }
            });
        </script>
        <script>
            $(document).on('click', '.remove_item_btn', function(e) {
                e.preventDefault();
                let row_item = $(this).parent().parent();
                $(row_item).remove();
                count--;
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                for (let i = 0; i < (count + 1); i++) {
                    // if ($('#amount-' + i).is(":empty")) {
                    //     var price = 1;
                    // } else {
                    //     var price = parseInt($('#amount-' + i).val()); //get value from amount
                    // }

                    var qty = parseInt($('#amount-' + i).val()); //get value from amount
                    var item_name = $('#productname-' + i).val(); //get value from amount
                    var sel = $('#focsel-' + i).val(); //get value from amount



                    let price = parseInt($('#price-' + i).val()); //get vlaue from price

                    console.log("price" + price)
                    // console.log("price2"+Object.values(price2))
                    $("#result-" + i).text((price *
                        qty));

                    //  $("#price-"+ i).val(data['retail_sale']);
                    if (sel >= 1) {
                        $("#foc-" + i).text('FOC');
                        price = 0;
                        // total = 0; //total adding (amount*price)
                    }
                    if (sel < 1) {
                        $("#foc-" + i).text((price * qty));
                        //  total = total + (price * qty); //total adding (amount*price)
                    } /// set  (amount*price) to result subtotal for each product

                    total = total + (price * qty); //total adding (amount*price)

                }
                let taxt = total * 0.05;
                taxt = Math.ceil(taxt);
                let total_total = taxt + total;
                $("#invoiceyoghtml").val(total); //set  (amount*price)  per invoice  subtotal
                // $("#commercial_text").val(taxt); //commercial taxt 5% of total (sub total)
                $("#total").val(total_total); //super total
                $('#extra_discount').val('');
                $('#paid').val('');
                $('#balance').val('');
                $('#total_total').val(total_total);
                $('#total_discount').val('');
                // alert("Text:sdfgsdf"+ qty + "count is ;" + count);

            });


            function paidFunction() {

                let paid = document.getElementById("paid").value;
                let total_p = document.getElementById("total_total").value;
                let balance = total_p - paid;
                $("#balance").val(balance);
            }
        </script>







        <script>
            $(document).ready(function() {
                var path = "{{ route('customer_service_search') }}";


                $('#customer').typeahead({
                    source: function(query, process) {
                        return $.get(path, {
                            query: query
                        }, function(data) {
                            // Format the data for Typeahead
                            var formattedData = [];
                            $.each(data, function(index, customer) {
                                // Check if the query matches the name or phone number
                                if (customer.name.toLowerCase().indexOf(query
                                        .toLowerCase()) !== -1) {
                                    // If the query matches the name, show the name
                                    formattedData.push(customer.name);
                                } else if (customer.phno.indexOf(query) !== -1) {
                                    // If the query matches the phone number, show the phone number
                                    formattedData.push(customer.phno);
                                }
                            });
                            return process(formattedData);
                        });
                    }
                });




                $(document).on('click', '#customer_search', function(e) {
                    e.preventDefault();
                    let serialNumber = $("#customer").val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('customer_service_search_fill') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            model: serialNumber // Adjusted to match server-side parameter name
                        },
                        success: function(data) {
                            console.log(data);

                            $("#name").val(data['customer']['name']);
                            $("#patient_id").val(data['customer']['patient_id']);
                            $("#customer_id").val(data['customer']['id']);
                            $("#phone_no").val(data['customer']['phno']);
                            $("#type").val(data['customer']['type']);
                            $("#address").val(data['customer']['address']);
                            // Adjusted to match server-side data
                            $("#customer").val('');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>



        <script>
            $("input").on("change", function() {
                if (this.value && moment(this.value, "YYYY-MM-DD").isValid()) {
                    this.setAttribute(
                        "data-date",
                        moment(this.value, "YYYY-MM-DD").format("DD/MM/YYYY")
                    );
                } else {
                    this.setAttribute("data-date", "dd/mm/yyyy");
                }
            }).trigger("change");
        </script>
        <script>
            //Enter Key click add row
            $(document).on('keydown', '.form-control', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#addproduct').click();
                }
            });
        </script>



        <script>
            // function calculateTotal() {
            //     let subtotal = parseFloat($("#invoiceyoghtml").val()) || 0;
            //     let discount = parseFloat($("#total_discount").val()) || 0;
            //     let total = subtotal - discount;
            //     $("#total_total").val(total);
            //     $("#deposit").val(total);
            //     $("#balance").val(0);
            // }

            // $(document).on("input", "#total_discount", function() {
            //     calculateTotal();

            // });

            function calculateTotal() {
                // Parse values as float and use 0 if the values are not available or invalid
                let subtotal = parseFloat($("#invoiceyoghtml").val()) || 0;
                let totalDiscount = parseFloat($("#total_discount").val()) || 0;
                let totalVAT = parseFloat($("#commercial_text").val()) || 0;

                // Calculate total by subtracting discount and VAT from subtotal
                let total = subtotal - totalDiscount - totalVAT;

                // Set the total value to relevant fields without using toFixed
                $("#total_total").val(total); // Keep as float
                $("#deposit").val(total); // Keep as float
                $("#balance").val("0"); // Assuming balance is zero initially
            }

            $(document).on("input", "#total_discount", function() {
                calculateTotal();

            });


            $(document).ready(function() {
                $('#invoice_no, #invoice_no_latest').on('input', function() {
                    let poNo = parseInt($('#invoice_no').val().replace("POS-", ""));
                    let poNoLatest = parseInt($('#invoice_no_latest').val().replace("POS-", ""));

                    if (!isNaN(poNo) && !isNaN(poNoLatest)) {
                        if (poNo < poNoLatest) {
                            $('#invoice_no').val("POS-" + poNoLatest);
                            alert("The Invoice number must be greater than the latest Invoice number.");
                        }
                    }
                });
            });
        </script>
        {{-- <script src="{{ asset('plugins/jquery/jquery.min.js ') }}"></script> --}}
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



</body>

</HTML>
