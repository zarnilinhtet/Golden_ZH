<!DOCTYPE html>
<HTML>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>

    <script src="{{ asset('backend/js/moment2103.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap400.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
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


        <h1 class="mx-4 mt-3">
            Suspend
        </h1>
        @php
            $userPermissions = [];
            if (auth()->user()->permission) {
                $decodedPermissions = json_decode(auth()->user()->permission, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $userPermissions = $decodedPermissions;
                }
            }
        @endphp
        <form method="post" id="myForm" action="{{ url('/invoice_update', $invoice->id) }}"
            enctype="multipart/form-data">
            @csrf

            <div class="mx-3 row ">
                {{-- <input type='hidden' name='sale_by' id="sale_by" value="{{ auth()->user()->name }}" class="form-control"> --}}
                <div class="mt-4 row">
                    <div class="col-md-3">
                        <label for="invoice_no" style="font-weight:bolder">POS Number</label>
                        <input type="text" id="invoice_no" class="form-control" name="invoice_no"
                            value="{{ $invoice->invoice_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_date" style="font-weight:bolder">Date</label>
                        <input type="date" name="invoice_date" class="form-control"
                            value="{{ $invoice->invoice_date }}" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3" style="display:none">
                        <label for="overdue_date" class=" caption"
                            style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>
                        <input type="date" name="overdue_date" id="overdue_date" class="form-control round"
                            autocomplete="off" min="<?= date('Y-m-d') ?>" value="{{ $invoice->overdue_date }}">
                    </div>
                    <div class="col-md-3 ">
                        <label for="payment_method" style="font-weight:bolder">{{ trans('Payment Methods') }}</label>
                        <select class="mb-4 form-control round" aria-label="Default select example"
                            name="payment_method" required>
                            <option value="Cash" @if ($invoice->payment_method == 'Cash') selected @endif>Cash</option>
                            <option value="KPAY" @if ($invoice->payment_method == 'KPAY') selected @endif>KPAY</option>
                            <option value="WAVE" @if ($invoice->payment_method == 'WAVE') selected @endif>WAVE</option>
                            <option value="Others" @if ($invoice->payment_method == 'Others') selected @endif>Others</option>
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
                                        <div class="col-sm-12 cmp-pnl">
                                            <div id="customerpanel" class="inner-cmp-pnl">

                                                <!-- <div class="form-group row">
                                                    <div class="frmSearch col-sm-12">
                                                        <div class="frmSearch col-sm-6">
                                                            <span style="font-weight:bolder">
                                                                <label for="cst"
                                                                    class="caption">{{ trans('Search With Customer Name & Phone Number') }}</label>
                                                            </span>
                                                            <input type="text" id="customer" name="customer"
                                                                class="form-control round" autocomplete="off">

                                                            <button type="submit" class="btn btn-primary mt-3"
                                                                id="customer_search">Add</button>

                                                            <div id="customer-box-result"></div>
                                                        </div>


                                                    </div>
                                                </div> -->
                                                <div class="frmSearch col-sm-12">
                                                    <div class="frmSearch col-sm-12">
                                                        <div class="row">
                                                            <div class="frmSearch col-sm-3">
                                                                <span style="font-weight:bolder">
                                                                    <label for="cst"
                                                                        class="caption">{{ trans('Search  Patient Name & Phone No.') }}</label>
                                                                </span>
                                                                <div class="form-group d-flex">
                                                                    <input type="text" id="customer" name="customer"
                                                                        class="mr-2 form-control round"
                                                                        autocomplete="off" placeholder="Search.....">
                                                                    &nbsp;&nbsp;&nbsp; <button type="submit"
                                                                        class="btn btn-primary"
                                                                        id="customer_search">Add</button>
                                                                </div>
                                                                <div id="customer-box-result"></div>
                                                            </div>


                                                            @if (Auth::user()->type == '0' || Auth::user()->is_admin == '1')


                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label for="location"
                                                                            style="font-weight:bolder">Choose
                                                                            Location</label>
                                                                        <select name="location" id="location"
                                                                            class="form-control mb-4" required>

                                                                            @foreach ($warehouses as $warehouse)
                                                                                <option value="{{ $warehouse->id }}"
                                                                                    @if ($warehouse->id == $invoice->location) selected @endif)>
                                                                                    {{ $warehouse->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="frmSearch col-sm-3" style="display: none">
                                                                    <label for="location"
                                                                        style="font-weight:bolder">Choose
                                                                        Location</label>
                                                                    <select name="location" id="location"
                                                                        class="form-control mb-4" required>

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
                                                                    <label for="doctor"
                                                                        style="font-weight:bolder">Doctor
                                                                        Name<span
                                                                            class="text-danger fw-bold">*</span></label>
                                                                    <select name="doctor_id" id="doctor_id"
                                                                        class="form-control" required>
                                                                        <option value="">Select Doctor</option>
                                                                        @foreach ($doctors as $doctor)
                                                                            @if ($doctor->id == $invoice->doctor_id && $doctor->branch == $invoice->location)
                                                                                <option
                                                                                    data-branch="{{ $doctor->branch }}"
                                                                                    value="{{ $doctor->id }}"
                                                                                    selected>
                                                                                    {{ $doctor->name }}
                                                                                </option>
                                                                            @break;
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                @if (session('error_doctor'))
                                                                    <strong
                                                                        class="text-danger">{{ session('error_doctor') }}</strong>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>



                                            </div>
                                            <input type="hidden" id="service_id" name="service_id"
                                                value="0">

                                            <input type="hidden" name="manager_type"
                                                value="{{ Auth::user()->type }}">
                                            <input type="text" name="status" class="form-control"
                                                value="invoice" style="display: none">




                                        </div>
                                    </div>
                                    <div class="mb-4 row table-responsive" style="margin-top:1vh;">
                                        <table class="table table-bordered">
                                            <thead style="background-color:#0047AA;color:white;">
                                                <tr class="item_header bg-gradient-directional-blue white">
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
                                                            name='customer_name' id="name"
                                                            class="form-control"
                                                            value="{{ $invoice->customer_name }}"></td>
                                                    <input type='hidden' name='customer_id' id="customer_id"
                                                        class="form-control">
                                                    <input type='hidden' name='status' id="status"
                                                        class="form-control" value="suspend">
                                                    <td class="text-center"><input type='text' name='phno'
                                                            id="phone_no" class="form-control"
                                                            value="{{ $invoice->phno }}"></td>
                                                    <td class="text-center"><input type='text' name='type'
                                                            id="type" class="form-control"
                                                            value="{{ $invoice->type }}"></td>
                                                    <td class="text-center"><input type='text' name='address'
                                                            class="form-control" id="address"
                                                            value="{{ $invoice->address }}"></td>
                                                </tr>


                                            </tbody>
                                        </table>


                                    </div>
                                    <hr>
                                    {{-- @if (auth::user()->type == '0' || auth::user()->is_admin == '1')
                                        <div class="mt-4 frmSearch col-md-3">

                                            <label for="location" style="font-weight:bolder">Choose
                                                Location</label>
                                            <select name="location" id="location" class="mb-4 form-control"
                                                required>

                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        @if ($warehouse->id == $invoice->location) selected @endif>
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="mt-4 frmSearch col-md-3" style="display: none;">

                                            <label for="location" style="font-weight:bolder">Choose
                                                Location</label>
                                            <select name="location" id="location" class="mb-4 form-control"
                                                required>

                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        @if ($warehouse->id == auth::user()->level) selected @endif>
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif --}}
                                    <div class="row">
                                        <div class="mt-4 frmSearch col-md-3">
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

                                        <div class="mt-4 frmSearch col-md-3">
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
                                        <div class="mt-4 frmSearch col-md-3">
                                            <label for="payment"
                                                style="font-weight:bolder">{{ trans('Sale Price Category') }}
                                            </label>
                                            <select class="mb-4 form-control round "
                                                aria-label="Default select example" name="sale_price_category"
                                                id="sale_price_category" required>
                                                <option value="{{ $invoice->sale_price_category }}" selected>
                                                    Retail
                                                </option>
                                                {{-- <option value="Default">Default</option>
                                                <option value="Whole Sale">Whole Sale</option> --}}
                                                {{-- <option value="Retail">Retail</option> --}}
                                            </select>
                                        </div>
                                    </div>



                                </div>




                                <div class="row table-responsive " style="margin-top:1vh;">
                                    <!-- <table class="table-responsive tfr my_stripe"> -->
                                    <table class="table table-bordered">
                                        <thead style="background-color:#0047AA;color:white;">
                                            <tr class="item_header bg-gradient-directional-blue white"
                                                style="margin-bottom:10px;">
                                                <th width="5%" class="text-center">{{ trans('No') }}
                                                </th>
                                                <th width="18%" class="text-center">
                                                    {{ trans('Item Name') }}
                                                </th>
                                                <th width="23%" class="text-center" style="display:none">
                                                    {{ trans('Descriptions') }}
                                                </th>
                                                <th width="8%" class="text-center">
                                                    {{ trans('Qty') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Unit') }}
                                                </th>

                                                {{-- <th  width="9%" class="text-center">
                                                            {{ trans('လက်ကားစျေး') }}
                                                        </th> --}}
                                                <th width="9%" class="text-center">
                                                    {{ trans('Retail Price') }}
                                                </th>

                                                <th width="10%" class="text-center">
                                                    {{ trans('Discounts') }}
                                                </th>

                                                <th width="9%" class="text-center">
                                                    {{ trans('Expiry') }}
                                                </th>


                                                <th width="12%" class="text-center">{{ trans('Amount') }}
                                                    ({{ config('currency.symbol') }})
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody id="showitem123">
                                            @foreach ($sells as $key => $sell)
                                                <tr>

                                                    <td style="display: none;">
                                                        <input type="text" class="form-control barcode"
                                                            name="barcode[]"
                                                            value="{{ optional($sell->variations->first())->variations_barcode }}"
                                                            placeholder="{{ trans('Enter BarCode') }}"
                                                            id="barcode-0" autocomplete="off">
                                                    </td>

                                                    <td class="text-center" id="count">
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td style="display: none"><input type="hidden"
                                                            class="form-control buy_price" name="buy_price[]"
                                                            autocomplete="off" value="{{ $sell->buy_price }}">
                                                    </td>
                                                    <td><input type="text"
                                                            class="form-control item_name productname typeahead"
                                                            name="part_number[]" value="{{ $sell->part_number }}"
                                                            placeholder="{{ trans('Enter Part Number') }}"
                                                            id='item_name-0' autocomplete="off">
                                                        <input type="hidden"
                                                            class="form-control result_item_name typeahead"
                                                            name="result_item_name[]"
                                                            value="{{ $sell->product_name }}"
                                                            id='result_item_name-0' autocomplete="off">
                                                        <input type="hidden"
                                                            class="form-control item_id typeahead"
                                                            name="item_id[]" value="{{ $sell->item_id }}"
                                                            id='item_id-0' autocomplete="off">
                                                        <input type="hidden"
                                                            class="form-control result_id typeahead"
                                                            name="result_id[]" value="{{ $sell->variation_id }}"
                                                            id='result_id-0' autocomplete="off">
                                                    </td>

                                                    <td style="display: none;"><input type="text"
                                                            class="form-control description typeahead"
                                                            value="{{ $sell->description }}"
                                                            name="part_description[]"
                                                            placeholder="{{ trans('') }}" id='description-0'
                                                            autocomplete="off"></td>
                                                    <td><input type="text" class="form-control req amnt"
                                                            name="product_qty[]" id="amount-0"
                                                            autocomplete="off"
                                                            value="{{ $sell->product_qty }}"><input
                                                            type="hidden" id="alert-0" value=""
                                                            name="alert[]"></td>
                                                    <td><input type="text" class="form-control unit"
                                                            name="item_unit[]" value="{{ $sell->unit }}"
                                                            id="item_unit-0">

                                                    </td>

                                                    <td><input type="text" class="form-control retail_price"
                                                            name="retail_price[]" id="retail_price-0"
                                                            autocomplete="off" value="{{ $sell->retail_price }}">
                                                    </td>

                                                    <td>
                                                        <div class="d-flex align-items-between"><input
                                                                type="text"
                                                                class="form-control col-md-8 item_discount"
                                                                name="item_discount[]"
                                                                id="item_discount-{{ $key + 1 }}"
                                                                autocomplete="off"
                                                                value="{{ $sell->item_discount }}">
                                                            @if ($sell->ks_percent == 'Ks')
                                                                <a id="flipFlopButton-{{ $key + 1 }}"
                                                                    class="flip-flop-btn form-control Ks col-md-4"
                                                                    href="javascript:void(0)"
                                                                    style="text-decoration: none; margin-left: 10px;">Ks</a><input
                                                                    type="hidden"
                                                                    id="valueIndicator-{{ $key + 1 }}"
                                                                    class="form-control valueIndicator"
                                                                    name="valueIndicator[]" value="Ks">
                                                            @else
                                                                <a id="flipFlopButton-{{ $key + 1 }}"
                                                                    class="flip-flop-btn form-control percent col-md-4"
                                                                    href="javascript:void(0)"
                                                                    style="text-decoration: none; margin-left: 10px;">%</a><input
                                                                    type="hidden"
                                                                    id="valueIndicator-{{ $key + 1 }}"
                                                                    class="form-control valueIndicator"
                                                                    name="valueIndicator[]" value="%">
                                                            @endif

                                                        </div>
                                                    </td>

                                                    <td><input type="text" class="form-control exp_date "
                                                            name="exp_date[]" id="exp_date-0" autocomplete="off"
                                                            value="{{ $sell->exp_date }}">
                                                    </td>
                                                    <td style="display: none;"><input type="text"
                                                            class="form-control warehouse " name="warehouse[]"
                                                            id="warehouse-0" autocomplete="off"
                                                            value="{{ $sell->warehouse }}">
                                                    </td>
                                                    <!-- <td><input type="text" class="form-control vat " name="discount[]" id="vat-0" autocomplete="off" value="{{ old('discount') }}">
</td> -->

                                                    <td style="text-align:center">
                                                        <strong>
                                                            <span class='ttlText1' id="foc-0"></span>
                                                        </strong>
                                                        <span
                                                            class="currenty">{{ config('currency.symbol') }}</span>
                                                        {{-- <strong>
                                                                <input style="border: none;font-weight: bold"
                                                                    type="text"
                                                                    class="form-control text-center amount_total"
                                                                    readonly name="amount[]" id="result-0"
                                                                    value="{{ $sell->retail_price * $sell->product_qty }}">
                                                            </strong> --}}
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
                                                    <td style="width: 5%;"><button type="submit"
                                                            class="btn btn-danger remove_item_btn"
                                                            id="removebutton">Remove</button></td>
                                                    </input>
                                            @endforeach
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
                                                                id="addproduct"
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
                                                        <select name="user_id" class="selectpicker form-control">
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
                                                        id="invoiceyoghtml" value="{{ $invoice->net_total }}"
                                                        readonly style="background-color: #E9ECEF">

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
                                                        value="{{ $invoice->discount_total }}"
                                                        id="total_discount">

                                                </td>

                                            </tr>
                                            <tr class="sub_c">
                                                <td colspan="2">

                                                </td>
                                                <td colspan="3" align="right"><strong>Total
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="2" class="col-md-4"><input
                                                        type="text" name="total" class="form-control"
                                                        value="{{ $invoice->total }}" id="total_total" readonly
                                                        style="background-color: #E9ECEF">

                                                </td>

                                            </tr>



                                            <tr class="sub_c" style="display: none;">
                                                <td colspan="2">

                                                </td>
                                                <td colspan="3" align="right"><strong>Cash
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="2"><input type="text"
                                                        name="deposit" class="form-control" id="deposit"
                                                        oninput="paidFunction()" value="{{ $invoice->deposit }}">

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
                                                        readonly>
                                                </td>
                                            </tr>

                                            <tr class="sub_c " style="display: table-row;">
                                                <td colspan="12"> <label for="remark">Remark</label>
                                                    <textarea name="remark" id="remark" class="form-control" rows="2">{{ $invoice->remark }}</textarea>

                                                </td>
                                            </tr>
                                            <tr class="sub_c " style="display: table-row;">


                                                <td align="right" colspan="9">

                                                    <button id="submitButton" class="mt-3 btn btn-primary"
                                                        type="submit">Confirm</button>


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
        });



    });
