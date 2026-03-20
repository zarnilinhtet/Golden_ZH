<!DOCTYPE html>
<HTML>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
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
            Purchase Order Edit
        </h1>
        <form method="post" id="data_form" action=" {{ URL('purchase_order_update', $purchase_orders->id) }}"
            enctype="multipart/form-data">
            @csrf

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="content-wrapper" style="background-color:aqua">
                <div class="content-body">
                    <div class="card">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="col-sm-10 cmp-pnl">
                                    <div id="customerpanel" class="inner-cmp-pnl">

                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="po_no" style="font-weight:bolder">Purchase Order
                                                    Number</label>
                                                <input type="text" id="po_no" class="form-control"
                                                    name="po_number" value="{{ $purchase_orders->quote_no }}" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label for="po_no" style="font-weight:bolder">Purchase Order
                                                    Date</label>
                                                <input type="date" name="po_date" class="form-control" required
                                                    value="{{ $purchase_orders->po_date ? $purchase_orders->po_date : $purchase_orders->created_at->format('Y-m-d') }}">
                                            </div>

                                            <div class="frmSearch col-sm-4" id="supplier_box">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="cst"
                                                            class="caption">{{ trans('Supplier Name') }}</label>
                                                    </span>
                                                    <select name="supplier_id" id="" class="form-control">
                                                        <option value="" selected disabled>Choose Supplier
                                                        </option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}"
                                                                @if ($supplier->id == $purchase_sells[0]->supplier_id) selected @endif>
                                                                {{ $supplier->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                            </div>
                                            @php
                                                use Illuminate\Support\Facades\Auth;
                                            @endphp
                                            @if (Auth::user()->type == '0' || Auth::user()->is_admin == '1')
                                                <div class="frmSearch col-sm-4 mt-2">
                                                    <label for="location" style="font-weight:bolder">Choose
                                                        Location</label>
                                                    <select name="location" id="location" class="form-control mb-4"
                                                        required>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                @if ($warehouse->id == $purchase_orders->unit) selected @endif>
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                        break;
                                                    </select>

                                                </div>
                                            @else
                                                <div class="frmSearch col-sm-4 mt-2" style="display: none;">
                                                    <label for="location" style="font-weight:bolder">Choose
                                                        Location</label>
                                                    <select name="location" id="location" class="form-control mb-4"
                                                        required>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                @if ($warehouse->id == Auth::user()->level) selected @endif>
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                        break;
                                                    </select>

                                                </div>

                                            @endif
                                            <div class="frmSearch col-md-3 col-sm-6 mt-2">
                                                <div class="frmSearch col-sm-12">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst" class="caption"> Receiving Mode
                                                            </label>
                                                        </span>
                                                        <select name="balance_due" id="balance_due"
                                                            class="mb-4 form-control balance_due">

                                                            <option value="Purchase Order"
                                                                @if ($purchase_orders->balance_due == 'Purchase Order') selected @endif>
                                                                Purchase Order
                                                            </option>
                                                            <option value="Sale Return Invoice"
                                                                @if ($purchase_orders->balance_due == 'Sale Return Invoice') selected @endif>Sale
                                                                Return</option>

                                                        </select>

                                                        <div id="customer-box-result"></div>
                                                    </div>


                                                </div>



                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 cmp-pnl">

                                        <div class="inner-cmp-pnl">

                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="invoice" name="status">

                                <div class="row " style="margin-top:1vh;">
                                    <table class="">
                                        <thead style="background-color:#2A7774;color:white; border: 1px solid white;">
                                            <tr class="item_header bg-gradient-directional-blue white"
                                                style="margin-bottom:10px;">
                                                <!-- <th width="3%" class="text-center">{{ trans('No') }}</th> -->
                                                <th width="20%" class="text-center">{{ trans('Product Name') }}
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
                                            @foreach ($purchase_sells as $key => $p_sell)
                                                <tr>
                                                    <td class="text-center" style="display:none" id="count">
                                                        {{ $key + 1 }}</td>
                                                    <td><input type="text"
                                                            class="form-control productname typeahead"
                                                            name="part_number[]" style="background-color: #E9ECEF"
                                                            placeholder="{{ trans('Enter Part Number') }}"
                                                            id='productname-0' value="{{ $p_sell->part_number }}"
                                                            autocomplete="off">
                                                        <input type="hidden"
                                                            class="form-control result_item_name typeahead result_item_name"
                                                            name="result_item_name[]" id="result_item_name-0"
                                                            autocomplete="off" value="{{ $p_sell->product_name }}">
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
                                                            name="result_id[]" id="result_id-0"
                                                            value="{{ $p_sell->variation_id }}" autocomplete="off">
                                                        <input type="hidden"
                                                            class="form-control item_id typeahead item_id"
                                                            name="item_id[]" value="{{ $p_sell->item_id }}"
                                                            id="item_id-0" autocomplete="off">
                                                        <input type="hidden"
                                                            class="form-control result_expired_date typeahead result_expired_date"
                                                            name="result_expired_date[]" id="result_expired_date-0"
                                                            value="{{ $p_sell->exp_date }}" autocomplete="off">
                                                    </td>
                                                    <input type="hidden" class="form-control description typeahead"
                                                        name="part_description[]" placeholder="{{ trans('') }}"
                                                        id='description-0' value="{{ $p_sell->description }}"
                                                        autocomplete="off">

                                                    <td><input type="text" style="background-color: #E9ECEF"
                                                            class="form-control req amnt" name="product_qty[]"
                                                            id="qty-{{ $key + 1 }}" autocomplete="off"
                                                            onchange="sumQty({{ $key + 1 }})"
                                                            value="{{ $p_sell->product_qty }}"><input type="hidden"
                                                            id="alert-0" value="" name="alert[]"></td>
                                                    <td><input type="text" class="form-control companyprice "
                                                            name="company_price[]"
                                                            id="company_price-{{ $key + 1 }}" autocomplete="off"
                                                            value="{{ $p_sell->company_price }}"
                                                            style="background-color: #E9ECEF"></td>
                                                    <td><input type="text" style="background-color: #E9ECEF"
                                                            class="form-control foc " name="foc[]"
                                                            id="foc-{{ $key + 1 }}" autocomplete="off"
                                                            value="{{ $p_sell->foc }}"
                                                            onchange="sumQty({{ $key }})">

                                                    </td>

                                                    @foreach ($p_sell->variations as $variation)
                                                        <td>
                                                            <select name="item_unit[]"
                                                                style="background-color: #E9ECEF"
                                                                id="unit-{{ $loop->parent->index + 1 }}"
                                                                class="form-control unit">
                                                                @if ($variation->name1)
                                                                    <option value="{{ $variation->name1 }}"
                                                                        @if ($variation->name1 == $p_sell->unit) selected @endif>
                                                                        {{ $variation->name1 }}
                                                                    </option>
                                                                @endif
                                                                @if ($variation->name2)
                                                                    <option value="{{ $variation->name2 }}"
                                                                        @if ($variation->name2 == $p_sell->unit) selected @endif>
                                                                        {{ $variation->name2 }}
                                                                    </option>
                                                                @endif
                                                                @if ($variation->name3)
                                                                    <option value="{{ $variation->name3 }}"
                                                                        @if ($variation->name3 == $p_sell->unit) selected @endif>
                                                                        {{ $variation->name3 }}
                                                                    </option>
                                                                @endif
                                                            </select>
                                                        </td>
                                                    @endforeach

                                                    <td>
                                                        <input type="text" style="background-color: #E9ECEF"
                                                            class="form-control totalQty" name="totalQty[]"
                                                            id="totalQty-{{ $loop->index + 1 }}" autocomplete="off"
                                                            value="{{ $p_sell->totalQty }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" style="background-color: #E9ECEF"
                                                            class="form-control discount" name="discount[]"
                                                            id="discount-{{ $loop->index + 1 }}" autocomplete="off"
                                                            value="{{ $p_sell->discount }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" style="background-color: #E9ECEF"
                                                            class="form-control commercialtax" name="commercialtax[]"
                                                            id="commercialtax-{{ $loop->index + 1 }}"
                                                            autocomplete="off" value="{{ $p_sell->commercial_tax }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" style="background-color: #E9ECEF"
                                                            class="form-control total" name="amount[]"
                                                            id="amount-{{ $loop->index + 1 }}" autocomplete="off"
                                                            value="{{ $p_sell->amount }}">
                                                    </td>

                                                    <td style="display: none;"><input type="text"
                                                            class="form-control warehouse " name="warehouse[]"
                                                            id="warehouse-0" autocomplete="off"
                                                            value="{{ $p_sell->warehouse }}">
                                                    </td>
                                                    <td style="text-align:center">

                                                        <span class="currenty">{{ config('currency.symbol') }}</span>
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
                                                    <input type="hidden" class="ttInput" name="product_subtotal[]"
                                                        id="total-0" value="0">
                                                    <input type="hidden" class="pdIn" name="product_id[]"
                                                        id="pid-0" value="0">

                                                    <input type="hidden" name="unit_m[]" id="unit_m-0"
                                                        value="1">
                                                    <input type="hidden" name="code[]" id="hsn-0"
                                                        value="">
                                                    <input type="hidden" name="serial[]" id="serial-0"
                                                        value="">

                                                </tr>
                                                <tr class="text-center">
                                                    <td>unit</td>
                                                    <td>Buy Price</td>
                                                    <td>Wholesale Price</td>

                                                    <td>Retail Price</td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="name1[]"
                                                            value="{{ $p_sell->name1 }}"></td>
                                                    <td><input type="text" class="form-control" name="price1[]"
                                                            value="{{ $p_sell->price1 }}"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="wholesale1[]" value="{{ $p_sell->wholesale1 }}">
                                                    </td>

                                                    <td><input type="text" class="form-control" name="retail1[]"
                                                            value="{{ $p_sell->retail1 }}"></td>

                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="name2[]"
                                                            value="{{ $p_sell->name2 }}"></td>
                                                    <td><input type="text" class="form-control" name="price2[]"
                                                            value="{{ $p_sell->price2 }}"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="wholesale2[]" value="{{ $p_sell->wholesale2 }}">
                                                    </td>

                                                    <td><input type="text" class="form-control" name="retail2[]"
                                                            value="{{ $p_sell->retail2 }}"></td>

                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="name3[]"
                                                            value="{{ $p_sell->name3 }}"></td>
                                                    <td><input type="text" class="form-control" name="price3[]"
                                                            value="{{ $p_sell->price3 }}"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="wholesale3[]" value="{{ $p_sell->wholesale3 }}">
                                                    </td>

                                                    <td><input type="text" class="form-control" name="retail3[]"
                                                            value="{{ $p_sell->retail3 }}"></td>

                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tr class="last-item-row sub_c">
                                            <!-- <td></td> -->
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

                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="4">

                                                </td>
                                                <td colspan="2" align="right"><strong>Sub Total
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="4" class="col-md-4"><input
                                                        type="text" name="sub_total"
                                                        value="{{ $purchase_orders->net_total }}"
                                                        class="form-control" id="invoiceyoghtml" readonly
                                                        style="background-color: #E9ECEF" id="subtotal">
                                                </td>
                                            </tr>

                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="4">

                                                </td>
                                                <td colspan="2" align="right"><strong>Total Discount
                                                    </strong>
                                                </td>
                                                <td align="left" colspan="4" class="col-md-4"><input
                                                        type="text" value="{{ $purchase_orders->discount_total }}"
                                                        name="discount_total" class="form-control"
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
                                                        class="form-control"
                                                        value="{{ $purchase_orders->invoice_category }}"
                                                        id="discount_total_percent">
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
                                                        id="total" value="{{ $purchase_orders->total }}"
                                                        readonly>

                                                </td>
                                            </tr>
                                        <tbody id="trContainer">
                                            @forelse ($payment_method as $index => $payment)
                                                <tr class="sub_c">
                                                    <td colspan="3"></td>
                                                    <td colspan="3" align="right">
                                                        @if ($index === 0)
                                                            <strong>Payment Method</strong>
                                                        @endif
                                                    </td>
                                                    <td align="left" colspan="2" class="col-md-2">
                                                        <input type="text" name="payment_amount[]"
                                                            class="form-control payment_amount" id="payment_amount"
                                                            value="{{ $payment->payment_amount }}">
                                                        <input type="hidden" name="payment_id[]"
                                                            class="form-control payment_id" id="payment_id"
                                                            value="{{ $payment->id }}">
                                                    </td>
                                                    <td align="left" colspan="3"
                                                        class="col-md-2 payment_method">
                                                        <div class="input-group">
                                                            <select name="payment_method[]"
                                                                id="payment_method-{{ $index }}"
                                                                class="form-control">
                                                                @foreach ($transactions as $transactionCollection)
                                                                    @foreach ($transactionCollection as $transaction)
                                                                        <option value="{{ $transaction->id }}"
                                                                            @if ($transaction->id == $payment->transaction_id) selected @endif>
                                                                            {{ $transaction->transaction_name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                @if ($index === 0)
                                                                    <button type="button" id="addRow"
                                                                        class="btn btn-primary">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </button>
                                                                @else
                                                                    <button class="removeRow btn btn-danger"><i
                                                                            class="fa-solid fa-minus"></i></button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr class="sub_c">
                                                    <td colspan="3"></td>
                                                    <td colspan="3" align="right"><strong>Payment
                                                            Method</strong></td>
                                                    <td align="left" colspan="2" class="col-md-2">
                                                        <input type="text" name="payment_amount[]"
                                                            class="form-control payment_amount" id="payment_amount"
                                                            required>
                                                    </td>
                                                    <td align="left" colspan="3"
                                                        class="col-md-2 payment_method">
                                                        <div class="input-group">
                                                            <select name="payment_method[]" id="payment_method-0"
                                                                class="form-control payment_method" required>
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
                                            @endforelse


                                        </tbody>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="4">

                                            </td>
                                            <td colspan="2" align="right"><strong>Deposit
                                                </strong>
                                            </td>
                                            <td align="left" colspan="4" class="col-md-4"><input type="text"
                                                    name="paid" class="form-control" id="paid"
                                                    onchange="paidFunction()" value="{{ $purchase_orders->deposit }}"
                                                    readonly>

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
                                                    readonly="" value="{{ $purchase_orders->remain_balance }}">

                                            </td>
                                        </tr>

                                        <tr class="sub_c " style="display: table-row;">
                                            <td colspan="10"> <label for="remark">Remark</label>
                                                <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                            </td>
                                        </tr>
                                        <tr class="sub_c " style="display: table-row;">


                                            <td align="right" colspan="10">

                                                <button id="submitButton" class="mt-3 btn btn-danger"
                                                    type="submit">Update</button>


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
            document.getElementById('total').value = parseFloat(total);
        });
    </script>
    <script>
        document.getElementById('discount_total_percent').addEventListener('input', function() {
            var discountTotal = document.getElementById('discount_total');
            discountTotal.value = "";

            const subTotal = parseFloat(document.getElementById('invoiceyoghtml').value) || 0;
            const discountTotalPercent = parseFloat(this.value) || 0;
            const total = subTotal - (discountTotalPercent * subTotal / 100);
            document.getElementById('total').value = parseFloat(total);
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
            let count = {{ $purchase_sells->count() }};

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

                var payment_count = @json($payment_method).length + 1;
                $('#addRow').click(function() {

                    // Check the number of rows
                    // if ($('#trContainer tr.sub_c').length < 4) {
                    var newRow = `<tr class="sub_c">
                                        <td colspan="3"></td>
                                        <td colspan="3" align="right"><strong></strong></td>
                                        <td align="left" colspan="2" class="col-md-2">
                                            <input type="text" name="payment_amount[]" class="form-control payment_amount">
                                        </td>
                                        <td align="left" colspan="3" class="col-md-2">
                                            <div class="input-group">
                                                <select name="payment_method[]" id="payment_method-${payment_count}" class="form-control" required>
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

                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none"><i class="fa-solid fa-times"></i></button></td></tr>';

                $("#showitem123").append(newRow);
                $('#showitem123').on('click', '.remove_item_btn', function() {
                    let currentRow = $(this).closest('tr');
                    currentRow.nextAll('tr:lt(4)').addBack().remove();


                });
                initializeTypeahead(count);
            });


            $(document).on('click', '.remove_item_btn', function(e) {
                e.preventDefault();
                $(this).closest('tr').nextAll('tr:lt(4)').addBack().remove();
                count--;

                initializeTypeaheads();
            });


            $(document).on('click', '.typeahead .dropdown-item', function(e) {
                e.preventDefault();

                if ($("#customer").val()) {} else {
                    const row = $(this).closest('tr');

                    const item_name = row.find('.result_item_name').val();
                    const description = row.find('.result_descriptions').val();
                    // const product_code = row.find('.result_product_code').val();
                    const item_id = row.find('.result_id').val();
                    const expired_date = row.find('.result_expired_date').val();
                    let cuz_name = $("#type").val();
                    updateItemName(item_name, row, description, item_id, cuz_name, expired_date);
                    $('#productname').val('');
                }
            });


            // Initialize typeahead for the first row
            initializeTypeahead(count);

            //calculate total
            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                let totalTax = 0;
                var count1 = document.getElementsByClassName('productname').length;
                console.log(count1); // Logs the number of elements with class 'productname'

                for (let i = 1; i < (count1 + 1); i++) {
                    var qty = parseInt($('#totalQty-' + i).val() || 0);
                    var discount = parseInt($('#discount-' + i).val() || 0);
                    let price = parseFloat($('#company_price-' + i).val() || 0);
                    let taxRate = parseFloat($('#commercialtax-' + i).val() || 0);

                    var item_amount = document.getElementById('amount-' + i);
                    console.log(item_amount);

                    if (taxRate >= 0) {
                        let itemTax = (price * qty * taxRate) / 100;
                        totalTax += parseFloat(itemTax);
                    }

                    if (taxRate > 0 && discount === 0) {
                        let tax = (price * qty * taxRate) / 100;
                        item_amount.value = parseFloat(((price * qty) + tax));
                    } else if (!isNaN(discount) && discount > 0 && taxRate === 0) {
                        let item_discount = (price * qty * discount) / 100;
                        item_amount.value = parseFloat(((price * qty) - item_discount));
                    } else if (discount > 0 && taxRate > 0) {
                        let tax = ((price * qty * taxRate) / 100) + (price * qty);
                        let item_discount = (tax * discount) / 100;
                        item_amount.value = parseFloat((tax - item_discount));
                    } else {
                        item_amount.value = parseFloat((price * qty));
                    }

                    // Add the amount
                    var amount = parseFloat(document.getElementById('amount-' + i).value || 0);
                    total += amount;
                }
                console.log(total);

                $("#invoiceyoghtml").val(parseFloat(total));
                $("#discount_total").val('');
                $("#total").val(parseFloat(total));
            });


        });
    </script>
    <script>
        $(document).on('click', '.remove_item_btn', function(e) {

            $(this).closest('tr').nextAll('tr:lt(4)').addBack().remove();
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



        });
    </script>


    <script>
        $(document).ready(function() {

            var path = "{{ route('po_search') }}";
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

            $(document).one('click', '#customer_search', function(e) {
                e.preventDefault();
                let serialNumber = $("#customer").val();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('po_search_fill') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        model: serialNumber // Adjusted to match server-side parameter name
                    },
                    success: function(data) {
                        console.log(data);
                        $("#supplier_id").val(data['product']['id']);

                        $("#name").val(data['product']['name']);
                        $("#phno").val(data['product']['phno']);
                        $("#address").val(data['product']['address']);
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
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                $('#addproduct').click();
            }
        });


        $(document).ready(function() {

            var purchase_orders = <?php echo json_encode($purchase_orders->balance_due); ?>;
            if (purchase_orders == "Sale Return Invoice") {

                $("#supplier_box").hide();
            }

            $(document).on("change", "#balance_due", function() {
                if ($(this).val() == "Purchase Order") { // Check if balance_due is empty
                    $("#supplier_box").show();

                } else {

                    $("#supplier_box").hide();

                }
            });
        });
    </script>


</body>

</HTML>
