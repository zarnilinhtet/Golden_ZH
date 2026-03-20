<!DOCTYPE html>
<HTML>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script> <!-- jQuery should be included first -->

    <!-- Include Bootstrap JS here -->
    <script src="{{ asset('backend/js/bootstrap.js') }}"></script>

    <script src="{{ asset('backend/js/typehead401.js') }}"></script>
    <script src="{{ asset('backend/js/moment2103.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">


    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
            right: 12px;
            color: black;
            opacity: 1;
        }

        .dropdown-menu {
            max-height: 200px;
            /* Set a maximum height for the dropdown menu */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        /* CSS for active item in dropdown menu */
        .dropdown-menu .active,
        .dropdown-menu .active:hover {
            background-color: #E2E3E5;
            /* Set background color for active item */
            color: #E2E3E5;
        }
    </style>
    </style>
</head>

<body>

    <div class="container-fluid">

        <h1 class="mt-3">
            Purchase Order
        </h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="post" id="data_form" action=" {{ URL('purchase_order_store') }}" enctype="multipart/form-data">
            @csrf




            <div class="content-wrapper mt-3" style="background-color:aqua">
                <div class="content-body">
                    <div class="card">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="col-sm-10 cmp-pnl">
                                    <div id="customerpanel" class="inner-cmp-pnl">

                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="po_no" style="font-weight:bolder">Purchase Order
                                                    Number</label>
                                                <input type="text" id="po_no" class="form-control"
                                                    name="po_number" value="PO - {{ $po_no }}">
                                                <input type="hidden" id="po_no_latest" class="form-control"
                                                    name="" value="{{ $po_number }}">
                                            </div>
                                            {{-- <div class="col-sm-6 cmp-pnl">

                                        <div class="col-md-3 mt-1">
                                            <label for="date" class="" style="font-weight:bolder">Date</label>
                                            <input type="date" name="invoice_date" class="form-control"
                                                max="<?php echo date('Y-m-d'); ?>" required value="{{ date('Y-m-d') }}">

                                        </div>
                                    </div> --}}
                                            <div class="col-sm-3">
                                                <label for="po_no" style="font-weight:bolder">Purchase Order
                                                    Date</label>
                                                <input type="date" name="po_date" class="form-control" required
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="frmSearch col-sm-3 d-none">
                                                <div class="frmSearch col-sm-12">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst" class="caption">Receiving Mode
                                                            </label>
                                                        </span>
                                                        <select name="balance_due" id="balance_due"
                                                            class="mb-4 form-control balance_due" required>

                                                            <option value="Purchase Order">PO</option>
                                                            <option value="Sale Return Invoice">Sale Return</option>

                                                        </select>

                                                        <div id="customer-box-result"></div>
                                                    </div>


                                                </div>



                                            </div>

                                            @php
                                                use Illuminate\Support\Facades\Auth;
                                            @endphp
                                            @if (auth::user()->type === '0' || auth::user()->level === '1')
                                                <div class="frmSearch col-sm-3">
                                                    <label for="location" style="font-weight:bolder">Choose
                                                        Location</label>
                                                    <select name="location" id="location" class="form-control mb-4"
                                                        required>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <div class="frmSearch col-sm-4" style="display: none;">
                                                    <label for="location" style="font-weight:bolder">Choose
                                                        Location</label>
                                                    <select name="location" id="location" class="form-control mb-4"
                                                        required>

                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                @if (auth::user()->level === $warehouse->id) selected @endif>


                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            @endif
                                            <div class="frmSearch col-sm-3">
                                                <div class="frmSearch col-sm-12">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst"
                                                                class="caption">{{ trans('Supplier Name') }}</label>
                                                        </span>
                                                        <select name="supplier_id" class="form-control">
                                                            <option value="" selected disabled>Choose Supplier
                                                            </option>
                                                            @foreach ($suppliers as $supplier)
                                                                <option value="{{ $supplier->id }}">
                                                                    {{ $supplier->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <div id="customer-box-result"></div>
                                                    </div>


                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="frmSearch col-sm-3">
                                        <div class="frmSearch col-sm-12 " id="supplier_box">
                                            <span style="font-weight:bolder">
                                                <label for="cst"
                                                    class="caption">{{ trans('Supplier Name') }}</label>
                                            </span>
                                            <select name="supplier_id" class="form-control">
                                                <option value="" selected disabled>Choose Supplier
                                                </option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div> --}}

                                </div>
                                <input type="hidden" value="invoice" name="status">

                                <div class="row " style="margin-top:1vh;">
                                    <table class="" id="myTable">
                                        <thead style="background-color:#2A7774;color:white; border: 1px solid white;">
                                            <tr class="item_header bg-gradient-directional-blue white"
                                                style="margin-bottom:10px;">
                                                <th width="3%" class="text-center" style="display:none">
                                                    {{ trans('No') }}</th>
                                                <th width="20%" class="text-center">{{ trans('Item Name') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Qty') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Company Price') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('FOC ') }}
                                                </th>
                                                <th width="8%" class="text-center">{{ trans('Unit') }}
                                                </th>
                                                <th width="7%" class="text-center">{{ trans('Total Qty ') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Discount (%)') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Commercial Tax ') }}
                                                </th>

                                                <th width="14%" class="text-center">{{ trans('Amount') }}
                                                    ({{ config('currency.symbol') }})
                                                </th>
                                            </tr>

                                        </thead>
                                        <tbody id="showitem123">
                                            <tr class="">
                                                <td class="text-center" id="count" style="display:none">1</td>
                                                <td><input type="text" style="background-color: #E9ECEF"
                                                        class="form-control  productname typeahead"
                                                        name="part_number[]"
                                                        placeholder="{{ trans('Enter Part Number') }}"
                                                        id='productname-0' autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_item_name typeahead result_item_name"
                                                        name="result_item_name[]" id="result_item_name-0"
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
                                                        class="form-control result_id typeahead result_id"
                                                        name="result_id[]" id="result_id-0" autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control item_id typeahead item_id"
                                                        name="item_id[]" id="item_id-0" autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_expired_date typeahead result_expired_date"
                                                        name="result_expired_date[]" id="result_expired_date-0"
                                                        autocomplete="off">
                                                </td>
                                                <input type="hidden" class="form-control description typeahead"
                                                    name="part_description[]" placeholder="{{ trans('') }}"
                                                    id='description-0' autocomplete="off">
                                                <!-- <td><input type="text" class="form-control description typeahead" name="part_description[]" placeholder="{{ trans('') }}" id='description-0' autocomplete="off"></td> -->
                                                <td><input type="text" class="form-control req amnt"
                                                        style="background-color: #E9ECEF" name="product_qty[]"
                                                        id="qty-0" autocomplete="off" onchange="sumQty(0)"
                                                        value="1"><input type="hidden" id="alert-0"
                                                        style="background-color: #E9ECEF" value=""
                                                        name="alert[]">
                                                </td>



                                                <td><input type="text" class="form-control  companyPrice"
                                                        name="company_price[]" id="company_price-0"
                                                        autocomplete="off" value="0"
                                                        style="background-color: #E9ECEF">

                                                </td>
                                                <td><input type="text" class="form-control  foc" name="foc[]"
                                                        onchange="sumQty(0)" id="foc-0" autocomplete="off"
                                                        value="0" style="background-color: #E9ECEF">

                                                </td>
                                                <td>


                                                    <select class="form-control unit" id="unit-0" required
                                                        name="item_unit[]"style="background-color: #E9ECEF">

                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control  totalQty"
                                                        name="totalQty[]" id="totalQty-0" autocomplete="off"
                                                        value=""style="background-color: #E9ECEF">

                                                </td>

                                                <td><input type="text" class="form-control  discount"
                                                        value="0" name="discount[]" id="discount-0"
                                                        autocomplete="off"
                                                        value=""style="background-color: #E9ECEF">

                                                </td>
                                                <td><input type="text" class="form-control  commercialtax"
                                                        name="commercialtax[]" id="commercialtax-0" value="0"
                                                        autocomplete="off"
                                                        value=""style="background-color: #E9ECEF">

                                                </td>
                                                {{-- <td><input type="text" class="form-control buy_price" name="buy_price[]" id="buy_price-0" autocomplete="off" value="0">
                                                </td>
                                                <td><input type="text" class="form-control retail_price" name="retail_price[]" id="retail_price-0" autocomplete="off" value="0">
                                                </td>
                                                <td><input type="text" class="form-control wholesale_price" name="wholesale_price[]" id="wholesale_price-0" autocomplete="off" value="0">
                                                </td>
                                                <td><input type="text" class="form-control exp_date "
                                                        name="exp_date[]" id="exp_date-0" autocomplete="off">
                                                </td> --}}

                                                <td style="display: none;"><input type="text"
                                                        class="form-control warehouse " name="warehouse[]"
                                                        id="warehouse-0" autocomplete="off">
                                                </td>
                                                <td style="text-align:center">
                                                    <input type="text" class="form-control amount " id="amount-0"
                                                        name="amount[]" style="background-color: #E9ECEF">
                                                    <!-- {{-- <span class='ttlText' id="foc-0"></span> --}}
                                                    <span class="currenty">{{ config('currency.symbol') }}</span>
                                                    <strong>
                                                        <span class='ttlText' id="result-0"></span>
                                                    </strong> -->
                                                </td>
                                                <input type="hidden" class="form-control vat " name="product_tax[]"
                                                    id="vat-0" value="0">
                                                <input type="hidden" name="total_tax[]" id="taxa-0"
                                                    value="0">
                                                <input type="hidden" name="total_discount[]" id="disca-0"
                                                    value="0">
                                                <input type="hidden" class="ttInput" name="product_subtotal[]"
                                                    id="total-0" value="0">
                                                <input type="hidden" class="pdIn" name="product_id[]"
                                                    id="pid-0" value="0">

                                                <input type="hidden" name="unit_m[]" id="unit_m-0" value="1">
                                                <input type="hidden" name="code[]" id="hsn-0" value="">
                                                <input type="hidden" name="serial[]" id="serial-0" value="">

                                            </tr>
                                            <tr class="text-center">

                                                <td style="font-weight: bold">Unit</td>
                                                <td style="font-weight: bold">Buy Price</td>
                                                <td style="font-weight: bold">Wholesale Price</td>
                                                <td style="font-weight: bold">Retail Price</td>



                                            </tr>
                                            <tr>

                                                <td><input type="text" class="form-control name1" name="name1[]"
                                                        id="name1-0">
                                                </td>
                                                <td><input type="text" class="form-control price1 "
                                                        name="price1[]" id="price1-0"></td>
                                                <td><input type="text" class="form-control wholesale1"
                                                        name="wholesale1[]" id="wholesale1-0"></td>

                                                <td><input type="text" class="form-control retail1"
                                                        name="retail1[]" id="retail1-0"> </td>


                                            </tr>
                                            <tr>
                                                <!-- <td></td> -->
                                                <td><input type="text" class="form-control name2 " name="name2[]"
                                                        id="name2-0"></td>
                                                <td><input type="text" class="form-control price2" name="price2[]"
                                                        id="price2-0"></td>
                                                <td><input type="text" class="form-control wholesale2"
                                                        name="wholesale2[]" id="wholesale2-0"></td>

                                                <td><input type="text" class="form-control retail2"
                                                        name="retail2[]" id="retail2-0"></td>


                                            </tr>
                                            <tr>

                                                <td><input type="text" class="form-control  name3" name="name3[]"
                                                        id="name3-0"></td>
                                                <td><input type="text" class="form-control  price3 "
                                                        name="price3[]" id="price3-0"></td>
                                                <td><input type="text" class="form-control  wholesale3"
                                                        name="wholesale3[]" id="wholesale3-0"></td>

                                                <td><input type="text" class="form-control retail3"
                                                        name="retail3[]" id="retail3-0"></td>


                                            </tr>
                                        </tbody>

                                        <tr class="last-item-row sub_c">

                                            <td class="add-row">
                                                <button type="button" class="btn btn-success" id="addproduct"
                                                    style="margin-top:30px;margin-bottom:20px;">
                                                    <i class="fa fa-plus-square"></i> {{ trans('Add row') }}
                                                </button>
                                                <button type="button" class="btn btn-primary" id="calculate"
                                                    style="margin-top:30px;margin-bottom:20px;">
                                                    Calculate
                                                </button>


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
                                        <tbody id="showitem">
                                            <tr style="display: table-row;">
                                                <td></td>
                                                <td colspan="">
                                                </td>
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
                                                <td colspan="4">

                                                </td>
                                                <td colspan="2" align="right"><strong>Sub Total
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="4" class="col-md-4"><input
                                                        type="text" name="sub_total" class="form-control"
                                                        id="invoiceyoghtml" style="background-color: #E9ECEF">
                                                </td>
                                            </tr>

                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="4">

                                                </td>
                                                <td colspan="2" align="right"><strong>Total Discount
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="4" class="col-md-4"><input
                                                        type="text" name="discount_total" class="form-control"
                                                        id="discount_total">
                                                </td>

                                            </tr>
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="4">

                                                </td>
                                                <td colspan="2" align="right"><strong>Total Discount(%)
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="4" class="col-md-4"><input
                                                        type="text" name="discount_total_percent"
                                                        class="form-control" id="discount_total_percent">
                                                </td>

                                            </tr>

                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="4">

                                                </td>
                                                <td colspan="2" align="right"><strong>Total
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="4" class="col-md-4"><input
                                                        type="text" name="total" class="form-control"
                                                        id="total">

                                                </td>
                                            </tr>
                                        <tbody id="trContainer">
                                            <tr class="sub_c">
                                                <td colspan="3"></td>
                                                <td colspan="3" align="right"><strong>Payment
                                                        Method</strong></td>
                                                <td align="left" colspan="1" class="col-md-2">
                                                    <input type="text" name="payment_amount[]"
                                                        class="form-control payment_amount" id="payment_amount"
                                                        required>
                                                </td>
                                                <td align="left" colspan="3" class="col-md-2 payment_method">
                                                    <div class="input-group">
                                                        <select name="payment_method[]" id="payment_method-0"
                                                            class="form-control" required>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <button type="button" id="addRow"
                                                                class="btn btn-primary">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        </tbody>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="4">

                                            </td>
                                            <td colspan="2" align="right"><strong>Deposit
                                                </strong>
                                            </td>
                                            <td align="left" colspan="4" class="col-md-4"><input type="text"
                                                    name="paid" class="form-control" id="paid" readonly>

                                            </td>

                                        </tr>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="4">

                                            </td>
                                            <td colspan="2" align="right"><strong>Remaining Balance
                                                </strong>
                                            </td>
                                            <td align="left" colspan="4" class="col-md-4"><input type="text"
                                                    name="balance" class="form-control" id="balance"
                                                    readonly="">

                                            </td>
                                        </tr>


                                        <tr class="sub_c " style="display: table-row;">
                                            <td colspan="14"> <label for="remark">Remark</label>
                                                <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                            </td>
                                        </tr>
                                        <tr class="sub_c " style="display: table-row;">


                                            <td align="right" colspan="15">

                                                <button id="submitButton" class="mt-3 btn btn-danger"
                                                    type="submit">Save</button>


                                                <a href="{{ url('purchase_order_manage') }}" type="submit"
                                                    class="mt-3 btn btn-warning">Cancel
                                                </a>

                                            </td>
                                        </tr>
                                        </tbody>
                                        </tbody>
                                    </table>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>

        </form>
    </div>

    </div>
    <script>
        function handleKeyUp(event) {
            console.log(`Key pressed: ${event.key}`);
            // You can add more logic here to respond to the event
        }
    </script>
    <script>
        document.getElementById('discount_total').addEventListener('input', function() {
            var discountTotalPercent = document.getElementById('discount_total_percent');
            discountTotalPercent.value = "";

            const subTotal = parseFloat(document.getElementById('invoiceyoghtml').value) || 0;
            const discountTotal = parseFloat(this.value) || 0;

            const total = subTotal - discountTotal;

            document.getElementById('total').value = total;
        });

        $('#location').on('change', function() {
            getAccount(0);
        });
        $('#balance_due').on('change', function() {
            getAccount(0);
        });


        function getAccount(payment_count) {
            var locationId = $("#location").val();
            var balance_due = $("#balance_due").val();
            $('#payment_method-' + payment_count).html(
                '<option value="">Loading...</option>');
            if (locationId) {
                $.ajax({
                    url: '{{ route('get_accounts_transaction_po') }}',
                    type: 'GET',
                    data: {
                        locationId: locationId,
                        balance_due: balance_due,
                    },
                    success: function(data) {
                        console.log(data);
                        $('#payment_method-' + payment_count)
                            .empty().append(
                                '<option value="">Select Transaction</option>'
                            );
                        if (data && data.length > 0) {
                            $.each(data, function(index,
                                transaction) {
                                $('#payment_method-' +
                                        payment_count)
                                    .append(
                                        '<option value="' +
                                        transaction
                                        .id + '">' +
                                        transaction
                                        .transaction_name +
                                        '</option>');
                            });
                        } else {
                            $('#payment_method-' +
                                payment_count).append(
                                '<option value="">No Transaction available</option>'
                            );
                        }
                    },
                    error: function() {
                        $('#payment_method-' + payment_count)
                            .empty().append(
                                '<option value="">Error loading transactions</option>'
                            );
                    }
                });
            } else {
                $('#payment_method-' + payment_count).empty().append(
                    '<option value="">Select Transaction</option>');
            }
            $('#location').on('change', function() {
                getAccount(payment_count);
            });
        }
    </script>
    <script>
        document.getElementById('discount_total_percent').addEventListener('input', function() {
            var discountTotal = document.getElementById('discount_total');
            discountTotal.value = "";

            const subTotal = parseFloat(document.getElementById('invoiceyoghtml').value) || 0;
            const discountTotalPercent = parseFloat(this.value) || 0;

            const total = subTotal - (discountTotalPercent * subTotal / 100);

            document.getElementById('total').value = total;
        });
    </script>

    <script>
        function sumQty(count) {
            let qty = document.getElementById("qty-" + count).value;
            let foc = document.getElementById("foc-" + count).value;
            qty = parseFloat(qty) || 0;
            foc = parseFloat(foc) || 0;

            let totalQty = document.getElementById("totalQty-" + count);
            totalQty.value = qty + foc;
        }
    </script>

    <script>
        $(document).ready(function() {
            let count = 0;


            function initializeTypeahead(count) {
                $('#productname-' + count).typeahead({
                    source: function(query, process) {
                        var Selectedlocation = $('#location').val();
                        return $.ajax({
                            url: "{{ route('item_search_for_po') }}",
                            method: 'POST',
                            data: {
                                query: query,
                                location: Selectedlocation,
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
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
                        return item
                            .display;
                    },
                    afterSelect: function(item) {
                        $('#result_descriptions-' + count).val(item.description);
                        $('#result_product_code-' + count).val(item.product_code);
                        $('#result_item_name-' + count).val(item.item_name);
                        $('#result_id-' + count).val(item.id);
                        $('#item_id-' + count).val(item.item_id);
                        $('#result_expired_date-' + count).val(item.expired_date);
                    },
                    autoSelect: true
                });
            }


            function initializeTypeaheads() {
                for (let i = 0; i <= count; i++) {
                    initializeTypeahead(i);
                }
            }

            function updateItemName(item_name, row, description, item_id, cuz_name, expired_date) {
                let buyprice = row.find('.buy_price');
                let retail = row.find('.retail_price');
                let wholesale = row.find('.wholesale_price');
                let partDesc = row.find('.description');
                let exp_date = row.find('.exp_date');
                var Selectedlocation = $('#location').val();
                let warehouse = row.find('.warehouse');
                let unit = row.find('.unit');


                $.ajax({
                    type: 'POST',
                    url: "{{ route('get-part-data-invoice') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        result_item_name: item_name,
                        result_descriptions: description,
                        // result_product_code: product_code,
                        item_id: item_id,
                        result_expired_date: expired_date,
                        location: Selectedlocation,
                    },
                    success: function(data) {
                        buyprice.val(data.buy_price);

                        //  itemNameInput.val(data.retail_price);
                        $(unit).on('change', function() {
                            let selectedUnit = unit.val();
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('unit_search_withName') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    unit: selectedUnit,
                                    item_name: item_name,
                                    description: description,
                                    // product_code: product_code,
                                    item_id: item_id,
                                    result_expired_date: expired_date,
                                },
                                success: function(data) {
                                    wholesale.val(data.wholesale);
                                    retail.val(data.retail);
                                    buyprice.val(data.buyprice);

                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });

                        });
                        if (data.name2 != null && data.name3 != null) {
                            unitdata = [data.name1, data.name2, data.name3];
                        } else if (data.name2 != null && data.name3 == null) {
                            unitdata = [data.name1, data.name2];
                        } else if (data.name2 == null && data.name3 != null) {
                            unitdata = [data.name1, data.name3];
                        } else {
                            unitdata = [data.name1];
                        }

                        $.each(unitdata, function(index, item) {
                            let option = $('<option></option>').val(item).text(item);
                            unit.append(option);
                        });
                        unit.trigger('change');
                        partDesc.val(data.descriptions);
                        exp_date.val(data.expired_date);
                        warehouse.val(data.warehouse_id);

                        console.log(count);
                        $('#name1-' + count).val(data.name1);
                        $('#price1-' + count).val(data.price1);
                        $('#retail1-' + count).val(data.retail1);
                        $('#wholesale1-' + count).val(data.wholesale1);

                        $('#name2-' + count).val(data.name2);
                        $('#price2-' + count).val(data.price2);
                        $('#retail2-' + count).val(data.retail2);
                        $('#wholesale2-' + count).val(data.wholesale2);

                        $('#name3-' + count).val(data.name3);
                        $('#price3-' + count).val(data.price3);
                        $('#retail3-' + count).val(data.retail3);
                        $('#wholesale3-' + count).val(data.wholesale3);


                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

            }

            $(document).ready(function() {
                function calculatePayment() {
                    let total = 0;
                    $('.payment_amount').each(function() {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    total = total;
                    $('#paid').val(total);
                    paidFunction();
                }

                function paidFunction() {
                    let paid = parseFloat($('#paid').val()) || 0;
                    let total_p = parseFloat($('#total').val()) || 0;
                    let balance = total_p - paid;
                    balance = balance;
                    // if($('#currency_method').val() == 'MMK'){
                    //     balance = Math.round(balance);
                    // }else{
                    //     balance = balance.toFixed(2);
                    // }
                    $('#balance').val(balance);
                }

                $(document).on('input', '.payment_amount', function() {
                    calculatePayment();
                });

                $('#paid').on('input', function() {
                    paidFunction();
                });

                let payment_count = 1;
                $('#addRow').click(function() {
                    // Check the number of rows
                    // if ($('#trContainer tr.sub_c').length < 4) {
                    var newRow = `<tr class="sub_c">
                                                <td colspan="3"></td>
                                                <td colspan="3" align="right"><strong></strong></td>
                                                <td align="left" colspan="1" class="col-md-2">
                                                    <input type="text" name="payment_amount[]" class="form-control payment_amount">
                                                </td>
                                                <td align="left" colspan="2" class="col-md-2">
                                                    <div class="input-group">
                                            <select name="payment_method[]" class="form-control"  id="payment_method-${payment_count}"  required>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="removeRow btn btn-danger">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>`;
                    $('#trContainer').append(newRow);
                    getAccount(payment_count)
                    payment_count++;
                    // } else {
                    //     alert('You can only add a maximum of 4 payment rows.');
                    // }
                });
                $(document).on('click', '.removeRow', function() {
                    $(this).closest('tr').remove();
                    payment_count--;
                    calculatePayment();
                });

                calculatePayment();
            });

            $("#addproduct").click(function(e) {
                e.preventDefault();

                $.ajax({

                    type: 'GET',
                    url: "{{ route('get.part.data-unit') }}",
                    data: {
                        // _token: "{{ csrf_token() }}",

                    },
                    success: function(data) {


                        var selectBox = document.getElementById("unit-" + count);

                        // Loop through the data array
                        data.forEach(function(item) {
                            // Create an option element
                            var option = document.createElement("option");

                            // Set the value attribute to the unit id
                            option.value = item.unit;

                            // Set the text of the option to the unit name
                            option.text = item.unit;

                            // Append the option to the select element
                            selectBox.appendChild(option);
                        });


                    },
                    error: function(error) {
                        console.error(error);
                    }
                });


                var jobs = <?php echo json_encode($units); ?>

                count++;
                let rowCount = $("#showitem123 tr").length;

                if ($(".productname").length >= 25) {
                    alert("You can only add up to 25 rows.");
                    return;
                }

                let newRow = '<tr>' +
                    '<td class="text-center" style="display:none">' + (rowCount - 3) + '</td>' +
                    '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="productname-' +
                    count +
                    '" autocomplete="off" placeholder="Enter Product Name" style="background-color: #E9ECEF"><input type="hidden" class="form-control result_item_name typeahead result_item_name" name="result_item_name[]" id="result_item_name-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_descriptions typeahead result_descriptions" name="result_descriptions[]" id="result_descriptions-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_product_code typeahead result_product_code" name="result_product_code[]" id="result_product_code-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_id typeahead result_id" name="result_id[]" id="result_id-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control item_id typeahead item_id" name="item_id[]" id="item_id-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_expired_date typeahead result_expired_date" name="result_expired_date[]" id="result_expired_date-' +
                    count + '" autocomplete="off"></td>' +
                    '<input type="hidden" class="form-control description typeahead" name="part_description[]" required id="description-' +
                    count + '" autocomplete="off">' +
                    '<td><input style="background-color: #E9ECEF" type="text" class="form-control req amnt" name="product_qty[]" onchange="sumQty(' +
                    count + ')" id="qty-' +
                    count +
                    '" autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                    '<td><input type="text" style="background-color: #E9ECEF" class="form-control companyPrice amnt" name="company_price[]" id="company_price-' +
                    count + '" autocomplete="off" value="0" ></td>' +
                    '<td><input type="text" class="form-control foc amnt"style="background-color: #E9ECEF" name="foc[]" onchange="sumQty(' +
                    count + ')" id="foc-' +
                    count + '" autocomplete="off"  value="0" ></td>' +
                    '<td><select class="form-control unit" style="background-color: #E9ECEF" name="item_unit[]" id="unit-' +
                    count +
                    '" autocomplete="off" required></select><span class="mt-0" id="uniterror" style="display: none; color: red;">Please Choose A Unit.</span></td>' +
                    '<td><input type="text" style="background-color: #E9ECEF" class="form-control totalQty" name="totalQty[]" id="totalQty-' +
                    count + '" autocomplete="off" value="1"></td>' +
                    '<td><input type="text" class="form-control discount" style="background-color: #E9ECEF" name="discount[]" id="discount-' +
                    count + '" autocomplete="off" value="0" ></td>' +
                    '<td><input type="text" class="form-control commercialtax" style="background-color: #E9ECEF" name="commercialtax[]" id="commercialtax-' +
                    count + '" autocomplete="off" value="0" ></td>' +
                    '<td style="display:none;"><input type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                    count + '" autocomplete="off"></td>' +
                    '<td style="text-align:center"><input type="text" style="background-color: #E9ECEF" class="form-control amount" name="amount[]" id="amount-' +
                    count + '" autocomplete="off"></td>' +
                    '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                    '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                    '<input type="hidden" class="ttInput" name="product_subtotal[]" value="0">' +
                    '<input type="hidden" class="pdIn" name="product_id[]" id="pid-' + count +
                    '" value="0">' +
                    '<input type="hidden" name="unit_m[]" id="unit_m-' + count + '" value="1">' +
                    '<input type="hidden" name="code[]" id="hsn-' + count + '" value="">' +
                    '<input type="hidden" name="serial[]" id="serial-' + count + '" value="">' +
                    '<td><button  type="button" class="btn btn-danger remove_item_btn" ><i class="fa-solid fa-times"></i></button></td>' +
                    '</tr>' +
                    '<tr><td class="text-center" style="font-weight:bold;">unit</td><td class="text-center" style="font-weight:bold;">Buy Price</td><td class="text-center" style="font-weight:bold;">Wholesale Price</td><td class="text-center" style="font-weight:bold;">Retail Price</td>' +
                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>' +
                    '<tr><td><input type="text" class="form-control name1" name="name1[]" id="name1-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control price1" name="price1[]" id="price1-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control wholesale1" name="wholesale1[]" id="wholesale1-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail1" name="retail1[]" id="retail1-' +
                    count + '" autocomplete="off"></td>' +

                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>' +
                    '<tr><td><input type="text" class="form-control name2" name="name2[]" id="name2-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control price2" name="price2[]" id="price2-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control wholesale2" name="wholesale2[]" id="wholesale2-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail2" name="retail2[]" id="retail2-' +
                    count + '" autocomplete="off"></td>' +

                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>' +
                    '<tr><td><input type="text" class="form-control name3" name="name3[]" id="name3-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control price3" name="price3[]" id="price3-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control wholesale3" name="wholesale3[]" id="wholesale3-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail3" name="retail3[]" id="retail3-' +
                    count + '" autocomplete="off"></td>' +

                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>';

                $("#showitem123").append(newRow);
                $('#showitem123').on('click', '.remove_item_btn', function() {
                    let currentRow = $(this).closest('tr');
                    currentRow.nextAll('tr:lt(4)').addBack().remove();


                });
                initializeTypeahead(count);
            });

            // $(document).on('click', '.remove_item_btn', function(e) {
            // e.preventDefault();
            // let row_item = $(this).closest('tr'); // Find the closest <tr> parent
            // $(row_item).remove();
            // // let row_item = $(this).parent().parent();
            // // $(row_item).remove();


            //             $('#showitem123').on('click', '.remove_item_btn', function() {
            //     $(this).closest('tr').nextUntil(':not(:has(button.remove_item_btn))').addBack().remove();
            //     initializeTypeaheads();
            // });
            // Update row numbers
            // $('#showitem123 tr').each(function(index) {
            //     $(this).find('td:first').text(index + 1);
            // });


            // });

            $(document).on('click', '.typeahead .dropdown-item', function(e) {
                e.preventDefault();

                if ($("#customer").val()) {} else {
                    const row = $(this).closest('tr');

                    const item_name = row.find('.result_item_name').val();
                    const description = row.find('.result_descriptions').val();
                    const product_code = row.find('.result_product_code').val();
                    const expired_date = row.find('.result_expired_date').val();
                    const item_id = row.find('.result_id').val();
                    let cuz_name = $("#type").val();
                    updateItemName(item_name, row, description, item_id, cuz_name, expired_date);
                    $('#productname').val('');
                }
            });


            // Initialize typeahead for the first row
            initializeTypeahead(count);

            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();

                let total = 0;
                let totalTax = 0;

                $('#showitem123 tr').each(function() {
                    let qty = $(this).find('.req.amnt').val();
                    let discount = $(this).find('.discount').val();
                    let price = parseFloat($(this).find('.companyPrice').val());
                    let taxRate = parseFloat($(this).find('.commercialtax').val());
                    let item_amount = $(this).find('.amount');

                    if (!isNaN(price) && !isNaN(qty)) {
                        let itemTax = 0;
                        if (taxRate >= 0) {
                            itemTax = parseFloat(((price * qty * taxRate) / 100));
                            totalTax += itemTax;
                        }

                        if (taxRate > 0 && discount == 0) {
                            let tax = parseFloat(((price * qty * taxRate) / 100));
                            item_amount.val(parseFloat((price * qty) + tax));
                        } else if (!isNaN(discount) && discount > 0 && taxRate == 0) {
                            let item_discount = parseFloat(((price * qty * discount) / 100));
                            item_amount.val(parseFloat((price * qty) - item_discount));
                        } else if (discount > 0 && taxRate > 0) {
                            let tax = parseFloat(((price * qty * taxRate) / 100 + (price * qty)));
                            let item_discount = parseFloat(((tax * discount) / 100));
                            item_amount.val(parseFloat(tax - item_discount));
                        } else {
                            item_amount.val(parseFloat((price * qty)));
                        }

                        total += parseFloat(item_amount.val());
                    }
                });

                $("#invoiceyoghtml").val(parseFloat(total));
                $("#discount_total").val('');
                $("#total").val(parseFloat(total));
            });



            document.getElementById('submitButton').addEventListener('click', function() {
                var serviceType = document.getElementById('unit-' + count).value;
                var serviceTypeError = document.getElementById('uniterror');
                if (serviceType === 'Choose Unit') {
                    serviceTypeError.style.display = 'block';
                } else {
                    serviceTypeError.style.display = 'none';
                    // Add your code to handle the submission without a form
                }
            });

        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", '#calculate', function(e) {
            e.preventDefault();
            let total = 0;
            let totalTax = 0;

            for (let i = 0; i < (count + 1); i++) {

                var qty = $('#qty-' + i).val();
                var discount = $('#discount-' + i).val();

                let price = parseInt($('#company_price-' + i).val());
                let taxRate = parseFloat($('#commercialtax-' + i).val());
                console.log(amount);

                var item_amount = document.getElementById('amount-' + i);

                if (taxRate >= 0) {
                    let itemTax = (price * qty * taxRate) / 100;
                    totalTax += itemTax;
                }

                if (taxRate > 0 && discount == 0) {

                    let tax = (price * qty * taxRate) / 100;
                    item_amount.value = parseFloat((price * qty) + tax);
                } else if (!isNaN(discount) && discount > 0 && taxRate == 0) {
                    let item_discount = (price * qty * discount) / 100;
                    item_amount.value = parseFloat((price * qty) - item_discount);
                } else if (discount > 0 && taxRate > 0) {

                    let tax = (price * qty * taxRate) / 100 + (price * qty);
                    let item_discount = (tax * discount) / 100;
                    item_amount.value = tax - item_discount;
                } else {
                    item_amount.value = parseFloat(price * qty);
                }
                // Add the amount
                var amount = document.getElementById('amount-' + i).value;
                total += parseInt(amount);
            }






            $("#invoiceyoghtml").val(total);
            $("#discount_total").val('');

            $("#total").val(total);

            //set  (amount*price)  per invoice  subtotal


        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on("change", "#balance_due", function() {
                if ($(this).val() == "Purchase Order") { // Check if balance_due is empty
                    $("#supplier_box").show(); // Show the supplier_box
                } else {
                    $("#supplier_box").hide(); // Hide the supplier_box if balance_due is not empty
                }
            });
        });

        $(document).ready(function() {
            $('#location').change(function() {
                var selectedLocation = $(this).val();
                $('#supplier_id option').each(function() {
                    var supplierBranch = $(this).data(
                        'branch');
                    if (supplierBranch == selectedLocation || $(this).val() == '') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('#supplier_id').val('');
            }).change();
        });

        $(document).ready(function() {
            $('#po_no, #po_no_latest').on('input', function() {
                let poNo = parseInt($('#po_no').val().replace("PO - ", ""));
                let poNoLatest = parseInt($('#po_no_latest').val().replace("PO - ", ""));

                if (!isNaN(poNo) && !isNaN(poNoLatest)) {
                    if (poNo < poNoLatest) {
                        $('#po_no').val("PO - " + poNoLatest);
                        alert("The PO number must be greater than the latest PO number.");
                    }
                }
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

    <!--Enter Key click add row-->
    <script>
        //Enter Key click add row
        $(document).on('keydown', '.form-control', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                $('#addproduct').click();
            }
        });
    </script>


</body>

</HTML>