</script>
<script>
    function Indicator(key) {
        let flipFlopButton = document.getElementById('flipFlopButton-' + key);
        let valueIndicator = document.getElementById('valueIndicator-' + key);

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
    }

    // Call Indicator for each dynamic key after the page loads or dynamically
    document.addEventListener('DOMContentLoaded', function() {
        let key = "{{ $sell_no }}"; // This will get the integer value from the controller

        // Call the Indicator function with the key
        for (let i = 1; i <= key; i++) {
            Indicator(i);
        }

    });
</script>
<script>
    $(document).ready(function() {
        let count = 0;

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

                    $('.barcode-input').val(
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
                existingRow = $("#showitem123 input.barcode[value='" + barcode + "']").closest('tr');
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
                forclick = {{ $sell_no }} + count;
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
                    forclick +

                    '"   autocomplete="off"> <a href="javascript:void(0)" id="flipFlopButton-' +
                    (forclick) +
                    '" class="flip-flop-btn form-control Ks col-md-4"style="text-decoration: none; margin-left: 10px;">Ks</a><input type="hidden" id="valueIndicator-' +
                    (forclick) +
                    '" name="valueIndicator[]" value="Ks"></div></td>' +
                    '<td><input type="text" class="form-control exp_date" name="exp_date[]" id="exp_date-' +
                    count + '" autocomplete="off" value="' + (variation['expired_date'] || '') + '"></td>' +
                    '<td style="display: none;"><input type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                    count + '" autocomplete="off" value="' + item['warehouse_id'] + '"></td>' +
                    '<td style="text-align:center"><span class="currenty"></span><strong><span id="result-' +
                    count + '"></span></strong></td>' +
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
                let flipFlopButton = document.getElementById('flipFlopButton-' + forclick);
                let valueIndicator = document.getElementById('valueIndicator-' + forclick);

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




        function calculateTotals() {
            let salePriceCategory = $('#sale_price_category').val();

            let totalTax = 0;
            let itemDiscount = 0;
            let totalAmount = 0;
            let totalTotal = 0;

            let total = 0;
            $('#showitem123 tr').each(function() {
                let row = $(this);

                let qty = parseInt(row.find('.req.amnt').val()) || 0;
                let price = 0;
                let amount = 0;

                // Determine the price based on the selected category
                if (salePriceCategory === 'Default') {
                    let cuz_name = $("#type").val();
                    price = cuz_name === "Whole Sale" ?
                        parseFloat(row.find('.price').val()) || 0 :
                        parseFloat(row.find('.retail_price').val()) || 0;
                } else if (salePriceCategory === 'Whole Sale') {
                    price = parseFloat(row.find('.price').val()) || 0;
                } else if (salePriceCategory === 'Retail') {
                    price = parseFloat(row.find('.retail_price').val()) || 0;
                } else if (salePriceCategory === 'Buy Price') {
                    price = parseFloat(row.find('.buy_price').val()) || 0;
                }

                let discount = parseFloat(row.find('.item_discount').val()) || 0;

                let indicator = row.find('.valueIndicator').val();
                let itemTotal = 0;
                console.log(indicator);

                if (!isNaN(discount) && discount >= 0) {
                    if (indicator == 'Ks') {
                        let itemTax = discount;
                        totalTax += itemTax;
                        amount = price * qty - discount;
                    } else {
                        let itemTax = (price * qty * discount) / 100;
                        amount = price * qty - (price * qty * discount) / 100;
                        totalTax += itemTax;
                    }
                }

                row.find('.currenty').text(amount);
                itemTotal = price * qty;
                totalTotal += itemTotal;

                total += itemTotal;

                // Show/hide based on the price condition
                if (price > 0) {
                    row.find('.ttlText1').show();
                    row.find('.ttlText').hide();
                } else {
                    row.find('.ttlText1').hide();
                    row.find('.ttlText').show();
                }
            });

            let tax = total * 0.05; // Calculate tax
            tax = parseFloat(tax); // Ensure tax is parsed as a float

            $('#invoiceyoghtml').val(totalTotal);
            $('#commercial_text').val(totalTax);
            calculateTotal();
            paidFunction();
        }


        $(document).ready(function() {
            $('#calculate').on('click', function() {
                calculateTotals();
                calculateTotal();
            });

            calculateTotals();
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>


<script>
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


                    $("#name").val(data['customer']['name']);
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
    function calculateTotal() {
        // Parse all input values as floats and default to 0 if not valid
        let subtotal = parseFloat($("#invoiceyoghtml").val()) || 0.0;
        let totalDiscount = parseFloat($("#total_discount").val()) || 0.0;
        let totalVAT = parseFloat($("#commercial_text").val()) || 0.0;

        // Calculate total, ensuring all operations are done with float values
        let total = subtotal - totalDiscount - totalVAT;

        // Log values for debugging purposes
        console.log('Subtotal:', subtotal);
        console.log('Total Discount:', totalDiscount);
        console.log('Total VAT:', totalVAT);

        // Set the calculated total values to the appropriate fields
        $("#total_total").val(total);
        $("#deposit").val(total);
        $("#balance").val(0); // Reset balance or adjust as needed
    }


    $(document).on("input", "#total_discount", function() {
        calculateTotal();
    });
</script>

</body>

</HTML>
