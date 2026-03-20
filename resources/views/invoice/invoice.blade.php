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
        tfoot td[colspan] {
            border: none !important;
            background-color: #FFFFFF !important;
        }


        :root {
            --primary-color: #2A7774;
            /* Changed to red */
            --secondary-color: #f8f9fa;
            --accent-color: #ff6b7c;
            /* Lighter red for accents */
        }

        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .invoice-container {
            margin: 20px auto;
            background: white;
            box-shadow: 0 0.15rem 1rem rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .invoice-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.25rem 1.75rem;
            border-bottom: 3px solid rgba(0, 0, 0, 0.1);
        }





        .form-control-sm {
            padding: 0.45rem 0.65rem;
            font-size: 0.9rem;
            border: 1px solid #ddd;
            border-radius: 0.35rem;
        }

        .form-control-sm:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(214, 41, 62, 0.2);
        }

        .invoice-header input {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .invoice-header input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .invoice-header input:focus {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
        }
    </style>
    <style>
        .special_price_td .d-flex {
            flex-direction: row;
        }

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

        .flip-flop-btn.Ks,
        .flip-flop-btn.super_Ks {
            background-color: #4CAF50;
            /* Color for 'ks' state */
            text-align: center;
        }

        .flip-flop-btn.percent,
        .flip-flop-btn.super_percent {
            background-color: #2196F3;
            /* Color for '%' state */
            text-align: center;
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

</head>

<body>

    <div class="containerw" id="content">


        <div class="row mx-1">
            {{-- <h1 class="mt-2 col-md-4">
                Invoice
            </h1> --}}


            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif




            <form method="post" id="myForm" action="{{ url('invoice_register') }}" enctype="multipart/form-data">
                @csrf
                <div class="invoice-header mt-1">
                    <div class="row">
                        <div class="col">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Invoice</h3>
                                <div class="row g-2 align-items-center"> <!-- Nested row for inputs -->
                                    <div class="col-md-6 col-6">
                                        <!-- 6 columns on medium screens, 12 (full width) on mobile -->
                                        <span class="text-nowrap">Invoice #:
                                            <input type="text"
                                                class="form-control form-control-sm d-inline-block ms-1 invoice_no"
                                                style="width: 180px;" name="invoice_no" id="invoice_no"
                                                placeholder="INV-001" required value="{{ $invoice_no }}">
                                        </span>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <span class="text-nowrap">DO No. #:
                                            <input type="text"
                                                class="form-control form-control-sm d-inline-block ms-1 delivery_no"
                                                style="width: 180px;" name="delivery_no" id="delivery_no"
                                                placeholder="DO-001" required value="DO - {{ $delivery_no }}">
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mt-1 ml-1">



                    <div class="my-1  row">
                        {{-- <div class="col-md-2 ">
                            <label for="date" style="font-weight:bolder">Invoice Number</label>
                            <input type="text" id="invoice_no" class="form-control" name="invoice_no"
                                value="Invoice - {{ $invoice_no }}">
                            <input type="hidden" id="invoice_no_latest" class="form-control" name=""
                                value="{{ $invoices }}">
                        </div> --}}

                        <div class="col-md-2 ">
                            <label for="date" class="" style="font-weight:bolder">Date</label>
                            <input type="date" name="invoice_date" class="form-control"
                                required value="{{ date('Y-m-d') }}">

                        </div>
                        <div class="col-md-2 ">
                            <label for="overdue" class="mt-1 caption"
                                style="font-weight:bolder">{{ trans(' OverDue Date') }}</label>
                            <input type="date" name="overdue_date" id="overdue_date" class="form-control round"
                                autocomplete="off" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-sm-2  d-none">
                            <div class="form-group">

                                <span style="font-weight:bolder">
                                    <label for="cst" class="caption mt-1">Register
                                        Mode</label>
                                </span>
                                <select name="balance_due" id="balance_due" class="mb-4 form-control balance_due"
                                    required>

                                    <option value="Invoice">Invoice</option>
                                    <option value="Po Return">Po Return</option>

                                </select>



                            </div>
                        </div>
                        @if (Auth::user()->type == '0' || Auth::user()->is_admin == '1')


                            <div class="col-sm-2 ">
                                <div class="form-group">
                                    <label for="location" style="font-weight:bolder" class="mt-1">
                                        Location</label>
                                    <select name="location" id="location" class="form-control mb-4" required>

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
                                <select name="location" id="location" class="form-control mb-4" required>

                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            @if ($warehouse->id == auth::user()->level) selected @endif>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label for="payment" style="font-weight:bolder">{{ trans('Sale Price Category') }}</label>
                            <select class="form-control round mb-4" aria-label="Default select example"
                                name="sale_price_category" id="sale_price_category" required>
                                <option value="Default" selected>Default</option>
                                <option value="Retail">Retail</option>
                                <option value="Whole Sale">Whole Sale</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="inout" style="font-weight:bolder">Sale Person</label>
                            <select name="sale_person_id" class="form-control" id="sale_person">
                                @foreach ($sale_persons as $sale_person)
                                    <option value="{{ $sale_person->id }}">{{ $sale_person->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 mt-1 ">
                            <label for="payment" style="font-weight:bolder">{{ trans('Payment Method') }}</label>
                            <select class="form-control round mb-3" aria-label="Default select example"
                                name="payment_method" required>

                                <option value="Cash">Cash</option>
                                <option value="KPay">KPay</option>
                                <option value="Wave Pay">Wave Pay</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <input type="hidden" name="quote_category" id="quote_category" value="Invoice"
                            class="form-control">
                    </div> --}}
                    <hr class="">

                    <div class="content-wrapper">
                        {{-- <div class="content-body">
                                <div class="mx-1">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 cmp-pnl">

                                                    <div class="row">
                                                        <div class="frmSearch col-md-4">
                                                            <span style="font-weight:bolder">
                                                                <label for="cst"
                                                                    class="caption">{{ trans('Search  Patient Name & Phone No.') }}</label>
                                                            </span>
                                                            <div class="form-group d-flex">
                                                                <input type="text" id="customer" name="customer"
                                                                    class="mr-2 form-control round" autocomplete="off"
                                                                    placeholder="Search.....">
                                                                &nbsp;&nbsp;&nbsp; <button type="submit"
                                                                    class="btn btn-primary"
                                                                    id="customer_search">Add</button>
                                                            </div>
                                                            <div id="customer-box-result"></div>
                                                        </div>

                                                        <div class="col-md-3">

                                                            <div class="form-group"> <label for=""
                                                                    class="text-danger"
                                                                    style="font-weight: bold">Haven't
                                                                    Patient? Register
                                                                    Here</label> <br>
                                                                <button type="button" data-toggle="modal"
                                                                    data-target="#modal-lg"
                                                                    class="btn btn-secondary">Patient
                                                                    Register</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-center mt-4">
                                                            <div class="form-check me-3">
                                                                <input type="radio" value="other"
                                                                    name="treatment_and_other"
                                                                    class="form-check-input toggle-table"
                                                                    style="transform: scale(1.5);">
                                                                <label class="form-check-label">Others</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="radio" value="treatment"
                                                                    name="treatment_and_other"
                                                                    class="form-check-input toggle-table"
                                                                    style="transform: scale(1.5);" checked>
                                                                <label class="form-check-label">Treatment</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3" id="doctor_div" style="display:block;">
                                                            <div class="form-group">
                                                                <label for="doctor"
                                                                    style="font-weight:bolder">Doctor
                                                                    Name <span
                                                                        class="text-danger fw-bold">*</span></label>
                                                                <select name="doctor_id" id="doctor_id"
                                                                    class="form-control" required>
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




                                                    </div>



                                                </div>



                                            </div>


                                        </div>


                                    </div>
                                </div>

                            </div> --}}
                        <input type="hidden" id="service_id" name="service_id" value="0">


                        <input type="hidden" name="manager_type" value="{{ Auth::user()->type }}">

                        <input type="text" name="status" class="form-control" value="invoice"
                            style="display: none">
                        <div class="row ">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="customer_id" id="customer_id">
                                    <label for="customer" style="font-weight:bolder">Customer
                                        Name</label>
                                    <input type="text" name="customer_name" class="form-control round"
                                        id="patient_name" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="phone_no" style="font-weight:bolder">Phone
                                        Number</label>
                                    <input type="text" id="phone_no" name="phno" class="form-control round"
                                        autocomplete="off">
                                </div>
                            </div>

                            {{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="age" style="font-weight:bolder">Age</label>
                                        <input type='number' name='age' class="form-control" id="age"
                                            readonly>
                                    </div>
                                </div> --}}
                            {{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="age" style="font-weight:bolder">Department</label>
                                        <input type='text' name='service_type' class="form-control"
                                            id="service_type" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="age" style="font-weight:bolder">IN/OUT Patient</label>
                                        <input type='text' name='patient_type' class="form-control"
                                            id="patient_type" readonly>
                                    </div>
                                </div> --}}



                            {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address" style="font-weight:bolder">Deposit</label>
                                        <input type='text' name='customer_deposit' class="form-control"
                                            id="customer_deposit" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none">
                                    <div class="form-group">
                                        <label for="address" style="font-weight:bolder">NRC</label>
                                        <input type='text' name='nrc' class="form-control" id="nrc">
                                    </div>
                                </div> --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="address" style="font-weight:bolder">Date of
                                        Birth</label>
                                    <input type='date' name='dob' class="form-control" id="dob">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address" style="font-weight:bolder">Address</label>
                                    <input type='text' name='address' class="form-control" id="address">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="address" style="font-weight:bolder">Sale Category</label>
                                    <select name="category" class="form-control" id="">
                                        <option value="Consignment">Consignment</option>
                                        <option value="COD">COD</option>
                                        <option value="Credit">Credit</option>
                                        <option value="FOC">FOC</option>
                                        <option value="FOC Rental">FOC Rental</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2" style="display: none;">
                                <div class="form-group">
                                    <label for="address" style="font-weight:bolder">C.D.C
                                        Number</label>
                                    <input type='text' name='cdc_no' class="form-control" id="cdc_no">
                                </div>
                            </div>
                            <div class="col-md-2" style="display: none;">
                                <div class="form-group">
                                    <label for="address" style="font-weight:bolder">Company</label>
                                    <input type='text' name='company' class="form-control" id="company">
                                </div>
                            </div>

                            {{-- <div class="col-md-3">
                                    <label for="inout" style="font-weight:bolder"> Patient Type (FOC)</label>
                                    <select name="foc_patient" class="form-control" id="">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div> --}}
                        </div>
                        {{-- <div class="form-group" id="treatment_remark_div" style="display: block;">
                                <label for="treatment_remark" style="font-weight: bold;">Treatment
                                    Remark</label>
                                <textarea name="treatment_remark" id="treatment_remark" cols="30" rows="3" class="form-control"
                                    placeholder="Enter treatment remark..."></textarea>
                                <input type="hidden" name="treatment_id" id="treatment_id">
                            </div> --}}


                    </div>
                    <div class="row table-responsive " style="margin-top:1vh;">
                        <!-- <table class="table-responsive tfr my_stripe"> -->
                        <table class="table table-bordered ml-2">
                            <thead style="background-color:#2A7774;color:white;">
                                <tr class="item_header bg-gradient-directional-blue white"
                                    style="margin-bottom:10px;">
                                    <th width="3%" class="text-center">{{ trans('No') }}
                                    </th>
                                    <th width="23%" class="text-center">
                                        {{ trans('Product Name ') }}
                                    </th>
                                    <!-- <th width="23%" class="text-center">
                                                            {{ trans('Descriptions') }}
                                                        </th> -->
                                    <th width="6%" class="text-center">
                                        {{ trans('Qty') }}
                                    </th>
                                    <th width="8%" class="text-center">{{ trans('Unit') }}
                                    </th>

                                    <th width="10%" class="whole_sale text-center">
                                        {{ trans('Wholesale Price') }}
                                    </th>
                                    <th width="10%" class="retailprice text-center">
                                        {{ trans('Price') }}
                                    </th>
                                    <th width="12%" class=" text-center">
                                        {{ trans('Special Price') }}
                                    </th>
                                    <th width="10%" class="buyprice text-center">
                                        {{ trans('Buy Price') }}
                                    </th>
                                    <th width="13%" class="text-center">
                                        {{ trans('Discounts') }}
                                    </th>
                                    <th width="15%" class="text-center">
                                        {{ trans('Special Discounts') }}
                                    </th>
                                    <th class="d-none" width="14%" class="text-center">
                                        {{ trans('Expiry') }}
                                    </th>

                                    <th width="10%" class="text-center">{{ trans('Amount') }}

                                    </th>
                                    <th width="3%" class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody id="showitem123">
                                <tr>
                                    <td class="text-center" id="count">1</td>
                                    <td>
                                        <input type="text" class="form-control productname typeahead item_name"
                                            name="part_number[]" value="{{ old('part_number') }}"
                                            placeholder="{{ trans('Enter Product Number') }}" id="productname-0"
                                            autocomplete="off">
                                        <input type="hidden"
                                            class="form-control result_item_name typeahead result_item_name"
                                            name="result_item_name[]" id="result_item_name-0" autocomplete="off">
                                        <input type="hidden"
                                            class="form-control result_descriptions typeahead descriptions"
                                            name="result_descriptions[]" id="result_descriptions-0"
                                            autocomplete="off">
                                        <input type="hidden"
                                            class="form-control result_product_code typeahead result_product_code"
                                            name="result_product_code[]" id="result_product_code-0"
                                            autocomplete="off">
                                        <input type="hidden" class="form-control result_id typeahead result_id"
                                            name="result_id[]" id="result_id-0" autocomplete="off">
                                        <input type="hidden" class="form-control item_id typeahead item_id"
                                            name="item_id[]" id="item_id-0" autocomplete="off">
                                        <input type="hidden"
                                            class="form-control result_expired_date typeahead result_expired_date"
                                            name="result_expired_date[]" id="result_expired_date-0"
                                            autocomplete="off">
                                    </td>
                                    <input type="hidden" class="form-control description typeahead"
                                        value="{{ old('part_description') }}" name="part_description[]"
                                        placeholder="{{ trans('') }}" id='description-0' autocomplete="off">
                                    <!-- <td ><input type="hidden"
                                                                class="form-control description typeahead"
                                                                value="{{ old('part_description') }}"
                                                                name="part_description[]"
                                                                placeholder="{{ trans('') }}" id='description-0'
                                                                required autocomplete="off"></td> -->
                                    <td><input type="text" class="form-control product-qty req amnt" name="product_qty[]"
                                            id="amount-0" autocomplete="off" value="1"><input type="hidden"
                                            id="alert-0" value="" name="alert[]"></td>
                                    <td>


                                        <select class="form-control unit" id="unit-0" required name="item_unit[]">
                                        </select>
                                    </td>



                                    <td class="whole_sale_price_td">
                                        <input type="text" class="form-control price" name="product_price[]"
                                            id="price-0" autocomplete="off" value="0">

                                    </td>

                                    <td class="retail_price_td">

                                        <input type="text" class="form-control retail_price" name="retail_price[]"
                                            id="retail_price-0" autocomplete="off" value="0">



                                    </td>
                                    <td class="special_price_td">
                                        <div class="input-group">
                                            <input type="text" class="form-control special_price"
                                                name="special_price[]" id="special_price-0" autocomplete="off"
                                                value="1" style="width: 90px;">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="is_special_price[]" id="is_special_price-0">
                                                </div>
                                            </div>
                                        </div>
                                    </td>



                                    <td class="buy_price_td">
                                        <input type="text" class="form-control buy_price" name="buy_price[]"
                                            id="buy_price-0" autocomplete="off" value="0">
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-between">
                                            <input type="text" class="form-control item_discount col-md-8"
                                                name="item_discount[]" id="item_discount-0" value="0"
                                                autocomplete="off" value="{{ old('item_discount') }}">
                                            <a href="javascript:void(0)" id="flipFlopButton-0"
                                                class="flip-flop-btn form-control Ks col-md-4"
                                                style="text-decoration: none; margin-left: 10px;">Ks</a><input
                                                type="hidden" id="valueIndicator-0" name="valueIndicator[]"
                                                value="Ks">


                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-between">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control super_item_discount col-md-12"
                                                    name="super_item_discount[]" id="super_item_discount-0"
                                                    value="0" autocomplete="off"
                                                    value="{{ old('super_item_discount') }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="is_special_discount[]" id="is_special_discount-0">
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0)" id="super_flipFlopButton-0"
                                                class="flip-flop-btn form-control super_Ks col-md-4"
                                                style="text-decoration: none; margin-left: 10px;">Ks</a><input
                                                type="hidden" id="super_valueIndicator-0"
                                                name="super_valueIndicator[]" value="Ks">


                                        </div>
                                    </td>

                                    <td class="d-none"><input type="text" class="form-control exp_date "
                                            name="exp_date[]" id="exp_date-0" autocomplete="off">
                                    </td>

                                    <td style="display: none;"><input type="text" class="form-control warehouse"
                                            name="warehouse[]" id="warehouse-0" autocomplete="off">
                                    </td>
                                    <td style="text-align:center">
                                        <span class='ttlText' id="foc-0"></span>
                                        <span class="currenty">{{ config('currency.symbol') }}</span>
                                        <strong>
                                            <span class='ttlText' id="result-0"></span>
                                        </strong>
                                    </td>
                                    <td><button type="submit" class="btn btn-danger remove_item_btn"
                                            id ="removebutton"><i class="fa-solid fa-times"></i></button></td>
                                    <input type="hidden" class="form-control vat " name="product_tax[]"
                                        id="vat-0" value="0">
                                    <input type="hidden" name="total_tax[]" id="taxa-0" value="0">
                                    <input type="hidden" name="total_discount[]" id="disca-0" value="0">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0"
                                        value="0">
                                    <input type="hidden" class="pdIn" name="product_id[]" id="pid-0"
                                        value="0">
                                    <input type="hidden" attr-org="" name="unit[]" id="unit-0"
                                        value="">
                                    <input type="hidden" name="unit_m[]" id="unit_m-0" value="1">
                                    <input type="hidden" name="code[]" id="hsn-0" value="">
                                    <input type="hidden" name="serial[]" id="serial-0" value="">

                                </tr>
                            </tbody>

                        </table>

                        <table class="mt-1">

                            <tbody id="showitem">

                                <tr class="last-item-row sub_c">
                                    <td></td>

                                    <td class="add-row">
                                        <button type="button" class="btn btn-success" id="addproduct"
                                            style="margin-top:20px;margin-bottom:20px;">
                                            <i class="fa fa-plus-square"></i>
                                            {{ trans('Add row') }}
                                        </button>
                                        <button type="button" class="btn btn-primary" id="calculate">
                                            Calculate
                                        </button>
                                        {{-- <button type="button" class="btn btn-danger" id="hide-group">
                                           Special
                                        </button> --}}
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modal-lg">
                                            Special
                                        </button>


                                        {{-- @if (in_array('Item', $userPermissions) || auth()->user()->is_admin == '1')
                                            <a href="{{ URL('items') }}" target="_blank" id="item_search">
                                                <button type="button" class="btn btn-success">
                                                    <i class="fa fa-plus-square"></i> Item Search
                                                </button></a>
                                        @endif --}}
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
                                    <td colspan="3" align="right">
                                    </td>

                                    <td colspan="2" align="right" class="col-md-2"><strong>Sub Total
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="sub_total" class="form-control" id="invoiceyoghtml" readonly
                                            style="background-color: #E9ECEF">

                                    </td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="3" align="right"></td>
                                    <td colspan="2" align="right"><strong>Item Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="commercial_text" class="form-control" id="commercial_text"
                                            readonly>

                                    </td>

                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="3" align="right">
                                    </td>

                                    <td colspan="2" align="right"><strong>Overall Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2"class="col-md-4"><input type="text"
                                            name="discount" class="form-control" id="total_discount">

                                    </td>

                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="3" align="right">
                                    </td>

                                    <td colspan="2" align="right"><strong>Total Amount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="total" class="form-control" id="total_amount" readonly
                                            style="background-color: #E9ECEF">
                                    </td>

                                </tr>
                            <tbody id="trContainer">
                                <tr class="sub_c">
                                    <td colspan="3" align="right"></td>
                                    </td>
                                    <td colspan="2" align="right"><strong>Payment
                                            Method</strong></td>
                                    <td align="left" colspan="1" class="col-md-2">
                                        <input type="text" name="payment_amount[]"
                                            class="form-control payment_amount" id="payment_amount" required>
                                    </td>

                                    <td align="left" colspan="1" class="col-md-2 payment_method">
                                        <div class="input-group">
                                            <select name="payment_method[]" id="payment_method-0"
                                                class="form-control payment_method" required>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" id="addRow" class="btn btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tr class="sub_c" style="display:table-row">
                                <td colspan="2" align="right">
                                </td>

                                <td colspan="3" align="right"><strong>Deposit
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="deposit" class="form-control" id="deposit"
                                        onchange="paidFunction()">
                                </td>
                            </tr>
                            <tr class="sub_c">
                                <td colspan="2" align="right">
                                <td colspan="3" align="right"><strong>Remaining Balance
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="balance" class="form-control" id="balance" readonly="">
                                </td>
                            </tr>
                            </tbody>
                            {{-- special model --}}

                            {{-- end special model --}}


                            {{-- special --}}


                            {{-- <tr><td><hr></td></tr>
                                <tr class="hide d-none" style="display: table-row;">
                                   <td colspan="2" align="right" class="col-md-2 ">
                                    </td>

                                    <td colspan="3" align="right" class="col-md-2"><strong>Special Sub Total
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="super_sub_total" class="form-control" id="super_invoiceyoghtml" readonly
                                            style="background-color: #E9ECEF">

                                    </td>
                                </tr>
                                <tr class="hide d-none" style="display: table-row;">
                                   <td colspan="2" align="right"></td>
                                    <td colspan="3" align="right"><strong>Special Item Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="super_commercial_text" class="form-control" id="super_commercial_text"
                                            readonly>

                                    </td>

                                </tr>
                                <tr class="hide d-none" style="display: table-row;">
                                   <td colspan="2" align="right">
                                    </td>

                                    <td colspan="3" align="right"><strong>Special Overall Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2"class="col-md-4"><input type="text"
                                            name="super_discount" class="form-control" id="super_total_discount">

                                    </td>

                                </tr>
                                 <tr class="hide d-none" style="display: table-row;">
                                   <td colspan="2" align="right">
                                    </td>

                                    <td colspan="3" align="right"><strong>Special Total Amount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="super_total" class="form-control" id="super_total_amount" readonly
                                            style="background-color: #E9ECEF">
                                    </td>

                                </tr>
                                 <tbody id="trContainer">
                                <tr class="hide d-none" >
                                   <td colspan="2" align="right"></td>
                                    </td>
                                    <td colspan="3" align="right"><strong>Special Payment
                                            Method</strong></td>
                                    <td align="left" colspan="1" class="col-md-2">
                                        <input type="text" name="super_payment_amount[]"
                                            class="form-control super_payment_amount" id="super_payment_amount" required>
                                    </td>

                                    <td align="left" colspan="1" class="col-md-2 special_payment_method">
                                        <div class="input-group">
                                            <select name="super_payment_method[]" id="super_payment_method-0"
                                                class="form-control super_payment_method" required>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" id="super_addRow" class="btn btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                                <tr class="hide d-none " style="display:table-row">
                                <td colspan="2" align="right">
                                </td>

                                <td colspan="3" align="right"><strong>Special Deposit
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="super_deposit" class="form-control" id="super_deposit"
                                        onchange="super_paidFunction()">
                                </td>
                            </tr>
                            <tr class="hide d-none" >
                                <td colspan="2" align="right">
                                <td colspan="3" align="right"><strong>Special Remaining Balance
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="super_balance" class="form-control" id="super_balance" readonly="">
                                </td>
                            </tr>
                            </tbody> --}}
                            {{-- end special --}}
                            {{-- <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2">

                                    </td>
                                    <td colspan="3" align="right" class="col-md-4"><strong>Sub Total
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="sub_total" class="form-control" id="invoiceyoghtml" readonly
                                            style="background-color: #E9ECEF">

                                    </td>

                                </tr>

                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2">

                                    </td>
                                    <td colspan="3" align="right"><strong>Item Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="commercial_text" class="form-control" id="commercial_text"
                                            readonly>

                                    </td>

                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2">

                                    </td>
                                    <td colspan="3" align="right"><strong>Overall Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2"class="col-md-4"><input type="text"
                                            name="discount" class="form-control" id="total_discount">

                                    </td>

                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2">

                                    </td>
                                    <td colspan="3" align="right"><strong>Total Amount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="total" class="form-control" id="total_amount" readonly
                                            style="background-color: #E9ECEF">
                                    </td>

                                </tr>
                            <tbody id="trContainer">
                                <tr class="sub_c">
                                    <td colspan="2"></td>
                                    <td colspan="3" align="right"><strong>Payment
                                            Method</strong></td>
                                    <td align="left" colspan="1" class="col-md-2">
                                        <input type="text" name="payment_amount[]"
                                            class="form-control payment_amount" id="payment_amount" required>
                                    </td>

                                    <td align="left" colspan="1" class="col-md-2 payment_method">
                                        <div class="input-group">
                                            <select name="payment_method[]" id="payment_method-0"
                                                class="form-control payment_method" required>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" id="addRow" class="btn btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                            <tr class="sub_c" style="display:table-row">
                                <td colspan="2">

                                </td>
                                <td colspan="3" align="right"><strong>Deposit
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="deposit" class="form-control" id="deposit"
                                        onchange="paidFunction()">
                                </td>
                            </tr>
                            <tr class="sub_c">
                                <td colspan="2">

                                </td>
                                <td colspan="3" align="right"><strong>Remaining Balance
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="balance" class="form-control" id="balance" readonly="">
                                </td>
                            </tr> --}}

                            <tr class="sub_c " style="display: table-row;">
                                <td colspan="12"> <label for="remark">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr class="sub_c " style="display: table-row;">
                                <td align="right" colspan="9">

                                    <button id="submitButton" class="mt-3 btn btn-danger"
                                        type="submit">Save</button>
                                    <a href="{{ url('invoice') }}" type="submit"
                                        class="mt-3 btn btn-warning">Cancel
                                    </a>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

        </div>
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Special Payment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <tbody>
                                <tr class="" style="display: table-row;">
                                    <td colspan="2" align="right" class="col-md-2 ">
                                    </td>

                                    <td colspan="3" align="right" class="col-md-2"><strong>Special Sub Total
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="super_sub_total" class="form-control" id="super_invoiceyoghtml"
                                            readonly style="background-color: #E9ECEF">

                                    </td>
                                </tr>
                                <tr class="" style="display: table-row;">
                                    <td colspan="2" align="right"></td>
                                    <td colspan="3" align="right"><strong>Special Item Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="super_commercial_text" class="form-control"
                                            id="super_commercial_text" readonly>

                                    </td>

                                </tr>
                                <tr class="" style="display: table-row;">
                                    <td colspan="2" align="right">
                                    </td>

                                    <td colspan="3" align="right"><strong>Special Overall Discount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2"class="col-md-4"><input type="text"
                                            name="super_discount" class="form-control" id="super_total_discount">

                                    </td>

                                </tr>
                                <tr class="" style="display: table-row;">
                                    <td colspan="2" align="right">
                                    </td>

                                    <td colspan="3" align="right"><strong>Special Total Amount
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="col-md-4"><input type="text"
                                            name="super_total" class="form-control" id="super_total_amount" readonly
                                            style="background-color: #E9ECEF">
                                    </td>

                                </tr>
                            <tbody id="trContainer">
                                <tr class="">
                                    <td colspan="2" align="right"></td>
                                    </td>
                                    <td colspan="3" align="right"><strong>Special Payment
                                            Method</strong></td>
                                    <td align="left" colspan="1" class="col-md-2">
                                        <input type="text" name="super_payment_amount[]"
                                            class="form-control super_payment_amount" id="super_payment_amount">
                                    </td>

                                    <td align="left" colspan="1" class="col-md-2 special_payment_method">
                                        <div class="input-group">
                                            <select name="super_payment_method[]" id="super_payment_method-0"
                                                class="form-control super_payment_method">
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" id="super_addRow" class="btn btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tr class=" " style="display:table-row">
                                <td colspan="2" align="right">
                                </td>

                                <td colspan="3" align="right"><strong>Special Deposit
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="super_deposit" class="form-control" id="super_deposit"
                                        onchange="super_paidFunction()">
                                </td>
                            </tr>
                            <tr class="">
                                <td colspan="2" align="right">
                                <td colspan="3" align="right"><strong>Special Remaining Balance
                                    </strong>
                                </td>
                                <td align="left" colspan="2" class="col-md-4"><input type="text"
                                        name="super_balance" class="form-control" id="super_balance" readonly="">
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </form>
    </div>
    </div>

    <script>
        $(document).ready(function() {

            getAccount(0);
        });
        $('#location').on('change', function() {
            getAccount(0);
        });
        $('#balance_due').on('change', function() {
            getAccount(0);
        });

        function getAccount(payment_count) {
            var locationId = $("#location").val();
            var register_mode = $("#balance_due").val();

            console.log(register_mode);
            $('#payment_method-' + payment_count).html(
                '<option value="">Loading...</option>');
            console.log(register_mode);
            if (locationId) {
                $.ajax({

                    url: "{{ route('get_accounts_transaction') }}",
                    method: 'GET',
                    data: {
                        locationId: locationId,
                        balance_due: register_mode,
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
            $('#special_payment_method-' + payment_count).html(
                '<option value="">Loading...</option>');
            console.log(register_mode);
            if (locationId) {
                $.ajax({

                    url: "{{ route('get_accounts_transaction') }}",
                    method: 'GET',
                    data: {
                        locationId: locationId,
                        balance_due: register_mode,
                    },
                    success: function(data) {
                        console.log(data);
                        $('#super_payment_method-' + payment_count)
                            .empty().append(
                                '<option value="">Select Transaction</option>'
                            );
                        if (data && data.length > 0) {
                            $.each(data, function(index,
                                transaction) {
                                $('#super_payment_method-' +
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
                            $('#super_payment_method-' +
                                payment_count).append(
                                '<option value="">No Transaction available</option>'
                            );
                        }
                    },
                    error: function() {
                        $('#super_payment_method-' + payment_count)
                            .empty().append(
                                '<option value="">Error loading transactions</option>'
                            );
                    }
                });
            } else {
                $('#super_payment_method-' + payment_count).empty().append(
                    '<option value="">Select Transaction</option>');
            }
            $('#location').on('change', function() {
                getAccount(payment_count);
            });
        }


        $(document).ready(function() {
            $('#location').on('change', function() {


                const selectedBranch = $(this).val();
                const doctorSelect = document.getElementById('sale_person');

                doctorSelect.innerHTML = '<option value="">Select Sale Person</option>';

                // Filter and add the doctors based on the selected branch
                @foreach ($sale_persons as $sale)
                    if (selectedBranch === '{{ $sale->location }}') {
                        const option = document.createElement('option');
                        option.value = '{{ $sale->id }}';
                        option.textContent = '{{ $sale->name }}';
                        doctorSelect.appendChild(option);
                    }
                @endforeach
            });
            $("#location").trigger("change");



        });
    </script>


    <script>
        $(document).ready(function() {

            let count = 0;

            let flipFlopButton = document.getElementById('flipFlopButton-0');
            let valueIndicator = document.getElementById('valueIndicator-0');

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
            let super_flipFlopButton = document.getElementById('super_flipFlopButton-0');
            let super_valueIndicator = document.getElementById('super_valueIndicator-0');

            super_flipFlopButton.addEventListener('click', () => {
                // Toggle button text and class based on its current state
                if (super_flipFlopButton.classList.contains('super_Ks')) {
                    super_flipFlopButton.textContent = '%';
                    super_flipFlopButton.classList.remove('super_Ks');
                    super_flipFlopButton.classList.add('super_percent');
                    super_valueIndicator.value = '%'; // Assign value to hidden input
                } else {
                    super_flipFlopButton.textContent = 'Ks';
                    super_flipFlopButton.classList.remove('super_percent');
                    super_flipFlopButton.classList.add('super_Ks');
                    super_valueIndicator.value = 'Ks'; // Assign value to hidden input
                }
            });
            // search item name suggestion (get item name from db)
            function initializeTypeahead(count) {
                $('#productname-' + count).typeahead({
                    source: function(query, process) {

                        var Selectedlocation = $('#location').val();

                        return $.ajax({
                            url: "{{ route('autocomplete-part-code-invoice') }}",
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

            function updateItemName(item_name, row, description, cuz_name, expired_date, item_id) {
                // console.log(row); // Debugging: Check if row is undefined or null

                let warehouse = row.find('.warehouse');
                let itemNameInput = row.find('.price');
                let retail = row.find('.retail_price');
                let buyPrice = row.find('.buy_price');
                let special = row.find('.special_price');
                let partDesc = row.find('.description');
                let exp_date = row.find('.exp_date');
                var Selectedlocation = $('#location').val();
                let unit = row.find('.unit');
                // var selectedCategory = $('#sale_price_category').val();
                var selectedCategory = $(
                    '#sale_price_category').val();


                var newRowElement = $(
                    "#showitem123 tr:last");

                // Now you can find the price cells within the new row using their IDs
                var wholeSalePriceCell = newRowElement.find(
                    '#whole_sale_price_td');
                var retailPriceCell = newRowElement.find(
                    '#retail_price_td');
                var buyPriceCell = newRowElement.find(
                    '#buy_price_td');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('get-part-data-invoice') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        item_id: item_id,
                        result_item_name: item_name,
                        result_descriptions: description,
                        // result_product_code: product_code,
                        result_expired_date: expired_date,
                        location: Selectedlocation,
                    },
                    success: function(data) {


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
                                    item_id: item_id,
                                    // product_code: product_code,
                                    result_expired_date: expired_date,
                                    location: Selectedlocation,
                                },
                                success: function(data) {

                                    itemNameInput.val(data.wholesale);
                                    retail.val(data.retail);
                                    buyPrice.val(data.buyprice);
                                    $('#whole_sale, #retailprice, #buyprice')
                                        .hide();
                                    wholeSalePriceCell.hide();
                                    retailPriceCell.hide();
                                    buyPriceCell.hide();

                                    // Show the relevant header and input based on the selected category
                                    if (selectedCategory == 'Whole Sale') {
                                        $('#whole_sale').show();
                                        wholeSalePriceCell.show();
                                        special.val(data.wholesale);
                                    } else if (selectedCategory == 'Retail') {
                                        $('#retailprice').show();
                                        retailPriceCell.show();
                                        special.val(data.retail);
                                    } else if (selectedCategory ==
                                        'Buy Price') {
                                        $('#buyprice').show();
                                        buyPriceCell.show();
                                    } else {
                                        let cuz_name = $("#type").val();

                                        if (cuz_name == "Whole Sale") {
                                            $('#whole_sale').show();
                                            wholeSalePriceCell.show();
                                            special.val(data.wholesale);
                                        } else if (cuz_name == "Buy Price") {
                                            $('#buyprice').show();
                                            buyPriceCell.show();
                                        } else {
                                            $('#retailprice').show();
                                            retailPriceCell.show();
                                            special.val(data.retail);
                                        }
                                    }


                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });

                        });
                        // itemNameInput.val(data.wholesale_price);
                        // retail.val(data.retail_price);
                        unit.empty();


                        if (data.name2 != null && data.name3 != null) {
                            unitdata = [data.name1, data.name2, data.name3];
                        } else if (data.name2 != null && data.name3 == null) {
                            unitdata = [data.name1, data.name2];
                        } else if (data.name2 == null && data.name3 != null) {
                            unitdata = [data.name1, data.name3];
                        } else {
                            unitdata = [data.name1];
                        }

                        // Assuming data is an array of items
                        // e.g., data = ['item1', 'item2', 'item3']

                        unitdata.forEach(item => {
                            let option = $('<option></option>').val(item).text(item);
                            unit.append(option);
                        });
                        unit.trigger('change');

                        partDesc.val(data.descriptions);
                        exp_date.val(data.expired_date);
                        warehouse.val(data.warehouse_id);
                        var qq = Math.floor(data.quantity / data.unit2);
                        if (parseFloat(data.reorder_level_stock) >= qq) {
                            alert(qq + "  " + data.name1 + "  " + "is available !");
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

            }


            $(document).ready(function() {
                // let count = 0;

                function togglePriceColumns() {
                    const selectedCategory = $('#sale_price_category').val();
                    const wholeSaleColumn = $('.whole_sale');
                    const retailPriceColumn = $('.retailprice');
                    const buyPriceColumn = $('.buyprice');
                    const wholeSaleColumntd = $('.whole_sale_price_td');
                    const retailPriceColumntd = $('.retail_price_td');
                    const buyPriceColumntd = $('.buy_price_td');


                    wholeSaleColumn.hide();
                    retailPriceColumn.hide();
                    buyPriceColumn.hide();
                    wholeSaleColumntd.hide();
                    retailPriceColumntd.hide();
                    buyPriceColumntd.hide();

                    if (selectedCategory == 'Whole Sale') {
                        wholeSaleColumn.show();
                        wholeSaleColumntd.show();
                        $('.buyprice').hide();
                        $('.retailprice').hide();
                        $('.buy_price_td').hide();
                        $('.retail_price_td').hide();
                    } else if (selectedCategory == 'Retail') {
                        retailPriceColumn.show();
                        retailPriceColumntd.show();
                        $('.whole_sale').hide();
                        $('.buyprice').hide();
                        $('.buy_price_td').hide();
                        $('.whole_sale_price_td').hide();

                    } else if (selectedCategory == 'Buy Price') {
                        buyPriceColumn.show();
                        buyPriceColumntd.show();
                        $('.whole_sale').hide();
                        $('.retailprice').hide();
                        $('.whole_sale_price_td').hide();
                        $('.retail_price_td').hide();
                    } else {
                        let cuz_name = $("#type").val();
                        if (cuz_name == "Whole Sale") {
                            $('.whole_sale').show();
                            wholeSaleColumntd.show();
                        } else if (cuz_name == "Buy Price") {
                            $('.buy_price_td').show();
                            buyPriceColumntd.show();
                        } else {
                            $('.retailprice').show();
                            retailPriceColumntd.show();
                        }
                    }
                }

                $('#sale_price_category').on('change', togglePriceColumns);

                togglePriceColumns();



                $("#addproduct").click(function(e) {
                    e.preventDefault();
                    count++;


                    let rowCount = $("#showitem123 tr").length;

                    if ($(".productname").length >= 30) {
                        alert("You can only add up to 30 rows.");
                        return;
                    }


                    let newRow = '<tr>' +
                        '<td class="text-center">' + (rowCount + 1) + '</td>' +
                        '<td>' +
                        '<input type="text" class="form-control productname typeahead item_name" name="part_number[]" id="productname-' +
                        count +
                        '" autocomplete="off" placeholder="Enter Product Number"><input type="hidden" class="form-control result_item_name typeahead result_item_name" name="result_item_name[]" id="result_item_name-' +
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
                        count + '" autocomplete="off">' +
                        '</td>' +
                        '<input type="hidden" class="form-control description typeahead" name="part_description[]" required id="description-' +
                        count + '" autocomplete="off">' +
                        '<td><input type="text" class="form-control product-qty req amnt" name="product_qty[]" id="amount-' +
                        count +
                        '" autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                        '<td><select class="form-control unit " name="item_unit[]" id="unit-' +
                        count +
                        '" autocomplete ="off" required><option selected disabled>Choose Unit</option></select><span class="mt-0" id="uniterror" style="display: none; color: red;">Please Choose A Unit.</span> </td>' +
                        '<td class="whole_sale_price_td"><input type="text" class="form-control price" name="product_price[]" value="0" class="price" id="price-' +
                        count + '" autocomplete="off"></td>' +
                        '<td class="retail_price_td"><input type="text" class="form-control retail_price" name="retail_price[]" value="0" class="retail_price" id="retail_price-' +
                        count + '" autocomplete="off"></td>' +
                        '<td class="buy_price_td"><input type="text" class="form-control buy_price" name="buy_price[]" value="0" class="buy_price" id="buy_price-' +
                        count + '" autocomplete="off"></td>' +
                        ' <td class="special_price_td"><div class="input-group"><input type="text" class="form-control special_price" name="special_price[]" id="special_price-' +
                        count +
                        '" autocomplete="off" value="1" style="width: 90px;"><div class="input-group-append"> <div class="input-group-text"><input type="checkbox" class="form-check-input" name="is_special_price[]" id="is_special_price-' +
                        count + '"> </div></div></div></td>' +
                        '<td><div class="d-flex align-items-between"><input type="text" class="form-control col-md-8 item_discount" name="item_discount[]" value="0" id="item_discount-' +
                        count +
                        '"   autocomplete="off"> <a href="javascript:void(0)" id="flipFlopButton-' +
                        count +
                        '" class="flip-flop-btn form-control Ks col-md-4"style="text-decoration: none; margin-left: 10px;">Ks</a><input type="hidden" id="valueIndicator-' +
                        count + '" name="valueIndicator[]" value="Ks"></div></td>' +
                        '<td><div class="d-flex align-items-between"><div class="input-group"><input type="text" class="form-control col-md-12 super_item_discount" name="super_item_discount[]" value="0" id="super_item_discount-' +
                        count +
                        '"   autocomplete="off"><div class="input-group-append"> <div class="input-group-text"><input type="checkbox" class="form-check-input" name="is_special_discount[]" id="is_special_discount-' +
                        count +
                        '"> </div></div></div> <a href="javascript:void(0)" id="super_flipFlopButton-' +
                        count +
                        '" class="flip-flop-btn form-control super_Ks col-md-4"style="text-decoration: none; margin-left: 10px;">Ks</a><input type="hidden" id="super_valueIndicator-' +
                        count + '" name="super_valueIndicator[]" value="Ks"></div></td>' +
                        '<td class="d-none"><input type="text" class="form-control exp_date " name="exp_date[]" id="exp_date-' +
                        count + '" autocomplete="off"></td>' +
                        '<td style="display : none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                        count + '" autocomplete="off"></td>' +
                        '<td style="text-align:center"><span class="currenty"></span><strong><span id="result-' +
                        count + '">0</span></strong></td>' +
                        '<input type="hidden" name="total_tax[]" id="taxa-' + count +
                        '" value="0">' +
                        '<input type="hidden" name="total_discount[]" id="disca-' + count +
                        '" value="0">' +
                        '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' +
                        count + '" value="0">' +
                        '<input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">' +
                        '<input type="hidden" name="unit_m[]" id="unit_m-0" value="1">' +
                        '<input type="hidden" name="code[]" id="hsn-0" value="">' +
                        '<input type="hidden" name="serial[]" id="serial-0" value="">' +
                        '<td style="width: 3%;table-row"> <button type="submit" class="btn btn-danger remove_item_btn" id = "removebutton" " ><i class="fa-solid fa-times"></i></button> </td>' +

                        '</tr>';
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
                    let super_flipFlopButton = document.getElementById('super_flipFlopButton-' +
                        count);
                    let super_valueIndicator = document.getElementById('super_valueIndicator-' +
                        count);

                    super_flipFlopButton.addEventListener('click', () => {
                        // Toggle button text and class based on its current state
                        if (super_flipFlopButton.classList.contains('super_Ks')) {
                            super_flipFlopButton.textContent = '%';
                            super_flipFlopButton.classList.remove('super_Ks');
                            super_flipFlopButton.classList.add('super_percent');
                            super_valueIndicator.value =
                                '%'; // Assign value to hidden input
                        } else {
                            super_flipFlopButton.textContent = 'Ks';
                            super_flipFlopButton.classList.remove('super_percent');
                            super_flipFlopButton.classList.add('super_Ks');
                            super_valueIndicator.value =
                                'Ks'; // Assign value to hidden input
                        }
                    });
                    initializeTypeahead(count);

                    // Call togglePriceInputs function
                    togglePriceColumns();
                });
            });

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

            //down arrow up arrow
            // $(document).on('change', '.productname', function() {
            // let itemCode = $(this).val();
            //   let row = $(this).closest('tr');
            //   let cuz_name = $("#type").val();
            //   console.log(cuz_name);
            //  updateItemName(itemCode, row, cuz_name);
            // });


            //start edit farroq
            $(document).on('click', '.typeahead .dropdown-item', function(e) {
                e.preventDefault();

                if ($("#customer").val()) {} else {
                    const row = $(this).closest('tr');

                    const item_name = row.find('.result_item_name').val();
                    const item_id = row.find('.result_id').val();
                    const description = row.find('.result_descriptions').val();
                    const product_code = row.find('.result_product_code').val();
                    const expired_date = row.find('.result_expired_date').val();
                    let cuz_name = $("#type").val();
                    updateItemName(item_name, row, description, cuz_name,
                        expired_date, item_id);
                    $('#productname').val('');
                }
            });
            //     $(document).ready(function() {
            //         function calculatePayment() {
            //             let total = 0;
            //             $('.payment_amount').each(function() {
            //                 let value = parseFloat($(this).val()) || 0;
            //                 total += value;
            //             });
            //             $('#deposit').val(total.toFixed(2));
            //             paidFunction();
            //         }

            //         function paidFunction() {
            //             let paid = parseFloat($('#deposit').val()) || 0;
            //             let total_p = parseFloat($('#total_amount').val()) || 0;
            //             let balance = total_p - paid;
            //             $('#balance').val(balance.toFixed(2));
            //         }


            //         $(document).on('input', '.payment_amount', function() {
            //             calculatePayment();
            //         });

            //         $('#deposit').on('input', function() {
            //             paidFunction();
            //         });

            //         // Function to add a new row
            //         $('#addRow').click(function() {
            //             if ($('#trContainer tr.sub_c').length < 4) {
            //                 var newRow = `<tr class="sub_c">
        //         <td colspan="2"></td>
        //         <td colspan="3" align="right"><strong></strong></td>
        //         <td align="left" colspan="1" class="col-md-2">
        //             <input type="text" name="payment_amount[]" class="form-control payment_amount">
        //         </td>
        //         <td align="left" colspan="1" class="col-md-2">


        //               <div class="input-group">
        //     <select name="payment_method[]" class="form-control" required>
        //         <option value="Cash">Cash</option>
        //         <option value="K Pay">K Pay</option>
        //         <option value="Wave">Wave</option>
        //         <option value="Others">Others</option>
        //     </select>
        //     <div class="input-group-append">
        //         <button type="button" class="removeRow btn btn-danger">
        //             <i class="fa-solid fa-minus"></i>
        //         </button>
        //     </div>
        // </div>
        //         </td>

        //     </tr>`;

            //                 $('#trContainer').append(newRow);
            //             } else {
            //                 alert('You can only add a maximum of 4 payment rows.');
            //             }
            //         });
            //         $(document).on('click', '.removeRow', function() {
            //             $(this).closest('tr').remove();
            //             calculatePayment();
            //         });

            //         calculatePayment();
            //     });
            $(document).ready(function() {
                function calculatePayment() {
                    let total = 0;
                    $('.payment_amount').each(function() {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#deposit').val(total);
                    paidFunction();
                }

                function paidFunction() {
                    let paid = parseFloat($('#deposit').val()) || 0;
                    let total_p = parseFloat($('#total_amount').val()) || 0;
                    let balance = total_p - paid;
                    $('#balance').val(balance);
                }


                $(document).on('input', '.payment_amount', function() {
                    calculatePayment();
                });

                $('#deposit').on('input', function() {
                    paidFunction();
                });

                let payment_count = 1;
                // Function to add a new row
                $('#addRow').click(function() {
                    console.log(payment_count);
                    // if ($('#trContainer tr.sub_c').length < 4) {
                    var newRow = `<tr class="sub_c">
                                        <td colspan="2"></td>
                                        <td colspan="3" align="right"><strong></strong></td>
                                        <td align="left" colspan="1" class="col-md-2">
                                            <input type="text" name="payment_amount[]" class="form-control payment_amount">
                                        </td>
                                        <td align="left" colspan="1" class="col-md-2">
                                        <div class="input-group">
                                            <select name="payment_method[]" id="payment_method-${payment_count}" class="form-control payment_method" required>
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
                    calculatePayment();
                });

                calculatePayment();
            });

            $(document).ready(function() {
                function calculatePayment() {
                    let total = 0;
                    $('.super_payment_amount').each(function() {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#super_deposit').val(total);
                    super_paidFunction();
                }

                function super_paidFunction() {
                    let paid = parseFloat($('#super_deposit').val()) || 0;
                    let total_p = parseFloat($('#super_total_amount').val()) || 0;
                    let balance = total_p - paid;
                    $('#super_balance').val(balance);
                }


                $(document).on('input', '.super_payment_amount', function() {
                    calculatePayment();
                });

                $('#super_deposit').on('input', function() {
                    super_paidFunction();
                });

                let payment_count = 1;
                // Function to add a new row
                $('#super_addRow').click(function() {
                    console.log(payment_count);
                    // if ($('#trContainer tr.sub_c').length < 4) {
                    var newRow = `<tr class="sub_c">

                                        <td colspan="2" align="right"><strong></strong></td>
                                        <td align="left" colspan="1" class="col-md-2">
                                            <input type="text" name="super_payment_amount[]" class="form-control super_payment_amount">
                                        </td>
                                        <td align="left" colspan="1" class="col-md-2">
                                        <div class="input-group">
                                            <select name="super_payment_method[]" id="super_payment_method-${payment_count}" class="form-control super_payment_method" required>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="super_removeRow btn btn-danger">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>`;
                    $('#trContainer tr.sub_c:first').after(newRow);

                    getAccount(payment_count)
                    payment_count++;
                    // } else {
                    //     alert('You can only add a maximum of 4 payment rows.');
                    // }
                });

                $(document).on('click', '.super_removeRow', function() {
                    $(this).closest('tr').remove();
                    calculatePayment();
                });

                calculatePayment();
            });


            $(document).on('click', '.typeahead .dropdown-item', function(e) {
                e.preventDefault();
                if ($("#customer").val()) {

                } else {
                    const itemCode = $(this).text().trim();
                    const row = $(this).closest('tr');
                    let cuz_name = $("#type").val();
                    updateItemName(itemCode, row, cuz_name);
                    $('#productname').val('');
                }
            });
         $('.item_discount, .product-qty, .retail_price,.buy_price, .price,.special_price, .special_discount').on('change', function() {
                $('#calculate').click();

            });


                $('#myForm').on('change input', 'input, select', function () {
                    $('#calculate').trigger('click');
                });

                $('#calculate').on('click', function () {
                    console.log('Calculate clicked');
                    // your calculation logic here
                });


            //end farroq
            // Initialize typeahead for the first row
            initializeTypeahead(count);
            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                let totalTax = 0;
                let total_discount = 0;
                let salePriceCategory = $('#sale_price_category').val();

                for (let i = 0; i < (count + 1); i++) {
                    // Parse quantity as a float, fallback to 0 if empty or invalid
                    var qty = parseFloat($('#amount-' + i).val()) || 0;

                    // Parse the price based on the salePriceCategory, ensure it's a float
                    let price = 0;
                    if (salePriceCategory === 'Default') {
                        let cuz_name = $("#type").val();

                        price = parseFloat($('#retail_price-' + i).val()) || 0;

                    } else if (salePriceCategory === 'Whole Sale') {

                        price = parseFloat($('#price-' + i).val()) || 0;

                    } else if (salePriceCategory === 'Retail') {


                        price = parseFloat($('#retail_price-' + i).val()) || 0;

                    } else if (salePriceCategory === 'Buy Price') {
                        price = parseFloat($('#buy_price-' + i).val()) || 0;
                    }

                    // Parse discount as a float
                    let discount = 0;
                    let taxRate = parseFloat($('#item_discount-' + i).val()) || 0;
                    let indicator = $('#valueIndicator-' + i).val();

                    // Apply discount logic
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

                    // Update totals with float calculations
                    total += price * qty;
                    total_discount += discount;
                    totalTax += discount;
                }


                let taxt = total * 0.05; // Calculate tax based on the updated total
                taxt = Math.ceil(taxt); // You can apply Math.ceil() here if you want to round up the tax


                let total_total = total - totalTax;
                $("#invoiceyoghtml").val(total);
                $("#commercial_text").val(totalTax);
                $("#total_amount").val(total);
                let super_total = 0;
                let super_totalTax = 0;
                let super_total_discount = 0;
                for (let i = 0; i < (count + 1); i++) {
                    var qty = parseFloat($('#amount-' + i).val()) || 0;
                    let price = 0;

                    price = parseFloat($('#special_price-' + i).val()) || 0;



                    let discount = 0;
                    let taxRate = parseFloat($('#super_item_discount-' + i).val()) || 0;
                    let indicator = $('#super_valueIndicator-' + i).val();


                    if (!isNaN(taxRate) && taxRate > 0) {
                        if (indicator == "Ks") {
                            discount = taxRate;
                            // $("#result-" + i).text((price * qty) - discount);
                        } else {
                            discount = (price * qty * taxRate) / 100;
                            // $("#result-" + i).text((price * qty) - discount);
                        }
                    } else {
                        // $("#result-" + i).text(price * qty);
                    }
                    super_total += price * qty;
                    super_total_discount += discount;
                    super_totalTax += discount;
                }
                let super_tax = super_total * 0.05; // Calculate tax based on the updated total
                super_tax = Math.ceil(
                    super_tax); // You can apply Math.ceil() here if you want to round up the tax

                let super_total_total = super_total - super_totalTax;
                $("#super_invoiceyoghtml").val(super_total);
                $("#super_commercial_text").val(super_totalTax);
                $("#super_total_amount").val(super_total);
                // Call calculateTotal() after the calculation is done
                calculateTotal();
            });



            function paidFunction() {
                let paid = document.getElementById("deposit").value;
                let total_p = document.getElementById("total_amount").value;
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







        function paidFunction() {

            let paid = document.getElementById("deposit").value;
            let total_p = document.getElementById("total_amount").value;
            let balance = total_p - paid;
            $("#balance").val(balance); //update balance
        }

        function super_paidFunction() {

            let paid = document.getElementById("super_deposit").value;
            let total_p = document.getElementById("super_total_amount").value;
            let balance = total_p - paid;
            $("#super_balance").val(balance); //update balance
        }
    </script>

    <script>
        var path = "{{ route('customer_service_search') }}";
        var customerMap = {};
        $('#patient_name').typeahead({

            source: function(query, process) {
                var Selectedlocation = $('#location').val();
                return $.get(path, {
                    query: query,
                    location: Selectedlocation,
                }, function(data) {
                    let suggestions = data.map(function(customer) {
                        let label = customer.name + ' (' + customer.phno + ')';
                        customerMap[label] = customer.id; // Save id by label
                        return label;
                    });
                    return process(suggestions);
                });
            },
            afterSelect: function(item) {

                let selectedCustomerId = customerMap[item];
                $('#customer_id').val(selectedCustomerId);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('customer_service_fill') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        result: selectedCustomerId
                    },
                    success: function(data) {
                        if (data.customer) {
                            $("#customer_id").val(data['customer']['id']);
                            $("#patient_name").val(data['customer']['name']);
                            $("#phone_no").val(data['customer']['phno']);
                            $("#address").val(data['customer']['address']);
                            $("#dob").val(data['customer']['dob']);
                            $("#dob").trigger("change");
                        } else {
                            console.error("Customer not found");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        // $('#patient_name').typeahead({

        //     source: function(query, process) {
        //         var Selectedlocation = $('#location').val();
        //         // console.log(path);
        //         // console.log(Selectedlocation);
        //         return $.get(path, {
        //             query: query,
        //             location: Selectedlocation,
        //         }, function(data) {
        //             // Format the data for Typeahead
        //             // console.log(data);
        //             var formattedData = [];
        //             var seenNames = new Set();
        //             var seenPhones =
        //                 new Set();

        //             if ($('input[name="treatment_and_other"]:checked').val() ==
        //                 'other') {
        //                 $.each(data, function(index, customer) {
        //                     if (customer.name.toLowerCase().indexOf(query
        //                             .toLowerCase()) !== -1) {
        //                         formattedData.push(customer.name);
        //                     } else if (customer.phno.indexOf(query) !== -1) {
        //                         formattedData.push(customer.phno);
        //                     }
        //                 });
        //             } else {
        //                 $.each(data, function(index, customer) {
        //                     if (!seenNames.has(customer.name)) {
        //                         formattedData.push(customer.name);
        //                         seenNames.add(customer.name);
        //                     }
        //                     if (!seenPhones.has(customer.phno)) {
        //                         formattedData.push(customer.phno);
        //                         seenPhones.add(customer.phno);
        //                         // console.log('Phone:', customer.phno);
        //                     }
        //                 });
        //             }
        //             return process(formattedData);
        //             ''
        //         });
        //     },
        //     afterSelect: function(item) {
        //         // Set the customer ID in the hidden input

        //     let serialNumber = $("#customer").val();
        //     var searchFillRoute = "{{ route('customer_service_search_fill') }}";
        //     $.ajax({
        //         type: 'POST',
        //         url: searchFillRoute,
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             model: serialNumber,
        //             location: $('#location').val()
        //             // Adjusted to match server-side parameter name
        //         },
        //         success: function(data) {
        //             // console.log(data);

        //             $("#customer_id").val(data['customer']['id']);
        //             $("#patient_name").val(data['customer']['name']);
        //             $("#patient_id").val(data['customer']['patient_id']);
        //             $("#customer_id").val(data['customer']['id']);
        //             $("#phone_no").val(data['customer']['phno']);

        //             $("#address").val(data['customer']['address']);
        //             $("#age").val(data['customer']['age'] ?? data['customer']['customer_age']);
        //             $("#service_type").val(data['customer']['department']);

        //             $("#patient_type").val(data['customer']['inout_patient']);
        //             $("#cdc_no").val(data['customer']['cdc_no']);
        //             $("#company").val(data['customer']['company']);
        //             $("#dob").val(data['customer']['dob']);
        //             $("#nrc").val(data['customer']['nrc']);
        //             $("#customer_deposit").val(data['customer']['deposit'] ?? data['customer'][
        //                 'customer_deposit'
        //             ]);
        //             $("#doctor_id").val(data.customer.doctor_id);
        //             if (data.customer.treatment) {
        //                 $("#treatment_remark").val(data.customer.treatment);
        //                 $("#treatment_id").val(data.customer.id);



        //             }


        //             $("#customer").val('');
        //             // Adjusted to match server-side data
        //         },
        //         error: function(xhr, status, error) {
        //             console.error(xhr.responseText);
        //         }
        //     });
        //     }
        // });

        // $(document).on('click', '#customer_search', function(e) {
        //     e.preventDefault();
        //     let serialNumber = $("#customer").val();
        //     var searchFillRoute = "{{ route('customer_service_search_fill') }}";
        //     $.ajax({
        //         type: 'POST',
        //         url: searchFillRoute,
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             model: serialNumber,
        //             location: $('#location').val()
        //             // Adjusted to match server-side parameter name
        //         },
        //         success: function(data) {
        //             // console.log(data);

        //             $("#customer_id").val(data['customer']['id']);
        //             $("#patient_name").val(data['customer']['name']);
        //             $("#patient_id").val(data['customer']['patient_id']);
        //             $("#customer_id").val(data['customer']['id']);
        //             $("#phone_no").val(data['customer']['phno']);

        //             $("#address").val(data['customer']['address']);
        //             $("#age").val(data['customer']['age'] ?? data['customer']['customer_age']);
        //             $("#service_type").val(data['customer']['department']);

        //             $("#patient_type").val(data['customer']['inout_patient']);
        //             $("#cdc_no").val(data['customer']['cdc_no']);
        //             $("#company").val(data['customer']['company']);
        //             $("#dob").val(data['customer']['dob']);
        //             $("#nrc").val(data['customer']['nrc']);
        //             $("#customer_deposit").val(data['customer']['deposit'] ?? data['customer'][
        //                 'customer_deposit'
        //             ]);
        //             $("#doctor_id").val(data.customer.doctor_id);
        //             if (data.customer.treatment) {
        //                 $("#treatment_remark").val(data.customer.treatment);
        //                 $("#treatment_id").val(data.customer.id);



        //             }


        //             $("#customer").val('');
        //             // Adjusted to match server-side data
        //         },
        //         error: function(xhr, status, error) {
        //             console.error(xhr.responseText);
        //         }
        //     });
        // });
    </script>


    <script>
        $("input[type='date']").on("change", function() {
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
    </script>

    <script>
        $(document).ready(function() {
            function togglePriceInputs() {
                var selectedCategory = $('#sale_price_category').val();
                var cuz_name = $("#type").val();
                // console.log(cuz_name);
                // Hide all headers and inputs initially
                $('#whole_sale, #retailprice, #buyprice').hide();
                $('#whole_sale_price_td, #retail_price_td, #buy_price_td').hide();

                // Show the relevant header and input based on the selected category
                if (selectedCategory == 'Whole Sale') {
                    $('#whole_sale').show();
                    $('#whole_sale_price_td').show();
                } else if (selectedCategory == 'Retail') {
                    $('#retailprice').show();
                    $('#retail_price_td').show();
                } else if (selectedCategory == 'Buy Price') {
                    $('#buyprice').show();
                    $('#buy_price_td').show();
                } else if (selectedCategory == 'Default') {
                    // console.log(cuz_name);
                    if (cuz_name == "Whole Sale") {
                        $('#whole_sale').show();
                        $('#whole_sale_price_td').show();
                    } else if (cuz_name == "Buy Price") {
                        $('#buyprice').show();
                        $('#buy_price_td').show();
                    } else {
                        $('#retailprice').show();
                        $('#retail_price_td').show();
                    }
                }
            }

            $('#sale_price_category').on('change', togglePriceInputs);

            // Initial call to set the correct state on page load
            togglePriceInputs();
        });
    </script>
    <script>
        function calculateTotal() {
            // Parse the input values as floats, fallback to 0 if invalid
            let subtotal = parseFloat($("#invoiceyoghtml").val()) || 0;
            let totalVAT = parseFloat($("#commercial_text").val()) || 0;
            let totalDiscount = parseFloat($("#total_discount").val()) || 0;
            let super_subtotal = parseFloat($("#super_invoiceyoghtml").val()) || 0;
            let super_totalVAT = parseFloat($("#super_commercial_text").val()) || 0;
            let super_totalDiscount = parseFloat($("#super_total_discount").val()) || 0;
            // Calculate the total amount after discount and VAT
            let total = subtotal - totalDiscount - totalVAT;
            let super_total = super_subtotal - super_totalDiscount - super_totalVAT;

            // Update the total fields with the result
            $("#total_amount").val(total);
            $("#super_total_amount").val(super_total);
            // $("#deposit").val(total);
            paidFunction();
            super_paidFunction();
        }


        $(document).on("input", "#total_discount", function() {
            calculateTotal();

        });
        $(document).on("input", "#super_total_discount", function() {
            calculateTotal();

        });



        $(document).ready(function() {
            $('#invoice_no, #invoice_no_latest').on('input', function() {
                let poNo = parseInt($('#invoice_no').val().replace("Invoice - ", ""));
                let poNoLatest = parseInt($('#invoice_no_latest').val().replace("Invoice - ", ""));

                if (!isNaN(poNo) && !isNaN(poNoLatest)) {
                    if (poNo < poNoLatest) {
                        $('#invoice_no').val("Invoice - " + poNoLatest);
                        alert("The Invoice number must be greater than the latest Invoice number.");
                    }
                }
            });
        });
    </script>

    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</HTML>
